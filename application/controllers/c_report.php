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
            
            // Auto-size columns for better readability
            foreach (range('A', 'S') as $columnID) {
                $sheet1->getColumnDimension($columnID)->setAutoSize(true);
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
            
            // Auto-size columns for sheet 2
            foreach (range('A', 'S') as $columnID) {
                $sheet2->getColumnDimension($columnID)->setAutoSize(true);
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
            
            // Auto-size columns for sheet 3
            foreach (range('A', 'S') as $columnID) {
                $sheet3->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            // Set active sheet back to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
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
            
            // Auto-size columns
            foreach (range('A', 'N') as $columnID) {
                $sheet1->getColumnDimension($columnID)->setAutoSize(true);
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
            
            // Auto-size columns for sheet 2
            foreach (range('A', 'N') as $columnID) {
                $sheet2->getColumnDimension($columnID)->setAutoSize(true);
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
            
            // Auto-size columns for sheet 3
            foreach (range('A', 'N') as $columnID) {
                $sheet3->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            // Set active sheet back to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
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
            
            // Auto-size columns for sheet 1
            foreach (range('A', 'M') as $columnID) {
                $sheet1->getColumnDimension($columnID)->setAutoSize(true);
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
            
            // Auto-size columns for sheet 2
            foreach (range('A', 'K') as $columnID) {
                $sheet2->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            // Set active sheet back to first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
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

            foreach (range('A', 'D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

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

            foreach (range('A', 'AE') as $columnID) {
                $sheet1->getColumnDimension($columnID)->setAutoSize(true);
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

            foreach (range('A', 'N') as $columnID) {
                $sheet2->getColumnDimension($columnID)->setAutoSize(true);
            }

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
            $result = $this->build_machine_use_excel_zip($year, $tempDir);
            if (file_exists($result['path'])) {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $result['name'] . '"');
                header('Cache-Control: max-age=0');
                readfile($result['path']);
                @unlink($result['path']);
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
            $files[] = $this->build_productivity_excel_zip($year, $tempDir, $timestamp);
            $files[] = $this->build_production_qty_excel_zip($year, $tempDir, $timestamp);
            $files[] = $this->build_machine_eff_excel_zip($year, $tempDir, $timestamp);
            $files[] = $this->build_losstime_excel_zip($year, $tempDir, $timestamp);
            $files[] = $this->build_seven_data_excel_zip($year, $tempDir, $timestamp);
            $files[] = $this->build_machine_use_excel_zip($year, $tempDir, $timestamp);

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

            // Stream ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="All_Reports_' . $timestamp . '.zip"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            exit;
        } catch (Exception $e) {
            log_message('error', 'ZIP export failed: ' . $e->getMessage());
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

    private function build_productivity_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');
        $std = $this->mr->get_productivity_full_excel_data($year);
        $nett = $this->mr->get_productivity_nett_excel_data($year);
        $gross = $this->mr->get_productivity_gross_excel_data($year);
        if ($std->num_rows() == 0 && $nett->num_rows() == 0 && $gross->num_rows() == 0) {
            throw new Exception('No productivity data for ' . $year);
        }

        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("DPR System")->setTitle("Productivity Chart " . $year);

        $yy2 = substr($year, -2);
        $currentMonth = intval(date('n'));
        $monthAbbr = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];

        $st = [
            'header' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '1F4E79']],
            ],
            'title' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '2E75B6']],
            ],
            'totals' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D6E4F0']],
            ],
            'odd' => [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFFFF']],
            ],
            'even' => [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'F2F2F2']],
            ],
        ];
        $baseHeaders = ['YY','Product ID','Product Name','Max SPM-Std','Max SPM Std2','Tool','Mm Chk','Min SPM-Set','Max SPM-Set'];
        for ($m=1; $m<=12; $m++) { $baseHeaders[] = sprintf('%d.0', $m); }

        // Helper to write productivity data rows
        $writeProdRows = function($sheet, $data, $headers, $startRow) {
            $r = $startRow;
            foreach ($data->result_array() as $row) {
                $col = 'A';
                foreach ($headers as $h) {
                    $key = str_replace('.0', '', $h);
                    $key = str_replace('-', '_', $key);
                    $key = str_replace(' ', '_', $key);
                    if (is_numeric($key) && intval($key) >= 1 && intval($key) <= 12) {
                        $key = sprintf('%02d', intval($key));
                    }
                    $val = isset($row[$key]) ? $row[$key] : '';
                    if (is_numeric($val) && $val == 0) $val = '';
                    $sheet->setCellValue($col++ . $r, $val);
                }
                $r++;
            }
            return $r;
        };

        // Helper to write PT CKU header block
        $writeHeaderBlock = function($sheet) use ($st) {
            $sheet->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
            $sheet->setCellValue('A2', 'Plastic Division');
            $sheet->setCellValue('A3', 'Production  Dept.');
            $sheet->getStyle('A1:A3')->applyFromArray($st['header']);
        };

        $sheetIdx = 0;

        // === Pre-fetch all monthly data in bulk (reduces ~30 queries to 3) ===
        $allLpRaw = $this->mr->get_10_lowest_productivity_all($year)->result_array();
        $allRankedRaw = $this->mr->get_monthly_productivity_ranked_all($year)->result_array();
        $allTotalsRaw = $this->mr->get_monthly_productivity_totals_all($year)->result_array();

        $lpByMonth = [];
        foreach ($allLpRaw as $row) {
            $m = intval($row['bulan']);
            $lpByMonth[$m][] = $row;
        }
        foreach ($lpByMonth as &$lpRows) {
            usort($lpRows, function($a, $b) {
                return floatval($a['Ttl_Net_Prod']) - floatval($b['Ttl_Net_Prod']);
            });
            $lpRows = array_slice($lpRows, 0, 10);
        }
        unset($lpRows);

        $rankedByMonth = [];
        foreach ($allRankedRaw as $row) {
            $m = intval($row['bulan']);
            $rankedByMonth[$m][] = $row;
        }
        foreach ($rankedByMonth as &$rRows) {
            usort($rRows, function($a, $b) {
                return floatval($a['Net_Productivity']) - floatval($b['Net_Productivity']);
            });
            $rRows = array_slice($rRows, 0, 10);
        }
        unset($rRows);

        $rankedByMonthGross = [];
        foreach ($allRankedRaw as $row) {
            $m = intval($row['bulan']);
            $rankedByMonthGross[$m][] = $row;
        }
        foreach ($rankedByMonthGross as &$grRows) {
            usort($grRows, function($a, $b) {
                return floatval($a['Gross_Productivity']) - floatval($b['Gross_Productivity']);
            });
            $grRows = array_slice($grRows, 0, 10);
        }
        unset($grRows);

        $totalsByMonth = [];
        foreach ($allTotalsRaw as $row) {
            $totalsByMonth[intval($row['bulan'])] = $row;
        }

        // === Sheet 1: 1-Std Prod ===
        $excel->setActiveSheetIndex($sheetIdx);
        $s = $excel->getActiveSheet(); $s->setTitle('1-Std Prod');
        $col = 'A'; foreach ($baseHeaders as $h) { $s->setCellValue($col++ . '1', $h); }
        $writeProdRows($s, $std, $baseHeaders, 2);
        $sheetIdx++;

        // === Sheet 2: 2-Net Prod ===
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s = $excel->getActiveSheet(); $s->setTitle('2-Net Prod');
        $col = 'A'; foreach ($baseHeaders as $h) { $s->setCellValue($col++ . '1', $h); }
        $writeProdRows($s, $nett, $baseHeaders, 2);
        $sheetIdx++;

        // === Sheet 3: 3-Gros Prod ===
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s = $excel->getActiveSheet(); $s->setTitle('3-Gros Prod');
        $col = 'A'; foreach ($baseHeaders as $h) { $s->setCellValue($col++ . '1', $h); }
        $writeProdRows($s, $gross, $baseHeaders, 2);
        $sheetIdx++;

        // === Sheet 4: Productivity Graph ===
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s = $excel->getActiveSheet(); $s->setTitle('Productivity Graph');
        $writeHeaderBlock($s);
        $s->setCellValue('E5', 'Production Report:');
        $s->setCellValue('F5', $year . '-01-01');
        $s->getStyle('E5:F5')->applyFromArray($st['header']);

        $s->setCellValue('H45', 'Month');
        $s->setCellValue('H45', 'Month');
        $s->setCellValue('H45', 'Month');

        $colStart = ord('H');
        for ($m = 1; $m <= 12; $m++) {
            $col = chr($colStart + $m - 1);
            $s->setCellValue($col . '45', $monthAbbr[$m - 1]);
        }
        $s->setCellValue(chr($colStart + 12) . '45', 'Total');
        $s->setCellValue(chr($colStart + 12) . '44', 'Total');
        $s->getStyle('H45:' . chr($colStart + 12) . '45')->applyFromArray($st['title']);

        $s->setCellValue('E46', 'Net Prod Actual');
        $s->setCellValue('E47', 'Gross Prod Actual');
        $s->setCellValue('E48', 'Net Prod Target');
        $s->setCellValue('E49', 'Gross Prod Target');
        $s->getStyle('E46:E49')->applyFromArray($st['title']);

        $netSum = 0; $netCnt = 0; $grossSum = 0; $grossCnt = 0;
        for ($m = 1; $m <= 12; $m++) {
            $col = chr($colStart + $m - 1);
            $netVal = isset($totalsByMonth[$m]) ? floatval($totalsByMonth[$m]['Ttl_Net_Productivity']) : 0;
            $grossVal = isset($totalsByMonth[$m]) ? floatval($totalsByMonth[$m]['Ttl_Gross_Productivity']) : 0;
            $s->setCellValue($col . '46', $netVal > 0 ? round($netVal, 2) : '');
            $s->setCellValue($col . '47', $grossVal > 0 ? round($grossVal, 2) : '');
            $s->setCellValue($col . '48', 100);
            $s->setCellValue($col . '49', 95);
            if ($netVal > 0) { $netSum += $netVal; $netCnt++; }
            if ($grossVal > 0) { $grossSum += $grossVal; $grossCnt++; }
        }
        $totalCol = chr($colStart + 12);
        $s->setCellValue($totalCol . '46', $netCnt > 0 ? round($netSum / $netCnt, 2) : '');
        $s->setCellValue($totalCol . '47', $grossCnt > 0 ? round($grossSum / $grossCnt, 2) : '');
        $s->setCellValue($totalCol . '48', 100);
        $s->setCellValue($totalCol . '49', 95);
        for ($dr = 46; $dr <= 49; $dr++) {
            $s->getStyle('H' . $dr . ':' . $totalCol . $dr)->applyFromArray($st[$dr % 2 === 0 ? 'odd' : 'even']);
        }

        // === Part Table (below chart data) ===
        $partData = $this->mr->get_productivity_part_table_data($year)->result_array();
        $partRows = [];
        foreach ($partData as $pr) {
            $partRows[] = $pr;
        }
        $totalParts = count($partRows);

        $partStartRow = 52;

        // Company headers
        $s->setCellValue('A' . $partStartRow, 'PT Ciptajaya Keasindo Utama');
        $s->setCellValue('A' . ($partStartRow + 1), 'Plastic Division');
        $s->setCellValue('A' . ($partStartRow + 2), 'Production  Dept.');

        $headerRow1 = $partStartRow + 5; // "Month" row
        $headerRow2 = $partStartRow + 6; // Jan...Rank Feb...Rank
        $headerRow3 = $partStartRow + 7; // No. PartID PartName StdCt Net Gros...

        $monthAbbrShort = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // Per-month block: 11 columns each
        // Block columns: [empty] [PartID] [PartName] [StdCt] [Net] [Gros] [Net%] [Gros%] [empty] [NetRank] [GrosRank] [empty]
        $blockCols = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        // Fixed left columns: A=empty, B=empty, C=No., D=Part ID, E=Part Name
        // First month block starts at column F (index 5)

        $s->setCellValue('F' . $headerRow1, 'Month');
        $s->setCellValue('F' . $headerRow1, 'Month');

        for ($mb = 0; $mb < 12; $mb++) {
            $blockStartCol = 6 + ($mb * 11); // F=6, Q=17, AB=28, ...
            $monthLabelCol = $blockStartCol;
            $rankLabelCol = $blockStartCol + 9;

            $s->setCellValueByColumnAndRow($monthLabelCol, $headerRow2, $monthAbbrShort[$mb]);
            $s->setCellValueByColumnAndRow($rankLabelCol, $headerRow2, 'Rank');
        }

        // Total block header
        $totalBlockStartCol = 6 + (12 * 11);
        $s->setCellValueByColumnAndRow($totalBlockStartCol, $headerRow1, 'Total');
        $s->setCellValueByColumnAndRow($totalBlockStartCol, $headerRow2, 'Total');

        // Row 3 headers (column names) - only for first month block (reference shows headers repeated in first block only)
        $s->setCellValue('C' . $headerRow3, 'No.');
        $s->setCellValue('D' . $headerRow3, 'Part ID');
        $s->setCellValue('E' . $headerRow3, 'Part Name');

        $firstBlockStartCol = 6; // F
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 3, $headerRow3, 'Std Ct');
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 4, $headerRow3, 'Net ');
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 5, $headerRow3, 'Gros');
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 6, $headerRow3, 'Net %');
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 7, $headerRow3, 'Gros %');
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 9, $headerRow3, 'Net');
        $s->setCellValueByColumnAndRow($firstBlockStartCol + 10, $headerRow3, 'Gros');

        // Total block headers
        $s->setCellValueByColumnAndRow($totalBlockStartCol + 1, $headerRow3, 'Std Ct');
        $s->setCellValueByColumnAndRow($totalBlockStartCol + 2, $headerRow3, 'Net ');
        $s->setCellValueByColumnAndRow($totalBlockStartCol + 3, $headerRow3, 'Gros');

        // Prepare rank arrays per month
        $netRanksByMonth = [];
        $grossRanksByMonth = [];
        for ($mb = 1; $mb <= 12; $mb++) {
            $netPcts = [];
            $grossPcts = [];
            foreach ($partRows as $idx => $pr) {
                $stdCt = floatval($pr['SPMStd']);
                $netVal = floatval($pr['nett_' . $mb]);
                $grossVal = floatval($pr['gross_' . $mb]);
                $netPcts[$idx] = ($netVal > 0) ? ($stdCt / $netVal * 100) : -1;
                $grossPcts[$idx] = ($grossVal > 0) ? ($stdCt / $grossVal * 100) : -1;
            }
            asort($netPcts);
            asort($grossPcts);
            $rank = 1;
            $noDataRank = $totalParts;
            $netRanks = [];
            foreach ($netPcts as $idx => $pct) {
                $netRanks[$idx] = ($pct >= 0) ? $rank : $noDataRank;
                $rank++;
            }
            $rank = 1;
            $grossRanks = [];
            foreach ($grossPcts as $idx => $pct) {
                $grossRanks[$idx] = ($pct >= 0) ? $rank : $noDataRank;
                $rank++;
            }
            $netRanksByMonth[$mb] = $netRanks;
            $grossRanksByMonth[$mb] = $grossRanks;
        }

        // Write data rows
        $dataStartRow = $headerRow3 + 1;
        $sumStdCtByMonth = array_fill(1, 12, 0);
        $sumNetByMonth = array_fill(1, 12, 0);
        $sumGrossByMonth = array_fill(1, 12, 0);

        foreach ($partRows as $rowIdx => $pr) {
            $r = $dataStartRow + $rowIdx;
            $s->setCellValue('C' . $r, $rowIdx + 1);
            $s->setCellValueExplicit('D' . $r, $pr['kode_product'], PHPExcel_Cell_DataType::TYPE_STRING);
            $s->setCellValue('E' . $r, $pr['nama_product']);
            $stdCt = floatval($pr['SPMStd']);

            for ($mb = 1; $mb <= 12; $mb++) {
                $blockStartCol = 6 + (($mb - 1) * 11);
                $netVal = floatval($pr['nett_' . $mb]);
                $grossVal = floatval($pr['gross_' . $mb]);

                $s->setCellValueExplicitByColumnAndRow($blockStartCol + 1, $r, $pr['kode_product'], PHPExcel_Cell_DataType::TYPE_STRING);
                $s->setCellValueByColumnAndRow($blockStartCol + 2, $r, $pr['nama_product']);
                $s->setCellValueByColumnAndRow($blockStartCol + 3, $r, $stdCt > 0 ? $stdCt : '');
                $s->setCellValueByColumnAndRow($blockStartCol + 4, $r, $netVal > 0 ? $netVal : '');
                $s->setCellValueByColumnAndRow($blockStartCol + 5, $r, $grossVal > 0 ? $grossVal : '');

                $netPct = ($netVal > 0) ? round($stdCt / $netVal * 100, 2) : '';
                $grossPct = ($grossVal > 0) ? round($stdCt / $grossVal * 100, 2) : '';

                $s->setCellValueByColumnAndRow($blockStartCol + 6, $r, $netPct);
                $s->setCellValueByColumnAndRow($blockStartCol + 7, $r, $grossPct);
                $s->setCellValueByColumnAndRow($blockStartCol + 9, $r, $netRanksByMonth[$mb][$rowIdx]);
                $s->setCellValueByColumnAndRow($blockStartCol + 10, $r, $grossRanksByMonth[$mb][$rowIdx]);

                $sumStdCtByMonth[$mb] += $stdCt;
                $sumNetByMonth[$mb] += $netVal;
                $sumGrossByMonth[$mb] += $grossVal;
            }

            // Total block per part
            $totalNet = 0; $totalGross = 0;
            for ($mb = 1; $mb <= 12; $mb++) {
                $totalNet += floatval($pr['nett_' . $mb]);
                $totalGross += floatval($pr['gross_' . $mb]);
            }
            $totalStdCt = $stdCt * 12;
            $s->setCellValueByColumnAndRow($totalBlockStartCol + 1, $r, $stdCt > 0 ? round($totalStdCt, 1) : '');
            $s->setCellValueByColumnAndRow($totalBlockStartCol + 2, $r, $totalNet > 0 ? round($totalNet) : '');
            $s->setCellValueByColumnAndRow($totalBlockStartCol + 3, $r, $totalGross > 0 ? round($totalGross) : '');
        }

        // TOTAL row
        $totalRow = $dataStartRow + $totalParts;
        $s->setCellValue('C' . $totalRow, $totalParts);
        $grandTotalStdCt = 0; $grandTotalNet = 0; $grandTotalGross = 0;

        for ($mb = 1; $mb <= 12; $mb++) {
            $blockStartCol = 6 + (($mb - 1) * 11);
            $s->setCellValueByColumnAndRow($blockStartCol + 3, $totalRow, $sumStdCtByMonth[$mb] > 0 ? round($sumStdCtByMonth[$mb], 1) : 0);
            $s->setCellValueByColumnAndRow($blockStartCol + 4, $totalRow, $sumNetByMonth[$mb] > 0 ? round($sumNetByMonth[$mb]) : 0);
            $s->setCellValueByColumnAndRow($blockStartCol + 5, $totalRow, $sumGrossByMonth[$mb] > 0 ? round($sumGrossByMonth[$mb]) : 0);

            $grandTotalStdCt += $sumStdCtByMonth[$mb];
            $grandTotalNet += $sumNetByMonth[$mb];
            $grandTotalGross += $sumGrossByMonth[$mb];
        }
        $s->setCellValueByColumnAndRow($totalBlockStartCol + 1, $totalRow, round($grandTotalStdCt, 1));
        $s->setCellValueByColumnAndRow($totalBlockStartCol + 2, $totalRow, round($grandTotalNet));
        $s->setCellValueByColumnAndRow($totalBlockStartCol + 3, $totalRow, round($grandTotalGross));

        // PRODUCTIVITY row
        $prodRow = $totalRow + 1;
        $s->setCellValue('E' . $prodRow, 'PRODUCTIVITY');

        for ($mb = 1; $mb <= 12; $mb++) {
            $blockStartCol = 6 + (($mb - 1) * 11);
            $prodNet = ($sumNetByMonth[$mb] > 0) ? round($sumStdCtByMonth[$mb] / $sumNetByMonth[$mb] * 100, 2) : '';
            $prodGross = ($sumGrossByMonth[$mb] > 0) ? round($sumStdCtByMonth[$mb] / $sumGrossByMonth[$mb] * 100, 2) : '';
            $s->setCellValueByColumnAndRow($blockStartCol + 4, $prodRow, $prodNet);
            $s->setCellValueByColumnAndRow($blockStartCol + 5, $prodRow, $prodGross);
        }

        // Chart will be added after totalsByMonth is populated (below)
        $prodGraphSheet = $s;

        $sheetIdx++;

        // === Sheet 5: Productivity ===
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s = $excel->getActiveSheet(); $s->setTitle('Productivity');
        $writeHeaderBlock($s);
        $s->setCellValue('A5', 'Production : Nett Productivity & Gross Productivity  (Year ' . $year . ')');
        $s->getStyle('A5')->applyFromArray($st['header']);

        $s1Months = [1,2,3,4,5,6];
        $s2Months = [7,8,9,10,11,12];

        // Semester 1 - Net Productivity
        $s->setCellValue('D42', 'Month');
        for ($i = 0; $i < 6; $i++) {
            $s->setCellValue(chr(ord('D') + $i) . '42', $monthAbbr[$s1Months[$i] - 1]);
        }
        $s->setCellValue(chr(ord('D') + 6) . '42', 'Achieve. smt. I');
        $s->getStyle('D42:' . chr(ord('D') + 6) . '42')->applyFromArray($st['title']);
        $s->setCellValue('B43', 'Target Nett Prod. (%)');
        for ($i = 0; $i < 6; $i++) { $s->setCellValue(chr(ord('D') + $i) . '43', 100); }
        $s->setCellValue(chr(ord('D') + 6) . '43', 100);
        $s->getStyle('D43:' . chr(ord('D') + 6) . '43')->applyFromArray($st['even']);
        $s->setCellValue('B44', 'Nett Productivity  (%)');
        $s1NetSum = 0; $s1NetCnt = 0;
        for ($i = 0; $i < 6; $i++) {
            $m = $s1Months[$i];
            $val = isset($totalsByMonth[$m]) ? floatval($totalsByMonth[$m]['Ttl_Net_Productivity']) : 0;
            $s->setCellValue(chr(ord('D') + $i) . '44', $val > 0 ? round($val, 2) : '');
            if ($val > 0) { $s1NetSum += $val; $s1NetCnt++; }
        }
        $s->setCellValue(chr(ord('D') + 6) . '44', $s1NetCnt > 0 ? round($s1NetSum / $s1NetCnt, 2) : '');
        $s->getStyle('D44:' . chr(ord('D') + 6) . '44')->applyFromArray($st['odd']);

        // Semester 1 - Gross Productivity
        for ($i = 0; $i < 6; $i++) {
            $s->setCellValue(chr(ord('D') + $i) . '46', $monthAbbr[$s1Months[$i] - 1]);
        }
        $s->setCellValue(chr(ord('D') + 6) . '46', 'Achieve. smt. I');
        $s->getStyle('D46:' . chr(ord('D') + 6) . '46')->applyFromArray($st['title']);
        $s->setCellValue('B47', 'Target Gross Productivity (%)');
        for ($i = 0; $i < 6; $i++) { $s->setCellValue(chr(ord('D') + $i) . '47', 95); }
        $s->setCellValue(chr(ord('D') + 6) . '47', 95);
        $s->getStyle('D47:' . chr(ord('D') + 6) . '47')->applyFromArray($st['even']);
        $s->setCellValue('B48', 'Gross Productivity (%)');
        $s1GrossSum = 0; $s1GrossCnt = 0;
        for ($i = 0; $i < 6; $i++) {
            $m = $s1Months[$i];
            $val = isset($totalsByMonth[$m]) ? floatval($totalsByMonth[$m]['Ttl_Gross_Productivity']) : 0;
            $s->setCellValue(chr(ord('D') + $i) . '48', $val > 0 ? round($val, 2) : '');
            if ($val > 0) { $s1GrossSum += $val; $s1GrossCnt++; }
        }
        $s->setCellValue(chr(ord('D') + 6) . '48', $s1GrossCnt > 0 ? round($s1GrossSum / $s1GrossCnt, 2) : '');
        $s->getStyle('D48:' . chr(ord('D') + 6) . '48')->applyFromArray($st['odd']);

        // Semester 2 - Net Productivity
        $s->setCellValue('D50', 'Achieve SMt I');
        for ($i = 0; $i < 6; $i++) {
            $s->setCellValue(chr(ord('D') + 1 + $i) . '50', $monthAbbr[$s2Months[$i] - 1]);
        }
        $s->setCellValue(chr(ord('D') + 7) . '50', 'Achieve SMt II');
        $s->setCellValue(chr(ord('D') + 8) . '50', 'Total Achieve');
        $s->getStyle('D50:' . chr(ord('D') + 8) . '50')->applyFromArray($st['title']);
        $s->setCellValue('B51', 'Target Nett Prod. (%)');
        $s->setCellValue('D51', round($s1NetCnt > 0 ? $s1NetSum / $s1NetCnt : 0, 2));
        for ($i = 0; $i < 6; $i++) { $s->setCellValue(chr(ord('D') + 1 + $i) . '51', 100); }
        $s->setCellValue(chr(ord('D') + 7) . '51', 100);
        $s->setCellValue(chr(ord('D') + 8) . '51', 100);
        $s->getStyle('D51:' . chr(ord('D') + 8) . '51')->applyFromArray($st['even']);
        $s->setCellValue('B52', 'Nett Productivity  (%)');
        $s->setCellValue('D52', round($s1NetCnt > 0 ? $s1NetSum / $s1NetCnt : 0, 2));
        $s2NetSum = 0; $s2NetCnt = 0;
        for ($i = 0; $i < 6; $i++) {
            $m = $s2Months[$i];
            $val = isset($totalsByMonth[$m]) ? floatval($totalsByMonth[$m]['Ttl_Net_Productivity']) : 0;
            $s->setCellValue(chr(ord('D') + 1 + $i) . '52', $val > 0 ? round($val, 2) : '');
            if ($val > 0) { $s2NetSum += $val; $s2NetCnt++; }
        }
        $s->setCellValue(chr(ord('D') + 7) . '52', $s2NetCnt > 0 ? round($s2NetSum / $s2NetCnt, 2) : '');
        $allNetAvg = ($s1NetCnt + $s2NetCnt) > 0 ? round(($s1NetSum + $s2NetSum) / ($s1NetCnt + $s2NetCnt), 2) : '';
        $s->setCellValue(chr(ord('D') + 8) . '52', $allNetAvg);
        $s->getStyle('D52:' . chr(ord('D') + 8) . '52')->applyFromArray($st['odd']);

        // Semester 2 - Gross Productivity
        $s->setCellValue('D54', 'Achieve SMt I');
        for ($i = 0; $i < 6; $i++) {
            $s->setCellValue(chr(ord('D') + 1 + $i) . '54', $monthAbbr[$s2Months[$i] - 1]);
        }
        $s->setCellValue(chr(ord('D') + 7) . '54', 'Achieve SMt II');
        $s->setCellValue(chr(ord('D') + 8) . '54', 'Total Achieve');
        $s->getStyle('D54:' . chr(ord('D') + 8) . '54')->applyFromArray($st['title']);
        $s->setCellValue('B55', 'Target Gross Productivity (%)');
        $s->setCellValue('D55', round($s1GrossCnt > 0 ? $s1GrossSum / $s1GrossCnt : 0, 2));
        for ($i = 0; $i < 6; $i++) { $s->setCellValue(chr(ord('D') + 1 + $i) . '55', 95); }
        $s->setCellValue(chr(ord('D') + 7) . '55', 95);
        $s->setCellValue(chr(ord('D') + 8) . '55', 95);
        $s->getStyle('D55:' . chr(ord('D') + 8) . '55')->applyFromArray($st['even']);
        $s->setCellValue('B56', 'Gross Productivity (%)');
        $s->setCellValue('D56', round($s1GrossCnt > 0 ? $s1GrossSum / $s1GrossCnt : 0, 2));
        $s2GrossSum = 0; $s2GrossCnt = 0;
        for ($i = 0; $i < 6; $i++) {
            $m = $s2Months[$i];
            $val = isset($totalsByMonth[$m]) ? floatval($totalsByMonth[$m]['Ttl_Gross_Productivity']) : 0;
            $s->setCellValue(chr(ord('D') + 1 + $i) . '56', $val > 0 ? round($val, 2) : '');
            if ($val > 0) { $s2GrossSum += $val; $s2GrossCnt++; }
        }
        $s->setCellValue(chr(ord('D') + 7) . '56', $s2GrossCnt > 0 ? round($s2GrossSum / $s2GrossCnt, 2) : '');
        $allGrossAvg = ($s1GrossCnt + $s2GrossCnt) > 0 ? round(($s1GrossSum + $s2GrossSum) / ($s1GrossCnt + $s2GrossCnt), 2) : '';
        $s->setCellValue(chr(ord('D') + 8) . '56', $allGrossAvg);
        $s->getStyle('D56:' . chr(ord('D') + 8) . '56')->applyFromArray($st['odd']);

        // Improvement List header
        $s->setCellValue('B61', 'IMPROVEMENT LIST (BASED ON WORST PROBLEM) IN MONTH.......');
        $s->setCellValue('B63', 'Period');
        $s->setCellValue('C63', 'No');
        $s->setCellValue('D63', 'Prod.ID');
        $s->setCellValue('E63', 'Prod.Name');
        $s->setCellValue('F63', 'Nett/Gross Produktivity');
        $s->setCellValue('G63', 'Problem');
        $s->setCellValue('H63', 'Root Cause');
        $s->setCellValue('J63', 'Corrective Action');
        $s->setCellValue('L63', 'PIC');
        $s->setCellValue('M63', 'Date');
        $s->setCellValue('N63', 'Status');
        $s->setCellValue('J64', 'Temporary');
        $s->setCellValue('K64', 'Permanent');

        // Chart will be added after totalsByMonth is populated (below)
        $prodSheet = $s;

        $sheetIdx++;

        // === Add Chart to Productivity Graph sheet ===
        $graphCatValues = array();
        $graphNetVals = array();
        $graphGrossVals = array();
        for ($gm = 1; $gm <= 12; $gm++) {
            $graphCatValues[] = $monthAbbr[$gm - 1];
            $graphNetVals[] = isset($totalsByMonth[$gm]) ? floatval($totalsByMonth[$gm]['Ttl_Net_Productivity']) : 0;
            $graphGrossVals[] = isset($totalsByMonth[$gm]) ? floatval($totalsByMonth[$gm]['Ttl_Gross_Productivity']) : 0;
        }
        $gNL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gNL->setDataValues(array('Net Prod Actual'), false);
        $gGL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gGL->setDataValues(array('Gross Prod Actual'), false);
        $gCat = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gCat->setDataValues($graphCatValues, false);
        $gNV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $gNV->setDataValues($graphNetVals, false);
        $gGV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $gGV->setDataValues($graphGrossVals, false);
        $barS = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_BARCHART, PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
            array(0, 1), array($gNL, $gGL), array($gCat, $gCat), array($gNV, $gGV)
        );
        $barS->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
        $gNTL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gNTL->setDataValues(array('Net Target'), false);
        $gGTL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gGTL->setDataValues(array('Gross Target'), false);
        $gNTV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $gNTV->setDataValues(array_fill(0, 12, 100), false);
        $gGTV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $gGTV->setDataValues(array_fill(0, 12, 95), false);
        $lineS = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            array(0, 1), array($gNTL, $gGTL), array($gCat, $gCat), array($gNTV, $gGTV)
        );
        $gPlotArea = new PHPExcel_Chart_PlotArea(null, array($barS, $lineS));
        $gLegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
        $gChart = new PHPExcel_Chart(
            'productivity_chart', new PHPExcel_Chart_Title('Nett & Gross Productivity - Year ' . $year),
            $gLegend, $gPlotArea, true, '0', null, null,
            new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
        );
        $gChart->setTopLeftPosition('A7');
        $gChart->setBottomRightPosition('K40');
        $prodGraphSheet->addChart($gChart);

        // === Add Charts to Productivity sheet (4 separate charts) ===
        // Helper to build a single bar+line chart
        $buildProdChart = function($chartName, $title, $catLabels, $actualLabel, $actualValues, $targetLabel, $targetValues) {
            $tL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
            $tL->setDataValues(array($targetLabel), false);
            $cL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
            $cL->setDataValues($catLabels, false);
            $tV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
            $tV->setDataValues($targetValues, false);
            $bS = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_BARCHART, PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
                array(0), array($tL), array($cL), array($tV)
            );
            $bS->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
            $aL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
            $aL->setDataValues(array($actualLabel), false);
            $aV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
            $aV->setDataValues($actualValues, false);
            $lS = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                array(0), array($aL), array($cL), array($aV)
            );
            $pA = new PHPExcel_Chart_PlotArea(null, array($bS, $lS));
            $pLg = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
            $ch = new PHPExcel_Chart($chartName, new PHPExcel_Chart_Title($title), $pLg, $pA, true, '0', null, null,
                new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis());
            return $ch;
        };

        // S1 data (Jan-Jun)
        $s1CatLabels = array(); $s1NetVals = array(); $s1GrossVals = array();
        foreach ($s1Months as $pm) {
            $s1CatLabels[] = $monthAbbr[$pm - 1];
            $s1NetVals[] = isset($totalsByMonth[$pm]) ? floatval($totalsByMonth[$pm]['Ttl_Net_Productivity']) : 0;
            $s1GrossVals[] = isset($totalsByMonth[$pm]) ? floatval($totalsByMonth[$pm]['Ttl_Gross_Productivity']) : 0;
        }

        // S2 data (Jul-Dec)
        $s2CatLabels = array(); $s2NetVals = array(); $s2GrossVals = array();
        foreach ($s2Months as $pm) {
            $s2CatLabels[] = $monthAbbr[$pm - 1];
            $s2NetVals[] = isset($totalsByMonth[$pm]) ? floatval($totalsByMonth[$pm]['Ttl_Net_Productivity']) : 0;
            $s2GrossVals[] = isset($totalsByMonth[$pm]) ? floatval($totalsByMonth[$pm]['Ttl_Gross_Productivity']) : 0;
        }

        // Chart 1: S1 Nett
        $ch1 = $buildProdChart('prod_s1_nett', 'Nett Productivity Semester I - ' . $year, $s1CatLabels, 'Nett Prod', $s1NetVals, 'Target', array_fill(0, 6, 100));
        $ch1->setTopLeftPosition('A7');
        $ch1->setBottomRightPosition('F22');
        $prodSheet->addChart($ch1);

        // Chart 2: S1 Gross
        $ch2 = $buildProdChart('prod_s1_gross', 'Gross Productivity Semester I - ' . $year, $s1CatLabels, 'Gross Prod', $s1GrossVals, 'Target', array_fill(0, 6, 95));
        $ch2->setTopLeftPosition('G7');
        $ch2->setBottomRightPosition('L22');
        $prodSheet->addChart($ch2);

        // Chart 3: S2 Nett
        $ch3 = $buildProdChart('prod_s2_nett', 'Nett Productivity Semester II - ' . $year, $s2CatLabels, 'Nett Prod', $s2NetVals, 'Target', array_fill(0, 6, 100));
        $ch3->setTopLeftPosition('A23');
        $ch3->setBottomRightPosition('F38');
        $prodSheet->addChart($ch3);

        // Chart 4: S2 Gross
        $ch4 = $buildProdChart('prod_s2_gross', 'Gross Productivity Semester II - ' . $year, $s2CatLabels, 'Gross Prod', $s2GrossVals, 'Target', array_fill(0, 6, 95));
        $ch4->setTopLeftPosition('G23');
        $ch4->setBottomRightPosition('L38');
        $prodSheet->addChart($ch4);

        // === Sheets 6-15: 10LP-{Mon}{YY} (10 Lowest Productivity per month) ===
        $lpHeaders = ['YYMM','Mo','Product ID','Product Name','Ttl Gros Prod','Ttl Net Prod','G-Prod','N-Prod','Max SPM Std','Min SPM Set','Max SPM Set','WH','OT','Qty Total','Qty OK','Qty Defect','Cavity','Customer'];
        for ($m = 1; $m <= 12; $m++) {
            $abbr = $monthAbbr[$m - 1];
            $sheetName = '10LP-' . $abbr . $yy2;
            $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
            $s = $excel->getActiveSheet(); $s->setTitle($sheetName);

            $col = 'A'; foreach ($lpHeaders as $h) { $s->setCellValue($col++ . '1', $h); }

            $lpData = isset($lpByMonth[$m]) ? $lpByMonth[$m] : [];
            $r = 2;
            foreach ($lpData as $row) {
                $s->setCellValue('A' . $r, $row['YYMM']);
                $s->setCellValue('B' . $r, $row['Mo']);
                $s->setCellValue('C' . $r, $row['Product_ID']);
                $s->setCellValue('D' . $r, $row['Product_Name']);
                $s->setCellValue('E' . $r, $row['Ttl_Gros_Prod'] > 0 ? rtrim(rtrim(number_format($row['Ttl_Gros_Prod'], 2), '0'), '.') . '%' : '');
                $s->setCellValue('F' . $r, $row['Ttl_Net_Prod'] > 0 ? rtrim(rtrim(number_format($row['Ttl_Net_Prod'], 2), '0'), '.') . '%' : '');
                $s->setCellValue('G' . $r, $row['G_Prod']);
                $s->setCellValue('H' . $r, $row['N_Prod']);
                $s->setCellValue('I' . $r, $row['Max_SPM_Std']);
                $s->setCellValue('J' . $r, $row['Min_SPM_Set']);
                $s->setCellValue('K' . $r, $row['Max_SPM_Set']);
                $s->setCellValue('L' . $r, $row['WH']);
                $s->setCellValue('M' . $r, $row['OT']);
                $s->setCellValue('N' . $r, $row['Qty_Total']);
                $s->setCellValue('O' . $r, $row['Qty_OK']);
                $s->setCellValue('P' . $r, $row['Qty_Defect']);
                $s->setCellValue('Q' . $r, $row['Cavity']);
                $s->setCellValue('R' . $r, $row['Customer']);
                $r++;
            }
            $sheetIdx++;
        }

        // === Sheets 16-27: {Mon}{YY} (Monthly detail with ranked products) ===
        for ($m = 1; $m <= 12; $m++) {
            $abbr = $monthAbbr[$m - 1];
            $sheetName = $abbr . $yy2;
            $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
            $s = $excel->getActiveSheet(); $s->setTitle($sheetName);

            $writeHeaderBlock($s);

            $monthDate = $year . '-' . sprintf('%02d', $m) . '-01';
            $s->setCellValue('E5', 'Production Report:');
            $s->setCellValue('F5', $monthDate);
            $s->getStyle('E5:F5')->applyFromArray($st['header']);

            // --- Section 1: 10 Worst Net Productivity ---
            $netRanked = isset($rankedByMonth[$m]) ? $rankedByMonth[$m] : [];
            if (!empty($netRanked)) {
                $netCatLabels = [];
                $netValues = [];
                foreach ($netRanked as $nr) {
                    $netCatLabels[] = $nr['Part_Name'];
                    $netValues[] = floatval($nr['Net_Productivity']);
                }
                $nCL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
                $nCL->setDataValues(array('Net Productivity'), false);
                $nCat = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
                $nCat->setDataValues($netCatLabels, false);
                $nVL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
                $nVL->setDataValues($netCatLabels, false);
                $nVV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
                $nVV->setDataValues($netValues, false);
                $netBarS = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
                    array(0), array($nCL), array($nCat), array($nVV)
                );
                $netBarS->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
                $netPlotArea = new PHPExcel_Chart_PlotArea(null, array($netBarS));
                $netLegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
                $netChart = new PHPExcel_Chart(
                    'net_prod_chart_' . $m,
                    new PHPExcel_Chart_Title('10 Worst Net Productivity'),
                    $netLegend, $netPlotArea, true, '0', null, null,
                    new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
                );
                $netChart->setTopLeftPosition('A7');
                $netChart->setBottomRightPosition('L31');
                $s->addChart($netChart);
            }

            $s->setCellValue('C33', 'RANK');
            $s->setCellValue('D33', $monthDate);

            if (isset($totalsByMonth[$m])) {
                $tRow = $totalsByMonth[$m];
                $s->setCellValue('C35', 'Ttl Net Productivity');
                $s->setCellValue('D35', $tRow['Ttl_Net_Productivity']);
                $s->setCellValue('E35', '%');
                $s->setCellValue('C36', 'Ttl Gross Productivity');
                $s->setCellValue('D36', $tRow['Ttl_Gross_Productivity']);
                $s->setCellValue('E36', '%');

                $s->setCellValue('C38', 'RANK');
                $s->setCellValue('D38', 'Part No');
                $s->setCellValue('E38', 'Part Name');
                $s->setCellValue('F38', 'Tool');
                $s->setCellValue('G38', 'Net Productivity');
                $s->setCellValue('H38', 'PROBLEM / CAUSES');

                $r = 39;
                $rank = 1;
                foreach ($netRanked as $row) {
                    $tool = ($row['Tool'] === '-' || $row['Tool'] === 0 || $row['Tool'] === '0') ? '-' : 'Tool ' . $row['Tool'];
                    $s->setCellValue('C' . $r, $rank);
                    $s->setCellValue('D' . $r, $row['Part_No']);
                    $s->setCellValue('E' . $r, $row['Part_Name']);
                    $s->setCellValue('F' . $r, $tool);
                    $s->setCellValue('G' . $r, $row['Net_Productivity']);
                    $s->getStyle('C' . $r . ':H' . $r)->applyFromArray($st[$rank % 2 === 1 ? 'odd' : 'even']);
                    $r++;
                    $rank++;
                }

                $s->getStyle('C33:D33')->applyFromArray($st['title']);
                $s->getStyle('C35:E36')->applyFromArray($st['totals']);
                $s->getStyle('C38:H38')->applyFromArray($st['title']);
            }

            // --- Section 2: 10 Worst Gross Productivity ---
            $s->setCellValue('A50', 'PT Ciptajaya Kreasindo Utama');
            $s->setCellValue('A51', 'Plastic Division');
            $s->setCellValue('A52', 'Production  Dept.');
            $s->getStyle('A50:A52')->applyFromArray($st['header']);
            $s->setCellValue('E55', 'Production Report:');
            $s->setCellValue('F55', $monthDate);
            $s->getStyle('E55:F55')->applyFromArray($st['header']);

            $grossRanked = isset($rankedByMonthGross[$m]) ? $rankedByMonthGross[$m] : [];
            if (!empty($grossRanked)) {
                $gCatLabels = [];
                $gValues = [];
                foreach ($grossRanked as $gr) {
                    $gCatLabels[] = $gr['Part_Name'];
                    $gValues[] = floatval($gr['Gross_Productivity']);
                }
                $gCL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
                $gCL->setDataValues(array('Gross Productivity'), false);
                $gCat = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
                $gCat->setDataValues($gCatLabels, false);
                $gVL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
                $gVL->setDataValues($gCatLabels, false);
                $gVV = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
                $gVV->setDataValues($gValues, false);
                $grossBarS = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
                    array(0), array($gCL), array($gCat), array($gVV)
                );
                $grossBarS->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
                $grossPlotArea = new PHPExcel_Chart_PlotArea(null, array($grossBarS));
                $grossLegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
                $grossChart = new PHPExcel_Chart(
                    'gross_prod_chart_' . $m,
                    new PHPExcel_Chart_Title('10 Worst Gross Productivity'),
                    $grossLegend, $grossPlotArea, true, '0', null, null,
                    new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
                );
                $grossChart->setTopLeftPosition('A57');
                $grossChart->setBottomRightPosition('L81');
                $s->addChart($grossChart);
            }

            $s->setCellValue('C83', 'RANK');
            $s->setCellValue('D83', $monthDate);

            if (isset($totalsByMonth[$m])) {
                $s->setCellValue('C85', 'Ttl Net Productivity');
                $s->setCellValue('D85', $tRow['Ttl_Net_Productivity']);
                $s->setCellValue('E85', '%');
                $s->setCellValue('C86', 'Ttl Gross Productivity');
                $s->setCellValue('D86', $tRow['Ttl_Gross_Productivity']);
                $s->setCellValue('E86', '%');

                $s->setCellValue('C88', 'RANK');
                $s->setCellValue('D88', 'Part No');
                $s->setCellValue('E88', 'Part Name');
                $s->setCellValue('F88', 'Tool');
                $s->setCellValue('G88', 'Gross Productivity');
                $s->setCellValue('H88', 'PROBLEM / CAUSES');

                $r = 89;
                $rank = 1;
                foreach ($grossRanked as $row) {
                    $tool = ($row['Tool'] === '-' || $row['Tool'] === 0 || $row['Tool'] === '0') ? '-' : 'Tool ' . $row['Tool'];
                    $s->setCellValue('C' . $r, $rank);
                    $s->setCellValue('D' . $r, $row['Part_No']);
                    $s->setCellValue('E' . $r, $row['Part_Name']);
                    $s->setCellValue('F' . $r, $tool);
                    $s->setCellValue('G' . $r, $row['Gross_Productivity']);
                    $s->getStyle('C' . $r . ':H' . $r)->applyFromArray($st[$rank % 2 === 1 ? 'odd' : 'even']);
                    $r++;
                    $rank++;
                }

                $s->getStyle('C83:D83')->applyFromArray($st['title']);
                $s->getStyle('C85:E86')->applyFromArray($st['totals']);
                $s->getStyle('C88:H88')->applyFromArray($st['title']);
            }

            $sheetIdx++;
        }

        // === Determine months with data (from nett rows) ===
        $monthsWithData = [];
        foreach ($nett->result_array() as $row) {
            for ($m=1; $m<=12; $m++) {
                $k = sprintf('%02d', $m);
                if (isset($row[$k]) && $row[$k] != 0 && !in_array($m, $monthsWithData)) {
                    $monthsWithData[] = $m;
                }
            }
        }
        sort($monthsWithData);

        // === Sheet: Total Net ===
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $sNet = $excel->getActiveSheet(); $sNet->setTitle('Total Net');
        $writeHeaderBlock($sNet);
        $sNet->setCellValue('E5', 'Production  Report:');
        $sNet->getStyle('E5')->applyFromArray($st['header']);
        $sNet->setCellValue('C7', 'RANK');
        $sNet->setCellValue('D7', 'NET PRODUCTIVITY');
        $sNet->getStyle('C7:D7')->applyFromArray($st['title']);

        $writeTotalRankBlock = function($sheet, $dataArr, $months, $startRow, $titleText) use ($year, $st) {
            $dateRow = $startRow;
            $headerRow = $startRow + 1;
            $dataStartRow = $startRow + 2;

            $col = 3; // D = column index 3
            foreach ($months as $m) {
                $dateVal = gmmktime(0, 0, 0, $m, 1, $year) / 86400 + 25569;
                $cellRef = PHPExcel_Cell::stringFromColumnIndex($col) . $dateRow;
                $sheet->setCellValue($cellRef, $dateVal);
                $sheet->getStyle($cellRef)->getNumberFormat()->setFormatCode('mmmm/yyyy');
                $sheet->getStyle($cellRef)->applyFromArray($st['title']);
                $col += 5;
            }

            $col = 3;
            for ($i = 0; $i < count($months); $i++) {
                $sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col) . $headerRow, 'Part No');
                $sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col + 1) . $headerRow, 'Part Name');
                $sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col + 2) . $headerRow, 'Tool');
                $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($col) . $headerRow . ':' . PHPExcel_Cell::stringFromColumnIndex($col + 2) . $headerRow)->applyFromArray($st['title']);
                $col += 5;
            }

            for ($rank = 1; $rank <= 10; $rank++) {
                $r = $dataStartRow + ($rank - 1);
                $sheet->setCellValue('C' . $r, $rank);

                $col = 3;
                foreach ($months as $m) {
                    $rankedData = isset($dataArr[$m]) ? $dataArr[$m] : [];
                    if (isset($rankedData[$rank - 1])) {
                        $row = $rankedData[$rank - 1];
                        $tool = ($row['Tool'] === '-' || $row['Tool'] === 0 || $row['Tool'] === '0') ? '-' : 'Tool ' . $row['Tool'];
                        $sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col) . $r, $row['Part_No']);
                        $sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col + 1) . $r, $row['Part_Name']);
                        $sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col + 2) . $r, $tool);
                    }
                    $col += 5;
                }
                $sheet->getStyle('C' . $r . ':H' . $r)->applyFromArray($st[$rank % 2 === 1 ? 'odd' : 'even']);
            }
        };

        $half1 = [1, 2, 3, 4, 5, 6];
        $half2 = [7, 8, 9, 10, 11, 12];
        $writeTotalRankBlock($sNet, $rankedByMonth, $half1, 13, 'NET PRODUCTIVITY');
        $writeTotalRankBlock($sNet, $rankedByMonth, $half2, 28, 'NET PRODUCTIVITY');
        $sheetIdx++;

        // === Sheet: Total Gross ===
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $sGross = $excel->getActiveSheet(); $sGross->setTitle('Total Gross');
        $writeHeaderBlock($sGross);
        $sGross->setCellValue('E5', 'Production  Report:');
        $sGross->getStyle('E5')->applyFromArray($st['header']);
        $sGross->setCellValue('C7', 'RANK');
        $sGross->setCellValue('D7', 'GROSS PRODUCTIVITY');
        $sGross->getStyle('C7:D7')->applyFromArray($st['title']);

        $writeTotalRankBlock($sGross, $rankedByMonthGross, $half1, 13, 'GROSS PRODUCTIVITY');
        $writeTotalRankBlock($sGross, $rankedByMonthGross, $half2, 28, 'GROSS PRODUCTIVITY');
        $sheetIdx++;

        $excel->setActiveSheetIndex(0);
        $path = $dir . DIRECTORY_SEPARATOR . '1-Productivity_Chart_' . $timestamp . '.xlsx';
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->setIncludeCharts(true);
        $writer->save($path);
        return ['path' => $path, 'name' => basename($path)];
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

        $prodTotals = $this->mr->total_produksi($year)->row_array();
        $okTotals = $this->mr->total_prod_ok($year)->row_array();
        $ngTotals = $this->mr->total_prod_ng($year)->row_array();
        $ppmTargetRow = $this->mr->ppm_fcost_target($year)->row_array();
        $fCostTargetRow = $this->mr->f_cost_target($year)->row_array();
        $fCostIntDefect = $this->mr->f_cost_int_defect($year)->row_array();

        $targetPpm = isset($ppmTargetRow['ppm_target']) ? floatval($ppmTargetRow['ppm_target']) : 2000;
        $targetFCost = isset($fCostTargetRow['f_cost_target']) ? floatval($fCostTargetRow['f_cost_target']) : 0;

        $monthProd = []; $monthOk = []; $monthNg = []; $monthPpm = []; $monthFCost = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthProd[$m] = floatval($prodTotals['total_prod' . $m] ?? 0);
            $monthOk[$m] = floatval($okTotals['ok' . $m] ?? 0);
            $monthNg[$m] = floatval($ngTotals['ng' . $m] ?? 0);
            $monthPpm[$m] = $monthProd[$m] > 0 ? ($monthNg[$m] / $monthProd[$m]) * 1000000 : 0;
            $monthFCost[$m] = floatval($fCostIntDefect['total' . $m] ?? 0);
        }

        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("DPR System")->setTitle("Production Qty & PPM " . $year);

        $stStyle = [
            'header' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '1F4E79']],
            ],
            'title' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '2E75B6']],
            ],
            'totals' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D6E4F0']],
            ],
            'odd' => [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFFFF']],
            ],
            'even' => [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'F2F2F2']],
            ],
        ];

        $makeSheet = function($sheet, $data, $hasPrice) use ($stStyle) {
            $headers = ['YY', 'Product ID', 'Product Name', 'Max SPM-Std', 'Max SPM Std2'];
            if ($hasPrice) $headers[] = 'Price';
            $headers[] = 'Tool';
            $headers[] = 'Min SPM-Set';
            $headers[] = 'Max SPM-Set';
            $headers[] = 'Tot';
            for ($m=1; $m<=12; $m++) { $headers[] = sprintf('%d.0', $m); }

            $col = 'A';
            foreach ($headers as $h) { $sheet->setCellValue($col++ . '1', $h); }

            $r = 2;
            foreach ($data->result_array() as $row) {
                $col = 'A';
                $sheet->setCellValue($col++ . $r, $row['YY']);
                $sheet->setCellValue($col++ . $r, $row['Product_ID']);
                $sheet->setCellValue($col++ . $r, $row['Product_Name']);
                $sheet->setCellValue($col++ . $r, $row['Max_SPM_Std']);
                $sheet->setCellValue($col++ . $r, $row['Max_SPM_Std2']);
                if ($hasPrice) $sheet->setCellValue($col++ . $r, $row['Price']);
                $sheet->setCellValue($col++ . $r, $row['Tool']);
                $sheet->setCellValue($col++ . $r, $row['Min_SPM_Set']);
                $sheet->setCellValue($col++ . $r, $row['Max_SPM_Set']);

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
            for ($c = 'A'; $c <= $lastCol; $c++) {
                $sheet->getColumnDimension($c)->setAutoSize(true);
            }
        };

        $excel->setActiveSheetIndex(0);
        $s1 = $excel->getActiveSheet(); $s1->setTitle('4-Ttl Prod');
        $makeSheet($s1, $total, true);

        $excel->createSheet(); $excel->setActiveSheetIndex(1);
        $s2 = $excel->getActiveSheet(); $s2->setTitle('5-Ttl Prod OK');
        $makeSheet($s2, $ok, true);

        $excel->createSheet(); $excel->setActiveSheetIndex(2);
        $s3 = $excel->getActiveSheet(); $s3->setTitle('6-Ttl Prod NG');
        $makeSheet($s3, $ng, true);

        // === Defect Level Sheet ===
        $excel->createSheet(); $excel->setActiveSheetIndex(3);
        $sD = $excel->getActiveSheet(); $sD->setTitle('Defect Level');

        $sD->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sD->setCellValue('A2', 'Plastic Division');
        $sD->setCellValue('A3', 'Production  Dept.');
        $sD->setCellValue('A5', 'Production  Internal Defect  (Year ' . $year . ')');

        $monthAbbr = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agust','Sep','Okt','Nop','Des'];

        // --- Semester I block (Jan-Jun) ---
        $s1StartCol = 3; // column D
        $sD->setCellValueByColumnAndRow($s1StartCol, 24, 'Jan');
        $sD->setCellValueByColumnAndRow($s1StartCol + 1, 24, 'Feb');
        $sD->setCellValueByColumnAndRow($s1StartCol + 2, 24, 'Mar');
        $sD->setCellValueByColumnAndRow($s1StartCol + 3, 24, 'Apr');
        $sD->setCellValueByColumnAndRow($s1StartCol + 4, 24, 'Mei');
        $sD->setCellValueByColumnAndRow($s1StartCol + 5, 24, 'Jun');
        $sD->setCellValueByColumnAndRow($s1StartCol + 6, 24, 'Achieve. smt. I');

        $s1Rows = [
            ['label' => 'Total Production', 'key' => 'prod'],
            ['label' => 'Production OK', 'key' => 'ok'],
            ['label' => 'Defect', 'key' => 'ng'],
            ['label' => 'Defect Rate', 'key' => 'defrate'],
            ['label' => 'Target Ppm', 'key' => 'targetppm'],
            ['label' => 'PPm', 'key' => 'ppm'],
            ['label' => 'Target F - COST', 'key' => 'targetfcost'],
            ['label' => 'F - COST (IDR) Int Defect', 'key' => 'fcost'],
            ['label' => 'F - COST (IDR) Ext Defect', 'key' => 'fcostext'],
            ['label' => 'TOTAL', 'key' => 'totalrow'],
        ];

        $s1SumProd = 0; $s1SumOk = 0; $s1SumNg = 0; $s1SumFCost = 0;
        for ($i = 0; $i < count($s1Rows); $i++) {
            $row = 25 + $i;
            $sD->setCellValue('B' . $row, $s1Rows[$i]['label']);
            if ($i == 9) $sD->setCellValue('C' . $row, '(IDR)');

            for ($m = 1; $m <= 6; $m++) {
                $col = $s1StartCol + $m - 1;
                switch ($s1Rows[$i]['key']) {
                    case 'prod':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthProd[$m]);
                        $s1SumProd += $monthProd[$m];
                        break;
                    case 'ok':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthOk[$m]);
                        $s1SumOk += $monthOk[$m];
                        break;
                    case 'ng':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthNg[$m]);
                        $s1SumNg += $monthNg[$m];
                        break;
                    case 'defrate':
                        $v = $monthProd[$m] > 0 ? $monthNg[$m] / $monthProd[$m] : 0;
                        $sD->setCellValueByColumnAndRow($col, $row, $v);
                        break;
                    case 'targetppm':
                        $sD->setCellValueByColumnAndRow($col, $row, $targetPpm);
                        break;
                    case 'ppm':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthPpm[$m]);
                        break;
                    case 'targetfcost':
                        $sD->setCellValueByColumnAndRow($col, $row, $targetFCost);
                        break;
                    case 'fcost':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthFCost[$m]);
                        $s1SumFCost += $monthFCost[$m];
                        break;
                }
            }

            // Achieve smt I column (col index = s1StartCol + 6 = 9)
            $achCol = $s1StartCol + 6;
            switch ($s1Rows[$i]['key']) {
                case 'prod': $sD->setCellValueByColumnAndRow($achCol, $row, $s1SumProd); break;
                case 'ok': $sD->setCellValueByColumnAndRow($achCol, $row, $s1SumOk); break;
                case 'ng': $sD->setCellValueByColumnAndRow($achCol, $row, $s1SumNg); break;
                case 'defrate':
                    $v = $s1SumProd > 0 ? $s1SumNg / $s1SumProd : 0;
                    $sD->setCellValueByColumnAndRow($achCol, $row, $v);
                    break;
                case 'targetppm': $sD->setCellValueByColumnAndRow($achCol, $row, $targetPpm); break;
                case 'ppm':
                    $v = $s1SumProd > 0 ? ($s1SumNg / $s1SumProd) * 1000000 : 0;
                    $sD->setCellValueByColumnAndRow($achCol, $row, $v);
                    break;
                case 'targetfcost': $sD->setCellValueByColumnAndRow($achCol, $row, $targetFCost * 6); break;
                case 'fcost': $sD->setCellValueByColumnAndRow($achCol, $row, $s1SumFCost); break;
            }
        }

        // --- Semester II block (Jul-Dec) ---
        $s2StartCol = 4; // column E (one column right because col D = Achieve smt I)
        $sD->setCellValueByColumnAndRow($s2StartCol - 1, 39, 'Achieve. smt. I');

        $s2Headers = ['Jul','Agust','Sep','Okt','Nop','Des'];
        for ($h = 0; $h < 6; $h++) {
            $sD->setCellValueByColumnAndRow($s2StartCol + $h, 39, $s2Headers[$h]);
        }
        $sD->setCellValueByColumnAndRow($s2StartCol + 6, 39, 'Achieve. smt. II');
        $sD->setCellValueByColumnAndRow($s2StartCol + 7, 39, 'Total Achieve');

        $s2Rows = $s1Rows; // same row labels
        $s2SumProd = 0; $s2SumOk = 0; $s2SumNg = 0; $s2SumFCost = 0;
        for ($i = 0; $i < count($s2Rows); $i++) {
            $row = 40 + $i;
            $sD->setCellValue('B' . $row, $s2Rows[$i]['label']);
            if ($i == 9) $sD->setCellValue('C' . $row, '(IDR)');

            for ($m = 7; $m <= 12; $m++) {
                $col = $s2StartCol + $m - 7;
                switch ($s2Rows[$i]['key']) {
                    case 'prod':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthProd[$m]);
                        $s2SumProd += $monthProd[$m];
                        break;
                    case 'ok':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthOk[$m]);
                        $s2SumOk += $monthOk[$m];
                        break;
                    case 'ng':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthNg[$m]);
                        $s2SumNg += $monthNg[$m];
                        break;
                    case 'defrate':
                        $v = $monthProd[$m] > 0 ? $monthNg[$m] / $monthProd[$m] : 0;
                        $sD->setCellValueByColumnAndRow($col, $row, $v);
                        break;
                    case 'targetppm':
                        $sD->setCellValueByColumnAndRow($col, $row, $targetPpm);
                        break;
                    case 'ppm':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthPpm[$m]);
                        break;
                    case 'targetfcost':
                        $sD->setCellValueByColumnAndRow($col, $row, $targetFCost);
                        break;
                    case 'fcost':
                        $sD->setCellValueByColumnAndRow($col, $row, $monthFCost[$m]);
                        $s2SumFCost += $monthFCost[$m];
                        break;
                }
            }

            $achCol = $s2StartCol + 6;
            switch ($s2Rows[$i]['key']) {
                case 'prod': $sD->setCellValueByColumnAndRow($achCol, $row, $s2SumProd); break;
                case 'ok': $sD->setCellValueByColumnAndRow($achCol, $row, $s2SumOk); break;
                case 'ng': $sD->setCellValueByColumnAndRow($achCol, $row, $s2SumNg); break;
                case 'defrate':
                    $v = $s2SumProd > 0 ? $s2SumNg / $s2SumProd : 0;
                    $sD->setCellValueByColumnAndRow($achCol, $row, $v);
                    break;
                case 'targetppm': $sD->setCellValueByColumnAndRow($achCol, $row, $targetPpm); break;
                case 'ppm':
                    $v = $s2SumProd > 0 ? ($s2SumNg / $s2SumProd) * 1000000 : 0;
                    $sD->setCellValueByColumnAndRow($achCol, $row, $v);
                    break;
                case 'targetfcost': $sD->setCellValueByColumnAndRow($achCol, $row, $targetFCost * 6); break;
                case 'fcost': $sD->setCellValueByColumnAndRow($achCol, $row, $s2SumFCost); break;
            }

            // Total Achieve column
            $totalCol = $s2StartCol + 7;
            switch ($s2Rows[$i]['key']) {
                case 'prod':
                    $sD->setCellValueByColumnAndRow($totalCol, $row, $s1SumProd + $s2SumProd); break;
                case 'ok':
                    $sD->setCellValueByColumnAndRow($totalCol, $row, $s1SumOk + $s2SumOk); break;
                case 'ng':
                    $sD->setCellValueByColumnAndRow($totalCol, $row, $s1SumNg + $s2SumNg); break;
                case 'defrate':
                    $allProd = $s1SumProd + $s2SumProd;
                    $allNg = $s1SumNg + $s2SumNg;
                    $v = $allProd > 0 ? $allNg / $allProd : 0;
                    $sD->setCellValueByColumnAndRow($totalCol, $row, $v);
                    break;
                case 'targetppm': $sD->setCellValueByColumnAndRow($totalCol, $row, $targetPpm); break;
                case 'ppm':
                    $allProd = $s1SumProd + $s2SumProd;
                    $allNg = $s1SumNg + $s2SumNg;
                    $v = $allProd > 0 ? ($allNg / $allProd) * 1000000 : 0;
                    $sD->setCellValueByColumnAndRow($totalCol, $row, $v);
                    break;
                case 'targetfcost': $sD->setCellValueByColumnAndRow($totalCol, $row, $targetFCost * 12); break;
                case 'fcost': $sD->setCellValueByColumnAndRow($totalCol, $row, $s1SumFCost + $s2SumFCost); break;
            }

            // Achieve smt I column (carry forward from semester I)
            switch ($s2Rows[$i]['key']) {
                case 'prod': $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $s1SumProd); break;
                case 'ok': $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $s1SumOk); break;
                case 'ng': $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $s1SumNg); break;
                case 'defrate':
                    $v = $s1SumProd > 0 ? $s1SumNg / $s1SumProd : 0;
                    $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $v);
                    break;
                case 'targetppm': $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $targetPpm); break;
                case 'ppm':
                    $v = $s1SumProd > 0 ? ($s1SumNg / $s1SumProd) * 1000000 : 0;
                    $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $v);
                    break;
                case 'targetfcost': $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $targetFCost * 6); break;
                case 'fcost': $sD->setCellValueByColumnAndRow($s2StartCol - 1, $row, $s1SumFCost); break;
            }
        }

        // --- Styling for Defect Level sheet ---
        $boldLabel = ['font' => ['bold' => true]];
        $sD->getStyle('A1:A3')->applyFromArray($stStyle['header']);
        $sD->getStyle('A5')->applyFromArray($stStyle['header']);

        // Semester I header row
        $sD->getStyle('D24:J24')->applyFromArray($stStyle['title']);
        // Semester I data rows
        for ($i = 0; $i < 10; $i++) {
            $r = 25 + $i;
            $sD->getStyle('B' . $r . ':J' . $r)->applyFromArray($stStyle[$i % 2 === 0 ? 'odd' : 'even']);
            $sD->getStyle('B' . $r)->applyFromArray($boldLabel);
        }
        // Semester I TOTAL row
        $sD->getStyle('B34:J34')->applyFromArray($stStyle['totals']);

        // Semester II header row
        $sD->getStyle('D39:L39')->applyFromArray($stStyle['title']);
        // Semester II data rows
        for ($i = 0; $i < 10; $i++) {
            $r = 40 + $i;
            $sD->getStyle('B' . $r . ':L' . $r)->applyFromArray($stStyle[$i % 2 === 0 ? 'odd' : 'even']);
            $sD->getStyle('B' . $r)->applyFromArray($boldLabel);
        }
        // Semester II TOTAL row
        $sD->getStyle('B49:L49')->applyFromArray($stStyle['totals']);

        foreach (range('A', 'L') as $colLetter) {
            $sD->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // PPM Line Chart
        $catLabels = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $catLabels->setDataValues($monthAbbr, false);

        $ppmActualLabel = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $ppmActualLabel->setDataValues(array('PPM Actual'), false);
        $ppmActualValues = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $ppmActualValues->setDataValues(array_values($monthPpm), false);

        $targetPpmLabel = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $targetPpmLabel->setDataValues(array('Target PPM'), false);
        $targetPpmValues = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $targetPpmValues->setDataValues(array_fill(0, 12, $targetPpm), false);

        $ppmSeries = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART,
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            array(0, 1),
            array($ppmActualLabel, $targetPpmLabel),
            array($catLabels, $catLabels),
            array($ppmActualValues, $targetPpmValues)
        );

        $ppmPlotArea = new PHPExcel_Chart_PlotArea(null, array($ppmSeries));
        $ppmLegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);

        $chart = new PHPExcel_Chart(
            'ppm_chart',
            new PHPExcel_Chart_Title('PPM Actual vs Target'),
            $ppmLegend,
            $ppmPlotArea,
            true, '0', null, null,
            new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
        );
        $chart->setTopLeftPosition('A11');
        $chart->setBottomRightPosition('L22');
        $sD->addChart($chart);

        // === Total Production Qty Graph Sheet ===
        $excel->createSheet(); $excel->setActiveSheetIndex(4);
        $sG = $excel->getActiveSheet(); $sG->setTitle('Total Production Qty Graph');

        // Section A: Company header
        $sG->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sG->setCellValue('A2', 'Plastic Division');
        $sG->setCellValue('A3', 'Production  Dept.');
        $sG->setCellValue('A5', 'Production  Internal Defect - Year ' . $year);
        $sG->getStyle('A1:A3')->applyFromArray($stStyle['header']);
        $sG->getStyle('A5')->applyFromArray($stStyle['header']);

        // Section A: Summary data rows (Semester I)
        $sG->setCellValue('H42', 'Month');
        $sG->setCellValueByColumnAndRow(7, 43, 'Jan'); // H
        $sG->setCellValueByColumnAndRow(8, 43, 'Feb'); // I
        $sG->setCellValueByColumnAndRow(9, 43, 'Mar'); // J
        $sG->setCellValueByColumnAndRow(10, 43, 'Apr'); // K
        $sG->setCellValueByColumnAndRow(11, 43, 'Mei'); // L
        $sG->setCellValueByColumnAndRow(12, 43, 'Jun'); // M

        $summaryLabels = ['Total Production', 'Total Prod OK', 'Total Prod NG', 'PPm', 'PPm Target',
            'F - COST (IDR) Int Defect', 'F - COST (IDR) Ext Defect',
            'SUM  F-COST (USD) Defect', 'SUM  F-COST (IDR) Defect'];
        for ($i = 0; $i < count($summaryLabels); $i++) {
            $sG->setCellValue('E' . (44 + $i), $summaryLabels[$i]);
        }

        // Write Semester I monthly data into summary
        for ($m = 1; $m <= 6; $m++) {
            $col = 6 + $m; // month 1 → col 7=H, month 6 → col 12=M
            $sG->setCellValueByColumnAndRow($col, 44, $monthProd[$m]);     // Total Production
            $sG->setCellValueByColumnAndRow($col, 45, $monthOk[$m]);       // Total Prod OK
            $sG->setCellValueByColumnAndRow($col, 46, $monthNg[$m]);       // Total Prod NG
            $sG->setCellValueByColumnAndRow($col, 47, $monthPpm[$m]);      // PPm
            $sG->setCellValueByColumnAndRow($col, 48, $targetPpm);         // PPm Target
            $sG->setCellValueByColumnAndRow($col, 49, $monthFCost[$m]);    // F-COST Int Defect
            // F-COST Ext Defect = empty (row 50)
        }

        // Style summary table: header row + data rows
        $sG->getStyle('H43:M43')->applyFromArray($stStyle['title']);
        for ($i = 0; $i < count($summaryLabels); $i++) {
            $sr = 44 + $i;
            $sG->getStyle('H' . $sr . ':M' . $sr)->applyFromArray($stStyle[$i % 2 === 0 ? 'even' : 'odd']);
        }

        // Chart A: Bar chart (Total Prod/OK/NG) + Line (PPM/Target) for Semester I
        $graphCatLabels = ['Jan','Feb','Mar','Apr','Mei','Jun'];
        $gCL = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gCL->setDataValues($graphCatLabels, false);

        $prodVal = []; $okVal = []; $ngVal = [];
        for ($m = 1; $m <= 6; $m++) { $prodVal[] = $monthProd[$m]; $okVal[] = $monthOk[$m]; $ngVal[] = $monthNg[$m]; }

        $barLabels = [];
        $barVals = [];
        $barSeriesNames = ['Total Prod', 'Total Prod OK', 'Total Prod NG'];
        $barSeriesVals = [$prodVal, $okVal, $ngVal];
        for ($bi = 0; $bi < 3; $bi++) {
            $lbl = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
            $lbl->setDataValues(array($barSeriesNames[$bi]), false);
            $barLabels[] = $lbl;
            $vals = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
            $vals->setDataValues($barSeriesVals[$bi], false);
            $barVals[] = $vals;
        }

        $barS = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_BARCHART, PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
            array(0, 1, 2), $barLabels,
            array($gCL, $gCL, $gCL), $barVals
        );
        $barS->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

        $ppmL1 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $ppmL1->setDataValues(array('PPM Actual'), false);
        $ppmV1 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $ppmV1->setDataValues(array_slice($monthPpm, 0, 6), false);

        $tgtL1 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $tgtL1->setDataValues(array('PPM Target'), false);
        $tgtV1 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $tgtV1->setDataValues(array_fill(0, 6, $targetPpm), false);

        $lineS = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            array(0, 1), array($ppmL1, $tgtL1), array($gCL, $gCL), array($ppmV1, $tgtV1)
        );

        $chartAPlot = new PHPExcel_Chart_PlotArea(null, array($barS, $lineS));
        $chartALegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
        $chartA = new PHPExcel_Chart(
            'prod_qty_graph_s1',
            new PHPExcel_Chart_Title('Production Qty Semester I - ' . $year),
            $chartALegend, $chartAPlot, true, '0', null, null,
            new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
        );
        $chartA->setTopLeftPosition('A7');
        $chartA->setBottomRightPosition('J40');
        $sG->addChart($chartA);

        // === Section B: Per-Product Per-Month Tables ===
        $sG->setCellValue('A55', 'PT Ciptajaya Kreasindo Utama');
        $sG->setCellValue('A56', 'Plastic Division');
        $sG->setCellValue('A57', 'Production  Dept.');
        $sG->getStyle('A55:A57')->applyFromArray($stStyle['header']);

        // Section labels (K, L, M, ... for each month block)
        $monthLetterLabels = ['K','L','M','N','O','P','Q','R','S','T','U','V'];
        for ($mi = 0; $mi < 12; $mi++) {
            $col = 7 + ($mi * 13); // 7, 20, 33, 46, 59, 72, 85, 98, 111, 124, 137, 150
            $sG->setCellValueByColumnAndRow($col, 59, $monthLetterLabels[$mi]);
            $sG->setCellValueByColumnAndRow($col, 61, $monthAbbr[$mi]);
            $sG->getStyleByColumnAndRow($col, 59)->applyFromArray($stStyle['title']);
            $sG->getStyleByColumnAndRow($col, 61)->applyFromArray($stStyle['title']);
        }

        // Header row (R62) - first block has extended headers
        $firstBlockHeaders = ['No.', 'Part ID', 'Part Name', '', 'Tool', 'COST',
            'Ttl Prod', 'Prod OK', 'Prod NG', 'PPM', 'F - COST', 'ppm', 'rank',
            'Part Id', 'Part Name', 'Ttl prod', 'Prod Ok', 'Prod NG', 'Tool',
            'Ttl Prod', 'Prod OK', 'Prod NG', 'PPM', 'F - COST', 'ppm', 'rank'];
        for ($hi = 0; $hi < count($firstBlockHeaders); $hi++) {
            if ($firstBlockHeaders[$hi] !== '') {
                $sG->setCellValueByColumnAndRow($hi + 2, 62, $firstBlockHeaders[$hi]); // starts col C=2
            }
        }

        // Right-half header for Jul-Dec (col 73+)
        $rightBlockHeaders = ['Prod OK', 'Prod NG', 'PPM', 'F - COST', 'ppm', 'rank',
            'Part Id', 'Part Name', 'Ttl prod', 'Prod Ok', 'Prod NG', 'Tool',
            'Ttl Prod', 'Prod OK', 'Prod NG', 'PPM', 'F - COST', 'ppm', 'rank'];
        for ($hi = 0; $hi < count($rightBlockHeaders); $hi++) {
            $sG->setCellValueByColumnAndRow(73 + $hi, 62, $rightBlockHeaders[$hi]);
        }

        // Style header row 62 (title style across all columns)
        $sG->getStyleByColumnAndRow(2, 62, 92, 62)->applyFromArray($stStyle['title']);

        // Build per-month ranked data for each month
        $allProducts = [];
        foreach ($total->result_array() as $row) {
            $allProducts[] = $row;
        }
        // Also include products from OK and NG that might not be in total
        $prodMap = [];
        foreach ($allProducts as $r) { $prodMap[$r['Product_ID'] . '|' . $r['Tool']] = true; }
        foreach ($ok->result_array() as $r) {
            $k = $r['Product_ID'] . '|' . $r['Tool'];
            if (!isset($prodMap[$k])) { $allProducts[] = $r; $prodMap[$k] = true; }
        }
        foreach ($ng->result_array() as $r) {
            $k = $r['Product_ID'] . '|' . $r['Tool'];
            if (!isset($prodMap[$k])) { $allProducts[] = $r; $prodMap[$k] = true; }
        }

        // Index OK and NG by product+tool
        $okByProdTool = []; $ngByProdTool = [];
        foreach ($ok->result_array() as $row) { $okByProdTool[$row['Product_ID'] . '|' . $row['Tool']] = $row; }
        foreach ($ng->result_array() as $row) { $ngByProdTool[$row['Product_ID'] . '|' . $row['Tool']] = $row; }

        // Build monthly rankings
        $monthlyRankData = []; // [month][rank] = product data
        for ($m = 1; $m <= 12; $m++) {
            $mk = sprintf('%02d', $m);
            $monthProducts = [];
            foreach ($allProducts as $pr) {
                $pId = $pr['Product_ID'];
                $tool = $pr['Tool'];
                $pk = $pId . '|' . $tool;
                $ttlProd = floatval($pr[$mk] ?? 0);
                $okVal_m = 0; $ngVal_m = 0;
                if (isset($okByProdTool[$pk])) { $okVal_m = floatval($okByProdTool[$pk][$mk] ?? 0); }
                if (isset($ngByProdTool[$pk])) { $ngVal_m = floatval($ngByProdTool[$pk][$mk] ?? 0); }
                $ppmVal = $ttlProd > 0 ? ($ngVal_m / $ttlProd) * 1000000 : 0;
                $fCostVal = $ngVal_m * floatval($pr['Price'] ?? 0);
                $rankVal = $ttlProd > 0 ? $ppmVal : 0;
                if ($ttlProd > 0 || $ngVal_m > 0) {
                    $monthProducts[] = [
                        'Product_ID' => $pId, 'Product_Name' => $pr['Product_Name'],
                        'Tool' => $tool, 'Price' => $pr['Price'],
                        'TtlProd' => $ttlProd, 'Ok' => $okVal_m, 'Ng' => $ngVal_m,
                        'PPM' => $ppmVal, 'FCost' => $fCostVal, 'Rank' => $rankVal
                    ];
                }
            }
            usort($monthProducts, function($a, $b) { return $b['Rank'] <=> $a['Rank']; });
            $monthlyRankData[$m] = $monthProducts;
        }

        // Write product rows (starting at row 63)
        $dataRow = 63;
        foreach ($allProducts as $idx => $pr) {
            $pId = $pr['Product_ID'];
            $tool = $pr['Tool'];
            $pk = $pId . '|' . $tool;
            $price = floatval($pr['Price'] ?? 0);

            for ($mi = 0; $mi < 12; $mi++) {
                $m = $mi + 1;
                $mk = sprintf('%02d', $m);
                $col = 7 + ($mi * 13); // base column for this month block

                if ($mi === 0) {
                    // First block: No., Part ID, Part Name, Tool, COST, then detail cols, then next sub-block
                    $sG->setCellValueByColumnAndRow(2, $dataRow, $idx + 1); // No.
                    $sG->setCellValueByColumnAndRow(3, $dataRow, $pId);     // Part ID
                    $sG->setCellValueByColumnAndRow(4, $dataRow, $pr['Product_Name']); // Part Name
                    $sG->setCellValueByColumnAndRow(6, $dataRow, $price);    // COST
                }

                $ttlProd = floatval($pr[$mk] ?? 0);
                $okV = 0; $ngV = 0;
                if (isset($okByProdTool[$pk])) { $okV = floatval($okByProdTool[$pk][$mk] ?? 0); }
                if (isset($ngByProdTool[$pk])) { $ngV = floatval($ngByProdTool[$pk][$mk] ?? 0); }
                $ppmV = $ttlProd > 0 ? ($ngV / $ttlProd) * 1000000 : 0;
                $fCostV = $ngV * $price;

                // Find rank for this product in this month
                $rank = 1;
                foreach ($monthlyRankData[$m] as $ri => $rp) {
                    if ($rp['Product_ID'] === $pId && $rp['Tool'] === $tool) { $rank = $ri + 1; break; }
                }

                // First sub-block: Ttl Prod, Prod OK, Prod NG, PPM, F-COST, ppm, rank
                $sG->setCellValueByColumnAndRow($col, $dataRow, $ttlProd > 0 ? $ttlProd : 0);
                $sG->setCellValueByColumnAndRow($col + 1, $dataRow, $okV);
                $sG->setCellValueByColumnAndRow($col + 2, $dataRow, $ngV);
                $sG->setCellValueByColumnAndRow($col + 3, $dataRow, $ttlProd > 0 ? $ppmV : '-');
                $sG->setCellValueByColumnAndRow($col + 4, $dataRow, $fCostV > 0 ? $fCostV : 0);
                $sG->setCellValueByColumnAndRow($col + 5, $dataRow, $ttlProd > 0 ? $ppmV : 0);
                $sG->setCellValueByColumnAndRow($col + 6, $dataRow, $rank);

                // Second sub-block: Part Id, Part Name, Ttl prod, Prod Ok, Prod NG, Tool
                $sG->setCellValueByColumnAndRow($col + 7, $dataRow, $pId);
                $sG->setCellValueByColumnAndRow($col + 8, $dataRow, $pr['Product_Name']);
                $sG->setCellValueByColumnAndRow($col + 9, $dataRow, $ttlProd > 0 ? $ttlProd : 0);
                $sG->setCellValueByColumnAndRow($col + 10, $dataRow, $okV);
                $sG->setCellValueByColumnAndRow($col + 11, $dataRow, $ngV);
                $sG->setCellValueByColumnAndRow($col + 12, $dataRow, $tool);
            }
            $dataRow++;
        }

        // Style per-product data rows with alternating colors
        $dataStartRow = 63;
        for ($sr = $dataStartRow; $sr < $dataRow; $sr++) {
            $sG->getStyleByColumnAndRow(2, $sr, 92, $sr)
                ->applyFromArray($stStyle[($sr - $dataStartRow) % 2 === 0 ? 'even' : 'odd']);
        }

        // === Section C: Summary rows at bottom ===
        $summaryRow = $dataRow + 1;
        // Write grand totals across all month blocks
        for ($mi = 0; $mi < 12; $mi++) {
            $m = $mi + 1;
            $col = 7 + ($mi * 13);
            $sG->setCellValueByColumnAndRow($col, $summaryRow, $monthProd[$m]);
            $sG->setCellValueByColumnAndRow($col + 1, $summaryRow, $monthOk[$m]);
            $sG->setCellValueByColumnAndRow($col + 2, $summaryRow, $monthNg[$m]);
            $ppmVal = $monthProd[$m] > 0 ? ($monthNg[$m] / $monthProd[$m]) * 1000000 : 0;
            $sG->setCellValueByColumnAndRow($col + 3, $summaryRow, $ppmVal);
            $sG->setCellValueByColumnAndRow($col + 4, $summaryRow, $monthFCost[$m]);
            $sG->setCellValueByColumnAndRow($col + 5, $summaryRow, $monthProd[$m]);
            $sG->setCellValueByColumnAndRow($col + 6, $summaryRow, $monthNg[$m]);
            $sG->setCellValueByColumnAndRow($col + 7, $summaryRow, $ppmVal);
        }
        $sG->setCellValue('E' . $summaryRow, 'PPm');
        $sG->setCellValueByColumnAndRow(7, $summaryRow + 1, 1);
        for ($mi = 0; $mi < 12; $mi++) {
            $sG->setCellValueByColumnAndRow(7 + ($mi * 13), $summaryRow + 1, $mi + 1);
        }
        // Style summary row
        $sG->getStyleByColumnAndRow(2, $summaryRow, 92, $summaryRow)->applyFromArray($stStyle['totals']);

        // Chart B: Semester II bar+line chart (in right half area)
        $graphCatLabels2 = ['Jul','Agust','Sep','Okt','Nop','Des'];
        $gCL2 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $gCL2->setDataValues($graphCatLabels2, false);

        $prodVal2 = []; $okVal2 = []; $ngVal2 = [];
        for ($m = 7; $m <= 12; $m++) { $prodVal2[] = $monthProd[$m]; $okVal2[] = $monthOk[$m]; $ngVal2[] = $monthNg[$m]; }

        $barLabels2 = []; $barVals2 = [];
        $barSeriesNames2 = ['Total Prod', 'Total Prod OK', 'Total Prod NG'];
        $barSeriesVals2 = [$prodVal2, $okVal2, $ngVal2];
        for ($bi = 0; $bi < 3; $bi++) {
            $lbl = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
            $lbl->setDataValues(array($barSeriesNames2[$bi]), false);
            $barLabels2[] = $lbl;
            $vals = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
            $vals->setDataValues($barSeriesVals2[$bi], false);
            $barVals2[] = $vals;
        }
        $barS2 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_BARCHART, PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
            array(0, 1, 2), $barLabels2, array($gCL2, $gCL2, $gCL2), $barVals2
        );
        $barS2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

        $ppmL2 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $ppmL2->setDataValues(array('PPM Actual'), false);
        $ppmV2 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $ppmV2->setDataValues(array_slice($monthPpm, 6, 6), false);

        $tgtL2 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING);
        $tgtL2->setDataValues(array('PPM Target'), false);
        $tgtV2 = new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER);
        $tgtV2->setDataValues(array_fill(0, 6, $targetPpm), false);

        $lineS2 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            array(0, 1), array($ppmL2, $tgtL2), array($gCL2, $gCL2), array($ppmV2, $tgtV2)
        );

        $chartBPlot = new PHPExcel_Chart_PlotArea(null, array($barS2, $lineS2));
        $chartBLegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
        $chartB = new PHPExcel_Chart(
            'prod_qty_graph_s2',
            new PHPExcel_Chart_Title('Production Qty Semester II - ' . $year),
            $chartBLegend, $chartBPlot, true, '0', null, null,
            new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
        );
        $chartB->setTopLeftPosition('BC8'); // right-half area
        $chartB->setBottomRightPosition('HP40');
        $sG->addChart($chartB);


        // === 10worst Monthly Sheets (Jan - Dec) ===
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agust', 'Sept', 'Okt', 'Nov', 'Des'];
        $yyShort = substr($year, -2);
        $worstHeaders = ['YY', 'YYMM', 'Product ID', 'Product Name', 'PPM', 'Qty Total', 'Qty OK', 'Qty NG', '% NG'];

        for ($m = 1; $m <= 12; $m++) {
            $worstData = $this->mr->get_10worst_data_by_month($year, $m);

            if ($worstData->num_rows() > 0) {
                $excel->createSheet();
                $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
                $sW = $excel->getActiveSheet();
                $sW->setTitle('10worst-' . $monthNames[$m - 1] . $yyShort);

                // Write header row
                $col = 'A';
                foreach ($worstHeaders as $h) {
                    $sW->setCellValue($col++ . '1', $h);
                }
                $sW->getStyle('A1:I1')->applyFromArray($stStyle['title']);

                // Write data rows
                $rowNum = 2;
                foreach ($worstData->result_array() as $wRow) {
                    $yyStr = (string) $year;
                    $yymmStr = $yyShort . '-' . sprintf('%02d', $m);
                    $qtyTotal = floatval($wRow['Qty_Total']);
                    $qtyNg = floatval($wRow['Qty_NG']);
                    $ppm = floatval($wRow['PPM']);
                    $pctNg = $qtyTotal > 0 ? round(($qtyNg / $qtyTotal) * 100, 2) : 0;

                    $col = 'A';
                    $sW->setCellValue($col++ . $rowNum, $yyStr);
                    $sW->setCellValue($col++ . $rowNum, $yymmStr);
                    $sW->setCellValue($col++ . $rowNum, $wRow['Product_ID']);
                    $sW->setCellValue($col++ . $rowNum, $wRow['Product_Name']);
                    $sW->setCellValue($col++ . $rowNum, $ppm > 0 ? $ppm : 0);
                    $sW->setCellValue($col++ . $rowNum, $qtyTotal);
                    $sW->setCellValue($col++ . $rowNum, floatval($wRow['Qty_OK']));
                    $sW->setCellValue($col++ . $rowNum, $qtyNg);
                    $sW->setCellValue($col++ . $rowNum, $pctNg > 0 ? number_format($pctNg, 2) . '%' : '0%');

                    // Apply alternating row style
                    $sW->getStyle('A' . $rowNum . ':I' . $rowNum)
                        ->applyFromArray($stStyle[$rowNum % 2 === 0 ? 'even' : 'odd']);

                    $rowNum++;
                }

                // Auto-size columns A-I
                foreach (range('A', 'I') as $colLetter) {
                    $sW->getColumnDimension($colLetter)->setAutoSize(true);
                }
            }
        }

        // === Jan-Dec Monthly Report Sheets ===
        $monthNames3 = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'];

        for ($m = 1; $m <= 12; $m++) {
            $excel->createSheet();
            $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
            $sM = $excel->getActiveSheet();
            $sM->setTitle($monthNames3[$m - 1]);

            // Company header (rows 0-2)
            $sM->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
            $sM->setCellValue('A2', 'Plastic Division');
            $sM->setCellValue('A3', 'Production  Dept.');
            $sM->getStyle('A1:A3')->applyFromArray($stStyle['header']);

            // Date serial for 1st day of month (row 4 col E, row 31 col D)
            $monthFirstDay = gmmktime(0, 0, 0, $m, 1, $year) / 86400 + 25569;
            $sM->setCellValue('A5', 'Production  Report:');
            $sM->setCellValueByColumnAndRow(4, 5, $monthFirstDay); // E5

            // RANK label (row 32)
            $sM->setCellValue('C32', 'RANK');
            $sM->setCellValueByColumnAndRow(3, 32, $monthFirstDay); // D32
            $sM->getStyle('C32')->applyFromArray(['font' => ['bold' => true]]);

            // Get monthly totals
            $monthTotals = $this->mr->get_monthly_total_production($year, $m);
            $mtRow = $monthTotals->row_array();
            $totalProd = floatval($mtRow['Total_Prod'] ?? 0);
            $totalNg = floatval($mtRow['Total_NG'] ?? 0);
            $totalPpm = $totalProd > 0 ? ($totalNg / $totalProd) * 1000000 : 0;

            // Summary rows (33-35)
            $sM->setCellValue('D34', 'Total Production ');
            $sM->setCellValue('E34', $totalProd);
            $sM->setCellValue('F34', 'pc');
            $sM->setCellValue('D35', 'Total NG');
            $sM->setCellValue('E35', $totalNg);
            $sM->setCellValue('F35', 'pc');
            $sM->setCellValue('D36', 'Total PPM ');
            $sM->setCellValue('E36', $totalPpm);

            // Style summary rows
            foreach (range(34, 36) as $sr) {
                $sM->getStyle('D' . $sr . ':F' . $sr)->applyFromArray($stStyle[$sr % 2 === 0 ? 'even' : 'odd']);
            }

            // Column headers (row 37) — headers start at E to align with data columns
            $sM->setCellValue('E37', 'Part No');
            $sM->setCellValue('F37', 'Part Name');
            $sM->setCellValue('G37', 'Tool');
            $sM->setCellValue('H37', 'Prod Qty');
            $sM->setCellValue('I37', 'NG');
            $sM->setCellValue('J37', 'PPM (Total Prod)');
            $sM->setCellValue('K37', 'Mc No.');
            $sM->setCellValue('L37', 'Problem');
            $sM->setCellValueByColumnAndRow(14, 37, 'Causes'); // O37
            $sM->setCellValueByColumnAndRow(16, 37, 'Countermeasure'); // Q37
            $sM->getStyle('E37:Q37')->applyFromArray($stStyle['title']);

            // Get top 10 worst products
            $top10Data = $this->mr->get_top10_worst_ppm_by_month($year, $m);
            $top10Rows = $top10Data->result_array();

            $dataRow = 38;
            $sumProdQty = 0;
            $sumNg = 0;
            $sumPpm = 0;

            for ($rank = 0; $rank < 10; $rank++) {
                $sM->setCellValue('C' . $dataRow, 701 - $rank);
                $sM->setCellValue('D' . $dataRow, $rank + 1);

                if (isset($top10Rows[$rank])) {
                    $pr = $top10Rows[$rank];
                    $prodQty = floatval($pr['Prod_Qty']);
                    $ng = floatval($pr['NG']);
                    $ppmVal = $totalProd > 0 ? ($ng / $totalProd) * 1000000 : 0;

                    $sM->setCellValue('E' . $dataRow, $pr['Part_No']);
                    $sM->setCellValue('F' . $dataRow, $pr['Part_Name']);
                    $sM->setCellValue('G' . $dataRow, $pr['Tool']);
                    $sM->setCellValue('H' . $dataRow, $prodQty);
                    $sM->setCellValue('I' . $dataRow, $ng);
                    $sM->setCellValue('J' . $dataRow, $ppmVal);

                    $sumProdQty += $prodQty;
                    $sumNg += $ng;
                    $sumPpm += $ppmVal;
                }

                // Apply alternating row style
                $sM->getStyle('C' . $dataRow . ':Q' . $dataRow)
                    ->applyFromArray($stStyle[$dataRow % 2 === 0 ? 'even' : 'odd']);

                $dataRow++;
            }

            // TOTAL row
            $totalRow = 48;
            $sM->setCellValue('F' . $totalRow, 'TOTAL');
            $sM->setCellValue('H' . $totalRow, $sumProdQty);
            $sM->setCellValue('I' . $totalRow, $sumNg);
            $sM->setCellValue('J' . $totalRow, $sumPpm);
            $sM->getStyle('C' . $totalRow . ':Q' . $totalRow)->applyFromArray($stStyle['totals']);

            // Auto-size columns C-Q
            foreach (range('C', 'Q') as $colLetter) {
                $sM->getColumnDimension($colLetter)->setAutoSize(true);
            }

            // === Bar Chart: Top 10 Worst Defect by PPM ===
            $monthFull = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            $sheetTitle = $monthNames3[$m - 1];
            $chartTitle = '10 WORST DEFECT BY PPM ' . $sheetTitle . ' - ' . $year;

            $chartCatLabels = new PHPExcel_Chart_DataSeriesValues(
                PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING,
                "'" . $sheetTitle . "'!\$F\$38:\$F\$47"
            );
            $chartPpmLabel = new PHPExcel_Chart_DataSeriesValues(
                PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_STRING,
                array('PPM (Total Prod)')
            );
            $chartPpmValues = new PHPExcel_Chart_DataSeriesValues(
                PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER,
                "'" . $sheetTitle . "'!\$J\$38:\$J\$47"
            );

            $chartSeries = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
                array(0), array($chartPpmLabel), array($chartCatLabels), array($chartPpmValues)
            );
            $chartSeries->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

            $chartPlotArea = new PHPExcel_Chart_PlotArea(null, array($chartSeries));
            $chartLegend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
            $chartObj = new PHPExcel_Chart(
                'worst_chart_' . $sheetTitle,
                new PHPExcel_Chart_Title($chartTitle),
                $chartLegend,
                $chartPlotArea,
                true, '0', null, null,
                new PHPExcel_Chart_Axis(), new PHPExcel_Chart_Axis()
            );
            $chartObj->setTopLeftPosition('A1');
            $chartObj->setBottomRightPosition('P30');
            $sM->addChart($chartObj);
        }

        // === Total Sheet (multi-month overview matching raw Excel) ===
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sTotal = $excel->getActiveSheet();
        $sTotal->setTitle('Total');

        // Company header
        $sTotal->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sTotal->setCellValue('A2', 'Plastic Division');
        $sTotal->setCellValue('A3', 'Production  Dept.');
        $sTotal->getStyle('A1:A3')->applyFromArray($stStyle['header']);

        $sTotal->setCellValue('A5', 'Production  Report:');
        $sTotal->setCellValue('F5', 'Year ' . $year);
        $sTotal->setCellValue('D8', 'RANK');
        $sTotal->getStyle('D8')->applyFromArray(['font' => ['bold' => true]]);

        $mr = $this->mr;
        $writeTotalHalf = function($startMonth, $dateRow, $headerRow, $dataStartRow) use ($sTotal, $year, $stStyle, $mr) {
            for ($i = 0; $i < 6; $i++) {
                $m = $startMonth + $i;
                $blockStartCol = 4 + ($i * 5); // E=4, J=9, O=14, T=19, Y=24, AD=29

                // Date serial (1st day of month)
                $monthFirstDay = gmmktime(0, 0, 0, $m, 1, $year) / 86400 + 25569;
                $sTotal->setCellValueByColumnAndRow($blockStartCol, $dateRow, $monthFirstDay);

                // Headers
                $sTotal->setCellValueByColumnAndRow($blockStartCol, $headerRow, 'Part No');
                $sTotal->setCellValueByColumnAndRow($blockStartCol + 1, $headerRow, 'Part Name');
                $sTotal->setCellValueByColumnAndRow($blockStartCol + 2, $headerRow, 'Tool');

                // Get top 10 worst data for this month
                $top10 = $mr->get_top10_worst_ppm_by_month($year, $m)->result_array();

                for ($rank = 0; $rank < 10; $rank++) {
                    $dr = $dataStartRow + $rank;
                    $sTotal->setCellValue('C' . $dr, 701 - $rank);
                    $sTotal->setCellValue('D' . $dr, $rank + 1);

                    if (isset($top10[$rank])) {
                        $sTotal->setCellValueByColumnAndRow($blockStartCol, $dr, $top10[$rank]['Part_No']);
                        $sTotal->setCellValueByColumnAndRow($blockStartCol + 1, $dr, $top10[$rank]['Part_Name']);
                        $sTotal->setCellValueByColumnAndRow($blockStartCol + 2, $dr, $top10[$rank]['Tool']);
                    }
                }
            }
        };

        // First half: months 1-6
        $writeTotalHalf(1, 14, 15, 16);

        // Second half: months 7-12
        $writeTotalHalf(7, 28, 29, 30);

        // Header styles
        $sTotal->getStyle('E15:AH15')->applyFromArray($stStyle['title']);
        $sTotal->getStyle('E29:AH29')->applyFromArray($stStyle['title']);

        // Alternating row styles
        for ($dr = 16; $dr <= 25; $dr++) {
            $sTotal->getStyle('C' . $dr . ':AH' . $dr)
                ->applyFromArray($stStyle[$dr % 2 === 0 ? 'even' : 'odd']);
        }
        for ($dr = 30; $dr <= 39; $dr++) {
            $sTotal->getStyle('C' . $dr . ':AH' . $dr)
                ->applyFromArray($stStyle[$dr % 2 === 0 ? 'even' : 'odd']);
        }

        // Auto-size columns C-AH
        $totalCols = array_merge(range('C', 'Z'), ['AA','AB','AC','AD','AE','AF','AG','AH']);
        foreach ($totalCols as $colLetter) {
            $sTotal->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // === Contoh Report Sheet ===
        $contohData = $this->mr->get_contoh_report_data($year);
        if ($contohData->num_rows() > 0) {
            $excel->createSheet();
            $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
            $sC = $excel->getActiveSheet();
            $sC->setTitle('contoh report');

            $contohHeaders = [
                'YY', 'YYMM', 'Product ID', 'Product Name', 'PPM', 'Qty Produksi',
                'Qty NG', '% NG', 'Customer', 'Scratch NG', 'Dent NG', 'Short NG',
                'Bury NG', 'Silver NG', 'Black Dot NG', 'Flow Mark NG', 'Undercut NG',
                'Weld Line NG', 'Crack NG', 'Dim Others NG', 'White Dot'
            ];

            // Header row (R0)
            $col = 'A';
            foreach ($contohHeaders as $h) {
                $sC->setCellValue($col++ . '1', $h);
            }
            $sC->getStyle('A1:U1')->applyFromArray($stStyle['title']);

            // Data rows
            $contohRow = 2;
            $ngTotals = [
                'Scratch' => 0, 'Dent' => 0, 'Short' => 0, 'Silver' => 0,
                'Black Dot' => 0, 'Flow Mark' => 0, 'Undercut' => 0,
                'Weld Line' => 0, 'Crack' => 0, 'Dim Others' => 0, 'White Dot' => 0
            ];
            $grandTotalNg = 0;

            foreach ($contohData->result_array() as $cr) {
                $qtyProduksi = floatval($cr['Qty_Produksi']);
                $qtyNg = floatval($cr['Qty_NG']);
                $pctNg = $qtyProduksi > 0 ? round(($qtyNg / $qtyProduksi) * 100, 2) : 0;

                $col = 'A';
                $sC->setCellValue($col++ . $contohRow, $cr['YY']);
                $sC->setCellValue($col++ . $contohRow, $cr['YYMM']);
                $sC->setCellValue($col++ . $contohRow, $cr['Product_ID']);
                $sC->setCellValue($col++ . $contohRow, $cr['Product_Name']);
                $sC->setCellValue($col++ . $contohRow, $cr['PPM']);
                $sC->setCellValue($col++ . $contohRow, $qtyProduksi);
                $sC->setCellValue($col++ . $contohRow, $qtyNg);
                $sC->setCellValue($col++ . $contohRow, $pctNg > 0 ? number_format($pctNg, 2) . '%' : '0%');
                $sC->setCellValue($col++ . $contohRow, $cr['Customer'] ?? '');
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Scratch_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Dent_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Short_NG']));
                $sC->setCellValue($col++ . $contohRow, ''); // Bury NG - not in DB
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Silver_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Black_Dot_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Flow_Mark_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Undercut_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Weld_Line_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Crack_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['Dim_Others_NG']));
                $sC->setCellValue($col++ . $contohRow, floatval($cr['White_Dot_NG']));

                $sC->getStyle('A' . $contohRow . ':U' . $contohRow)
                    ->applyFromArray($stStyle[$contohRow % 2 === 0 ? 'even' : 'odd']);

                // Accumulate for NG Mode summary
                $ngTotals['Scratch'] += floatval($cr['Scratch_NG']);
                $ngTotals['Dent'] += floatval($cr['Dent_NG']);
                $ngTotals['Short'] += floatval($cr['Short_NG']);
                $ngTotals['Silver'] += floatval($cr['Silver_NG']);
                $ngTotals['Black Dot'] += floatval($cr['Black_Dot_NG']);
                $ngTotals['Flow Mark'] += floatval($cr['Flow_Mark_NG']);
                $ngTotals['Undercut'] += floatval($cr['Undercut_NG']);
                $ngTotals['Weld Line'] += floatval($cr['Weld_Line_NG']);
                $ngTotals['Crack'] += floatval($cr['Crack_NG']);
                $ngTotals['Dim Others'] += floatval($cr['Dim_Others_NG']);
                $ngTotals['White Dot'] += floatval($cr['White_Dot_NG']);
                $grandTotalNg += $qtyNg;

                $contohRow++;
            }

            // NG Mode summary table
            $summaryRow = $contohRow + 2;
            $sC->setCellValue('A' . $summaryRow, 'NG Mode');
            $sC->getStyle('A' . $summaryRow)->applyFromArray($stStyle['header']);
            $summaryRow++;

            $sC->setCellValue('A' . $summaryRow, 'Defect Type');
            $sC->setCellValue('B' . $summaryRow, 'Qty');
            $sC->setCellValue('C' . $summaryRow, '% Total NG');
            $sC->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray($stStyle['title']);
            $summaryRow++;

            arsort($ngTotals);
            $rank = 1;
            foreach ($ngTotals as $defectName => $defectQty) {
                if ($defectQty <= 0) continue;
                $pctOfTotal = $grandTotalNg > 0 ? round(($defectQty / $grandTotalNg) * 100, 2) : 0;
                $sC->setCellValue('A' . $summaryRow, "$rank. $defectName");
                $sC->setCellValue('B' . $summaryRow, $defectQty);
                $sC->setCellValue('C' . $summaryRow, number_format($pctOfTotal, 2) . '%');
                $sC->getStyle('A' . $summaryRow . ':C' . $summaryRow)
                    ->applyFromArray($stStyle[$summaryRow % 2 === 0 ? 'even' : 'odd']);
                $summaryRow++;
                $rank++;
            }

            // Total row for summary
            $sC->setCellValue('A' . $summaryRow, 'TOTAL');
            $sC->setCellValue('B' . $summaryRow, $grandTotalNg);
            $sC->setCellValue('C' . $summaryRow, '100%');
            $sC->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray($stStyle['totals']);

            // Auto-size columns A-U
            foreach (range('A', 'U') as $colLetter) {
                $sC->getColumnDimension($colLetter)->setAutoSize(true);
            }
        }

        $excel->setActiveSheetIndex(0);
        $path = $dir . DIRECTORY_SEPARATOR . '2-Production_Qty_PPM_' . $timestamp . '.xlsx';
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->setIncludeCharts(true);
        $writer->save($path);
        return ['path' => $path, 'name' => basename($path)];
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

        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("DPR System")->setTitle("Efficiency Machine Chart " . $year);

        // ========== Sheet 1: 7-Data ==========
        $excel->setActiveSheetIndex(0);
        $s1 = $excel->getActiveSheet(); $s1->setTitle('7-Data');

        // Row 1 headers (34 columns: A-H + ST1-ST5(I-M) + ST items(N-AJ) + Mach Eff %(AK) + Name(AL))
        $col = 'A';
        $s1->setCellValue($col++ . '1', 'YY-MM');        // A
        $s1->setCellValue($col++ . '1', 'Mo');             // B
        $s1->setCellValue($col++ . '1', 'Mach');           // C
        $s1->setCellValue($col++ . '1', 'SumOfW H');        // D
        $s1->setCellValue($col++ . '1', 'SumOfO T');        // E
        $s1->setCellValue($col++ . '1', 'Mo Mach Std Hr');  // F
        $s1->setCellValue($col++ . '1', 'Mach Eff Hr');     // G
        $s1->setCellValue($col++ . '1', 'SumOfTtl S T');     // H
        $s1->setCellValue($col++ . '1', 'SumOfTtl S T1');    // I  = LT PRODUKSI
        $s1->setCellValue($col++ . '1', 'SumOfTtl S T2');    // J  = LT ME
        $s1->setCellValue($col++ . '1', 'SumOfTtl S T3');    // K  = LT TM
        $s1->setCellValue($col++ . '1', 'SumOfTtl S T4');    // L  = LT PPIC
        $s1->setCellValue($col++ . '1', 'SumOfTtl S T5');    // M  = LT QA
        for ($sti = 0; $sti < 19; $sti++) {
            $catName = isset($ltCategoryNames[$sti]) && $ltCategoryNames[$sti] !== '' 
                ? $ltCategoryNames[$sti] 
                : 'SumOf' . ($sti + 1);
            $s1->setCellValue($col++ . '1', $catName);
        }
        $s1->setCellValue($col++ . '1', 'Mach Eff %');     // AG
        $s1->setCellValue($col++ . '1', 'Name');           // AH

        $r = 2;
        $effRows = $effData->result_array();
        foreach ($effRows as $row) {
            $mach = $row['Mach'];
            for ($m = 1; $m <= 12; $m++) {
                $k = sprintf('%02d', $m);
                $wh = floatval($row['wh' . $k]);
                $ot = floatval($row['ot' . $k]);
                $eff = floatval($row['eff' . $k]);
                $lt = floatval($row['lt' . $k]);
                $pt = floatval($row['pt' . $k]);
                $machEffPct = ($wh + $ot) > 0 ? round(($pt / ($wh + $ot)), 4) : 0;

                $col = 'A';
                $s1->setCellValue($col++ . $r, $year . '-' . $k);
                $s1->setCellValue($col++ . $r, $m);
                $s1->setCellValue($col++ . $r, $mach);
                $s1->setCellValue($col++ . $r, $wh > 0 ? $wh : '');
                $s1->setCellValue($col++ . $r, $ot > 0 ? $ot : '');
                $s1->setCellValue($col++ . $r, $wh + $ot > 0 ? $wh + $ot : '');
                $s1->setCellValue($col++ . $r, $pt > 0 ? $pt : '');
                $s1->setCellValue($col++ . $r, $lt > 0 ? $lt : '');          // H: SumOfTtl S T (grand total LT)
                // I-M: SumOfTtl S T1-T5 (per-kategori subtotals)
                foreach ($ltKategoriOrder as $kIdx => $kategori) {
                    $katTotal = 0;
                    $items = isset($ltKategoriMap[$kategori]) ? $ltKategoriMap[$kategori] : [];
                    foreach ($items as $itemName) {
                        if (isset($ltByCategory[intval($mach)][$m][$itemName])) {
                            $katTotal += $ltByCategory[intval($mach)][$m][$itemName];
                        }
                    }
                    $s1->setCellValue($col++ . $r, $katTotal > 0 ? round($katTotal, 2) : '');
                }
                // ST1-ST19: per-category loss time from DB
                for ($sti = 0; $sti < 19; $sti++) {
                    $catName = isset($ltCategoryNames[$sti]) ? $ltCategoryNames[$sti] : '';
                    $catVal = '';
                    if ($catName !== '' && isset($ltByCategory[intval($mach)][$m][$catName])) {
                        $catVal = $ltByCategory[intval($mach)][$m][$catName];
                        $catVal = $catVal > 0 ? $catVal : '';
                    }
                    $s1->setCellValue($col++ . $r, $catVal);
                }
                $s1->setCellValue($col++ . $r, $machEffPct > 0 ? $machEffPct : '');
                $s1->setCellValue($col++ . $r, $machineNames[intval($mach)] ?? '');
                $r++;
            }
        }

        // Auto-size columns
        for ($ci = 0; $ci < 35; $ci++) {
            $colLetter = $ci < 26 ? chr(65 + $ci) : chr(64 + (int)($ci / 26)) . chr(65 + $ci % 26);
            $s1->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // ========== Sheet 2: 7-Table ==========
        $excel->createSheet(); $excel->setActiveSheetIndex(1);
        $s2 = $excel->getActiveSheet(); $s2->setTitle('7-Table');

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
                $v = floatval($row['eff' . $k]);
                $s2->setCellValue($col++ . $r, $v > 0 ? $v : '');
                $total += $v;
            }
            $s2->setCellValue('B' . $r, $total > 0 ? round($total, 2) : '');
            $r++;
        }

        foreach (range('A', 'N') as $colLetter) {
            $s2->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // ========== Sheet 3: Machine Cap (per-month block layout matching raw Excel) ==========
        $excel->createSheet(); $excel->setActiveSheetIndex(2);
        $s3 = $excel->getActiveSheet(); $s3->setTitle('Machine Cap ' . $year);

        // Company header
        $s3->setCellValue('A1', 'P.T. Padma Soode Indonesia');
        $s3->setCellValue('A2', 'Plastic Division');
        $s3->setCellValue('A6', 'Machine Capacity ' . $year);

        $tonnages = [40, 55, 60, 80, 90, 120, 160, 200];

        // Group capData by month
        $capByMonth = [];
        foreach ($capData->result_array() as $row) {
            $capByMonth[intval($row['bulan'])][$row['tonnase']] = $row;
        }

        $blockRow = 10; // start row for first month block
        for ($m = 1; $m <= 12; $m++) {
            // Month label (e.g. "Jan/26")
            $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'];
            $s3->setCellValue('A' . $blockRow, $monthLabels[$m - 1] . '/' . substr($year, -2));

            // "Total" label at J
            $s3->setCellValue('J' . ($blockRow + 1), 'Total');

            // Tons header row
            $s3->setCellValue('A' . ($blockRow + 2), 'Tons');
            $s3->setCellValue('J' . ($blockRow + 2), 'Total');

            // Data rows (8 rows: Tons values, #Machines, Available Cap, Machine Use, Balance, Use%-1, Avail Cap+OT, Use%-2)
            $rowLabels = ['Tons', '#Machines', 'Available Capacity (Hrs)', 'Machine Use (Hrs)', 'Balance (Idle)', 'Machine Use (%) - 1', 'Available Capacity + OT', 'Machine Use (%) - 2'];
            for ($ri = 0; $ri < 8; $ri++) {
                $dr = $blockRow + 3 + $ri;
                $s3->setCellValue('A' . $dr, $rowLabels[$ri]);
            }

            // Tonnage values
            $drTons = $blockRow + 3;
            $totalMachines = 0;
            $totalAvailCap = 0;
            $totalMachineUse = 0;
            $totalBalance = 0;

            for ($ti = 0; $ti < count($tonnages); $ti++) {
                $ton = $tonnages[$ti];
                $colIdx = 1 + $ti; // 1-based column index

                $data = isset($capByMonth[$m][$ton]) ? $capByMonth[$m][$ton] : null;
                $machines = $data ? intval($data['jumlah_mesin']) : 0;
                $availCap = $data ? floatval($data['available_capacity']) : 0;
                $machineUse = $data ? floatval($data['machine_use']) : 0;
                $balance = $data ? floatval($data['balance_idle']) : 0;
                $usePct = $data ? floatval($data['pct_use']) : 0;
                $availCapOT = $availCap;
                $usePct2 = $availCap > 0 ? round(($machineUse / $availCapOT) * 100, 2) : 0;

                // Row drTons: Tons value (40, 55, 60, etc.)
                $s3->setCellValueByColumnAndRow($colIdx, $drTons, $ton);
                // Row drTons+1: #Machines
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 1, $machines > 0 ? $machines : '');
                // Row drTons+2: Available Capacity
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 2, $availCap > 0 ? $availCap : '');
                // Row drTons+3: Machine Use
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 3, $machineUse > 0 ? $machineUse : '');
                // Row drTons+4: Balance (Idle)
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 4, $balance);
                // Row drTons+5: Use%-1
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 5, $usePct > 0 ? $usePct : '');
                // Row drTons+6: Available Cap + OT
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 6, $availCapOT > 0 ? $availCapOT : '');
                // Row drTons+7: Use%-2
                $s3->setCellValueByColumnAndRow($colIdx, $drTons + 7, $usePct2 > 0 ? $usePct2 : '');

                $totalMachines += $machines;
                $totalAvailCap += $availCap;
                $totalMachineUse += $machineUse;
                $totalBalance += $balance;
            }

            // Total column (J = column index 9, after 8 tonnages B-I)
            $totalColIdx = 9;
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons, 'Total');
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 1, $totalMachines > 0 ? $totalMachines : '');
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 2, $totalAvailCap > 0 ? $totalAvailCap : '');
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 3, $totalMachineUse > 0 ? $totalMachineUse : '');
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 4, $totalBalance);
            $totalUsePct1 = $totalAvailCap > 0 ? round(($totalMachineUse / $totalAvailCap) * 100, 2) : 0;
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 5, $totalUsePct1 > 0 ? $totalUsePct1 : '');
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 6, $totalAvailCap > 0 ? $totalAvailCap : '');
            $totalUsePct2 = $totalAvailCap > 0 ? round(($totalMachineUse / $totalAvailCap) * 100, 2) : 0;
            $s3->setCellValueByColumnAndRow($totalColIdx, $drTons + 7, $totalUsePct2 > 0 ? $totalUsePct2 : '');

            $blockRow += 13; // 13 rows per month block (date + total label + tons header + 8 data + gap)
        }

        // Auto-size Machine Cap columns
        foreach (range('A', 'J') as $colLetter) {
            $s3->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // ========== Sheets 4-15: Jan-Des Monthly Sheets ==========
        $monthNamesEff = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Juli', 'Agust', 'Sept', 'Oct', 'Nov', 'Des'];
        $monthNames3 = $monthNamesEff;

        // Pre-compute monthly machine data for Table sheet
        $monthlyMachineData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyMachineData[$m] = [];
            foreach ($effRows as $row) {
                $mach = $row['Mach'];
                $k = sprintf('%02d', $m);
                $monthlyMachineData[$m][$mach] = [
                    'wh' => floatval($row['wh' . $k]),
                    'ot' => floatval($row['ot' . $k]),
                    'eff' => floatval($row['eff' . $k]),
                    'lt' => floatval($row['lt' . $k]),
                    'pt' => floatval($row['pt' . $k]),
                ];
            }
        }

        // Get machine tonnage mapping and count
        $machineTonnage = [];
        $machineCount = 0;
        $effRows2 = $effRows;
        if (!empty($effRows2)) {
            $machIds = array_map(function($r) { return intval($r['Mach']); }, $effRows2);
            if (!empty($machIds)) {
                $tonResult = $this->db->query(
                    "SELECT no_mesin, tonnase FROM t_no_mesin WHERE aktif = 1 AND no_mesin IN (" . implode(',', $machIds) . ")"
                );
                foreach ($tonResult->result_array() as $tRow) {
                    $machineTonnage[intval($tRow['no_mesin'])] = intval($tRow['tonnase']);
                }
            }
        }
        // Also count all active machines
        $allMachResult = $this->db->query("SELECT COUNT(*) as cnt FROM t_no_mesin WHERE aktif = 1");
        $machineCount = $allMachResult ? intval($allMachResult->row()->cnt) : count($effRows);

        $monthLabelsCap = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'];

        for ($m = 1; $m <= 12; $m++) {
            $excel->createSheet();
            $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
            $sM = $excel->getActiveSheet();
            $sM->setTitle($monthNamesEff[$m - 1]);

            // Company header
            $sM->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
            $sM->setCellValue('A2', 'Plastic Division');
            $sM->setCellValue('A3', 'Production  Dept.');
            $sM->getStyle('A1:A3')->applyFromArray(['font' => ['bold' => true, 'size' => 12]]);

            // Month label
            $sM->setCellValue('A5', $monthLabelsCap[$m - 1] . '/' . substr($year, -2));
            $sM->getStyle('A5')->applyFromArray(['font' => ['bold' => true, 'size' => 11]]);

            $colStart = 3; // D = column index 3
            $mc = $machineCount;
            $totalColIdx = $colStart + $mc;

            // Style definitions
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '2F5496']],
                'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            ];
            $labelStyle = [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'BDD7EE']],
            ];
            $totalStyle = [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D9E2F3']],
            ];
            $altStyle = [
                'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'F2F2F2']],
            ];

            // Row 6: "NO." label + machine numbers
            $sM->setCellValue('C6', 'NO.');
            $sM->getStyle('C6')->applyFromArray($labelStyle);
            for ($mi = 0; $mi < $mc; $mi++) {
                $sM->setCellValueByColumnAndRow($colStart + $mi, 6, $mi + 1);
                $sM->getStyleByColumnAndRow($colStart + $mi, 6)->applyFromArray($headerStyle);
            }
            $sM->setCellValueByColumnAndRow($totalColIdx, 6, 'Total');
            $sM->getStyleByColumnAndRow($totalColIdx, 6)->applyFromArray($headerStyle);

            // Row 7: Tons
            $sM->setCellValue('C7', 'Tons');
            $sM->getStyle('C7')->applyFromArray($labelStyle);
            for ($mi = 0; $mi < $mc; $mi++) {
                $machNum = $mi + 1;
                $sM->setCellValueByColumnAndRow($colStart + $mi, 7, $machineTonnage[$machNum] ?? '');
            }

            // Row 8: Available Capacity
            $sM->setCellValue('C8', 'Available Capacity');
            $sM->getStyle('C8')->applyFromArray($labelStyle);
            $totalAvail = 0;
            for ($mi = 0; $mi < $mc; $mi++) {
                $machNum = $mi + 1;
                $mData = isset($monthlyMachineData[$m][$machNum]) ? $monthlyMachineData[$m][$machNum] : null;
                $avail = $mData ? ($mData['wh'] + $mData['ot']) : 0;
                $sM->setCellValueByColumnAndRow($colStart + $mi, 8, $avail > 0 ? $avail : '');
                $totalAvail += $avail;
            }
            $sM->setCellValueByColumnAndRow($totalColIdx, 8, $totalAvail > 0 ? $totalAvail : '');
            $sM->getStyleByColumnAndRow($totalColIdx, 8)->applyFromArray($totalStyle);

            // Row 9: Machine Use (Hrs) — using production_time
            $sM->setCellValue('C9', 'Machine Use (Hrs)');
            $sM->getStyle('C9')->applyFromArray($labelStyle);
            $totalUse = 0;
            for ($mi = 0; $mi < $mc; $mi++) {
                $machNum = $mi + 1;
                $mData = isset($monthlyMachineData[$m][$machNum]) ? $monthlyMachineData[$m][$machNum] : null;
                $use = $mData ? $mData['pt'] : 0;
                $sM->setCellValueByColumnAndRow($colStart + $mi, 9, $use > 0 ? $use : '');
                $totalUse += $use;
                if ($mi % 2 === 1) {
                    $sM->getStyleByColumnAndRow($colStart + $mi, 9)->applyFromArray($altStyle);
                }
            }
            $sM->setCellValueByColumnAndRow($totalColIdx, 9, $totalUse > 0 ? round($totalUse, 2) : '');
            $sM->getStyleByColumnAndRow($totalColIdx, 9)->applyFromArray($totalStyle);

            // Row 10: Machine Use (%)
            $sM->setCellValue('C10', 'Machine Use (%)');
            $sM->getStyle('C10')->applyFromArray($labelStyle);
            for ($mi = 0; $mi < $mc; $mi++) {
                $machNum = $mi + 1;
                $mData = isset($monthlyMachineData[$m][$machNum]) ? $monthlyMachineData[$m][$machNum] : null;
                $avail = $mData ? ($mData['wh'] + $mData['ot']) : 0;
                $use = $mData ? $mData['pt'] : 0;
                $pct = $avail > 0 ? round(($use / $avail) * 100, 2) : 0;
                $sM->setCellValueByColumnAndRow($colStart + $mi, 10, $pct > 0 ? $pct : '');
                if ($mi % 2 === 1) {
                    $sM->getStyleByColumnAndRow($colStart + $mi, 10)->applyFromArray($altStyle);
                }
            }

            // Row 11: WH
            $sM->setCellValue('C11', 'WH');
            $sM->getStyle('C11')->applyFromArray($labelStyle);
            $totalWh = 0;
            for ($mi = 0; $mi < $mc; $mi++) {
                $machNum = $mi + 1;
                $mData = isset($monthlyMachineData[$m][$machNum]) ? $monthlyMachineData[$m][$machNum] : null;
                $wh = $mData ? $mData['wh'] : 0;
                $sM->setCellValueByColumnAndRow($colStart + $mi, 11, $wh > 0 ? $wh : '');
                $totalWh += $wh;
                if ($mi % 2 === 1) {
                    $sM->getStyleByColumnAndRow($colStart + $mi, 11)->applyFromArray($altStyle);
                }
            }
            $sM->setCellValueByColumnAndRow($totalColIdx, 11, $totalWh > 0 ? $totalWh : '');
            $sM->getStyleByColumnAndRow($totalColIdx, 11)->applyFromArray($totalStyle);

            // Row 12: OT
            $sM->setCellValue('C12', 'OT');
            $sM->getStyle('C12')->applyFromArray($labelStyle);
            $totalOt = 0;
            for ($mi = 0; $mi < $mc; $mi++) {
                $machNum = $mi + 1;
                $mData = isset($monthlyMachineData[$m][$machNum]) ? $monthlyMachineData[$m][$machNum] : null;
                $ot = $mData ? $mData['ot'] : 0;
                $sM->setCellValueByColumnAndRow($colStart + $mi, 12, $ot > 0 ? $ot : '');
                $totalOt += $ot;
                if ($mi % 2 === 1) {
                    $sM->getStyleByColumnAndRow($colStart + $mi, 12)->applyFromArray($altStyle);
                }
            }
            $sM->setCellValueByColumnAndRow($totalColIdx, 12, $totalOt > 0 ? $totalOt : '');
            $sM->getStyleByColumnAndRow($totalColIdx, 12)->applyFromArray($totalStyle);

            // Auto-size columns
            $lastCol = $totalColIdx + 1;
            for ($ci = 0; $ci <= $lastCol; $ci++) {
                $colLetter = $ci < 26 ? chr(65 + $ci) : chr(64 + (int)($ci / 26)) . chr(65 + $ci % 26);
                $sM->getColumnDimension($colLetter)->setAutoSize(true);
            }
        }

        // ========== Sheet: Total (year summary) ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sT = $excel->getActiveSheet();
        $sT->setTitle('Total');

        $sT->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sT->setCellValue('A2', 'Plastic Division');
        $sT->setCellValue('A3', 'Production  Dept.');
        $sT->setCellValue('I4', ' ');
        $sT->setCellValue('A5', 'Efficiency Machine  Report:');
        $sT->setCellValue('H5', gmmktime(0, 0, 0, 1, 1, $year) / 86400 + 25569);

        // Row 36: available time
        $sT->setCellValue('C36', 'available time');
        $rowAvail = 37;
        $sT->setCellValue('D36', '2-49');
        $sT->setCellValue('E36', '50-97');
        $sT->setCellValue('F36', '98-145');
        $sT->setCellValue('G36', '146-193');
        $sT->setCellValue('H36', '194-241');
        $sT->setCellValue('I36', '242-289');
        $sT->setCellValue('J36', '290-337');
        $sT->setCellValue('K36', '338-385');
        $sT->setCellValue('L36', '386-433');
        $sT->setCellValue('M36', '434-481');
        $sT->setCellValue('N36', '482-529');
        $sT->setCellValue('O36', '530-577');

        // Row 38: "Machine" at D38
        $sT->setCellValue('D38', 'Machine');

        // Row 39: Month headers
        $sT->setCellValue('C39', 'NO.');
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec', 'Total'];
        for ($i = 0; $i < 13; $i++) {
            $sT->setCellValueByColumnAndRow(3 + $i, 38, $monthLabels[$i]);
        }

        // Data rows 40-50
        $summaryLabels = [
            'Machine (unit)', 'Available Capacity', 'Machine Use (Hrs)',
            'Total Loss Time', 'Idle', 'Machine Use (%)', 'WH', 'OT',
            '', // row 48: blank
            'm up time ( hour )', 'm up time (%)'
        ];
        $summaryTotals = [];
        for ($mi = 0; $mi < 13; $mi++) { $summaryTotals[$mi] = []; }

        // Compute per-month totals
        for ($m = 1; $m <= 12; $m++) {
            $monthTotalAvail = 0;
            $monthTotalUse = 0;
            $monthTotalLT = 0;
            $monthTotalWh = 0;
            $monthTotalOt = 0;
            $machineCount = 0;

            foreach ($effRows as $row) {
                $mData = isset($monthlyMachineData[$m][$row['Mach']]) ? $monthlyMachineData[$m][$row['Mach']] : null;
                if ($mData) {
                    $avail = $mData['wh'] + $mData['ot'];
                    $monthTotalAvail += $avail;
                    $monthTotalUse += $mData['eff'];
                    $monthTotalLT += $mData['lt'];
                    $monthTotalWh += $mData['wh'];
                    $monthTotalOt += $mData['ot'];
                    $machineCount++;
                }
            }

            $colIdx = 3 + $m;
            $summaryTotals[$m]['machines'] = $machineCount;
            $summaryTotals[$m]['availCap'] = $monthTotalAvail;
            $summaryTotals[$m]['machineUse'] = $monthTotalUse;
            $summaryTotals[$m]['lossTime'] = $monthTotalLT;
            $summaryTotals[$m]['idle'] = $monthTotalAvail - $monthTotalUse;
            $summaryTotals[$m]['usePct'] = $monthTotalAvail > 0 ? round(($monthTotalUse / $monthTotalAvail) * 100, 2) : 0;
            $summaryTotals[$m]['wh'] = $monthTotalWh;
            $summaryTotals[$m]['ot'] = $monthTotalOt;
            $summaryTotals[$m]['mUpTime'] = $monthTotalAvail - $monthTotalLT;
            $summaryTotals[$m]['mUpTimePct'] = $monthTotalAvail > 0 ? round((($monthTotalAvail - $monthTotalLT) / $monthTotalAvail) * 100, 2) : 0;
        }

        // Grand totals (column P = index 15)
        $grandTotals = [];
        $grandTotals['machines'] = 47;
        $grandTotals['availCap'] = 0;
        $grandTotals['machineUse'] = 0;
        $grandTotals['lossTime'] = 0;
        $grandTotals['idle'] = 0;
        $grandTotals['wh'] = 0;
        $grandTotals['ot'] = 0;
        $grandTotals['mUpTime'] = 0;
        for ($m = 1; $m <= 12; $m++) {
            $grandTotals['availCap'] += $summaryTotals[$m]['availCap'];
            $grandTotals['machineUse'] += $summaryTotals[$m]['machineUse'];
            $grandTotals['lossTime'] += $summaryTotals[$m]['lossTime'];
            $grandTotals['idle'] += $summaryTotals[$m]['idle'];
            $grandTotals['wh'] += $summaryTotals[$m]['wh'];
            $grandTotals['ot'] += $summaryTotals[$m]['ot'];
            $grandTotals['mUpTime'] += $summaryTotals[$m]['mUpTime'];
        }
        $grandTotals['usePct'] = $grandTotals['availCap'] > 0 ? round(($grandTotals['machineUse'] / $grandTotals['availCap']) * 100, 2) : 0;
        $grandTotals['mUpTimePct'] = $grandTotals['availCap'] > 0 ? round(($grandTotals['mUpTime'] / $grandTotals['availCap']) * 100, 2) : 0;

        // Write summary data rows
        $dataRowIdx = 40;
        // Row 40: Machine (unit)
        $sT->setCellValue('C40', 'Machine (unit)');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['machines'] > 0 ? $summaryTotals[$m]['machines'] : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['machines']);
        $dataRowIdx++;

        // Row 41: Available Capacity
        $sT->setCellValue('C41', 'Available Capacity');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['availCap'] > 0 ? round($summaryTotals[$m]['availCap'], 2) : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['availCap'] > 0 ? round($grandTotals['availCap'], 2) : '');
        $dataRowIdx++;

        // Row 42: Machine Use (Hrs)
        $sT->setCellValue('C42', 'Machine Use (Hrs)');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['machineUse'] > 0 ? round($summaryTotals[$m]['machineUse'], 2) : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['machineUse'] > 0 ? round($grandTotals['machineUse'], 2) : '');
        $dataRowIdx++;

        // Row 43: Total Loss Time
        $sT->setCellValue('C43', 'Total Loss Time');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['lossTime'] > 0 ? round($summaryTotals[$m]['lossTime'], 2) : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['lossTime'] > 0 ? round($grandTotals['lossTime'], 2) : '');
        $dataRowIdx++;

        // Row 44: Idle
        $sT->setCellValue('C44', 'Idle');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, round($summaryTotals[$m]['idle'], 2));
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, round($grandTotals['idle'], 2));
        $dataRowIdx++;

        // Row 45: Machine Use (%)
        $sT->setCellValue('C45', 'Machine Use (%)');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['usePct'] > 0 ? $summaryTotals[$m]['usePct'] : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['usePct'] > 0 ? $grandTotals['usePct'] : '');
        $dataRowIdx++;

        // Row 46: WH
        $sT->setCellValue('C46', 'WH');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['wh'] > 0 ? round($summaryTotals[$m]['wh'], 2) : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, round($grandTotals['wh'], 2));
        $dataRowIdx++;

        // Row 47: OT
        $sT->setCellValue('C47', 'OT');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['ot'] > 0 ? round($summaryTotals[$m]['ot'], 2) : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, round($grandTotals['ot'], 2));
        $sT->setCellValueByColumnAndRow(16, $dataRowIdx, $grandTotals['ot'] > 0 ? round($grandTotals['usePct'], 2) : ''); // OT/total ratio
        $dataRowIdx++;

        // Row 48: blank row
        $dataRowIdx++;

        // Row 49: m up time (hour)
        $sT->setCellValue('C49', 'm up time ( hour )');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['mUpTime'] > 0 ? round($summaryTotals[$m]['mUpTime'], 2) : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['mUpTime'] > 0 ? round($grandTotals['mUpTime'], 2) : '');
        $dataRowIdx++;

        // Row 50: m up time (%)
        $sT->setCellValue('C50', 'm up time (%)');
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, $dataRowIdx, $summaryTotals[$m]['mUpTimePct'] > 0 ? $summaryTotals[$m]['mUpTimePct'] : '');
        }
        $sT->setCellValueByColumnAndRow(15, $dataRowIdx, $grandTotals['mUpTimePct'] > 0 ? $grandTotals['mUpTimePct'] : '');

        // Row 51-55: Repeat company header
        $sT->setCellValue('A51', 'PT Ciptajaya Kreasindo Utama');
        $sT->setCellValue('A52', 'Plastic Division');
        $sT->setCellValue('A53', 'Production  Dept.');
        $sT->setCellValue('I54', ' ');
        $sT->setCellValue('A55', 'Efficiency Machine  Report:');
        $sT->setCellValue('H55', gmmktime(0, 0, 0, 1, 1, $year) / 86400 + 25569);

        // Auto-size
        foreach (range('A', 'Q') as $colLetter) {
            $sT->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // ========== Sheet: Table (per-machine detail with Machine Use % & Use Hrs) ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sTb = $excel->getActiveSheet();
        $sTb->setTitle('Table');

        $sTb->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sTb->setCellValue('A2', 'Plastic Division');
        $sTb->setCellValue('A3', 'Production  Dept.');
        $sTb->setCellValue('J4', ' ');
        $sTb->setCellValue('A5', 'Machine Monthly Report:');
        $sTb->setCellValue('H5', gmmktime(0, 0, 0, 1, 1, $year) / 86400 + 25569);
        $sTb->setCellValue('E8', floatval($year));

        // Row 9: Month headers
        $sTb->setCellValue('D9', 'Month');
        for ($m = 1; $m <= 12; $m++) {
            $sTb->setCellValueByColumnAndRow(4 + $m, 9, $m);
        }
        $sTb->setCellValueByColumnAndRow(16, 9, 'Total');

        // Row 10: Available Capacity header
        $sTb->setCellValue('C10', 'M/C');
        $sTb->setCellValue('D10', 'Available Capacity');
        $yearDayData = [];
        $ydResult = $this->db->query("SELECT bulan, total FROM year_day WHERE tahun = ? ORDER BY bulan", [$year]);
        if ($ydResult) {
            foreach ($ydResult->result_array() as $ydRow) {
                $yearDayData[intval($ydRow['bulan'])] = floatval($ydRow['total']);
            }
        }
        for ($m = 1; $m <= 12; $m++) {
            $avail = isset($yearDayData[$m]) ? $yearDayData[$m] : 462;
            $sTb->setCellValueByColumnAndRow(4 + $m, 10, $avail);
        }
        $totalAvailCap = 0;
        for ($m = 1; $m <= 12; $m++) {
            $avail = isset($yearDayData[$m]) ? $yearDayData[$m] : 462;
            $totalAvailCap += $avail;
        }
        $sTb->setCellValueByColumnAndRow(16, 10, $totalAvailCap);

        // Row 11+: Per-machine data alternating Machine Use (Hrs) and Machine Use (%)
        $tableRow = 11;
        foreach ($effRows as $row) {
            $mach = $row['Mach'];
            $sTb->setCellValue('C' . $tableRow, $mach);

            // Machine Use (Hrs) row
            $sTb->setCellValue('D' . $tableRow, 'Machine Use (Hrs)');
            $totalUseHrs = 0;
            for ($m = 1; $m <= 12; $m++) {
                $mData = isset($monthlyMachineData[$m][$mach]) ? $monthlyMachineData[$m][$mach] : null;
                $useHrs = $mData ? $mData['eff'] : 0;
                $sTb->setCellValueByColumnAndRow(4 + $m, $tableRow, $useHrs > 0 ? $useHrs : '');
                $totalUseHrs += $useHrs;
            }
            $sTb->setCellValueByColumnAndRow(16, $tableRow, $totalUseHrs > 0 ? round($totalUseHrs, 2) : '');
            $tableRow++;

            // Machine Use (%) row
            $sTb->setCellValue('D' . $tableRow, 'Machine Use (%)');
            $totalUsePct = 0;
            for ($m = 1; $m <= 12; $m++) {
                $mData = isset($monthlyMachineData[$m][$mach]) ? $monthlyMachineData[$m][$mach] : null;
                $avail = $mData ? ($mData['wh'] + $mData['ot']) : 0;
                $useHrs = $mData ? $mData['eff'] : 0;
                $pct = $avail > 0 ? round(($useHrs / $avail) * 100, 2) : 0;
                $sTb->setCellValueByColumnAndRow(4 + $m, $tableRow, $pct > 0 ? $pct : '');
                $totalUsePct += $pct;
            }
            $sTb->setCellValueByColumnAndRow(16, $tableRow, $totalUsePct > 0 ? round($totalUsePct / 12, 2) : '');
            $tableRow++;
        }

        // Auto-size
        foreach (range('A', 'Q') as $colLetter) {
            $sTb->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // ========== Sheet: Grafpermach (placeholder) ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sGf = $excel->getActiveSheet();
        $sGf->setTitle('Grafpermach');

        $sGf->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sGf->setCellValue('A2', 'Plastic Division');
        $sGf->setCellValue('A3', 'Production  Dept.');
        $sGf->setCellValue('I4', ' ');
        $sGf->setCellValue('A5', 'Machine Monthly Report:');
        $sGf->setCellValue('E5', 'Machine 1 ~ Machine 12');

        $excel->setActiveSheetIndex(0);
        $path = $dir . DIRECTORY_SEPARATOR . '3-Efficiency_Machine_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save($path);
        return ['path' => $path, 'name' => basename($path)];
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

        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("DPR System")->setTitle("Stop Time Machine Chart " . $year);

        $effRows = $effData ? $effData->result_array() : [];

        // Pre-fetch machine names
        $machineNames = [];
        $machResult = $this->db->query(
            "SELECT m.no_mesin, nm.nama_mesin, m.tonnase FROM t_no_mesin m
             LEFT JOIN t_nama_mesin nm ON nm.id_nama_mesin = m.id_nama_mesin
             WHERE m.aktif = 1 ORDER BY m.no_mesin"
        );
        $machineTonnage = [];
        foreach ($machResult->result_array() as $mr) {
            $machineNames[intval($mr['no_mesin'])] = $mr['nama_mesin'];
            $machineTonnage[intval($mr['no_mesin'])] = intval($mr['tonnase']);
        }
        foreach ($effRows as $row) {
            if (!isset($machineNames[intval($row['Mach'])])) {
                $machineNames[intval($row['Mach'])] = '';
            }
        }
        $allMachResult = $this->db->query("SELECT COUNT(*) as cnt FROM t_no_mesin WHERE aktif = 1");
        $machineCount = $allMachResult ? intval($allMachResult->row()->cnt) : count($effRows);

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

        // Available capacity per month
        $capResult = $this->mr->get_available_capacity_per_month($year);
        $stdHrs = [];
        if ($capResult) {
            foreach ($capResult->result_array() as $cr) { $stdHrs[$cr['bulan']] = $cr['std_hours']; }
        }

        // Pre-compute monthly machine data
        $monthlyMachineData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyMachineData[$m] = [];
            foreach ($effRows as $row) {
                $mach = $row['Mach'];
                $k = sprintf('%02d', $m);
                $monthlyMachineData[$m][$mach] = [
                    'wh' => floatval($row['wh' . $k]),
                    'ot' => floatval($row['ot' . $k]),
                    'eff' => floatval($row['eff' . $k]),
                    'lt' => floatval($row['lt' . $k]),
                    'pt' => floatval($row['pt' . $k]),
                ];
            }
        }

        // ========== Sheet 1: Table ==========
        $excel->setActiveSheetIndex(0);
        $s1 = $excel->getActiveSheet(); $s1->setTitle('Table');

        $s1->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $s1->setCellValue('A2', 'Plastic Division');
        $s1->setCellValue('A3', 'Production  Dept.');
        $s1->getStyle('A1:A3')->applyFromArray(['font' => ['bold' => true, 'size' => 12]]);
        $s1->setCellValue('A5', 'Machine Monthly Report:');
        $s1->setCellValue('H5', 'Jan/' . substr($year, -2));
        $s1->setCellValue('E8', floatval($year));

        // Row 8: Month headers
        $s1->setCellValue('D8', 'Month');
        for ($m = 1; $m <= 12; $m++) { $s1->setCellValueByColumnAndRow(4 + $m, 8, $m); }

        // Row 9: Available Capacity
        $s1->setCellValue('C9', 'M/C');
        $s1->setCellValue('D9', 'Available Capacity');
        for ($m = 1; $m <= 12; $m++) {
            $s1->setCellValueByColumnAndRow(4 + $m, 9, isset($stdHrs[$m]) ? $stdHrs[$m] : 462);
        }

        // Rows 10+: Per-machine Machine Use (Hrs) + Machine Use (%)
        $r = 10;
        foreach ($effRows as $row) {
            $mach = $row['Mach'];
            $s1->setCellValue('C' . $r, $mach);

            // Machine Use (Hrs) row
            $s1->setCellValue('D' . $r, 'Machine Use (Hrs)');
            $totalUseHrs = 0;
            for ($m = 1; $m <= 12; $m++) {
                $mData = isset($monthlyMachineData[$m][$mach]) ? $monthlyMachineData[$m][$mach] : null;
                $useHrs = $mData ? $mData['pt'] : 0;
                $s1->setCellValueByColumnAndRow(4 + $m, $r, $useHrs > 0 ? round($useHrs, 2) : '');
                $totalUseHrs += $useHrs;
            }
            $r++;

            // Machine Use (%) row
            $s1->setCellValue('D' . $r, 'Machine Use (%)');
            for ($m = 1; $m <= 12; $m++) {
                $cap = isset($stdHrs[$m]) ? floatval($stdHrs[$m]) : 462;
                $mData = isset($monthlyMachineData[$m][$mach]) ? $monthlyMachineData[$m][$mach] : null;
                $useHrs = $mData ? $mData['pt'] : 0;
                $pct = $cap > 0 ? round(($useHrs / $cap) * 100, 2) : 0;
                $s1->setCellValueByColumnAndRow(4 + $m, $r, $pct > 0 ? $pct : '');
            }
            $r++;
        }

        foreach (range('A', 'Q') as $cl) { $s1->getColumnDimension($cl)->setAutoSize(true); }

        // ========== Sheet 2: 7-Table ==========
        $excel->createSheet(); $excel->setActiveSheetIndex(1);
        $s3 = $excel->getActiveSheet(); $s3->setTitle('7-Table');
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
        foreach (range('A', 'N') as $cl) { $s3->getColumnDimension($cl)->setAutoSize(true); }

        // ========== Sheet 3: 7-Data ==========
        $excel->createSheet(); $excel->setActiveSheetIndex(2);
        $s2 = $excel->getActiveSheet(); $s2->setTitle('7-Data');

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
        for ($ci = 0; $ci < 35; $ci++) {
            $cl = $ci < 26 ? chr(65 + $ci) : chr(64 + (int)($ci / 26)) . chr(65 + $ci % 26);
            $s2->getColumnDimension($cl)->setAutoSize(true);
        }

        // ========== Sheets 4-15: Jan-Des Monthly Sheets (STOP TIME DATA) ==========
        $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'];
        $fixedMachineCount = 47;
        $labelCol = 2; // column C (index 2)
        $dataStartCol = 3; // column D (index 3), machines 1-47
        $totalCol = $dataStartCol + $fixedMachineCount; // column index 50 = BY

        $stHeaderStyle = [
            'font' => ['bold' => true, 'size' => 11],
        ];
        $labelStyle = [
            'font' => ['bold' => true, 'size' => 10],
        ];
        $totalLabelStyle = [
            'font' => ['bold' => true, 'size' => 10],
            'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D9E2F3']],
        ];

        for ($m = 1; $m <= 12; $m++) {
            $excel->createSheet();
            $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
            $sM = $excel->getActiveSheet();
            $sM->setTitle($monthLabels[$m - 1]);

            // Header
            $sM->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
            $sM->setCellValue('A2', 'Plastic Division');
            $sM->setCellValue('A3', 'Production  Dept.');
            $sM->getStyle('A1:A3')->applyFromArray(['font' => ['bold' => true, 'size' => 12]]);
            $sM->setCellValue('C5', 'STOP TIME DATA');
            $sM->setCellValue('F5', $monthLabels[$m - 1] . ' ' . $year);
            $sM->getStyle('C5')->applyFromArray(['font' => ['bold' => true, 'size' => 11]]);

            $r = 6; // start row for ST sections
            $stIdx = 1;
            $grandTotalByMachine = [];
            for ($mi = 1; $mi <= $fixedMachineCount; $mi++) { $grandTotalByMachine[$mi] = 0; }

            foreach ($ltKategoriOrder as $kategori) {
                $items = isset($ltKategoriMap[$kategori]) ? $ltKategoriMap[$kategori] : [];

                // ST-N header
                $sM->setCellValueByColumnAndRow($labelCol, $r, 'ST-' . $stIdx);
                $sM->getStyleByColumnAndRow($labelCol, $r)->applyFromArray($stHeaderStyle);
                $r++;

                // Machine numbers row (1-47) + Total
                for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                    $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $mi);
                }
                $sM->setCellValueByColumnAndRow($totalCol, $r, 'Total');
                $sM->getStyleByColumnAndRow($totalCol, $r)->applyFromArray($labelStyle);
                $r++;

                // "Machine / Ton" label row
                $sM->setCellValueByColumnAndRow($labelCol, $r, 'Machine / Ton');
                for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                    $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $mi);
                }
                $sM->setCellValueByColumnAndRow($totalCol, $r, 'Total');
                $r++;

                // M/C Use row
                $sM->setCellValueByColumnAndRow($labelCol, $r, 'M/C Use');
                $mcUseTotal = 0;
                for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                    $mData = isset($monthlyMachineData[$m][$mi]) ? $monthlyMachineData[$m][$mi] : null;
                    $pt = $mData ? $mData['pt'] : 0;
                    $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $pt > 0 ? round($pt, 2) : '');
                    $mcUseTotal += $pt;
                }
                $sM->setCellValueByColumnAndRow($totalCol, $r, $mcUseTotal > 0 ? round($mcUseTotal, 2) : '');
                $r++;

                // Individual LT item rows
                $stTotalByMachine = [];
                for ($mi = 1; $mi <= $fixedMachineCount; $mi++) { $stTotalByMachine[$mi] = 0; }
                $stGrandTotal = 0;

                foreach ($items as $itemName) {
                    $sM->setCellValueByColumnAndRow($labelCol, $r, $itemName);
                    $itemTotal = 0;
                    for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                        $val = 0;
                        if (isset($ltByCategory[$mi][$m][$itemName])) {
                            $val = floatval($ltByCategory[$mi][$m][$itemName]);
                        }
                        $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $val > 0 ? round($val, 2) : 0);
                        $stTotalByMachine[$mi] += $val;
                        $itemTotal += $val;
                    }
                    $sM->setCellValueByColumnAndRow($totalCol, $r, $itemTotal > 0 ? round($itemTotal, 2) : 0);
                    $stGrandTotal += $itemTotal;
                    $r++;
                }

                // Ttl Stop Time N row
                $sM->setCellValueByColumnAndRow($labelCol, $r, 'Ttl Stop Time ' . $stIdx);
                $sM->getStyleByColumnAndRow($labelCol, $r)->applyFromArray($labelStyle);
                for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                    $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $stTotalByMachine[$mi] > 0 ? round($stTotalByMachine[$mi], 2) : 0);
                    $grandTotalByMachine[$mi] += $stTotalByMachine[$mi];
                }
                $sM->setCellValueByColumnAndRow($totalCol, $r, $stGrandTotal > 0 ? round($stGrandTotal, 2) : 0);
                $r++;

                // Blank row between sections
                $r++;

                $stIdx++;
            }

            // Grand Total Stop Time
            $r++;
            $grandSTTotal = 0;
            for ($mi = 1; $mi <= $fixedMachineCount; $mi++) { $grandSTTotal += $grandTotalByMachine[$mi]; }
            $sM->setCellValueByColumnAndRow($labelCol, $r, '');
            for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $grandTotalByMachine[$mi] > 0 ? round($grandTotalByMachine[$mi], 2) : '');
            }
            $sM->setCellValueByColumnAndRow($totalCol, $r, $grandSTTotal > 0 ? round($grandSTTotal, 2) : '');
            $r += 2;

            // Grand Total M/C Use
            $grandMcUse = 0;
            for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                $mData = isset($monthlyMachineData[$m][$mi]) ? $monthlyMachineData[$m][$mi] : null;
                $pt = $mData ? $mData['pt'] : 0;
                $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $pt > 0 ? round($pt, 2) : '');
                $grandMcUse += $pt;
            }
            $sM->setCellValueByColumnAndRow($totalCol, $r, $grandMcUse > 0 ? round($grandMcUse, 2) : '');
            $r += 2;

            // AKTUAL % (total stop time / total M/C Use)
            $aktualPerMachine = 0;
            $aktualTotal = $grandMcUse > 0 ? round(($grandSTTotal / $grandMcUse) * 100, 2) : 0;
            $sM->setCellValueByColumnAndRow($labelCol, $r, 'AKTUAL');
            for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                $mData = isset($monthlyMachineData[$m][$mi]) ? $monthlyMachineData[$m][$mi] : null;
                $pt = $mData ? $mData['pt'] : 0;
                $pct = $pt > 0 ? round(($grandTotalByMachine[$mi] / $pt) * 100, 2) : 0;
                $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, $pct > 0 ? $pct : '');
            }
            $sM->setCellValueByColumnAndRow($totalCol, $r, $aktualTotal > 0 ? $aktualTotal : '');
            $r += 2;

            // TARGET LOSS TIME
            $sM->setCellValueByColumnAndRow($labelCol, $r, 'TARGET LOSS TIME');
            for ($mi = 1; $mi <= $fixedMachineCount; $mi++) {
                $sM->setCellValueByColumnAndRow($dataStartCol + $mi - 1, $r, 5);
            }
            $sM->setCellValueByColumnAndRow($totalCol, $r, 5);

            // Auto-size columns
            $lastCol = $totalCol + 1;
            for ($ci = 0; $ci <= $lastCol; $ci++) {
                $cl = $ci < 26 ? chr(65 + $ci) : chr(64 + (int)($ci / 26)) . chr(65 + $ci % 26);
                $sM->getColumnDimension($cl)->setAutoSize(true);
            }
        }

        // ========== Sheet 16: Total ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sT = $excel->getActiveSheet();
        $sT->setTitle('Total');

        $sT->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sT->setCellValue('A2', 'Plastic Division');
        $sT->setCellValue('A3', 'Production  Dept.');
        $sT->getStyle('A1:A3')->applyFromArray(['font' => ['bold' => true, 'size' => 12]]);
        $sT->setCellValue('A5', 'Machine Stop Time  Report:');
        $sT->setCellValue('F5', 'Year : ' . $year);

        // Compute per-month per-kategori totals
        $monthKategoriTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthKategoriTotals[$m] = [];
            foreach ($ltKategoriOrder as $kategori) {
                $katTotal = 0;
                $items = isset($ltKategoriMap[$kategori]) ? $ltKategoriMap[$kategori] : [];
                foreach ($items as $itemName) {
                    foreach ($effRows as $row) {
                        $mach = $row['Mach'];
                        if (isset($ltByCategory[intval($mach)][$m][$itemName])) {
                            $katTotal += $ltByCategory[intval($mach)][$m][$itemName];
                        }
                    }
                }
                $monthKategoriTotals[$m][$kategori] = $katTotal;
            }
        }

        // Compute per-month total machine use (production_time)
        $monthTotalUse = [];
        for ($m = 1; $m <= 12; $m++) {
            $totalUse = 0;
            foreach ($effRows as $row) {
                $mData = isset($monthlyMachineData[$m][$row['Mach']]) ? $monthlyMachineData[$m][$row['Mach']] : null;
                if ($mData) $totalUse += $mData['pt'];
            }
            $monthTotalUse[$m] = $totalUse;
        }

        // Row 41: Target 5s across all months
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, 41, 5);
        }

        // Rows 42-53: Stop Time summary
        $sT->setCellValue('D42', 'Month');
        $sT->setCellValue('P42', 'Total');
        $monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, 43, $monthNames[$m - 1]);
        }

        // Rows 44-48: Stop Time 1-5 (dynamic from $ltKategoriOrder)
        $stRow = 44;
        foreach ($ltKategoriOrder as $kategori) {
            $stNum = $stRow - 43;
            $sT->setCellValue('C' . $stRow, 'Stop Time ' . $stNum);
            for ($m = 1; $m <= 12; $m++) {
                $v = isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
                $sT->setCellValueByColumnAndRow(3 + $m, $stRow, round($v, 2));
            }
            $stRow++;
        }
        // Row 49: Total Stop Time
        $sT->setCellValue('C49', 'Total Stop Time');
        $grandTotalST = 0;
        for ($m = 1; $m <= 12; $m++) {
            $totalST = 0;
            foreach ($ltKategoriOrder as $kategori) {
                $totalST += isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
            }
            $sT->setCellValueByColumnAndRow(3 + $m, 49, round($totalST, 2));
            $grandTotalST += $totalST;
        }
        $sT->setCellValueByColumnAndRow(15, 49, round($grandTotalST, 2));

        // Row 51: M/c Use
        $sT->setCellValue('C51', 'M/c Use');
        $grandTotalUse = 0;
        for ($m = 1; $m <= 12; $m++) {
            $sT->setCellValueByColumnAndRow(3 + $m, 51, round($monthTotalUse[$m], 2));
            $grandTotalUse += $monthTotalUse[$m];
        }
        $sT->setCellValueByColumnAndRow(15, 51, round($grandTotalUse, 2));

        // Row 52: Total Stop Time %
        $sT->setCellValue('C52', 'Total Stop Time %');
        for ($m = 1; $m <= 12; $m++) {
            $totalST = 0;
            foreach ($ltKategoriOrder as $kategori) {
                $totalST += isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
            }
            $pct = $monthTotalUse[$m] > 0 ? round(($totalST / $monthTotalUse[$m]) * 100, 2) : 0;
            $sT->setCellValueByColumnAndRow(3 + $m, 52, $pct);
        }
        $grandPct = $grandTotalUse > 0 ? round(($grandTotalST / $grandTotalUse) * 100, 2) : 0;
        $sT->setCellValueByColumnAndRow(15, 52, $grandPct);

        // Row 54: Stop Time Target % (only at Jan column)
        $sT->setCellValue('C54', 'Stop Time Target %');
        $sT->setCellValueByColumnAndRow(4, 54, 5);

        foreach (range('A', 'Q') as $cl) { $sT->getColumnDimension($cl)->setAutoSize(true); }

        // ========== Sheet 17: Lost Time Progress ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sLP = $excel->getActiveSheet();
        $sLP->setTitle('Lost Time Progress');

        $sLP->setCellValue('D1', 'LOST TIME By SECTION');
        $sLP->setCellValue('A2', 'Target  : ');
        $sLP->setCellValue('B2', 5);
        $sLP->setCellValue('C2', '%');
        $sLP->setCellValue('A3', 'Year  :');
        $sLP->setCellValue('B3', $year);

        // Row 4: Headers
        $sLP->setCellValue('B4', 'Month');
        $sLP->setCellValue('C4', $year - 1);
        $sLP->setCellValue('D4', 'Achievement');
        $sLP->setCellValue('AB4', 'Total');
        $sLP->setCellValue('AC4', 'Average');

        // Row 5: Month names
        $sLP->setCellValue('A5', 'Kind Of Lost time');
        for ($m = 1; $m <= 12; $m++) {
            $colIdx = 3 + ($m - 1) * 2;
            $sLP->setCellValueByColumnAndRow($colIdx, 5, $monthLabels[$m - 1]);
            $sLP->setCellValueByColumnAndRow($colIdx + 1, 5, '%');
        }

        // Write ST-1 through ST-5 sections
        $r = 6;
        $stIdx = 1;
        foreach ($ltKategoriOrder as $kategori) {
            $items = isset($ltKategoriMap[$kategori]) ? $ltKategoriMap[$kategori] : [];
            if (empty($items)) continue;

            // Section header
            $sLP->setCellValue('A' . $r, 'ST-' . $stIdx);
            $sLP->getStyle('A' . $r)->applyFromArray(['font' => ['bold' => true]]);
            $r++;

            // Individual items
            foreach ($items as $itemName) {
                $sLP->setCellValue('A' . $r, $itemName);
                $totalItem = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $val = 0;
                    foreach ($effRows as $row) {
                        if (isset($ltByCategory[intval($row['Mach'])][$m][$itemName])) {
                            $val += $ltByCategory[intval($row['Mach'])][$m][$itemName];
                        }
                    }
                    $colIdx = 3 + ($m - 1) * 2;
                    $sLP->setCellValueByColumnAndRow($colIdx, $r, $val > 0 ? round($val, 2) : 0);
                    $totalItem += $val;
                }
                $sLP->setCellValueByColumnAndRow(27, $r, $totalItem > 0 ? round($totalItem, 2) : 0);
                $r++;
            }

            // Total row
            $sLP->setCellValue('A' . $r, 'Total');
            $sLP->getStyle('A' . $r)->applyFromArray(['font' => ['bold' => true]]);
            for ($m = 1; $m <= 12; $m++) {
                $colIdx = 3 + ($m - 1) * 2;
                $sLP->setCellValueByColumnAndRow($colIdx, $r, isset($monthKategoriTotals[$m][$kategori]) ? round($monthKategoriTotals[$m][$kategori], 2) : 0);
            }
            $grandKat = 0;
            for ($m = 1; $m <= 12; $m++) {
                $grandKat += isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
            }
            $sLP->setCellValueByColumnAndRow(27, $r, $grandKat > 0 ? round($grandKat, 2) : 0);
            $r++;

            // % row
            $sLP->setCellValue('A' . $r, '%');
            for ($m = 1; $m <= 12; $m++) {
                $colIdx = 3 + ($m - 1) * 2;
                $katTotal = isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
                $pct = $monthTotalUse[$m] > 0 ? round(($katTotal / $monthTotalUse[$m]) * 100, 4) : 0;
                $sLP->setCellValueByColumnAndRow($colIdx + 1, $r, $pct > 0 ? $pct : 0);
            }
            $r += 2; // gap
            $stIdx++;
        }

        // Grand totals at bottom
        $sLP->setCellValue('A' . $r, 'Total Stop time');
        for ($m = 1; $m <= 12; $m++) {
            $totalST = 0;
            foreach ($ltKategoriOrder as $kategori) {
                $totalST += isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
            }
            $colIdx = 3 + ($m - 1) * 2;
            $sLP->setCellValueByColumnAndRow($colIdx, $r, $totalST > 0 ? round($totalST, 2) : 0);
        }
        $r += 2;

        $sLP->setCellValue('A' . $r, 'Mesin Use');
        for ($m = 1; $m <= 12; $m++) {
            $colIdx = 3 + ($m - 1) * 2;
            $sLP->setCellValueByColumnAndRow($colIdx, $r, $monthTotalUse[$m] > 0 ? round($monthTotalUse[$m], 2) : 0);
        }
        $grandUse = array_sum($monthTotalUse);
        $sLP->setCellValueByColumnAndRow(27, $r, $grandUse > 0 ? round($grandUse, 2) : 0);
        $r += 2;

        $sLP->setCellValue('A' . $r, '% Stop time');
        $sLP->setCellValue('C' . $r, 5);
        for ($m = 1; $m <= 12; $m++) {
            $totalST = 0;
            foreach ($ltKategoriOrder as $kategori) {
                $totalST += isset($monthKategoriTotals[$m][$kategori]) ? $monthKategoriTotals[$m][$kategori] : 0;
            }
            $pct = $monthTotalUse[$m] > 0 ? round(($totalST / $monthTotalUse[$m]) * 100, 4) : 0;
            $colIdx = 3 + ($m - 1) * 2;
            $sLP->setCellValueByColumnAndRow($colIdx, $r, $pct > 0 ? $pct : '-');
        }

        // ========== Sheet 18: Prod Stop Time (placeholder) ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sPS = $excel->getActiveSheet();
        $sPS->setTitle('Prod Stop Time');
        $sPS->setCellValue('A1', 'P.T. Padma Soode Indonesia');
        $sPS->setCellValue('A2', 'Plastic Division');
        $sPS->setCellValue('A3', 'Production  Dept.');
        $sPS->setCellValue('A5', 'Stop Time Production  (year ' . $year . ')');

        // ========== Sheet 19: Grafpermach (placeholder) ==========
        $excel->createSheet();
        $excel->setActiveSheetIndex($excel->getSheetCount() - 1);
        $sGf = $excel->getActiveSheet();
        $sGf->setTitle('Grafpermach');
        $sGf->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sGf->setCellValue('A2', 'Plastic Division');
        $sGf->setCellValue('A3', 'Production  Dept.');
        $sGf->setCellValue('A5', 'Machine Monthly Report:');
        $sGf->setCellValue('E5', 'Machine 1 ~ Machine 12');

        $excel->setActiveSheetIndex(0);
        $path = $dir . DIRECTORY_SEPARATOR . '4-Stop_Time_Machine_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save($path);
        return ['path' => $path, 'name' => basename($path)];
    }

    private function build_seven_data_excel_zip($year, $dir, $timestamp)
    {
        $this->load->library('Excel');
        $dataRows = $this->mr->get_7table_data_from_year($year);
        $summaryRows = $this->mr->get_7_table_summary_data($year);
        if ((!$dataRows || $dataRows->num_rows() === 0) && (!$summaryRows || $summaryRows->num_rows() === 0)) {
            throw new Exception('No 7 Data / 7 Table for ' . $year);
        }
        $excel = new PHPExcel(); $excel->getProperties()->setCreator("DPR System")->setTitle("7 Data & 7 Table " . $year);

        $excel->setActiveSheetIndex(0); $s1=$excel->getActiveSheet(); $s1->setTitle('7 Data');
        $headersData = [
            'No','Mesin','Month','SumOfWH','SumOfOT','Total Hour Std','MachEffHour','TotalST',
            'No Material','No Packing','Material Problem','Adjust Parameter','Daily Checklist',
            'Pre-heating Material','Cleaning Hopper Barrel','Setup Mold','Setup Parameter Machine',
            'IPQC Inspection','Machine','Hopper Dryer','Robot','MTC','Chiller','Compressor',
            'Listrik','Overhole','QC Lolos','Mold Problem','Trial','Setup Awal Produksi','MCH Eff Percentage'
        ];
        $col='A'; foreach($headersData as $h){ $s1->setCellValue($col.'1',$h); $col++; }
        $r=2; $i=1;
        if ($dataRows) {
            foreach($dataRows->result_array() as $row){ $col='A';
                $s1->setCellValue($col++.$r,$i++);
                $s1->setCellValue($col++.$r,$row['no_mesin']);
                $s1->setCellValue($col++.$r,$row['month']);
                $s1->setCellValue($col++.$r,$row['SumOfWH']);
                $s1->setCellValue($col++.$r,$row['SumOfOT']);
                $s1->setCellValue($col++.$r,$row['total_hour_std']);
                $s1->setCellValue($col++.$r,$row['MachEffHour']);
                $s1->setCellValue($col++.$r,$row['TotalST']);
                $s1->setCellValue($col++.$r,$row['no_material']);
                $s1->setCellValue($col++.$r,$row['no_packing']);
                $s1->setCellValue($col++.$r,$row['material_problem']);
                $s1->setCellValue($col++.$r,$row['adjust_parameter']);
                $s1->setCellValue($col++.$r,$row['daily_checklist']);
                $s1->setCellValue($col++.$r,$row['pre_heating_material']);
                $s1->setCellValue($col++.$r,$row['cleaning_hopper_barrel']);
                $s1->setCellValue($col++.$r,$row['setup_mold']);
                $s1->setCellValue($col++.$r,$row['setup_parameter_machine']);
                $s1->setCellValue($col++.$r,$row['ipqc_inspection']);
                $s1->setCellValue($col++.$r,$row['machine']);
                $s1->setCellValue($col++.$r,$row['hopper_dryer']);
                $s1->setCellValue($col++.$r,$row['robot']);
                $s1->setCellValue($col++.$r,$row['mtc']);
                $s1->setCellValue($col++.$r,$row['chiller']);
                $s1->setCellValue($col++.$r,$row['compressor']);
                $s1->setCellValue($col++.$r,$row['listrik']);
                $s1->setCellValue($col++.$r,$row['overhole']);
                $s1->setCellValue($col++.$r,$row['qc_lolos']);
                $s1->setCellValue($col++.$r,$row['mold_problem']);
                $s1->setCellValue($col++.$r,$row['trial']);
                $s1->setCellValue($col++.$r,$row['setup_awal_produksi']);
                $s1->setCellValue($col++.$r,$row['mch_eff_percentage']);
                $r++;
            }
        }

        $excel->createSheet(); $excel->setActiveSheetIndex(1); $s2=$excel->getActiveSheet(); $s2->setTitle('7 Table');
        $headersSummary = ['Mach','Total Of Mach Eff Hr','01','02','03','04','05','06','07','08','09','10','11','12'];
        $col='A'; foreach($headersSummary as $h){ $s2->setCellValue($col.'1',$h); $col++; }
        $r=2;
        if ($summaryRows) {
            foreach($summaryRows->result_array() as $row){
                $s2->setCellValue('A'.$r,$row['Mach']);
                $s2->setCellValue('B'.$r,$row['total_mach_eff_hr']);
                $s2->setCellValue('C'.$r,$row['01']); $s2->setCellValue('D'.$r,$row['02']); $s2->setCellValue('E'.$r,$row['03']);
                $s2->setCellValue('F'.$r,$row['04']); $s2->setCellValue('G'.$r,$row['05']); $s2->setCellValue('H'.$r,$row['06']);
                $s2->setCellValue('I'.$r,$row['07']); $s2->setCellValue('J'.$r,$row['08']); $s2->setCellValue('K'.$r,$row['09']);
                $s2->setCellValue('L'.$r,$row['10']); $s2->setCellValue('M'.$r,$row['11']); $s2->setCellValue('N'.$r,$row['12']);
                $r++;
            }
        }

        $excel->setActiveSheetIndex(0);
        $path = $dir . DIRECTORY_SEPARATOR . '7Data_7Table_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($excel,'Excel2007')->save($path);
        return ['path'=>$path,'name'=>basename($path)];
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

        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("DPR System")->setTitle("Machine Use Chart " . $year);
        $sheetIdx = 0;

        // ======================== SHEET 1: Rate ========================
        $excel->setActiveSheetIndex($sheetIdx);
        $sRate = $excel->getActiveSheet(); $sRate->setTitle('Rate');
        $sRate->setCellValue('A1', 'TON');
        $sRate->setCellValue('B1', 'Total Of Ttl Cost');
        $sRate->setCellValue('C1', substr($year, 2) . '-01');
        $r = 2;
        foreach ($tonnages as $ton) {
            $sRate->setCellValue('A' . $r, $ton);
            $costVal = isset($rateByTonMap[$ton]) ? $rateByTonMap[$ton] : 0;
            $sRate->setCellValue('B' . $r, $costVal);
            $sRate->setCellValue('C' . $r, $costVal);
            $r++;
        }
        $sheetIdx++;

        // ======================== SHEET 2: 7-Data ========================
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s7 = $excel->getActiveSheet(); $s7->setTitle('7-Data');

        // Headers: A=YY-MM, B=Mo, C=Mach, D=SumOfWH, E=SumOfOT, F=Mo Mach Std Hr, G=Mach Eff Hr,
        // H=SumOfTtl S T, I=SumOfTtl S T1..L=SumOfTtl S T4,
        // M=SumOfST1..AA=SumOfST19, AB=Mach Eff %, AC=Name
        $col = 0;
        $h7 = ['YY-MM', 'Mo', 'Mach', 'SumOfW H', 'SumOfO T', 'Mo Mach Std Hr', 'Mach Eff Hr', 'SumOfTtl S T',
               'SumOfTtl S T1', 'SumOfTtl S T2', 'SumOfTtl S T3', 'SumOfTtl S T4'];
        foreach ($ltCategories as $i => $cat) {
            $h7[] = 'SumOfST' . ($i + 1);
        }
        $h7[] = 'Mach Eff %';
        $h7[] = 'Name';
        foreach ($h7 as $h) {
            $s7->setCellValueByColumnAndRow($col++, 1, $h);
        }

        $r = 2;
        if ($effData) {
            foreach ($effData->result_array() as $row) {
                for ($m = 1; $m <= 12; $m++) {
                    $k = sprintf('%02d', $m);
                    $col = 0;
                    $s7->setCellValueByColumnAndRow($col++, $r, $year . '-' . $k);
                    $s7->setCellValueByColumnAndRow($col++, $r, intval($k));
                    $machId = intval($row['Mach']);
                    $s7->setCellValueByColumnAndRow($col++, $r, $machId);
                    $wh = floatval($row['wh' . $k]);
                    $ot = floatval($row['ot' . $k]);
                    $s7->setCellValueByColumnAndRow($col++, $r, $wh);
                    $s7->setCellValueByColumnAndRow($col++, $r, $ot);
                    $totalWH = $wh + $ot;
                    $s7->setCellValueByColumnAndRow($col++, $r, $totalWH);
                    $effHr = floatval($row['eff' . $k]);
                    $s7->setCellValueByColumnAndRow($col++, $r, $effHr);
                    $ltTotal = floatval($row['lt' . $k]);
                    $s7->setCellValueByColumnAndRow($col++, $r, $ltTotal);

                    // ST1-ST4 groupings (first 4 categories grouped by first letter/type)
                    $stGrouped = [0, 0, 0, 0];
                    $ltForMach = isset($ltLookup[$machId][$m]) ? $ltLookup[$machId][$m] : [];
                    $catIdx = 0;
                    foreach ($ltCategories as $cat) {
                        $val = isset($ltForMach[$cat]) ? $ltForMach[$cat] : 0;
                        $s7->setCellValueByColumnAndRow($col++, $r, $val);
                        // Group into ST1-ST4 (every 5 categories)
                        if ($catIdx < 5) $stGrouped[0] += $val;
                        elseif ($catIdx < 10) $stGrouped[1] += $val;
                        elseif ($catIdx < 14) $stGrouped[2] += $val;
                        else $stGrouped[3] += $val;
                        $catIdx++;
                    }

                    // Go back and fill ST1-ST4
                    $s7->setCellValueByColumnAndRow(8, $r, $stGrouped[0]);
                    $s7->setCellValueByColumnAndRow(9, $r, $stGrouped[1]);
                    $s7->setCellValueByColumnAndRow(10, $r, $stGrouped[2]);
                    $s7->setCellValueByColumnAndRow(11, $r, $stGrouped[3]);

                    // Mach Eff %
                    $stdH = isset($stdHrs[$m]) ? $stdHrs[$m] : 462;
                    $effPct = $stdH > 0 ? round(($effHr / $stdH) * 100, 4) : 0;
                    $s7->setCellValueByColumnAndRow($col++, $r, $effPct);

                    // Name (machine name as zero-padded string)
                    $s7->setCellValueByColumnAndRow($col++, $r, str_pad($machId, 3, '0', STR_PAD_LEFT));

                    $r++;
                }
            }
        }
        $sheetIdx++;

        // ======================== SHEET 3: 8-Data ========================
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s8 = $excel->getActiveSheet(); $s8->setTitle('8-Data');

        $col = 0;
        $s8->setCellValueByColumnAndRow($col++, 1, 'YY-MM');
        $s8->setCellValueByColumnAndRow($col++, 1, 'Customer');
        $s8->setCellValueByColumnAndRow($col++, 1, 'Mo');
        $s8->setCellValueByColumnAndRow($col++, 1, 'name');
        $s8->setCellValueByColumnAndRow($col++, 1, 'Total Of SumOfMach Eff Hr');
        foreach ($tonnages as $ton) {
            $s8->setCellValueByColumnAndRow($col++, 1, $ton);
        }

        $r = 2;
        // Blank separator row
        $r++;
        if ($custTonCrossData) {
            $prevMonth = null;
            foreach ($custTonCrossData->result_array() as $row) {
                $mth = intval($row['bulan']);
                if ($mth !== $prevMonth) {
                    if ($prevMonth !== null) $r++; // blank between months
                    $s8->setCellValueByColumnAndRow(0, $r, $row['yy_mm']);
                    $s8->setCellValueByColumnAndRow(2, $r, sprintf('%02d', $mth));
                    $r++;
                    $prevMonth = $mth;
                }
                $s8->setCellValueByColumnAndRow(0, $r, $row['yy_mm']);
                $s8->setCellValueByColumnAndRow(1, $r, $row['customer']);
                $s8->setCellValueByColumnAndRow(2, $r, sprintf('%02d', $mth));
                $s8->setCellValueByColumnAndRow(3, $r, sprintf('%02d', $mth) . $row['customer']);
                $s8->setCellValueByColumnAndRow(4, $r, floatval($row['total_eff_hr']));
                $ton = intval($row['tonnase']);
                if ($ton > 0) {
                    $tonColIdx = array_search($ton, $tonnages);
                    if ($tonColIdx !== false) {
                        $s8->setCellValueByColumnAndRow(5 + $tonColIdx, $r, floatval($row['total_eff_hr']));
                    }
                }
                $r++;
            }
        }
        $sheetIdx++;

        // ======================== SHEET 4: 9-Data ========================
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $s9 = $excel->getActiveSheet(); $s9->setTitle('9-Data');

        $col = 0;
        $s9->setCellValueByColumnAndRow($col++, 1, 'YY-MM');
        $s9->setCellValueByColumnAndRow($col++, 1, 'Customer');
        $s9->setCellValueByColumnAndRow($col++, 1, 'Total Of Mach Eff Hr%%');
        foreach ($tonnages as $ton) {
            $s9->setCellValueByColumnAndRow($col++, 1, $ton);
        }

        $r = 2;
        $prevMonth = null;
        $prevCust = null;
        $currentRow = $r;
        if ($custTonCrossData) {
            foreach ($custTonCrossData->result_array() as $row) {
                $mth = intval($row['bulan']);
                $cust = $row['customer'];
                $ton = intval($row['tonnase']);
                $hrs = floatval($row['total_eff_hr']);
                $totalCap = isset($capMap[$ton][$mth]) ? $capMap[$ton][$mth] : 0;
                $pct = $totalCap > 0 ? round(($hrs / $totalCap) * 100, 4) : 0;

                if ($mth !== $prevMonth) {
                    if ($prevMonth !== null) { $currentRow++; }
                    $s9->setCellValueByColumnAndRow(0, $currentRow, $row['yy_mm']);
                    $currentRow++;
                    $prevMonth = $mth;
                    $prevCust = null;
                }

                if ($cust !== $prevCust) {
                    $currentRow++;
                    $s9->setCellValueByColumnAndRow(0, $currentRow, $row['yy_mm']);
                    $s9->setCellValueByColumnAndRow(1, $currentRow, $cust);
                    $prevCust = $cust;
                }

                if ($ton > 0) {
                    $tonColIdx = array_search($ton, $tonnages);
                    if ($tonColIdx !== false) {
                        $s9->setCellValueByColumnAndRow(3 + $tonColIdx, $currentRow, $pct);
                    }
                }
            }
        }
        $sheetIdx++;

        // ======================== SHEETS 5-16: Monthly sheets ========================
        for ($m = 1; $m <= 12; $m++) {
            $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
            $sm = $excel->getActiveSheet(); $sm->setTitle($monthNames[$m - 1] . substr($year, 2));

            $stdH = isset($stdHrs[$m]) ? $stdHrs[$m] : 462;
            $mthCustomers = isset($custTonLookup[$m]) ? $custTonLookup[$m] : [];
            $mthCustomerList = array_keys($mthCustomers);
            sort($mthCustomerList);

            // Row 1-3: Company header
            $sm->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
            $sm->setCellValue('A2', 'Plastic Division');
            $sm->setCellValue('A3', 'PPIC Dept.');

            // Row 5: Title
            $sm->setCellValue('A5', 'Machine use by Ton By Customer');

            // Row 6: Machine counts per tonnage
            // Row 7: Total capacity (45658 or similar)
            // Row 8: Tons header with tonnage values
            // Row 9: Av. Hr (capacity per tonnage)

            // Build tonnage column positions
            // C col = Mc Use (hours), D col = %, E col = Mc Use (Unit)
            // Then 3 cols per tonnage: hours, %, unit
            // Tonnages: 40, 55, 60, 80, 90, 120, 125, 160, 200
            // Raw layout: starting at col F for 40T
            // Pattern: [No][Customer][Mc Use][%][Unit] then [40T: Hrs][%][Unit] [55T: Hrs][%][Unit] ...

            $tonColStart = 5; // column F (0-indexed = 5) for first tonnage
            $totalCol = $tonColStart + count($tonnages) * 3; // TOTAL column
            $totalUnitCol = $totalCol + 2; // Total (unit) column

            // Row 6: Machine count per tonnage at specific positions
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3 + 1; // % column for this tonnage
                $machCnt = isset($machCountByTon[$ton]) ? $machCountByTon[$ton] : 0;
                $sm->setCellValueByColumnAndRow($c, 6, $machCnt);
            }

            // Row 7: Total std hours and working days
            $sm->setCellValueByColumnAndRow(1, 7, $stdH * array_sum($machCountByTon));
            $sm->setCellValueByColumnAndRow(2, 7, $stdH);
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3 + 1;
                $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
                $sm->setCellValueByColumnAndRow($c, 7, $cap);
            }
            // Working days
            $wd = round($stdH / 21);
            $sm->setCellValueByColumnAndRow($totalCol - 1, 7, $wd);
            $sm->setCellValueByColumnAndRow($totalCol + 1, 7, $wd);

            // Row 8: Tons labels
            $sm->setCellValueByColumnAndRow(1, 8, 'Tons');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $sm->setCellValueByColumnAndRow($c, 8, $ton);
            }
            $sm->setCellValueByColumnAndRow($totalCol, 8, 'TOTAL');
            $sm->setCellValueByColumnAndRow($totalCol + 2, 8, 'Total');

            // Row 9: Av. Hr capacity per tonnage
            $sm->setCellValueByColumnAndRow(2, 9, 'Av. Hr');
            $totalAvHr = 0;
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $sm->setCellValueByColumnAndRow($c, 9, 'Av. Hr');
                $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
                $sm->setCellValueByColumnAndRow($c + 1, 9, $cap);
                $totalAvHr += $cap;
            }
            $sm->setCellValueByColumnAndRow($totalCol - 1, 9, 'Av. Hr');
            $sm->setCellValueByColumnAndRow($totalCol, 9, $totalAvHr);
            $sm->setCellValueByColumnAndRow($totalCol + 1, 9, 'Av. Hr');
            $sm->setCellValueByColumnAndRow($totalCol + 2, 9, 'Mc Use');

            // Row 10: Column headers
            $sm->setCellValueByColumnAndRow(0, 10, 'No.');
            $sm->setCellValueByColumnAndRow(1, 10, 'Customer');
            $sm->setCellValueByColumnAndRow(2, 10, 'Mc Use (hours)');
            $sm->setCellValueByColumnAndRow(3, 10, '%');
            $sm->setCellValueByColumnAndRow(4, 10, 'Mc Use (Unit)');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $sm->setCellValueByColumnAndRow($c, 10, 'Mc Use (hours)');
                $sm->setCellValueByColumnAndRow($c + 1, 10, '%');
                $sm->setCellValueByColumnAndRow($c + 2, 10, 'Mc Use (Unit)');
            }
            $sm->setCellValueByColumnAndRow($totalCol, 10, 'Mc Use (hours)');
            $sm->setCellValueByColumnAndRow($totalCol + 1, 10, '%');
            $sm->setCellValueByColumnAndRow($totalCol + 2, 10, '(unit)');

            // Row 11: blank

            // Data rows starting at row 12
            $dataRow = 12;
            $no = 1;
            $grandTotalHrs = 0;
            $grandTotalPct = 0;
            $grandTotalUnit = 0;

            foreach ($mthCustomerList as $cust) {
                $tonData = $mthCustomers[$cust];
                $custTotal = 0;

                $sm->setCellValueByColumnAndRow(0, $dataRow, $no++);
                $sm->setCellValueByColumnAndRow(1, $dataRow, $cust);

                // Per tonnage
                foreach ($tonnages as $ti => $ton) {
                    $c = $tonColStart + $ti * 3;
                    $hrs = isset($tonData[$ton]) ? $tonData[$ton] : 0;
                    $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 1;
                    $pct = $cap > 0 ? round(($hrs / $cap) * 100, 4) : 0;
                    $unit = $cap > 0 ? round($hrs / $cap, 4) : 0;

                    $sm->setCellValueByColumnAndRow($c, $dataRow, $hrs > 0 ? $hrs : '');
                    $sm->setCellValueByColumnAndRow($c + 1, $dataRow, $pct > 0 ? $pct : '');
                    $sm->setCellValueByColumnAndRow($c + 2, $dataRow, $unit > 0 ? $unit : '');
                    $custTotal += $hrs;
                }

                // Customer total
                $totalCap = $totalAvHr > 0 ? $totalAvHr : 1;
                $custPct = round(($custTotal / $totalCap) * 100, 4);
                $custUnit = round($custTotal / $totalCap, 4);

                $sm->setCellValueByColumnAndRow(2, $dataRow, $custTotal > 0 ? $custTotal : '');
                $sm->setCellValueByColumnAndRow(3, $dataRow, $custPct > 0 ? $custPct : '');
                $sm->setCellValueByColumnAndRow(4, $dataRow, $custUnit > 0 ? $custUnit : '');
                $sm->setCellValueByColumnAndRow($totalCol, $dataRow, $custTotal > 0 ? $custTotal : '');
                $sm->setCellValueByColumnAndRow($totalCol + 1, $dataRow, $custPct > 0 ? $custPct : '');
                $sm->setCellValueByColumnAndRow($totalCol + 2, $dataRow, $custUnit > 0 ? $custUnit : '');

                $grandTotalHrs += $custTotal;
                $grandTotalPct += $custPct;
                $grandTotalUnit += $custUnit;
                $dataRow++;
            }

            // Total row
            $sm->setCellValueByColumnAndRow(2, $dataRow, $grandTotalHrs);
            $sm->setCellValueByColumnAndRow(3, $dataRow, 100);
            $sm->setCellValueByColumnAndRow($totalCol, $dataRow, $grandTotalHrs);
            $sm->setCellValueByColumnAndRow($totalCol + 1, $dataRow, 100);

            // Per tonnage totals
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $tonTotal = 0;
                foreach ($mthCustomerList as $cust) {
                    $tonTotal += isset($mthCustomers[$cust][$ton]) ? $mthCustomers[$cust][$ton] : 0;
                }
                $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 1;
                $pct = $cap > 0 ? round(($tonTotal / $cap) * 100, 4) : 0;
                $sm->setCellValueByColumnAndRow($c, $dataRow, $tonTotal);
                $sm->setCellValueByColumnAndRow($c + 1, $dataRow, $pct);
            }

            // Tonnage summary table (rows 26-38 in raw)
            $tonSumRow = $dataRow + 5;
            $sm->setCellValueByColumnAndRow(1, $tonSumRow, "Ton's");
            $sm->setCellValueByColumnAndRow(2, $tonSumRow, 'Usage');
            $sm->setCellValueByColumnAndRow(3, $tonSumRow, 'Cap');
            $sm->setCellValueByColumnAndRow(4, $tonSumRow, '%');
            $tonSumRow++;
            foreach ($tonnages as $ton) {
                $tonTotal = 0;
                foreach ($mthCustomerList as $cust) {
                    $tonTotal += isset($mthCustomers[$cust][$ton]) ? $mthCustomers[$cust][$ton] : 0;
                }
                $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
                $pct = $cap > 0 ? round(($tonTotal / $cap) * 100, 4) : 0;
                $sm->setCellValueByColumnAndRow(1, $tonSumRow, $ton);
                $sm->setCellValueByColumnAndRow(2, $tonSumRow, $tonTotal);
                $sm->setCellValueByColumnAndRow(3, $tonSumRow, $cap);
                $sm->setCellValueByColumnAndRow(4, $tonSumRow, $pct);
                $tonSumRow++;
            }
            // Grand total
            $sm->setCellValueByColumnAndRow(1, $tonSumRow, 'Total');
            $sm->setCellValueByColumnAndRow(2, $tonSumRow, $grandTotalHrs);
            $sm->setCellValueByColumnAndRow(3, $tonSumRow, $totalAvHr);
            $sm->setCellValueByColumnAndRow(4, $tonSumRow, $totalAvHr > 0 ? round(($grandTotalHrs / $totalAvHr) * 100, 4) : 0);

            // Section 2: Machine Rate By ton (rows 43+)
            $sec2Row = $tonSumRow + 5;
            $sm->setCellValueByColumnAndRow(0, $sec2Row, 'PT Ciptajaya Kreasindo Utama');
            $sm->setCellValueByColumnAndRow(0, $sec2Row + 1, 'Plastic Division');
            $sm->setCellValueByColumnAndRow(0, $sec2Row + 2, 'PPIC Dept.');
            $sm->setCellValueByColumnAndRow(0, $sec2Row + 4, 'Machine Rate By ton');
            $sm->setCellValueByColumnAndRow(1, $sec2Row + 5, $stdH * array_sum($machCountByTon));

            $rateRow = $sec2Row + 6;
            $sm->setCellValueByColumnAndRow(0, $rateRow, 'Machine Ton');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $sm->setCellValueByColumnAndRow($c, $rateRow, $ton);
            }
            $sm->setCellValueByColumnAndRow($totalCol, $rateRow, 'TOTAL');

            $rateRow++;
            $sm->setCellValueByColumnAndRow(0, $rateRow, 'Av. Machine (Hr)');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
                $sm->setCellValueByColumnAndRow($c, $rateRow, $cap);
            }
            $totalMachHr = 0;
            foreach ($tonnages as $ton) {
                $totalMachHr += isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
            }
            $sm->setCellValueByColumnAndRow($totalCol, $rateRow, $totalMachHr);

            $rateRow += 2;
            $sm->setCellValueByColumnAndRow(0, $rateRow, 'Ttl Machine rate Std/ Month (US$)');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $cap = isset($capMap[$ton][$m]) ? $capMap[$ton][$m] : 0;
                $sm->setCellValueByColumnAndRow($c, $rateRow, round($cap * $fctVal, 2));
            }

            $rateRow += 3;
            $sm->setCellValueByColumnAndRow(0, $rateRow, 'Machine use (Hr)');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $tonTotal = 0;
                foreach ($mthCustomerList as $cust) {
                    $tonTotal += isset($mthCustomers[$cust][$ton]) ? $mthCustomers[$cust][$ton] : 0;
                }
                $sm->setCellValueByColumnAndRow($c, $rateRow, $tonTotal);
            }

            $rateRow++;
            $sm->setCellValueByColumnAndRow(0, $rateRow, 'Ttl Machine rate Std/ Month (US$)');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $tonTotal = 0;
                foreach ($mthCustomerList as $cust) {
                    $tonTotal += isset($mthCustomers[$cust][$ton]) ? $mthCustomers[$cust][$ton] : 0;
                }
                $sm->setCellValueByColumnAndRow($c, $rateRow, round($tonTotal * $fctVal, 2));
            }

            $rateRow++;
            $sm->setCellValueByColumnAndRow(0, $rateRow, 'Ttl Machine rate ACt/ Month (US$)');
            foreach ($tonnages as $ti => $ton) {
                $c = $tonColStart + $ti * 3;
                $cost = isset($rateByTonMap[$ton]) ? $rateByTonMap[$ton] : 0;
                $sm->setCellValueByColumnAndRow($c, $rateRow, $cost);
            }

            $sheetIdx++;
        }

        // ======================== SHEET 17: Total by month by Cust ========================
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $sMC = $excel->getActiveSheet(); $sMC->setTitle('Total by month by Cust');

        $sMC->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sMC->setCellValue('A2', 'Plastic Division');
        $sMC->setCellValue('A3', 'PPIC Dept.');
        $sMC->setCellValue('A5', 'Machine use by Month By Customer');

        // Year label
        $sMC->setCellValue('B8', 'Year :');
        $sMC->setCellValue('C8', intval($year));
        $sMC->setCellValue('D8', $defaultWd);

        $sMC->setCellValue('C9', 'Month');
        $sMC->setCellValue('AK9', 'TOTAL');
        $sMC->setCellValue('AM9', 'Avg / Month');
        $sMC->setCellValue('AN9', '%');

        // Row 10: Month names and column headers
        $monthCols = [2, 5, 8, 11, 14, 17, 20, 23, 26, 28, 30, 33]; // C, F, I, L, O, R, U, X, AA, AC, AE, AH
        for ($mi = 0; $mi < 12; $mi++) {
            $c = $monthCols[$mi];
            $sMC->setCellValueByColumnAndRow($c - 1, 10, $monthNames[$mi]);
        }
        $sMC->setCellValueByColumnAndRow(0, 10, 'No.');
        $sMC->setCellValueByColumnAndRow(1, 10, 'Customer');

        // Per month: Hours, %, WD
        for ($mi = 0; $mi < 12; $mi++) {
            $c = $monthCols[$mi];
            $sMC->setCellValueByColumnAndRow($c, 10, 'Hours');
            $sMC->setCellValueByColumnAndRow($c + 1, 10, '%');
            $wdVal = isset($stdHrs[$mi + 1]) ? round($stdHrs[$mi + 1] / 21) : 23;
            $sMC->setCellValueByColumnAndRow($c + 2, 10, $wdVal);
        }

        // Data rows
        $dataRow = 12;
        $no = 1;
        $yearGrandTotal = 0;
        $yearGrandCap = 0;

        foreach ($allCustomers as $cust) {
            $sMC->setCellValueByColumnAndRow(0, $dataRow, $no++);
            $sMC->setCellValueByColumnAndRow(1, $dataRow, $cust);
            $custYearTotal = 0;

            for ($mi = 0; $mi < 12; $mi++) {
                $m = $mi + 1;
                $c = $monthCols[$mi];
                $hrs = isset($custMonthTotals[$m][$cust]) ? $custMonthTotals[$m][$cust] : 0;
                $cap = isset($stdHrs[$m]) ? $stdHrs[$m] * array_sum($machCountByTon) : 462;
                $pct = $cap > 0 ? round(($hrs / $cap) * 100, 4) : 0;
                $sMC->setCellValueByColumnAndRow($c, $dataRow, $hrs > 0 ? $hrs : '');
                $sMC->setCellValueByColumnAndRow($c + 1, $dataRow, $pct > 0 ? $pct : '');
                $custYearTotal += $hrs;
            }

            $avgMonth = round($custYearTotal / 12, 2);
            $yearGrandTotal += $custYearTotal;
            $sMC->setCellValueByColumnAndRow(36, $dataRow, $custYearTotal > 0 ? $custYearTotal : '');
            $sMC->setCellValueByColumnAndRow(38, $dataRow, $avgMonth > 0 ? $avgMonth : '');
            $dataRow++;
        }

        // Total Machine Use row
        $sMC->setCellValueByColumnAndRow(1, $dataRow, 'Ttl Machine Use (hr)');
        $mthGrandTotal = 0;
        $mthGrandCap = 0;
        for ($mi = 0; $mi < 12; $mi++) {
            $m = $mi + 1;
            $c = $monthCols[$mi];
            $mthTotal = 0;
            foreach ($allCustomers as $cust) {
                $mthTotal += isset($custMonthTotals[$m][$cust]) ? $custMonthTotals[$m][$cust] : 0;
            }
            $cap = isset($stdHrs[$m]) ? $stdHrs[$m] * array_sum($machCountByTon) : 462;
            $sMC->setCellValueByColumnAndRow($c, $dataRow, $mthTotal);
            $sMC->setCellValueByColumnAndRow($c + 1, $dataRow, 100);
            $mthGrandTotal += $mthTotal;
            $mthGrandCap += $cap;
        }
        $sMC->setCellValueByColumnAndRow(36, $dataRow, $mthGrandTotal);
        $sMC->setCellValueByColumnAndRow(38, $dataRow, round($mthGrandTotal / 12, 2));
        $sMC->setCellValueByColumnAndRow(39, $dataRow, 100);
        $dataRow++;

        // Machine Cap row
        $sMC->setCellValueByColumnAndRow(1, $dataRow, 'Machine Cap');
        for ($mi = 0; $mi < 12; $mi++) {
            $m = $mi + 1;
            $c = $monthCols[$mi];
            $cap = isset($stdHrs[$m]) ? $stdHrs[$m] * array_sum($machCountByTon) : 462;
            $sMC->setCellValueByColumnAndRow($c, $dataRow, $cap);
        }
        $yearCap = 0;
        for ($mi = 0; $mi < 12; $mi++) {
            $m = $mi + 1;
            $cap = isset($stdHrs[$m]) ? $stdHrs[$m] * array_sum($machCountByTon) : 462;
            $yearCap += $cap;
        }
        $sMC->setCellValueByColumnAndRow(36, $dataRow, $yearCap);
        $dataRow++;

        // Machine use % row
        $sMC->setCellValueByColumnAndRow(1, $dataRow, 'Machine use (%)');
        for ($mi = 0; $mi < 12; $mi++) {
            $m = $mi + 1;
            $c = $monthCols[$mi];
            $cap = isset($stdHrs[$m]) ? $stdHrs[$m] * array_sum($machCountByTon) : 462;
            $mthTotal = 0;
            foreach ($allCustomers as $cust) {
                $mthTotal += isset($custMonthTotals[$m][$cust]) ? $custMonthTotals[$m][$cust] : 0;
            }
            $pct = $cap > 0 ? round($mthTotal / $cap, 4) : 0;
            $sMC->setCellValueByColumnAndRow($c, $dataRow, $pct);
        }
        $sMC->setCellValueByColumnAndRow(36, $dataRow, $yearCap > 0 ? round($mthGrandTotal / $yearCap, 4) : 0);

        // Padma Soode letterhead at bottom
        $sMC->setCellValueByColumnAndRow(0, 64, 'P.T. Padma Soode Indonesia');
        $sMC->setCellValueByColumnAndRow(0, 65, 'Plastic Division');
        $sMC->setCellValueByColumnAndRow(0, 66, 'PPIC Dept.');
        $sheetIdx++;

        // ======================== SHEET 18: Total by Ton by Cust ========================
        $excel->createSheet(); $excel->setActiveSheetIndex($sheetIdx);
        $sTC = $excel->getActiveSheet(); $sTC->setTitle('Total by Ton by Cust');

        $sTC->setCellValue('A1', 'PT Ciptajaya Kreasindo Utama');
        $sTC->setCellValue('A2', 'Plastic Division');
        $sTC->setCellValue('A3', 'PPIC Dept.');
        $sTC->setCellValue('A6', 'Machine use by Ton By Customer');
        $sTC->setCellValue('B8', 'Total ' . $year);

        // Row 9: Tons header
        $sTC->setCellValueByColumnAndRow(1, 9, 'Tons');
        foreach ($tonnages as $ti => $ton) {
            $c = 2 + $ti * 3;
            $sTC->setCellValueByColumnAndRow($c, 9, $ton);
        }

        // Row 10: Av. Hr / working days
        $sTC->setCellValueByColumnAndRow(2, 10, 'Av. Hr');
        foreach ($tonnages as $ti => $ton) {
            $c = 2 + $ti * 3;
            $sTC->setCellValueByColumnAndRow($c, 10, 'Av. Hr');
            $sTC->setCellValueByColumnAndRow($c + 1, 10, $defaultWd);
        }

        // Row 11: Headers
        $sTC->setCellValueByColumnAndRow(0, 11, 'No.');
        $sTC->setCellValueByColumnAndRow(1, 11, 'Customer');
        foreach ($tonnages as $ti => $ton) {
            $c = 2 + $ti * 3;
            $sTC->setCellValueByColumnAndRow($c, 11, 'Mc Use (hours)');
            $sTC->setCellValueByColumnAndRow($c + 1, 11, '%');
        }
        $sTC->setCellValueByColumnAndRow(2 + count($tonnages) * 3, 11, 'Ttl');

        // Aggregate customer x tonnage across all months
        $custTonYearly = [];
        foreach ($custTonLookup as $mthData) {
            foreach ($mthData as $cust => $tonData) {
                if (!isset($custTonYearly[$cust])) $custTonYearly[$cust] = [];
                foreach ($tonData as $ton => $hrs) {
                    if (!isset($custTonYearly[$cust][$ton])) $custTonYearly[$cust][$ton] = 0;
                    $custTonYearly[$cust][$ton] += $hrs;
                }
            }
        }
        $tonCustList = array_keys($custTonYearly);
        sort($tonCustList);

        $dataRow = 12;
        $no = 1;
        foreach ($tonCustList as $cust) {
            $sTC->setCellValueByColumnAndRow(0, $dataRow, $no++);
            $sTC->setCellValueByColumnAndRow(1, $dataRow, $cust);
            $custTotal = 0;

            foreach ($tonnages as $ti => $ton) {
                $c = 2 + $ti * 3;
                $hrs = isset($custTonYearly[$cust][$ton]) ? $custTonYearly[$cust][$ton] : 0;
                // Average capacity across months
                $avgCap = 0;
                for ($mi = 1; $mi <= 12; $mi++) {
                    $avgCap += isset($capMap[$ton][$mi]) ? $capMap[$ton][$mi] : 0;
                }
                $avgCap = $avgCap / 12;
                $pct = $avgCap > 0 ? round(($hrs / $avgCap) * 100, 4) : 0;

                $sTC->setCellValueByColumnAndRow($c, $dataRow, $hrs > 0 ? $hrs : '');
                $sTC->setCellValueByColumnAndRow($c + 1, $dataRow, $pct > 0 ? $pct : '');
                $custTotal += $hrs;
            }
            $sTC->setCellValueByColumnAndRow(2 + count($tonnages) * 3, $dataRow, $custTotal > 0 ? $custTotal : '');
            $dataRow++;
        }

        $excel->setActiveSheetIndex(0);
        $path = $dir . DIRECTORY_SEPARATOR . '5-Machine_Use_Chart_' . $timestamp . '.xlsx';
        PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save($path);
        return ['path' => $path, 'name' => basename($path)];
    }

}
