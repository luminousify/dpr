<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ticket 3 / Phase 1.3: queue drain worker.
 *
 * Drains t_sync_queue rows (enqueued by the Ticket 2 fast-accept endpoint,
 * api/Sync::submit, and posted by the PWA's Ticket 6 sync engine) into the
 * real production tables: t_production_op + t_production_op_dl +
 * t_production_op_release + t_production_op_cutting_tools_usage.
 *
 * Triggered externally by Windows Task Scheduler hitting
 *   http://localhost/dpr/c_sync/queue_drain
 * every 1 minute (see SPEC_PWA_QUEUE.md section 1.4). Mirrors the existing
 * c_scheduler.php pattern (CI controller triggered externally) but adds a
 * minimal local-only guard so the route cannot be driven by random clients.
 *
 * ---------------------------------------------------------------------------
 * OPS: Windows Task Scheduler registration (run once on the production
 * server from an elevated shell - creates a task that fires every minute
 * during the input window; mirror this for dev/test as needed):
 *
 *   schtasks /Create /SC MINUTE /MO 1 /TN "DPR_SyncQueueDrain" ^
 *     /TR "curl.exe -s http://localhost/dpr/c_sync/queue_drain >nul 2>&1" ^
 *     /RU SYSTEM /F
 *
 * To remove for rollback:  schtasks /Delete /TN "DPR_SyncQueueDrain" /F
 * To check status:         schtasks /Query  /TN "DPR_SyncQueueDrain" /V /FO LIST
 * (Use /RU "<SERVER>\<user>" + /RP "<password>" instead of /RU SYSTEM if
 *  the curl invocation needs a logged-in user context.)
 * ---------------------------------------------------------------------------
 *
 * Idempotency (two layers, both required - see CONTEXT.md Decision 6):
 *   1. Per-row atomic claim (m_sync_queue::try_claim): two concurrent drain
 *      invocations cannot process the same queue row. The loser sees
 *      affected_rows=0 and skips.
 *   2. Pre-insert existence check (m_operator::id_production_exists): if
 *      the same id_production is already in t_production_op (inserted by
 *      the legacy form or a prior drain), the row is flagged
 *      'duplicate_review' rather than re-inserted.
 *
 * Duplicate gate (CONTEXT.md Decision 5): tanggal + mesin + shift + id_bom.
 * Run per drained row BEFORE insert. On match -> 'duplicate_review'. The
 * payload's confirm_duplicate flag (operator acknowledged the dup at save
 * time) overrides the check, mirroring m_operator::add().
 *
 * Error isolation: one malformed payload in a batch of 50 must not abort
 * the whole batch. Each row's processing is wrapped so failures land as
 * status='error' + error_message and the loop continues.
 *
 * Parallel deployment (CONTEXT.md Decision 8): this controller does NOT
 * touch c_operator/add or m_operator/add (the legacy write path). Both
 * paths drain into the same t_production_op. The insert_batch logic is
 * reused from the existing Dpr_batch_builder library (Ticket 1 refactor).
 *
 * URL: GET /dpr/c_sync/queue_drain
 *
 * Responses:
 *   200 {"status":"ok","processed":N,"summary":{...}} - batch drained
 *   403 {"status":"error","error":"Forbidden"}          - non-local caller
 */
class c_sync extends CI_Controller
{
    /** How many pending rows to claim per invocation (SPEC 1.3: LIMIT 50). */
    const CLAIM_LIMIT = 50;

    /** Per-invocation time budget; 50 rows * batch inserts is well under this. */
    const TIME_LIMIT_SECONDS = 300;

