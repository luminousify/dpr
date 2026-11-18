<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class m_target extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	/**
	 * Get targets for a specific year and month
	 */
	public function get_by_period($tahun, $bulan)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		// Guard: if target table does not exist yet, return empty result
		$exists = $this->db->query(
			"SELECT COUNT(*) AS c FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'dpr_injection_new' AND TABLE_NAME = 't_losstime_target'"
		)->row_array();
		if (empty($exists) || (int)$exists['c'] === 0) {
			return $this->db->query('SELECT 1 WHERE 0');
		}

		$sql = "SELECT id, tahun, bulan, bagian, target_percent, created_by, updated_by, created_at, updated_at
				FROM dpr_injection_new.t_losstime_target
				WHERE tahun = ? AND bulan = ?
				ORDER BY bagian ASC";
		return $this->db->query($sql, [$tahun, $bulan]);
	}

	/**
	 * Create a new target row. Returns insert id on success, false on failure.
	 */
	public function create(array $data)
	{
		$payload = [
			'tahun' => (int) ($data['tahun'] ?? 0),
			'bulan' => (int) ($data['bulan'] ?? 0),
			'bagian' => (string) ($data['bagian'] ?? ''),
			'target_percent' => (float) ($data['target_percent'] ?? 0),
			'created_by' => isset($data['created_by']) ? (string) $data['created_by'] : null,
			'updated_by' => isset($data['updated_by']) ? (string) $data['updated_by'] : null,
		];

		$ok = $this->db->insert('dpr_injection_new.t_losstime_target', $payload);
		if ($ok) {
			return (int) $this->db->insert_id();
		}
		return false;
	}

	/**
	 * Update an existing target row by id. Returns true on success, false on failure.
	 */
	public function update($id, array $data)
	{
		$id = (int) $id;
		$payload = [];
		if (isset($data['target_percent'])) {
			$payload['target_percent'] = (float) $data['target_percent'];
		}
		if (isset($data['updated_by'])) {
			$payload['updated_by'] = (string) $data['updated_by'];
		}

		if (empty($payload)) {
			return true; // nothing to update
		}

		$this->db->where('id', $id);
		return $this->db->update('dpr_injection_new.t_losstime_target', $payload);
	}

	/**
	 * Delete a target row by id. Returns true on success, false on failure.
	 */
	public function delete($id)
	{
		$id = (int) $id;
		$this->db->where('id', $id);
		return $this->db->delete('dpr_injection_new.t_losstime_target');
	}

	/**
	 * Get targets by category and month for losstime by category table
	 */
	public function get_by_category_and_month($tahun, $bulan)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		
		// Guard: if target table does not exist yet, return empty result
		$exists = $this->db->query(
			"SELECT COUNT(*) AS c FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'dpr_injection_new' AND TABLE_NAME = 't_losstime_target'"
		)->row_array();
		if (empty($exists) || (int)$exists['c'] === 0) {
			return $this->db->query('SELECT 1 WHERE 0');
		}

		$sql = "SELECT bagian, target_percent
				FROM dpr_injection_new.t_losstime_target
				WHERE tahun = ? AND bulan = ?
				ORDER BY bagian ASC";
		return $this->db->query($sql, [$tahun, $bulan]);
	}

	/**
	 * Get sum of all targets by month for losstime by month table
	 */
	public function get_sum_by_month($tahun, $bulan)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		
		// Guard: if target table does not exist yet, return 0
		$exists = $this->db->query(
			"SELECT COUNT(*) AS c FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'dpr_injection_new' AND TABLE_NAME = 't_losstime_target'"
		)->row_array();
		if (empty($exists) || (int)$exists['c'] === 0) {
			return 0;
		}

		$sql = "SELECT COALESCE(SUM(target_percent), 0) as total_target
				FROM dpr_injection_new.t_losstime_target
				WHERE tahun = ? AND bulan = ?";
		$result = $this->db->query($sql, [$tahun, $bulan])->row_array();
		return (float) $result['total_target'];
	}

	/**
	 * Get sum of all targets for each month in a year for losstime by month table
	 */
	public function get_sum_by_each_month($tahun)
	{
		$tahun = (int) $tahun;
		
		// Guard: if target table does not exist yet, return empty result
		$exists = $this->db->query(
			"SELECT COUNT(*) AS c FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'dpr_injection_new' AND TABLE_NAME = 't_losstime_target'"
		)->row_array();
		if (empty($exists) || (int)$exists['c'] === 0) {
			return $this->db->query('SELECT 1 WHERE 0');
		}

		$sql = "SELECT bulan, COALESCE(SUM(target_percent), 0) as total_target
				FROM dpr_injection_new.t_losstime_target
				WHERE tahun = ?
				GROUP BY bulan
				ORDER BY bulan";
		return $this->db->query($sql, [$tahun]);
	}

	/**
	 * Auto-copy targets from previous month if current month has no targets
	 */
	public function auto_copy_from_previous_month($tahun, $bulan)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		
		// Check if current month already has targets
		$existing = $this->db->query(
			"SELECT COUNT(*) as count FROM dpr_injection_new.t_losstime_target 
			 WHERE tahun = ? AND bulan = ?",
			[$tahun, $bulan]
		)->row_array();
		
		if ((int)$existing['count'] > 0) {
			return false; // Already has targets, no need to copy
		}
		
		// Get previous month
		if ($bulan == 1) {
			$prev_tahun = $tahun - 1;
			$prev_bulan = 12;
		} else {
			$prev_tahun = $tahun;
			$prev_bulan = $bulan - 1;
		}
		
		// Get targets from previous month
		$prev_targets = $this->db->query(
			"SELECT bagian, target_percent FROM dpr_injection_new.t_losstime_target 
			 WHERE tahun = ? AND bulan = ?",
			[$prev_tahun, $prev_bulan]
		)->result_array();
		
		if (empty($prev_targets)) {
			return false; // No previous targets to copy
		}
		
		// Copy targets to current month
		$created_by = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'system_auto_copy';
		$copied_count = 0;
		
		foreach ($prev_targets as $target) {
			$this->db->insert('dpr_injection_new.t_losstime_target', [
				'tahun' => $tahun,
				'bulan' => $bulan,
				'bagian' => $target['bagian'],
				'target_percent' => $target['target_percent'],
				'created_by' => $created_by,
				'updated_by' => $created_by,
			]);
			$copied_count++;
		}
		
		return $copied_count;
	}

	/**
	 * Copy targets to next month(s)
	 */
	public function copy_to_next_months($tahun, $bulan, $months_count = 1)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		$months_count = (int) $months_count;
		
		// Get current targets
		$current_targets = $this->db->query(
			"SELECT bagian, target_percent FROM dpr_injection_new.t_losstime_target 
			 WHERE tahun = ? AND bulan = ?",
			[$tahun, $bulan]
		)->result_array();
		
		if (empty($current_targets)) {
			return false; // No targets to copy
		}
		
		$created_by = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'system_copy';
		$copied_count = 0;
		
		// Copy to specified number of months
		for ($i = 1; $i <= $months_count; $i++) {
			$target_tahun = $tahun;
			$target_bulan = $bulan + $i;
			
			// Handle year rollover
			if ($target_bulan > 12) {
				$target_tahun++;
				$target_bulan = $target_bulan - 12;
			}
			
			// Delete existing targets for target month (to avoid duplicates)
			$this->db->delete('dpr_injection_new.t_losstime_target', [
				'tahun' => $target_tahun,
				'bulan' => $target_bulan
			]);
			
			// Insert copied targets
			foreach ($current_targets as $target) {
				$this->db->insert('dpr_injection_new.t_losstime_target', [
					'tahun' => $target_tahun,
					'bulan' => $target_bulan,
					'bagian' => $target['bagian'],
					'target_percent' => $target['target_percent'],
					'created_by' => $created_by,
					'updated_by' => $created_by,
				]);
				$copied_count++;
			}
		}
		
		return $copied_count;
	}

	/**
	 * Check if month has targets and compare with previous month
	 */
	public function get_month_status($tahun, $bulan)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		
		// Check current month targets
		$current_count = $this->db->query(
			"SELECT COUNT(*) as count FROM dpr_injection_new.t_losstime_target 
			 WHERE tahun = ? AND bulan = ?",
			[$tahun, $bulan]
		)->row_array()['count'];
		
		// Get previous month
		if ($bulan == 1) {
			$prev_tahun = $tahun - 1;
			$prev_bulan = 12;
		} else {
			$prev_tahun = $tahun;
			$prev_bulan = $bulan - 1;
		}
		
		// Check previous month targets
		$prev_count = $this->db->query(
			"SELECT COUNT(*) as count FROM dpr_injection_new.t_losstime_target 
			 WHERE tahun = ? AND bulan = ?",
			[$prev_tahun, $prev_bulan]
		)->row_array()['count'];
		
		return [
			'has_targets' => $current_count > 0,
			'has_previous_targets' => $prev_count > 0,
			'can_copy' => $current_count == 0 && $prev_count > 0,
			'status' => $current_count > 0 ? 'has_targets' : ($prev_count > 0 ? 'can_copy' : 'no_data')
		];
	}

	/**
	 * Get targets with auto-copy functionality
	 */
	public function get_by_period_with_auto_copy($tahun, $bulan)
	{
		// Try to auto-copy if no targets exist
		$this->auto_copy_from_previous_month($tahun, $bulan);
		
		// Return targets (either existing or auto-copied)
		return $this->get_by_period($tahun, $bulan);
	}
}


