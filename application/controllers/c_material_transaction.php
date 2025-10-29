<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_material_transaction extends CI_Controller {

  public function __construct()
  {
  parent::__construct();
  //session_start();

  $this->load->model('m_material_transaction' , 'mm');
  $this->load->model('m_machine' , 'mc');
  $this->load->helper(array('url','html','form'));
  $this->data = [
            'user_name'   => $_SESSION['user_name'],
            'bagian'      => $_SESSION['divisi'],
            'nama_actor'  => $_SESSION['nama_actor'],
            'posisi'     => $_SESSION['posisi'],
            ];
  $this->mm->cek_login();
  }

  public function material_transaction()
  {
    if($this->input->post('show') == 'Show')
      {
        $tanggal_dari = $this->input->post('tanggal_dari');
        $tanggal_sampai = $this->input->post('tanggal_sampai');      
      }
      else
      {
        $tanggal_dari = date('Y-m-d');
        $tanggal_sampai = date('Y-m-d');   
      }
        $data = [
              'data'            => $this->data,
              'aktif'           => 'material_transaction',
              'material_transaction' => $this->mm->tampil('material_transaction', $tanggal_dari, $tanggal_sampai),
              'mesin'             => $this->mm->tampil_no_mesin(),
              'dari'            => $tanggal_dari,
              'sampai'          => $tanggal_sampai,
            ];
        $this->load->view('material_transaction/material_transaction' , $data);
  }

  public function material_transactionAct($table = null , $where = null , $id = null)
  {
      if($id == null)
      {
      //$posisi = $this->session->userdata('user_name');
      $data = [
              'data'          => $this->data,
              'aktif'         => 'material_transaction',
              //'pic'           => $this->mm->tampil_select_group($posisi),
              'action'        => 'Add'
            ];
      $this->load->view('material_transaction/material_transactionAct' , $data);
      }
      else
      {
        // $posisi = $this->session->userdata('user_name');
        $data = [
                'data'          => $this->data,
                'aktif'         => 'material_transaction',
                'data_tabel'    => $this->mm->edit_tampil($table , $where , $id),
                //'pic'           => $this->mm->tampil_select_group($posisi),
                'action'        => 'Edit'
              ];
        $this->load->view('material_transaction/material_transactionAct' , $data);
      }
  }

  function report_material_transaction_by_machine()
    {
      if($this->input->post('show') == 'Show')
      {
        $no_mesin  = $this->input->post('mesin');
        $tahun = $this->input->post('tahun');
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'material_transaction',
                  'data_report'     => $this->mm->tampil_report_supply_material_byfilter($no_mesin, $bulan, $tahuns), 
                  'no_mesin'        => $this->mm->tampil_no_mesin(),
                  'mesin'           => $no_mesin,
                  'tahun'           => $tahuns,
                  'bulan'           => $bulan  
                  ];
        $this->load->view('global/supply_material/report_material_transaction_bymachine' , $data);
      }
      else
      {
        $no_mesin  = 1;
        $tahun = date('Y-m');
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'material_transaction',
                  'data_report'     => $this->mm->tampil_report_supply_material_byfilter($no_mesin, $bulan, $tahuns), 
                  'no_mesin'        => $this->mm->tampil_no_mesin(),
                  'mesin'           => $no_mesin,
                  'tahun'           => $tahuns,
                  'bulan'           => $bulan
                  ];
      $this->load->view('global/supply_material/report_material_transaction_bymachine' , $data);
      }
       
    }
    function material_transaction_coba()
  {
    if($this->input->post('show'))
      {
      $tanggal   = $this->input->post('tanggal');
      $data = [
                'data'                  => $this->data,
                'aktif'                 => 'material_transaction',
                'material_transaction'  => $this->mm->tampil('material_transaction', $tanggal),
                'tanggal'               => $tanggal,
                'pic'                   => $this->mm->tampil_pic('mixer'),
            ];
      $this->load->view('material_transaction/daily' , $data);
      }
      else
      {
        $tanggal      = date('Y-m-d');
        $data = [
                'data'          => $this->data,
                'aktif'         => 'material_transaction',
                'material_transaction' => $this->mm->tampil('material_transaction', $tanggal),
                'tanggal'          => $tanggal,
                'pic'                   => $this->mm->tampil_pic('mixer'),
              ];
        $this->load->view('material_transaction/daily' , $data);
      }
  }

  public function addNew()
  {
    $tanggal = $this->input->post('tanggal');
    $pic = $this->input->post('pic');
    $data = [
                'data'          => $this->data,
                'aktif'         => 'material_transaction',
                'data_tabel'    => $this->mm->tampil_mesin_all('t_no_mesin'),
                'tanggal'       => $tanggal,
                'pic'           => $pic,
                //'kanit'         => $this->op->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                //'line'          => $line
              ];
    $this->load->view('material_transaction/addNew' , $data);
  }


    function view_detail_supply_material($kode_product)  
   {
        // $tahun = date('Y-m');
        // $tahuns = substr($tahun,0,4);
        // $bulans  = substr($tahun,5,2);
        $data = [
              'data'                  => $this->data,
              'aktif'                 => 'material_transaction',
              'view_detail_worst'     => $this->mm->tampil_detail_supply_material($kode_product)
              // 'tahun'         => $tahuns,
              // 'bulan'         => $bulan
        ];
      $this->load->view('material_transaction/view_detail_supply_material' , $data);
     
   }

  
    function get_autocomplete()
    {
        if (isset($_GET['term'])) {
            $result = $this->mm->search_product($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'                 => $row->kode_product,
              'kode_product'          =>$row->kode_product,
              'nama_product'          =>$row->nama_product,
              'nama_product_release'  =>$row->nama_product_release,
              'cyt_mc'                =>$row->cyt_mc,
              'mtrl_use_kg'           =>$row->mtrl_use_kg,
              'keb_mtrl'              =>$row->keb_mtrl,
            );
              echo json_encode($arr_result);
            }
        }
    }

    function get_autocompleteEdit()
    {
        if (isset($_GET['term'])) {
            $result = $this->mm->search_productEdit($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'                 => $row->kode_product,
              'kode_product'          =>$row->kode_product,
              'nama_product'          =>$row->nama_product,
              'nama_product_release'  =>$row->nama_product_release,
              'cyt_mc'                =>$row->cyt_mc,
              'mtrl_use_kg'           =>$row->mtrl_use_kg,
              'keb_mtrl'              =>$row->keb_mtrl,
            );
              echo json_encode($arr_result);
            }
        }
    }

    function get_autocompleteMesin()
    {
        if (isset($_GET['term'])) {
            $result = $this->mm->search_mesin($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'            => $row->no_mesin,
              'no_mesin'         =>$row->no_mesin,
              'tonnase'          =>$row->tonnase,
            );
              echo json_encode($arr_result);
            }
        }
    }

    function get_autocompletePIC()
    {
        $posisi = $this->session->userdata('user_name');
        if (isset($_GET['term'])) {
            $result = $this->mm->search_pic($posisi); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'            => $row->nama_actor,
              'nama_actor '      =>$row->nama_actor,
            );
              echo json_encode($arr_result);
            }
        }
    }

    function get_autocompletePICEdit()
    {
      $posisi = $this->session->userdata('user_name');
        if (isset($_GET['term'])) {
            $result = $this->mm->search_pic($posisi); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'            => $row->nama_actor,
              'nama_actor '      =>$row->nama_actor,
            );
              echo json_encode($arr_result);
            }
        }
    }

    function get_autocompleteMesinEdit()
    {
        if (isset($_GET['term'])) {
            $result = $this->mm->search_mesin($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'            => $row->no_mesin,
              'no_mesin'         =>$row->no_mesin,
              'tonnase'          =>$row->tonnase,
            );
              echo json_encode($arr_result);
            }
        }
    }
    // public function getTonaseMachine(){
    //     $searchTerm = $this->input->post('searchTerm');
    //     $response = $this->mm->getTonaseMachine($searchTerm);
    //     echo json_encode($response);
    // }

    public function getTonaseMachine(){
        $searchTerm = $_GET['searchTerm'];
        $response= $this->mm->getTonaseMachine($searchTerm);
        $data = array();
        foreach($portdata as $row){
            $data[] = array("id"=>$row->Name, "text"=>$row->Name);
        }
        echo json_encode($data);
    }

  function Add($table,$redirect)
  {
    if($this->input->post('simpan'))
    {
      $this->mm->add_action($table);
      redirect('c_material_transaction/'.$redirect);
    }
  }

  function Edit($table,$redirect)
  {
    $id     = $this->input->post('id'); 
    $where  = $this->input->post('where');
    $this->mm->edit_action($table,$where,$id);
    $this->session->set_flashdata('update', 'Data Berhasil Di Update!');
    redirect('c_material_transaction/'.$redirect);

  }

  function Delete($redirect,$table,$where,$id)
  {
    $this->db->where($where, $id);
    $this->db->delete($table);
    $this->session->set_flashdata('delete', 'Data Berhasil Di Hapus!');
    redirect('c_material_transaction/'.$redirect);
  }

  function add_material_transaction()
  {
    $this->mm->add();
    $this->session->set_flashdata('success', 'Data Berhasil Di Tambahkan!');
    redirect('c_material_transaction/material_transaction_coba');
  }

  //Untuk copy data
  function copy()
  {
    $tanggal = $this->input->post('tanggal');
    $pic = $this->input->post('pic');
    $data = [
                'data'          => $this->data,
                'aktif'         => 'material_transaction',
                'data_tabel'    => $this->mm->copy($tanggal,$pic),
                'tanggal'       => $tanggal,
                'pic'           => $pic,
                'data_pic'      => $this->mm->tampil_pic('mixer'),
              ];
    $this->load->view('material_transaction/copy' , $data);
  }

  function cekData()
      {
        $data = $this->mm->getCekData();
        echo json_encode($data);
      }

  //Tambahan
  public function addition_supply_material()
  {
      $data = [
              'data'          => $this->data,
              'aktif'         => 'material_transaction',
              'action'        => 'Addition Supply Material'
            ];
      $this->load->view('material_transaction/addition_supply_material' , $data);
  }

  //Report Tambahan
  function report_material_transaction_by_part()
    {
      if($this->input->post('show') == 'Show')
      {
        $product  = $this->input->post('product');
        $tahun = $this->input->post('tahun');
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'material_transaction',
                  'data_report'     => $this->mm->tampil_report_supply_material_bypart($product, $bulan, $tahuns), 
                  'product'         => $product,
                  'tahun'           => $tahuns,
                  'bulan'           => $bulan,
                  ];
        $this->load->view('global/supply_material/report_material_transaction_by_part' , $data);
      }
      else
      {
        $product  = 'Ketik Disini';
        $tahun = date('Y-m');
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'material_transaction',
                  'data_report'     => $this->mm->tampil_report_supply_material_bypart($product, $bulan, $tahuns),
                  'product'         => $product,
                  'tahun'           => $tahuns,
                  'bulan'           => $bulan
                  ];
      $this->load->view('global/supply_material/report_material_transaction_by_part' , $data);
      }
       
    }

    //autocomplete
    function get_autocomplete_product()
    {
        if (isset($_GET['term'])) {
            $result = $this->mm->search_product_new($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'               => $row->kode_product.' - '.$row->nama_product,
              'kode_product'        => $row->kode_product,
              'nama_product'        => $row->nama_product,
            );
              echo json_encode($arr_result);
            }
        }
    }

    function report_material_transaction_by_material()
    {
      if($this->input->post('show') == 'Show')
      {
        $material  = $this->input->post('material');
        $tahun = $this->input->post('tahun');
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'material_transaction',
                  'data_report'     => $this->mm->tampil_report_supply_material_bymtrl($material, $bulan, $tahuns), 
                  'material'        => $material,
                  'tahun'           => $tahuns,
                  'bulan'           => $bulan,
                  ];
        $this->load->view('global/supply_material/report_material_transaction_bymaterial' , $data);
      }
      else
      {
        $material  = 'Ketik Disini';
        $tahun = date('Y-m');
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
                  'data'            => $this->data, 
                  'aktif'           => 'material_transaction',
                  'data_report'     => $this->mm->tampil_report_supply_material_bymtrl($material, $bulan, $tahuns),
                  'material'        => $material,
                  'tahun'           => $tahuns,
                  'bulan'           => $bulan
                  ];
      $this->load->view('global/supply_material/report_material_transaction_bymaterial' , $data);
      }
    }

    function get_autocomplete_material()
    {
        if (isset($_GET['term'])) {
            $result = $this->mm->search_material_new($_GET['term']); 
            if (count($result) > 0) {
            foreach ($result as $row)
              $arr_result[] = array(
              'label'               => $row->kode_product.' - '.$row->nama_product,
              'kode_product'        => $row->kode_product,
              'nama_product'        => $row->nama_product,
            );
              echo json_encode($arr_result);
            }
        }
    }
}