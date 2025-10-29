<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_inventory extends CI_Controller {

  public function __construct()
  {
  parent::__construct();
  $this->load->model('m_inventory' , 'mi');
  $this->load->helper(array('url','html','form'));
  $this->data = [
            'user_name'  => $_SESSION['user_name'],
            'bagian'     => $_SESSION['divisi'],
            ];
  $this->mi->cek_login();
  }



  function index()
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
                'data'          => $this->data,
                'aktif'         => 'Inventory',
                'data_tabel'    => $this->mi->tampil_product(),
                'tahun'       => $tahuns,
                'bulan'       => $bulan
              ];
    $this->load->view('inventory/1inventory', $data);
  }

  // function analis($id,$thnbln)
  // {
  //       $tahun = date('Y-m');
  //       $tahuns = substr($tahun,0,4);
  //       $bulan  = substr($tahun,5,2);
  //       $sql = "SELECT kode_product , nama_product  from t_product where id_product = '$id' ";
  //       $query = $this->db->query($sql);
  //       foreach ($query->result() as $row)
  //         { $kode_product = $row->kode_product; $nama_product = $row->nama_product; } 
  //           $data = [
  //                   'data'              => $this->data,
  //                   'aktif'             => 'global',
  //                   // 'data_production'     => $this->mr->tampil_production($tahun),
  //                   // 'data_DefectGrafik'   => $this->mr->tampil_DefectGrafik($tahuns,$bulan),
  //                   // 'data_EffGrafikYear'  => $this->mr->tampil_EffGrafikYear($tahuns),
  //                   // 'maxPPM'            => $this->mr->maxPPM($tahuns,$bulan),
  //                   'data_tabel'    => $this->mi->tampil_production($id),
  //                   'tanggal'       => $tahun,
  //                   'getNilai'      => substr($tahun,5,2),
  //                   'tahun'         => $tahuns,
  //                   'bulan'         => $bulan,
  //                   'thnbln'        => $thnbln,
  //                   'kode_product'  => $kode_product,
  //                   'nama_product'  => $nama_product
  //                   ];
  //       $this->load->view('inventory/analis' , $data);
  // }

  

  public function view_total_prod($id_product){
    $tahun = date('Y-m');
    $tahuns = substr($tahun,0,4);
    $bulan  = substr($tahun,5,2);
            $data = [
              'data'          => $this->data,
              'aktif'         => 'global',
              'data_header'   => $this->mi->getProduk($id_product),
              'data_tabel'    => $this->mi->tampil_production($id_product),
              'total_analisis'    => $this->mi->total_analisis_default($id_product),
              'tanggal'       => $tahun,
              'tahun'             => $tahuns,
              'bulan'             => $bulan
            ];
            $this->load->view('inventory/total_prod' , $data);
  }
  public function total_prod_filter(){
        if($this->input->post('show') == 'Show')
        {
            $id_product = $this->input->post('id_product');
            $tahun = $this->input->post('tahun');
            $tahuns = substr($tahun,0,4);
            $bulan  = substr($tahun,5,2);
            $data = [
              'data'              => $this->data,
              'aktif'             => 'global',
              'data_header'       => $this->mi->getProduk($id_product),
              'data_tabel'        => $this->mi->tampil_production_byfilter($id_product,$tahun),
              'total_analisis'    => $this->mi->total_analisis_byfilter($id_product,$tahun),
              'tanggal'           => $tahun,
              'tahun'             => $tahuns,
              'bulan'             => $bulan
            ];
            $this->load->view('inventory/total_prod' , $data);
        }
        else
        {
            $tahun = date('Y-m');
            $data = [
              'data'              => $this->data,
              'aktif'             => 'global',
              'data_header'       => $this->mi->getProduk($id_product),
              'data_tabel'        => $this->mi->tampil_production($id_product),
              'total_analisis'    => $this->mi->total_analisis_default($id_product),
              'tanggal'           => $tahun,
            ];
            $this->load->view('inventory/total_prod' , $data);
        } 
    }

    public function view_analisis($id_product){
    $tahun = date('Y-m');
            $data = [
              'data'          => $this->data,
              'aktif'         => 'global',
              'data_header'   => $this->mi->getProduk($id_product),
              'data_tabel'    => $this->mi->tampil_analisis($id_product),
              'data_detail'   => $this->mi->tampil_total_analisis($id_product),
              'tanggal'       => $tahun,
            ];
            $this->load->view('inventory/prod_analisis' , $data);
  }

  public function total_prod_analisis(){
        if($this->input->post('show') == 'Show')
        {
            $id_product = $this->input->post('id_product');
            $tahun = $this->input->post('tahun');
            $data = [
              'data'          => $this->data,
              'aktif'         => 'global',
              'data_header'   => $this->mi->getProduk($id_product),
              'data_tabel'    => $this->mi->tampil_analisis_byfilter($id_product,$tahun),
              'data_detail'   => $this->mi->tampil_total_analisis_byfilter($id_product,$tahun),
              'tanggal'       => $tahun,
            ];
            $this->load->view('inventory/prod_analisis' , $data);
        }
        else
        {
            $tahun = date('Y-m');
            $data = [
              'data'          => $this->data,
              'aktif'         => 'global',
              'data_header'   => $this->mi->getProduk($id_product),
              'data_tabel'    => $this->mi->tampil_analisis($id_product),
              'data_detail'   => $this->mi->tampil_total_analisis($id_product),
              'tanggal'       => $tahun,
            ];
            $this->load->view('inventory/prod_analisis' , $data);
        } 
    }
}