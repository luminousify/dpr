<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_operator extends CI_Model
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

        /**
         * Ticket 7 (Phase 5.2): full BOM list for the PWA offline reference
         * cache. Joins t_bom + t_product directly (rather than the `adabom`
         * VIEW search_bomOp uses) so the cache works on both local dev (no
         * adabom view) and production (where the view exists). Ordered by
         * id_bom to match the autocomplete's ordering. Returns result_array()
         * so Pwa_reference_normalizer::normalize_bom_list can consume it
         * without re-casting.
         */
        function get_all_bom_for_cache(){
                $this->db->select('t_bom.id_bom, t_bom.nama_bom, t_bom.cyt_mp_bom, t_bom.cyt_mc_bom, t_bom.id_product, t_product.kode_product, t_product.nama_product, t_product.nama_proses, t_product.cyt_quo, t_product.target_shift, t_product.customer, t_product.kode_proses, t_product.cavity');
                $this->db->from('t_bom');
                $this->db->join('t_product', 't_bom.id_product = t_product.id_product', 'left');
                $this->db->order_by('t_bom.id_bom', 'ASC');
                $query = $this->db->get();
                return $query->result_array();
        }

        /**
         * Autocomplete helper: search operator names from t_operator (all jabatan)
         * @param string $term
         * @param int $limit
         * @return array
         */
        public function search_operator_name($term, $limit = 20)
        {
                $term = (string) $term;
                $limit = (int) $limit;
                if ($limit <= 0) $limit = 20;

                $this->db->select('nama_operator');
                $this->db->from('t_operator');
                $this->db->like('nama_operator', $term, 'both');
                $this->db->order_by('nama_operator', 'ASC');
                $this->db->limit($limit);
                $query = $this->db->get();
                return $query->result_array();
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


        public function find_duplicate_entry($tanggal, $mesin, $shift, $id_bom)
        {
                return $this->db->select('id_production_op, id_production, operator, qty_ok, tanggal_input')
                        ->from('t_production_op')
                        ->where('tanggal', $tanggal)
                        ->where('mesin', $mesin)
                        ->where('shift', $shift)
                        ->where('id_bom', $id_bom)
                        ->limit(1)
                        ->get()
                        ->row();
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
                     $users = isset($_POST['user']) && is_array($_POST['user']) ? $_POST['user'] : array();
                     $confirm_duplicate = $this->input->post('confirm_duplicate') == '1';
                     if (!$confirm_duplicate) {
                         foreach ($users as $u) {
                             $dup = $this->find_duplicate_entry($u['tanggal'], $u['mesin'], $u['shift'], $u['id_bom']);
                             if ($dup) {
                                 $msg = 'Duplikat terdeteksi: tanggal ' . $u['tanggal']
                                      . ', mesin ' . $u['mesin'] . ', shift ' . $u['shift']
                                      . ' sudah diinput (operator: ' . $dup->operator
                                      . ', qty OK: ' . $dup->qty_ok
                                      . ', jam input: ' . $dup->tanggal_input . ').'
                                      . ' Jika benar-benar perlu menambah, kirim ulang dengan centang "Konfirmasi duplikat".';
                                 $this->session->set_flashdata('gagal', $msg);
                                 redirect('login_op/input_dpr');
                                 return;
                             }
                         }
                     }
                     $this->load->library('dpr_batch_builder');

                     $user_rows = Dpr_batch_builder::build_production_op_rows($users, array(
                         'lot_global'    => $this->input->post('lot_global'),
                         'tanggal_input' => $input,
                         'runner'        => $this->input->post('runner'),
                         'id_production' => $id_production,
                     ));
                     if (! empty($user_rows)) {
                         $this->db->insert_batch('t_production_op', $user_rows);
                     }

                     $details = isset($_POST['detail']) && is_array($_POST['detail']) ? $_POST['detail'] : array();
                     $detail_rows = Dpr_batch_builder::build_child_rows($details, array('id_production' => $id_production));
                     if (! empty($detail_rows)) {
                         $this->db->insert_batch('t_production_op_dl', $detail_rows);
                     }

                     $detailsLT = isset($_POST['detailLT']) && is_array($_POST['detailLT']) ? $_POST['detailLT'] : array();
                     $detaillt_rows = Dpr_batch_builder::build_child_rows($detailsLT, array('id_production' => $id_production));
                     if (! empty($detaillt_rows)) {
                         $this->db->insert_batch('t_production_op_dl', $detaillt_rows);
                     }

                     $ress_list = isset($_POST['ress']) && is_array($_POST['ress']) ? $_POST['ress'] : array();
                     $ress_rows = Dpr_batch_builder::build_child_rows($ress_list, array(
                         'runner'        => $this->input->post('runner'),
                         'id_production' => $id_production,
                     ));
                     if (! empty($ress_rows)) {
                         $this->db->insert_batch('t_production_op_release', $ress_rows);
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

        

        // Add cutting tool usages for a production record
        function add_cutting_tools_usage($id_production, $cutting_tools_ids) {
            $this->load->library('dpr_batch_builder');
            $rows = Dpr_batch_builder::build_cutting_tools_rows(is_array($cutting_tools_ids) ? $cutting_tools_ids : array(), $id_production);
            if (! empty($rows)) {
                $this->db->insert_batch('t_production_op_cutting_tools_usage', $rows);
            }
        }

        // Add this method to save cutting tool usages for a DPR record
        public function insert_cutting_tool_usages($id_production, $cutting_tools_ids) {
            $this->load->library('dpr_batch_builder');
            if (!$id_production || !is_array($cutting_tools_ids)) return;
            $rows = Dpr_batch_builder::build_cutting_tools_rows($cutting_tools_ids, $id_production);
            if (! empty($rows)) {
                $this->db->insert_batch('t_production_op_cutting_tools_usage', $rows);
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

        /**
         * Check if a production ID already exists in t_production_op
         * @param string $id_production
         * @return bool
         */
        public function id_production_exists($id_production) {
            $this->db->where('id_production', $id_production);
            $query = $this->db->get('t_production_op');
            return $query->num_rows() > 0;
        }
}