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
        // Check if this is a POST request AND parameters exist
        $isFilterPost = ($this->input->server('REQUEST_METHOD') === 'POST') && 
                        ($this->input->post('tahun') !== null && $this->input->post('tahun') !== '' &&
                         $this->input->post('id_product') !== null && $this->input->post('id_product') !== '');

        // Handle POST request - Process and Redirect (PRG Pattern)
        if($isFilterPost) {
            $tahun = $this->input->post('tahun');
            $id_product = $this->input->post('id_product');
            
            // Validate format to ensure it's Y-m
            if (!preg_match('/^\d{4}-\d{2}$/', $tahun)) {
                $tahun = date('Y-m');
            }
            
            // Validate id_product
            if (empty($id_product) || !is_numeric($id_product)) {
                redirect('c_inventory/index');
                return;
            }
            
            // Build GET URL with parameters and redirect (PRG Pattern)
            $redirect_url = site_url('c_inventory/total_prod_filter?id_product=' . urlencode($id_product) . '&tahun=' . urlencode($tahun));
            redirect($redirect_url);
            return; // Stop execution after redirect
        }
        
        // Handle GET request - Display results
        $id_product = $this->input->get('id_product');
        $tahun = $this->input->get('tahun');
        
        // Validate id_product - redirect if missing
        if (empty($id_product) || !is_numeric($id_product)) {
            redirect('c_inventory/index');
            return;
        }
        
        // Default value if no GET parameter
        if (empty($tahun)) {
            $tahun = date('Y-m');
        }
        
        // Validate format to ensure it's Y-m
        if (!preg_match('/^\d{4}-\d{2}$/', $tahun)) {
            $tahun = date('Y-m');
        }
        
        $tahuns = substr($tahun,0,4);
        $bulan  = substr($tahun,5,2);
        $data = [
          'data'              => $this->data,
          'aktif'             => 'global',
          'data_header'       => $this->mi->getProduk($id_product),
          'data_tabel'        => $this->mi->tampil_production($id_product),
          'total_analisis'    => $this->mi->total_analisis_default($id_product),
          'tanggal'           => $tahun,
          'tahun'             => $tahuns,
          'bulan'             => $bulan
        ];
        $this->load->view('inventory/total_prod' , $data);
    }

    public function view_analisis($id_product){
    $tahun = date('Y-m');
    
    // Validate id_product
    if (empty($id_product) || !is_numeric($id_product)) {
        redirect('c_inventory/index');
        return;
    }
    
    // Retrieve data
    $data_detail = $this->mi->tampil_total_analisis($id_product);
    $data_header = $this->mi->getProduk($id_product);
    $data_tabel = $this->mi->tampil_analisis($id_product);
    
    // Check if product exists (for header)
    if (empty($data_header) || $data_header->num_rows() == 0) {
        redirect('c_inventory/index');
        return;
    }
    
    // Determine if we have production data
    $has_data = false;
    $total_qty = 0;
    
    if (!empty($data_detail) && $data_detail->num_rows() > 0) {
        $detail_row = $data_detail->row_array();
        $total_qty = isset($detail_row['total_qty']) ? $detail_row['total_qty'] : 0;
        $has_data = ($total_qty > 0);
    }
    
    $data = [
      'data'          => $this->data,
      'aktif'         => 'global',
      'data_header'   => $data_header,
      'data_tabel'    => $data_tabel,
      'data_detail'   => $data_detail,
      'tanggal'       => $tahun,
      'has_data'      => $has_data,
      'total_qty'     => $total_qty
    ];
    $this->load->view('inventory/prod_analisis' , $data);
  }

  public function total_prod_analisis(){
        // Check if this is a POST request AND parameters exist
        $isFilterPost = ($this->input->server('REQUEST_METHOD') === 'POST') && 
                        ($this->input->post('tahun') !== null && $this->input->post('tahun') !== '' &&
                         $this->input->post('id_product') !== null && $this->input->post('id_product') !== '');

        // Handle POST request - Process and Redirect (PRG Pattern)
        if($isFilterPost) {
            $tahun = $this->input->post('tahun');
            $id_product = $this->input->post('id_product');
            
            // Validate format to ensure it's Y-m
            if (!preg_match('/^\d{4}-\d{2}$/', $tahun)) {
                $tahun = date('Y-m');
            }
            
            // Validate id_product
            if (empty($id_product) || !is_numeric($id_product)) {
                redirect('c_inventory/index');
                return;
            }
            
            // Build GET URL with parameters and redirect (PRG Pattern)
            $redirect_url = site_url('c_inventory/total_prod_analisis?id_product=' . urlencode($id_product) . '&tahun=' . urlencode($tahun));
            redirect($redirect_url);
            return; // Stop execution after redirect
        }
        
        // Handle GET request - Display results
        $id_product = $this->input->get('id_product');
        $tahun = $this->input->get('tahun');
        
        // Validate id_product - redirect if missing
        if (empty($id_product) || !is_numeric($id_product)) {
            redirect('c_inventory/index');
            return;
        }
        
        // Default value if no GET parameter
        if (empty($tahun)) {
            $tahun = date('Y-m');
        }
        
        // Validate format to ensure it's Y-m
        if (!preg_match('/^\d{4}-\d{2}$/', $tahun)) {
            $tahun = date('Y-m');
        }
        
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