    /**
     * Load the models + libraries the drain loop needs. Libraries are pure
     * (no CI3 deps) and are loaded here purely so CI3 makes them available
     * as $this->sync_drain_helper / $this->dpr_batch_builder; we still call
     * their methods statically to match the rest of the codebase.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_sync_queue');
        $this->load->model('m_operator', 'op');
        $this->load->library('dpr_batch_builder');
        $this->load->library('sync_drain_helper');
    }

    /**
     * Drain up to CLAIM_LIMIT pending queue rows into the real production
     * tables.
     *
     * Flow per row: claim (atomic pending->processing) -> decode payload ->
     * normalize -> id_production existence check -> duplicate gate ->
     * insert_batch inside a transaction -> set final status.
     *
     * Emits a JSON summary so the Task Scheduler log (and any manual curl)
     * can see what happened. Never throws - all errors land as
     * status='error' on the offending row and the loop continues.
     *
     * @return void Emits a JSON response; does not return.
     */
    public function queue_drain()
    {
        header('Content-Type: application/json');

        // Guard: CLI (manual `php index.php c_sync queue_drain`) OR localhost.
        // Task Scheduler hits via curl on http://localhost/... so its
        // REMOTE_ADDR is 127.0.0.1 / ::1. c_scheduler.php has no guard at
        // all; we add this minimal one as defense-in-depth so the route
        // can't be driven by random external clients.
        if (!$this->is_locally_invoked()) {
            $this->send_error_json(403, 'Forbidden');
            return;
        }

        set_time_limit(self::TIME_LIMIT_SECONDS);

        $rows = $this->m_sync_queue->fetch_pending(self::CLAIM_LIMIT);

        // Counters keyed to match the keys we bump in process_one()'s return.
        $summary = array(
            'claimed'   => 0,
            'done'      => 0,
            'duplicate' => 0,
            'error'     => 0,
            'skipped'   => 0, // lost the claim to a concurrent drain
        );

        foreach ($rows as $row) {
            // Per-row atomic claim. If we lose, another drain invocation
            // already took this row - skip it (idempotency layer 1).
            if (!$this->m_sync_queue->try_claim((int) $row['id'])) {
                $summary['skipped']++;
                continue;
            }
            $summary['claimed']++;

            $outcome = $this->process_one($row);
            $summary[$outcome['counter_key']]++;

            $this->m_sync_queue->update_status(
                (int) $row['id'],
                $outcome['status'],
                isset($outcome['error']) ? $outcome['error'] : null
            );
        }

        http_response_code(200);
        echo json_encode(array(
            'status'    => 'ok',
            'processed' => $summary['claimed'],
            'summary'   => $summary,
        ));
    }

    /**
     * Process one claimed queue row.
     *
     * Returns an outcome array:
     *   array(
     *     'status'       => 'done' | 'duplicate_review' | 'error',
     *     'counter_key'  => 'done' | 'duplicate' | 'error',  // summary bucket
     *     'error'        => string (optional, only when status='error'),
     *   )
     *
     * Never throws - all caught exceptions + failed transactions land as
     * the 'error' outcome so the caller can mark the row and continue.
     *
     * @param array $row  Queue row (id, id_production, payload, created_at).
     * @return array
     */
    private function process_one(array $row)
    {
        // 1) Decode payload.
        $decoded = json_decode($row['payload'], true);
        if (!is_array($decoded)) {
            return $this->error_outcome('Malformed payload JSON');
        }

        // 2) Normalize into the shape Dpr_batch_builder consumes.
        $norm = Sync_drain_helper::normalize_payload($decoded);
        if (!$norm['ok']) {
            return $this->error_outcome('Invalid payload: ' . $norm['error']);
        }
        $id_production = $norm['id_production'];

        // 3) Idempotency layer 2: if id_production already lives in
        //    t_production_op (a prior drain inserted it then crashed before
        //    updating the queue row, or a manual reset requeued it), the row
        //    is already in its desired end state. Mark 'done' (idempotent
        //    no-op) - do NOT re-insert and do NOT flag 'duplicate_review'.
        //    CONTEXT.md Decision 5 reserves 'duplicate_review' strictly for
        //    tanggal+mesin+shift+id_bom matches against a DIFFERENT
        //    id_production (checked below); conflating the two would seed
        //    the Ticket 8 supervisor queue with false positives.
        if ($this->op->id_production_exists($id_production)) {
            return array(
                'status'      => 'done',
                'counter_key' => 'done',
            );
        }

        // 4) Duplicate gate (CONTEXT.md Decision 5). The PWA form only ever
        //    produces a single user[0], so we check that one row; the
        //    legacy m_operator::add() checks all user rows, but the drain
        //    only consumes PWA payloads. confirm_duplicate overrides (the
        //    operator acknowledged the dup at save time).
        $is_duplicate = $this->is_duplicate_by_dpr_key($norm);
        if ($is_duplicate) {
            return array(
                'status'      => Sync_drain_helper::decide_status(true, $norm['confirm_duplicate']),
                'counter_key' => $norm['confirm_duplicate'] ? 'done' : 'duplicate',
            );
        }

        // 5) Insert. Wrap in a transaction so a partial failure leaves no
        //    orphans in the child tables. db_debug toggled off so a failed
        //    insert becomes a trans_status()=FALSE we can report, not a
        //    rendered error page that aborts the batch.
        $saved_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        try {
            $this->db->trans_start();

            $input_ts = date('Y-m-d H:i:s');

            $user_rows = Dpr_batch_builder::build_production_op_rows(
                $norm['users'],
                array(
                    'tanggal_input' => $input_ts,
                    'runner'        => $norm['runner'],
                    'id_production' => $id_production,
                )
            );
            if (!empty($user_rows)) {
                $this->db->insert_batch('t_production_op', $user_rows);
            }

            $child_extra = array('id_production' => $id_production);

            $detail_rows = Dpr_batch_builder::build_child_rows($norm['detail'], $child_extra);
            if (!empty($detail_rows)) {
                $this->db->insert_batch('t_production_op_dl', $detail_rows);
            }

            $detaillt_rows = Dpr_batch_builder::build_child_rows($norm['detailLT'], $child_extra);
            if (!empty($detaillt_rows)) {
                $this->db->insert_batch('t_production_op_dl', $detaillt_rows);
            }

            $ress_extra = array('id_production' => $id_production, 'runner' => $norm['runner']);
            $ress_rows = Dpr_batch_builder::build_child_rows($norm['ress'], $ress_extra);
            if (!empty($ress_rows)) {
                $this->db->insert_batch('t_production_op_release', $ress_rows);
            }

            $ct_rows = Dpr_batch_builder::build_cutting_tools_rows(
                $norm['cutting_tools_ids'],
                $id_production
            );
            if (!empty($ct_rows)) {
                $this->db->insert_batch('t_production_op_cutting_tools_usage', $ct_rows);
            }

            $this->db->trans_complete();
            $trans_ok = $this->db->trans_status();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $trans_ok = false;
            $caught_msg = $e->getMessage();
        } finally {
            $this->db->db_debug = $saved_debug;
        }

        if (!$trans_ok) {
            $msg = 'Insert transaction failed';
            if (isset($caught_msg)) {
                $msg .= ': ' . $caught_msg;
            } else {
                $dberr = $this->db->error();
                if (!empty($dberr['message'])) {
                    $msg .= ': ' . $dberr['message'];
                }
            }
            return $this->error_outcome($msg);
        }

        // Clear the DB result cache so the new record shows up immediately
        // in the DPR listing (mirrors c_operator::add()'s cache_delete_all).
        $this->db->cache_delete_all();

        return array(
            'status'      => Sync_drain_helper::decide_status(false, $norm['confirm_duplicate']),
            'counter_key' => 'done',
        );
    }

