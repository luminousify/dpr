<?php
use PHPUnit\Framework\TestCase;

/**
 * Red-green tests for the Ticket 7 reference-data normalizer seam.
 *
 * The PWA reference-data endpoint (login_op::pwa_reference_data) feeds the
 * offline cache. Raw model rows come back in shapes that differ from what
 * the autocomplete widgets and the kanit <select> expect. This pure helper
 * transposes + trims + de-duplicates them into the stable cache shape so
 * the JS layer (reference-data.js) can store and render them verbatim.
 *
 * Tested in isolation (no DB, no CI3) because the normalization is pure:
 * array in, array out. Matches the Dpr_batch_builder / Sync_payload_helper
 * test pattern.
 */
class Pwa_reference_normalizer_test extends TestCase
{
  // ---- BOM -------------------------------------------------------------

  public function test_normalize_bom_list_maps_raw_row_to_cache_shape()
  {
    $rows = array(
      array(
        'id_bom'        => 'B001',
        'nama_bom'      => 'Part A',
        'kode_product'  => 'KP1',
        'nama_product'  => 'Product A',
        'nama_proses'   => 'Injection',
        'cyt_mc_bom'    => '30',
        'cyt_mp_bom'    => '25',
        'cyt_quo'       => '28',
        'target_shift'  => '1000',
        'id_product'    => 'P1',
        'cavity'        => '4',
        'customer'      => 'Cust A',
        'kode_proses'   => 'INJ',
      ),
    );

    $out = Pwa_reference_normalizer::normalize_bom_list($rows);

    $this->assertCount(1, $out);
    $this->assertSame('B001', $out[0]['id_bom']);
    $this->assertSame('Part A', $out[0]['nama_bom']);
    // The label mirrors what c_operator::get_autocomplete builds, so the
    // offline autocomplete renders identically to the online one.
    $this->assertSame('B001 ( Part A )', $out[0]['label']);
    $this->assertSame('KP1 ( Product A - Injection )', $out[0]['kp_pr']);
    $this->assertSame('Cust A', $out[0]['customer']);
    $this->assertSame('INJ', $out[0]['kode_proses']);
  }

  public function test_normalize_bom_list_coerces_null_cavity_to_one()
  {
    // The online autocomplete does this in JS (cavity_product == null -> 1).
    // Bake the same fallback into the cache so offline selects match.
    $rows = array(
      array('id_bom' => 'B1', 'nama_bom' => 'X', 'cavity' => null),
    );

    $out = Pwa_reference_normalizer::normalize_bom_list($rows);

    $this->assertSame('1', $out[0]['cavity_product']);
  }

  public function test_normalize_bom_list_dedupes_by_id_bom()
  {
    // A JOIN can fan out rows (e.g. adabom x t_product). The cache should
    // carry one entry per id_bom so the autocomplete does not show dupes.
    $rows = array(
      array('id_bom' => 'B1', 'nama_bom' => 'Part A', 'cavity' => '2'),
      array('id_bom' => 'B1', 'nama_bom' => 'Part A', 'cavity' => '2'),
      array('id_bom' => 'B2', 'nama_bom' => 'Part B', 'cavity' => '4'),
    );

    $out = Pwa_reference_normalizer::normalize_bom_list($rows);

    $this->assertCount(2, $out);
    $this->assertSame('B1', $out[0]['id_bom']);
    $this->assertSame('B2', $out[1]['id_bom']);
  }

  public function test_normalize_bom_list_skips_rows_without_id_bom()
  {
    $rows = array(
      array('id_bom' => '', 'nama_bom' => 'No id'),
      array('nama_bom' => 'Missing key entirely'),
      array('id_bom' => 'B1', 'nama_bom' => 'Valid'),
    );

    $out = Pwa_reference_normalizer::normalize_bom_list($rows);

    $this->assertCount(1, $out);
    $this->assertSame('B1', $out[0]['id_bom']);
  }

  public function test_normalize_bom_list_accepts_empty_input()
  {
    $this->assertSame(array(), Pwa_reference_normalizer::normalize_bom_list(array()));
  }

  // ---- Kanit -----------------------------------------------------------

