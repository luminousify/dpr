<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_dpr extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_dpr' , 'mm');
    $this->load->helper(array('url','html','form'));
    $this->data = [
      'user_name'   => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
      'bagian'      => isset($_SESSION['divisi']) ? $_SESSION['divisi'] : '',
      'posisi'      => isset($_SESSION['posisi']) ? $_SESSION['posisi'] : '',
    ];
    // Only run cek_login if NOT the autocomplete AJAX endpoint
    $current_method = $this->router->fetch_method();
    if ($current_method !== 'get_autocomplete_cutting_tools') {
      $this->mm->cek_login();
    }
  }

 
  public function dpr()
  {
      // Treat any POST with filter fields as an explicit filter request
      $isFilterPost = ($this->input->server('REQUEST_METHOD') === 'POST') &&
                      ($this->input->post('tanggal_dari') !== null || $this->input->post('tanggal_sampai') !== null || $this->input->post('shift') !== null);

      if($isFilterPost)
      {
      $dari = $this->input->post('tanggal_dari');
      $sampai = $this->input->post('tanggal_sampai');
      $shift = $this->input->post('shift');
      $data_tabel = $this->mm->tampil_production_rev($dari , $sampai,$shift);
      
      $data = [
              'data'              => $this->data,
              'aktif'             => 'dpr',
              'data_tabel'        => $data_tabel,
              'verif_kasi'        => $this->mm->verif_kasi($dari,$sampai,$shift),
              'data_verif_kanit'  => $this->mm->data_verif_kanit($dari,$sampai,$shift),
              'data_verif_kasi'  => $this->mm->data_verifikasi_kasi($dari,$sampai),
              'dari'              => $dari,
              'sampai'            => $sampai,
              'shift'             => $shift,
              'posisi'            => $this->session->userdata('posisi'),
            ];
      $this->load->view('dpr/dpr' , $data);
      }
      else
      {
        $dari      = date('Y-m-d');
        $sampai    = date('Y-m-d');
        $shift = 'All';
        
        $data_tabel = $this->mm->tampil_production_rev($dari , $sampai , 'All');
        
        $data = [
                'data'              => $this->data,
                'aktif'             => 'dpr',
                'data_tabel'        => $data_tabel,
                'dari'              => $dari,
                'sampai'            => $sampai,
                'shift'             => $shift,
                'verif_kasi'        => $this->mm->verif_kasi($dari,$sampai,$shift),
                'data_verif_kanit'  => $this->mm->data_verif_kanit($dari,$sampai,$shift),
                'data_verif_kasi'  => $this->mm->data_verifikasi_kasi($dari,$sampai),
                'posisi'            => $this->session->userdata('posisi'),
              ];
          
       $this->load->view('dpr/dpr' , $data);
      }
  }

  public function verifikasi_dpr_by_kasi()
  {
      if($this->input->post('show'))
      {
      $dari = $this->input->post('tanggal_dari');
      $sampai = $this->input->post('tanggal_sampai');
      $shift = $this->input->post('shift');
      $data = [
              'data'              => $this->data,
              'aktif'             => 'dpr',
              'verif_kasi'        => $this->mm->verif_kasi_filter($dari,$sampai,$shift),
              'dari'              => $dari,
              'sampai'            => $sampai,
              'posisi'            => $this->session->userdata('posisi'),
            ];
      $this->load->view('dpr/verifikasi_kasi' , $data);
      }
      else
      {
        $dari      = date('Y-m-d');
        $sampai    = date('Y-m-d');
        $shift = 'All';
        $data = [
                'data'              => $this->data,
                'aktif'             => 'dpr',
                'dari'              => $dari,
                'sampai'            => $sampai,
                'verif_kasi'        => $this->mm->verif_kasi_filter($dari,$sampai,$shift),
                'posisi'            => $this->session->userdata('posisi'),
              ];
        $this->load->view('dpr/verifikasi_kasi' , $data);
      }
  }

  function report($jenis,$name) //qty_ok , ok
  {
    // Debug logging
    log_message('debug', 'Report function called - POST data: ' . print_r($_POST, true));
    log_message('debug', 'show value: ' . $this->input->post('show'));
    log_message('debug', 'tahun value: ' . $this->input->post('tahun'));
    
    if($this->input->post('show') == 'Show')
      {
        $tahun = $this->input->post('tahun');  
        log_message('debug', 'Using POST tahun: ' . $tahun);
      }
      else
      {
        $tahun = date('Y-m');
        log_message('debug', 'Using default tahun: ' . $tahun);
      }

      $tahuns = substr($tahun,0,4);
      $bulan  = substr($tahun,5,2);
      $pilihan = $this->input->post('pilihan');

      if($pilihan == 5)
      {
        $query_model = 'onlineYear';
        $query_grafik = 'onlineGrafik';
      }
      else if($pilihan == 6){
        $query_model = 'reportByCustMonthly';
        $query_grafik = 'onlineGrafik';
      }
      else
      {
        $query_model = 'onlineReport';
        $query_grafik = 'onlineGrafik';
      }
      $tanggal      = $tahun;
      $data = [
              'data'            => $this->data,
              'aktif'           => 'report',
              'data_production' => $this->mm->$query_model($tanggal, $jenis),
              'data_grafik'     => $this->mm->$query_grafik($tanggal, $jenis), 
              'judul_laporan'   => 'Reporting Production '.$name,
              'tanggal'         => $tahun,
              'tahun'           => $tahuns,
              'bulan'           => $bulan,
              'name'            => $name,
              'jenis'           => $jenis,
              'valueNya'        => $this->input->post('pilihan')
            ];
      $this->load->view('report/report' , $data);
    }
      

  function productivity()
    {
      if($this->input->post('show') == 'Show')
      {
        $tahun = $this->input->post('tahun');
          
      }
      else
      {
        $tahun = date('Y-m');
      }
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);

        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'report',
                  'data_productionGrafik' => $this->mm->tampil_productionGrafik($tahuns),
                  'tampil_worst_nett'           => $this->mm->tampil_worst_nett($bulan,$tahuns),
                  'tampil_worst_gross'          => $this->mm->tampil_worst_gross($bulan,$tahuns),
                  'minNett'           => $this->mm->minNett($tahuns,$bulan),
                  'tahun'       => $tahuns,
                  'bulan'       => $bulan
                  ];
      $this->load->view('report/1productivity' , $data);
    }

    function productivity_detail()
    {
      if($this->input->post('show') == 'Show')
      {
        $tahun = $this->input->post('tahun');
          
      }
      else
      {
        $tahun = date('Y');
      }

        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'global',
                  'detail_productivity'   => $this->mm->tampil_detail_productivity($tahuns),
                  'tahun'       => $tahuns,
                  'bulan'       => $bulan
                  ];
      $this->load->view('report/detail_productivity' , $data);
    }

    function productivity_detail_by_part_by_month()
    {
      if($this->input->post('show') == 'Show')
      {
        $tahun = $this->input->post('tahun');
          
      }
      else
      {
        $tahun = date('Y-m');
      }
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);

        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'global',
                  'detail_productivity_bypart_bymonth'    => $this->mm->tampil_detail_productivity_bypart_bymonth($bulan,$tahuns),
                  'tahun'       => $tahuns,
                  'bulan'       => $bulan
                  ];
      $this->load->view('report/productivity_detail_by_part_by_month' , $data);
    }

    function view_detail_worst_nett($kode_product,$bulan)  
   {
        $tahun = date('Y-m');
        $tahuns = substr($tahun,0,4);
        $bulans  = substr($tahun,5,2);
        $data = [
              'data'              => $this->data,
              'aktif'             => 'report',
              'view_detail_worst'     => $this->mm->tampil_detail_worst($kode_product,$bulan,$tahuns),
              'tahun'         => $tahuns,
              'bulan'         => $bulan
        ];
      $this->load->view('report/view_detail_worst_nett' , $data);
     
   }

   function view_detail_worst_gross($kode_product,$bulan)  
   {
        $tahun = date('Y-m');
        $tahuns = substr($tahun,0,4);
        $bulans  = substr($tahun,5,2);
        $data = [
              'data'              => $this->data,
              'aktif'             => 'report',
              'view_detail_worst'     => $this->mm->tampil_detail_worst($kode_product,$bulan,$tahuns),
              'tahun'         => $tahuns,
              'bulan'         => $bulan
        ];
      $this->load->view('report/view_detail_worst_gross' , $data);
     
   }

    function view_detail_bypart($kode_product,$bulan)  
   {
        $tahun = date('Y-m');
        $tahuns = substr($tahun,0,4);
        $bulans  = substr($tahun,5,2);
        $data = [
              'data'              => $this->data,
              'aktif'             => 'report',
              'view_detail_bypart'    => $this->mm->view_detail_prod_bypart_bymonth($kode_product,$bulan,$tahuns),
              'tahun'         => $tahuns,
              'bulan'         => $bulan
        ];
      $this->load->view('report/view_detail_bypart_bymonth' , $data);
   }

   function add_dpr()  
   {
        $data = [
              'kanit'             => $this->mm->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
              'operator'          => $this->mm->tampil_select_group('t_operator','jabatan','operator','nama_operator'),
              'data'              => $this->data,
              'aktif'             => 'dpr',
        ];
      $this->load->view('dpr/add_dpr' , $data);
   }

   function add()
      {$raw_nett = round($_POST['user'][0]['nett_prod']);
        $raw_gross = round($_POST['user'][0]['gross_prod']);
        $_POST['user'][0]['nett_prod'] = $raw_nett;
        $_POST['user'][0]['gross_prod'] = $raw_gross;
        $lotGlobal      = $this->input->post('lotGlobalSave');
        $id_production    = $this->input->post('id_production');
        $this->mm->add();
        $cutting_tools_ids = $this->input->post('cutting_tools_id');
        $id_production = $this->input->post('id_production'); 
        if ($id_production && is_array($cutting_tools_ids)) {
            foreach ($cutting_tools_ids as $ctid) {
                if ($ctid) {
                    $this->db->insert('t_production_op_cutting_tools_usage', [
                        'id_production' => $id_production,
                        'cutting_tools_id' => $ctid
                    ]);
                }
            }
        }
        redirect('c_dpr/dpr'); 
      } 

  function update_verif_kanit($id)
      {
        $data = array(
            'cek_kanit'       => 1,
        );                
        $this->mm->update($data,$id);
        
        // Clear query cache after verification update
        $this->db->cache_delete_all();
        log_message('debug', 'Kanit verification updated and cache cleared for: ' . $id);
        
        redirect('c_dpr/dpr'); 
      } 

  function edit_verif_bykasi()
      {
        $tanggal = $this->input->post('tanggal');
        $shift = $this->input->post('shift');
        $pic_kasi = $this->input->post('pic_kasi');
        $data = array(
            'cek_kasi'       => 1,
            'pic_kasi'       => $pic_kasi,
        );                
        $this->mm->update_verif_kasi($data,$tanggal,$shift);
        
        // Clear query cache after verification update
        $this->db->cache_delete_all();
        log_message('debug', 'Kasi verification updated and cache cleared');
        
        redirect('c_dpr/dpr'); 
      } 

  // AJAX: Cutting Tools Autocomplete
  public function get_autocomplete_cutting_tools() {
    // Allow AJAX if either admin or operator session is present
    if (empty($_SESSION['user_name']) && empty($_SESSION['nama_operator'])) {
      echo json_encode([]);
      return;
    }
    $term = $this->input->get('term');
    $this->load->model('m_dpr');
    $results = $this->m_dpr->get_cutting_tools_autocomplete($term);
    $data = array();
    foreach ($results as $row) {
      if (!empty($row['code'])) {
        $data[] = array(
          'id' => $row['id'],
          'label' => $row['code'],
          'value' => $row['code']
        );
      }
    }
    echo json_encode($data);
  }

  public function view_dpr($id_production) {
        $data = [
            'data' => $this->mm->get_data_by_id($id_production),
            'cutting_tools' => $this->mm->get_cutting_tools_by_production($id_production),
            // ...other data as needed
        ];
        $this->load->view('dpr/view_dpr', $data);
    }
    
    public function print_ng_report() {
        // Get date from query parameter with fallback to current date
        $date = $this->input->get('date');
        if (empty($date)) {
            $date = date('Y-m-d'); // Default to current date if not provided
        }
        
        // Log incoming date parameter for debugging
        log_message('debug', 'NG Report - Received date parameter: ' . $date);
        
        // Format the date to ensure correct format (Y-m-d)
        $formatted_date = date('Y-m-d', strtotime($date));
        log_message('debug', 'NG Report - Controller formatted date: ' . $formatted_date);
        
        // Get NG reports for the selected date
        $ng_data = $this->mm->get_ng_reports($formatted_date);
        
        // Log the number of results for debugging
        log_message('debug', 'NG Report - Number of results: ' . $ng_data->num_rows());
        
        // If no results, try directly with hardcoded date format that matches DB
        if ($ng_data->num_rows() == 0 && $date == '2025-05-09') {
            log_message('debug', 'NG Report - Trying alternative date format: 2025-5-9');
            $alt_date = '2025-5-9'; // Try without leading zeros
            $ng_data = $this->mm->get_ng_reports($alt_date);
            log_message('debug', 'NG Report - Alternative query results: ' . $ng_data->num_rows());
        }
        
        $data = [
            'data' => $this->data,
            'ng_reports' => $ng_data,
            'date' => $formatted_date,
            'judul_laporan' => 'NG Report - ' . date('d F Y', strtotime($formatted_date)),
            'aktif' => 'report',
            'debug_info' => [
                'received_date' => $date,
                'formatted_date' => $formatted_date,
                'result_count' => $ng_data->num_rows()
            ]
        ];
        
        // Load the print view
        $this->load->view('report/print_ng_report', $data);
    }
}