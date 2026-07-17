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
