<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class c_operator extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//session_start();
		//parent::__construct();
		// if ( !isset($_SESSION['user_name']) ) {
		//         redirect('login_control');
		// }
		$this->load->model('m_operator', 'op');
		// $this->load->model('m_master');
		//$this->load->model('m_grid');
		//$this->load->model('login_model');
		$this->load->helper(array('url', 'html', 'form'));
		//   $this->load->model('model_');
	}


	public function getDataDetail($table, $where)
	{
		$data 	   = [];
		$id        = $this->input->post('id');
		$data      = $this->op->tampilDataDetail($table, $where, $id);
		echo json_encode($data);
	}


	function get_autocomplete()
	{
		if (isset($_GET['term'])) {
			$result = $this->op->search_bomOp($_GET['term']);
			if (count($result) > 0) {
				foreach ($result as $row)
					$arr_result[] = array(
						'label'     		=> $row->id_bom . ' ( ' . $row->nama_bom . ' )',
						'kp_pr'     		=> $row->kode_product . ' ( ' . $row->nama_product . ' - ' . $row->nama_proses . ' )',
						'id_bom'    		=> $row->id_bom,
						'cyt_mp_bom'    	=> $row->cyt_mp_bom,
						'cyt_mc_bom'    	=> $row->cyt_mc_bom,
						'cyt_quo'    		=> isset($row->cyt_quo) ? $row->cyt_quo : null,
						'target_shift'  	=> $row->target_shift,
						'id_pr'  	        => $row->id_product,
						'cavity_product'  => $row->cavity,
						'customer'  		=> $row->customer,
						'kode_proses'  	=> $row->kode_proses,
					);
				echo json_encode($arr_result);
			}
		}
	}


	public function getdatabomMesinDPR()
	{

		$id_bom      = $this->input->post('id_bom');
		$model       = $this->op->tampildataMesin($id_bom);
		$query       = $this->db->query($model);
		foreach ($model as $p) {
			$data .= "<option value ='$p[no_mesin]'> $p[no_mesin] </option>";
		}
		echo $data;
	}

	public function getdataRelease()
	{
		$id_bom      = $this->input->post('id_bom');
		$model       = $this->op->tampildataRelease($id_bom);
		$query       = $this->db->query($model);
		foreach ($model as $p) {
			$data .= "'$p[id_product]'";
		}
		echo $data;
	}

	public function showRelease()
	{
		$id  = $this->uri->segment(3);
		$data['view_release'] = $this->op->tampilRelease($id);
		$this->load->view('online/show_release', $data);
	}

	public function showRelease_edit()
	{
		$id  = $this->uri->segment(3);
		$data['view_release'] = $this->op->tampilRelease($id);
		$this->load->view('online/edit_show_release', $data);
	}


	function get_autocompleteDefect()
	{
		if (isset($_GET['term'])) {
			$result = $this->op->search_Defect($_GET['term']);
			if (count($result) > 0) {
				foreach ($result as $row)
					$arr_result[] = array(
						'label'        => $row->nama,
						'kategori'     => $row->kategori_defect,
						'type'         => $row->type,
						'satuan'       => $row->satuan
					);
				echo json_encode($arr_result);
			}
		}
	}


	function get_autocompleteLosstime()
	{
		if (isset($_GET['term'])) {
			$result = $this->op->search_Losstime($_GET['term']);
			if (count($result) > 0) {
				foreach ($result as $row)
					$arr_result[] = array(
						'label'     => $row->nama,
						'kategori'     => $row->kategori_defect,
						'type'         => $row->type,
						'satuan'       => $row->satuan
					);
				echo json_encode($arr_result);
			}
		}
	}


	function add()
	{
		if (!empty($_POST['user'][0]['id_bom'])) {
			$raw_nett = round($_POST['user'][0]['nett_prod']);
			$raw_gross = round($_POST['user'][0]['gross_prod']);
			$_POST['user'][0]['nett_prod'] = $raw_nett;
			$_POST['user'][0]['gross_prod'] = $raw_gross;
			$lotGlobal           = $this->input->post('lotGlobalSave');
			$id_production       = $this->input->post('id_production');

			// START FIX: Generate id_production on the server if it's empty
			if (empty($id_production)) {
				$tanggal = $this->input->post('user[0][tanggal]');
				$waktu = date('His'); // Current time in His format
				$id_bom = $this->input->post('user[0][id_bom]');
				
				if (!empty($tanggal) && !empty($id_bom)) {
					$ambil_tahun = substr($tanggal, 2, 2);
					$ambil_bulan = substr($tanggal, 5, 2);
					$ambil_tanggal = substr($tanggal, 8, 2);
					$id_production = $ambil_tahun . $ambil_tanggal . $ambil_bulan . $waktu . $id_bom;
					$_POST['id_production'] = $id_production;
				} else {
					// If critical data is missing, we cannot generate an ID. Redirect back.
					$this->session->set_flashdata('gagal', 'Gagal menyimpan: Tanggal atau ID BOM kosong. Silakan coba lagi.');
					redirect('login_op/input_dpr');
				}
			}
			// END FIX

			$this->op->add();

			// --- Cutting Tool Usage: Save selected tools ---
			$cutting_tools_ids = $this->input->post('cutting_tools_ids');
			if ($cutting_tools_ids && is_array($cutting_tools_ids)) {
				$this->op->insert_cutting_tool_usages($id_production, $cutting_tools_ids);
			}
			// ------------------------------------------------

			// CRITICAL FIX: Clear query cache after insert so new data shows immediately!
			$this->db->cache_delete_all();
			log_message('debug', 'DPR saved and cache cleared for: ' . $id_production);

			redirect('c_operator/hasil_input/' . $id_production);
		} else {
			redirect('login_op/input_dpr');
		}
	}


	function hasil_input($id_production = null)
	{
		// START FIX: Handle missing id_production to prevent fatal error
		if (empty($id_production)) {
			$this->session->set_flashdata('gagal', 'ID Produksi tidak ditemukan. Data tidak dapat ditampilkan.');
			redirect('login_op/list_dpr');
		}
		// END FIX

		$data = array(
			'data_production'            => $this->op->tampil_production($id_production),
			'data_productionRelease'     => $this->op->tampil_productionRelease($id_production),
			'data_productionNG'          => $this->op->tampil_productionDL($id_production, 'NG'),
			'data_productionLT'          => $this->op->tampil_productionDL($id_production, 'LT'),
			'kanit'                      => $this->op->tampil_select_group('t_operator', 'jabatan', 'kanit', 'nama_operator'),
			'divisi'                     => $this->session->userdata('divisi'),
			'cutting_tools_usage'        => $this->op->get_cutting_tools_usage($id_production)
		);
		$this->session->set_flashdata('success', 'Data berhasil disimpan!');
		$this->load->view('online/input_hasil_dpr', $data);
	}


	function tes()
	{
		$data = [
			//'mesin'     => $this->m_master->tampil_mesin(),
			'kanit'     => $this->op->tampil_select_group('t_operator', 'jabatan', 'kanit', 'nama_operator'),
			'nik'       => $this->session->userdata('nik'),
			'nama'      => $this->session->userdata('nama_operator'),
			'jabatan'   => $this->session->userdata('jabatan'),
			'divisi'    => $this->session->userdata('divisi'),
		];
		$this->load->view('online/tes', $data);
	}

	function check_prod_plan()
	{

		$result = $this->op->search_prod_plan($_POST['date']);
		// 
		// if (isset($_GET['term'])) {
		//     $result = $this->op->search_Losstime($_GET['term']); 
		if (count($result) > 0) {
			foreach ($result as $row)
				$arr_result[] = array(
					'kp_pr'     => $row->id_bom . " ( " . $row->kode_produk . " ( " . $row->nama_produk . " ) )",
					'nama_produk'    => $row->nama_produk,
					'material_name'  => $row->material_name,
					'prod_qty'       => $row->prod_qty,
					'no_mesin'       => $row->no_mesin,
					'cavity'       => $row->cavity,
					'id_bom'       => $row->id_bom,
					'cyt_mc'       => $row->cyt_mc
				);
			echo json_encode($arr_result);
		}
	}
}
