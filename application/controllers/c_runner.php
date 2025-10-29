<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_runner extends CI_Controller {

  public function __construct()
  {
  parent::__construct();
  $this->load->model('m_runner' , 'mr');
  $this->load->helper(array('url','html','form'));
  $this->data = [
            'user_name'   => $_SESSION['user_name'],
            'bagian'      => $_SESSION['divisi'],
            'posisi'     => $_SESSION['posisi'],
            ];
  $this->mr->cek_login();
  }

 
  public function runner()
  {
      if($this->input->post('show'))
      {
      $tanggal = $this->input->post('tanggal');
      $data = [
              'data'          => $this->data,
              'aktif'         => 'runner',
              'data_tabel'    => $this->mr->tampil_runner_new($tanggal),
              'tanggal'       => $tanggal
            ];
      $this->load->view('runner/runner' , $data);
      }
      else
      {
        $tanggal      = date('Y-m-d');
        $data = [
                'data'          => $this->data,
                'aktif'         => 'runner',
                'data_tabel'    => $this->mr->tampil_runner_new($tanggal),
                'tanggal'       => $tanggal
              ];
        $this->load->view('runner/runner' , $data);
      }
  }

  public function supply_material()
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
              'aktif'           => 'runner',
              'material_transaction' => $this->mr->tampil_supply('material_transaction', $tanggal),
              'mesin'             => $this->mr->tampil_no_mesin(),
              'tanggal'            => $tanggal
            ];
        $this->load->view('runner/supply_material' , $data);
  }

  public function supply_materialAct($table = null , $where = null , $id = null)
  {
      if($id == null)
      {
      //$posisi = $this->session->userdata('user_name');
      $data = [
              'data'          => $this->data,
              'aktif'         => 'runner',
              //'pic'           => $this->mm->tampil_select_group($posisi),
              'action'        => 'Add'
            ];
      $this->load->view('runner/supply_materialAct' , $data);
      }
      else
      {
        // $posisi = $this->session->userdata('user_name');
        $data = [
                'data'          => $this->data,
                'aktif'         => 'runner',
                'data_tabel'    => $this->mr->edit_tampil($table , $where , $id),
                //'pic'           => $this->mm->tampil_select_group($posisi),
                'action'        => 'Edit'
              ];
        $this->load->view('runner/supply_materialAct' , $data);
      }
  }

  public function edit_runner($tanggal,$kode_product,$no_mesin)
  {
      $data = [
              'data'          => $this->data,
              'aktif'         => 'runner',
              'data_tabel'    => $this->mr->v_edit_runner($tanggal, $kode_product, $no_mesin),
              'action'        => 'Add'
            ];
      $this->load->view('runner/edit_runner' , $data);
  }

  public function update_runner() {
        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $kode_product = $this->input->post('kode_product');
        $data = array(
            'loss_purge'       => $this->input->post('loss_purge'),
            'runner'             => $this->input->post('runner'),
        );                
        $this->mr->update($data,$id);
        // $this->session->set_flashdata('success', 'Data Berhasil Di Update!');
        redirect('c_runner/edit_runner/'.$tanggal.'/'.$kode_product);    
    }

  function Edit($table,$redirect)
  {
    $id     = $this->input->post('id'); 
    $where  = $this->input->post('where');
    $this->mr->edit_action($table,$where,$id);
    $this->session->set_flashdata('update', 'Data Berhasil Di Update!');
    redirect('c_runner/'.$redirect);

  }

  function Delete($redirect,$table,$where,$id)
  {
    $this->db->where($where, $id);
    $this->db->delete($table);
    $this->session->set_flashdata('delete', 'Data Berhasil Di Hapus!');
    redirect('c_runner/'.$redirect);
  }
}