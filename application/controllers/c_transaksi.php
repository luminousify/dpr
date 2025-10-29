<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_transaksi extends CI_Controller {

  public function __construct()
  {
  parent::__construct();
  //session_start();

  $this->load->model('m_new' , 'mm');
  $this->load->helper(array('url','html','form'));
  $this->data = [
            'user_name'  => $_SESSION['user_name'],
            'bagian'     => $_SESSION['divisi'],
            ];
  $this->mm->cek_login();
  }

  public function receiving()
  {
      $data = [
              'data'          => $this->data,
              'aktif'         => 'transaksi',
              'data_product'  => $this->mm->tampil('t_product'),
            ];
      $this->load->view('transaksi/receiving' , $data);
  }


  public function shipping()
  {
      $data = [
              'data'          => $this->data,
              'aktif'         => 'transaksi',
              'data_product'  => $this->mm->tampil('t_product'),
            ];
      $this->load->view('transaksi/shipping' , $data);
    }
}