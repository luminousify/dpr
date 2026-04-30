<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class c_report extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_dpr', 'mm');
		$this->load->model('m_report', 'mr');
		$this->load->model('m_target', 'mt');
		$this->load->helper(array('url', 'html', 'form'));
		$this->data = [
			'user_name'  => $_SESSION['user_name'],
			'bagian'     => $_SESSION['divisi'],
		];
		$this->mm->cek_login();
	}


	// Daily OK Report - New function based on c_dpr/report/qty_ok/qty_ok but daily basis
	function report_daily_ok()
	{
		// Check if this is a filter POST request (same pattern as c_dpr/dpr)
		$isFilterPost = ($this->input->server('REQUEST_METHOD') === 'POST') &&
						($this->input->post('tanggal_dari') !== null || $this->input->post('tanggal_sampai') !== null || $this->input->post('shift') !== null);

		if ($isFilterPost) {
			$dari = $this->input->post('tanggal_dari');
			$sampai = $this->input->post('tanggal_sampai');
			$shift = $this->input->post('shift');
		} else {
			$dari = date('Y-m-d');
			$sampai = date('Y-m-d');
			$shift = 'All';
		}

		$data = [
			'data'       => $this->data,
			'aktif'      => 'report',
			'data_tabel' => $this->mr->get_daily_ok_report($dari, $sampai, $shift),
			'dari'       => $dari,
			'sampai'     => $sampai,
			'shift'      => $shift
		];
		
		$this->load->view('report/daily_ok', $data);
	}

	function productivity()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);

		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'data_productionGrafik' => $this->mr->tampil_productionGrafik_byCTQuo($tahuns),
			'tampil_worst_nett'   	    	=> $this->mr->tampil_worst_nett_byquo($bulan, $tahuns),
			'tampil_worst_gross'   	    	=> $this->mr->tampil_worst_gross_byquo($bulan, $tahuns),
			'minNett'   	    	=> $this->mr->minNett($tahuns, $bulan),
			'tahun'				=> $tahuns,
			'bulan'				=> $bulan
		];
		$this->load->view('global/productivity/1productivity', $data);
	}

	function productivity_quartal()
	{
		// Determine the year from GET or POST, or use current year as default
		$year_param = $this->input->get('year') ?: $this->input->post('year');

		// Use year parameter if provided, otherwise default to current year
		if (!empty($year_param)) {
			$tahun = $year_param;
		} else {
			$tahun = date('Y');
		}

		// Build data array with all required variables
		$data = [
			'data'          => $this->data,
			'aktif'         => 'global',
			'productivity_q1'   => $this->mr->productivity_q1($tahun),
			'productivity'  => $this->mr->get_annual_productivity($tahun),
			'year'          => $tahun,
			'tahun'         => $tahun
		];

		// Load the view with the data
		$this->load->view('global/productivity/productivity_quartal', $data);
	}

	function productivity_detail()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y');
		}

		$tahuns = substr($tahun, 0, 4);
		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'detail_productivity'  	=> $this->mr->tampil_detail_productivity_byCTQuo($tahuns),
			'tahun'				=> $tahuns,
		];
		$this->load->view('global/productivity/detail_productivity', $data);
	}

	

	function seven_data_and_table()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y');
		}

		$tahuns = substr($tahun, 0, 4);
		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'seventable'  	=> $this->mr->get_7table_data_from_year($tahuns),
			'seventable_summary' => $this->mr->get_7_table_summary_data($tahuns),
			'tahun'				=> $tahuns,
		];
		
		$this->load->view('global/7data/seven_data_and_table', $data);
	}

	

	function productivity_detail_by_part_by_month()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);

		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'detail_productivity_bypart_bymonth'  	=> $this->mr->tampil_detail_productivity_bypart_bymonth($bulan, $tahuns),
			'tahun'				=> $tahuns,
			'bulan'				=> $bulan
		];
		$this->load->view('global/productivity/productivity_detail_by_part_by_month', $data);
	}

	function summary_prod_by_part()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y');
		}

		$tahuns = substr($tahun, 0, 4);
		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'detail_productivity'  	=> $this->mr->tampil_summary_by_part($tahuns),
			'tahun'					=> $tahuns,
		];
		$this->load->view('global/productivity/summary_prod_by_part', $data);
	}


	function worst_gross_nett()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);

		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'tampil_worst_nett'   	    	=> $this->mr->tampil_worst_nett_byquo($bulan, $tahuns),
			'tampil_worst_gross'   	    	=> $this->mr->tampil_worst_gross_byquo($bulan, $tahuns),
			'tampil_worst_nett_all'   	    	=> $this->mr->tampil_worst_nett_byquo_all($bulan, $tahuns),
			'tampil_worst_gross_all'   	    	=> $this->mr->tampil_worst_gross_byquo_all($bulan, $tahuns),
			'minNett'   	    	=> $this->mr->minNett($tahuns, $bulan),
			'tahun'				=> $tahuns,
			'bulan'				=> $bulan
		];
		$this->load->view('global/productivity/worst_gross_nett', $data);
	}

	function view_detail_worst_nett($kode_product, $bulan)
	{
		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulans  = substr($tahun, 5, 2);
		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'view_detail_worst'   	=> $this->mr->tampil_detail_worst_byquo($kode_product, $bulan, $tahuns),
			'tahun'					=> $tahuns,
			'bulan'					=> $bulan
		];
		$this->load->view('global/productivity/view_detail_worst_nett', $data);
	}

	function view_detail_worst_gross($kode_product, $bulan)
	{
		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulans  = substr($tahun, 5, 2);
		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'view_detail_worst'   	=> $this->mr->tampil_detail_worst_byquo($kode_product, $bulan, $tahuns),
			'tahun'					=> $tahuns,
			'bulan'					=> $bulan
		];
		$this->load->view('global/productivity/view_detail_worst_gross', $data);
	}

	function view_detail_bypart($kode_product, $bulan)
	{
		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulans  = substr($tahun, 5, 2);
		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'view_detail_bypart'   	=> $this->mr->view_detail_prod_bypart_bymonth($kode_product, $bulan, $tahuns),
			'tahun'					=> $tahuns,
			'bulan'					=> $bulan
		];
		$this->load->view('global/productivity/view_detail_prod_bypart_bymonth', $data);
	}


	function productionPPM()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);
		$data = [
			'data'          			=> $this->data,
			'aktif'         			=> 'global',
			'data_productionGrafik' 	=> $this->mr->tampil_grafikPPM($tahun),
			'total_produksi' 			=> $this->mr->total_produksi($tahun),
			'total_produksi_ok' 		=> $this->mr->total_prod_ok($tahun),
			'total_produksi_ng'			=> $this->mr->total_prod_ng($tahun),
			'total_ppm'					=> $this->mr->total_ppm($tahun),
			'ppm_fcost_target'			=> $this->mr->ppm_fcost_target($tahun),
			'fcost_target'				=> $this->mr->f_cost_target($tahun),
			// 'detail_prod_qty_ppm'		=> $this->mr->detail_prod_qty_ppm($tahun),
			// 'prod_qty_and_ppm'			=> $this->mr->production_qty_and_ppm($tahuns),
			'total_prod_qty_and_ppm'	=> $this->mr->total_prod_qty_and_ppm($tahuns),
			'f_cost_int_defect'			=> $this->mr->f_cost_int_defect($tahuns),
			// 'fcost_usd'					=> $this->mr->f_cost_int_defect($tahuns),
			// 'fcost_idr'					=> $this->mr->f_cost_int_defect($tahuns),
			// 'total_prod_bymonth'		=> $this->mr->prod_qty_bymonth($bulan,$tahuns),
			// 'worst_10_ng'				=> $this->mr->worst_10_ng($bulan,$tahuns),
			// 'total_bylimit'				=> $this->mr->total_bylimit($bulan,$tahuns),
			'tanggal'					=> $tahun,
			'getNilai'		    		=> substr($tahun, 5, 2),
			'tahun'						=> $tahuns,
			'bulan'						=> $bulan

		];
		$this->load->view('global/production_ppm/1production_ppm', $data);
	}

	function production_qty_quartal()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y');
		}
		$tahuns = substr($tahun, 0, 4);
		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'ppm_fcost_target'			=> $this->mr->ppm_fcost_target($tahun), //0.0037
			'data_productionGrafik'   => $this->mr->tampil_grafikPPM($tahuns), //1.1633
			'total_produksi' 			=> $this->mr->total_produksi($tahun), //0.7711
			'total_produksi_ok' 		=> $this->mr->total_prod_ok($tahun), //0.8502
			'total_produksi_ng'			=> $this->mr->total_prod_ng($tahun), //1.1627
			'total_ppm'					=> $this->mr->total_ppm($tahun), //0.7571
			'fcost_target'				=> $this->mr->f_cost_target($tahun), //0.0029
			'total_prod_qty_and_ppm_q1'   => $this->mr->total_prod_qty_and_ppm_q1($tahuns), //2.7195
			'total_prod_qty_and_ppm_q2'   => $this->mr->total_prod_qty_and_ppm_q2($tahuns), //2.6785
			'total_prod_qty_and_ppm_q3'   => $this->mr->total_prod_qty_and_ppm_q3($tahuns), //3.0050
			'f_cost_int_defect_q1'   => $this->mr->f_cost_int_defect_q1($tahuns), //2.8942
			'f_cost_int_defect_q2'   => $this->mr->f_cost_int_defect_q2($tahuns), //2.6633
			'f_cost_int_defect_q3'   => $this->mr->f_cost_int_defect_q3($tahuns), //2.9342
			'tahun'				=> $tahuns,
		]; //21.0824

		$this->load->view('global/production_ppm/productivity_ppm_quartal', $data);
	}

	function production_qty_ppm_detail()
	{

		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);
		$data = [
			'data'          			=> $this->data,
			'aktif'         			=> 'global',
			'prod_qty_and_ppm'			=> $this->mr->production_qty_and_ppm($tahuns),
			'total_prod_qty_and_ppm'	=> $this->mr->total_prod_qty_and_ppm($tahuns),
			'tahun'						=> $tahuns,
			'bulan'						=> $bulan
		];
		$this->load->view('global/production_ppm/detail_production_qty_ppm', $data);
	}

	function worst_defect_by_ppm()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);
		$data = [
			'data'          			=> $this->data,
			'aktif'         			=> 'global',
			'total_prod_bymonth'		=> $this->mr->prod_qty_bymonth($bulan, $tahuns),
			'worst_10_ng'				=> $this->mr->worst_10_ng($bulan, $tahuns),
			'total_bylimit'				=> $this->mr->total_bylimit($bulan, $tahuns),
			'tanggal'					=> $tahun,
			'tahun'						=> $tahuns,
			'bulan'						=> $bulan

		];
		$this->load->view('global/production_ppm/worst_defect_ppm', $data);
	}

	function efesiencyMesin()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);
		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'data_EffGrafik' 		=> $this->mr->tampil_EffGrafik($tahuns, $bulan), //5s
			'data_EffGrafikYear' 	=> $this->mr->tampil_EffGrafikYear($tahuns), //5s
			'data_total_mesin'   	=> $this->mr->getTotalMesin(), //0s
			'data_EffMesinRekap' 	=> $this->mr->eff_mesin_rekap($tahuns), //5s

			'tanggal'				=> $tahun,
			'getNilai'	    		=> substr($tahun, 5, 2),
			'tahun'					=> $tahuns,
			'bulan'					=> $bulan

		];
		$this->load->view('global/efesiency/1efesiency', $data);
	}


	function defect()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);

		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'data_production'   	=> $this->mr->tampil_production($tahun),
			'data_DefectGrafik' 	=> $this->mr->tampil_DefectGrafik($tahuns, $bulan),
			'data_DefectGrafikYear' => $this->mr->tampil_DefectGrafikYear($tahuns),
			'data_DefectGrafikProductLimit' => $this->mr->tampil_DefectGrafikProductDefault_defectbyfilter($tahuns, $bulan),
			'data_DefectGrafikProduct' 		=> $this->mr->tampil_DefectGrafikProductDefault_defectbyfilter($tahuns, $bulan),
			'maxPPM'   	    		=> $this->mr->maxPPM($tahuns, $bulan),
			'tanggal'				=> $tahun,
			'getNilai'	    		=> substr($tahun, 5, 2),
			'tahun'					=> $tahuns,
			'bulan'					=> $bulan

		];
		$this->load->view('global/defect/1defect', $data);
	}

	function view_detail_ng($kategori, $bulan)
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
			$tahuns = substr($tahun, 0, 4);
			$bulan  = substr($tahun, 5, 2);
			$data = [
				'data'          		=> $this->data,
				'aktif'         		=> 'global',
				'view_detail_ng'   	=> $this->mr->tampil_detailNG_new($kategori, $tahuns, $bulan),
				'view_header_ng'   	=> $this->mr->tampil_detailNG($kategori),
				'tahun'					=> $tahuns,
				'bulan'					=> $bulan
			];
			$this->load->view('global/defect/view_detailNG', $data);
		} else {
			$tahun = date('Y-m');
			$tahuns = substr($tahun, 0, 4);
			$bulans  = substr($tahun, 5, 2);
			$data = [
				'data'          		=> $this->data,
				'aktif'         		=> 'global',
				'view_detail_ng'   	=> $this->mr->tampil_detailNG_new($kategori, $tahuns, $bulan),
				'view_header_ng'   	=> $this->mr->tampil_detailNG($bulan),
				'tahun'					=> $tahuns,
				'bulan'					=> $bulan
			];
			$this->load->view('global/defect/view_detailNG', $data);
		}
	}

	function view_detail_ng_byproduct($kode_product, $bulan)
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
			$tahuns = substr($tahun, 0, 4);
			$bulan  = substr($tahun, 5, 2);
			$data = [
				'data'          				=> $this->data,
				'aktif'         				=> 'global',
				'header'   				=> $this->mr->tampil_product($kode_product),
				'view_detail_ng_byproduct'   	=> $this->mr->tampil_detailNG_byproduct($kode_product, $tahuns, $bulan),
				'tahun'							=> $tahuns,
				'bulan'							=> $bulan
			];
			$this->load->view('global/defect/view_detailNG_byproduct', $data);
		} else {
			$tahun = date('Y-m');
			$tahuns = substr($tahun, 0, 4);
			$bulans  = substr($tahun, 5, 2);
			$data = [
				'data'          				=> $this->data,
				'aktif'         				=> 'global',
				'header'   				=> $this->mr->tampil_product($kode_product),
				'view_detail_ng_byproduct'   	=> $this->mr->tampil_detailNG_byproduct($kode_product, $tahuns, $bulan),
				'tahun'							=> $tahuns,
				'bulan'							=> $bulan
			];
			$this->load->view('global/defect/view_detailNG_byproduct', $data);
		}
	}

	function view_detail_ng_bykategori($nama_kategori, $bulan)
	{
		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulans  = substr($tahun, 5, 2);
		$data = [
			'data'          				=> $this->data,
			'aktif'         				=> 'global',
			//'view_header_ng'   				=> $this->mr->tampil_detailNG_header_byproduct($kode_product,$bulan),
			'view_detail_ng_bykategori'   	=> $this->mr->tampil_detailNG_bykategori($nama_kategori, $bulan),
			'tahun'							=> $tahuns,
			'bulan'							=> $bulan
		];
		$this->load->view('global/defect/view_detailNG_bykategori', $data);
	}

	function losstime()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
			$tahuns = substr($tahun, 0, 4);
			$bulan  = substr($tahun, 5, 2);
			$data = [
				'data'          		=> $this->data,
				'aktif'          		=> 'global',
				'data_DefectGrafik'  	=> $this->mr->tampil_DefectGrafikLT($tahuns, $bulan),
				'data_DefectGrafikYear' => $this->mr->tampil_DefectGrafikYear($tahuns),
				'data_DefectGrafikProductLimit' => $this->mr->tampil_DefectGrafikProductLimit($tahuns, $bulan),
				'data_DefectGrafikProduct'  	=> $this->mr->tampil_DefectGrafikProduct($tahuns, $bulan),
				'data_losstime_byname_limit_bymonth'  	=> $this->mr->tampil_losstime_byname_limit_byfilter($tahuns, $bulan),
				'data_7table' => $this->mr->tampil_7Table(),
				'data_7table_table' => $this->mr->tampil_7Table_table(),
				'kategori'     	    	=> $this->mr->tampil_select_group('t_defectdanlosstime', 'LT'),
				'tanggal'				=> $tahun,
				'getNilai'	    		=> substr($tahun, 5, 2),
				'tahun'					=> $tahuns,
				'bulan'					=> $bulan
			];
			// Load targets for current period
			$this->load->model('m_target', 'mt');
			$data['targets'] = $this->mt->get_by_period($tahuns, $bulan);
			$this->load->view('global/losstime/1losstime', $data);
		} else {
			$tahun = date('Y-m');
			$tahuns = substr($tahun, 0, 4);
			$bulan  = substr($tahun, 5, 2);
			$data = [
				'data'          		=> $this->data,
				'aktif'          		=> 'global',
				'data_DefectGrafik'  	=> $this->mr->tampil_DefectGrafikLTDefault(),
				'data_DefectGrafikYear' => $this->mr->tampil_DefectGrafikYear($tahuns),
				'data_DefectGrafikProductLimit' => $this->mr->tampil_DefectGrafikProductLimitDefault(),
				'data_DefectGrafikProduct'  	=> $this->mr->tampil_DefectGrafikProductDefault(),
				'data_losstime_byname_limit_bymonth'  	=> $this->mr->tampil_losstime_byname_limit_default(),
				'data_7table' => $this->mr->tampil_7Table(),
				'data_7table_table' => $this->mr->tampil_7Table_table(),
				'kategori'     	    	=> $this->mr->tampil_select_group('t_defectdanlosstime', 'LT'),
				'tanggal'				=> $tahun,
				'getNilai'	    		=> substr($tahun, 5, 2),
				'tahun'					=> $tahuns,
				'bulan'					=> $bulan
			];
			// Load targets for current period
			$this->load->model('m_target', 'mt');
			$data['targets'] = $this->mt->get_by_period($tahuns, $bulan);
			$this->load->view('global/losstime/1losstime', $data);
		}
	}

	function view_detail_lt($kategori, $bulan)
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
			$tahuns = substr($tahun, 0, 4);
			$bulan  = substr($tahun, 5, 2);
			$data = [
				'data'          		=> $this->data,
				'aktif'          		=> 'global',
				'view_detail_lt'   	=> $this->mr->tampil_detailLT($kategori, $tahuns, $bulan),
				'view_header_lt'   	=> $this->mr->tampil_header_detailLT($kategori),
				'tahun'					=> $tahuns,
				'bulan'					=> $bulan
			];
			$this->load->view('global/losstime/view_detail_lt', $data);
		} else {
			$tahun = date('Y-m');
			$tahuns = substr($tahun, 0, 4);
			$bulans  = substr($tahun, 5, 2);
			$data = [
				'data'          		=> $this->data,
				'aktif'          		=> 'global',
				'view_detail_lt'   	=> $this->mr->tampil_detailLT($kategori, $tahuns, $bulan),
				'view_header_lt'   	=> $this->mr->tampil_header_detailLT($kategori),
				'tahun'					=> $tahuns,
				'bulan'					=> $bulan
			];
			$this->load->view('global/losstime/view_detail_lt', $data);
		}
	}

	function view_detail_lt_byproduct($kode_product, $bulan)
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
			$tahuns = substr($tahun, 0, 4);
			$bulan  = substr($tahun, 5, 2);
			$data = [
				'data'          				=> $this->data,
				'aktif'         				=> 'global',
				'view_detail_lt_byproduct'   	=> $this->mr->tampil_detailLT_byproduct($kode_product, $tahuns, $bulan),
				'header'   				=> $this->mr->tampil_product($kode_product),
				'tahun'						=> $tahuns,
				'bulan'						=> $bulan
			];
			$this->load->view('global/losstime/view_detailLT_byproduct', $data);
		} else {
			$tahun = date('Y-m');
			$tahuns = substr($tahun, 0, 4);
			$bulans  = substr($tahun, 5, 2);
			$data = [
				'data'          				=> $this->data,
				'aktif'         				=> 'global',
				'view_detail_lt_byproduct'   	=> $this->mr->tampil_detailLT_byproduct($kode_product, $tahuns, $bulan),
				'header'   				=> $this->mr->tampil_product($kode_product),
				'tahun'						=> $tahuns,
				'bulan'						=> $bulan
			];
			$this->load->view('global/losstime/view_detailLT_byproduct', $data);
		}
	}

	function view_detail_lt_bykategori($nama_kategori, $bulan)
	{
		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulans  = substr($tahun, 5, 2);
		$data = [
			'data'          				=> $this->data,
			'aktif'         				=> 'global',
			//'view_header_ng'   				=> $this->mr->tampil_detailNG_header_byproduct($kode_product,$bulan),
			'view_detail_lt_bykategori'   	=> $this->mr->tampil_detailLT_bykategori($nama_kategori, $bulan),
			'tahun'						=> $tahuns,
			'bulan'						=> $bulan
		];
		$this->load->view('global/losstime/view_detailLT_bykategori', $data);
	}

	function view_detail_lt_byproduct_byname($kode_product, $nama_kategori, $bulan)
	{
		$tahun = date('Y-m');
		$tahuns = substr($tahun, 0, 4);
		$bulans  = substr($tahun, 5, 2);
		$data = [
			'data'          				=> $this->data,
			'aktif'         				=> 'global',
			//'view_header_ng'   				=> $this->mr->tampil_detailNG_header_byproduct($kode_product,$bulan),
			'view_detail_lt_byproduct_byname'   	=> $this->mr->tampil_detailLT_byproduct_byname($kode_product, $nama_kategori, $bulan),
			'header'   				=> $this->mr->tampil_product($kode_product),
			'tahun'						=> $tahuns,
			'bulan'						=> $bulan
		];
		$this->load->view('global/losstime/view_detail_lt_byproduct_byname', $data);
	}

	// ------------------ Target CRUD endpoints ------------------
	public function target_create()
	{
		$this->mm->cek_login();
		$tahun = (int) $this->input->post('tahun');
		$bulan = (int) $this->input->post('bulan');
		$bagian = trim($this->input->post('bagian'));
		$target_percent = (float) $this->input->post('target_percent');

		if ($tahun <= 0 || $bulan < 1 || $bulan > 12 || $bagian === '' || $target_percent < 0 || $target_percent > 100) {
			return $this->output->set_content_type('application/json')->set_output(json_encode([
				'success' => false,
				'message' => 'Invalid input'
			]));
		}

		$created_by = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
		$id = $this->mt->create([
			'tahun' => $tahun,
			'bulan' => $bulan,
			'bagian' => $bagian,
			'target_percent' => $target_percent,
			'created_by' => $created_by,
			'updated_by' => $created_by,
		]);

		if ($id === false) {
			return $this->output->set_content_type('application/json')->set_output(json_encode([
				'success' => false,
				'message' => 'Create failed (possibly duplicate)'
			]));
		}

		// Fetch created row for client-side in-place update
		$created = $this->db->query(
			"SELECT id, tahun, bulan, bagian, target_percent, created_by, updated_by, created_at, updated_at
			 FROM dpr_injection_new.t_losstime_target WHERE id = ?",
			[(int)$id]
		)->row_array();

		return $this->output->set_content_type('application/json')->set_output(json_encode([
			'success' => true,
			'data' => $created
		]));
	}

	public function target_update($id)
	{
		$this->mm->cek_login();
		$target_percent = (float) $this->input->post('target_percent');
		if ($target_percent < 0 || $target_percent > 100) {
			return $this->output->set_content_type('application/json')->set_output(json_encode([
				'success' => false,
				'message' => 'Invalid target'
			]));
		}
		$updated_by = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
		$ok = $this->mt->update((int) $id, [
			'target_percent' => $target_percent,
			'updated_by' => $updated_by,
		]);
		$updated = $this->db->query(
			"SELECT id, tahun, bulan, bagian, target_percent, created_by, updated_by, created_at, updated_at
			 FROM dpr_injection_new.t_losstime_target WHERE id = ?",
			[(int)$id]
		)->row_array();

		return $this->output->set_content_type('application/json')->set_output(json_encode([
			'success' => (bool) $ok,
			'data' => $updated
		]));
	}

	public function target_delete($id)
	{
		$this->mm->cek_login();
		$ok = $this->mt->delete((int) $id);
		return $this->output->set_content_type('application/json')->set_output(json_encode([
			'success' => (bool) $ok
		]));
	}

	// function view_detail_lt($kategori,$bulan)  
	// {
	// 	$tahun = date('Y-m');
	//   $tahuns = substr($tahun,0,4);
	//   $bulans  = substr($tahun,5,2);
	//   $data = [
	//           'data'          		=> $this->data,
	//           'aktif'         		=> 'global',
	//           'view_detail_lt'   	=> $this->mr->tampil_detailLT($kategori,$bulan),
	//           'tahun'					=> $tahuns,
	//           'bulan'					=> $bulan
	//   	];
	//  	$this->load->view('global/losstime/view_detail_lt' , $data);
	// }

	function analis()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}

		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);
		$data = [
			'data'          		=> $this->data,
			'aktif'         		=> 'global',
			'data_production'   	=> $this->mr->tampil_production($tahun),
			'data_DefectGrafik' 	=> $this->mr->tampil_DefectGrafik($tahuns, $bulan),
			'data_EffGrafikYear' 	=> $this->mr->tampil_EffGrafikYear($tahuns),
			'maxPPM'   	    		=> $this->mr->maxPPM($tahuns, $bulan),
			'tanggal'				=> $tahun,
			'getNilai'	    		=> substr($tahun, 5, 2),
			'tahun'					=> $tahuns,
			'bulan'					=> $bulan

		];
		$this->load->view('global/analis/analis', $data);
	}


	//Performance Kanit
	public function kanit_perform()
	{
		if ($this->input->post('show')) {
			$dari = $this->input->post('tanggal_dari');
			$sampai = $this->input->post('tanggal_sampai');
			$line = $this->input->post('line');
			$data = [
				'data'          => $this->data,
				'aktif'         => 'global',
				'data_ng_lt_kanit'      => $this->mr->tampil_ng_lt_bykanit_filter($dari, $sampai, $line),
				// 'data_tabel'    => $this->mm->tampil_production('v_production_op',$dari , $sampai,$shift),
				'dari'          => $dari,
				'sampai'        => $sampai,
				'line'          => $line,
				'posisi'        => $this->session->userdata('posisi'),
			];
			$this->load->view('report/performance_kanit', $data);
		} else {
			$dari      = date('Y-m-d');
			$sampai    = date('Y-m-d');
			$line = 'D';
			$data = [
				'data'          => $this->data,
				'aktif'         => 'global',
				'data_ng_lt_kanit'      => $this->mr->tampil_ng_lt_bykanit_filter($dari, $sampai, $line),
				// 'data_tabel'    => $this->mm->tampil_production('v_production_op',$dari , $sampai , 'All'),
				'dari'          => $dari,
				'sampai'        => $sampai,
				'line'          => $line,
				'posisi'       => $this->session->userdata('posisi'),
			];
			$this->load->view('report/performance_kanit', $data);
		}
	}

	public function detail_ng($dari, $sampai, $kanit)
	{
		$data = [
			'data'          				=> $this->data,
			'aktif'         				=> 'global',
			//'view_header_ng'   				=> $this->mr->tampil_detailNG_header_byproduct($kode_product,$bulan),
			'ng_by_kanit'   	=> $this->mr->detail_ng_by_kanit($dari, $sampai, $kanit),
			'dari'						=> $dari,
			'sampai'					=> $sampai,
			'kanit'					=> $kanit
		];
		$this->load->view('report/detail_ng_by_kanit', $data);
	}

	public function detail_lt($dari, $sampai, $kanit)
	{
		$data = [
			'data'          				=> $this->data,
			'aktif'         				=> 'global',
			//'view_header_ng'   				=> $this->mr->tampil_detailNG_header_byproduct($kode_product,$bulan),
			'ng_by_kanit'   	=> $this->mr->detail_lt_by_kanit($dari, $sampai, $kanit),
			'dari'						=> $dari,
			'sampai'					=> $sampai,
			'kanit'					=> $kanit
		];
		$this->load->view('report/detail_lt_by_kanit', $data);
	}

	//Tambahan report akunting (Mba Widya)
	function report_akunting()
	{
		if ($this->input->post('show') == 'Show') {
			$tanggal_dari = $this->input->post('tanggal_dari');
			$tanggal_sampai = $this->input->post('tanggal_sampai');
			$data = [
				'data'          			=> $this->data,
				'aktif'         			=> 'global',
				'rep_akunting1'				=> $this->mr->rep_akunting_new($tanggal_dari, $tanggal_sampai),
				'rep_total'				=> $this->mr->rep_akunting_total($tanggal_dari, $tanggal_sampai),
				'dari'						=> $tanggal_dari,
				'sampai'					=> $tanggal_sampai
			];
		} else {
			$tanggal_dari = '';
			$tanggal_sampai = '';
			$data = [
				'data'          			=> $this->data,
				'aktif'         			=> 'global',
				'rep_akunting1'				=> $this->mr->rep_akunting_default(),
				'rep_total'				=> $this->mr->rep_akunting_total_default(),
				'dari'						=> $tanggal_dari,
				'sampai'					=> $tanggal_sampai
			];
		}
		$this->load->view('global/akunting/rep_akunting', $data);
	}

	function productivity_by_month()
	{
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('tahun');
		} else {
			$tahun = date('Y-m');
		}
		$tahuns = substr($tahun, 0, 4);
		$bulan  = substr($tahun, 5, 2);

		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'detail_productivity_by_month'   	=> $this->mr->read_productivity_by_month($bulan, $tahuns),
			'tahun'				=> $tahuns,
			'bulan'				=> $bulan
		];
		$this->load->view('global/productivity/productivity_by_month', $data);
	}

	function productivity_by_part_by_machine_by_month()
	{
		if ($this->input->post('show') == 'Show') {
			$bulan = $this->input->post('tahun');
		} else {
			$bulan = date('Y-m');
		}
		$bulans = substr($bulan, 0, 4);
		$bulan  = substr($bulan, 5, 2);
		echo '$bulans = '.$bulans.'<br>';
		echo '$bulan = '.$bulan;
		// die();
		$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'detail_productivity_by_month'   	=> $this->mr->read_productivity_by_part_by_machine_by_month($bulan, $bulans),
			'tahun'				=> $bulans,
			'bulan'				=> $bulan
		];
		$this->load->view('global/productivity/productivity_by_part_by_machine_by_month.php', $data);
	}



	function report_qty_by_cust()
	{
		// Get dates from POST or use defaults
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		
		// Validate dates - set defaults if empty
		if (empty($start_date)) {
			$start_date = date('Y-m-01');
		}
		if (empty($end_date)) {
			$end_date = date('Y-m-d');
		}
		
		// Ensure dates are in correct format
		$start_date = date('Y-m-d', strtotime($start_date));
		$end_date = date('Y-m-d', strtotime($end_date));
		
		$data = [
			'data'          			=> $this->data,
			'aktif'          			=> 'global',
			'rep_qty_by_cust'				=> $this->mr->rep_qtybycust_default($start_date, $end_date),
			'start_date'				=> $start_date,
			'end_date'					=> $end_date
		];

		$this->load->view('global/qty_by_cust/report_qty_by_cust', $data);
	}

	function report_qty_by_cust_yearly()
	{
		if ($this->input->post('show') == 'Show') {
			$date = $this->input->post('tahun');
			$tahun = $tahuns = substr($date, 0, 4);
			$bulan = $tahuns = substr($date, 5);
			$data = [
				'data'          			=> $this->data,
				'aktif'          			=> 'global',
				'rep_qty_by_cust'				=> $this->mr->rep_qtybycust_yearly($tahun),
				'tahun'						=> $tahun
			];
		} else {

			$tahun = date('Y');
			$data = [
				'data'          			=> $this->data,
				'aktif'          			=> 'global',
				'rep_qty_by_cust'				=> $this->mr->rep_qtybycust_yearly($tahun),
				'tahun'						=> $tahun
			];
		}

		$this->load->view('global/qty_by_cust/report_qty_by_cust_yearly', $data);
	}

	public function reportCT()
    {
        // Get year from POST or default to current year
        if ($this->input->post('show')) {
            $tahun = $this->input->post('tahun');
            if (empty($tahun)) {
                $tahun = date('Y');
            }
        } else {
            $tahun = date('Y');
        }

        // Get raw data from the correct model function
        $raw_data = $this->mr->get_ct_by_product_yearly($tahun);

        // Pivot data: group by product, then assign each month's CT values to ct_quo_1, ct_std_1, ct_aktual_1, ...
        $pivoted = array();
        foreach ($raw_data as $row) {
            $key = $row['kode_product'] . '|' . $row['nama_product'];
            $month = isset($row['month_num']) ? (int)$row['month_num'] : null;
            if (!isset($pivoted[$key])) {
                $pivoted[$key] = array(
                    'kode_product' => $row['kode_product'],
                    'nama_product' => $row['nama_product'],
                );
            }
            if ($month) {
                $pivoted[$key]['ct_quo_' . $month] = isset($row['ct_quo']) ? $row['ct_quo'] : null;
                $pivoted[$key]['ct_std_' . $month] = isset($row['ct_std']) ? $row['ct_std'] : null;
                $pivoted[$key]['ct_aktual_' . $month] = isset($row['ct_aktual']) ? $row['ct_aktual'] : null;
            }
        }

        $data = [
            'data'          => $this->data,
            'aktif'         => 'global',
            'data_ct_by_product_yearly' => array_values($pivoted),
            'tahun'         => $tahun,
            'posisi'        => $this->session->userdata('posisi'),
        ];
        $this->load->view('report/detail_ct_by_product', $data);
    }

	public function reportCTDetail()
    {
        $month_bucket = $this->input->get('month_bucket', TRUE);
        $kode_product = $this->input->get('kode_product', TRUE);

        // Validate input
        if (empty($month_bucket) || empty($kode_product)) {
            show_error('Missing or invalid parameters for detail view.', 400);
            return;
        }

        // Fetch data
        $data_detail = $this->mr->get_ct_by_product_monthly_detail($month_bucket, $kode_product);

        $data = [
            'data'          => $this->data,
            'aktif'         => 'global',
            'month_bucket'  => $month_bucket,
            'kode_product'  => $kode_product,
            'data_detail'   => $data_detail,
        ];
        $this->load->view('report/detail_ct_by_product_detail', $data);
    }

    public function cutting_tool()
{
    // Get filter values from POST or GET or set defaults
    $cutting_tool_id = $this->input->post('cutting_tool_id') ?: $this->input->get('cutting_tool_id');
    
    // Set date range
    if ($this->input->post('show') == 'Show') {
        $year_month = $this->input->post('tahun');
    } else {
        $year_month = $this->input->get('tahun') ?: date('Y-m');
    }
    
    // Parse year and month
    $year = substr($year_month, 0, 4);
    $month = substr($year_month, 5, 2);
    
    // Create date range (beginning and end of the month)
    $start_date = $year . '-' . $month . '-01';
    $end_date = date('Y-m-t', strtotime($start_date));
    
    // Add debug logging
    error_log("Cutting Tool Report - Date Range: $start_date to $end_date, Cutting Tool ID: " . ($cutting_tool_id ?: 'All'));
    
    // Get data based on filters
    $cutting_tool_summary = $this->mr->get_cutting_tool_summary($cutting_tool_id, $start_date, $end_date);
    
    // Get product list if a specific cutting tool is selected
    $product_list = null;
    if ($cutting_tool_id) {
        $product_list = $this->mr->get_cutting_tool_product_list($cutting_tool_id, $start_date, $end_date);
    }
    
    // Get all cutting tools for the dropdown
    $all_cutting_tools = $this->mr->get_all_cutting_tools();

    // Prepare data for the view
    $data = [
        'data' => $this->data,
        'aktif' => 'global',
        'cutting_tool_summary' => $cutting_tool_summary,
        'product_list' => $product_list,
        'all_cutting_tools' => $all_cutting_tools,
        'cutting_tool_id' => $cutting_tool_id,
        'year_month' => $year_month,
        'start_date' => $start_date,
        'end_date' => $end_date
    ];

    // Load the view
    $this->load->view('report/cutting_tool', $data);
}

    public function annual_productivity()
    {
        // Get year from POST or default to current year
        $year = $this->input->post('year') ?: date('Y');
        
        $data = [
            'data'          => $this->data,
            'aktif'         => 'global',
            'productivity'  => $this->mr->get_annual_productivity($year),
            'year'         => $year,
            'posisi'       => $this->session->userdata('posisi')
        ];
        
        $this->load->view('global/productivity/productivity_quartal', $data);
    }

	public function debug_cutting_tool_data()
{
    $year = $this->input->get('year') ?: date('Y');
    $month = $this->input->get('month') ?: date('m');
    
    // Check if the tables have any data at all
    $sql1 = "SELECT COUNT(*) as count FROM t_production_op_cutting_tools_usage";
    $result1 = $this->db->query($sql1)->row_array();
    
    $sql2 = "SELECT COUNT(*) as count FROM t_production_op WHERE YEAR(tanggal) = '$year' AND MONTH(tanggal) = '$month'";
    $result2 = $this->db->query($sql2)->row_array();
    
    // Check for joined data
    $sql3 = "
        SELECT 
            tpu.id_ct_usage,
            tpu.cutting_tools_id,
            tpu.id_production,
            op.id_production as op_id_production,
            op.tanggal,
            op.id_bom,
            op.qty_ok,
            op.qty_ng
        FROM 
            t_production_op_cutting_tools_usage tpu
        LEFT JOIN 
            t_production_op op ON tpu.id_production = op.id_production
        WHERE 
            YEAR(op.tanggal) = '$year' AND MONTH(op.tanggal) = '$month'
        LIMIT 10
    ";
    $result3 = $this->db->query($sql3)->result_array();
    
    // Output results
    echo "<h3>Debugging Cutting Tool Data</h3>";
    echo "<p>Total rows in t_production_op_cutting_tools_usage: " . $result1['count'] . "</p>";
    echo "<p>Total rows in t_production_op for $year-$month: " . $result2['count'] . "</p>";
    echo "<h4>Sample Joined Data:</h4>";
    echo "<pre>" . print_r($result3, true) . "</pre>";
}

