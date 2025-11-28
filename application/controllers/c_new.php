<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class c_new extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_new', 'mm');
    $this->load->model('m_machine', 'mc');
    $this->load->model('m_operator', 'op');
    $this->load->model('m_dpr');
    $this->load->helper(array('url', 'html', 'form'));
    $this->data = [
      'user_name'     => $_SESSION['user_name'],
      'bagian'        => $_SESSION['divisi'],
      'posisi'        => $_SESSION['posisi'],
      'nama_actor'    => $_SESSION['nama_actor'],
    ];
    $this->mm->cek_login();
  }


  public function getDataDetail($table, $where)
  {
    $id        = $this->input->post('15111601');
    $data      = $this->m_new->tampilDataDetail($table, $where, $id);
    echo json_encode($data);
  }

  public function home()
  {
    // Initialize date and shift variables
    $tanggal = '';
    $shift = 'All';
    
    // Priority 1: Check if form was submitted via POST (check for tanggal field)
    if ($this->input->post('tanggal')) {
      $tanggal = $this->input->post('tanggal');
      $shift = $this->input->post('shift');
      
      // Validate and store in session
      if ($this->validate_date($tanggal)) {
        $this->session->set_userdata('selected_tanggal', $tanggal);
        $this->session->set_userdata('selected_shift', $shift);
      } else {
        // Fallback to session or today if invalid date
        $session_tanggal = $this->session->userdata('selected_tanggal');
        $session_shift = $this->session->userdata('selected_shift');
        
        if ($session_tanggal && $this->validate_date($session_tanggal)) {
          $tanggal = $session_tanggal;
          $shift = $session_shift ?: 'All';
        } else {
          date_default_timezone_set("Asia/Jakarta");
          $tanggal = date('Y-m-d');
          $shift = 'All';
        }
      }
      
      // Get current year for annual charts
      $current_year = date('Y');
      $this->load->model('m_report', 'mr');
      
      $data = [
        'data'                  => $this->data,
        'aktif'                 => 'dashboard',
        'data_tabelRekap'       => $this->mm->tampil_rekap($tanggal), //0.003s
        'data_tabelRekapNew'    => $this->mm->tampil_rekap_new($tanggal), //0.003s
        'data_tabelHeader'      => $this->mm->tampil_header_monthly(), // Monthly data for KPI cards
        'data_ng_lt_kanit'      => $this->mm->tampil_ng_lt_bykanit_filter($tanggal),
        'tanggal'               => $tanggal,
        'shift'                 => $shift,
        // Annual charts data
        'tahun'                 => $current_year,
        'productivity_annual'    => $this->mr->productivity_q1($current_year),
        'ppm_grafik'            => $this->mr->tampil_grafikPPM_fixed($current_year),
        'ppm_total'             => $this->mr->total_prod_qty_and_ppm($current_year),
        'ppm_fcost_target'      => $this->mr->ppm_fcost_target($current_year)
      ];
      $this->load->view('home', $data);
    } else {
      // Priority 2: Check session for previously selected date
      $session_tanggal = $this->session->userdata('selected_tanggal');
      $session_shift = $this->session->userdata('selected_shift');
      
      if ($session_tanggal && $this->validate_date($session_tanggal)) {
        // Use session data if valid
        $tanggal = $session_tanggal;
        $shift = $session_shift ?: 'All';
      } else {
        // Priority 3: Fallback to today's date ONLY if no valid session exists
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date('Y-m-d');
        $shift = 'All';
        
        // Only store today's date in session if completely empty (first visit)
        if (!$session_tanggal) {
          $this->session->set_userdata('selected_tanggal', $tanggal);
          $this->session->set_userdata('selected_shift', $shift);
        }
      }
      
      // Get current year for annual charts
      $current_year = date('Y');
      $this->load->model('m_report', 'mr');
      
      $data = [
        'data'                  => $this->data,
        'aktif'                 => 'dashboard',
        'data_tabelRekap'       => $this->mm->tampil_rekap($tanggal),
        'data_tabelRekapNew'    => $this->mm->tampil_rekap_new($tanggal),
        'data_tabelHeader'      => $this->mm->tampil_header_monthly(), // Monthly data for KPI cards
        'data_ng_lt_kanit'      => $this->mm->tampil_ng_lt_bykanit_default(),
        'tanggal'               => $tanggal,
        'shift'                 => $shift,
        // Annual charts data
        'tahun'                 => $current_year,
        'productivity_annual'   => $this->mr->productivity_q1($current_year),
        'ppm_grafik'            => $this->mr->tampil_grafikPPM_fixed($current_year),
        'ppm_total'             => $this->mr->total_prod_qty_and_ppm($current_year),
        'ppm_fcost_target'      => $this->mr->ppm_fcost_target($current_year)
      ];
      $this->load->view('home', $data);
    }
  }

  /**
   * Validate date string format and range
   */
  private function validate_date($date) {
    // Check if date is in valid format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }
    
    // Parse date components
    $date_parts = explode('-', $date);
    $year = (int)$date_parts[0];
    $month = (int)$date_parts[1];
    $day = (int)$date_parts[2];
    
    // Check if date is valid using checkdate
    if (!checkdate($month, $day, $year)) {
        return false;
    }
    
    // Check if date is not in the future
    date_default_timezone_set("Asia/Jakarta");
    $today = new DateTime();
    $input_date = new DateTime($date);
    
    if ($input_date > $today) {
        return false;
    }
    
    // Check if date is within reasonable range (not too old)
    $min_date = new DateTime();
    $min_date->sub(new DateInterval('P2Y')); // 2 years ago
    
    if ($input_date < $min_date) {
        return false;
    }
    
    return true;
  }


  function Add($table, $redirect)
  {
    // Always log to see if function is called
    error_log('=== Add function called ===');
    error_log('Table: ' . $table);
    error_log('Redirect: ' . $redirect);
    error_log('POST simpan: ' . ($this->input->post('simpan') ? 'YES: ' . $this->input->post('simpan') : 'NO'));
    error_log('Raw POST: ' . print_r($_POST, true));
    
    // Debug: Log what we receive
    log_message('debug', 'Add function called. Table: ' . $table . ', Redirect: ' . $redirect);
    log_message('debug', 'POST simpan: ' . ($this->input->post('simpan') ? 'YES' : 'NO'));
    log_message('debug', 'POST user data: ' . print_r($this->input->post('user'), true));
    
    // Check if form was submitted - use POST method AND presence of user data as indicator
    // (submit button value may not always be included in POST)
    $is_post_method = $_SERVER['REQUEST_METHOD'] === 'POST';
    $user_data = $this->input->post('user');
    $has_raw_user = isset($_POST['user']) && !empty($_POST['user']);
    
    error_log('POST method: ' . ($is_post_method ? 'YES' : 'NO'));
    error_log('Has user data (CI): ' . (!empty($user_data) ? 'YES' : 'NO'));
    error_log('Has user data (raw): ' . ($has_raw_user ? 'YES' : 'NO'));
    
    // Form is submitted if it's a POST request AND we have user data
    $is_submitted = $is_post_method && (!empty($user_data) || $has_raw_user);
    error_log('Is submitted: ' . ($is_submitted ? 'YES' : 'NO'));
    
    if ($is_submitted) {
      // Validate that user data exists
      if (empty($user_data) && !$has_raw_user) {
        log_message('error', 'No user data in POST. Raw POST: ' . print_r($_POST, true));
        
        // Generate table-specific error message
        $error_message = 'Tidak ada data yang akan ditambahkan! Silakan tambahkan data terlebih dahulu dengan mengklik tombol + (plus), lalu isi minimal field ';
        if ($table === 't_operator') {
          $error_message .= 'Nama Operator.';
        } elseif ($table === 't_product') {
          $error_message .= 'Nama Product atau Kode Product.';
        } else {
          $error_message .= 'yang diperlukan.';
        }
        
        $this->session->set_flashdata('gagal', $error_message);
        redirect('c_new/' . $redirect);
        return;
      }
      
      try {
        $this->mm->add_action($table);
        $this->session->set_flashdata('tambah', 'Data berhasil di tambahkan!');
        redirect('c_new/' . $redirect);
      } catch (Exception $e) {
        // Log the error for debugging
        log_message('error', 'Add action failed: ' . $e->getMessage());
        log_message('error', 'POST data: ' . print_r($_POST, true));
        log_message('error', 'CI Input user: ' . print_r($this->input->post('user'), true));
        log_message('error', 'Exception trace: ' . $e->getTraceAsString());
        $this->session->set_flashdata('gagal', 'Gagal menambahkan data: ' . $e->getMessage());
        redirect('c_new/' . $redirect);
      }
    } else {
      // If accessed directly without POST, redirect back to form
      error_log('No POST simpan found - redirecting');
      log_message('debug', 'No POST simpan found, redirecting');
      log_message('debug', 'Request method: ' . $_SERVER['REQUEST_METHOD']);
      log_message('debug', 'All POST keys: ' . print_r(array_keys($_POST), true));
      $this->session->set_flashdata('gagal', 'Form tidak terkirim dengan benar. Pastikan Anda mengklik tombol "Add" setelah mengisi data.');
      redirect('c_new/' . $redirect);
    }
  }

  function Edit($table, $redirect)
  {
    $id     = $this->input->post('id');
    $where  = $this->input->post('where');
    $this->mm->edit_action($table, $where, $id);
    $this->session->set_flashdata('edit', 'Data berhasil di edit!');
    redirect('c_new/' . $redirect);
  }

  function Delete($redirect, $table, $where, $id)
  {
    $this->db->where($where, $id);
    $this->db->delete($table);
    $this->session->set_flashdata('hapus', 'Data berhasil di hapus!');
    redirect('c_new/' . $redirect);
  }

  // MASTER DATA 
  // public function master_product(){
  //       if($this->input->post('search_product') == 'Search')
  //       {
  //           $keyword     = $this->input->post('keyword');
  //           $data = [
  //             'data'          => $this->data,
  //             'aktif'         => 'master',
  //             'data_tabel'    => $this->mm->tampil_product_bySearch($keyword),
  //             'keyword' => $keyword,
  //           ];
  //           $this->load->view('master/master_product' , $data);
  //       }
  //       else
  //       {
  //           $data = [
  //             'data'          => $this->data,
  //             'aktif'         => 'master',
  //             'data_tabel'    => $this->mm->tampil_product_default(),
  //             'keyword'       => '',
  //           ];
  //           $this->load->view('master/master_product' , $data);
  //       } 
  //   }

  public function search_master_product()
  {
    // Check if form was submitted (check for tampil button or keyword POST)
    if ($this->input->post('tampil') || $this->input->post('keyword')) {
      $keyword    = $this->input->post('keyword');
      $divisi     = $this->data['bagian'];
      
      // Only search if keyword is not empty
      if (!empty($keyword)) {
        $data = [
          'data'          => $this->data,
          'aktif'         => 'master',
          'keyword'       => $keyword,
          'data_tabel'    => $this->mm->tampil_product_bySearch($divisi, $keyword),
        ];
      } else {
        // If keyword is empty, show default
        $data = [
          'data'          => $this->data,
          'aktif'         => 'master',
          'data_tabel'    => $this->mm->tampil_product_default(),
          'keyword'       => '',
        ];
      }
      $this->load->view('master/master_product', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->tampil_product_default(),
        'keyword'       => '',
      ];
      $this->load->view('master/master_product', $data);
    }
  }
  public function master_product()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil_product_default(),
      'keyword'       => '',
    ];
    $this->load->view('master/master_product', $data);
  }

  public function master_productAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_productAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_productAct', $data);
    }
  }

  public function edit_master_product($id_product)
  {
    $data = array(
      'data_product'                => $this->mm->getDataProduct($id_product),
      'data'                    => $this->data,
      'aktif'                   => 'dpr',
    );
    $this->load->view('master/edit_master_product', $data);
  }

  public function update_master_product()
  {
    $id = $this->input->post('id_product');
    $data['kode_product'] = $this->input->post('kode_product');
    $data['nama_product'] = $this->input->post('nama_product');
    $data['MomID'] = $this->input->post('MomID');
    $data['MomProduct'] = $this->input->post('MomProduct');
    $data['kode_proses'] = $this->input->post('kode_proses');
    $data['nama_proses'] = $this->input->post('nama_proses');
    $data['usage'] = $this->input->post('usage');
    $data['cavity'] = $this->input->post('cavity');
    $data['sp'] = $this->input->post('sp');
    $data['type'] = $this->input->post('type');
    $data['cyt_mc'] = $this->input->post('cyt_mc');
    $data['cyt_quo'] = $this->input->post('cyt_quo');
    $data['customer'] = $this->input->post('customer');
    $data['AccID'] = $this->input->post('AccID');
    $data['AccInv'] = $this->input->post('AccInv');
    $data['cost'] = $this->input->post('cost') ? $this->input->post('cost') : '0';
    $this->mm->edit_master_product($data, $id);
    $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
    redirect('c_new/master_product');
  }

  public function delete_master_product($id_product)
  {
    $this->mm->delete_master_product($id_product);
    $this->session->set_flashdata('success', 'Data berhasil dihapus!');
    redirect('c_new/master_product');
  }

  public function add_table_show()
  {
    $keyword = $this->input->post('keyword');
    $data['data'] = $keyword;
    $this->load->view('master/product_show_table', $data);
  }

  public function master_bom()
  {
    $data = [
      'data' => $this->data,
      'aktif' => 'master',
      'data_tabel'    => $this->mm->tampil('v_master_bom'),
    ];
    $this->load->view('master/master_bom', $data);
  }

  public function master_bomAct()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'action'        => 'Add'
    ];
    $this->load->view('master/master_bomAct', $data);
  }

  public function edit_master_bom($id_bom)
  {
    $data = array(
      'data_bom'                => $this->mm->getDataBOM($id_bom),
      'data'                    => $this->data,
      'aktif'                   => 'dpr',
    );
    $this->load->view('master/edit_master_bom', $data);
  }

  // public function update_master_bom() {
  //       $id = $this->input->post('id_bom');
  //       $data['cyt_mc_bom'] = $this->input->post('cyt_mc_bom');
  //       $this->mm->edit_master_bom($data,$id);
  //       $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
  //       redirect('c_new/master_bom');  
  //   }
  public function update_master_bom()
  {
    $id = $this->input->post('id_bom');
    $data['id_product'] = $this->input->post('id_product');
    $data['nama_bom'] = $this->input->post('nama_bom');
    $data['cyt_mc_bom'] = $this->input->post('cyt_mc_bom');
    $data_bom_detail['id_product'] = $this->input->post('id_product_release');
    $this->mm->update_master_bom($data, $id);
    $this->mm->update_master_bom_detail($data_bom_detail, $id);
    $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
    redirect('c_new/master_bom');
  }

  //delete master bom
  public function delete_master_bom($id_bom)
  {
    $this->mm->delete_master_bom($id_bom);
    $this->mm->delete_master_bom_detail($id_bom);
    $this->session->set_flashdata('success', 'Data berhasil dihapus!');
    redirect('c_new/master_bom');
  }


  public function get_kodeProductRelease()
  {
    $searchTerm = $this->input->post('searchTerm');
    $response = $this->mm->get_kodeProductRelease($searchTerm);
    echo json_encode($response);
  }

  public function getMax_bom()
  {
    $sql = "SELECT IFNULL(MAX(p.id_bom),0)as id_bom from t_bom as p";
    $query = $this->db->query($sql);
    foreach ($query->result() as $row) {
      $tambah = $row->nomor;
      $data  = $tambah + 1;
    }
    echo json_encode($data);
  }

  public function add_bom()
  {
    // Validate POST data exists
    if (!isset($_POST['user']) || empty($_POST['user'])) {
      $this->session->set_flashdata('gagal', 'Tidak ada data BOM yang akan ditambahkan!');
      redirect('c_new/master_bomAct');
      return;
    }

    if (!isset($_POST['user_detail']) || empty($_POST['user_detail'])) {
      $this->session->set_flashdata('gagal', 'Tidak ada data Release yang akan ditambahkan!');
      redirect('c_new/master_bomAct');
      return;
    }

    try {
      $this->mm->simpanData();
      $this->session->set_flashdata('success', 'Data Berhasil Di Simpan!');
      redirect('c_new/master_bom');
    } catch (Exception $e) {
      log_message('error', 'BOM save failed: ' . $e->getMessage());
      log_message('error', 'POST data: ' . print_r($_POST, true));
      $this->session->set_flashdata('gagal', 'Gagal menyimpan data BOM: ' . $e->getMessage());
      redirect('c_new/master_bomAct');
    }
  }

  public function master_mesin()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->get_master_mesin_data_with_names(),
      'data_tabel_nama'    => $this->mm->tampil('t_nama_mesin'),
    ];
    $this->load->view('master/master_mesin', $data);
  }

  public function master_mesinAct($identifikasi, $table = null, $where = null, $id = null)
  {
    $this->load->model('m_new', 'mm'); // Load m_new model (which contains get_machine_names)

    $machine_names = $this->mm->get_machine_names(); // Call the new model function

    if ($identifikasi == 'no') {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add',
        'identifikasi'  => $identifikasi,
        'machine_names' => $machine_names // Pass machine names to the view
      ];
      $this->load->view('master/master_mesinAct', $data);
    } else {
      // Extract identifikasi from table name (e.g., "t_no_mesin" -> "no")
      // Remove "t_" prefix and "_mesin" suffix
      $extracted_identifikasi = 'no'; // Default to 'no' for t_no_mesin
      if ($table && strpos($table, 't_') === 0) {
        $temp = str_replace('t_', '', $table); // Remove "t_" prefix
        $temp = str_replace('_mesin', '', $temp); // Remove "_mesin" suffix
        if (!empty($temp)) {
          $extracted_identifikasi = $temp;
        }
      }
      
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit',
        'identifikasi'  => $extracted_identifikasi, // Extract from table name for Edit mode
        'machine_names' => $machine_names // Pass machine names to the view
      ];
      $this->load->view('master/master_mesinAct', $data);
    }
  }

  public function master_namamesinAct($identifikasi, $table = null, $where = null, $id = null)
  {

    if ($identifikasi == 'nama') {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add',
        'identifikasi'  => $identifikasi
      ];
      $this->load->view('master/master_namamesinAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_namamesinAct', $data);
    }
  }

  public function master_op()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('t_operator'),
    ];
    $this->load->view('master/master_op', $data);
  }

  public function master_opAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_opAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_opAct', $data);
    }
  }

  public function master_defect()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('t_defectdanlosstime'),
    ];
    $this->load->view('master/master_defect', $data);
  }

  public function master_defectAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add',
        'categories'    => $this->mm->get_all_kategori_defect() // Using existing model method
      ];
      $this->load->view('master/master_defectAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit',
        'categories'    => $this->mm->get_all_kategori_defect() // Using existing model method
      ];
      $this->load->view('master/master_defectAct', $data);
    }
  }

  public function master_user()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('user'),
    ];
    $this->load->view('master/master_user', $data);
  }

  public function master_userAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_userAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_userAct', $data);
    }
  }

  //Master Work Days
  public function master_work_days()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('year_day'),
    ];
    // use PHP's `usort` function to sort the `$data['data_tabel']` array.
    usort($data['data_tabel'], function ($a, $b) {
      // Assuming 'tahun_bulan' is a property of the objects in your `$data_tabel` array.
      // Adjust the property name if it's different (e.g., an array key).

      // Compare $b->tahun_bulan to $a->tahun_bulan for descending order.
      return strcmp($b->tahun_bulan, $a->tahun_bulan);
    });
    $this->load->view('master/master_work_days', $data);
  }

  public function master_work_daysAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_work_daysAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_work_daysAct', $data);
    }
  }

  //Master Target Produksi
  public function master_target_produksi()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('target_produksi'),
    ];
    $this->load->view('master/master_target_produksi', $data);
  }

  public function master_target_produksiAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_target_produksiAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_target_produksiAct', $data);
    }
  }

  //Master Target PPM
  public function master_target_ppm()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('ppm_target'),
    ];
    $this->load->view('master/master_target_ppm', $data);
  }

  public function master_target_ppmAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_target_ppmAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_target_ppmAct', $data);
    }
  }

  //Master Target F - Cost
  public function master_f_cost()
  {
    $data = [
      'data'          => $this->data,
      'aktif'         => 'master',
      'data_tabel'    => $this->mm->tampil('f_cost_target'),
    ];
    $this->load->view('master/master_f_cost_target', $data);
  }

  public function master_f_costAct($table = null, $where = null, $id = null)
  {
    if ($id == null) {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'action'        => 'Add'
      ];
      $this->load->view('master/master_f_costAct', $data);
    } else {
      $data = [
        'data'          => $this->data,
        'aktif'         => 'master',
        'data_tabel'    => $this->mm->edit_tampil($table, $where, $id),
        'action'        => 'Edit'
      ];
      $this->load->view('master/master_f_costAct', $data);
    }
  }


  function view_production_reporting_op($id_production)
  {
    // Load m_dpr model for cutting tools usage
    $this->load->model('m_dpr');
    $data = array(
      'data_production'     => $this->op->tampil_production($id_production),
      'data_productionRelease'  => $this->op->tampil_productionRelease($id_production),
      'data_productionNG'     => $this->op->tampil_productionDL($id_production, 'NG'),
      'data_productionLT'     => $this->op->tampil_productionDL($id_production, 'LT'),
      'kanit'               => $this->op->tampil_select_group('t_operator', 'jabatan', 'kanit', 'nama_operator'),
      'data'                    => $this->data,
      'aktif'                   => 'dpr',
      'cutting_tools'           => $this->m_dpr->get_cutting_tools_by_production($id_production),
    );
    $this->load->view('dpr/view_dpr', $data);
  }

  function edit_production_reporting_op($id_production)
  {
    // Store current filter values from session or POST data
    $tanggal_dari = $this->input->post('tanggal_dari') ?: $this->session->userdata('dpr_tanggal_dari');
    $tanggal_sampai = $this->input->post('tanggal_sampai') ?: $this->session->userdata('dpr_tanggal_sampai');
    $shift = $this->input->post('shift') ?: $this->session->userdata('dpr_shift');
    
    // Store filter values in session to preserve after edit
    if ($tanggal_dari) {
      $this->session->set_userdata('dpr_tanggal_dari', $tanggal_dari);
    }
    if ($tanggal_sampai) {
      $this->session->set_userdata('dpr_tanggal_sampai', $tanggal_sampai);
    }
    if ($shift) {
      $this->session->set_userdata('dpr_shift', $shift);
    }
    
    // Load models
    $this->load->model('m_dpr');
    // Get all available cutting tools
    $cutting_tools = $this->m_dpr->get_cutting_tools_autocomplete(null); // returns id, code
    // Get currently used cutting tools for this production
    $used_cutting_tools = $this->m_dpr->get_cutting_tools_by_production($id_production); // returns id, code
    $data = array(
      'data_production'         => $this->op->tampil_production($id_production),
      'data_productionRelease'  => $this->op->tampil_productionRelease($id_production),
      'data_productionNG'       => $this->op->tampil_productionDL($id_production, 'NG'),
      'data_productionLT'       => $this->op->tampil_productionDL($id_production, 'LT'),
      'kanit'                   => $this->op->tampil_select_group('user', 'posisi', 'Kanit', 'nama_actor'),
      'id_operator'             => $this->session->userdata('id_operator'),
      'nik'                     => $this->session->userdata('nik'),
      'password_op'             => $this->session->userdata('password_op'),
      'nama'                    => $this->session->userdata('nama_operator'),
      'jabatan'                 => $this->session->userdata('jabatan'),
      'divisi'                  => $this->session->userdata('divisi'),
      'data'                    => $this->data,
      'aktif'                   => 'dpr',
      'cutting_tools'           => $cutting_tools,
      'used_cutting_tools'      => $used_cutting_tools,
    );
    $this->load->view('dpr/edit_dpr', $data);
  }

  function del_production_reporting_op($id_production)
  {
    // Add logging to diagnose the issue
    log_message('debug', 'Attempting to delete production: ' . $id_production);
    
    // Check if cutting tools usage exists
    $cutting_tools_check = $this->db->get_where('t_production_op_cutting_tools_usage', 
      array('id_production' => $id_production))->num_rows();
    log_message('debug', 'Cutting tools usage records found: ' . $cutting_tools_check);
    
    try {
      // Delete in correct order (child records first)
      
      // 1. Delete cutting tools usage (if exists)
      if ($cutting_tools_check > 0) {
        $this->db->where('id_production', $id_production);
        $this->db->delete('t_production_op_cutting_tools_usage');
        log_message('debug', 'Deleted cutting tools usage');
      }
      
      // 2. Delete production details (t_production_op_release)
      $this->mm->delete_production_detail($id_production);
      log_message('debug', 'Deleted production details');
      
      // 3. Delete production op release (t_production_op_dl)
      $this->mm->delete_production_op_release($id_production);
      log_message('debug', 'Deleted production op dl');
      
      // 4. Delete main production record (t_production_op)
      $this->mm->delete_production_op($id_production);
      log_message('debug', 'Deleted production op - SUCCESS');
      
      // CRITICAL FIX: Clear query cache after delete!
      $this->db->cache_delete_all();
      log_message('debug', 'Cleared query cache');
      
      $this->session->set_flashdata('success', 'Data berhasil dihapus!');
    } catch (Exception $e) {
      log_message('error', 'Delete failed: ' . $e->getMessage());
      $this->session->set_flashdata('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
    
    // Get stored filter values from session to preserve after delete
    $tanggal_dari = $this->session->userdata('dpr_tanggal_dari');
    $tanggal_sampai = $this->session->userdata('dpr_tanggal_sampai');
    $shift = $this->session->userdata('dpr_shift');
    
    // Build redirect URL with preserved filter parameters
    $redirect_url = 'c_dpr/dpr';
    $params = [];
    
    if ($tanggal_dari) {
      $params[] = 'tanggal_dari=' . urlencode($tanggal_dari);
    }
    if ($tanggal_sampai) {
      $params[] = 'tanggal_sampai=' . urlencode($tanggal_sampai);
    }
    if ($shift) {
      $params[] = 'shift=' . urlencode($shift);
    }
    
    if (!empty($params)) {
      $redirect_url .= '?' . implode('&', $params);
    }
    
    redirect($redirect_url);
  }

  function delete_detail_dl($id_dl, $id_production, $type)
  {
    $this->mm->delete_detail_dl($id_dl);
    $query =  $this->db->query("SELECT SUM(qty) AS jumlah FROM t_production_op_dl WHERE id_production = '$id_production' AND TYPE = '$type'");
    foreach ($query->result() as $row) {
      $jumlah = $row->jumlah;
      //$data  = $tambah+1;

    }
    $hasil_akhir = $jumlah;
    $typeNya = strtolower($type);
    $data['qty_' . $typeNya] = $hasil_akhir;
    $this->mm->update_dpr_online($data, $id_production);
    $this->session->set_flashdata('success', 'Data berhasil dihapus!');
    redirect('index.php/c_new/edit_production_reporting_op/' . $id_production);
    return false;
  }

  public function edit_dpr_online()
  {
    $id_production    = $this->input->post('id_production');
    $raw_nett = round($_POST['user'][0]['nett_prod']);
    $raw_gross = round($_POST['user'][0]['gross_prod']);
    $_POST['user'][0]['nett_prod'] = $raw_nett;
    $_POST['user'][0]['gross_prod'] = $raw_gross;

    // Cutting tools sync logic
    $cutting_tools = $this->input->post('cutting_tools_ids');
    $this->m_dpr->sync_cutting_tools_by_production($id_production, $cutting_tools);

    $this->mm->update_dpr_online_all();
    
    // CRITICAL FIX: Clear query cache after update so changes show immediately!
    $this->db->cache_delete_all();
    log_message('debug', 'DPR updated and cache cleared for: ' . $id_production);
    
    // Get stored filter values from session to preserve after edit
    $tanggal_dari = $this->session->userdata('dpr_tanggal_dari');
    $tanggal_sampai = $this->session->userdata('dpr_tanggal_sampai');
    $shift = $this->session->userdata('dpr_shift');
    
    // Build redirect URL with preserved filter parameters
    $redirect_url = base_url('c_dpr/dpr');
    $params = [];
    
    if ($tanggal_dari) {
      $params[] = 'tanggal_dari=' . urlencode($tanggal_dari);
    }
    if ($tanggal_sampai) {
      $params[] = 'tanggal_sampai=' . urlencode($tanggal_sampai);
    }
    if ($shift) {
      $params[] = 'shift=' . urlencode($shift);
    }
    
    if (!empty($params)) {
      $redirect_url .= '?' . implode('&', $params);
    }
    
    redirect($redirect_url);
  }

  /*public function edit_dpr_online() {
        $id = $this->input->post('id_prod');
        $data['tanggal'] = $this->input->post('tanggal');
        $data['tanggal_input'] = $this->input->post('tanggal');
        $data['nwt_mp'] = $this->input->post('nwt_mp');
        $data['id_bom'] = $this->input->post('id_bom');
        $data['production_time'] = $this->input->post('production_time');        
        $data['cavity'] = $this->input->post('cavity');
        $data['tooling'] = $this->input->post('tooling');
        $data['ct_mc_aktual'] = $this->input->post('ct_mc_aktual');
        $data['target_mc'] = $this->input->post('target_mc');
        $data['nett_prod'] = $this->input->post('nett_prod');        
        $data['gross_prod'] = $this->input->post('gross_prod');
        $data['qty_ok'] = $this->input->post('qty_ok');
        $data['keterangan'] = $this->input->post('keterangan');

        $data['runner'] = $this->input->post('runner');
        $data['loss_purge'] = $this->input->post('loss_purge');
        $data['lot_material_no'] = $this->input->post('lot_material_no');
  
        $query = "SELECT id_bom FROM t_production_op WHERE id_production = $id";
        $result = $this->db->query($query);
        if ($id_bom == 0) {
          $this->mm->update_dpr_online($data,$id);
          $this->mm->simpanBOMKosong();
          $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
          redirect('c_dpr/dpr');  
        } else {
          $this->mm->update_dpr_online($data,$id);
          $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
          redirect('c_dpr/dpr');  
        }
    }
    */


  function add_table_ng()
  {
    /*foreach($_POST['sl'] as $user)
        {
            $this->db->insert('t_production_op_detail', $user);
        }
        */
    $id_production    = $this->input->post('id_production');
    $data = [
      'id_production' => $this->input->post('id_production'),
      'nama' => $this->input->post('nama'),
      'kategori' => $this->input->post('kategori'),
      'type' => $this->input->post('type'),
      'satuan' => $this->input->post('satuan'),
      'qty' => $this->input->post('qty'),
    ];
    $this->mm->insert_data_ng($data);
  }

  function add_table_lt()
  {
    /*foreach($_POST['sl'] as $user)
        {
            $this->db->insert('t_production_op_detail', $user);
        }
        */
    $data = [
      'id_production' => $this->input->post('id_production'),
      'nama' => $this->input->post('nama'),
      'kategori' => $this->input->post('kategori'),
      'type' => $this->input->post('type'),
      'satuan' => $this->input->post('satuan'),
      'qty' => $this->input->post('qty'),
    ];
    $this->mm->insert_data_lt($data);
  }

  public function loadNG()
  {
    $id_production  = $this->uri->segment(3);
    $data['data_productionNG']   = $this->op->tampil_productionDL($id_production, 'NG');
    $this->load->view('dpr/loadNG', $data);
  }

  public function loadLT()
  {
    $id_production  = $this->uri->segment(3);
    $data['data_productionLT']   = $this->op->tampil_productionDL($id_production, 'LT');
    $this->load->view('dpr/loadLT', $data);
  }

  public function add_kategori_defect()
  {
    $nama_kategori = $this->input->post('nama_kategori');
    $result = $this->mm->add_kategori_defect($nama_kategori);

    header('Content-Type: application/json');
    echo json_encode([
      'success' => $result,
      'message' => $result ? 'Category added successfully' : 'Failed to add category'
    ]);
  }

  public function delete_kategori_defect($id)
  {
    $result = $this->mm->delete_kategori_defect($id);

    header('Content-Type: application/json');
    echo json_encode([
      'success' => $result,
      'message' => $result ? 'Category deleted successfully' : 'Failed to delete category'
    ]);
  }

  public function get_kategori_defect()
  {
    $categories = $this->mm->get_all_kategori_defect(); // Using existing model method
    header('Content-Type: application/json');
    echo json_encode(['categories' => $categories]);
  }

  // --- CUTTING TOOL MASTER DATA ---
  public function cutting_tools() {
    $this->load->model('m_new');
    $data['data'] = $this->data;
    $data['aktif'] = 'cutting_tools';
    $data['cutting_tools'] = $this->m_new->get_cutting_tools();
    $this->load->view('cutting_tools/index', $data);
  }

  public function add_cutting_tool() {
    $code = $this->input->post('code');
    $code_group = $this->input->post('code_group');
    $this->load->model('m_new');
    $this->m_new->insert_cutting_tool($code, $code_group);
    $this->session->set_flashdata('tambah', 'Cutting tool added!');
    redirect('c_new/cutting_tools');
  }

  public function delete_cutting_tool($id) {
    $this->load->model('m_new');
    $this->m_new->delete_cutting_tool($id);
    $this->session->set_flashdata('hapus', 'Cutting tool deleted!');
    redirect('c_new/cutting_tools');
  }

  public function edit_cutting_tool($id) {
    $this->load->model('m_new');
    $tool = $this->m_new->get_cutting_tools();
    $tool_data = null;
    foreach ($tool as $row) {
      if ($row['id'] == $id) {
        $tool_data = $row;
        break;
      }
    }
    if ($tool_data) {
      echo json_encode($tool_data);
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not found']);
    }
  }

  public function update_cutting_tool() {
    $id = $this->input->post('id');
    $code = $this->input->post('code');
    $code_group = $this->input->post('code_group');
    $this->load->model('m_new');
    $this->m_new->update_cutting_tool($id, $code, $code_group);
    $this->session->set_flashdata('edit', 'Cutting tool updated!');
    redirect('c_new/cutting_tools');
  }

  public function update_discontinue_status()
  {
    $id_product = $this->input->post('id_product');
    $discontinue_status = $this->input->post('discontinue');
    
    header('Content-Type: application/json');

    if ($id_product !== null && in_array($discontinue_status, [0, 1, '0', '1'], true)) {
        $result = $this->mm->update_product_discontinue_status($id_product, $discontinue_status);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
  }
}
