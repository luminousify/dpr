<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_machine extends CI_Controller {

  public function __construct()
  {
  parent::__construct();
  $this->load->model('m_machine' , 'mm');
  $this->load->model('m_operator' , 'op');
  $this->load->helper(array('url','html','form'));
  $this->data = [
            'user_name'  => $_SESSION['user_name'],
            'bagian'     => $_SESSION['divisi'],
            ];
  $this->mm->cek_login();
  
  // Initialize machine filter session if not exists
  if (!isset($_SESSION['machine_filter'])) {
      $_SESSION['machine_filter'] = array(
          'dari' => date('Y-m-d'),
          'sampai' => date('Y-m-d'),
          'line' => 'All'
      );
  }
  }

  function index()
  {
    // Filter processing
    
    // Check for form submission - multiple methods for reliability (POST + GET)
    $form_submitted = isset($_POST['show']) || 
                     ($this->input->post('show') !== null) || 
                     ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tanggal_dari'])) ||
                     // Check for GET parameters as fallback
                     (isset($_GET['show']) && isset($_GET['tanggal_dari']));
    
    
    
    if($form_submitted)
      {
      // Get dates with multiple fallback methods (POST first, then GET)
      $dari   = $this->input->post('tanggal_dari');
      $sampai = $this->input->post('tanggal_sampai');
      $line = $this->input->post('line_new');
      
      // Try direct $_POST as fallback
      if (empty($dari)) $dari = isset($_POST['tanggal_dari']) ? $_POST['tanggal_dari'] : '';
      if (empty($sampai)) $sampai = isset($_POST['tanggal_sampai']) ? $_POST['tanggal_sampai'] : '';
      if (empty($line)) $line = isset($_POST['line_new']) ? $_POST['line_new'] : 'All';
      
      // If still empty, try GET parameters
      if (empty($dari)) $dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
      if (empty($sampai)) $sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';
      if (empty($line) || $line === 'All') $line = isset($_GET['line_new']) ? $_GET['line_new'] : $line;
      
      // Validate date format
      if (!empty($dari) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dari)) {
          log_message('error', 'Invalid dari date format: ' . $dari);
          $dari = date('Y-m-d');
      }
      if (!empty($sampai) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $sampai)) {
          log_message('error', 'Invalid sampai date format: ' . $sampai);
          $sampai = date('Y-m-d');
      }
      
      // Store filter data in session for persistence
      $_SESSION['machine_filter'] = array(
          'dari' => $dari,
          'sampai' => $sampai,
          'line' => $line
      );
      
      $tahuns     = substr($sampai,0,4);
      $bulan      = substr($sampai,5,2);
      $data = [
                'data'          => $this->data,
                'aktif'         => 'machine',
                'data_tabel1'   => $this->mm->tampil_machine_use('operasi_mesin',$dari , $sampai,1,$line),
                'data_tabel2'   => $this->mm->tampil_machine_use('operasi_mesin',$dari , $sampai,2,$line),
                'data_tabel3'   => $this->mm->tampil_machine_use('operasi_mesin',$dari , $sampai,3,$line),
                'kanit'         => $this->op->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                'data_tabelRekap'   => $this->mm->tampil_rekap($tahuns , $bulan),
                'data_tabelTotalMP'   => $this->mm->tampil_totalMP($tahuns , $bulan),
                'data_tabelTotalMPHadir1'   => $this->mm->tampil_mp_hadir($dari ,$sampai, 1),
                'data_tabelTotalMPHadir2'   => $this->mm->tampil_mp_hadir($dari , $sampai,2),
                'data_tabelTotalMPHadir3'   => $this->mm->tampil_mp_hadir($dari ,$sampai, 3),
                'data_tabelTotalMPTidakHadir1'   => $this->mm->tampil_mp_tidak_hadir($dari ,$sampai, 1),
                'data_tabelTotalMPTidakHadir2'   => $this->mm->tampil_mp_tidak_hadir($dari , $sampai,2),
                'data_tabelTotalMPTidakHadir3'   => $this->mm->tampil_mp_tidak_hadir($dari ,$sampai, 3),
                'data_tabelTotalSisaMPHadir1'   => $this->mm->tampil_sisa_mp_hadir($dari ,$sampai, 1),
                'data_tabelTotalSisaMPHadir2'   => $this->mm->tampil_sisa_mp_hadir($dari , $sampai,2),
                'data_tabelTotalSisaMPHadir3'   => $this->mm->tampil_sisa_mp_hadir($dari ,$sampai, 3),
                'dari'          => $dari,
                'sampai'        => $sampai,
                'line'        => $line,
                'lines'       => $this->mm->get_distinct_lines()
            ];
      
      $this->load->view('machine/1machine' , $data);
      }
      else
      {
        // Check for session data as final fallback
        if (isset($_SESSION['machine_filter'])) {
          $dari = isset($_SESSION['machine_filter']['dari']) ? $_SESSION['machine_filter']['dari'] : date('Y-m-d');
          $sampai = isset($_SESSION['machine_filter']['sampai']) ? $_SESSION['machine_filter']['sampai'] : date('Y-m-d');
          $line = isset($_SESSION['machine_filter']['line']) ? $_SESSION['machine_filter']['line'] : 'All';
        } else {
          $dari      = date('Y-m-d');
          $sampai    = date('Y-m-d');
          $line = 'All';
        }
        
        $tahuns     = substr($sampai,0,4);
        $bulan      = substr($sampai,5,2);
        $data = [
                'data'          => $this->data,
                'aktif'         => 'machine',
                'data_tabel1'   => $this->mm->tampil_machine_use('operasi_mesin',$dari , $sampai,1,$line),
                'data_tabel2'   => $this->mm->tampil_machine_use('operasi_mesin',$dari , $sampai,2,$line),
                'data_tabel3'   => $this->mm->tampil_machine_use('operasi_mesin',$dari , $sampai,3,$line),
                'data_tabelRekap'   => $this->mm->tampil_rekap($tahuns , $bulan),
                'data_tabelTotalMP'   => $this->mm->tampil_totalMP($tahuns , $bulan),
                'data_tabelTotalMPHadir1'   => $this->mm->tampil_mp_hadir($dari , $sampai, 1),
                'data_tabelTotalMPHadir2'   => $this->mm->tampil_mp_hadir($dari , $sampai, 2),
                'data_tabelTotalMPHadir3'   => $this->mm->tampil_mp_hadir($dari , $sampai, 3),
                'data_tabelTotalMPTidakHadir1'   => $this->mm->tampil_mp_tidak_hadir($dari ,$sampai, 1),
                'data_tabelTotalMPTidakHadir2'   => $this->mm->tampil_mp_tidak_hadir($dari , $sampai,2),
                'data_tabelTotalMPTidakHadir3'   => $this->mm->tampil_mp_tidak_hadir($dari ,$sampai, 3),
                'data_tabelTotalSisaMPHadir1'   => $this->mm->tampil_sisa_mp_hadir($dari ,$sampai, 1),
                'data_tabelTotalSisaMPHadir2'   => $this->mm->tampil_sisa_mp_hadir($dari , $sampai,2),
                'data_tabelTotalSisaMPHadir3'   => $this->mm->tampil_sisa_mp_hadir($dari ,$sampai, 3),
                'kanit'               => $this->op->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                'dari'          => $dari,
                'sampai'        => $sampai,
                'line'        => $line,
                'lines'       => $this->mm->get_distinct_lines()
              ];
        $this->load->view('machine/1machine' , $data);
      }
  }

  public function machine_use() 
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
        $bulans = substr($tahun,6,2);
        $this->benchmark->mark('code_start');
        $data = [
                  'data'                                      => $this->data,
                  'aktif'                                     => 'machine_use',
                   'total_machine_use_byton_bycustomer'        => $this->mm->total_machine_use_byton_bycustomer($tahuns), //0.14
                    'getTotalAvHour'                            => $this->mm->getTotalAvHour($tahuns), //0.73
                   'getTotalByMonthByCustomer'                 => $this->mm->getTotalByMonthByCustomer($tahuns), //0.1
                   'hitungTotalHourPerbulan'                   => $this->mm->hitungTotalHourPerbulan($tahuns), //0.8
                  'hitungTotalLTPerbulan'                     => $this->mm->hitungTotalLTPerbulan($tahuns),//0.12
                   'hitungTotalAVHourPerbulan'                 => $this->mm->hitungTotalAVHourPerbulan($tahuns),// 0.004
                  'machine_use_byton_bycustomer_bymonth'      => $this->mm->machine_use_by_customer_by_ton_by_month_default($bulan,$tahuns),// 0.12
                   'machine_use_byton_bycustomer_IEI'          => $this->mm->machine_use_bycustomer_bymonth_IEI($tahuns),// 0.1136
                  'total_ton_per_month'                       => $this->mm->total_ton_per_month($tahuns), //1.05
                  'avail_capacity_machine_total'              => $this->mm->avail_capacity_machine_total($tahuns), //0.002
                 'grafik_total_machine_use_bycustomer'       => $this->mm->grafik_total_machine_use_bycustomer($tahuns),// 0.1
                  'grafik_total_machine_use_byton'            => $this->mm->grafik_total_machine_use_byton($tahuns),//0.138
                   'grafik_machine_use_byton_bymonth'          => $this->mm->grafik_machine_use_byton_bymonth($bulan,$tahuns),//1.29 ->0.12
                  'grafik_machine_use_bycustomer_bymonth'     => $this->mm->grafik_machine_use_bycustomer_bymonth($bulan,$tahuns),//0.9
                  'avail_capacity_machine'                    => $this->mm->getAvailCapacity($bulan,$tahuns),/// 0.002
                  'total_machine_byton'                       => $this->mm->getTotalMachineByTon($bulan,$tahuns),//1.6 -> 0.4
                  'get_total_machine'                         => $this->mm->getTotalMachine(), //0.003
                  'tanggal'                                   => $tahun,
                  'tahun'                                     => $tahuns,
                  'bulan'                                     => $bulan

                  ];
                 
      $this->load->view('global/machine_use/1machine_use' , $data);
   }


  public function layar() 
  {
    date_default_timezone_set('Asia/Jakarta');
    if($this->input->post('show') == 'Show')
      {
        $tanggal = $this->input->post('tanggal');
        $shift = $this->input->post('shift');
      }
      else
      {
        $tanggal = date('Y-m-d');
        $test_shift = date('H:i:s');
        if ($test_shift >= '07.00' && $test_shift <= '15.00') {
          $shift = '1';
        } else if ($test_shift > '15.00' && $test_shift <= '23.00') {
          $shift = '2';
        } else {
          $shift = '3';
        }
      }
      $data = [
                  'data'                                      => $this->data,
                  'aktif'                                     => 'layar',
                  'layar_monitoring'                          => $this->mm->layar_monitoring($tanggal,$shift),
                  'total_running'                             => $this->mm->total_running($tanggal,$shift),
                  'total_idle'                                => $this->mm->total_idle($tanggal,$shift),
                  'tanggal'                                   => $tanggal,
                  'shift'                                     => $shift,
                  ];
    $this->load->view('machine/layar' , $data);
   }

  public function view_detail_layar($tanggal = null, $no_mesin = null)
    {
        // Handle missing URL segments safely (e.g. /view_detail_layar//1 passes only one arg)
        if (empty($tanggal) || empty($no_mesin)) {
            $uri = $this->uri->uri_string();
            $seg3 = $this->uri->segment(3);
            $seg4 = $this->uri->segment(4);
            log_message('error', 'view_detail_layar missing args. uri=' . $uri . ' seg3=' . $seg3 . ' seg4=' . $seg4);
            $this->session->set_flashdata('error', 'Tanggal / No. Mesin tidak lengkap. Silakan buka Info Detail dari halaman Screen Monitoring.');
            redirect('c_machine/layar');
            return;
        }

        // If date format is invalid, redirect to layar (prevents broken chart/data)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $tanggal) || !strtotime($tanggal)) {
            log_message('error', 'view_detail_layar invalid tanggal: ' . $tanggal . ' no_mesin=' . $no_mesin);
            $this->session->set_flashdata('error', 'Tanggal tidak valid. Silakan buka Info Detail dari halaman Screen Monitoring.');
            redirect('c_machine/layar');
            return;
        }

        $detail = $this->mm->view_detail_layar($tanggal, $no_mesin);
        // Redirect if no rows exist for this date/machine
        if (!$detail || (method_exists($detail, 'num_rows') && $detail->num_rows() === 0)) {
            log_message('debug', 'view_detail_layar no rows. tanggal=' . $tanggal . ' no_mesin=' . $no_mesin);
            $this->session->set_flashdata('error', 'Data tidak ditemukan untuk Tanggal ' . $tanggal . ' - Mesin ' . $no_mesin . '.');
            redirect('c_machine/layar');
            return;
        }

        // Redirect if rows exist but production detail is empty (LEFT JOIN produced NULL qty_ok/target_mc)
        // This avoids rendering a broken/empty chart page.
        $rows = $detail->result_array();
        $hasProductionValue = false;
        foreach ($rows as $r) {
            $qty_ok = isset($r['qty_ok']) ? $r['qty_ok'] : null;
            $target_mc = isset($r['target_mc']) ? $r['target_mc'] : null;
            if ($qty_ok !== null || $target_mc !== null) {
                $hasProductionValue = true;
                break;
            }
        }
        if (!$hasProductionValue) {
            log_message('debug', 'view_detail_layar empty production detail (qty_ok/target_mc NULL). tanggal=' . $tanggal . ' no_mesin=' . $no_mesin);
            $this->session->set_flashdata('error', 'Detail produksi tidak ditemukan untuk Tanggal ' . $tanggal . ' - Mesin ' . $no_mesin . '.');
            redirect('c_machine/layar');
            return;
        }

        $data = [
                'data'                  => $this->data,
                'aktif'                 => 'layar',
                'view_detail_layar'     => $detail,
              ];
        $this->load->view('machine/view_detail_layar' , $data);
    }

  public function getTonaseMachine(){
      $searchTerm = $this->input->post('searchTerm');
      $response = $this->mm->getTonaseMachine($searchTerm);
      echo json_encode($response);
  }

  public function delete_machine_use($id_machine_use)
    {
      $this->mm->delete_mu($id_machine_use);
      $this->session->set_flashdata('success', 'Data berhasil dihapus!');
      redirect('c_machine/index');
    }

  public function batch_delete_machine_use()
    {
      header('Content-Type: application/json');
      
      $ids = $this->input->post('ids');
      
      if (empty($ids) || !is_array($ids)) {
        echo json_encode([
          'success' => false,
          'message' => 'No valid IDs provided'
        ]);
        return;
      }
      
      // Filter valid IDs
      $valid_ids = array_filter($ids, function($id) {
        return is_numeric($id) && $id > 0;
      });
      
      if (empty($valid_ids)) {
        echo json_encode([
          'success' => false,
          'message' => 'No valid IDs provided'
        ]);
        return;
      }
      
      $result = $this->mm->batch_delete_mu($valid_ids);
      
      if ($result) {
        $deleted_count = count($valid_ids);
        echo json_encode([
          'success' => true,
          'message' => "Successfully deleted {$deleted_count} record(s)",
          'deleted_count' => $deleted_count
        ]);
      } else {
        echo json_encode([
          'success' => false,
          'message' => 'Failed to delete records'
        ]);
      }
    }

  public function delete_total_mp($id_mp)
  {
    $this->mm->delete_total_mp($id_mp);
    $this->session->set_flashdata('success', 'Data berhasil dihapus!');
    redirect('c_machine/index');
  }

  function get_autocomplete()
    {
        if (isset($_GET['term'])) {
            $result = $this->op->search_tonase($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'         => $row->id_no_mesin.' ( '.$row->no_mesin.' )',
              'kp_pr'         => $row->no_mesin.' ( '.$row->tonnnase.')',
              'tonnase'        => $row->tonnase,
            );
              echo json_encode($arr_result);
            }
        }
    }
    
    public function update_mp() {
        $id = $this->input->post('id');
        $data = array(
            'tanggal'       => $this->input->post('tanggal'),
            'shift'             => $this->input->post('shift'),
            'total'      => $this->input->post('total'),
            'total_tidak_hadir'     => $this->input->post('total_tidak_hadir'),
            'sisa_mp_hadir'     => $this->input->post('sisa_mp'),
            'keterangan'        => $this->input->post('keterangan'),
            'keterangan_sisa'        => $this->input->post('keterangan_sisa'),
        );                
        $this->mm->update($data,$id);
        // $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
        redirect('c_machine/index');    
    }


  public function addNew()
  {
    $divisi = $_SESSION['divisi'];
    $line   = $this->input->post('line');
    
    // If line is empty, check if it's in POST array directly
    if (empty($line) && isset($_POST['line'])) {
      $line = $_POST['line'];
    }
    
    // If still empty, set default or redirect with error
    if (empty($line)) {
      $this->session->set_flashdata('error', 'Line must be selected');
      redirect('c_machine/index');
      return;
    }
    
    // Get the data from model
    $data_tabel = $this->mm->tampil_mesin_aktif('t_no_mesin', $divisi, $line);
    
    // Debug: Check if query returned results
    // Uncomment to debug:
    // echo "Divisi: " . $divisi . "<br>";
    // echo "Line: " . $line . "<br>";
    // echo "Num rows: " . $data_tabel->num_rows() . "<br>";
    // var_dump($data_tabel->result_array());
    // die();
    
    $data = [
                'data'          => $this->data,
                'aktif'         => 'machine',
                'data_tabel'    => $data_tabel,
                'kanit'         => $this->op->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                'line'          => $line
              ];
    $this->load->view('machine/addNew' , $data);
  }
  
  public function add_family_mold()
  {
      $data = [
              'data'          => $this->data,
              'aktif'         => 'machine',
              'action'        => 'Add',
              'kanit'         => $this->op->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
              'lines'         => $this->mm->get_distinct_lines()
            ];
      $this->load->view('machine/add_family_mold' , $data);
  }

  function add()
  {
    $this->mm->add();
    
    // Check if we have filter data in session to preserve it
    $redirect_url = 'c_machine/index';
    if (isset($_SESSION['machine_filter'])) {
        $dari = isset($_SESSION['machine_filter']['dari']) ? $_SESSION['machine_filter']['dari'] : '';
        $sampai = isset($_SESSION['machine_filter']['sampai']) ? $_SESSION['machine_filter']['sampai'] : '';
        $line = isset($_SESSION['machine_filter']['line']) ? $_SESSION['machine_filter']['line'] : '';
        
        if ($dari && $sampai) {
            $redirect_url = 'c_machine/index?tanggal_dari=' . urlencode($dari) . 
                           '&tanggal_sampai=' . urlencode($sampai) . 
                           '&line_new=' . urlencode($line) . 
                           '&show=1';
            
        }
    }
    
    redirect($redirect_url);
  }

  function add_fm()
      {
        $this->mm->add_fm();
        
        // Check if we have filter data in session to preserve it
        $redirect_url = 'c_machine/index';
        if (isset($_SESSION['machine_filter'])) {
            $dari = isset($_SESSION['machine_filter']['dari']) ? $_SESSION['machine_filter']['dari'] : '';
            $sampai = isset($_SESSION['machine_filter']['sampai']) ? $_SESSION['machine_filter']['sampai'] : '';
            $line = isset($_SESSION['machine_filter']['line']) ? $_SESSION['machine_filter']['line'] : '';
            
            if ($dari && $sampai) {
                $redirect_url = 'c_machine/index?tanggal_dari=' . urlencode($dari) . 
                               '&tanggal_sampai=' . urlencode($sampai) . 
                               '&line_new=' . urlencode($line) . 
                               '&show=1';
                
            }
        }
        
        redirect($redirect_url); 
      } 

  function add_totalMP()
  {
        $this->mm->add_totalMP();
        redirect('c_machine/index');
  }


  function copy()
  {
    $tanggal = $this->input->post('tanggal');
    $group   = $this->input->post('group');
    $line    = $this->input->post('line');
    $divisi  = $_SESSION['divisi'];
    $data = [
                'data'          => $this->data,
                'aktif'         => 'machine',
                'data_tabel'    => $this->mm->copy($tanggal,$group,$line,$divisi),
                'kanit'         => $this->op->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                'tanggal'       => $tanggal,
                'group'         => $group,
                'line'          => $line
              ];
    $this->load->view('machine/copy' , $data);
  }


  function edit($id)
  {
    $data = [
                'data'          => $this->data,
                'aktif'         => 'machine',
                'data_tabel'    => $this->mm->edit($id),
                'id'            => $id,
              ];
    $this->load->view('machine/edit' , $data);
  }


  function edit_proses($id)
      {
        $this->mm->edit_proses($id);
        redirect('c_machine/index'); 
      } 


      function cekData()
      {
        $data = $this->mm->getCekData();
        echo json_encode($data);
        // $result = mysql_query('select p.id_machine_use from machine_use as p  where p.tanggal = "2021-11-23"');
        // if(mysql_num_rows($result)>0){ echo 1; }
        // else{ echo  0; }


      }

  function delete($table,$where,$id)
    {
          $this->db->where($where, $id);
          $this->db->delete($table);
          redirect('c_machine/index');
    }

    //ajax func to check data on prodplan
    function getdatabomMesinDPR() 
		{ 

		   $id_bom      = $this->input->post('id_bom');
		   $model       = $this->op->tampildataMesin($id_bom);
		   $query       = $this->db->query($model);
		   foreach ($model as $p){
		   $data .="<option value ='$p[no_mesin]'> $p[no_mesin] </option>"; }
		   echo $data;
		}
    
    public function getPlanforMachineToday($table , $where) 
    {
      $data 	   = [];
      $machno        = $this->input->post('machno');
      $data      = $this->op->tampilDataDetail($table,$where,$machno); 
      echo json_encode($data);
    }

  

 
  }

  

 
