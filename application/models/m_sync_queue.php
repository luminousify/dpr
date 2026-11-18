<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Thin model for the Ticket 2 async write queue table.
 *
 * One job: enqueue a validated payload. The controller
 * (application/controllers/api/Sync.php) builds the row via the pure
 * Sync_payload_helper, then calls enqueue() here.
 *
 * Returns a status string so the controller can distinguish the
 * expected "duplicate id_production" case (PWA retry) from real failures
 * without an extra SELECT round-trip:
 *   - 'inserted'  : row created, status='pending'
 *   - 'duplicate' : id_production already queued (UNIQUE KEY uk_id_production)
 *                   -> submit() treats this as success (idempotent)
 *   - 'failed'    : any other DB error -> submit() returns 500
 *
 * The drain worker (Ticket 3) consumes the queue via fetch_pending() +
 * try_claim() + update_status() below.
 */
class m_sync_queue extends CI_Model
{
    const TABLE = 't_sync_queue';

    /** MySQL error code for a UNIQUE constraint violation. */
    const ER_DUP_ENTRY = 1062;

    /** Allowed final-status values (mirrors the t_sync_queue.status ENUM). */
    private static $FINAL_STATUSES = array('done', 'error', 'duplicate_review');

    /**
     * Insert a single queue row.
     *
     * @param array $row Row shaped by Sync_payload_helper::build_queue_row().
     * @return string One of 'inserted' | 'duplicate' | 'failed'.
     */
    public function enqueue($row)
    {
        // CI3's db_debug=TRUE (the dev default) renders a Database Error
        // page and exits on the very duplicate-key we want to handle, so we
        // would never reach our error-code check. Toggle db_debug off for
        // this single statement to make the duplicate-key path reachable in
        // BOTH dev and prod. In production db_debug is already FALSE, so the
        // toggle is a harmless no-op there. Always restore the saved value.
        $saved_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $ok = $this->db->insert(self::TABLE, $row);
        $this->db->db_debug = $saved_debug;

        if ($ok) {
            return 'inserted';
        }

        $error = $this->db->error();
        if (isset($error['code']) && (int)$error['code'] === self::ER_DUP_ENTRY) {
            return 'duplicate';
        }

        log_message('error', 'm_sync_queue::enqueue failed: '
            . (isset($error['message']) ? $error['message'] : '(no message)')
            . ' [code ' . (isset($error['code']) ? $error['code'] : '?') . ']');
        return 'failed';
    }

    // --------------------------------------------------------------------
    // Drain-side methods (Ticket 3 / c_sync::queue_drain consumer)
    // --------------------------------------------------------------------

    /**
     * Select queue rows currently flagged for supervisor review
     * (Ticket 8 / Phase 4.3), newest-first so the freshest duplicate
     * appears at the top of the admin list.
     *
     * Returns the full row (id, id_production, payload, status,
     * error_message, created_at, processed_at) - the admin view needs the
     * payload to display tanggal/mesin/shift/id_bom/operator/qty_ok +
     * the JSON for context, and the timestamps so the supervisor can see
     * how long a row has been waiting.
     *
     * @param int $limit  Max rows to return (1..500, clamped).
     * @return array      List of associative row arrays.
     */
    public function fetch_duplicate_review($limit = 500)
    {
        $limit = max(1, min(500, (int) $limit));

        return $this->db->select('id, id_production, payload, status, error_message, created_at, processed_at')
            ->from(self::TABLE)
            ->where('status', 'duplicate_review')
            ->order_by('id', 'desc')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    /**
     * Fetch a single queue row by id.
     *
     * Used by the Ticket 8 approve/reject actions to look up the row the
     * supervisor clicked on. Returns null when the row is missing (e.g.
     * another supervisor already actioned it).
     *
     * @param int $id  Queue row primary key.
     * @return array|null
     */
    public function get_by_id($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return null;
        }
        $row = $this->db->select('id, id_production, payload, status, error_message, created_at, processed_at')
            ->from(self::TABLE)
            ->where('id', $id)
            ->limit(1)
            ->get()
            ->row_array();
        return $row ? $row : null;
    }

