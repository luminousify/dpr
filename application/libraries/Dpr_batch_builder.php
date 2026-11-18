<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pure batch-row builder for the DPR write path.
 *
 * No CodeIgniter dependencies: takes plain arrays in, returns plain arrays
 * suitable for CI3's $this->db->insert_batch(). This is the testable seam that
 * lets the Ticket 1 single-row -> insert_batch() refactor be covered by unit
 * tests without bootstrapping the framework.
 *
 * Behaviour preserved from the old per-row loops:
 *   - "extra" fields (formerly set via $this->db->set()) win over fields that
 *     happen to already exist on the row (array_merge order: extra is last).
 *   - t_production_op rows have the transient `qty_lt_minutes` stripped and
 *     `qty_lt` coerced to float.
 *   - non-array / non-scalar entries are skipped defensively.
 *
 * Note on harmonisation (Ticket 1): historically only m_operator::add() did the
 * qty_lt_minutes strip + qty_lt float coercion; m_dpr::add() did not. Routing
 * both write paths through build_production_op_rows() harmonises them so the
 * admin form (c_dpr) now also strips/coerces. qty_lt_minutes is a transient
 * client-side field with no matching column, so this is a safe unification.
 */
class Dpr_batch_builder
{
    /**
     * Build rows for t_production_op.
     *
     * @param array $users   Posted $_POST['user'] rows.
     * @param array $extra   Shared fields (lot_global, tanggal_input, runner, id_production).
     * @return array Rows ready for insert_batch('t_production_op', ...).
     */
    public static function build_production_op_rows(array $users, array $extra)
    {
        $rows = array();
        foreach ($users as $user) {
            if (! is_array($user)) {
                continue;
            }
            // Transient client-side field, never a real column.
            if (isset($user['qty_lt_minutes'])) {
                unset($user['qty_lt_minutes']);
            }
            if (isset($user['qty_lt'])) {
                $user['qty_lt'] = is_numeric($user['qty_lt']) ? (float) $user['qty_lt'] : 0.0;
            }
            $rows[] = array_merge($user, $extra);
        }
        return $rows;
    }

    /**
     * Build child rows for t_production_op_dl and t_production_op_release.
     *
     * @param array $items  Posted detail / detailLT / ress rows.
     * @param array $extra  Shared fields (id_production, [runner]).
     * @return array Rows ready for insert_batch(...).
     */
    public static function build_child_rows(array $items, array $extra)
    {
        $rows = array();
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $rows[] = array_merge($item, $extra);
        }
        return $rows;
    }

    /**
     * Build rows for t_production_op_cutting_tools_usage.
     *
     * @param array  $tool_ids       List of cutting_tools_id values.
     * @param string $id_production  Owning production id.
     * @return array Rows ready for insert_batch('t_production_op_cutting_tools_usage', ...).
     */
    public static function build_cutting_tools_rows(array $tool_ids, $id_production)
    {
        $rows = array();
        foreach ($tool_ids as $tool_id) {
            if (! is_scalar($tool_id) || (string) $tool_id === '') {
                continue;
            }
            $rows[] = array(
                'id_production'    => $id_production,
                'cutting_tools_id' => $tool_id,
            );
        }
        return $rows;
    }
}