    /**
     * Run the duplicate gate (find_duplicate_entry: tanggal + mesin +
     * shift + id_bom) against the first user row in the payload.
     *
     * Returns false when the check is skipped (confirm_duplicate set, no
     * user row, or any of the four key fields missing - a payload missing
     * these can't meaningfully be duplicate-checked anyway).
     *
     * @param array $norm  Normalized payload (output of normalize_payload).
     * @return bool
     */
    private function is_duplicate_by_dpr_key(array $norm)
    {
        if ($norm['confirm_duplicate']) {
            return false;
        }
        if (empty($norm['users'])) {
            return false;
        }
        $u = $norm['users'][0];
        $tanggal = isset($u['tanggal']) ? $u['tanggal'] : null;
        $mesin   = isset($u['mesin'])   ? $u['mesin']   : null;
        $shift   = isset($u['shift'])   ? $u['shift']   : null;
        $id_bom  = isset($u['id_bom'])  ? $u['id_bom']  : null;

        // Skip if any key field is missing/empty - can't meaningfully check.
        if ($tanggal === null || $tanggal === ''
            || $mesin === null || $mesin === ''
            || $shift === null || $shift === ''
            || $id_bom === null || $id_bom === ''
        ) {
            return false;
        }

        $dup = $this->op->find_duplicate_entry($tanggal, $mesin, $shift, $id_bom);
        return !empty($dup);
    }

    /**
     * Build a standardized 'error' outcome for process_one().
     *
     * @param string $msg
     * @return array
     */
    private function error_outcome($msg)
    {
        return array(
            'status'      => 'error',
            'counter_key' => 'error',
            'error'       => $msg,
        );
    }

    /**
     * Emit a JSON error response and stop.
     *
     * @param int    $http_code
     * @param string $message
     */
    private function send_error_json($http_code, $message)
    {
        http_response_code($http_code);
        echo json_encode(array(
            'status' => 'error',
            'error'  => $message,
        ));
    }

    /**
     * Is this invocation allowed to run the drain?
     *
     * True for CLI (manual `php index.php c_sync queue_drain`, useful for
     * the acceptance test) and for HTTP requests whose REMOTE_ADDR is the
     * loopback (Task Scheduler's curl on http://localhost/...). False for
     * any other client.
     *
     * @return bool
     */
    private function is_locally_invoked()
    {
        if (is_cli()) {
            return true;
        }
        $addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        return $addr === '127.0.0.1' || $addr === '::1';
    }
}