    /**
     * Delete a single queue row by primary key (Ticket 8 reject action).
     *
     * Used when a supervisor rejects a flagged duplicate: SPEC 4.3 says
     * "reject (delete queue entry)". The rejected payload's id_production
     * is never written to t_production_op, so deleting the queue row is
     * safe - there are no child-table orphans to clean up.
     *
     * @param int $id  Queue row primary key.
     * @return bool    True if a row was deleted, false otherwise.
     */
    public function delete_by_id($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }
        $saved_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $this->db->where('id', $id);
        $this->db->delete(self::TABLE);
        $affected = $this->db->affected_rows();
        $this->db->db_debug = $saved_debug;
        return $affected > 0;
    }

    /**
     * Select up to $limit pending queue rows, oldest first.
     *
     * Returns the raw row arrays WITHOUT claiming them; the controller must
     * call try_claim() on each row before processing it to win the per-row
     * lock atomically (defends against two concurrent drain invocations,
     * e.g. Task Scheduler firing while a manual trigger is still running).
     *
     * Only the columns the drain loop needs are selected (payload is JSON,
     * potentially large; we never need status back since try_claim already
     * told us we won it).
     *
     * @param int $limit  Max rows to return (1..200, clamped).
     * @return array      List of associative row arrays.
     */
    public function fetch_pending($limit = 50)
    {
        $limit = max(1, min(200, (int) $limit));

        return $this->db->select('id, id_production, payload, created_at')
            ->from(self::TABLE)
            ->where('status', 'pending')
            ->order_by('created_at', 'asc')
            ->order_by('id', 'asc')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    /**
     * Atomically flip a single row from 'pending' to 'processing'.
     *
     * Returns true if THIS caller won the claim (row was pending, now
     * processing). Returns false if another caller already claimed it, or
     * if the row was no longer pending (already done / errored / etc).
     *
     * Implementation note: the WHERE clause (id=? AND status='pending') is
     * what makes this atomic - MySQL serializes concurrent UPDATEs on the
     * same row, so the first caller's UPDATE commits (status flips,
     * affected_rows=1) before the second caller's WHERE is evaluated
     * (status no longer 'pending', affected_rows=0).
     *
     * db_debug is toggled off for the same reason as enqueue(): the dev
     * default would render an error page on any query error and prevent
     * us from inspecting affected_rows().
     *
     * @param int $id  Queue row primary key.
     * @return bool
     */
    public function try_claim($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }

        $saved_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $this->db->where('id', $id);
        $this->db->where('status', 'pending');
        $ok = $this->db->update(self::TABLE, array('status' => 'processing'));
        $affected = $this->db->affected_rows();
        $this->db->db_debug = $saved_debug;

        return $ok && $affected > 0;
    }

    /**
     * Set a row's final status (+ optional error_message) and stamp
     * processed_at.
     *
     * Only the three final statuses are accepted; anything else is logged
     * and silently treated as 'error' so a coding mistake can't strand a
     * row in 'processing' forever or write a value the ENUM rejects.
     *
     * @param int         $id             Queue row primary key.
     * @param string      $status         One of 'done'|'error'|'duplicate_review'.
     * @param string|null $error_message  Set when $status='error'; ignored otherwise.
     * @return void
     */
    public function update_status($id, $status, $error_message = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return;
        }

        if (!in_array($status, self::$FINAL_STATUSES, true)) {
            log_message('error', 'm_sync_queue::update_status refused bad status "'
                . $status . '" for queue id=' . $id);
            $status = 'error';
            $error_message = 'Internal drain error: bad status value';
        }

        $data = array(
            'status'       => $status,
            'processed_at' => date('Y-m-d H:i:s'),
        );
        if ($status === 'error' && $error_message !== null) {
            // Truncate to TEXT column safety (TEXT max is 65535 bytes).
            $data['error_message'] = self::truncate_to_bytes((string) $error_message, 65000);
        }

        $saved_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $this->db->where('id', $id);
        $this->db->update(self::TABLE, $data);
        $this->db->db_debug = $saved_debug;
    }

    /**
     * Truncate a string to at most $max_bytes UTF-8 bytes without splitting
     * a multi-byte sequence. Used for error_message (TEXT column).
     *
     * @param string $s
     * @param int    $max_bytes
     * @return string
     */
    private static function truncate_to_bytes($s, $max_bytes)
    {
        if (strlen($s) <= $max_bytes) {
            return $s;
        }
        // Step back to a UTF-8 boundary (a byte not matching 0b10xxxxxx,
        // i.e. a leading byte or ASCII).
        $cut = $max_bytes;
        while ($cut > 0 && (ord($s[$cut]) & 0xC0) === 0x80) {
            $cut--;
        }
        return substr($s, 0, $cut);
    }
}
