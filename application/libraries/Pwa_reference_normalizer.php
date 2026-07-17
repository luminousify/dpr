<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PWA reference-data + session normalizer (Ticket 7 / Phase 5).
 *
 * Pure helper: array in, array out. No CI3 superobject, no DB. This is the
 * single chokepoint that transposes raw model rows into the stable JSON
 * shape the PWA offline cache (IndexedDB `reference_data` + `session`
 * stores) consumes, so reference-data.js and auth.js can store and render
 * the data verbatim.
 *
 * Why a helper (not inline in the controller)?
 *  - Keeps login_op thin (the controller just aggregates model calls +
 *    json_encodes), matching how T2/T3 factored their logic into
 *    Sync_payload_helper / Sync_drain_helper.
 *  - Pure functions are unit-testable without a DB (see
 *    tests/Pwa_reference_normalizer_test.php), which is the only viable
 *    TDD seam for Ticket 7's PHP surface.
 *
 * Security note (SPEC 5.1): normalize_session_identity() deliberately
 * whitelists identity fields and NEVER includes password_op. Caching
 * operator identity (name/divisi/kanit) on personal phones is acceptable;
 * caching credentials is not.
 */
class Pwa_reference_normalizer
{
  /**
   * Fields cached for offline form rendering. Anything not in this list
   * (notably password_op) is dropped.
   */
  private static $SESSION_FIELDS = array(
    'id_operator', 'nik', 'nama_operator', 'jabatan',
    'divisi', 'nama_kanit', 'status',
  );

  /**
   * Normalize a raw BOM rowset (from m_operator::get_all_bom_for_cache)
   * into the autocomplete-ready cache shape.
   *
   * De-duplicates by id_bom (the adabom table may JOIN-fan-out), coerces
   * a null cavity to "1" (mirroring input_dpr.js's online fallback), and
   * builds the same label / kp_pr strings c_operator::get_autocomplete
   * builds so the offline widget renders identically.
   */
  public static function normalize_bom_list(array $rows)
  {
    $out = array();
    $seen = array();
    foreach ($rows as $row) {
      $id_bom = isset($row['id_bom']) ? trim((string) $row['id_bom']) : '';
      if ($id_bom === '' || isset($seen[$id_bom])) {
        continue;
      }
      $seen[$id_bom] = true;

      $nama_bom    = isset($row['nama_bom']) ? (string) $row['nama_bom'] : '';
      $kode_product = isset($row['kode_product']) ? (string) $row['kode_product'] : '';
      $nama_product = isset($row['nama_product']) ? (string) $row['nama_product'] : '';
      $nama_proses  = isset($row['nama_proses']) ? (string) $row['nama_proses'] : '';
      $cavity       = isset($row['cavity']) ? $row['cavity'] : null;

      $out[] = array(
        'id_bom'        => $id_bom,
        'nama_bom'      => $nama_bom,
        'label'         => $id_bom . ' ( ' . $nama_bom . ' )',
        'kp_pr'         => $kode_product . ' ( ' . $nama_product . ' - ' . $nama_proses . ' )',
        'cyt_mp_bom'    => isset($row['cyt_mp_bom']) ? (string) $row['cyt_mp_bom'] : '',
        'cyt_mc_bom'    => isset($row['cyt_mc_bom']) ? (string) $row['cyt_mc_bom'] : '',
        'cyt_quo'       => array_key_exists('cyt_quo', $row) ? ($row['cyt_quo'] === null ? null : (string) $row['cyt_quo']) : null,
        'target_shift'  => isset($row['target_shift']) ? (string) $row['target_shift'] : '',
        'id_pr'         => isset($row['id_product']) ? (string) $row['id_product'] : '',
        'cavity_product'=> ($cavity === null || $cavity === '') ? '1' : (string) $cavity,
        'customer'      => isset($row['customer']) ? (string) $row['customer'] : '',
        'kode_proses'   => isset($row['kode_proses']) ? (string) $row['kode_proses'] : '',
      );
    }
    return $out;
  }

  /**
   * Normalize a raw kanit rowset (from m_operator::tampil_select_group) into
   * a sorted, unique, trimmed list of operator names. The PWA kanit <select>
   * only needs the name values.
   */
  public static function normalize_kanit_list(array $rows)
  {
    $names = array();
    foreach ($rows as $row) {
      $name = isset($row['nama_operator']) ? trim((string) $row['nama_operator']) : '';
      if ($name !== '' && !in_array($name, $names, true)) {
        $names[] = $name;
      }
    }
    sort($names, SORT_STRING);
    return $names;
  }

  /**
   * Normalize a raw cutting-tools rowset (from m_dpr::get_cutting_tools_autocomplete)
   * into the autocomplete-ready cache shape. Mirrors the label/value/id
   * triple that c_dpr::get_autocomplete_cutting_tools emits.
   */
  public static function normalize_cutting_tools(array $rows)
  {
    $out = array();
    foreach ($rows as $row) {
      $code = isset($row['code']) ? trim((string) $row['code']) : '';
      if ($code === '') {
        continue;
      }
      $out[] = array(
        'id'    => isset($row['id']) ? $row['id'] : null,
        'code'  => $code,
        'label' => $code,
        'value' => $code,
      );
    }
    return $out;
  }

  /**
   * Whitelist + cast the CI3 session userdata into the cached identity
   * shape. Drops password_op and any other field not in $SESSION_FIELDS.
   * Missing fields default to '' so the cache shape is stable across
   * online refreshes and offline reads.
   */
  public static function normalize_session_identity(array $raw)
  {
    $out = array();
    foreach (self::$SESSION_FIELDS as $field) {
      $out[$field] = isset($raw[$field]) ? (string) $raw[$field] : '';
    }
    return $out;
  }
}
