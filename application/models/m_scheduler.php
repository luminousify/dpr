<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 require 'vendor/autoload.php';
 use WebSocket\Client;

class m_scheduler extends CI_Model
{
	 public function __construct()
        {
                $this->load->database();
                $this->load->helper(array('url','html','form'));
        }

    function get_unsend_schedule(){
        $query = $this->db->query("SELECT q.`no_mesin`,r.`ip` FROM machine_use_with_controller q
        LEFT JOIN t_no_mesin_copy r
        ON q.`no_mesin` = r.`no_mesin`
        WHERE q.`send` = 0");
        return $query;
    }

        

}