<?php
use PHPUnit\Framework\TestCase;

/**
 * Red-green tests for the pure drain-side helper used by the Ticket 3
 * queue drain worker (c_sync::queue_drain).
 *
 * The drain worker has two pure concerns that deserve unit testing without
 * a DB or framework:
 *
 *   1. normalize_payload() - turn a decoded PWA payload (the `form` object
 *      the PWA stored in IndexedDB and Ticket 6 POSTed to api/Sync/submit)
 *      into the normalized shape the drain loop consumes: sequential arrays
 *      for user/detail/detailLT/ress, a flat scalar array for
 *      cutting_tools_ids, and the scalar fields id_production / runner /
 *      confirm_duplicate pulled to the top level.
 *
 *      The PWA serializes the legacy form via serializeArray() + a nested
 *      builder (see input_dpr_pwa.php::formToObject), so section keys are
 *      STRINGS ("0", "1") after JSON round-trip. normalize_payload() must
 *      collapse those back to sequential arrays and tolerate missing
 *      sections (an operator may save with zero NG rows, etc).
 *
 *   2. decide_status() - the pure duplicate gate (CONTEXT.md Decision 5):
 *      given find_duplicate_entry()'s verdict and the payload's
 *      confirm_duplicate flag, return 'done' or 'duplicate_review'.
 *
 * The DB-touching work (SELECT pending, find_duplicate_entry, insert_batch,
 * UPDATE status) lives in the controller + m_sync_queue and is exercised by
 * the end-to-end drain test, not here.
 */
class Sync_drain_helper_test extends TestCase
{
    // --------------------------------------------------------------------
    // Test fixtures: a payload shaped exactly like the PWA's record.form
    // (see db.js header CONTRACT and input_dpr_pwa.php::formToObject).
    // --------------------------------------------------------------------

    private function sample_payload()
    {
        return array(
            'id_production' => '260717043018000BOM001',
            'user' => array(
                '0' => array(
                    'tanggal'        => '2026-07-17',
                    'shift'          => '1',
                    'mesin'          => 'BOM-MC-01',
                    'id_bom'         => 'BOM001',
                    'operator'       => 'Budi',
                    'qty_ok'         => '100',
                    'qty_ng'         => '5',
                    'lot_global'     => 'L2026',
                    'nett_prod'      => '950',
                    'gross_prod'     => '1000',
                    'qty_lt'         => '12.5',
                    'qty_lt_minutes' => '750', // transient client-only field
                ),
            ),
            'detail' => array(
                '0' => array('nama' => 'Scratch', 'kategori' => 'Visual', 'qty' => '3', 'satuan' => 'pcs', 'type' => 'NG'),
                '1' => array('nama' => 'Short', 'kategori' => 'Mold', 'qty' => '2', 'satuan' => 'pcs', 'type' => 'NG'),
            ),
            'detailLT' => array(
                '0' => array('nama' => 'Setup', 'kategori' => 'LT', 'qty' => '20', 'satuan' => 'min', 'type' => 'LT'),
            ),
            'ress' => array(
                '0' => array('id_product' => 'P001', 'qty' => '950', 'id_bom' => 'BOM001'),
            ),
            'cutting_tools_ids' => array('CT-100', 'CT-200'),
            'runner' => '5',
        );
    }

    // --------------------------------------------------------------------
    // normalize_payload - happy path
    // --------------------------------------------------------------------

    public function test_normalize_payload_ok_with_full_payload()
    {
        $r = Sync_drain_helper::normalize_payload($this->sample_payload());

        $this->assertTrue($r['ok']);
        $this->assertSame('260717043018000BOM001', $r['id_production']);
        $this->assertCount(1, $r['users']);
        $this->assertSame('Budi', $r['users'][0]['operator']);
        $this->assertSame('100', $r['users'][0]['qty_ok']);
        $this->assertCount(2, $r['detail']);
        $this->assertSame('Scratch', $r['detail'][0]['nama']);
        $this->assertSame('Short', $r['detail'][1]['nama']);
        $this->assertCount(1, $r['detailLT']);
        $this->assertSame('Setup', $r['detailLT'][0]['nama']);
        $this->assertCount(1, $r['ress']);
        $this->assertSame('P001', $r['ress'][0]['id_product']);
        $this->assertSame(array('CT-100', 'CT-200'), $r['cutting_tools_ids']);
        $this->assertSame('5', $r['runner']);
        $this->assertFalse($r['confirm_duplicate']);
    }

