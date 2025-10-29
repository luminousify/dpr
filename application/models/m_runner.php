
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_runner extends CI_Model
{
	     public function __construct()
        {
                $this->load->database();
                $this->load->helper(array('url','html','form'));
        }
        function cek_login()
            {
                if(empty($_SESSION['user_name']))
                {
                    redirect('login_control/index');
                }
            }

      public function tampil($table,$tanggal_dari,$tanggal_sampai)
    {
        $this->db->where('tanggal >=', $tanggal_dari);
        $this->db->where('tanggal <=', $tanggal_sampai);
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
      public function tampil_runnerDefault()
        {
           $query = $this->db->query("SELECT q.`tanggal`, q.`mesin`,q.`kode_product`, q.`nama_product`, w.`np_mr` AS material,
            REPLACE(REPLACE(q.kode_product, ' ', '-'), '/', '-') AS kode_product_new,
            SUM((CASE WHEN q.shift = 1  THEN q.loss_purge END)) AS loss_purge1,
            SUM((CASE WHEN q.shift = 1  THEN q.runner END)) AS runner1,
            SUM((CASE WHEN q.shift = 2  THEN q.loss_purge END)) AS loss_purge2,
            SUM((CASE WHEN q.shift = 2  THEN q.runner END)) AS runner2,
            SUM((CASE WHEN q.shift = 3  THEN q.loss_purge END)) AS loss_purge3,
            SUM((CASE WHEN q.shift = 3  THEN q.runner END)) AS runner3,
            SUM(q.`runner`) AS total_runner
            FROM `runner_by_day` AS q
            LEFT JOIN `select_bom` AS w ON q.`kode_product` = w.`kp_pr`
            WHERE q.`tanggal` = CURDATE()
            GROUP BY q.`kode_product`,  q.`mesin`
            ORDER BY q.`mesin` ASC");  
        return $query;
          }

      public function tampil_runner($tanggal_dari, $tanggal_sampai)
        {
           $query = $this->db->query("SELECT q.`tanggal`,q.`mesin`,q.`kode_product`, q.`nama_product`, w.`np_mr` AS material,
            REPLACE(REPLACE(q.kode_product, ' ', '-'), '/', '-') AS kode_product_new,
          SUM((CASE WHEN q.shift = 1  THEN q.loss_purge END)) AS loss_purge1,
          SUM((CASE WHEN q.shift = 1  THEN q.runner END)) AS runner1,
          SUM((CASE WHEN q.shift = 2  THEN q.loss_purge END)) AS loss_purge2,
          SUM((CASE WHEN q.shift = 2  THEN q.runner END)) AS runner2,
          SUM((CASE WHEN q.shift = 3  THEN q.loss_purge END)) AS loss_purge3,
          SUM((CASE WHEN q.shift = 3  THEN q.runner END)) AS runner3,
          SUM(q.`runner`) AS total_runner
          FROM `runner_by_day` AS q
          LEFT JOIN `select_bom` AS w ON q.`kode_product` = w.`kp_pr`
          WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
          GROUP BY q.`kode_product`, q.`mesin`
          ORDER BY q.`mesin` ASC");  
        return $query;
          }

          public function tampil_runner_new($tanggal)
        {
           $query = $this->db->query("SELECT 
    MAX(q.`tanggal`) as tanggal,
    q.`mesin`,
    q.`kode_product`, 
    MAX(q.`nama_product`) as nama_product, 
    MAX(w.`np_mr`) AS material,
    REPLACE(REPLACE(q.kode_product, ' ', '-'), '/', '-') AS kode_product_new,
    MAX(CASE WHEN q.shift = 1 THEN q.loss_purge END) AS loss_purge1,
    MAX(CASE WHEN q.shift = 1 THEN q.runner END) AS runner1,
    MAX(CASE WHEN q.shift = 2 THEN q.loss_purge END) AS loss_purge2,
    MAX(CASE WHEN q.shift = 2 THEN q.runner END) AS runner2,
    MAX(CASE WHEN q.shift = 3 THEN q.loss_purge END) AS loss_purge3,
    MAX(CASE WHEN q.shift = 3 THEN q.runner END) AS runner3,
    (COALESCE(MAX(CASE WHEN q.shift = 1 THEN q.runner END), 0) +
     COALESCE(MAX(CASE WHEN q.shift = 2 THEN q.runner END), 0) +
     COALESCE(MAX(CASE WHEN q.shift = 3 THEN q.runner END), 0)) AS total_runner
FROM `runner_by_day` AS q
LEFT JOIN `select_bom` AS w ON q.`kode_product` = w.`kp_pr`
WHERE q.`tanggal` = '$tanggal'
GROUP BY q.`kode_product`, q.`mesin`
ORDER BY q.`mesin` ASC");  
        return $query;
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

    function edit_action($table,$where,$id)
    {
        foreach ($_POST['user'] as $user)
        {
            $this->db->where($where,$id);
            $this->db->update($table , $user);
        }
    }

    public function update($data,$id) {
        return $this->db->update('t_production_op', $data, array('id_production_op' => $id));
      }

    public function v_edit_runner($tanggal, $kode_product, $no_mesin)
        {
           $query = $this->db->query("SELECT * FROM v_production_op AS w
        WHERE w.`tanggal` = '$tanggal' AND w.`kp_pr` = '$kode_product' AND w.`mesin` = '$no_mesin'");  
        return $query;
          }

     public function tampil_supply($table,$tanggal)
    {
        $this->db->where('tanggal', $tanggal);
        return $this->db->get($table)->result();
    }
}