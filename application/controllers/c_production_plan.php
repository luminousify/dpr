<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_production_plan extends CI_Controller {

  public function __construct()
  {
  parent::__construct();
  //session_start();

  $this->load->model('m_production_plan' , 'mm');
  $this->load->helper(array('url','html','form'));
  $this->data = [
            'user_name'   => $_SESSION['user_name'],
            'bagian'      => $_SESSION['divisi'],
            'nama_actor'  => $_SESSION['nama_actor'],
            ];
  $this->mm->cek_login();
  }

  public function production_plan()
  {
    if($this->input->post('show') == 'Show')
      {
        $tanggal = $this->input->post('tanggal');   
      }
      else
      {
        $tanggal = date('Y-m-d');
      }
        $data = [
              'data'            => $this->data,
              'aktif'           => 'production_plan',
              'cek_isi_db'      => $this->mm->cek_isi_db($tanggal),
              'production_plan' => $this->mm->tampil('production_plan', $tanggal),
              'tanggal'            => $tanggal,
            ];
        $this->load->view('production_plan/production_plan' , $data);
  }

  public function production_planAct($table = null , $where = null , $id = null)
  {
      if($id == null)
      {
      $data = [
              'data'          => $this->data,
              'aktif'         => 'production_plan',
              'action'        => 'Add'
            ];
      $this->load->view('production_plan/production_planAct' , $data);
      }
      else
      {
         $data = [
                'data'          => $this->data,
                'aktif'         => 'production_plan',
                'data_tabel'    => $this->mm->edit_tampil($table , $where , $id),
                'action'        => 'Edit'
              ];
        $this->load->view('production_plan/production_planAct' , $data);
      }
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
      redirect('c_production_plan/'.$redirect);
    }
  }

  function Edit($table,$redirect)
  {
    $id     = $this->input->post('id'); 
    $where  = $this->input->post('where');
    $this->mm->edit_action($table,$where,$id);
    redirect('c_production_plan/'.$redirect);

  }

  function Delete($redirect,$table,$where,$id)
  {
    $this->db->where($where, $id);
    $this->db->delete($table);
    redirect('c_production_plan/'.$redirect);
  }

  public function import_excel()
        {
            if(isset($_FILES["file"]["name"])){
                  // upload
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_size =$_FILES['file']['size'];
                $file_type=$_FILES['file']['type'];
                // move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
                
                $object = PHPExcel_IOFactory::load($file_tmp);
                
                $this->mm->delete_prod_plan_harian($tanggal);

                foreach($object->getWorksheetIterator() as $worksheet){
        
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
        
                    for($row=4; $row<=$highestRow; $row++){
                        
                        $tanggal = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $tanggal_format = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tanggal)); 

                        $kode_produk = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $nama_produk = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $material_name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $prod_qty = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $no_mesin = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $tonnase = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $ct_mesin = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

                        $schadule_jam = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $schadule_jam_format = PHPExcel_Style_NumberFormat::toFormattedString($schadule_jam, 'hh:mm:ss');

                        $target_jam = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $target_jam_format = PHPExcel_Style_NumberFormat::toFormattedString($target_jam, 'hh:mm:ss');
                        $group = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $divisi = $worksheet->getCellByColumnAndRow(11, $row)->getValue();


                        $data[] = array(

                            'tanggal'        => $tanggal_format,
                            'kode_produk'    => $kode_produk,
                            'nama_produk'    => $nama_produk,
                            'material_name'  => $material_name,
                            'prod_qty'       => $prod_qty,
                            'no_mesin'       => $no_mesin,
                            'tonnase'        => $tonnase,
                            'ct_mesin'       => $ct_mesin,
                            'jam'            => $schadule_jam_format,
                            'target'         => $target_jam_format,
                            'group'          => $group,
                            'divisi'         => $divisi,
                        );
        
                    } 
        
                }
        
                $this->db->insert_batch('prod_plan_harian', $data);
        
                $message = array(
                    'message'=>'<div class="alert alert-success text-center" style="text-align:center">Import file excel berhasil!</div>',
                );
                
                $this->session->set_flashdata($message);
                redirect('c_production_plan/production_plan');
            }
            else
            {
                 $message = array(
                    'message'=>'<div class="alert alert-danger">Import file gagal, coba lagi</div>',
                );
                
                $this->session->set_flashdata($message);
                redirect('c_production_plan/production_plan');
            }
        }

    public function import_excel_coba($tanggal)
        {
            if(isset($_FILES["file"]["name"])){
                  // upload
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_size =$_FILES['file']['size'];
                $file_type=$_FILES['file']['type'];
                echo $file_tmp;
                echo $file_name;
                echo $file_size;
                echo $file_type;
                // move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
                
                $object = PHPExcel_IOFactory::load($file_tmp);
                
                $this->mm->delete_prod_plan_harian($tanggal);

                foreach($object->getWorksheetIterator() as $worksheet){
        
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
        
                    for($row=10; $row<=$highestRow; $row++){
                        
                        $tanggal = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $tanggal_format = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tanggal)); 

                        $kode_produk = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $nama_produk = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $material_name = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $prod_qty = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $no_mesin = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $tonnase = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $ct_mesin = $worksheet->getCellByColumnAndRow(9, $row)->getValue();

                        $schadule_jam = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $schadule_jam_format = PHPExcel_Style_NumberFormat::toFormattedString($schadule_jam, 'hh:mm:ss');

                        $target_jam = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $target_jam_format = PHPExcel_Style_NumberFormat::toFormattedString($target_jam, 'hh:mm:ss');
                        // $group = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        // $divisi = $worksheet->getCellByColumnAndRow(11, $row)->getValue();


                        $data[] = array(

                            'tanggal'        => $tanggal_format,
                            'kode_produk'    => $kode_produk,
                            'nama_produk'    => $nama_produk,
                            'material_name'  => $material_name,
                            'prod_qty'       => $prod_qty,
                            'no_mesin'       => $no_mesin,
                            'tonnase'        => $tonnase,
                            'ct_mesin'       => $ct_mesin,
                            'jam'            => $schadule_jam_format,
                            'target'         => $target_jam_format,
                            'divisi'         => $this->session->userdata('divisi'),
                        );
        
                    } 
        
                }
        
                $this->db->insert_batch('prod_plan_harian', $data);
        
                $message = array(
                    'message'=>'<div class="alert alert-success text-center" style="text-align:center">Import file excel berhasil!</div>',
                );
                
                $this->session->set_flashdata($message);
                redirect('c_production_plan/production_plan');
            }
            else
            {
                 $message = array(
                    'message'=>'<div class="alert alert-danger">Import file gagal, coba lagi</div>',
                );
                
                $this->session->set_flashdata($message);
                redirect('c_production_plan/production_plan');
            }
        } 
        
        // public function import_excel_z()
        // {
        //     if(isset($_FILES["file"]["name"])){
        //           // upload
        //         $file_tmp = $_FILES['file']['tmp_name'];
        //         $file_name = $_FILES['file']['name'];
        //         $file_size =$_FILES['file']['size'];
        //         $file_type=$_FILES['file']['type'];
               
        //         // move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
                
        //         $object = PHPExcel_IOFactory::load($file_tmp);
                
        //         // $this->mm->delete_prod_plan_harian($tanggal);

        //         foreach($object->getWorksheetIterator() as $worksheet){
        
        //             $highestRow = $worksheet->getHighestRow();
        //             $highestColumn = $worksheet->getHighestColumn();
        
        //             for($row=10; $row<=$highestRow; $row++){
                        
        //                 $tanggal = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        //                 $tanggal_format = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tanggal)); 

        //                 $kode_produk = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        //                 $nama_produk = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        //                 $material_name = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
        //                 $prod_qty = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
        //                 $no_mesin = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
        //                 $tonnase = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
        //                 $ct_mesin = $worksheet->getCellByColumnAndRow(9, $row)->getValue();

        //                 $schadule_jam = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
        //                 $schadule_jam_format = PHPExcel_Style_NumberFormat::toFormattedString($schadule_jam, 'hh:mm:ss');

        //                 $target_jam = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
        //                 $target_jam_format = PHPExcel_Style_NumberFormat::toFormattedString($target_jam, 'hh:mm:ss');
        //                 // $group = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
        //                 // $divisi = $worksheet->getCellByColumnAndRow(11, $row)->getValue();


        //                 $data[] = array(

        //                     'tanggal'        => $tanggal_format,
        //                     'kode_produk'    => $kode_produk,
        //                     'nama_produk'    => $nama_produk,
        //                     'material_name'  => $material_name,
        //                     'prod_qty'       => $prod_qty,
        //                     'no_mesin'       => $no_mesin,
        //                     'tonnase'        => $tonnase,
        //                     'ct_mesin'       => $ct_mesin,
        //                     'jam'            => $schadule_jam_format,
        //                     'target'         => $target_jam_format,
        //                     'divisi'         => $this->session->userdata('divisi'),
        //                 );
        
        //             } 
        
        //         }
        
        //         // $this->db->insert_batch('prod_plan_harian', $data);
        
        //         // $message = array(
        //         //     'message'=>'<div class="alert alert-success text-center" style="text-align:center">Import file excel berhasil!</div>',
        //         // );
                
        //         // $this->session->set_flashdata($message);
        //         //redirect('c_production_plan/production_plan');
        //     }
        //     else
        //     {
        //          $message = array(
        //             'message'=>'<div class="alert alert-danger">Import file gagal, coba lagi</div>',
        //         );
                
        //         $this->session->set_flashdata($message);
        //         redirect('c_production_plan/production_plan');
        //     }
        // }  

  
}