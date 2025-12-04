<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Login_control extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->model('login_model');
        $this->load->database();
    }

    public function index()
    {
        // Check if user is already logged in
        if (isset($_SESSION['user_name'])) {
            redirect('c_new/home');
        }
        
        $this->load->view('login');
    }
    
    public function authenticate()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        if ($username && $password) {
            // Query the user table to get user data
            $this->db->where('user_name', $username);
            $this->db->where('password', $password);
            $query = $this->db->get('user');
            
            if ($query->num_rows() > 0) {
                // User found, set session data from database
                $user_data = $query->row();
                $_SESSION['user_name'] = $user_data->user_name;
                $_SESSION['divisi'] = $user_data->divisi ?? 'General Division';
                $_SESSION['posisi'] = $user_data->posisi ?? 'User';
                $_SESSION['nama_actor'] = $user_data->nama_actor ?? $username;
                $_SESSION['bagian'] = $user_data->bagian ?? $user_data->divisi ?? 'General Division';
                
                redirect('c_new/home');
            } else {
                // User not found or invalid credentials
                // Try to check if user exists in t_operator table as fallback
                $this->db->where('nik', $username);
                $this->db->where('password_op', $password);
                $op_query = $this->db->get('t_operator');
                
                if ($op_query->num_rows() > 0) {
                    $op_data = $op_query->row();
                    $_SESSION['user_name'] = $op_data->nama_operator ?? $username;
                    $_SESSION['divisi'] = $op_data->divisi ?? 'General Division';
                    $_SESSION['posisi'] = $op_data->jabatan ?? 'User';
                    $_SESSION['nama_actor'] = $op_data->nama_operator ?? $username;
                    $_SESSION['bagian'] = $op_data->divisi ?? 'General Division';
                    
                    redirect('c_new/home');
                } else {
                    // No user found in either table
                    $this->session->set_flashdata('error', 'Invalid username or password');
                    redirect('login_control/index');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please enter username and password');
            redirect('login_control/index');
        }
    }
    
    public function logout()
    {
        // Clear session data
        session_destroy();
        redirect('login_control/index');
    }
}