    public function test_normalize_payload_collapses_string_numeric_keys_to_sequential()
    {
        // PWA formToObject stores user/detail as {"0": {...}, "1": {...}}.
        // After json_decode(true) PHP keeps the string keys; normalize_payload
        // must array_values() them so Dpr_batch_builder's foreach yields a
        // 0-indexed result (insert_batch doesn't care, but downstream logic
        // accessing $r['users'][0] does).
        $r = Sync_drain_helper::normalize_payload($this->sample_payload());

        $this->assertSame(array(0, 1), array_keys($r['detail']));
        $this->assertSame(array(0), array_keys($r['users']));
    }

    public function test_normalize_payload_preserves_transient_qty_lt_minutes_for_batch_builder()
    {
        // The transient strip is Dpr_batch_builder's job (already tested there).
        // normalize_payload must NOT strip it - that would couple the helper
        // to the production-op schema. Verify pass-through.
        $r = Sync_drain_helper::normalize_payload($this->sample_payload());
        $this->assertArrayHasKey('qty_lt_minutes', $r['users'][0]);
    }

    public function test_normalize_payload_confirm_duplicate_truthy_string_one()
    {
        $payload = $this->sample_payload();
        $payload['confirm_duplicate'] = '1';
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertTrue($r['confirm_duplicate']);
    }

