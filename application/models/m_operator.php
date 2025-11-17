<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_operator extends CI_Model
{

        function tampilDataDetail($table,$where,$id)
        {
                $this->db->from($table);
                $this->db->where($where,$id);
                $query = $this->db->get();

                return $query->row(); 
        }

        function tampil_select_group($table,$where,$where_id,$order)
        {
                // Use Query Builder for better security and reliability
                $this->db->select('*');
                $this->db->from($table);
                $this->db->where($where, $where_id);
                $this->db->order_by($order, 'ASC');
                $query = $this->db->get();
                
                if ($query->num_rows() > 0){
                        return $query->result_array();
                }
                else {
                        return array();
                }
        }

        function search_bomOp($title){ 
                $this->db->select('adabom.*, t_product.cyt_quo');
                $this->db->from('adabom');
                $this->db->join('t_product', 'adabom.id_product = t_product.id_product', 'left');
                $this->db->like('id_bom', $title , 'both');
                $this->db->or_like('nama_bom', $title , 'both');
                $this->db->order_by('id_bom', 'ASC');
                $this->db->limit(5);
                return $this->db->get()->result();
        }

        function tampildataMesin($id_bom)
        {
                $q = "SELECT *
                        FROM `t_bom_mesin` `db`where db.id_bom = '$id_bom' ORDER by no_mesin ASC";
                                        $query = $result = $this->db->query($q);
                                        if ($result->num_rows() > 0){
                                                return $result->result_array();
                                        }
                                        else {
                                                return array();
                                        }
        }

        function tampildataRelease($id_bom)
        {
                $q = "SELECT *
                        FROM `t_bom_detail` `db`where db.id_bom = '$id_bom'";
                                        $query = $result = $this->db->query($q);
                                        if ($result->num_rows() > 0){
                                                return $result->result_array();
                                        }
                                        else {
                                                return array();
                                        }
        }

        function tampilRelease($id)
        {
                $query = "SELECT  b.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`nama_proses` ,  pp.`usage` , b.`id_bom` FROM t_bom_detail AS b
                        LEFT JOIN `t_product` AS pp ON pp.`id_product` = b.`id_product` where b.id_bom = $id";
                $result = $this->db->query($query);
                if ($result->num_rows()> 0){
                        return $result->result();
                }
                else {
                        return $result->result();
                }
        }


        function search_Defect($title){ 
                $this->db->like('nama', $title , 'both');
                $this->db->having('type', 'NG');
                $this->db->order_by('nama', 'ASC');
                
                return $this->db->get('t_defectdanlosstime')->result();
        }

        function search_Losstime($title){ 
                $this->db->like('nama', $title , 'both');
                $this->db->having('type', 'LT');
                $this->db->order_by('nama', 'ASC');
                // $this->db->limit(5);
                return $this->db->get('t_defectdanlosstime')->result();
        }


        function add()
        {
                $id_production  = $this->input->post("id_production");
                $input  = date('Y-m-d h:i:s');
                $ql = $this->db->select('id_production')->from('t_production_op')
                        ->where('id_production',$id_production)
                        ->get();
                if( $ql->num_rows() > 0 ) {
                    $this->session->set_flashdata('gagal', 'Gagal menambahkan, data yang anda input sudah ada!');
                    redirect('login_op/input_dpr');
                } else {
                     foreach($_POST['user'] as $user)
                        {
                            if (isset($user['qty_lt_minutes'])) {
                                unset($user['qty_lt_minutes']);
                            }
                            if (isset($user['qty_lt'])) {
                                $user['qty_lt'] = is_numeric($user['qty_lt']) ? (float) $user['qty_lt'] : 0;
                            }
                            $this->db->set('lot_global',$this->input->post("lot_global"));
                            $this->db->set('tanggal_input',$input);
                            $this->db->set('runner',$this->input->post("runner"));
                            $this->db->set('id_production',$id_production);
                            $this->db->insert('t_production_op', $user);
                        }

                        foreach($_POST['detail'] as $detail)
                        {
                            $this->db->set('id_production',$id_production);
                            $this->db->insert('t_production_op_dl', $detail);
                        }

                        foreach($_POST['detailLT'] as $detailLT)
                        {
                            $this->db->set('id_production',$id_production);
                            $this->db->insert('t_production_op_dl', $detailLT);
                        }

                        foreach($_POST['ress'] as $ress)
                        {
                            $this->db->set('runner',$this->input->post("runner"));
                            $this->db->set('id_production',$id_production);
                            $this->db->insert('t_production_op_release', $ress);
                        }   
                }
        }

            public function tampil_production($id_production)
            {
                    $this->db->select('*');
                    $this->db->from('v_production_op');
                    $this->db->where('id_production', $id_production);
                    return $this->db->get(); 
            }

            public function tampil_productionRelease($id_production)
            {
                    $this->db->select('*');
                    $this->db->from('v_production_op_release');
                    $this->db->where('id_production', $id_production);
                    return $this->db->get(); 
            }


            public function tampil_productionDL($id_production , $type)
            {
                    $this->db->select('*');
                    $this->db->from('t_production_op_dl');
                    $this->db->where('id_production', $id_production);
                    $this->db->having('type', $type);
                    return $this->db->get(); 
            }

            public function cek_login_user()
            {
                if(empty($this->session->userdata('status')))
                {
                    redirect('login_op');
                }
            }

            function tampil_dpr($operator)
        {
                $query = "SELECT * FROM t_production_op AS q
                        LEFT JOIN t_production_op_dl AS w ON w.`id_production` = q.`id_production`
                        LEFT JOIN t_production_op_release AS r ON r.`id_production` = q.`id_production`
                        LEFT JOIN t_bom AS t ON t.`id_bom` = r.`id_bom`
                        WHERE q.`operator` LIKE '$operator' 
                        GROUP BY q.`id_production`
                        ORDER BY q.`tanggal` DESC";
                $result = $this->db->query($query);
                if ($result->num_rows()> 0){
                        return $result;
                }
                else {
                        return $result;
                }
        }

        function search_prod_plan($date){ 
                $this->db->where('tanggal', $date);
                 // Produces: WHERE name = 'Joe'                
                 // $this->db->like('nama', $title , 'both');
                // $this->db->having('type', 'LT');
                // $this->db->order_by('nama', 'ASC');
                // $this->db->limit(5);
                 return $this->db->get('v_search_prod_plan')->result();
        }

        // Add cutting tool usages for a production record
        function add_cutting_tools_usage($id_production, $cutting_tools_ids) {
            if (!empty($cutting_tools_ids) && is_array($cutting_tools_ids)) {
                foreach ($cutting_tools_ids as $tool_id) {
                    $this->db->insert('t_production_op_cutting_tools_usage', [
                        'id_production' => $id_production,
                        'cutting_tools_id' => $tool_id
                    ]);
                }
            }
        }

        // Add this method to save cutting tool usages for a DPR record
        public function insert_cutting_tool_usages($id_production, $cutting_tools_ids) {
            if (!$id_production || !is_array($cutting_tools_ids)) return;
            foreach ($cutting_tools_ids as $tool_id) {
                $this->db->insert('t_production_op_cutting_tools_usage', [
                    'id_production' => $id_production,
                    'cutting_tools_id' => $tool_id
                ]);
            }
        }

        public function get_cutting_tools_usage($id_production) {
            $this->db->select('ct.code');
            $this->db->from('t_production_op_cutting_tools_usage u');
            $this->db->join('cutting_tools ct', 'u.cutting_tools_id = ct.id');
            $this->db->where('u.id_production', $id_production);
            $query = $this->db->get();
            return $query->result();
        }
}