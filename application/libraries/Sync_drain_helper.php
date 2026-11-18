<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pure, framework-free helper for the Ticket 3 queue drain worker
 * (c_sync::queue_drain).
 *
 * Two concerns the drain controller must settle before it touches the DB:
 *
 *   1. normalize_payload() - turn a decoded PWA payload (the `form` object
 *      the PWA stored in IndexedDB, Ticket 6 then POSTed to api/Sync/submit,
 *      and Sync_payload_helper stored verbatim in t_sync_queue.payload) into
 *      the normalized shape the drain loop consumes.
 *
 *      The PWA serializes the legacy form via serializeArray() + a nested
 *      builder (see input_dpr_pwa.php::formToObject). Section keys are
 *      STRINGS ("0", "1") after JSON round-trip; this helper collapses them
 *      back to sequential arrays so Dpr_batch_builder's foreach yields a
 *      0-indexed result. Missing sections (zero NG rows, no cutting tools,
 *      etc.) are tolerated as empty arrays - an operator may legitimately
 *      save with none.
 *
 *   2. decide_status() - the pure duplicate gate (CONTEXT.md Decision 5):
 *      given find_duplicate_entry()'s verdict and the payload's
 *      confirm_duplicate flag, decide whether the row should land in real
 *      tables ('done') or be flagged for supervisor review
 *      ('duplicate_review'). The actual duplicate lookup + DB writes live
 *      in the controller, not here.
 *
 * All methods are pure (no CI3, no DB, no I/O) so they can be unit-tested
 * in isolation by the existing PHPUnit harness (see tests/bootstrap.php,
 * which stubs BASEPATH so this file's guard does not fatal).
 *
 * The controller (application/controllers/c_sync.php) is intentionally a
 * thin shell: claim rows -> normalize -> duplicate-check -> insert_batch ->
 * update status.
 *
 * PAYLOAD CONTRACT (must agree with db.js header + input_dpr_pwa.php
 * submit intercept + Sync_payload_helper):
 *
 *   {
 *     id_production: "260717043018000BOM001",  // top-level, the queue UNIQUE KEY
 *     user:           { "0": { ...t_production_op columns... } },
 *     detail:         { "0": { nama, kategori, qty, satuan, type } },  // NG rows
 *     detailLT:       { "0": { ...same shape, type='LT' } },           // LT rows
 *     ress:           { "0": { id_product, qty, id_bom } },            // release
 *     cutting_tools_ids: ["CT-100", "CT-200"],                         // flat
 *     runner:         "5",                                             // scalar
 *     confirm_duplicate: "1"                                           // optional
 *   }
 *
 * `detail`, `detailLT`, `ress`, `cutting_tools_ids`, `runner`,
 * `confirm_duplicate` are all optional; an operator may save with any of
 * them absent.
 */
class Sync_drain_helper
{
    /**
     * Normalize a decoded PWA payload into the shape the drain loop needs.
     *
     * Returns an associative array shaped:
     *   - on success:  ['ok' => true, 'id_production' => string,
     *                   'users' => array, 'detail' => array,
     *                   'detailLT' => array, 'ress' => array,
     *                   'cutting_tools_ids' => array, 'runner' => string,
     *                   'confirm_duplicate' => bool]
     *   - on failure:  ['ok' => false, 'error' => string]
     *
     * Sections are run through array_values() so string-numeric keys ("0",
     * "1") collapse to a 0-indexed array. Non-array entries inside the
     * section objects are dropped defensively so a corrupt payload cannot
     * poison insert_batch().
     *
     * Note: this method does NOT strip the transient `qty_lt_minutes`
     * field from user rows - that is Dpr_batch_builder's responsibility
     * (and is already tested there). Keeping the concerns separated avoids
     * coupling this helper to the t_production_op column set.
     *
     * @param array $payload Decoded JSON (the `form` object from the PWA).
     * @return array
     */
    public static function normalize_payload(array $payload)
    {
        // id_production is the queue UNIQUE KEY; require a non-empty string.
        // Sync_payload_helper already enforces this on enqueue, but the
        // drain must not regress if a row was seeded by hand (the Ticket 3
        // acceptance test does exactly that) or by a future caller.
        if (!isset($payload['id_production'])
            || !is_string($payload['id_production'])
            || trim($payload['id_production']) === ''
        ) {
            return self::fail('Missing or empty id_production');
        }

        return array(
            'ok'                  => true,
            'id_production'       => trim($payload['id_production']),
            'users'               => self::extract_section($payload, 'user'),
            'detail'              => self::extract_section($payload, 'detail'),
            'detailLT'            => self::extract_section($payload, 'detailLT'),
            'ress'                => self::extract_section($payload, 'ress'),
            'cutting_tools_ids'   => self::extract_cutting_tools($payload),
            'runner'              => self::extract_runner($payload),
            'confirm_duplicate'   => self::extract_confirm_duplicate($payload),
        );
    }

    /**
     * Decide the final queue status for a row given duplicate signals.
     *
     * CONTEXT.md Decision 5: duplicates are flagged for supervisor review,
     * NOT auto-inserted, because the operator has already left the floor by
     * the time the drain runs. The confirm_duplicate flag (set when the
     * operator ticked "I know this is a duplicate, submit anyway") honors
     * the same override the legacy m_operator::add() applies.
     *
     * @param bool $is_duplicate       True if find_duplicate_entry() matched.
     * @param bool $confirm_duplicate  True if the payload asked to force-insert.
     * @return string 'done' or 'duplicate_review'.
     */
    public static function decide_status($is_duplicate, $confirm_duplicate)
    {
        if ($is_duplicate && !$confirm_duplicate) {
            return 'duplicate_review';
        }
        return 'done';
    }

    /**
     * Build the ordered insert plan for a normalized payload: a list of
     * (table, rows) groups ready for the controller to apply via
     * insert_batch() inside a transaction.
     *
     * This is the pure seam shared by:
     *   - the drain worker (Ticket 3, c_sync::process_one), after the
     *     duplicate gate passes; and
     *   - the supervisor approve action (Ticket 8, c_sync::approve_duplicate),
     *     which force-inserts a duplicate_review row.
     *
     * Pure (no CI3, no DB, no I/O) so it can be unit-tested in isolation.
     * The actual transaction + insert_batch lives in
     * c_sync::execute_insert_plan(), which both callers feed this plan to.
     *
     * Group order is fixed (parent first, then child tables) so a partial
     * failure rolls back cleanly:
     *   0. t_production_op                    parent: tanggal_input/runner/id_production merged
     *   1. t_production_op_dl   (detail)      NG rows; id_production merged
     *   2. t_production_op_dl   (detailLT)    LT rows; same table, SEPARATE group
     *                                          (caller applies via two insert_batch calls)
     *   3. t_production_op_release            id_production + runner merged
     *   4. t_production_op_cutting_tools_usage
     *
     * Row-building is delegated to Dpr_batch_builder (the existing Ticket 1
     * pure seam) so qty_lt_minutes stripping, qty_lt float coercion, and
     * defensive filtering remain in one place - both call sites inherit
     * identical row shapes.
     *
     * Empty sections emit empty groups (not skipped) so the plan shape is
     * stable across payloads; the controller skips empty groups via a
     * !empty() check before insert_batch().
     *
     * @param array  $norm          Output of normalize_payload().
     * @param string $id_production The production id (also in $norm, but
     *                              passed explicitly to make the contract
     *                              obvious at the call site).
     * @param string $input_ts      'YYYY-MM-DD HH:MM:SS' timestamp to stamp
     *                              on parent rows; caller supplies so the
     *                              whole insert shares ONE timestamp.
     * @return array Ordered list of ['table' => string, 'rows' => array].
     */
    public static function build_insert_plan(array $norm, $id_production, $input_ts)
    {
        $id_production = (string) $id_production;
        $runner        = isset($norm['runner']) ? $norm['runner'] : '';

        $user_rows = Dpr_batch_builder::build_production_op_rows(
            isset($norm['users']) ? $norm['users'] : array(),
            array(
                'tanggal_input' => $input_ts,
                'runner'        => $runner,
                'id_production' => $id_production,
            )
        );

        $child_extra = array('id_production' => $id_production);

        $detail_rows = Dpr_batch_builder::build_child_rows(
            isset($norm['detail']) ? $norm['detail'] : array(),
            $child_extra
        );

        $detaillt_rows = Dpr_batch_builder::build_child_rows(
            isset($norm['detailLT']) ? $norm['detailLT'] : array(),
            $child_extra
        );

        $ress_rows = Dpr_batch_builder::build_child_rows(
            isset($norm['ress']) ? $norm['ress'] : array(),
            array('id_production' => $id_production, 'runner' => $runner)
        );

        $ct_rows = Dpr_batch_builder::build_cutting_tools_rows(
            isset($norm['cutting_tools_ids']) ? $norm['cutting_tools_ids'] : array(),
            $id_production
        );

        return array(
            array('table' => 't_production_op',                     'rows' => $user_rows),
            array('table' => 't_production_op_dl',                  'rows' => $detail_rows),
            array('table' => 't_production_op_dl',                  'rows' => $detaillt_rows),
            array('table' => 't_production_op_release',             'rows' => $ress_rows),
            array('table' => 't_production_op_cutting_tools_usage', 'rows' => $ct_rows),
        );
    }

    // --------------------------------------------------------------------
    // Internal extractors (private)
    // --------------------------------------------------------------------

    /**
     * Pull a section (user / detail / detailLT / ress) and normalize it to a
     * 0-indexed array of arrays, dropping non-array entries.
     *
     * @param array  $payload
     * @param string $key
     * @return array
     */
    private static function extract_section(array $payload, $key)
    {
        if (!isset($payload[$key]) || !is_array($payload[$key])) {
            return array();
        }
        $out = array();
        foreach ($payload[$key] as $entry) {
            if (is_array($entry)) {
                $out[] = $entry;
            }
        }
        return $out;
    }

    /**
     * Pull cutting_tools_ids and flatten to an array of non-empty scalars.
     *
     * Mirrors Dpr_batch_builder::build_cutting_tools_rows()'s filter so a
     * corrupt payload (nested arrays, nulls, empties) cannot reach the DB.
     *
     * @param array $payload
     * @return array
     */
    private static function extract_cutting_tools(array $payload)
    {
        if (!isset($payload['cutting_tools_ids']) || !is_array($payload['cutting_tools_ids'])) {
            return array();
        }
        $out = array();
        foreach ($payload['cutting_tools_ids'] as $id) {
            if (is_scalar($id) && (string) $id !== '') {
                $out[] = $id;
            }
        }
        return $out;
    }

    /**
     * Pull runner (top-level scalar) and coerce to string. Default '' when
     * absent so the downstream release-row builder always has something to
     * merge (matches m_operator::add()'s $this->input->post('runner')).
     *
     * @param array $payload
     * @return string
     */
    private static function extract_runner(array $payload)
    {
        if (!isset($payload['runner'])) {
            return '';
        }
        return is_scalar($payload['runner']) ? (string) $payload['runner'] : '';
    }

    /**
     * Pull confirm_duplicate and coerce to bool. Truthy for: true, "1", 1.
     * Falsy for everything else (absent, "0", 0, null, "").
     *
     * @param array $payload
     * @return bool
     */
    private static function extract_confirm_duplicate(array $payload)
    {
        if (!isset($payload['confirm_duplicate'])) {
            return false;
        }
        $v = $payload['confirm_duplicate'];
        if (is_bool($v)) {
            return $v;
        }
        if (is_string($v)) {
            return $v === '1';
        }
        if (is_int($v)) {
            return $v === 1;
        }
        return false;
    }

    /**
     * Internal: build a failure result.
     *
     * @param string $error
     * @return array
     */
    private static function fail($error)
    {
        return array(
            'ok'    => false,
            'error' => $error,
        );
    }
}