  public function test_normalize_kanit_list_extracts_unique_operator_names()
  {
    // tampil_select_group returns rows like {nama_operator, jabatan, ...}.
    // The cache only needs the sorted unique names (the <select> values).
    $rows = array(
      array('nama_operator' => 'Budi', 'jabatan' => 'kanit'),
      array('nama_operator' => 'Andi', 'jabatan' => 'kanit'),
      array('nama_operator' => 'Budi', 'jabatan' => 'kanit'),
    );

    $out = Pwa_reference_normalizer::normalize_kanit_list($rows);

    // Sorted, de-duplicated.
    $this->assertSame(array('Andi', 'Budi'), $out);
  }

  public function test_normalize_kanit_list_trims_and_skips_empties()
  {
    $rows = array(
      array('nama_operator' => '  Siti  '),
      array('nama_operator' => ''),
      array('nama_operator' => '   '),
    );

    $out = Pwa_reference_normalizer::normalize_kanit_list($rows);

    $this->assertSame(array('Siti'), $out);
  }

  public function test_normalize_kanit_list_accepts_empty_input()
  {
    $this->assertSame(array(), Pwa_reference_normalizer::normalize_kanit_list(array()));
  }

  // ---- Cutting tools ---------------------------------------------------

  public function test_normalize_cutting_tools_maps_id_and_code()
  {
    $rows = array(
      array('id' => 7, 'code' => 'CT-001'),
      array('id' => 8, 'code' => 'CT-002'),
    );

    $out = Pwa_reference_normalizer::normalize_cutting_tools($rows);

    $this->assertCount(2, $out);
    $this->assertSame(7, $out[0]['id']);
    $this->assertSame('CT-001', $out[0]['code']);
    // label + value mirror c_dpr::get_autocomplete_cutting_tools so the
    // offline widget renders identically.
    $this->assertSame('CT-001', $out[0]['label']);
    $this->assertSame('CT-001', $out[0]['value']);
  }

  public function test_normalize_cutting_tools_skips_rows_without_code()
  {
    $rows = array(
      array('id' => 1, 'code' => ''),
      array('id' => 2, 'code' => 'CT-002'),
      array('id' => 3), // missing code key
    );

    $out = Pwa_reference_normalizer::normalize_cutting_tools($rows);

    $this->assertCount(1, $out);
    $this->assertSame('CT-002', $out[0]['code']);
  }

  public function test_normalize_cutting_tools_accepts_empty_input()
  {
    $this->assertSame(array(), Pwa_reference_normalizer::normalize_cutting_tools(array()));
  }

  // ---- Session identity ------------------------------------------------

  public function test_normalize_session_identity_picks_safe_fields_and_drops_password()
  {
    // The SPEC 5.1 security note is explicit: cache identity, NEVER the
    // password. This helper is the single chokepoint that enforces that.
    $raw = array(
      'id_operator'   => '42',
      'nik'           => '04050508',
      'password_op'   => 'secret',
      'jabatan'       => 'operator',
      'nama_operator' => 'Budi',
      'divisi'        => 'Injection',
      'nama_kanit'    => 'Andi',
      'status'        => 'login',
      'some_other_session_field' => 'ignored',
    );

    $out = Pwa_reference_normalizer::normalize_session_identity($raw);

    $this->assertSame('42', $out['id_operator']);
    $this->assertSame('Budi', $out['nama_operator']);
    $this->assertSame('Injection', $out['divisi']);
    $this->assertArrayNotHasKey('password_op', $out);
    $this->assertArrayNotHasKey('some_other_session_field', $out);
  }

  public function test_normalize_session_identity_missing_fields_default_to_empty_string()
  {
    $out = Pwa_reference_normalizer::normalize_session_identity(array());

    foreach (array('id_operator','nik','nama_operator','jabatan','divisi','nama_kanit','status') as $k) {
      $this->assertArrayHasKey($k, $out);
      $this->assertSame('', $out[$k]);
    }
  }

  public function test_normalize_session_identity_casts_scalars_to_string()
  {
    // CI3 session userdata can store ints. IndexedDB is fine with mixed
    // types, but forcing strings keeps the cache shape stable across
    // online refresh + offline reads.
    $out = Pwa_reference_normalizer::normalize_session_identity(array('id_operator' => 42));

    $this->assertSame('42', $out['id_operator']);
  }
}
