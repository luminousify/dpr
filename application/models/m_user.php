<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_user extends CI_Model
{
            //Ganti password
            public function getById($id) {
                $this->db->select('*');
                $this->db->from('t_operator');
                $this->db->where('id_operator', $id);
                return $this->db->get(); 
            }

            function update_password($where,$table,$data){
                $this->db->where($where);
                $this->db->update($table,$data);
            }




}