<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ticket 2 / Phase 1.2: fast-accept sync endpoint.
 *
 * Accepts a JSON POST containing a PWA production record, validates the
 * session + payload shape, inserts the row into t_sync_queue, and returns
 * {"status":"queued","id_production":"..."} in well under 50ms.
 *
 * What this endpoint does NOT do (by design - see CONTEXT.md decision 6
 * and SPEC_PWA_QUEUE.md section 1.2):
 *   - no business validation (duplicate-kanit, BOM existence, etc.)
 *   - no JOINs
 *   - no writes into the real production tables
 *   - no reference data lookups
 *
 * All of that is the drain worker's job (Ticket 3 / c_sync::queue_drain).
 *
 * URL: POST /dpr/api/Sync/submit  (CI3 resolves api/Sync -> this file)
 *
 * Auth: either an operator session (nama_operator) or an admin session
 * (user_name) - mirrors c_dpr::get_autocomplete_cutting_tools.
 *
 * Responses:
 *   200 {"status":"queued","id_production":"..."}  - newly inserted OR
 *                                                    idempotent retry of an
 *                                                    already-queued id_production
 *   400 {"status":"error","error":"..."}           - empty / malformed JSON body
 *   401 {"status":"error","error":"..."}           - no valid session
 *   422 {"status":"error","error":"..."}           - payload missing id_production
 *   500 {"status":"error","error":"..."}           - unexpected DB failure
 */
class Sync extends CI_Controller
{
    /**
     * Accept a JSON production payload from the PWA and enqueue it.
     *
     * Flow: validate session -> parse + validate JSON body -> build queue
     * row -> insert into t_sync_queue -> respond. A duplicate id_production
     * (PWA retry of an already-queued record) is treated as success so the
     * client's retry logic is transparent.
     *
     * @return void Emits a JSON response; does not return.
     */
    public function submit()
    {
        // Every path returns JSON; set the header once up front.
        header('Content-Type: application/json');

        // 1) Auth - reject before any parsing if no session is present.
        //    We read $_SESSION directly (the existing AJAX pattern) to avoid
        //    loading the session library just for an isset check.
        if (!Sync_payload_helper::is_authorized_session(isset($_SESSION) ? $_SESSION : array())) {
            $this->send_error(401, 'Not authenticated');
            return;
        }

        // 2) Parse + validate the JSON body.
        $raw_body = file_get_contents('php://input');
        $parsed   = Sync_payload_helper::parse_and_validate_payload($raw_body);
        if (!$parsed['ok']) {
            $this->send_error($parsed['http_code'], $parsed['error']);
            return;
        }

        // 3) Build the queue row and enqueue it. A duplicate id_production
        //    (PWA retry) is treated as success: the row is already queued.
        $this->load->model('m_sync_queue');
        $row    = Sync_payload_helper::build_queue_row($parsed['payload']);
        $result = $this->m_sync_queue->enqueue($row);

        if ($result === 'failed') {
            $this->send_error(500, 'Failed to enqueue');
            return;
        }

        // 4) Success - 'inserted' and 'duplicate' both return the same
        //    response so PWA retries are transparent.
        http_response_code(200);
        echo json_encode(array(
            'status'        => 'queued',
            'id_production' => $row['id_production'],
        ));
    }

    /**
     * Emit a JSON error response and stop.
     *
     * @param int    $http_code
     * @param string $message
     */
    private function send_error($http_code, $message)
    {
        http_response_code($http_code);
        echo json_encode(array(
            'status' => 'error',
            'error'  => $message,
        ));
    }
}