/**
 * Regenerate PPM data for a specific year
 * This function is accessible via URL to trigger PPM data regeneration
 */
function regenerate_ppm_data($tahun = null)
{
    // Check if user is logged in and has admin privileges
    if (!isset($_SESSION['user_name']) || $_SESSION['posisi'] != 'Admin') {
        echo "Access denied. Admin privileges required.";
        return;
    }
    
    // If year not provided in URL, use current year
    if ($tahun === null) {
        $tahun = date('Y');
    }
    
    // Validate year parameter
    $tahun = (int)$tahun;
    if ($tahun < 2020 || $tahun > 2030) {
        echo "Invalid year parameter. Please provide a year between 2020 and 2030.";
        return;
    }
    
    echo "<h2>Regenerating PPM Data for Year: $tahun</h2>";
    
    // Check if PPM data already exists for this year
    $exists = $this->mr->check_ppm_data_exists($tahun);
    if ($exists) {
        echo "<p>PPM data already exists for year $tahun. It will be regenerated.</p>";
    } else {
        echo "<p>No existing PPM data found for year $tahun. New data will be generated.</p>";
    }
    
    // Regenerate the data
    try {
        $affected_rows = $this->mr->regenerate_ppm_data($tahun);
        echo "<p style='color: green;'>✓ Successfully regenerated PPM data for $tahun</p>";
        echo "<p>Number of products processed: $affected_rows</p>";
        
        // Check if data was actually created
        $new_exists = $this->mr->check_ppm_data_exists($tahun);
        if ($new_exists) {
            echo "<p style='color: green;'>✓ PPM data verification successful</p>";
            
            // Check specifically for November data if 2025
            if ($tahun == 2025) {
                $ppm_total = $this->mr->total_prod_qty_and_ppm(2025);
                if ($ppm_total && $ppm_total->num_rows() > 0) {
                    $row = $ppm_total->row_array();
                    if (isset($row['total_prod11']) && $row['total_prod11'] > 0) {
                        echo "<p style='color: green;'>✓ November 2025 data is now available (Total Production: {$row['total_prod11']})</p>";
                    } else {
                        echo "<p style='color: orange;'>⚠ November 2025 data has been processed but shows zero production</p>";
                    }
                }
            }
        } else {
            echo "<p style='color: red;'>✗ PPM data verification failed</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error regenerating PPM data: " . $e->getMessage() . "</p>";
    }
    
    // Add a link back to the dashboard
    echo "<p><a href='" . base_url() . "c_new/home'>Back to Dashboard</a></p>";
}

  

    /**
     * Export enhanced Excel format using PHPExcel library with 2 sheets
     * Sheet 1: Cycle Time data (existing functionality)
     * Sheet 2: Average Nett productivity values
     */
    public function export_custom_excel()
    {
        // Validate year parameter
        $year = $this->input->post('year') ?: $this->input->get('year');
        
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }
        
        // Get data from model for all three sheets
        $ct_data_result = $this->mr->get_custom_excel_export_data($year);
        $nett_data_result = $this->mr->get_average_nett_excel_data($year);
        $gross_data_result = $this->mr->get_average_gross_excel_data($year);
        
        // Debug: Log the first row of data to see what we're getting
        if ($ct_data_result->num_rows() > 0) {
            $first_row = $ct_data_result->row_array();
            log_message('debug', 'First CT data row: ' . print_r($first_row, true));
        }
        
        if ($ct_data_result->num_rows() == 0 && $nett_data_result->num_rows() == 0 && $gross_data_result->num_rows() == 0) {
            $this->session->set_flashdata('error', 'No data found for year ' . $year);
            redirect('c_report/productivity_detail_by_part_by_month');
            return;
        }
        
        // Save current error reporting level and suppress deprecation warnings
        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        
        try {
            // Load the PHPExcel library
            $this->load->library('Excel');
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("DPR System")
                                     ->setTitle("Productivity Report " . $year)
                                     ->setSubject("Annual Productivity Analysis")
                                     ->setDescription("Annual productivity report with cycle time analysis, average nett, and average gross values for year " . $year);
            
            // ========== SHEET 1: CYCLE TIME DATA ==========
            // Set active sheet to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet1 = $objPHPExcel->getActiveSheet();
            $sheet1->setTitle('Cycle Time ' . $year);
            
            // Define headers for sheet 1
            $headers = [
                'YY', 'Product ID', 'Product Name', 'Cycle Time Standard', 'Cycle Time Quote', 
                'Tool', 
                '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'
            ];
            
            // Set column headers (starting from A1)
            $col = 'A';
            foreach ($headers as $header) {
                $sheet1->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Style the header row
            $headerStyle = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '4472C4'),
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            
            // Apply header style to all header columns
            $sheet1->getStyle('A1:S1')->applyFromArray($headerStyle);
            
            // Write CT data rows
            $rowNum = 2;
            foreach ($ct_data_result->result_array() as $data) {
                $col = 'A';
                
                // Add basic data
                $sheet1->setCellValue($col++ . $rowNum, $data['YY']);
                $sheet1->setCellValue($col++ . $rowNum, $data['Product_ID']);
                $sheet1->setCellValue($col++ . $rowNum, $data['Product_Name']);
                
                // Add numeric fields with proper formatting
                $cycleTimeStandard = is_numeric($data['Cycle_Time_Standard']) ? floatval($data['Cycle_Time_Standard']) : 0;
                $cycleTimeQuote = is_numeric($data['Cycle_Time_Quote']) ? floatval($data['Cycle_Time_Quote']) : 0;
                $toolValue = $data['Tool'] == '-' ? '-' : strval($data['Tool']);
                
                $sheet1->setCellValue($col++ . $rowNum, $cycleTimeStandard > 0 ? $cycleTimeStandard : 0);
                $sheet1->setCellValue($col++ . $rowNum, $cycleTimeQuote > 0 ? $cycleTimeQuote : 0);
                $sheet1->setCellValue($col++ . $rowNum, $toolValue);
                
                // Add monthly CT actual data (01-12)
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month);
                    $value = isset($data[$monthKey]) ? $data[$monthKey] : 0;
                    
                    if (is_numeric($value) && $value > 0) {
                        // Convert to integer to ensure whole numbers
                        $sheet1->setCellValue($col++ . $rowNum, intval(round($value)));
                    } else {
                        $sheet1->setCellValue($col++ . $rowNum, 0);
                    }
                }
                
                $rowNum++;
            }
            
            // ========== SHEET 2: AVERAGE NETT DATA ==========
            // Create second sheet
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(1);
            $sheet2 = $objPHPExcel->getActiveSheet();
            $sheet2->setTitle('Average Nett ' . $year);
            
            // Set column headers (same as sheet 1)
            $col = 'A';
            foreach ($headers as $header) {
                $sheet2->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style to sheet 2
            $sheet2->getStyle('A1:S1')->applyFromArray($headerStyle);
            
            // Write average nett data rows
            $rowNum = 2;
            foreach ($nett_data_result->result_array() as $data) {
                $col = 'A';
                
                // Add basic data
                $sheet2->setCellValue($col++ . $rowNum, $data['YY']);
                $sheet2->setCellValue($col++ . $rowNum, $data['Product_ID']);
                $sheet2->setCellValue($col++ . $rowNum, $data['Product_Name']);
                
                // Add numeric fields with proper formatting
                $cycleTimeStandard = is_numeric($data['Cycle_Time_Standard']) ? floatval($data['Cycle_Time_Standard']) : 0;
                $cycleTimeQuote = is_numeric($data['Cycle_Time_Quote']) ? floatval($data['Cycle_Time_Quote']) : 0;
                $toolValue = $data['Tool'] == '-' ? '-' : strval($data['Tool']);
                
                $sheet2->setCellValue($col++ . $rowNum, $cycleTimeStandard > 0 ? $cycleTimeStandard : 0);
                $sheet2->setCellValue($col++ . $rowNum, $cycleTimeQuote > 0 ? $cycleTimeQuote : 0);
                $sheet2->setCellValue($col++ . $rowNum, $toolValue);
                
                // Add monthly average nett data (01-12)
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month);
                    $value = isset($data[$monthKey]) ? $data[$monthKey] : 0;
                    
                    if (is_numeric($value) && $value > 0) {
                        // Convert to integer to ensure whole numbers
                        $sheet2->setCellValue($col++ . $rowNum, intval(round($value)));
                    } else {
                        $sheet2->setCellValue($col++ . $rowNum, 0);
                    }
                }
                
                $rowNum++;
            }
            
            // ========== SHEET 3: AVERAGE GROSS DATA ==========
            // Create third sheet
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(2);
            $sheet3 = $objPHPExcel->getActiveSheet();
            $sheet3->setTitle('Average Gross ' . $year);
            
            // Set column headers (same as sheets 1 & 2)
            $col = 'A';
            foreach ($headers as $header) {
                $sheet3->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style to sheet 3
            $sheet3->getStyle('A1:S1')->applyFromArray($headerStyle);
            
            // Write average gross data rows
            $rowNum = 2;
            foreach ($gross_data_result->result_array() as $data) {
                $col = 'A';
                
                // Add basic data
                $sheet3->setCellValue($col++ . $rowNum, $data['YY']);
                $sheet3->setCellValue($col++ . $rowNum, $data['Product_ID']);
                $sheet3->setCellValue($col++ . $rowNum, $data['Product_Name']);
                
                // Add numeric fields with proper formatting
                $cycleTimeStandard = is_numeric($data['Cycle_Time_Standard']) ? floatval($data['Cycle_Time_Standard']) : 0;
                $cycleTimeQuote = is_numeric($data['Cycle_Time_Quote']) ? floatval($data['Cycle_Time_Quote']) : 0;
                $toolValue = $data['Tool'] == '-' ? '-' : strval($data['Tool']);
                
                $sheet3->setCellValue($col++ . $rowNum, $cycleTimeStandard > 0 ? $cycleTimeStandard : 0);
                $sheet3->setCellValue($col++ . $rowNum, $cycleTimeQuote > 0 ? $cycleTimeQuote : 0);
                $sheet3->setCellValue($col++ . $rowNum, $toolValue);
                
                // Add monthly average gross data (01-12)
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month);
                    $value = isset($data[$monthKey]) ? $data[$monthKey] : 0;
                    
                    if (is_numeric($value) && $value > 0) {
                        // Convert to integer to ensure whole numbers
                        $sheet3->setCellValue($col++ . $rowNum, intval(round($value)));
                    } else {
                        $sheet3->setCellValue($col++ . $rowNum, 0);
                    }
                }
                
                $rowNum++;
            }
            
            // Set active sheet back to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $this->autosize_excel_columns($objPHPExcel);
            
            // Set headers for download
            $filename = 'Productivity_Report_' . $year . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Pragma: public');
            
            // Create writer and output to browser
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
            
        } catch (Exception $e) {
            // Restore error reporting in case of exception
            error_reporting($old_error_reporting);
            log_message('error', 'Excel export failed: ' . $e->getMessage());
            
            // Show error message
            $this->session->set_flashdata('error', 'Excel export failed: ' . $e->getMessage() . '. Please try again or contact support.');
            redirect('c_report/productivity_quartal');
            return;
        } finally {
            // Always restore error reporting
            error_reporting($old_error_reporting);
        }
    }

    /**
     * Export production quantity data to Excel with 3 sheets
     * Sheet 1: Total Production quantity
     * Sheet 2: Total OK quantity
     * Sheet 3: Total NG quantity
     */
    public function export_production_qty_excel()
    {
        // Validate year parameter
        $year = $this->input->post('year') ?: $this->input->get('year');
        
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }
        
        // Get data from model for all three sheets
        $total_prod_data_result = $this->mr->get_production_qty_excel_data($year);
        $ok_data_result = $this->mr->get_ok_qty_excel_data($year);
        $ng_data_result = $this->mr->get_ng_qty_excel_data($year);
        
        if ($total_prod_data_result->num_rows() == 0 && $ok_data_result->num_rows() == 0 && $ng_data_result->num_rows() == 0) {
            $this->session->set_flashdata('error', 'No data found for year ' . $year);
            redirect('c_report/production_qty_quartal');
            return;
        }
        
        // Save current error reporting level and suppress deprecation warnings
        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        
        try {
            // Load the PHPExcel library
            $this->load->library('Excel');
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("DPR System")
                                     ->setTitle("Production Quantity Report " . $year)
                                     ->setSubject("Annual Production Quantity Analysis")
                                     ->setDescription("Annual production quantity report with total, OK, and NG values for year " . $year);
            
            // Define headers
            $headers = [
                'Product ID', 'Product Name', 
                '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'
            ];
            
            // Style the header row
            $headerStyle = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '4472C4'),
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            
            // ========== SHEET 1: TOTAL PRODUCTION DATA ==========
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet1 = $objPHPExcel->getActiveSheet();
            $sheet1->setTitle('Total Production ' . $year);
            
            // Set column headers
            $col = 'A';
            foreach ($headers as $header) {
                $sheet1->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style
            $sheet1->getStyle('A1:N1')->applyFromArray($headerStyle);
            
            // Write data rows
            $rowNum = 2;
            foreach ($total_prod_data_result->result_array() as $data) {
                $col = 'A';
                $sheet1->setCellValue($col++ . $rowNum, $data['Product_ID']);
                $sheet1->setCellValue($col++ . $rowNum, $data['Product_Name']);
                
                // Add monthly data (01-12)
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month);
                    $value = isset($data[$monthKey]) ? $data[$monthKey] : 0;
                    $sheet1->setCellValue($col++ . $rowNum, is_numeric($value) ? intval($value) : 0);
                }
                $rowNum++;
            }
            
            // ========== SHEET 2: TOTAL OK DATA ==========
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(1);
            $sheet2 = $objPHPExcel->getActiveSheet();
            $sheet2->setTitle('Total OK ' . $year);
            
            // Set column headers (same as sheet 1)
            $col = 'A';
            foreach ($headers as $header) {
                $sheet2->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style
            $sheet2->getStyle('A1:N1')->applyFromArray($headerStyle);
            
            // Write OK data rows
            $rowNum = 2;
            foreach ($ok_data_result->result_array() as $data) {
                $col = 'A';
                $sheet2->setCellValue($col++ . $rowNum, $data['Product_ID']);
                $sheet2->setCellValue($col++ . $rowNum, $data['Product_Name']);
                
                // Add monthly OK data (01-12)
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month);
                    $value = isset($data[$monthKey]) ? $data[$monthKey] : 0;
                    $sheet2->setCellValue($col++ . $rowNum, is_numeric($value) ? intval($value) : 0);
                }
                $rowNum++;
            }
            
            // ========== SHEET 3: TOTAL NG DATA ==========
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(2);
            $sheet3 = $objPHPExcel->getActiveSheet();
            $sheet3->setTitle('Total NG ' . $year);
            
            // Set column headers (same as sheets 1 & 2)
            $col = 'A';
            foreach ($headers as $header) {
                $sheet3->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style to sheet 3
            $sheet3->getStyle('A1:N1')->applyFromArray($headerStyle);
            
            // Write NG data rows
            $rowNum = 2;
            foreach ($ng_data_result->result_array() as $data) {
                $col = 'A';
                $sheet3->setCellValue($col++ . $rowNum, $data['Product_ID']);
                $sheet3->setCellValue($col++ . $rowNum, $data['Product_Name']);
                
                // Add monthly NG data (01-12)
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month);
                    $value = isset($data[$monthKey]) ? $data[$monthKey] : 0;
                    $sheet3->setCellValue($col++ . $rowNum, is_numeric($value) ? intval($value) : 0);
                }
                $rowNum++;
            }
            
            // Set active sheet back to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $this->autosize_excel_columns($objPHPExcel);
            
            // Set headers for download
            $filename = 'Production_Qty_Report_' . $year . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Pragma: public');
            
            // Create writer and output to browser
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
            
        } catch (Exception $e) {
            // Restore error reporting in case of exception
            error_reporting($old_error_reporting);
            log_message('error', 'Production Qty Excel export failed: ' . $e->getMessage());
            
            // Show error message
            $this->session->set_flashdata('error', 'Excel export failed: ' . $e->getMessage() . '. Please try again or contact support.');
            redirect('c_report/production_qty_quartal');
            return;
        } finally {
            // Always restore error reporting
            error_reporting($old_error_reporting);
        }
    }

    /**
     * Export machine efficiency data to Excel with 2 sheets
     * Sheet 1: Machine efficiency hours by tonnage
     * Sheet 2: Machine efficiency percentage by tonnage
     */
    public function export_machine_efficiency_excel()
    {
        // Validate year parameter
        $year = $this->input->post('year') ?: $this->input->get('year');
        
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }
        
        // Get data from model for both sheets
        $hours_data_result = $this->mr->get_machine_efficiency_excel_data_hours($year);
        $percentage_data_result = $this->mr->get_machine_efficiency_excel_data_percentage($year);
        
        if ($hours_data_result->num_rows() == 0 && $percentage_data_result->num_rows() == 0) {
            $this->session->set_flashdata('error', 'No data found for year ' . $year);
            redirect('c_report/efesiencyMesin');
            return;
        }
        
        // Save current error reporting level and suppress deprecation warnings
        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        
        try {
            // Load the PHPExcel library
            $this->load->library('Excel');
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("DPR System")
                                     ->setTitle("Machine Efficiency Report " . $year)
                                     ->setSubject("Machine Efficiency Analysis")
                                     ->setDescription("Machine efficiency report with hours and percentages by tonnage for year " . $year);
            
            // Define headers for Sheet 1 (Hours)
            $headers_hours = [
                'YY-MM', 'Customer', 'Mo', 'name', 'Total Of SumOfMach Eff Hr', 
                '40', '55', '60', '80', '90', '120', '160', '200'
            ];
            
            // Define headers for Sheet 2 (Percentages)
            $headers_percentage = [
                'YY-MM', 'Customer', 'Total Of Mach Eff Hr%%', 
                '40', '55', '60', '80', '90', '120', '160', '200'
            ];
            
            // Style the header row
            $headerStyle = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '4472C4'),
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            
            // ========== SHEET 1: HOURS DATA ==========
            // Set active sheet to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet1 = $objPHPExcel->getActiveSheet();
            $sheet1->setTitle('Efficiency Hours ' . $year);
            
            // Set column headers
            $col = 'A';
            foreach ($headers_hours as $header) {
                $sheet1->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style
            $sheet1->getStyle('A1:M1')->applyFromArray($headerStyle);
            
            // Write data rows
            $rowNum = 2;
            foreach ($hours_data_result->result_array() as $data) {
                $col = 'A';
                $sheet1->setCellValue($col++ . $rowNum, $data['YY_MM']);
                $sheet1->setCellValue($col++ . $rowNum, $data['Customer']);
                $sheet1->setCellValue($col++ . $rowNum, $data['Mo']);
                $sheet1->setCellValue($col++ . $rowNum, $data['name']);
                $sheet1->setCellValue($col++ . $rowNum, is_numeric($data['total_of_sumofmach_eff_hr']) ? floatval($data['total_of_sumofmach_eff_hr']) : 0);
                
                // Add tonnage data
                foreach (['40', '55', '60', '80', '90', '120', '160', '200'] as $tonnage) {
                    $value = isset($data[$tonnage]) ? $data[$tonnage] : 0;
                    $sheet1->setCellValue($col++ . $rowNum, is_numeric($value) ? floatval($value) : 0);
                }
                
                $rowNum++;
            }
            
            // ========== SHEET 2: PERCENTAGE DATA ==========
            // Create second sheet
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(1);
            $sheet2 = $objPHPExcel->getActiveSheet();
            $sheet2->setTitle('Efficiency % ' . $year);
            
            // Set column headers
            $col = 'A';
            foreach ($headers_percentage as $header) {
                $sheet2->setCellValue($col . '1', $header);
                $col++;
            }
            
            // Apply header style to sheet 2
            $sheet2->getStyle('A1:K1')->applyFromArray($headerStyle);
            
            // Write data rows
            $rowNum = 2;
            foreach ($percentage_data_result->result_array() as $data) {
                $col = 'A';
                $sheet2->setCellValue($col++ . $rowNum, $data['YY_MM']);
                $sheet2->setCellValue($col++ . $rowNum, $data['Customer']);
                $sheet2->setCellValue($col++ . $rowNum, is_numeric($data['total_of_sumofmach_eff_hr_percent']) ? floatval($data['total_of_sumofmach_eff_hr_percent']) : 0);
                
                // Add tonnage percentage data
                foreach (['40', '55', '60', '80', '90', '120', '160', '200'] as $tonnage) {
                    $value = isset($data[$tonnage]) ? $data[$tonnage] : 0;
                    $sheet2->setCellValue($col++ . $rowNum, is_numeric($value) ? floatval($value) : 0);
                }
                
                $rowNum++;
            }
            
            // Set active sheet back to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $this->autosize_excel_columns($objPHPExcel);
            
            // Set headers for download
            $filename = 'Machine_Efficiency_Report_' . $year . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Pragma: public');
            
            // Create writer and output to browser
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
            
        } catch (Exception $e) {
            // Restore error reporting in case of exception
            error_reporting($old_error_reporting);
            log_message('error', 'Machine Efficiency Excel export failed: ' . $e->getMessage());
            
            // Show error message
            $this->session->set_flashdata('error', 'Excel export failed: ' . $e->getMessage() . '. Please try again or contact support.');
            redirect('c_report/efesiencyMesin');
            return;
        } finally {
            // Always restore error reporting
            error_reporting($old_error_reporting);
        }
    }

    /**
     * Export Loss Time By Kategori per Year to Excel
     */
    public function export_losstime_by_kategori_year_excel()
    {
        $year = $this->input->post('year') ?: $this->input->get('year');
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }

        $result = $this->mr->tampil_DefectGrafikYear($year);
        if (!$result || $result->num_rows() === 0) {
            $this->session->set_flashdata('error', 'No Loss Time data found for year ' . $year);
            redirect('c_report/losstime');
            return;
        }

        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        try {
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                ->setCreator("DPR System")
                ->setTitle("Loss Time By Kategori " . $year)
                ->setSubject("Loss Time By Kategori per Year")
                ->setDescription("Loss Time By Kategori per Year for " . $year);

            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->setTitle('Loss Time ' . $year);

            $headers = ['Bulan', 'Total Loss Time (Hours)', 'Total Mc. Use (Hours)', 'Persentase Loss Time (%)'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '4472C4'),
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

            $rowNum = 2;
            foreach ($result->result_array() as $row) {
                $sheet->setCellValue('A' . $rowNum, $row['bulan']);
                $sheet->setCellValue('B' . $rowNum, round($row['totalLT'], 1));
                $sheet->setCellValue('C' . $rowNum, round($row['total_machine_use'], 1));
                $sheet->setCellValue('D' . $rowNum, round($row['persen_lt'], 2));
                $rowNum++;
            }

            $this->autosize_excel_columns($objPHPExcel);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="LossTime_By_Kategori_' . $year . '.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            error_reporting($old_error_reporting);
            log_message('error', 'Loss Time Excel export failed: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Excel export failed: ' . $e->getMessage());
            redirect('c_report/losstime');
            return;
        } finally {
            error_reporting($old_error_reporting);
        }
    }

    /**
     * Export 7 Data & 7 Table to Excel (two sheets)
     */
    public function export_seven_data_and_table_excel()
    {
        $year = $this->input->post('year') ?: $this->input->get('year');
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }

        $dataRows = $this->mr->get_7table_data_from_year($year);
        $summaryRows = $this->mr->get_7_table_summary_data($year);

        if ((!$dataRows || $dataRows->num_rows() === 0) && (!$summaryRows || $summaryRows->num_rows() === 0)) {
            $this->session->set_flashdata('error', 'No 7 Data / 7 Table records found for year ' . $year);
            redirect('c_report/seven_data_and_table');
            return;
        }

        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        try {
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $headerStyle = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '4472C4'),
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            // Sheet 1: 7 Data
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet1 = $objPHPExcel->getActiveSheet();
            $sheet1->setTitle('7 Data ' . $year);
            $headersData = [
                'No', 'Mesin', 'Month', 'SumOfWH', 'SumOfOT', 'Total Hour Std', 'MachEffHour', 'TotalST',
                'No Material', 'No Packing', 'Material Problem', 'Adjust Parameter', 'Daily Checklist',
                'Pre-heating Material', 'Cleaning Hopper Barrel', 'Setup Mold', 'Setup Parameter Machine',
                'IPQC Inspection', 'Machine', 'Hopper Dryer', 'Robot', 'MTC', 'Chiller', 'Compressor',
                'Listrik', 'Overhole', 'QC Lolos', 'Mold Problem', 'Trial', 'Setup Awal Produksi', 'MCH Eff Percentage'
            ];
            $col = 'A';
            foreach ($headersData as $header) {
                $sheet1->setCellValue($col . '1', $header);
                $col++;
            }
            $sheet1->getStyle('A1:AE1')->applyFromArray($headerStyle);

            $rowNum = 2;
            $counter = 1;
            if ($dataRows) {
                foreach ($dataRows->result_array() as $row) {
                    $col = 'A';
                    $sheet1->setCellValue($col++ . $rowNum, $counter++);
                    $sheet1->setCellValue($col++ . $rowNum, $row['no_mesin']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['month']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['SumOfWH']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['SumOfOT']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['total_hour_std']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['MachEffHour']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['TotalST']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['no_material']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['no_packing']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['material_problem']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['adjust_parameter']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['daily_checklist']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['pre_heating_material']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['cleaning_hopper_barrel']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['setup_mold']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['setup_parameter_machine']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['ipqc_inspection']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['machine']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['hopper_dryer']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['robot']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['mtc']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['chiller']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['compressor']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['listrik']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['overhole']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['qc_lolos']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['mold_problem']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['trial']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['setup_awal_produksi']);
                    $sheet1->setCellValue($col++ . $rowNum, $row['mch_eff_percentage']);
                    $rowNum++;
                }
            }

            // Sheet 2: 7 Table summary
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(1);
            $sheet2 = $objPHPExcel->getActiveSheet();
            $sheet2->setTitle('7 Table ' . $year);

            $headersSummary = ['Mach', 'Total Of Mach Eff Hr', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
            $col = 'A';
            foreach ($headersSummary as $header) {
                $sheet2->setCellValue($col . '1', $header);
                $col++;
            }
            $sheet2->getStyle('A1:N1')->applyFromArray($headerStyle);

            $rowNum = 2;
            if ($summaryRows) {
                foreach ($summaryRows->result_array() as $row) {
                    $sheet2->setCellValue('A' . $rowNum, $row['Mach']);
                    $sheet2->setCellValue('B' . $rowNum, $row['total_mach_eff_hr']);
                    $sheet2->setCellValue('C' . $rowNum, $row['01']);
                    $sheet2->setCellValue('D' . $rowNum, $row['02']);
                    $sheet2->setCellValue('E' . $rowNum, $row['03']);
                    $sheet2->setCellValue('F' . $rowNum, $row['04']);
                    $sheet2->setCellValue('G' . $rowNum, $row['05']);
                    $sheet2->setCellValue('H' . $rowNum, $row['06']);
                    $sheet2->setCellValue('I' . $rowNum, $row['07']);
                    $sheet2->setCellValue('J' . $rowNum, $row['08']);
                    $sheet2->setCellValue('K' . $rowNum, $row['09']);
                    $sheet2->setCellValue('L' . $rowNum, $row['10']);
                    $sheet2->setCellValue('M' . $rowNum, $row['11']);
                    $sheet2->setCellValue('N' . $rowNum, $row['12']);
                    $rowNum++;
                }
            }

            $this->autosize_excel_columns($objPHPExcel);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="7Data_7Table_' . $year . '.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            error_reporting($old_error_reporting);
            log_message('error', '7 Data & 7 Table Excel export failed: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Excel export failed: ' . $e->getMessage());
            redirect('c_report/seven_data_and_table');
            return;
        } finally {
            error_reporting($old_error_reporting);
        }
    }

    public function export_machine_use_excel()
    {
        $year = $this->input->post('year') ?: $this->input->get('year');
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }
        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        try {
            $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'dpr_mu_' . uniqid();
            if (!@mkdir($tempDir, 0700, true)) {
                error_reporting($old_error_reporting);
                show_error('Unable to create temp directory.');
                return;
            }
            $result = $this->build_machine_use_excel_zip($year, $tempDir, date('Y-m-d-H-i'));
            // Download the main Machine Use Chart file (last entry)
            $mainFile = end($result);
            if (file_exists($mainFile['path'])) {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $mainFile['name'] . '"');
                header('Cache-Control: max-age=0');
                readfile($mainFile['path']);
                foreach ($result as $f) { @unlink($f['path']); }
                @rmdir($tempDir);
            }
        } catch (Exception $e) {
            error_reporting($old_error_reporting);
            log_message('error', 'Machine Use Excel export failed: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Excel export failed: ' . $e->getMessage());
            redirect('c_report');
            return;
        } finally {
            error_reporting($old_error_reporting);
        }
    }

    /**
     * Build and stream all five Excel reports as a single ZIP to minimize popups.
     * Reports included:
     *  1. Productivity Chart (Std/Nett/Gross + Total Net + Total Gross)
     *  2. Production Qty & PPM (Total/OK/NG + Total with PPM)
     *  3. Efficiency Machine Chart (7-Data + 7-Table + Machine Cap by tonnage)
     *  4. Stop Time Machine Chart (Table + 7-Data + 7-Table)
     *  5. Machine Use Chart (Rate + 7-Data + Total by Customer + Total by Ton by Customer)
     *  Bonus: 7 Data & 7 Table (2 sheets)
     */
    public function export_all_reports_zip()
    {
        $year = $this->input->post('year') ?: $this->input->get('year');
        if (!$year || !is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = date('Y');
        }

        $timestamp = date('Y-m-d-H-i');

        // Suppress deprecation notices from legacy PHPExcel library during export
        $old_error_reporting = error_reporting();
        error_reporting($old_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'dpr_reports_' . uniqid();
        if (!@mkdir($tempDir, 0700, true)) {
            error_reporting($old_error_reporting);
            show_error('Unable to create temporary export directory.');
            return;
        }

        $files = [];
        try {
            $prodFiles = $this->build_productivity_excel_zip($year, $tempDir, $timestamp);
            $files = array_merge($files, $prodFiles);
            $qtyFiles = $this->build_production_qty_excel_zip($year, $tempDir, $timestamp);
            $files = array_merge($files, $qtyFiles);
            $effFiles = $this->build_machine_eff_excel_zip($year, $tempDir, $timestamp);
            $files = array_merge($files, $effFiles);
            $muFiles = $this->build_machine_use_excel_zip($year, $tempDir, $timestamp);
            $files = array_merge($files, $muFiles ?: []);

            // Create ZIP
            $zipPath = $tempDir . DIRECTORY_SEPARATOR . 'All_Reports_' . $timestamp . '.zip';
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new Exception('Unable to create ZIP archive.');
            }
            foreach ($files as $file) {
                if (file_exists($file['path'])) {
                    $zip->addFile($file['path'], $file['name']);
                }
            }
            $zip->close();

            // Stream ZIP — discard any stray output (PHP notices, PHPExcel deprecations)
            // that would corrupt the binary download
            if (ob_get_length()) { ob_clean(); }
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="All_Reports_' . $timestamp . '.zip"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            exit;
        } catch (Exception $e) {
            log_message('error', 'ZIP export failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $this->session->set_flashdata('error', 'Export failed: ' . $e->getMessage());
            redirect('c_new/home');
        } finally {
            // Restore error reporting
            error_reporting($old_error_reporting);

            // Clean temp files
            foreach ($files as $file) {
                if (isset($file['path']) && file_exists($file['path'])) {
                    @unlink($file['path']);
                }
            }
            if (isset($zipPath) && file_exists($zipPath)) {
                @unlink($zipPath);
            }
            if (file_exists($tempDir)) {
                @rmdir($tempDir);
            }
        }
    }

    /** Helpers for ZIP builds (lightweight versions of existing exports) */

    private function write_header_rows($sheet, $row, $titles)
    {
        foreach ($titles as $title) {
            $sheet->setCellValue('A' . $row, $title);
            $row++;
        }
        return $row;
    }

    private function autosize_excel_columns($excel)
    {
        $MIN_WIDTH   = 8;     // minimum column width (chars)
        $MAX_WIDTH   = 50;    // cap so long text wraps instead of stretching the sheet
        $CHAR_FACTOR = 1.15;  // PHPExcel column-width unit ≈ 1 char of Calibri 11

        foreach ($excel->getAllSheets() as $sheet) {
            $highestCol = $sheet->getHighestColumn();
            $highestRow = $sheet->getHighestRow();
            $colCount   = PHPExcel_Cell::columnIndexFromString($highestCol);

            // Auto-detect data start row: first row where at least one cell is numeric
            // (skips title/preamble rows that are pure text)
            $dataStart = 1;
            for ($r = 1; $r <= min($highestRow, 10); $r++) {
                for ($c = 1; $c <= $colCount; $c++) {
                    $v = $sheet->getCellByColumnAndRow($c - 1, $r)->getValue();
                    if (is_numeric($v) && $v !== '' && $v !== null) {
                        $dataStart = $r;
                        break 2;
                    }
                }
            }

            for ($c = 1; $c <= $colCount; $c++) {
                $colLetter = PHPExcel_Cell::stringFromColumnIndex($c - 1);
                $maxLen = $MIN_WIDTH;
                for ($r = $dataStart; $r <= $highestRow; $r++) {
                    $val = $sheet->getCellByColumnAndRow($c - 1, $r)->getValue();
                    if ($val === null || $val === '') continue;
                    $len = mb_strlen((string)$val);
                    if ($len > $maxLen) $maxLen = $len;
                }
                $width = min($MAX_WIDTH, $maxLen * $CHAR_FACTOR + 2);
                $colDim = $sheet->getColumnDimension($colLetter);
                $colDim->setAutoSize(false);
                $colDim->setWidth($width);

                // Enable wrap text on cells that exceed the cap
                if ($maxLen > $MAX_WIDTH) {
                    $sheet->getStyle($colLetter . $dataStart . ':' . $colLetter . $highestRow)
                          ->getAlignment()->setWrapText(true);
                }
            }
        }
    }

    private function build_productivity_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');
        $std = $this->mr->get_productivity_full_excel_data($year);
        $nett = $this->mr->get_productivity_nett_excel_data($year);
        $gross = $this->mr->get_productivity_gross_excel_data($year);
        if ($std->num_rows() == 0 && $nett->num_rows() == 0 && $gross->num_rows() == 0) {
            throw new Exception('No productivity data for ' . $year);
        }

        $baseHeaders = ['YY','Product ID','Product Name','Max SPM-Std','Max SPM Std2','Tool','*Mm Chk*','Min SPM-Set','Max SPM-Set'];
        for ($m=1; $m<=12; $m++) { $baseHeaders[] = sprintf('%d.0', $m); }

        // Helper to write productivity data rows (skips rows with no monthly data)
        $writeProdRows = function($sheet, $data, $headers, $startRow, $useStdForMonths = false) {
            $r = $startRow;
            foreach ($data->result_array() as $row) {
                // Skip rows where all monthly columns are empty (no production data)
                $hasData = false;
                for ($m = 1; $m <= 12; $m++) {
                    $mk = sprintf('%02d', $m);
                    if (isset($row[$mk]) && $row[$mk] !== null && $row[$mk] !== '' && $row[$mk] != 0) {
                        $hasData = true;
                        break;
                    }
                }
                if (!$hasData) continue;

                $col = 'A';
                foreach ($headers as $h) {
                    $key = str_replace('.0', '', $h);
                    $key = str_replace('-', '_', $key);
                    $key = str_replace(' ', '_', $key);
                    if (is_numeric($key) && intval($key) >= 1 && intval($key) <= 12) {
                        $key = sprintf('%02d', intval($key));
                    }
                    if ($key === '*Mm_Chk*') {
                        $std = isset($row['Max_SPM_Std']) ? floatval($row['Max_SPM_Std']) : 0;
                        $min = isset($row['Min_SPM_Set']) && $row['Min_SPM_Set'] !== null && $row['Min_SPM_Set'] !== '' ? floatval($row['Min_SPM_Set']) : null;
                        $max = isset($row['Max_SPM_Set']) && $row['Max_SPM_Set'] !== null && $row['Max_SPM_Set'] !== '' ? floatval($row['Max_SPM_Set']) : null;
                        if ($min !== null && $max !== null) {
                            $ltC = $max > $std ? min(3, (int)round(($max - $std) / 3)) : 0;
                            $gtC = $min < $std ? min(3, (int)round(($std - $min) / 3)) : 0;
                            $val = str_repeat('<', $ltC) . ($ltC > 0 && $gtC > 0 ? ' ' : '') . str_repeat('>', $gtC);
                        } else {
                            $val = '';
                        }
                    } else {
                        $val = isset($row[$key]) ? $row[$key] : '';
                        if (is_numeric($val) && $val == 0) $val = '';
                    }
                    $sheet->setCellValue($col++ . $r, $val);
                }
                $r++;
            }
            return $r;
        };

        $datasets = [
            ['data' => $std,  'sheet' => '1-Std Prod',  'file' => '1-Std_Prod_'   . $timestamp . '.xlsx', 'useStd' => false],
            ['data' => $nett, 'sheet' => '2-Net Prod',  'file' => '2-Net_Prod_'   . $timestamp . '.xlsx', 'useStd' => false],
            ['data' => $gross,'sheet' => '3-Gros Prod',  'file' => '3-Gross_Prod_' . $timestamp . '.xlsx', 'useStd' => false],
        ];

        $resultFiles = [];
        foreach ($datasets as $ds) {
            $excelDs = new PHPExcel();
            $excelDs->getProperties()->setCreator("DPR System")->setTitle($ds['sheet'] . " " . $year);
            $sheet = $excelDs->getActiveSheet();
            $sheet->setTitle($ds['sheet']);
            $col = 'A'; foreach ($baseHeaders as $h) { $sheet->setCellValue($col++ . '1', $h); }
            $writeProdRows($sheet, $ds['data'], $baseHeaders, 2, $ds['useStd']);
            $path = $dir . DIRECTORY_SEPARATOR . $ds['file'];
            $this->autosize_excel_columns($excelDs);
            $writer = PHPExcel_IOFactory::createWriter($excelDs, 'Excel2007');
            $writer->save($path);
            $resultFiles[] = ['path' => $path, 'name' => $ds['file']];
        }
        return $resultFiles;
    }

    private function build_production_qty_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');
        $total = $this->mr->get_production_qty_full_excel_data($year);
        $ok = $this->mr->get_production_ok_full_excel_data($year);
        $ng = $this->mr->get_production_ng_full_excel_data($year);
        if ($total->num_rows() == 0 && $ok->num_rows() == 0 && $ng->num_rows() == 0) {
            throw new Exception('No production qty data for ' . $year);
        }

        $stStyle = [
            'title' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '2E75B6']],
            ],
            'odd' => [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFFFF']],
            ],
            'even' => [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'F2F2F2']],
            ],
        ];

        $makeSheet = function($sheet, $data, $hasPrice) use ($stStyle) {
            $headers = ['YY', 'Customer', 'Product ID', 'Product Name', 'Max SPM-Std', 'Max SPM Std2'];
            if ($hasPrice) $headers[] = 'Price';
            $headers[] = 'Tool';
            $headers[] = 'Tot';
            for ($m=1; $m<=12; $m++) { $headers[] = sprintf('%d.0', $m); }

            $col = 'A';
            foreach ($headers as $h) { $sheet->setCellValue($col++ . '1', $h); }

            $r = 2;
            foreach ($data->result_array() as $row) {
                $col = 'A';
                $sheet->setCellValue($col++ . $r, $row['YY']);
                $sheet->setCellValue($col++ . $r, $row['Customer']);
                $sheet->setCellValue($col++ . $r, $row['Product_ID']);
                $sheet->setCellValue($col++ . $r, $row['Product_Name']);
                $sheet->setCellValue($col++ . $r, $row['Max_SPM_Std']);
                $sheet->setCellValue($col++ . $r, $row['Max_SPM_Std2']);
                if ($hasPrice) $sheet->setCellValue($col++ . $r, $row['Price']);
                $sheet->setCellValue($col++ . $r, $row['Tool']);

                $monthTotal = 0;
                for ($m=1; $m<=12; $m++) {
                    $k = sprintf('%02d', $m);
                    $monthTotal += floatval(isset($row[$k]) ? $row[$k] : 0);
                }
                $sheet->setCellValue($col++ . $r, $monthTotal > 0 ? $monthTotal : '');

                for ($m=1; $m<=12; $m++) {
                    $k = sprintf('%02d', $m);
                    $v = isset($row[$k]) && $row[$k] != 0 ? $row[$k] : '';
                    $sheet->setCellValue($col++ . $r, $v);
                }
                $r++;
            }

            // Apply styles: title on header row, alternating on data rows
            $lastCol = chr(ord('A') + count($headers) - 1);
            $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($stStyle['title']);
            for ($sr = 2; $sr < $r; $sr++) {
                $sheet->getStyle('A' . $sr . ':' . $lastCol . $sr)
                    ->applyFromArray($stStyle[$sr % 2 === 0 ? 'even' : 'odd']);
            }
        };

        $qtyFiles = [];
        foreach ([
            ['data' => $total, 'sheet' => '4-Ttl Prod',    'file' => '4-Ttl_Prod_'    . $timestamp . '.xlsx'],
            ['data' => $ok,    'sheet' => '5-Ttl Prod OK', 'file' => '5-Ttl_Prod_OK_' . $timestamp . '.xlsx'],
            ['data' => $ng,    'sheet' => '6-Ttl Prod NG', 'file' => '6-Ttl_Prod_NG_' . $timestamp . '.xlsx'],
        ] as $qds) {
            $qExcel = new PHPExcel();
            $qExcel->getProperties()->setCreator("DPR System")->setTitle($qds['sheet'] . " " . $year);
            $qSheet = $qExcel->getActiveSheet();
            $qSheet->setTitle($qds['sheet']);
            $makeSheet($qSheet, $qds['data'], true);
            $this->autosize_excel_columns($qExcel);
            $qPath = $dir . DIRECTORY_SEPARATOR . $qds['file'];
            PHPExcel_IOFactory::createWriter($qExcel, 'Excel2007')->save($qPath);
            $qtyFiles[] = ['path' => $qPath, 'name' => $qds['file']];
        }

        // ── 12-Ttl Prod: product-level total (no Tool/Customer, adds Price + Min/Max SPM-Set)
        $totalProd12 = $this->mr->get_total_prod_excel_data($year);
        $q12 = new PHPExcel();
        $q12->getProperties()->setCreator("DPR System")->setTitle("12-Ttl Prod " . $year);
        $s12 = $q12->getActiveSheet();
        $s12->setTitle('Sum_PR_3_by_PROD_by_Mo__SB_Part');

        $hdr12 = ['YY','Product ID','Product Name','Max SPM-Std','Max SPM Std2','Price','Min SPM-Set','Max SPM-Set','Tot Prod',
                  '01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($hdr12 as $ci => $h) {
            $s12->setCellValueByColumnAndRow($ci, 1, $h);
        }

        $r = 2;
        if ($totalProd12) {
            foreach ($totalProd12->result_array() as $row) {
                $col = 0;
                $s12->setCellValueByColumnAndRow($col++, $r, $row['YY']);
                $s12->setCellValueByColumnAndRow($col++, $r, $row['Product_ID']);
                $s12->setCellValueByColumnAndRow($col++, $r, $row['Product_Name']);
                $s12->setCellValueByColumnAndRow($col++, $r, $row['Max_SPM_Std']);
                $s12->setCellValueByColumnAndRow($col++, $r, $row['Max_SPM_Std2']);
                $s12->setCellValueByColumnAndRow($col++, $r, floatval($row['Price']));
                $s12->setCellValueByColumnAndRow($col++, $r, $row['Min_SPM_Set'] !== null ? intval($row['Min_SPM_Set']) : '');
                $s12->setCellValueByColumnAndRow($col++, $r, $row['Max_SPM_Set'] !== null ? intval($row['Max_SPM_Set']) : '');
                $totProd = intval($row['Tot_Prod']);
                $s12->setCellValueByColumnAndRow($col++, $r, $totProd > 0 ? $totProd : '');
                for ($m = 1; $m <= 12; $m++) {
                    $k = sprintf('%02d', $m);
                    $v = intval(isset($row[$k]) ? $row[$k] : 0);
                    $s12->setCellValueByColumnAndRow($col++, $r, $v > 0 ? $v : '');
                }
                $r++;
            }
        }
        $this->autosize_excel_columns($q12);
        $q12Path = $dir . DIRECTORY_SEPARATOR . '12-Ttl_Prod_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($q12, 'Excel2007')->save($q12Path);
        $qtyFiles[] = ['path' => $q12Path, 'name' => basename($q12Path)];

        return $qtyFiles;

    }

    private function build_machine_eff_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');
        $effData = $this->mr->get_efficiency_per_machine_per_month($year);
        $capData = $this->mr->get_machine_capacity_by_tonnage($year);
        if ($effData->num_rows() == 0 && $capData->num_rows() == 0) {
            throw new Exception('No machine efficiency data for ' . $year);
        }

        // Pre-fetch machine names from t_no_mesin + t_nama_mesin
        $machineNames = [];
        $machResult = $this->db->query(
            "SELECT m.no_mesin, nm.nama_mesin FROM t_no_mesin m 
             LEFT JOIN t_nama_mesin nm ON nm.id_nama_mesin = m.id_nama_mesin 
             WHERE m.aktif = 1 ORDER BY m.no_mesin"
        );
        foreach ($machResult->result_array() as $mr) {
            $machineNames[intval($mr['no_mesin'])] = $mr['nama_mesin'];
        }
        // Ensure all machines in effData have entries
        foreach ($effData->result_array() as $row) {
            if (!isset($machineNames[intval($row['Mach'])])) {
                $machineNames[intval($row['Mach'])] = '';
            }
        }

        // Pre-fetch loss time by category per machine per month (for ST1-ST19)
        $ltByCategory = [];
        $ltData = $this->mr->get_loss_time_by_category_per_machine($year);
        if ($ltData) {
            foreach ($ltData->result_array() as $lr) {
                $ltByCategory[intval($lr['mesin'])][intval($lr['bulan'])][$lr['category']] = floatval($lr['total_lt']);
            }
        }

        // Get distinct LT category names (ordered for consistent column mapping)
        $ltCategoryNames = [];
        $ltCatResult = $this->db->query(
            "SELECT DISTINCT nama FROM t_defectdanlosstime WHERE type = 'LT' ORDER BY nama"
        );
        if ($ltCatResult) {
            foreach ($ltCatResult->result_array() as $cr) {
                $ltCategoryNames[] = $cr['nama'];
            }
        }
        // Pad to 19 if fewer categories
        while (count($ltCategoryNames) < 19) { $ltCategoryNames[] = ''; }

        // Build kategori-to-items mapping for SumOfTtl S T1-T5 grouped totals
        $ltKategoriMap = [];
        $katResult = $this->db->query(
            "SELECT nama, kategori_defect FROM t_defectdanlosstime WHERE type = 'LT' ORDER BY kategori_defect, nama"
        );
        if ($katResult) {
            foreach ($katResult->result_array() as $kr) {
                $ltKategoriMap[$kr['kategori_defect']][] = $kr['nama'];
            }
        }
        // Ordered kategori list for ST1-ST5 columns
        $ltKategoriOrder = ['LT PRODUKSI', 'LT ME', 'LT TM', 'LT PPIC', 'LT QA'];

        $effRows = $effData->result_array();

        // ========== Separate file: 7-Table ==========
        $effFiles = [];
        $e7t = new PHPExcel();
        $e7t->getProperties()->setCreator("DPR System")->setTitle("7-Table " . $year);
        $s2 = $e7t->getActiveSheet(); $s2->setTitle('SBJ_4_Mach_Eff_Exl_table');

        $col = 'A';
        $s2->setCellValue($col++ . '1', 'Mach');
        $s2->setCellValue($col++ . '1', 'Total Of Mach Eff Hr');
        for ($m = 1; $m <= 12; $m++) { $s2->setCellValue($col++ . '1', sprintf('%02d', $m)); }

        $r = 2;
        foreach ($effRows as $row) {
            $col = 'A';
            $s2->setCellValue($col++ . $r, $row['Mach']);
            $total = 0;
            for ($m = 1; $m <= 12; $m++) {
                $k = sprintf('%02d', $m);
                $v = floatval($row['wh' . $k]) + floatval($row['ot' . $k]);
                $s2->setCellValue($col++ . $r, $v > 0 ? round($v, 2) : '');
                $total += $v;
            }
            $s2->setCellValue('B' . $r, $total > 0 ? round($total, 2) : '');
            $r++;
        }

        $this->autosize_excel_columns($e7t);
        $e7tPath = $dir . DIRECTORY_SEPARATOR . '7-Table-Mch_eff_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($e7t, 'Excel2007')->save($e7tPath);
        $effFiles[] = ['path' => $e7tPath, 'name' => basename($e7tPath)];

        return $effFiles;
    }


    private function map_eff_key($header)
    {
        $map = [
            'YY-MM' => 'YY_MM',
            'Customer' => 'Customer',
            'Mo' => 'Mo',
            'name' => 'name',
            'Total Of SumOfMach Eff Hr' => 'total_of_sumofmach_eff_hr',
            'Total Of Mach Eff Hr%%' => 'total_of_mach_eff_hr_percent'
        ];
        $numeric = ['40','55','60','80','90','120','160','200'];
        if (isset($map[$header])) return $map[$header];
        if (in_array($header, $numeric)) return $header;
        return $header;
    }

    private function build_losstime_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');
        $effData = $this->mr->get_efficiency_per_machine_per_month($year);

        $effRows = $effData ? $effData->result_array() : [];

        // Pre-fetch machine names
        $machineNames = [];
        $machResult = $this->db->query(
            "SELECT m.no_mesin, nm.nama_mesin FROM t_no_mesin m
             LEFT JOIN t_nama_mesin nm ON nm.id_nama_mesin = m.id_nama_mesin
             WHERE m.aktif = 1 ORDER BY m.no_mesin"
        );
        foreach ($machResult->result_array() as $mr) {
            $machineNames[intval($mr['no_mesin'])] = $mr['nama_mesin'];
        }
        foreach ($effRows as $row) {
            if (!isset($machineNames[intval($row['Mach'])])) {
                $machineNames[intval($row['Mach'])] = '';
            }
        }

        // Pre-fetch LT by category per machine per month
        $ltByCategory = [];
        $ltData = $this->mr->get_loss_time_by_category_per_machine($year);
        if ($ltData) {
            foreach ($ltData->result_array() as $lr) {
                $ltByCategory[intval($lr['mesin'])][intval($lr['bulan'])][$lr['category']] = floatval($lr['total_lt']);
            }
        }
        $ltCategoryNames = [];
        $ltCatResult = $this->db->query("SELECT DISTINCT nama FROM t_defectdanlosstime WHERE type = 'LT' ORDER BY nama");
        if ($ltCatResult) {
            foreach ($ltCatResult->result_array() as $cr) { $ltCategoryNames[] = $cr['nama']; }
        }
        while (count($ltCategoryNames) < 19) { $ltCategoryNames[] = ''; }

        // Kategori mapping
        $ltKategoriMap = [];
        $katResult = $this->db->query("SELECT nama, kategori_defect FROM t_defectdanlosstime WHERE type = 'LT' ORDER BY kategori_defect, nama");
        if ($katResult) {
            foreach ($katResult->result_array() as $kr) { $ltKategoriMap[$kr['kategori_defect']][] = $kr['nama']; }
        }
        $ltKategoriOrder = ['LT PPIC', 'LT PRODUKSI', 'LT TM', 'LT ME', 'LT QA'];

        // ========== Separate file: 7-Table-ST_Machine ==========
        $ltFiles = [];
        $lt7t = new PHPExcel();
        $lt7t->getProperties()->setCreator("DPR System")->setTitle("7-Table-ST_Machine " . $year);
        $s3 = $lt7t->getActiveSheet(); $s3->setTitle('7-Table');
        $col = 'A';
        $s3->setCellValue($col++ . '1', 'Mach');
        $s3->setCellValue($col++ . '1', 'Total Of Mach Eff Hr');
        for ($m = 1; $m <= 12; $m++) { $s3->setCellValue($col++ . '1', sprintf('%02d', $m)); }
        $r = 2;
        foreach ($effRows as $row3) {
            $col = 'A';
            $s3->setCellValue($col++ . $r, $row3['Mach']);
            $total = 0;
            for ($m = 1; $m <= 12; $m++) {
                $k = sprintf('%02d', $m);
                $v = floatval($row3['eff' . $k]);
                $s3->setCellValue($col++ . $r, $v > 0 ? $v : '');
                $total += $v;
            }
            $s3->setCellValue('B' . $r, $total > 0 ? round($total, 2) : '');
            $r++;
        }
        $this->autosize_excel_columns($lt7t);
        $lt7tPath = $dir . DIRECTORY_SEPARATOR . '7-Table-ST_Machine_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($lt7t, 'Excel2007')->save($lt7tPath);
        $ltFiles[] = ['path' => $lt7tPath, 'name' => basename($lt7tPath)];

        // ========== Separate file: 7-Data-ST_Machine ==========
        $lt7d = new PHPExcel();
        $lt7d->getProperties()->setCreator("DPR System")->setTitle("7-Data-ST_Machine " . $year);
        $s2 = $lt7d->getActiveSheet(); $s2->setTitle('7-Data');

        $col = 'A';
        $s2->setCellValue($col++ . '1', 'YY-MM');
        $s2->setCellValue($col++ . '1', 'Mo');
        $s2->setCellValue($col++ . '1', 'Mach');
        $s2->setCellValue($col++ . '1', 'SumOfW H');
        $s2->setCellValue($col++ . '1', 'SumOfO T');
        $s2->setCellValue($col++ . '1', 'Mo Mach Std Hr');
        $s2->setCellValue($col++ . '1', 'Mach Eff Hr');
        $s2->setCellValue($col++ . '1', 'SumOfTtl S T');
        $s2->setCellValue($col++ . '1', 'SumOfTtl S T1');
        $s2->setCellValue($col++ . '1', 'SumOfTtl S T2');
        $s2->setCellValue($col++ . '1', 'SumOfTtl S T3');
        $s2->setCellValue($col++ . '1', 'SumOfTtl S T4');
        for ($sti = 0; $sti < 19; $sti++) {
            $catName = isset($ltCategoryNames[$sti]) && $ltCategoryNames[$sti] !== ''
                ? $ltCategoryNames[$sti] : 'SumOf' . ($sti + 1);
            $s2->setCellValue($col++ . '1', $catName);
        }
        $s2->setCellValue($col++ . '1', 'Mach Eff %');
        $s2->setCellValue($col++ . '1', 'Name');

        $r = 2;
        foreach ($effRows as $row2) {
            $mach = $row2['Mach'];
            for ($m = 1; $m <= 12; $m++) {
                $k = sprintf('%02d', $m);
                $wh = floatval($row2['wh' . $k]);
                $ot = floatval($row2['ot' . $k]);
                $eff = floatval($row2['eff' . $k]);
                $lt = floatval($row2['lt' . $k]);
                $pt = floatval($row2['pt' . $k]);
                $machEffPct = ($wh + $ot) > 0 ? round(($pt / ($wh + $ot)), 4) : 0;

                $col = 'A';
                $s2->setCellValue($col++ . $r, $year . '-' . $k);
                $s2->setCellValue($col++ . $r, $m);
                $s2->setCellValue($col++ . $r, $mach);
                $s2->setCellValue($col++ . $r, $wh > 0 ? $wh : '');
                $s2->setCellValue($col++ . $r, $ot > 0 ? $ot : '');
                $s2->setCellValue($col++ . $r, $wh + $ot > 0 ? $wh + $ot : '');
                $s2->setCellValue($col++ . $r, $pt > 0 ? $pt : '');
                $s2->setCellValue($col++ . $r, $lt > 0 ? $lt : '');
                foreach ($ltKategoriOrder as $kategori) {
                    $katTotal = 0;
                    $items = isset($ltKategoriMap[$kategori]) ? $ltKategoriMap[$kategori] : [];
                    foreach ($items as $itemName) {
                        if (isset($ltByCategory[intval($mach)][$m][$itemName])) {
                            $katTotal += $ltByCategory[intval($mach)][$m][$itemName];
                        }
                    }
                    $s2->setCellValue($col++ . $r, $katTotal > 0 ? round($katTotal, 2) : '');
                }
                for ($sti = 0; $sti < 19; $sti++) {
                    $catName = isset($ltCategoryNames[$sti]) ? $ltCategoryNames[$sti] : '';
                    $catVal = '';
                    if ($catName !== '' && isset($ltByCategory[intval($mach)][$m][$catName])) {
                        $catVal = $ltByCategory[intval($mach)][$m][$catName];
                        $catVal = $catVal > 0 ? $catVal : '';
                    }
                    $s2->setCellValue($col++ . $r, $catVal);
                }
                $s2->setCellValue($col++ . $r, $machEffPct > 0 ? $machEffPct : '');
                $s2->setCellValue($col++ . $r, $machineNames[intval($mach)] ?? '');
                $r++;
            }
        }
        $this->autosize_excel_columns($lt7d);
        $lt7dPath = $dir . DIRECTORY_SEPARATOR . '7-Data-ST_Machine_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($lt7d, 'Excel2007')->save($lt7dPath);
        $ltFiles[] = ['path' => $lt7dPath, 'name' => basename($lt7dPath)];

        return $ltFiles;
    }



    /**
     * Build Machine Use Chart Excel (Report 5 - Pak Mursalim format)
     * 18 sheets: Rate, 7-Data, 8-Data, 9-Data, Jan-Dec monthly, Total by month by Cust, Total by Ton by Cust
     */
    private function build_machine_use_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');

        $tonnages = [40, 55, 60, 80, 90, 120, 125, 160, 200];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // === Fetch data ===
        $effData = $this->mr->get_efficiency_per_machine_per_month($year);
        $ltCatResult = $this->mr->get_loss_time_category_list($year);
        $ltData = $this->mr->get_loss_time_by_category_per_machine($year);
        $custMonthData = $this->mr->get_machine_use_by_customer_by_month($year);
        $custTonData = $this->mr->get_machine_use_by_customer_tonnage_by_month($year);
        $custTonCrossData = $this->mr->get_machine_use_cust_tonnage_by_month($year);
        $capPerMonth = $this->mr->get_available_capacity_per_month($year);
        $machCapData = $this->mr->get_machine_capacity_by_tonnage($year);
        $fct = $this->mr->f_cost_target($year);
        $fctVal = ($fct && $fct->num_rows() > 0) ? floatval($fct->row()->f_cost_target) : 0;
        $rateByTon = $this->mr->get_machine_rate_by_tonnage($year, $fctVal);
        $machines = $this->mr->get_active_machines();

        // Build std hours map
        $stdHrs = [];
        if ($capPerMonth) {
            foreach ($capPerMonth->result_array() as $cr) {
                $stdHrs[intval($cr['bulan'])] = floatval($cr['std_hours']);
            }
        }

        // Build machine count per tonnage map
        $machCountByTon = [];
        if ($machCapData) {
            foreach ($machCapData->result_array() as $mc) {
                $ton = intval($mc['tonnase']);
                $machCountByTon[$ton] = intval($mc['jumlah_mesin']);
            }
        }

        // Build machine capacity per tonnage per month: capMap[ton][month] = capacity
        $capMap = [];
        if ($machCapData) {
            foreach ($machCapData->result_array() as $mc) {
                $ton = intval($mc['tonnase']);
                $mth = intval($mc['bulan']);
                $capMap[$ton][$mth] = floatval($mc['available_capacity']);
            }
        }

        // Build rate per tonnage map
        $rateByTonMap = [];
        if ($rateByTon) {
            foreach ($rateByTon->result_array() as $rr) {
                $rateByTonMap[intval($rr['tonnase'])] = floatval($rr['total_cost']);
            }
        }

        // Build LT category list
        $ltCategories = [];
        if ($ltCatResult) {
            $idx = 0;
            foreach ($ltCatResult->result_array() as $cat) {
                $ltCategories[] = $cat['category'];
                $idx++;
                if ($idx >= 19) break;
            }
        }
        while (count($ltCategories) < 19) {
            $ltCategories[] = '';
        }

        // Build LT data lookup: ltLookup[machine][month][category] = hours
        $ltLookup = [];
        if ($ltData) {
            foreach ($ltData->result_array() as $lt) {
                $mach = intval($lt['mesin']);
                $mth = intval($lt['bulan']);
                $cat = $lt['category'];
                if (!isset($ltLookup[$mach])) $ltLookup[$mach] = [];
                if (!isset($ltLookup[$mach][$mth])) $ltLookup[$mach][$mth] = [];
                $ltLookup[$mach][$mth][$cat] = floatval($lt['total_lt']);
            }
        }

        // Build customer x tonnage per month lookup: custTonLookup[month][customer][tonnage] = hours
        $custTonLookup = [];
        if ($custTonData) {
            foreach ($custTonData->result_array() as $ct) {
                $cust = $ct['customer'];
                $ton = intval($ct['tonnase']);
                $mth = intval($ct['bulan']);
                if (!$cust || !$ton) continue;
                if (!isset($custTonLookup[$mth])) $custTonLookup[$mth] = [];
                if (!isset($custTonLookup[$mth][$cust])) $custTonLookup[$mth][$cust] = [];
                if (!isset($custTonLookup[$mth][$cust][$ton])) $custTonLookup[$mth][$cust][$ton] = 0;
                $custTonLookup[$mth][$cust][$ton] += floatval($ct['total_hours']);
            }
        }

        // Build customer-month totals lookup
        $custMonthTotals = [];
        $allCustomers = [];
        if ($custMonthData) {
            foreach ($custMonthData->result_array() as $cm) {
                $cust = $cm['customer'];
                $allCustomers[] = $cust;
                for ($m = 1; $m <= 12; $m++) {
                    $k = sprintf('hr%02d', $m);
                    $v = floatval($cm[$k]);
                    if (!isset($custMonthTotals[$m])) $custMonthTotals[$m] = [];
                    if (!isset($custMonthTotals[$m][$cust])) $custMonthTotals[$m][$cust] = 0;
                    $custMonthTotals[$m][$cust] += $v;
                }
            }
        }
        $allCustomers = array_unique($allCustomers);
        sort($allCustomers);

        // Default working days
        $defaultWd = isset($stdHrs[1]) ? round($stdHrs[1] / 21) : 23;

        // Get unique customers from tonnage data too
        foreach ($custTonLookup as $mthData) {
            foreach (array_keys($mthData) as $cust) {
                if (!in_array($cust, $allCustomers)) {
                    $allCustomers[] = $cust;
                }
            }
        }
        $allCustomers = array_unique($allCustomers);
        sort($allCustomers);

        // ========== Separate file: 7-Data ========================
        // Matches Access "SBJ 4 Mach Eff Exl": 32 columns
        // Fixes vs original: Mach Eff Hr = WH+OT (not production_time-based);
        //   Mach Eff % = decimal fraction 0.xxxx (not xx.xxxx);
        //   every active machine x 12 months (not only machines with data)
        $muFiles = [];
        $mu7 = new PHPExcel();
        $mu7->getProperties()->setCreator("DPR System")->setTitle("7-Data " . $year);
        $s7 = $mu7->getActiveSheet(); $s7->setTitle('7-Data');

        // Access ST1..ST19 labels (decoded from DPR Input Form controls):
        // ST1=No Material, ST2=No Packing                            → T1 (PPIC)
        // ST3=Mat'l Problem, ST4=Set-Up Mat'l, ST5=No Operator,
        //   ST6=Trial, ST7=Listrik                                   → T2 (Material/Operator)
        // ST8=Overhole, ST9=Check List, ST10=Set-Up Mold,
        //   ST11=Ajust Cond., ST12=Mold, ST17=Start Up              → T3 (Mold/Setup)
        // ST13=Machine, ST14=Hopper Dyer, ST15=Robot, ST16=MTC,
        //   ST18=C.Chiller/C.Tower, ST19=Compresor                  → T4 (Equipment)
        //
        // MySQL `nama` (from t_defectdanlosstime master) → Access ST# slot.
        // Confirmed by user 2026-04-29 against DPR Input Form labels.
        $accessSTMap = [
            'NO MATERIAL'              => 1,   // ST1  No Material
            'NO PACKING'               => 2,   // ST2  No Packing
            'MATERIAL PROBLEM'         => 3,   // ST3  Mat'l Problem
            'PRE HEATING MATERIAL'     => 4,   // ST4  Set-Up Mat'l
            'NO OPERATOR'              => 5,   // ST5  No Operator
            'TRIAL'                    => 6,   // ST6  Trial
            'LISTRIK'                  => 7,   // ST7  Listrik
            'OVERHOULE MOLD'           => 8,   // ST8  Overhole
            'DAILY CHECK LIST'         => 9,   // ST9  Check List
            'IPQC INSPECTION'          => 9,   // ST9  Check List (no Access slot, folded here)
            'QC LOLOS'                 => 9,   // ST9  Check List (LT QA, no Access slot)
            'SET UP MOLD'              => 10,  // ST10 Set-Up Mold
            'SET UP PARAMETER MACHINE' => 10,  // ST10 Set-Up Mold
            'SET UP AWAL PRODUKSI'     => 10,  // ST10 Set-Up Mold
            'ADJUST KONDISI'           => 11,  // ST11 Ajust Cond.
            'ADJUST KONDISI0.5'        => 11,  // ST11 Ajust Cond. (legacy typo variant)
            'ADJUST PARAMETER'         => 11,  // ST11 Ajust Cond.
            'MOLD PROBLEM'             => 12,  // ST12 Mold
            'MACHINE'                  => 13,  // ST13 Machine
            'HOPPER DRYER'             => 14,  // ST14 Hopper Dyer
            'CLEANING HOPPER & BARREL' => 14,  // ST14 Hopper Dyer
            'ROBOT'                    => 15,  // ST15 Robot
            'MTC'                      => 16,  // ST16 MTC
            // ST17 Start Up — no MySQL nama maps here
            'COOLING TOWER'            => 18,  // ST18 C. Chiller/C. tower
            'COMPRESSOR'               => 19,  // ST19 Compresor
        ];

        $col = 0;
        $h7 = ['YY-MM', 'Mo', 'Mach', 'SumOfW H', 'SumOfO T', 'Mo Mach Std Hr', 'Mach Eff Hr', 'SumOfTtl S T',
               'SumOfTtl S T1', 'SumOfTtl S T2', 'SumOfTtl S T3', 'SumOfTtl S T4',
               'SumOfST1','SumOfST2','SumOfST3','SumOfST4','SumOfST5','SumOfST6','SumOfST7',
               'SumOfST8','SumOfST9','SumOfST10','SumOfST11','SumOfST12','SumOfST13',
               'SumOfST14','SumOfST15','SumOfST16','SumOfST17','SumOfST18','SumOfST19',
               'Mach Eff %', 'Name'];
        foreach ($h7 as $h) {
            $s7->setCellValueByColumnAndRow($col++, 1, $h);
        }

        // Build WH/OT lookup: effLookup7[machId][month] = ['wh'=>x, 'ot'=>y]
        $effLookup7 = [];
        if ($effData) {
            foreach ($effData->result_array() as $row) {
                $machId = intval($row['Mach']);
                for ($m = 1; $m <= 12; $m++) {
                    $k = sprintf('%02d', $m);
                    $effLookup7[$machId][$m] = [
                        'wh' => floatval($row['wh' . $k]),
                        'ot' => floatval($row['ot' . $k]),
                        'lt' => floatval($row['lt' . $k]),
                    ];
                }
            }
        }

        // Iterate ALL active machines × 12 months (matches Access SBJ 3 Mach NA App behaviour)
        $r = 2;
        foreach ($machines->result_array() as $mch) {
            $machId = intval($mch['no_mesin']);
            for ($m = 1; $m <= 12; $m++) {
                $k = sprintf('%02d', $m);
                $wh     = isset($effLookup7[$machId][$m]) ? $effLookup7[$machId][$m]['wh'] : 0;
                $ot     = isset($effLookup7[$machId][$m]) ? $effLookup7[$machId][$m]['ot'] : 0;
                $ltTotal = isset($effLookup7[$machId][$m]) ? $effLookup7[$machId][$m]['lt'] : 0;
                $stdH   = isset($stdHrs[$m]) ? $stdHrs[$m] : 462;
                // Access: Mach Eff Hr = IIf(WH+OT=0, Null, WH+OT)
                $effHr  = ($wh + $ot) > 0 ? ($wh + $ot) : null;
                // Access: Mach Eff % = decimal fraction 0.xxxx (CLng(x*10000)/10000)
                $effPct = ($effHr !== null && $stdH > 0) ? round($effHr / $stdH, 4) : null;

                // Map MySQL LT categories to fixed ST1..ST19 slots
                $ltForMach = isset($ltLookup[$machId][$m]) ? $ltLookup[$machId][$m] : [];
                $stVals = array_fill(1, 19, 0);
                foreach ($ltForMach as $cat => $hrs) {
                    if (isset($accessSTMap[$cat])) {
                        $stVals[$accessSTMap[$cat]] += $hrs;
                    } else {
                        log_message('error', "7-Data: unknown LT nama '{$cat}' (mach={$machId} mo={$k}) — not mapped to any ST slot");
                    }
                }
                // Access groupings: T1=ST1+2, T2=ST3..7, T3=ST8..12+17, T4=ST13..16+18+19
                $stT1 = $stVals[1] + $stVals[2];
                $stT2 = $stVals[3] + $stVals[4] + $stVals[5] + $stVals[6] + $stVals[7];
                $stT3 = $stVals[8] + $stVals[9] + $stVals[10] + $stVals[11] + $stVals[12] + $stVals[17];
                $stT4 = $stVals[13] + $stVals[14] + $stVals[15] + $stVals[16] + $stVals[18] + $stVals[19];

                $col = 0;
                $s7->setCellValueByColumnAndRow($col++, $r, $year . '-' . $k);   // YY-MM
                $s7->setCellValueByColumnAndRow($col++, $r, $k);                  // Mo (string "01".."12")
                $s7->setCellValueByColumnAndRow($col++, $r, $machId);             // Mach
                $s7->setCellValueByColumnAndRow($col++, $r, $wh);                 // SumOfW H
                $s7->setCellValueByColumnAndRow($col++, $r, $ot);                 // SumOfO T
                $s7->setCellValueByColumnAndRow($col++, $r, $stdH);               // Mo Mach Std Hr
                $s7->setCellValueByColumnAndRow($col++, $r, $effHr);              // Mach Eff Hr
                $s7->setCellValueByColumnAndRow($col++, $r, $ltTotal);            // SumOfTtl S T
                $s7->setCellValueByColumnAndRow($col++, $r, $stT1);               // SumOfTtl S T1
                $s7->setCellValueByColumnAndRow($col++, $r, $stT2);               // SumOfTtl S T2
                $s7->setCellValueByColumnAndRow($col++, $r, $stT3);               // SumOfTtl S T3
                $s7->setCellValueByColumnAndRow($col++, $r, $stT4);               // SumOfTtl S T4
                for ($st = 1; $st <= 19; $st++) {
                    $s7->setCellValueByColumnAndRow($col++, $r, $stVals[$st]);    // SumOfST1..19
                }
                $s7->setCellValueByColumnAndRow($col++, $r, $effPct);             // Mach Eff %
                $s7->setCellValueByColumnAndRow($col++, $r, sprintf('%02d%02d', $m, $machId)); // Name e.g. "0126"
                $r++;
            }
        }
        $this->autosize_excel_columns($mu7);
        $mu7Path = $dir . DIRECTORY_SEPARATOR . '7-Data_Machine_Use_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu7, 'Excel2007')->save($mu7Path);
        $muFiles[] = ['path' => $mu7Path, 'name' => basename($mu7Path)];

        // ========== Separate file: 8-Data ========================
        $mu8 = new PHPExcel();
        $mu8->getProperties()->setCreator("DPR System")->setTitle("8-Data " . $year);
        $s8 = $mu8->getActiveSheet(); $s8->setTitle('SBJ_4_Mach_Eff_Graph_by_cust_Cr');

        $col = 0;
        $s8->setCellValueByColumnAndRow($col++, 1, 'YY-MM');
        $s8->setCellValueByColumnAndRow($col++, 1, 'Customer');
        $s8->setCellValueByColumnAndRow($col++, 1, 'Mo');
        $s8->setCellValueByColumnAndRow($col++, 1, 'name');
        $s8->setCellValueByColumnAndRow($col++, 1, 'Total Of SumOfMach Eff Hr');
        foreach ($tonnages as $ton) {
            $s8->setCellValueByColumnAndRow($col++, 1, $ton);
        }

        // Build pivot: [bulan][customer][tonnase] = total_eff_hr
        // Also track yy_mm per month
        $pivot8 = [];
        $yymm8  = [];
        if ($custTonCrossData) {
            foreach ($custTonCrossData->result_array() as $row) {
                $mth  = intval($row['bulan']);
                $cust = $row['customer'];
                $ton  = intval($row['tonnase']);
                $val  = floatval($row['total_eff_hr']);
                if (!isset($pivot8[$mth]))             $pivot8[$mth] = [];
                if (!isset($pivot8[$mth][$cust]))      $pivot8[$mth][$cust] = [];
                if (!isset($pivot8[$mth][$cust][$ton])) $pivot8[$mth][$cust][$ton] = 0;
                $pivot8[$mth][$cust][$ton] += $val;
                $yymm8[$mth] = $row['yy_mm'];
            }
        }

        // Write one row per (Mo, Customer) — matches Access crosstab layout
        $r = 2;
        ksort($pivot8);
        foreach ($pivot8 as $mth => $customers) {
            $k = sprintf('%02d', $mth);
            $yymm = isset($yymm8[$mth]) ? $yymm8[$mth] : ($year . '-' . $k);
            ksort($customers);
            foreach ($customers as $cust => $tonVals) {
                $total = array_sum($tonVals);
                $col = 0;
                $s8->setCellValueByColumnAndRow($col++, $r, $yymm);
                $s8->setCellValueByColumnAndRow($col++, $r, $cust);
                $s8->setCellValueByColumnAndRow($col++, $r, $k);
                $s8->setCellValueByColumnAndRow($col++, $r, $k . $cust);
                $s8->setCellValueByColumnAndRow($col++, $r, round($total, 2));
                foreach ($tonnages as $ton) {
                    $v = isset($tonVals[$ton]) ? round($tonVals[$ton], 2) : '';
                    $s8->setCellValueByColumnAndRow($col++, $r, $v);
                }
                $r++;
            }
        }
        $this->autosize_excel_columns($mu8);
        $mu8Path = $dir . DIRECTORY_SEPARATOR . '8-Data_Machine_Use_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu8, 'Excel2007')->save($mu8Path);
        $muFiles[] = ['path' => $mu8Path, 'name' => basename($mu8Path)];

        // ========== Separate file: 9-Data ========================
        $mu9 = new PHPExcel();
        $mu9->getProperties()->setCreator("DPR System")->setTitle("9-Data " . $year);
        $s9 = $mu9->getActiveSheet(); $s9->setTitle('SBJ_4_Mach_Eff_Graph_by_cust_Cr');

        $col = 0;
        $s9->setCellValueByColumnAndRow($col++, 1, 'YY-MM');
        $s9->setCellValueByColumnAndRow($col++, 1, 'Customer');
        $s9->setCellValueByColumnAndRow($col++, 1, 'Total Of Mach Eff Hr%%');
        foreach ($tonnages as $ton) {
            $s9->setCellValueByColumnAndRow($col++, 1, $ton);
        }

        // Build pivot: [bulan][customer][tonnase] = % utilisation
        // Also track yy_mm per month
        $pivot9 = [];
        $yymm9  = [];
        if ($custTonCrossData) {
            foreach ($custTonCrossData->result_array() as $row) {
                $mth  = intval($row['bulan']);
                $cust = $row['customer'];
                $ton  = intval($row['tonnase']);
                $hrs  = floatval($row['total_eff_hr']);
                $cap  = isset($capMap[$ton][$mth]) ? $capMap[$ton][$mth] : 0;
                $pct  = ($cap > 0) ? round(($hrs / $cap) * 100, 4) : 0;
                if (!isset($pivot9[$mth]))              $pivot9[$mth] = [];
                if (!isset($pivot9[$mth][$cust]))       $pivot9[$mth][$cust] = [];
                if (!isset($pivot9[$mth][$cust][$ton])) $pivot9[$mth][$cust][$ton] = 0;
                $pivot9[$mth][$cust][$ton] += $pct;
                $yymm9[$mth] = $row['yy_mm'];
            }
        }

        // Write one row per (Mo, Customer) — matches Access crosstab layout
        $r = 2;
        ksort($pivot9);
        foreach ($pivot9 as $mth => $customers) {
            $k    = sprintf('%02d', $mth);
            $yymm = isset($yymm9[$mth]) ? $yymm9[$mth] : ($year . '-' . $k);
            ksort($customers);
            foreach ($customers as $cust => $tonPcts) {
                $total = array_sum($tonPcts);
                $col = 0;
                $s9->setCellValueByColumnAndRow($col++, $r, $yymm);
                $s9->setCellValueByColumnAndRow($col++, $r, $cust);
                $s9->setCellValueByColumnAndRow($col++, $r, round($total, 4));
                foreach ($tonnages as $ton) {
                    $v = isset($tonPcts[$ton]) ? round($tonPcts[$ton], 4) : '';
                    $s9->setCellValueByColumnAndRow($col++, $r, $v);
                }
                $r++;
            }
        }
        $this->autosize_excel_columns($mu9);
        $mu9Path = $dir . DIRECTORY_SEPARATOR . '9-Data_Machine_Use_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu9, 'Excel2007')->save($mu9Path);
        $muFiles[] = ['path' => $mu9Path, 'name' => basename($mu9Path)];

        // ── 10-Data: Stop Time (Maintenance/Mold) by Product ──────────────────
        $stMaintData = $this->mr->get_stop_time_maint_by_product($year);

        $mu10 = new PHPExcel();
        $mu10->getProperties()->setCreator("DPR System")->setTitle("10-Data " . $year);
        $s10 = $mu10->getActiveSheet(); $s10->setTitle('Sum_PR_3_by_PROD_by_Mo__SB_Stop');

        $hdr10 = ['Mo','Product ID','Product Name','Stop Time2','Overhoule','Check List','Set Up Mold','Ajust Cond','Mold','Sart Up','Tool'];
        foreach ($hdr10 as $ci => $h) {
            $s10->setCellValueByColumnAndRow($ci, 1, $h);
        }

        $r = 2;
        if ($stMaintData) {
            foreach ($stMaintData->result_array() as $row) {
                $overhoule   = floatval($row['overhoule']);
                $check_list  = floatval($row['check_list']);
                $set_up_mold = floatval($row['set_up_mold']);
                $ajust_cond  = floatval($row['ajust_cond']);
                $mold        = floatval($row['mold']);
                $sart_up     = floatval($row['sart_up']);
                $stop_time2  = round($overhoule + $check_list + $set_up_mold + $ajust_cond + $mold, 2);

                $s10->setCellValueByColumnAndRow(0,  $r, $row['Mo']);
                $s10->setCellValueByColumnAndRow(1,  $r, $row['product_id']);
                $s10->setCellValueByColumnAndRow(2,  $r, $row['product_name']);
                $s10->setCellValueByColumnAndRow(3,  $r, $stop_time2);
                $s10->setCellValueByColumnAndRow(4,  $r, $overhoule);
                $s10->setCellValueByColumnAndRow(5,  $r, $check_list);
                $s10->setCellValueByColumnAndRow(6,  $r, $set_up_mold);
                $s10->setCellValueByColumnAndRow(7,  $r, $ajust_cond);
                $s10->setCellValueByColumnAndRow(8,  $r, $mold);
                $s10->setCellValueByColumnAndRow(9,  $r, $sart_up);
                $s10->setCellValueByColumnAndRow(10, $r, $row['tool']);
                $r++;
            }
        }
        $this->autosize_excel_columns($mu10);
        $mu10Path = $dir . DIRECTORY_SEPARATOR . '10-Data_Stop_Time_by_Prod_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu10, 'Excel2007')->save($mu10Path);
        $muFiles[] = ['path' => $mu10Path, 'name' => basename($mu10Path)];

        // ── 11-Data: Stop Time (PPIC) by Product ──────────────────────────────
        $stPpicData = $this->mr->get_stop_time_ppic_by_product($year);

        $mu11 = new PHPExcel();
        $mu11->getProperties()->setCreator("DPR System")->setTitle("11-Data " . $year);
        $s11 = $mu11->getActiveSheet(); $s11->setTitle('Sum_PR_3_by_PROD_by_Mo__SB_Stop');

        $hdr11 = ['Mo','Product ID','Product Name','Stop Time1','No Material','No Packing'];
        foreach ($hdr11 as $ci => $h) {
            $s11->setCellValueByColumnAndRow($ci, 1, $h);
        }

        $r = 2;
        if ($stPpicData) {
            foreach ($stPpicData->result_array() as $row) {
                $no_material = floatval($row['no_material']);
                $no_packing  = floatval($row['no_packing']);
                $stop_time1  = round($no_material + $no_packing, 2);

                $s11->setCellValueByColumnAndRow(0, $r, $row['Mo']);
                $s11->setCellValueByColumnAndRow(1, $r, $row['product_id']);
                $s11->setCellValueByColumnAndRow(2, $r, $row['product_name']);
                $s11->setCellValueByColumnAndRow(3, $r, $stop_time1);
                $s11->setCellValueByColumnAndRow(4, $r, $no_material);
                $s11->setCellValueByColumnAndRow(5, $r, $no_packing);
                $r++;
            }
        }
        $this->autosize_excel_columns($mu11);
        $mu11Path = $dir . DIRECTORY_SEPARATOR . '11-Data_Stop_Time_by_Prod_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu11, 'Excel2007')->save($mu11Path);
        $muFiles[] = ['path' => $mu11Path, 'name' => basename($mu11Path)];

        // ── 13-Rate: total injection cost (qty_ok × cost) per tonnage per month ──
        $rateMonthData = $this->mr->get_machine_rate_by_tonnage_by_month($year);

        // Build pivot: [ton][yy_mm] = ttl_cost, collect ordered month/ton lists
        $pivot13  = [];
        $months13 = [];
        $tons13   = [];
        if ($rateMonthData) {
            foreach ($rateMonthData->result_array() as $row) {
                $ton  = intval($row['tonnase']);
                $ym   = $row['yy_mm'];
                if (!isset($pivot13[$ton]))      $pivot13[$ton] = [];
                $pivot13[$ton][$ym] = floatval($row['ttl_cost']);
                if (!in_array($ym, $months13))   $months13[] = $ym;
                if (!in_array($ton, $tons13))    $tons13[]   = $ton;
            }
        }
        sort($months13);
        sort($tons13);

        $mu13 = new PHPExcel();
        $mu13->getProperties()->setCreator("DPR System")->setTitle("13-Rate " . $year);
        $s13 = $mu13->getActiveSheet(); $s13->setTitle('M_C_Rate_Cal_Crosstab');

        // Header row
        $s13->setCellValueByColumnAndRow(0, 1, 'TON');
        $s13->setCellValueByColumnAndRow(1, 1, 'Total Of Ttl Cost');
        $hCol = 2;
        foreach ($months13 as $ym) {
            $s13->setCellValueByColumnAndRow($hCol++, 1, $ym);
        }

        // Data rows — one per tonnage
        $r = 2;
        foreach ($tons13 as $ton) {
            $total = array_sum($pivot13[$ton]);
            $s13->setCellValueByColumnAndRow(0, $r, $ton);
            $s13->setCellValueByColumnAndRow(1, $r, round($total));
            $col = 2;
            foreach ($months13 as $ym) {
                $v = isset($pivot13[$ton][$ym]) ? round($pivot13[$ton][$ym]) : '';
                $s13->setCellValueByColumnAndRow($col++, $r, $v);
            }
            $r++;
        }
        $this->autosize_excel_columns($mu13);
        $mu13Path = $dir . DIRECTORY_SEPARATOR . '13-Rate_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu13, 'Excel2007')->save($mu13Path);
        $muFiles[] = ['path' => $mu13Path, 'name' => basename($mu13Path)];

        // ── 14-Mc sum: capacity matrix (TON × Month) — reuses $capMap ─────────
        $mu14 = new PHPExcel();
        $mu14->getProperties()->setCreator("DPR System")->setTitle("14-Mc sum " . $year);
        $s14 = $mu14->getActiveSheet(); $s14->setTitle('Mc_Sum');

        // Header row
        $s14->setCellValueByColumnAndRow(0, 1, 'Expr1');
        $s14->setCellValueByColumnAndRow(1, 1, 'Total Of Mo Mach Std Hr');
        for ($m = 1; $m <= 12; $m++) {
            $s14->setCellValueByColumnAndRow(1 + $m, 1, sprintf('%02d', $m));
        }

        // Data rows — one per tonnage, sorted ascending
        $tons14 = array_keys($capMap);
        sort($tons14);
        $r = 2;
        foreach ($tons14 as $ton) {
            $rowTotal = 0;
            for ($m = 1; $m <= 12; $m++) {
                $rowTotal += isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
            }
            $s14->setCellValueByColumnAndRow(0, $r, $ton);
            $s14->setCellValueByColumnAndRow(1, $r, round($rowTotal));
            for ($m = 1; $m <= 12; $m++) {
                $v = isset($capMap[$ton][$m]) ? round($capMap[$ton][$m]) : 0;
                $s14->setCellValueByColumnAndRow(1 + $m, $r, $v);
            }
            $r++;
        }
        $this->autosize_excel_columns($mu14);
        $mu14Path = $dir . DIRECTORY_SEPARATOR . '14-Mc_sum_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($mu14, 'Excel2007')->save($mu14Path);
        $muFiles[] = ['path' => $mu14Path, 'name' => basename($mu14Path)];

        return $muFiles;
    }


}
