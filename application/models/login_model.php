<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class login_model extends CI_Model
{
         
        function __construct() 
 {
  parent::__construct();
 }
  
    function cekdb()
    {
        $user   = $this->input->post('user');
        $pass   = $this->input->post('pass');
        $divisi = $this->input->post('divisi');
        
        $this->db->where('user_name', $user );
        $this->db->where('password', $pass );
        $this->db->where('divisi', $divisi );
        $query = $this->db->get('user');
        return $query->row();
    }


    function cekdbX($user,$pass)
    {
        $q = "SELECT * FROM user WHERE  user_name = '$user' and password = '$pass' ";
        $query = $result = $this->db->query($q);
        if ($result->num_rows()> 0)
        {
            return $result->result_array();
        }
        else 
        {
            return array();
        }
    }

    function cek_loginOp($table,$where)
     {
        return $this->db->get_where($table,$where);
     }


}