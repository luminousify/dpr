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
		
		if ($this->input->post('show') == 'Show') {
			$tahun = $this->input->post('year');
		} else {
			$tahun = date('Y');
		}

		$data = [
			'data'          => $this->data,
			'aktif'         => 'global',
			'productivity_q1'   => $this->mr->productivity_q1($tahun),
			'productivity'  => $this->mr->get_annual_productivity($tahun), 
			'year'         => $tahun,
			'tahun'        => $tahun  
		];


		if ($this->input->post('show') == 'showUntilLastMonthOnly') {
			$data = [
			'data'          	=> $this->data,
			'aktif'         	=> 'global',
			'productivity_q1'   => $this->mr->productivity_q($tahun,date('n')-1),
			'productivity_q2'   => $this->mr->productivity_q2($tahun),
			'productivity_q3'   => $this->mr->productivity_q3($tahun),
			'tahun'				=> $tahun,
		];
			;}else{
		
		}

		
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

}
