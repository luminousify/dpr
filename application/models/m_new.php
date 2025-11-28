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
                log_message('debug', 'add_action called for table: ' . $table);
                log_message('debug', '_POST user data: ' . print_r($_POST['user'], true));
                
                if (!isset($_POST['user']) || empty($_POST['user'])) {
                        log_message('error', 'No user data in _POST');
                        throw new Exception('Data user tidak ditemukan');
                }
                
                // Determine required field based on table
                $required_field = null;
                $required_field_label = '';
                if ($table === 't_operator') {
                        $required_field = 'nama_operator';
                        $required_field_label = 'Nama Operator';
                } elseif ($table === 't_product') {
                        // For products, check if either nama_product or kode_product is filled
                        $required_field = 'nama_product';
                        $required_field_label = 'Nama Product atau Kode Product';
                }
                
                $inserted_count = 0;
                $errors = array();
                
                foreach ($_POST['user'] as $index => $user) {
                        log_message('debug', 'Processing user index ' . $index . ': ' . print_r($user, true));
                        
                        // Validate required field based on table
                        $is_valid = false;
                        if ($table === 't_operator') {
                                // For operator table, nama_operator is required
                                $is_valid = !empty($user['nama_operator']) && trim($user['nama_operator']) !== '';
                        } elseif ($table === 't_product') {
                                // For product table, either nama_product or kode_product must be filled
                                $is_valid = (!empty($user['nama_product']) && trim($user['nama_product']) !== '') ||
                                           (!empty($user['kode_product']) && trim($user['kode_product']) !== '');
                        } else {
                                // For other tables, check if at least one field has a value
                                $has_data = false;
                                foreach ($user as $key => $value) {
                                        if (!empty($value) && trim($value) !== '') {
                                                $has_data = true;
                                                break;
                                        }
                                }
                                $is_valid = $has_data;
                        }
                        
                        if (!$is_valid) {
                                log_message('debug', 'Skipping index ' . $index . ' - required field(s) empty');
                                continue;
                        }
                        
                        // Prepare data for insert - convert empty strings to NULL for optional fields
                        $insert_data = array();
                        foreach ($user as $key => $value) {
                                if ($value === '' || $value === null) {
                                        $insert_data[$key] = null;
                                } else {
                                        $insert_data[$key] = trim($value);
                                }
                        }
                        
                        log_message('debug', 'Insert data for index ' . $index . ': ' . print_r($insert_data, true));
                        
                        if (!$this->db->insert($table, $insert_data)) {
                                $error = $this->db->error();
                                log_message('error', 'Database insert failed for index ' . $index . ': ' . print_r($error, true));
                                $errors[] = "Baris " . ($index + 1) . ": " . $error['message'];
                        } else {
                                $inserted_count++;
                                log_message('debug', 'Successfully inserted row ' . $inserted_count);
                        }
                }
                
                if (!empty($errors)) {
                        throw new Exception('Gagal menyimpan beberapa data: ' . implode(' | ', $errors));
                }
                
                if ($inserted_count == 0) {
                        // Generate table-specific error message
                        $error_message = 'Tidak ada data valid yang dapat disimpan.';
                        if ($table === 't_operator') {
                                $error_message .= ' Pastikan field Nama Operator diisi.';
                        } elseif ($table === 't_product') {
                                $error_message .= ' Pastikan field Nama Product atau Kode Product diisi.';
                        } else {
                                $error_message .= ' Pastikan minimal satu field diisi.';
                        }
                        throw new Exception($error_message);
                }
                
                log_message('debug', 'Total inserted: ' . $inserted_count);
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
                // Get NG and productivity data from v_productivity_q1
                $query = $this->db->query("SELECT IFNULL(SUM(p.`total_ok`),0) AS ok , 
                IFNULL(SUM(p.`total_ng`),0) AS ng , 0 AS lt,
                IFNULL((AVG(p.`cyt_quo`) / AVG(p.`gross`)) * 100,0) AS persen_Gross , 
                IFNULL((AVG(p.`cyt_quo`) / AVG(p.`nett`)) * 100,0) AS persen_Nett 
                FROM `v_productivity_q1` AS p 
                WHERE DATE(p.`tanggal`) = '$tanggal'
                AND p.`cyt_quo` IS NOT NULL AND p.`gross` > 0 AND p.`nett` > 0");
                
                $result = $query->row();
                
                // Get LT data from v_production_op directly (convert from minutes to hours)
                $lt_query = $this->db->query("SELECT IFNULL(SUM(qty_lt), 0) AS total_lt FROM v_production_op WHERE DATE(tanggal) = '$tanggal'");
                $lt_result = $lt_query->row();
                
                // Update the LT value in the result (convert minutes to hours for display)
                if ($result && $lt_result) {
                    $result->lt = round($lt_result->total_lt / 60, 2); // Convert minutes to hours
                }
                
                return $query;
        }

        function tampil_header_byshift($tanggal, $shift)
        {
                if ($shift == 'All') {
                        // Get NG and productivity data from v_productivity_q1
                        $query = $this->db->query("SELECT IFNULL(SUM(p.`total_ok`),0) AS ok , 
                IFNULL(SUM(p.`total_ng`),0) AS ng , 0 AS lt,
                IFNULL((AVG(p.`cyt_quo`) / AVG(p.`gross`)) * 100,0) AS persen_Gross , 
                IFNULL((AVG(p.`cyt_quo`) / AVG(p.`nett`)) * 100,0) AS persen_Nett 
                FROM `v_productivity_q1` AS p 
                WHERE DATE(p.`tanggal`) = '$tanggal'
                AND p.`cyt_quo` IS NOT NULL AND p.`gross` > 0 AND p.`nett` > 0");
                        
                        $result = $query->row();
                        
                        // Get LT data from v_production_op directly (convert from minutes to hours)
                        $lt_query = $this->db->query("SELECT IFNULL(SUM(qty_lt), 0) AS total_lt FROM v_production_op WHERE DATE(tanggal) = '$tanggal'");
                        $lt_result = $lt_query->row();
                        
                        // Update the LT value in the result (convert minutes to hours for display)
                        if ($result && $lt_result) {
                            $result->lt = round($lt_result->total_lt / 60, 2); // Convert minutes to hours
                        }
                        
                        return $query;
                } else {
                        // Get NG and productivity data from v_productivity_q1
                        $query = $this->db->query("SELECT IFNULL(SUM(p.`total_ok`),0) AS ok , 
                IFNULL(SUM(p.`total_ng`),0) AS ng , 0 AS lt,
                IFNULL((AVG(p.`cyt_quo`) / AVG(p.`gross`)) * 100,0) AS persen_Gross , 
                IFNULL((AVG(p.`cyt_quo`) / AVG(p.`nett`)) * 100,0) AS persen_Nett 
                FROM `v_productivity_q1` AS p 
                LEFT JOIN (
                    SELECT DISTINCT id_bom, shift FROM t_production_op WHERE DATE(tanggal) = '$tanggal' AND shift = '$shift'
                ) s ON p.id_bom = s.id_bom
                WHERE DATE(p.`tanggal`) = '$tanggal'
                AND p.`cyt_quo` IS NOT NULL AND p.`gross` > 0 AND p.`nett` > 0");
                        
                        $result = $query->row();
                        
                        // Get LT data from v_production_op directly (convert from minutes to hours)
                        $lt_query = $this->db->query("SELECT IFNULL(SUM(qty_lt), 0) AS total_lt FROM v_production_op WHERE DATE(tanggal) = '$tanggal' AND shift = '$shift'");
                        $lt_result = $lt_query->row();
                        
                        // Update the LT value in the result (convert minutes to hours for display)
                        if ($result && $lt_result) {
                            $result->lt = round($lt_result->total_lt / 60, 2); // Convert minutes to hours
                        }
                        
                        return $query;
                }
        }

        function tampil_header_monthly()
        {
                $current_month = date('m');
                $current_year = date('Y');
                
                // Get NG and productivity data from v_productivity_q1
                $query = $this->db->query("SELECT 
                IFNULL(SUM(CASE WHEN MONTH(p.tanggal) = '$current_month' THEN p.total_ok END), 0) AS ok , 
                IFNULL(SUM(CASE WHEN MONTH(p.tanggal) = '$current_month' THEN p.total_ng END), 0) AS ng , 
                0 AS lt, 
                IFNULL((AVG(CASE WHEN MONTH(p.tanggal) = '$current_month' THEN p.cyt_quo END) / AVG(CASE WHEN MONTH(p.tanggal) = '$current_month' THEN p.gross END)) * 100, 0) AS persen_Gross , 
                IFNULL((AVG(CASE WHEN MONTH(p.tanggal) = '$current_month' THEN p.cyt_quo END) / AVG(CASE WHEN MONTH(p.tanggal) = '$current_month' THEN p.nett END)) * 100, 0) AS persen_Nett 
                FROM `v_productivity_q1` AS p 
                WHERE YEAR(p.tanggal) = '$current_year'
                AND p.cyt_quo IS NOT NULL AND p.gross > 0 AND p.nett > 0");
                
                $result = $query->row();
                
                // Get LT data from v_production_op directly (convert from minutes to hours)
                $lt_query = $this->db->query("SELECT IFNULL(SUM(qty_lt), 0) AS total_lt FROM v_production_op WHERE YEAR(tanggal) = '$current_year' AND MONTH(tanggal) = '$current_month'");
                $lt_result = $lt_query->row();
                
                // Update the LT value in the result (convert minutes to hours for display)
                if ($result && $lt_result) {
                    $result->lt = round($lt_result->total_lt / 60, 2); // Convert minutes to hours
                }
                
                return $query;
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
                // Properly group OR conditions with parentheses to ensure divisi filter applies to both conditions
                $query = "SELECT * FROM t_product AS q
                        WHERE q.`divisi` = '$divisi' AND (q.`kode_product` LIKE '%$keyword%' OR q.`nama_product` LIKE '%$keyword%')";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0) {
                        return $result;
                } else {
                        return $result;
                }
        }

        public function tampil_product_default()
        {
                $query = "SELECT * FROM t_product AS q ORDER BY q.`id_product` DESC";
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
                // Validate POST data exists
                if (!isset($_POST['user']) || empty($_POST['user'])) {
                        throw new Exception('Data BOM tidak ditemukan');
                }

                if (!isset($_POST['user_detail']) || empty($_POST['user_detail'])) {
                        throw new Exception('Data Release tidak ditemukan');
                }

                $errors = array();
                $inserted_bom = 0;
                $inserted_detail = 0;

                // Insert BOM data
                foreach ($_POST['user'] as $index => $user) {
                        // Validate required fields
                        if (empty($user['id_product']) || empty($user['nama_bom'])) {
                                $errors[] = "BOM baris " . ($index + 1) . ": id_product dan nama_bom harus diisi";
                                continue;
                        }

                        if (!$this->db->insert('t_bom', $user)) {
                                $error = $this->db->error();
                                $errors[] = "BOM baris " . ($index + 1) . ": " . $error['message'];
                        } else {
                                $inserted_bom++;
                        }
                }

                // Insert BOM detail data
                foreach ($_POST['user_detail'] as $index => $user) {
                        // Validate required fields
                        if (empty($user['id_bom']) || empty($user['id_product'])) {
                                $errors[] = "Release baris " . ($index + 1) . ": id_bom dan id_product harus diisi";
                                continue;
                        }

                        if (!$this->db->insert('t_bom_detail', $user)) {
                                $error = $this->db->error();
                                $errors[] = "Release baris " . ($index + 1) . ": " . $error['message'];
                        } else {
                                $inserted_detail++;
                        }
                }

                if (!empty($errors)) {
                        throw new Exception('Gagal menyimpan beberapa data: ' . implode(' | ', $errors));
                }

                if ($inserted_bom == 0) {
                        throw new Exception('Tidak ada data BOM yang berhasil disimpan');
                }

                if ($inserted_detail == 0) {
                        throw new Exception('Tidak ada data Release yang berhasil disimpan');
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
