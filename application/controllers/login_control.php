<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	class login_control extends CI_Controller
	{
       function __construct()
        {
         //session_start(); //mengadakan session
          parent::__construct();
          $this->load->model('login_model');
          $this->load->model('m_operator' , 'op');
          if(!isset($_SESSION)) 
            { 
                session_start(); 
            } 
        } 
  
        public function index()
        {
              if ( isset($_SESSION['user_name']) ) { //cek apakah session ada
                 redirect('c_new/home'); //redirect controller c_home
              }
               
              $this->load->library('form_validation'); //load library form_validation
              $this->form_validation->set_rules('user', 'Username', 'required'); //cek, validasi username
              $this->form_validation->set_rules('pass', 'Password', 'required'); //cek, validasi password
              if ( $this->form_validation->run() == TRUE ) 
              { //apabila validasi true(benar semua)
                 $this->load->model('login_model'); // load model m_user
               
                 $result  = $this->login_model->cekdb($this->input->post('user'),$this->input->post('pass'));
                 $resultX = $this->login_model->cekdbX($this->input->post('user'),$this->input->post('pass'));
                 
                        if ( $result == TRUE) { //apabila result = true(ada data)
                                $_SESSION['user_name'] = $this->input->post('user'); //create session
                                foreach ($resultX as $p)
                                {
                                    $_SESSION['posisi']       = $p['posisi'];
                                    $_SESSION['kode_bagian']  = $p['kode_bagian'];
                                    $_SESSION['divisi']       = $p['divisi'];
                                    $_SESSION['jabatan']      = $p['jabatan'];
                                    $_SESSION['nama_actor']   = $p['nama_actor'];
                                    $_SESSION['is_login']     = TRUE;
                                }
                              
                             
                                redirect('c_new/home'); // redirect controller c_home
                                //redirect('c_new/perbaikan'); 
                        }
              }  
            $this->load->view('login'); //apabila session kosong load login/v_form
        }
         
        public function logout() //fungsi logout
        {
             session_destroy(); //session destroy
             $this->index();//redirect function index()
        }


        public function getDataDetail($table , $where) 
          {
            $data      = [];
            $id        = $this->input->post('id');
            $data      = $this->op->tampilDataDetail($table,$where,$id); 
            echo json_encode($data);
          }
}
