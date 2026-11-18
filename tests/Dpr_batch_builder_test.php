<?php
use PHPUnit\Framework\TestCase;

/**
 * Red-green tests for the pure batch-builder seam.
 *
 * These cover the behaviour that the Ticket 1 insert_batch() refactor relies on:
 * turning posted form-array rows + shared "extra" fields into arrays that CI3's
 * insert_batch() can consume, while preserving the side-effects the old
 * single-row insert loops had via $this->db->set().
 */
class Dpr_batch_builder_test extends TestCase
{
    public function test_build_production_op_rows_merges_extra_fields_into_each_user()
    {
        $users = array(
            array('operator' => 'A', 'qty_ok' => 10),
            array('operator' => 'B', 'qty_ok' => 20),
        );
        $extra = array(
            'lot_global'    => 'L1',
            'tanggal_input' => '2026-07-17 08:00:00',
            'runner'        => 'R1',
            'id_production' => 'ID1',
        );

        $rows = Dpr_batch_builder::build_production_op_rows($users, $extra);

        $this->assertCount(2, $rows);
        $this->assertSame('A', $rows[0]['operator']);
        $this->assertSame('ID1', $rows[0]['id_production']);
        $this->assertSame('L1', $rows[0]['lot_global']);
        $this->assertSame('B', $rows[1]['operator']);
        $this->assertSame('ID1', $rows[1]['id_production']);
    }

    public function test_build_production_op_rows_strips_qty_lt_minutes_transient_field()
    {
        $users = array(
            array('operator' => 'A', 'qty_lt_minutes' => 90, 'qty_ok' => 5),
        );

        $rows = Dpr_batch_builder::build_production_op_rows($users, array());

        $this->assertArrayNotHasKey('qty_lt_minutes', $rows[0]);
    }

    public function test_build_production_op_rows_normalizes_qty_lt_to_float()
    {
        $users = array(
            array('qty_lt' => '12.5'),
            array('qty_lt' => 'not-a-number'),
        );

        $rows = Dpr_batch_builder::build_production_op_rows($users, array());

        $this->assertSame(12.5, $rows[0]['qty_lt']);
        $this->assertSame(0.0, $rows[1]['qty_lt']);
    }

    public function test_build_production_op_rows_extra_fields_override_row_fields()
    {
        // The old code used $this->db->set('id_production', ...) AFTER the data
        // array, so set() wins. array_merge order must preserve that: extra wins.
        $users = array(
            array('id_production' => 'STALE', 'operator' => 'A'),
        );
        $extra = array('id_production' => 'CORRECT');

        $rows = Dpr_batch_builder::build_production_op_rows($users, $extra);

        $this->assertSame('CORRECT', $rows[0]['id_production']);
    }

    public function test_build_production_op_rows_empty_input_returns_empty_array()
    {
        $this->assertSame(
            array(),
            Dpr_batch_builder::build_production_op_rows(array(), array('id_production' => 'X'))
        );
    }

    public function test_build_production_op_rows_skips_non_array_entries()
    {
        $users = array(array('operator' => 'A'), 'garbage', null, 42);
        $rows  = Dpr_batch_builder::build_production_op_rows($users, array('id_production' => 'X'));
        $this->assertCount(1, $rows);
        $this->assertSame('A', $rows[0]['operator']);
    }

    public function test_build_child_rows_merges_extra_fields()
    {
        $items = array(
            array('nama' => 'DL1', 'qty' => 3),
            array('nama' => 'DL2', 'qty' => 4),
        );
        $extra = array('id_production' => 'ID1');

        $rows = Dpr_batch_builder::build_child_rows($items, $extra);

        $this->assertCount(2, $rows);
        $this->assertSame('DL1', $rows[0]['nama']);
        $this->assertSame('ID1', $rows[0]['id_production']);
        $this->assertSame('DL2', $rows[1]['nama']);
    }

    public function test_build_child_rows_extra_overrides_row_field()
    {
        $items = array(array('id_production' => 'STALE', 'nama' => 'X'));
        $extra = array('id_production' => 'CORRECT', 'runner' => 'R1');

        $rows = Dpr_batch_builder::build_child_rows($items, $extra);

        $this->assertSame('CORRECT', $rows[0]['id_production']);
        $this->assertSame('R1', $rows[0]['runner']);
    }

    public function test_build_child_rows_empty_input_returns_empty_array()
    {
        $this->assertSame(array(), Dpr_batch_builder::build_child_rows(array(), array()));
    }

    public function test_build_child_rows_skips_non_array_entries()
    {
        $items = array(array('nama' => 'ok'), 'nope', null);
        $rows  = Dpr_batch_builder::build_child_rows($items, array('id_production' => 'X'));
        $this->assertCount(1, $rows);
    }

    public function test_build_cutting_tools_rows_builds_pairs()
    {
        $ids = array('7', '12', '15');
        $rows = Dpr_batch_builder::build_cutting_tools_rows($ids, 'PROD-1');

        $this->assertCount(3, $rows);
        $this->assertSame(array('id_production' => 'PROD-1', 'cutting_tools_id' => '7'),  $rows[0]);
        $this->assertSame(array('id_production' => 'PROD-1', 'cutting_tools_id' => '15'), $rows[2]);
    }

    public function test_build_cutting_tools_rows_empty_returns_empty()
    {
        $this->assertSame(array(), Dpr_batch_builder::build_cutting_tools_rows(array(), 'PROD-1'));
    }

    public function test_build_cutting_tools_rows_skips_non_scalar()
    {
        $ids   = array('7', array('oops'), null, '');
        $rows  = Dpr_batch_builder::build_cutting_tools_rows($ids, 'PROD-1');
        $this->assertCount(1, $rows);
        $this->assertSame('7', $rows[0]['cutting_tools_id']);
    }
}