    public function test_normalize_payload_confirm_duplicate_truthy_boolean_true()
    {
        $payload = $this->sample_payload();
        $payload['confirm_duplicate'] = true;
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['confirm_duplicate']);
    }

    public function test_normalize_payload_confirm_duplicate_falsy_when_absent()
    {
        $r = Sync_drain_helper::normalize_payload($this->sample_payload());
        $this->assertFalse($r['confirm_duplicate']);
    }

    public function test_normalize_payload_confirm_duplicate_falsy_when_zero_string()
    {
        $payload = $this->sample_payload();
        $payload['confirm_duplicate'] = '0';
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertFalse($r['confirm_duplicate']);
    }

    public function test_normalize_payload_runner_defaults_to_empty_string_when_absent()
    {
        $payload = $this->sample_payload();
        unset($payload['runner']);
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertSame('', $r['runner']);
    }

    public function test_normalize_payload_runner_coerced_to_string()
    {
        $payload = $this->sample_payload();
        $payload['runner'] = 7;
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertSame('7', $r['runner']);
    }

    // --------------------------------------------------------------------
    // normalize_payload - missing sections tolerated
    // --------------------------------------------------------------------

    public function test_normalize_payload_tolerates_missing_user_section()
    {
        $payload = $this->sample_payload();
        unset($payload['user']);
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertSame(array(), $r['users']);
    }

    public function test_normalize_payload_tolerates_missing_detail_sections()
    {
        // An operator may save with zero NG / zero LT / zero release rows.
        $payload = $this->sample_payload();
        unset($payload['detail'], $payload['detailLT'], $payload['ress']);
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertSame(array(), $r['detail']);
        $this->assertSame(array(), $r['detailLT']);
        $this->assertSame(array(), $r['ress']);
    }

    public function test_normalize_payload_tolerates_missing_cutting_tools_ids()
    {
        $payload = $this->sample_payload();
        unset($payload['cutting_tools_ids']);
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertSame(array(), $r['cutting_tools_ids']);
    }

    // --------------------------------------------------------------------
    // normalize_payload - defensive filtering
    // --------------------------------------------------------------------

    public function test_normalize_payload_filters_non_array_user_entries()
    {
        // Defensive: a corrupt payload mustn't poison insert_batch. Garbage
        // entries inside `user` are dropped; valid rows survive.
        $payload = $this->sample_payload();
        $payload['user']['garbage'] = 'not-an-array';
        $payload['user']['1'] = array('operator' => 'Cici');
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertCount(2, $r['users']);
        $this->assertSame('Budi', $r['users'][0]['operator']);
        $this->assertSame('Cici', $r['users'][1]['operator']);
    }

    public function test_normalize_payload_filters_non_scalar_cutting_tools_ids()
    {
        $payload = $this->sample_payload();
        $payload['cutting_tools_ids'] = array('CT-1', array('oops'), null, '', 'CT-2');
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertSame(array('CT-1', 'CT-2'), $r['cutting_tools_ids']);
    }

    public function test_normalize_payload_trims_id_production()
    {
        // Sync_payload_helper already trims on enqueue; the drain must not
        // regress if a row was seeded by hand or by a future caller.
        $payload = $this->sample_payload();
        $payload['id_production'] = '  260717043018000BOM001  ';
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertTrue($r['ok']);
        $this->assertSame('260717043018000BOM001', $r['id_production']);
    }

    // --------------------------------------------------------------------
    // normalize_payload - failure cases
    // --------------------------------------------------------------------

    public function test_normalize_payload_rejects_missing_id_production()
    {
        $payload = $this->sample_payload();
        unset($payload['id_production']);
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertFalse($r['ok']);
        $this->assertNotEmpty($r['error']);
    }

    public function test_normalize_payload_rejects_empty_id_production()
    {
        $payload = $this->sample_payload();
        $payload['id_production'] = '   ';
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertFalse($r['ok']);
    }

    public function test_normalize_payload_rejects_non_string_id_production()
    {
        $payload = $this->sample_payload();
        $payload['id_production'] = array('oops');
        $r = Sync_drain_helper::normalize_payload($payload);
        $this->assertFalse($r['ok']);
    }

    // --------------------------------------------------------------------
    // decide_status - the pure duplicate gate
    // --------------------------------------------------------------------

    public function test_decide_status_done_when_not_duplicate()
    {
        $this->assertSame('done', Sync_drain_helper::decide_status(false, false));
    }

    public function test_decide_status_done_when_not_duplicate_even_if_confirmed()
    {
        $this->assertSame('done', Sync_drain_helper::decide_status(false, true));
    }

    public function test_decide_status_duplicate_review_when_duplicate_and_not_confirmed()
    {
        // CONTEXT.md Decision 5: a duplicate is flagged for supervisor review,
        // NOT auto-inserted. The operator has already left the floor.
        $this->assertSame('duplicate_review', Sync_drain_helper::decide_status(true, false));
    }

    public function test_decide_status_done_when_duplicate_but_confirmed()
    {
        // confirm_duplicate means the operator acknowledged the dupe at save
        // time and wants it force-inserted (matches m_operator::add()).
        $this->assertSame('done', Sync_drain_helper::decide_status(true, true));
    }

    // --------------------------------------------------------------------
    // build_insert_plan - the pure row-building seam shared by the Ticket 3
    // drain worker (c_sync::process_one) and the Ticket 8 supervisor approve
    // action (c_sync::approve_duplicate). Both callers feed the returned
    // plan to c_sync::execute_insert_plan(), which wraps it in a transaction
    // and applies insert_batch() per group.
    //
    // Naming note: the T8 handoff suggested "force_insert_payload"; this
    // helper is named build_insert_plan instead because Sync_drain_helper
    // is the project's pure-PHP / no-CI3 / no-DB / no-I/O seam (see class
    // header). A method that actually inserted would break that contract
    // and the unit suite that depends on it. The impure half of the
    // extraction (the transaction wrapper) lives on the c_sync controller
    // as execute_insert_plan(), tested via browser e2e.
    // --------------------------------------------------------------------

    public function test_build_insert_plan_returns_five_groups_in_canonical_order()
    {
        // Group order is fixed: parent first, then the two dl groups
        // (NG rows, then LT rows - both go to t_production_op_dl but must
        // stay separate so the controller applies them deterministically),
        // then releases, then cutting tools.
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');

        $this->assertCount(5, $plan);
        $this->assertSame('t_production_op',                     $plan[0]['table']);
        $this->assertSame('t_production_op_dl',                  $plan[1]['table']);
        $this->assertSame('t_production_op_dl',                  $plan[2]['table']);
        $this->assertSame('t_production_op_release',             $plan[3]['table']);
        $this->assertSame('t_production_op_cutting_tools_usage', $plan[4]['table']);
    }

    public function test_build_insert_plan_merges_tanggal_input_runner_id_production_into_user_rows()
    {
        // Parent rows get the three shared fields merged in - matches the
        // original drain worker block exactly.
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');

        $user_rows = $plan[0]['rows'];
        $this->assertCount(1, $user_rows);
        $this->assertSame('ID-1', $user_rows[0]['id_production']);
        $this->assertSame('5', $user_rows[0]['runner']);
        $this->assertSame('2026-07-20 10:00:00', $user_rows[0]['tanggal_input']);
        // Original user fields survive the merge.
        $this->assertSame('Budi', $user_rows[0]['operator']);
        $this->assertSame('100',  $user_rows[0]['qty_ok']);
    }

    public function test_build_insert_plan_strips_transient_qty_lt_minutes_via_batch_builder()
    {
        // build_insert_plan delegates to Dpr_batch_builder, which strips the
        // client-only qty_lt_minutes field. The supervisor approve path must
        // not regress this - t_production_op has no such column.
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');
        $this->assertArrayNotHasKey('qty_lt_minutes', $plan[0]['rows'][0]);
    }

    public function test_build_insert_plan_coerces_qty_lt_to_float_via_batch_builder()
    {
        // Same delegation: Dpr_batch_builder coerces qty_lt to float. The
        // supervisor path inherits this so a force-inserted row matches the
        // shape a drain-inserted row would have.
        $payload = $this->sample_payload();
        $payload['user']['0']['qty_lt'] = '12.5';
        $norm = Sync_drain_helper::normalize_payload($payload);
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');
        $this->assertSame(12.5, $plan[0]['rows'][0]['qty_lt']);
    }

    public function test_build_insert_plan_emits_detail_and_detaillt_as_separate_dl_groups()
    {
        // Both detail (NG) and detailLT (LT) rows land in t_production_op_dl
        // but as TWO separate groups - matches the drain worker's two
        // sequential insert_batch('t_production_op_dl', ...) calls.
        // id_production is merged into every child row.
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');

        $this->assertSame('t_production_op_dl', $plan[1]['table']);
        $this->assertSame('t_production_op_dl', $plan[2]['table']);
        $this->assertCount(2, $plan[1]['rows']);           // detail (NG) rows
        $this->assertSame('Scratch', $plan[1]['rows'][0]['nama']);
        $this->assertSame('Short',   $plan[1]['rows'][1]['nama']);
        $this->assertCount(1, $plan[2]['rows']);           // detailLT (LT) rows
        $this->assertSame('Setup',   $plan[2]['rows'][0]['nama']);
        // id_production merged into every child row.
        $this->assertSame('ID-1', $plan[1]['rows'][0]['id_production']);
        $this->assertSame('ID-1', $plan[2]['rows'][0]['id_production']);
    }

    public function test_build_insert_plan_release_rows_get_id_production_and_runner_merged()
    {
        // Release rows are child rows but get runner merged too (matches
        // the drain worker's $ress_extra).
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');

        $release_group = $plan[3];
        $this->assertSame('t_production_op_release', $release_group['table']);
        $this->assertCount(1, $release_group['rows']);
        $this->assertSame('ID-1', $release_group['rows'][0]['id_production']);
        $this->assertSame('5',    $release_group['rows'][0]['runner']);
        $this->assertSame('P001', $release_group['rows'][0]['id_product']);
    }

    public function test_build_insert_plan_cutting_tools_rows_are_flat_id_production_tool_id_pairs()
    {
        // The PWA payload's flat cutting_tools_ids list becomes one row per
        // tool, each stamped with id_production.
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');

        $ct_group = $plan[4];
        $this->assertSame('t_production_op_cutting_tools_usage', $ct_group['table']);
        $this->assertCount(2, $ct_group['rows']);
        $this->assertSame(
            array('id_production' => 'ID-1', 'cutting_tools_id' => 'CT-100'),
            $ct_group['rows'][0]
        );
        $this->assertSame(
            array('id_production' => 'ID-1', 'cutting_tools_id' => 'CT-200'),
            $ct_group['rows'][1]
        );
    }

    public function test_build_insert_plan_tolerates_missing_sections_with_empty_row_arrays()
    {
        // An operator may legitimately save with no NG / no LT / no release
        // / no cutting tools (and, defensively, no users). The plan must
        // still emit five groups with empty rows arrays - the controller
        // skips empties via a !empty() check before insert_batch, and the
        // stable shape keeps both call sites iteration-simple.
        $minimal = array('id_production' => 'ID-1');
        $norm = Sync_drain_helper::normalize_payload($minimal);
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');

        $this->assertCount(5, $plan);
        foreach ($plan as $group) {
            $this->assertArrayHasKey('table', $group);
            $this->assertArrayHasKey('rows', $group);
            $this->assertSame(array(), $group['rows']);
        }
    }

    public function test_build_insert_plan_uses_supplied_input_timestamp_verbatim()
    {
        // Both drain and approve must stamp ONE timestamp for the whole
        // insert (not regenerate per call) so a re-insert after a crash
        // doesn't smear tanggal_input. Caller supplies; build_insert_plan
        // passes through.
        $norm = Sync_drain_helper::normalize_payload($this->sample_payload());
        $plan = Sync_drain_helper::build_insert_plan($norm, 'ID-1', '2026-07-20 10:00:00');
        $this->assertSame('2026-07-20 10:00:00', $plan[0]['rows'][0]['tanggal_input']);
    }
}
