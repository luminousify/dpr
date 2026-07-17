<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pure, framework-free helper for the Ticket 2 fast-accept endpoint
 * (api/Sync::submit).
 *
 * Three concerns the controller must settle before touching the DB:
 *   1. Authorisation - is this session an operator OR an admin?
 *   2. Payload shape - is the raw POST body a JSON object carrying a
 *      non-empty string `id_production` (the queue table's UNIQUE KEY)?
 *   3. Queue-row construction - turn a validated payload into the array
 *      that CI3's insert() consumes for t_sync_queue.
 *
 * All methods are pure (no CI3, no DB, no I/O) so they can be unit-tested
 * in isolation by the existing PHPUnit harness (see tests/bootstrap.php,
 * which stubs BASEPATH so this file's guard does not fatal).
 *
 * The controller (application/controllers/api/Sync.php) is intentionally a
 * thin shell: read raw body -> delegate here -> insert -> respond.
 */
class Sync_payload_helper
{
    /**
     * The session keys that mark an authenticated admin vs. operator.
     * Mirrors the existing AJAX auth pattern used by
     * c_dpr::get_autocomplete_cutting_tools.
     */
    const ADMIN_SESSION_KEY   = 'user_name';
    const OPERATOR_SESSION_KEY = 'nama_operator';

    /**
     * Is the given session authorised to submit a sync payload?
     *
     * Accepts either an operator session (nama_operator set) or an admin
     * session (user_name set). Empty / whitespace-only values are rejected
     * so a freshly-destroyed session cannot slip through.
     *
     * @param array $session The $_SESSION / CI3 session array (or a slice of it).
     * @return bool
     */
    public static function is_authorized_session($session)
    {
        if (!is_array($session)) {
            return false;
        }
        foreach (array(self::ADMIN_SESSION_KEY, self::OPERATOR_SESSION_KEY) as $key) {
            if (isset($session[$key])
                && is_string($session[$key])
                && trim($session[$key]) !== ''
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Decode and validate the raw POST body.
     *
     * Returns an associative array shaped:
     *   - on success:  ['ok' => true,  'payload' => array]
     *   - on failure:  ['ok' => false, 'error' => string, 'http_code' => int]
     *
     * Validation rules:
     *   - body must be a non-empty string           -> 400
     *   - body must decode to a JSON object         -> 400
     *   - payload must carry a non-empty string
     *     `id_production` (trimmed)                 -> 422
     *
     * On success the `id_production` value inside the returned payload is
     * the trimmed version, so callers don't have to re-trim.
     *
     * @param string $raw_body
     * @return array
     */
    public static function parse_and_validate_payload($raw_body)
    {
        if (!is_string($raw_body) || trim($raw_body) === '') {
            return self::fail('Empty request body', 400);
        }

        $decoded = json_decode($raw_body, true);

        // json_decode of a scalar / array / null is syntactically valid but
        // not a usable payload; reject anything that isn't a JSON object.
        if (!is_array($decoded) || array_keys($decoded) === range(0, count($decoded) - 1)) {
            return self::fail('Request body must be a JSON object', 400);
        }

        if (!array_key_exists('id_production', $decoded)
            || !is_string($decoded['id_production'])
            || trim($decoded['id_production']) === ''
        ) {
            return self::fail('Missing or empty id_production', 422);
        }

        // Normalise before insert: UNIQUE KEY uk_id_production would otherwise
        // treat ' P1' and 'P1' as different rows.
        $decoded['id_production'] = trim($decoded['id_production']);

        return array('ok' => true, 'payload' => $decoded);
    }

    /**
     * Build the t_sync_queue row to insert for a validated payload.
     *
     * - id_production is copied verbatim (already trimmed/validated upstream).
     * - payload is serialised as canonical JSON (no whitespace, no unicode
     *   escapes) so identical payloads produce identical stored bytes - useful
     *   for diffs and audits later.
     * - status is always 'pending' (the queue entry starts its life here).
     * - created_at / processed_at are intentionally omitted: the DB schema
     *   defaults created_at to CURRENT_TIMESTAMP and leaves processed_at NULL.
     *
     * @param array $payload Validated payload (must contain 'id_production').
     * @return array
     */
    public static function build_queue_row($payload)
    {
        return array(
            'id_production' => $payload['id_production'],
            'payload'       => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'status'        => 'pending',
        );
    }

    /**
     * Internal: build a normalised failure result.
     *
     * @param string $error
     * @param int    $http_code
     * @return array
     */
    private static function fail($error, $http_code)
    {
        return array(
            'ok'        => false,
            'error'     => $error,
            'http_code' => $http_code,
        );
    }
}
