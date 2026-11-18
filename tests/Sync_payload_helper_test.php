<?php
use PHPUnit\Framework\TestCase;

/**
 * Red-green tests for the pure payload/session helper used by the Ticket 2
 * fast-accept endpoint (api/Sync::submit).
 *
 * These cover the three pure concerns the controller needs to settle before
 * touching the DB:
 *   1. Is this session authorised to submit? (operator OR admin session)
 *   2. Is the raw POST body a valid JSON payload with the required
 *      id_production field (the queue table's UNIQUE KEY)?
 *   3. How do we turn a validated payload into a t_sync_queue row?
 *
 * Keeping these pure lets us unit-test them without bootstrapping CI3 or
 * hitting MySQL; the controller itself stays a thin shell.
 */
class Sync_payload_helper_test extends TestCase
{
    // --------------------------------------------------------------------
    // is_authorized_session
    // --------------------------------------------------------------------

    public function test_is_authorized_session_true_for_operator_session()
    {
        $session = array(
            'status'         => 'login',
            'nama_operator'  => 'Budi',
            'id_operator'    => '12',
        );
        $this->assertTrue(Sync_payload_helper::is_authorized_session($session));
    }

    public function test_is_authorized_session_true_for_admin_session()
    {
        $session = array(
            'user_name' => 'admin01',
            'divisi'    => 'CKU',
        );
        $this->assertTrue(Sync_payload_helper::is_authorized_session($session));
    }

    public function test_is_authorized_session_false_for_empty_session()
    {
        $this->assertFalse(Sync_payload_helper::is_authorized_session(array()));
    }

    public function test_is_authorized_session_false_when_keys_present_but_empty()
    {
        // Defensive: an unauthenticated request shouldn't slip through just
        // because the keys exist with empty values (CI3 sessions are arrays).
        $session = array('nama_operator' => '', 'user_name' => '');
        $this->assertFalse(Sync_payload_helper::is_authorized_session($session));
    }

    public function test_is_authorized_session_false_for_null_values()
    {
        $session = array('nama_operator' => null, 'user_name' => null);
        $this->assertFalse(Sync_payload_helper::is_authorized_session($session));
    }

    // --------------------------------------------------------------------
    // parse_and_validate_payload
    // --------------------------------------------------------------------

    public function test_parse_and_validate_payload_ok_with_id_production()
    {
        $raw = json_encode(array(
            'id_production' => '260707080000001',
            'user'          => array('operator' => 'Budi'),
            'detail'        => array(),
            'detailLT'      => array(),
            'ress'          => array(),
            'cutting_tools' => array(),
        ));

        $result = Sync_payload_helper::parse_and_validate_payload($raw);

        $this->assertTrue($result['ok']);
        $this->assertSame('260707080000001', $result['payload']['id_production']);
    }

    public function test_parse_and_validate_payload_rejects_empty_body()
    {
        $result = Sync_payload_helper::parse_and_validate_payload('');
        $this->assertFalse($result['ok']);
        $this->assertSame(400, $result['http_code']);
        $this->assertNotEmpty($result['error']);
    }

    public function test_parse_and_validate_payload_rejects_invalid_json()
    {
        $result = Sync_payload_helper::parse_and_validate_payload('{not json');
        $this->assertFalse($result['ok']);
        $this->assertSame(400, $result['http_code']);
    }

    public function test_parse_and_validate_payload_rejects_non_object_json()
    {
        // A bare array or scalar is valid JSON but not a usable payload.
        $result = Sync_payload_helper::parse_and_validate_payload('[1,2,3]');
        $this->assertFalse($result['ok']);
        $this->assertSame(400, $result['http_code']);
    }

    public function test_parse_and_validate_payload_rejects_missing_id_production()
    {
        $raw = json_encode(array('user' => array('operator' => 'Budi')));
        $result = Sync_payload_helper::parse_and_validate_payload($raw);
        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['http_code']);
    }

    public function test_parse_and_validate_payload_rejects_empty_id_production()
    {
        $raw = json_encode(array('id_production' => ''));
        $result = Sync_payload_helper::parse_and_validate_payload($raw);
        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['http_code']);
    }

    public function test_parse_and_validate_payload_trims_whitespace_id_production()
    {
        // The UNIQUE KEY means trailing/leading whitespace would create
        // near-duplicates; normalise before insert.
        $raw = json_encode(array('id_production' => '  260707080000001  '));
        $result = Sync_payload_helper::parse_and_validate_payload($raw);
        $this->assertTrue($result['ok']);
        $this->assertSame('260707080000001', $result['payload']['id_production']);
    }

    public function test_parse_and_validate_payload_rejects_non_string_id_production()
    {
        $raw = json_encode(array('id_production' => 12345));
        $result = Sync_payload_helper::parse_and_validate_payload($raw);
        $this->assertFalse($result['ok']);
        $this->assertSame(422, $result['http_code']);
    }

    public function test_parse_and_validate_payload_preserves_other_form_fields()
    {
        $raw = json_encode(array(
            'id_production' => 'P1',
            'user'          => array('operator' => 'A'),
            'detail'        => array(array('nama' => 'NG1', 'qty' => 2)),
        ));
        $result = Sync_payload_helper::parse_and_validate_payload($raw);
        $this->assertTrue($result['ok']);
        $this->assertSame(array('nama' => 'NG1', 'qty' => 2), $result['payload']['detail'][0]);
    }

    // --------------------------------------------------------------------
    // build_queue_row
    // --------------------------------------------------------------------

    public function test_build_queue_row_sets_status_pending_and_json_payload()
    {
        $payload = array('id_production' => 'P1', 'user' => array('x' => 1));

        $row = Sync_payload_helper::build_queue_row($payload);

        $this->assertSame('P1', $row['id_production']);
        $this->assertSame('pending', $row['status']);
        // Payload is stored as a JSON string (JSON column on the DB side).
        $this->assertIsString($row['payload']);
        $decoded = json_decode($row['payload'], true);
        $this->assertSame('P1', $decoded['id_production']);
        $this->assertSame(1, $decoded['user']['x']);
    }

    public function test_build_queue_row_payload_is_canonical_json()
    {
        // Canonical JSON (no pretty print, no unicode escapes) so identical
        // payloads hash identically - useful for diff/audit later.
        $payload = array('id_production' => 'P1', 'user' => array('operator' => 'Budi'));

        $row = Sync_payload_helper::build_queue_row($payload);

        $this->assertSame(
            '{"id_production":"P1","user":{"operator":"Budi"}}',
            $row['payload']
        );
    }

    public function test_build_queue_row_does_not_set_timestamps()
    {
        // created_at defaults to CURRENT_TIMESTAMP on the DB side; processed_at
        // stays NULL. The helper should not preempt either.
        $row = Sync_payload_helper::build_queue_row(array('id_production' => 'P1'));
        $this->assertArrayNotHasKey('created_at', $row);
        $this->assertArrayNotHasKey('processed_at', $row);
    }
}
