<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_production_plan extends CI_Model
{
	public function __construct()
    {
        $this->load->database();
        $this->load->helper(array('url','html','form'));
    }

    public function tampil($table,$tanggal)
    {
        $this->db->where('tanggal', $tanggal);
        return $this->db->get($table)->result();
    }

    function add_action($table)
    {
        foreach ($_POST['user'] as $user)
        {
            $this->db->insert($table , $user);
        }
    }

    function edit_action($table,$where,$id)
    {
        foreach ($_POST['user'] as $user)
        {
            $this->db->where($where,$id);
            $this->db->update($table , $user);
        }
    }

    function edit_tampil($table,$where,$id) 
    {
        $query = "SELECT * FROM $table where $where = '$id'";
        $result = $this->db->query($query);
            if ($result->num_rows() > 0)
            {
                return $result;
            }
            else {
                return null;
            }
    }

    function search_product($title){ 
                $this->db->like('kode_product', $title , 'both');
                $this->db->or_like('nama_product', $title , 'both');
                $this->db->order_by('id_bom', 'ASC');
                $this->db->limit(5);
                return $this->db->get('v_bom_product_release')->result();
        }

    function search_productEdit($title){ 
                $this->db->like('kode_product', $title , 'both');
                $this->db->or_like('nama_product', $title , 'both');
                $this->db->order_by('id_bom', 'ASC');
                $this->db->limit(5);
                return $this->db->get('v_bom_product_release')->result();
        }

    function search_mesin($title){ 
                $this->db->like('no_mesin', $title);
                $this->db->order_by('no_mesin', 'ASC');
                $this->db->limit(5);
                return $this->db->get('t_no_mesin')->result();
        }
    //Get autocomplete
    // public function getTonaseMachine($searchTerm=""){
    //          $this->db->select('*');
    //          $this->db->where("no_mesin LIKE '%".$searchTerm."%'");
    //          // $this->db->limit(1);
    //          $fetched_records = $this->db->get('t_no_mesin');
    //          $users = $fetched_records->result_array();
    //          // Initialize Array with fetched data
    //          $data = array();
    //          foreach($users as $user){
    //              $data[] = array("id"=>$user['tonnase'], "text"=>$user['no_mesin']);
    //          }
    //          return $data;
    // }

     public function getTonaseMachine($searchTerm){
        $this->db->like('no_mesin', $searchTerm);
        $query = $this->db->select('*')->get('t_no_mesin');
        $portlist = $query->result();
        return $portlist;
    }

    function cek_login()
    {
        if(empty($_SESSION['user_name']))
        {
            redirect('login_control/index');
        }
    }

    public function delete_prod_plan_harian($tanggal){
                $this->db->where('tanggal', $tanggal);
                $this->db->delete('prod_plan_harian');
        }

    public function cek_isi_db($tanggal)
        {
           $query = $this->db->query("SELECT COUNT(q.`id`) AS total FROM `prod_plan_harian` AS q
           WHERE q.`tanggal` = '$tanggal'");
           $res = $query->result();
           return $res;
          }
    
}