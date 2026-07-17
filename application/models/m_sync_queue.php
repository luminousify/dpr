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
 * The drain worker (Ticket 3) will get its own methods here (fetch_pending,
 * mark_done, etc.); they are intentionally out of scope for this ticket.
 */
class m_sync_queue extends CI_Model
{
    const TABLE = 't_sync_queue';

    /** MySQL error code for a UNIQUE constraint violation. */
    const ER_DUP_ENTRY = 1062;

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
}
