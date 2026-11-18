<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
  class Login_op extends CI_Controller
  {
        function __construct()
        {
          //session_start(); //mengadakan session
          parent::__construct();
          $this->load->model('login_model');
          $this->load->model('m_operator' , 'mm'); 
          $this->load->model('m_user'); 
         } 


  public function index()
  {
    $this->load->view('login_operator');
  }


  public function ceklogin()
  {
    $nik      = $this->input->post('nik');
    $password = $this->input->post('password');
    $divisi   = $this->input->post('divisi');
    $where    = array(
                'nik'           => $nik, //ME
                'password_op'   => $password,
                'divisi'        => $divisi,
                'status'        => 1
                );
    
           //$cek     = $this->login_model->cek_loginOp("t_operator",$where)->num_rows();
           $cekLg   = $this->login_model->cek_loginOp("t_operator",$where);
           $row1    = $cekLg->row();
           if($cekLg->num_rows() > 0){
            $data_session = array(
              'id_operator'       => $row1->id_operator,
              'password_op'       => $row1->password_op,
              'nik'               => $nik,
              'jabatan'           => $row1->jabatan,
              'nama_operator'     => $row1->nama_operator,
              'divisi'            => $row1->divisi,
              'nama_kanit'         => $row1->nama_kanit,
              'status'            => "login"
              );
       
            $this->session->set_userdata($data_session);
            $this->input();
          }else{
            $this->session->set_flashdata('gagal', 'Username atau password salah!');
            redirect('login_op');
          }
  }

  function input(){
      $data = [
                //'mesin'     => $this->m_master->tampil_mesin(),
                //'kanit'     => $this->m_master->tampil_kanit(),
                'nik'           => $this->session->userdata('nik'),
                'nama'          => $this->session->userdata('nama_operator'),
                'jabatan'       => $this->session->userdata('jabatan'),
                'divisi'       => $this->session->userdata('divisi'),
      ];
               $this->load->view('online/input' , $data);
  }

  function input_mesin()
  {
    $this->load->view('online/input_mesin');
  }

  function input_dpr()
  {
    $this->mm->cek_login_user();

    $data = [
                //'mesin'     => $this->m_master->tampil_mesin(),
                'kanit'             => $this->mm->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                'id_operator'       => $this->session->userdata('id_operator'),
                'nik'               => $this->session->userdata('nik'),
                'password_op'       => $this->session->userdata('password_op'),
                'nama'              => $this->session->userdata('nama_operator'),
                'jabatan'           => $this->session->userdata('jabatan'),
                'nama_kanit'        => $this->session->userdata('nama_kanit'),
                'divisi'            => $this->session->userdata('divisi'),
      ];
    $this->load->view('online/input_dpr',$data);
  }

  /**
   * PWA entry point (Ticket 4). Parallel deployment of the offline-capable
   * input form (CONTEXT.md Decision 8): this method mirrors input_dpr() but
   * loads online/input_dpr_pwa which links the manifest + registers sw.js.
   * The existing login_op/input_dpr route is intentionally left untouched.
   */
  function input_dpr_pwa()
  {
    $this->mm->cek_login_user();

    // Enable query caching for kanit dropdown (cached for 1 hour)
    $this->db->cache_on();
    $kanit_data = $this->mm->tampil_select_group('t_operator','jabatan','kanit','nama_operator');
    $this->db->cache_off();

    // Optimize session data retrieval - get all at once
    $session_data = $this->session->userdata();

    $data = [
                //'mesin'     => $this->m_master->tampil_mesin(),
                'kanit'             => $kanit_data,
                'id_operator'       => $session_data['id_operator'],
                'nik'               => $session_data['nik'],
                'password_op'       => $session_data['password_op'],
                'nama'              => $session_data['nama_operator'],
                'jabatan'           => $session_data['jabatan'],
                'nama_kanit'        => $session_data['nama_kanit'],
                'divisi'            => $session_data['divisi'],
      ];
    $this->load->view('online/input_dpr_pwa',$data);
  }

  /**
   * Ticket 7 (Phase 5.2): AJAX endpoint that returns the full reference
   * data set (kanit + BOM + cutting tools) as JSON for the PWA offline
   * cache. Called by reference-data.js on app open (when online) to
   * populate IndexedDB. Requires an active operator session; returns an
   * empty payload (not a redirect) if unauthenticated so the JS layer
   * can degrade gracefully.
   *
   * The kanit list reuses the same tampil_select_group query input_dpr_pwa
   * uses. BOM uses get_all_bom_for_cache (full list, no limit). Cutting
   * tools reuses m_dpr::get_cutting_tools_autocomplete(null). All three
   * pass through Pwa_reference_normalizer so the JSON shape matches what
   * the JS autocomplete widgets expect.
   */
  public function pwa_reference_data()
  {
    $this->output->set_content_type('application/json');
    if (empty($this->session->userdata('nama_operator'))) {
      $this->output->set_output(json_encode(array('kanit' => array(), 'bom' => array(), 'cutting_tools' => array())));
      return;
    }

    $this->load->model('m_dpr');

    $kanit_raw      = $this->mm->tampil_select_group('t_operator','jabatan','kanit','nama_operator');
    $bom_raw        = $this->mm->get_all_bom_for_cache();
    $cutting_raw    = $this->m_dpr->get_cutting_tools_autocomplete(null);

    $this->load->library('Pwa_reference_normalizer');
    $data = array(
      'kanit'         => Pwa_reference_normalizer::normalize_kanit_list($kanit_raw ? $kanit_raw : array()),
      'bom'           => Pwa_reference_normalizer::normalize_bom_list($bom_raw ? $bom_raw : array()),
      'cutting_tools' => Pwa_reference_normalizer::normalize_cutting_tools($cutting_raw ? $cutting_raw : array()),
    );
    $this->output->set_output(json_encode($data));
  }

  /**
   * Ticket 7 (Phase 5.1): lightweight session-validation ping. Called by
   * auth.js on pageshow/online when the network is up to silently confirm
   * the CI3 session cookie is still live and to refresh the cached
   * identity (in case name/divisi/kanit changed server-side). Does NOT
   * redirect on failure - returns {valid:false} so the JS layer keeps the
   * offline form usable from the IndexedDB cache and surfaces a re-login
   * prompt via the sync badge (Ticket 6's 401 handling).
   */
  public function pwa_session_ping()
  {
    $this->output->set_content_type('application/json');
    if (empty($this->session->userdata('nama_operator'))) {
      $this->output->set_output(json_encode(array('valid' => false)));
      return;
    }
    $this->load->library('Pwa_reference_normalizer');
    $identity = Pwa_reference_normalizer::normalize_session_identity($this->session->userdata());
    $this->output->set_output(json_encode(array('valid' => true, 'identity' => $identity)));
  }

  public function change_password()
  {
    $id_operator = $this->session->userdata('id_operator');
    $data = [
              'data_user'    => $this->m_user->getById($id_operator),
              'title'     => 'DPR Online | Change Password'
            ];
        $this->load->view('online/change_password',$data);
  }

  public function change_passwordAct($id)
  {
    $id_operator = $this->session->userdata('id_operator');
    $pass_db = $this->session->userdata('password_op');
    $pass_sebelum = $this->input->post('password_old');
    $pass_baru = $this->input->post('newPass');
    $pass_konfirmasi = $this->input->post('confirmPass');
        if ($pass_sebelum !== $pass_db) {
          $this->session->set_flashdata('salah', 'Kata sandi sebelumnya salah!');
          redirect('login_op/change_password');
          }else if ($pass_baru == $pass_db) {
            $this->session->set_flashdata('sama', 'Kata sandi baru tidak boleh sama dengan kata sandi sebelumnya!');
              redirect('login_op/change_password');
          }else if ($pass_baru !== $pass_konfirmasi) {
            $this->session->set_flashdata('tidak_sama', 'Kata sandi baru harus sama dengan kata sandi konfirmasi!');
              redirect('login_op/change_password');
          } else {
              $data = [
                  'password_op'     => $pass_baru
              ];
              $where = array(
          'id_operator' => $id_operator
        );
              $this->m_user->update_password($where,'t_operator', $data);
              $this->session->set_flashdata('success_change_password', 'Berhasil edit pasword, silahkan login kembali!');
              //session()->destroy();
              redirect('login_op/change_password');
        }
    } 

    public function logout()
    {
      $this->session->sess_destroy();
      redirect('login_op');
    } 

    function list_dpr()
    {
    $this->mm->cek_login_user();
    $operator = $this->session->userdata('nama_operator');
    $data = [
                //'mesin'     => $this->m_master->tampil_mesin(),
                'list_dpr'          => $this->mm->tampil_dpr($operator),
                'id_operator'       => $this->session->userdata('id_operator'),
                'nik'               => $this->session->userdata('nik'),
                'password_op'       => $this->session->userdata('password_op'),
                'nama'              => $this->session->userdata('nama_operator'),
                'jabatan'           => $this->session->userdata('jabatan'),
                'divisi'            => $this->session->userdata('divisi'),
      ];
    $this->load->view('online/list_dpr',$data);
    }

    function view_dpr_detail($id_production)
      {
        $data = array(
                'data_production'     =>$this->mm->tampil_production($id_production),
                'data_productionRelease'  =>$this->mm->tampil_productionRelease($id_production),
                'data_productionNG'     =>$this->mm->tampil_productionDL($id_production , 'NG'),
                'data_productionLT'     =>$this->mm->tampil_productionDL($id_production , 'LT'),
                'kanit'               => $this->mm->tampil_select_group('t_operator','jabatan','kanit','nama_operator'),
                'divisi'                => $this->session->userdata('divisi'),

            );
        $this->load->view('online/view_detail_dpr' , $data);
      }

    function save_dpr()
    {
        // Save DPR record
        $id_production = $this->mm->save_dpr();
        
        // After saving DPR, save cutting tools usage
        $cutting_tools_ids = $this->input->post('cutting_tools_ids');
        if (!empty($cutting_tools_ids)) {
            $this->m_operator->add_cutting_tools_usage($id_production, $cutting_tools_ids);
        }
    }
}