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
}


