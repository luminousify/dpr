<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class m_new extends CI_Model
{
        public function __construct()
        {
                $this->load->database();
                $this->load->helper(array('url', 'html', 'form'));
        }

        function tampilDataDetail($table, $where, $id)
        {
                $this->db->from($table);
                $this->db->where($where, $id);
                $query = $this->db->get();

                return $query->row();
        }

        public function tampil($table)
        {
                return $this->db->get($table)->result();
        }
        public function tampil_product($table)
        {
                $this->db->limit(5);
                return $this->db->get($table)->result();
        }


        function add_action($table)
        {
                foreach ($_POST['user'] as $user) {
                        $this->db->insert($table, $user);
                }
        }

        function edit_tampil($table, $where, $id)
        {
                $query = "SELECT * FROM $table where $where = '$id'";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0) {
                        return $result;
                } else {
                        return null;
                }
        }

        function edit_action($table, $where, $id)
        {
                foreach ($_POST['user'] as $user) {
                        $this->db->where($where, $id);
                        $this->db->update($table, $user);
                }
        }

        function cek_login()
        {
                if (empty($_SESSION['user_name'])) {
                        redirect('login_control/index');
                }
        }


        function tampil_header($tanggal)
        {
                $shift = $this->input->post('shift');
                $query = $this->db->query("SELECT IFNULL(SUM(p.`qty_ok`),0) AS ok , 
                IFNULL(SUM(p.`qty_ng`),0) AS ng , IFNULL(SUM(p.`qty_lt`),0) AS lt , 
                IFNULL(SUM(p.`nett_prod`),0) AS nett_prod ,
                IFNULL(SUM(p.`gross_prod`),0) AS gross_prod  , 
                IFNULL(SUM(p.`ct_standar`),0) AS ct_standar ,
                (IFNULL(SUM(p.`ct_standar`) / SUM(p.`gross_prod`),0)*100) AS persen_Gross , 
                (IFNULL(SUM(p.`ct_standar`) / SUM(p.`nett_prod`),0)*100) AS persen_Nett FROM `productivity_by_day` AS p 
                WHERE p.`tanggal` =  '$tanggal'");
                return $query;
        }

        function tampil_header_byshift($tanggal, $shift)
        {
                if ($shift == 'All') {
                        $query = $this->db->query("SELECT IFNULL(SUM(p.`qty_ok`),0) AS ok , 
                IFNULL(SUM(p.`qty_ng`),0) AS ng , IFNULL(SUM(p.`qty_lt`),0) AS lt , 
                IFNULL(SUM(p.`nett_prod`),0) AS nett_prod ,
                IFNULL(SUM(p.`gross_prod`),0) AS gross_prod  , 
                IFNULL(SUM(p.`ct_standar`),0) AS ct_standar ,
                (IFNULL(SUM(p.`ct_standar`) / SUM(p.`gross_prod`),0)*100) AS persen_Gross , 
                (IFNULL(SUM(p.`ct_standar`) / SUM(p.`nett_prod`),0)*100) AS persen_Nett FROM `productivity_by_day` AS p 
                WHERE p.`tanggal` =  '$tanggal'");
                        return $query;
                } else {
                        $query = $this->db->query("SELECT IFNULL(SUM(p.`qty_ok`),0) AS ok , 
                IFNULL(SUM(p.`qty_ng`),0) AS ng , IFNULL(SUM(p.`qty_lt`),0) AS lt , 
                IFNULL(SUM(p.`nett_prod`),0) AS nett_prod ,
                IFNULL(SUM(p.`gross_prod`),0) AS gross_prod  , 
                IFNULL(SUM(p.`ct_standar`),0) AS ct_standar ,
                (IFNULL(SUM(p.`ct_standar`) / SUM(p.`gross_prod`),0)*100) AS persen_Gross , 
                (IFNULL(SUM(p.`ct_standar`) / SUM(p.`nett_prod`),0)*100) AS persen_Nett FROM `productivity_by_day` AS p 
                WHERE p.`tanggal` =  '$tanggal' AND p.`shift` = '$shift'");
                        return $query;
                }
        }

        function tampil_rekap($tanggal)
        {
                $query = $this->db->query("SELECT p.`tanggal` , p.`shift`,
                SUM(p.running) AS sumRun , SUM(p.man_power) AS sumPower 
                FROM `machine_use` AS p 
                WHERE p.`tanggal` =  '$tanggal'
                GROUP BY p.`tanggal` , p.shift
                ORDER BY p.`tanggal`");
                return $query;
        }

        function tampil_rekap_new($tanggal)
        {
                $query = $this->db->query("SELECT p.`tanggal` , p.`shift`,
                SUM(p.running) AS sumRun , SUM(p.man_power) AS sumPower, q.`total`, q.`total_tidak_hadir`, q.`sisa_mp_hadir`
                FROM `machine_use` AS p 
                LEFT JOIN total_mp AS q ON q.`tanggal` = p.`tanggal` AND q.`shift` = p.`shift`
                WHERE p.`tanggal` =  '$tanggal'
                GROUP BY p.`tanggal` , p.shift
                ORDER BY p.`tanggal`");
                return $query;
        }

        function tampil_rekap_newby_filter($tanggal, $shift)
        {
                $shift = $this->input->post('shift');
                if ($shift == 'All') {
                        $shiftNya = '';
                } else {
                        $shiftNya = 'and q.shift = $shift';
                }
                $query = $this->db->query("SELECT p.`tanggal` , p.`shift`,
                SUM(p.running) AS sumRun , SUM(p.man_power) AS sumPower, q.`total`, q.`total_tidak_hadir`
                FROM `machine_use` AS p 
                LEFT JOIN total_mp AS q ON q.`tanggal` = p.`tanggal` AND q.`shift` = p.`shift`
                WHERE p.`tanggal` =  '$tanggal' $shiftNya
                GROUP BY p.`tanggal` , p.shift
                ORDER BY p.`tanggal`");
                return $query;
        }

        //Get autocomplete get_kodeProductRelease
        public function get_kodeProductRelease($searchTerm = "")
        {
                $this->db->select('*');
                $this->db->where("kode_product LIKE '%" . $searchTerm . "%' OR nama_product LIKE '%" . $searchTerm . "%'");
                $this->db->group_by("kode_product");
                $fetched_records = $this->db->get('t_product');
                $users = $fetched_records->result_array();
                // Initialize Array with fetched data
                $data = array();
                foreach ($users as $user) {
                        $data[] = array("id" => $user['id_product'], "nama" => $user['nama_product'], "text" => $user['kode_product'] . ' (' . $user['nama_product'] . ')');
                }
                return $data;
        }

        //delete dpr online
        public function delete_production_op($id_production)
        {
                $this->db->where('id_production', $id_production);
                $this->db->delete('t_production_op');
        }

        public function delete_production_detail($id_production)
        {
                $this->db->where('id_production', $id_production);
                $this->db->delete('t_production_op_release');
        }

        public function delete_production_op_release($id_production)
        {
                $this->db->where('id_production', $id_production);
                $this->db->delete('t_production_op_dl');
        }

        public function delete_detail_dl($id_dl)
        {
                $this->db->where('id_DL', $id_dl);
                $this->db->delete('t_production_op_dl');
        }

        //update dpr online
        public function update_dpr_online($data, $id)
        {
                return $this->db->update('t_production_op', $data, array('id_production' => $id));
        }

        public function insert_data_ng($data)
        {
                return $this->db->insert('t_production_op_dl', $data);
        }

        public function insert_data_lt($data)
        {
                return $this->db->insert('t_production_op_dl', $data);
        }

        public function simpanBOMKosong()
        {
                foreach ($_POST['ress'] as $ress) {
                        $this->db->set('runner', $this->input->post("runner"));
                        $this->db->set('id_production', $id_production);
                        $this->db->insert('t_production_op_release', $ress);
                }
        }

        //Edit master bom
        public function getDataBOM($id_bom)
        {
                $query = "SELECT * FROM v_master_bom AS q
                        WHERE q.`id_bom` = $id_bom";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0) {
                        return $result;
                } else {
                        return null;
                }
        }

        public function edit_master_bom($data, $id)
        {
                return $this->db->update('t_bom', $data, array('id_bom' => $id));
        }

        //Edit master Product
        public function getDataProduct($id_product)
        {
                $query = "SELECT * FROM t_product AS q
                        WHERE q.`id_product` = $id_product";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0) {
                        return $result;
                } else {
                        return null;
                }
        }

        public function edit_master_product($data, $id)
        {
                return $this->db->update('t_product', $data, array('id_product' => $id));
        }

        //delete master product
        public function delete_master_product($id_product)
        {
                $this->db->where('id_product', $id_product);
                $this->db->delete('t_product');
        }

        public function update_product_discontinue_status($id_product, $status)
        {
                $this->db->where('id_product', $id_product);
                return $this->db->update('t_product', ['discontinue' => $status]);
        }

        public function update_dpr_online_all()
        {
                $id_production  = $this->input->post("id_production");
                $input  = date('Y-m-d h:i:s');
                foreach ($_POST['user'] as $user) {
                        $this->db->set('tanggal_input', $input);
                        $this->db->set('runner', $this->input->post("runner"));
                        $this->db->where('id_production', $id_production);
                        $this->db->update('t_production_op', $user);
                }
                if (isset($_POST['detail'])) {
                        foreach ($_POST['detail'] as $detail) {
                                $this->db->set('id_production', $id_production);
                                $this->db->insert('t_production_op_dl', $detail);
                        }
                }
                if (isset($_POST['detailLT'])) {
                        foreach ($_POST['detailLT'] as $detailLT) {
                                $this->db->set('id_production', $id_production);
                                $this->db->insert('t_production_op_dl', $detailLT);
                        }
                }

                if (isset($_POST['ress'])) {
                        foreach ($_POST['ress'] as $ress) {
                                $this->db->set('runner', $this->input->post("runner"));
                                $this->db->where('id_production', $id_production);
                                $this->db->update('t_production_op_release', $ress);
                        }
                }
        }

        public function tampil_product_bySearch($divisi, $keyword)
        {
                $query = "SELECT * FROM t_product AS q
                        WHERE q.`divisi` = '$divisi' AND q.`kode_product` LIKE '%$keyword%' OR q.`nama_product` LIKE '%$keyword%'";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0) {
                        return $result;
                } else {
                        return $result;
                }
        }

        public function tampil_product_default()
        {
                $query = "SELECT * FROM t_product AS q WHERE q.`cost` = '1' LIMIT 5";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0) {
                        return $result;
                } else {
                        return $result;
                }
        }

        //add master bom
        public function simpanData()
        {
                foreach ($_POST['user'] as $user) {
                        $this->db->insert('t_bom', $user);
                }

                foreach ($_POST['user_detail'] as $user) {
                        $this->db->insert('t_bom_detail', $user);
                }
        }
        //delete master bom
        public function delete_master_bom($id_bom)
        {
                $this->db->where('id_bom', $id_bom);
                $this->db->delete('t_bom');
        }

        public function delete_master_bom_detail($id_bom)
        {
                $this->db->where('id_bom', $id_bom);
                $this->db->delete('t_bom_detail');
        }

        //update BOM
        public function update_master_bom($data, $id)
        {
                return $this->db->update('t_bom', $data, array('id_bom' => $id));
        }

        //update BOM detail
        public function update_master_bom_detail($data_bom_detail, $id)
        {
                return $this->db->update('t_bom_detail', $data_bom_detail, array('id_bom' => $id));
        }

        //Tampil ng lt terbanyak by kanit
        function tampil_ng_lt_bykanit_default()
        {
                $query = $this->db->query("SELECT q.kanit,SUM(q.`qty_ok` + q.`qty_ng`) AS total_produksi, SUM(q.qty_ok) AS total_ok, SUM(q.`qty_ng`) AS total_ng, 
            ROUND(SUM(q.`qty_lt`),2) AS total_lt FROM v_production_op AS q
            WHERE q.`tanggal` = CURDATE()
            GROUP BY q.`kanit`
            ORDER BY SUM(q.`qty_ng`)  DESC");
                return $query;
        }

        function tampil_ng_lt_bykanit_filter($tanggal)
        {
                $query = $this->db->query("SELECT q.kanit,SUM(q.`qty_ok` + q.`qty_ng`) AS total_produksi, SUM(q.qty_ok) AS total_ok, SUM(q.`qty_ng`) AS total_ng, 
            ROUND(SUM(q.`qty_lt`),2) AS total_lt FROM v_production_op AS q
            WHERE q.`tanggal` = '$tanggal'
            GROUP BY q.`kanit`
            ORDER BY SUM(q.`qty_ng`)  DESC");
                return $query;
        }
        public function get_all_kategori_defect()
        {
                return $this->db->get('master_kategori_defect')->result();
        }

        public function add_kategori_defect($nama_kategori)
        {
                return $this->db->insert('master_kategori_defect', ['nama_kategori' => $nama_kategori]);
        }

        public function delete_kategori_defect($id)
        {
                return $this->db->delete('master_kategori_defect', ['id_master_kategori_defect' => $id]);
        }

        public function get_master_mesin_data_with_names()
        {
                $this->db->select('t_no_mesin.*, t_nama_mesin.nama_mesin'); // Select all columns from t_no_mesin and nama_mesin from t_nama_mesin
                $this->db->from('t_no_mesin');
                $this->db->join('t_nama_mesin', 't_no_mesin.id_nama_mesin = t_nama_mesin.id_nama_mesin', 'left'); // Join based on foreign key
                $query = $this->db->get();
                return $query->result_array();
        }

        public function get_machine_names()
        {
                $this->db->select('id_nama_mesin, nama_mesin');
                $this->db->from('t_nama_mesin');
                $query = $this->db->get();
                return $query->result_array(); // Return result as array of associative arrays
        }

        // --- CUTTING TOOL MASTER DATA ---
        public function get_cutting_tools() {
                return $this->db->get('cutting_tools')->result_array();
        }

        public function insert_cutting_tool($code, $code_group) {
                $data = [
                        'code' => $code,
                        'code_group' => $code_group
                ];
                return $this->db->insert('cutting_tools', $data);
        }

        public function delete_cutting_tool($id) {
                $this->db->where('id', $id);
                return $this->db->delete('cutting_tools');
        }

        public function update_cutting_tool($id, $code, $code_group) {
                $data = [
                        'code' => $code,
                        'code_group' => $code_group
                ];
                $this->db->where('id', $id);
                return $this->db->update('cutting_tools', $data);
        }
}
