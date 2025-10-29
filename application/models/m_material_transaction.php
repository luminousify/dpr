<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_material_transaction extends CI_Model
{
	public function __construct()
    {
        $this->load->database();
        $this->load->helper(array('url','html','form'));
    }

    public function tampil($table,$tanggal)
    {
        $this->db->where('tanggal', $tanggal);
        $this->db->order_by('no_mesin', 'ASC');
        return $this->db->get($table)->result();
    }

    function tampil_no_mesin()
        {
                $q = "SELECT q.`no_mesin` FROM `t_no_mesin` AS q";
                $query = $result = $this->db->query($q);
                if ($result->num_rows() > 0){
                        return $result->result_array();
                }
                else {
                        return array();
                }
        }
    public function tampil_report_supply_material_default()
       {
        $query = $this->db->query("SELECT q.*, ROUND(SUM(w.`loss_purge`),2) AS loss_purge, ROUND(SUM(w.`runner`),2) AS runner FROM `material_transaction` AS q
                LEFT JOIN v_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`kode_produk` = w.kode_product
                WHERE q.`no_mesin` = 1 AND MONTH(q.`tanggal`) = MONTH(CURDATE())
                GROUP BY q.`tanggal`
                ORDER BY q.kode_produk ASC,q.tanggal ASC
        ");
        return $query;
       }

    public function tampil_report_supply_material_byfilter($no_mesin, $bulan, $tahun)
       {
        $query = $this->db->query("SELECT q.`tanggal`, q.`no_mesin`, q.`kode_produk`, q.`nama_produk`, q.`material`,
                q.`virgin`, q.`regrind`, ROUND(SUM(w.`loss_purge`),2) AS loss_purge, ROUND(SUM(w.`runner`),2) AS runner FROM `material_transaction` AS q
                LEFT JOIN v_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`kode_produk` = w.kode_product
                WHERE q.`no_mesin` = '$no_mesin' AND MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun' AND q.`kode_produk` != ''
                GROUP BY q.`tanggal`, q.`kode_produk`
                ORDER BY q.tanggal ASC
        ");
        return $query;
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
                $this->db->like('kode_product', $title);
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

    function search_pic($title){ 
                $this->db->where('user_name', $title);
                $this->db->order_by('user_name', 'ASC');
                $this->db->limit(5);
                return $this->db->get('user')->result();
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

    function tampil_select_group($username)
        {
                $q = "SELECT * FROM USER AS q
                        WHERE q.`user_name` = '$username'";
                $query = $result = $this->db->query($q);
                if ($result->num_rows() > 0){
                        return $result->result_array();
                }
                else {
                        return array();
                }
        }

    public function tampil_mesin_all($table)
        {
            $this->db->select('no_mesin');
            $this->db->from($table);
            $this->db->order_by('no_mesin' , 'ASC');
            return $this->db->get();         
        }

    function add()
        {
                foreach($_POST['user'] as $user)
                {   
                    $this->db->set('tanggal' , $this->input->post('tanggal'));
                    $this->db->insert('material_transaction', $user);
                }
        }

    function copy($tanggal,$pic)
        {
            $query = $this->db->query("SELECT * FROM `material_transaction` AS p  
                WHERE p.`tanggal` = '$tanggal' AND p.`pic` = '$pic'
                ORDER BY p.`no_mesin` ASC");
         return $query;
        }

    function getCekData()
        {
            $tanggal = $this->input->post('tanggal');
            $pic = $this->input->post('pic');
              $response = array();
              $this->db->select('*');
              $this->db->where('tanggal', $tanggal);
              $this->db->where('pic', $pic);
              $records = $this->db->get('material_transaction');
              $response = $records->result_array();
              return $response;
        }

    function tampil_pic($posisi)
        {
                $q = "SELECT * FROM user AS q
                        WHERE q.`posisi` LIKE '%$posisi%' OR q.`nama_actor` = 'Waluyo' OR q.`nama_actor` = 'DAHLAN TOHA DARMATIN'";
                $query = $result = $this->db->query($q);
                if ($result->num_rows() > 0){
                        return $result->result_array();
                }
                else {
                        return array();
                }
        }


    //Tambahan Report
    public function tampil_report_supply_material_bypart($product, $bulan, $tahun)
       {
        $query = $this->db->query("SELECT q.`tanggal`, q.`kode_produk`, q.`nama_produk`, q.`material`, q.`virgin`, q.`regrind`, ROUND(SUM(w.`loss_purge`),2) AS loss_purge, ROUND(SUM(w.`runner`),2) AS runner FROM `material_transaction` AS q
                LEFT JOIN v_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`kode_produk` = w.kode_product
                WHERE q.`kode_produk` = '$product' AND MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun'
                GROUP BY q.`tanggal`, q.`kode_produk`
        ");
        return $query;
       }

    public function tampil_report_supply_material_bymtrl($material, $bulan, $tahun)
       {
        $query = $this->db->query("SELECT q.`tanggal`, q.`no_mesin`, q.`material`, q.`virgin`, q.`regrind`,
                ROUND(SUM(w.`loss_purge`),2) AS loss_purge, ROUND(SUM(w.`runner`),2) AS runner FROM `material_transaction` AS q
                LEFT JOIN v_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`kode_produk` = w.kode_product
                WHERE q.`material` = '$material' AND MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun'
                GROUP BY q.`tanggal`,q.material, q.no_mesin
                ORDER BY q.tanggal ASC, q.no_mesin ASC
        ");
        return $query;
       }

    function search_product_new($title){ 
                $this->db->like('kode_product', $title , 'both');
                $this->db->or_like('nama_product', $title , 'both');
                $this->db->where('type', 'FG');
                $this->db->order_by('kode_product', 'ASC');
                $this->db->limit(5);
                return $this->db->get('t_product')->result();
    }

    function search_material_new($title){ 
                $this->db->like('kode_product', $title , 'both');
                $this->db->or_like('nama_product', $title , 'both');
                $this->db->where('type', 'RMW');
                $this->db->order_by('kode_product', 'ASC');
                $this->db->group_by('kode_product');
                $this->db->limit(5);
                return $this->db->get('t_product')->result();
    }
}