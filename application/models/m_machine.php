<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_machine extends CI_Model
{
	 public function __construct()
        {
                $this->load->database();
                $this->load->helper(array('url','html','form'));
        }

        /**
         * Get distinct line values from t_no_mesin.
         * Used to populate Line dropdowns dynamically on machine pages.
         *
         * @return array<int, string>
         */
        public function get_distinct_lines()
        {
            $rows = $this->db
                ->select('line')
                ->from('t_no_mesin')
                ->group_by('line')
                ->order_by('line', 'ASC')
                ->get()
                ->result_array();

            $lines = [];
            foreach ($rows as $row) {
                if (!isset($row['line'])) {
                    continue;
                }
                $val = trim((string) $row['line']);
                if ($val === '') {
                    continue;
                }
                $lines[] = $val;
            }

            return $lines;
        }

        public function tampil_machine_use($table , $tanggal_dari , $tanggal_sampai,$shift,$line)
        {
            if($line == 'All') { $lineNya = ''; } else { $lineNya = $this->db->where('line', $line);}
            $this->db->select('*');
            $this->db->from($table);
            // $this->db->join('t_production_op', 't_production_op.id = blogs.id');
            //$this->db->join('t_production_op as w', 'q.tanggal = w.tanggal','q.shift = w.shift','q.no_mesin = w.mesin', 'left');
            $this->db->where('tanggal >=', $tanggal_dari); 
            $this->db->where('tanggal <=', $tanggal_sampai); 
            $this->db->where('shift', $shift); 
            $lineNya;
            $this->db->order_by('line','ASC');
            $this->db->order_by('no_mesin','ASC');
            return $this->db->get();         
        }

        public function tampil_machine_use_coba($tanggal_dari , $tanggal_sampai,$shift,$line)
        {
            if($line == 'All') { $lineNya = ''; } else { $lineNya = 'and q.line = $line';}
            $query = $this->db->query("SELECT q.*, w.`id_production` FROM operasi_mesin AS q
            LEFT JOIN t_production_op AS w ON q.tanggal = w.`tanggal` AND q.`shift` = w.`shift` AND q.`no_mesin` = w.`mesin`
            WHERE q.`tanggal` >= '$tanggal_dari' AND q.`tanggal` <=  '$tanggal_sampai' AND q.`shift` = $shift $lineNya
            ORDER BY q.`line`, q.`no_mesin` ASC");
            return $query;
        }

        function tampil_mp_hadir($tanggal_dari,$tanggal_sampai,$shift)
        {
            $query = $this->db->query("SELECT SUM(total) AS totals FROM `total_mp` AS p 
            WHERE p.tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai' AND p.shift = $shift
            ORDER BY p.`tanggal` DESC");
            return $query;
        }

        function tampil_mp_tidak_hadir($tanggal_dari,$tanggal_sampai,$shift)
        {
            $query = $this->db->query("SELECT SUM(total_tidak_hadir) AS totals FROM `total_mp` AS p 
            WHERE p.tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai' AND p.shift = $shift
            ORDER BY p.`tanggal` DESC");
            return $query;
        }

        function tampil_sisa_mp_hadir($tanggal_dari,$tanggal_sampai,$shift)
        {
            $query = $this->db->query("SELECT SUM(sisa_mp_hadir) AS sisa FROM `total_mp` AS p 
            WHERE p.tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai' AND p.shift = '$shift'
            ORDER BY p.`tanggal` DESC");
            return $query;
        }

        


        public function tampil_mesin_aktif($table , $divisi , $line)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where('divisi', $divisi); 
            $this->db->where('aktif', 1); 
            $this->db->where('line', $line); 
            $this->db->order_by('no_mesin' , 'ASC');
            return $this->db->get();         
        }

        function add()
        {
                foreach($_POST['user'] as $index => $user)
                {   
                    //Trigger TMS
                    $machine = $_POST['user'][$index]['no_mesin'];
                    //$url = "http://192.168.1.57/tms/Machine/wake/$machine";
                    // $url = "http://192.168.50.14/ws/Machine/websocket_client.php";
                    // $curl = curl_init($url);
                    // curl_setopt($curl, CURLOPT_URL, $url);
                    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);            
                    // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);            
                    // $resp = curl_exec($curl);
                    // curl_close($curl);
                    // var_dump($resp);
                    $this->db->set('tanggal' , $this->input->post('tanggal'));
                    $this->db->set('shift' , $this->input->post('shift'));
                    $this->db->set('group' , $this->input->post('group'));
                    $this->db->set('send' , false);
                    $this->db->insert('machine_use', $user);
                }
        }


        function add_totalMP()
        {
                foreach($_POST['user'] as $user)
                {   
                    $this->db->insert('total_mp', $user);
                }
        }



        function tampil_rekap($tahuns,$bulan)
        {
            $query = $this->db->query("SELECT * , DAY(p.tanggal) AS hasil ,
                SUM(CASE WHEN p.shift = 1 THEN p.running ELSE 0 END) AS MP1 , 
                SUM(CASE WHEN p.shift = 1 THEN p.man_power ELSE 0 END) AS MP1s, 
                SUM(CASE WHEN p.shift = 2 THEN p.running ELSE 0 END) AS MP2 , 
                SUM(CASE WHEN p.shift = 2 THEN p.man_power ELSE 0 END) AS MP2s, 
                SUM(CASE WHEN p.shift = 3 THEN p.running ELSE 0 END) AS MP3  ,
                SUM(CASE WHEN p.shift = 3 THEN p.man_power ELSE 0 END) AS MP3s
                FROM `machine_use` AS p 
                WHERE YEAR(p.`tanggal`) = '$tahuns' AND MONTH(p.`tanggal`) = '$bulan'
                GROUP BY p.`tanggal`
                ORDER BY p.`tanggal`");
         return $query;
        }


        function tampil_totalMP($tahuns,$bulan)
        {
            $query = $this->db->query("SELECT * FROM `total_mp` AS p 
                    WHERE YEAR(p.`tanggal`) = $tahuns AND MONTH(p.`tanggal`) = $bulan
                    ORDER BY p.`tanggal` DESC");
         return $query;
        }


        function copy($tanggal,$group,$line,$divisi)
        {
            $query = $this->db->query("SELECT *,ROUND(SUM((3600/p.`ct_std_prod`)*(p.`cavity_prod`*p.`nwt`))) AS target FROM `v_tampil_data_copy_mesin` AS p
                WHERE p.`tanggal` = '$tanggal' AND p.`group` = '$group' AND p.line = '$line'
                GROUP BY p.`id_machine_use`
                ORDER BY p.`no_mesin` ASC");
         return $query;
        }

         function edit($id)
        {
            $query = $this->db->query("SELECT * FROM `machine_use` AS p  
                LEFT JOIN `select_bom` AS ss ON ss.`id_bom` = p.`id_bom`
                WHERE p.id_machine_use = $id
                ORDER BY p.`no_mesin` ASC");
         return $query;
        }

        function edit_proses($id)
        {
            foreach($_POST['user'] as $user)
                {   
                    $this->db->where('id_machine_use' , $id);
                    $this->db->set('send' , false);
                    $this->db->update('machine_use', $user);
                    
                }
                    $url = "http://192.168.1.57/dpr/c_scheduler/send_notif_controller_edit/$id";
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);            
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);            
                    $resp = curl_exec($curl);
                    curl_close($curl);
        }



        function getCekData()
        {
                $tanggal = $this->input->post('tanggal');
                $group   = $this->input->post('group');
                $shift   = $this->input->post('shift');
                $line   = $this->input->post('line');
              $response = array();
              $this->db->select('*');
              $this->db->where('tanggal', $tanggal);
              $this->db->where('shift', $shift);
              $this->db->where('line', $line);
              //$this->db->where('group', $group);
              $records = $this->db->get('machine_use');
              $response = $records->result_array();

              return $response;
        }





         function cek_login()
            {
                if(empty($_SESSION['user_name']))
                {
                    redirect('login_control/index');
                }
            }


        //Get autocomplete
        public function getTonaseMachine($searchTerm=""){
             $this->db->select('*');
             $this->db->where("no_mesin LIKE '%".$searchTerm."%'");
             $this->db->limit(1);
             $fetched_records = $this->db->get('t_no_mesin');
             $users = $fetched_records->result_array();
             // Initialize Array with fetched data
             $data = array();
             foreach($users as $user){
                 $data[] = array("id"=>$user['tonnase'], "text"=>$user['no_mesin']);
             }
             return $data;
         }

         function search_tonase($title){ 
                $this->db->like('no_mesin', $title , 'both');
                $this->db->limit(5);
                return $this->db->get('t_no_mesin')->result();
        }

        function add_fm(){
            foreach($_POST['user'] as $user)
            {
                $this->db->set('send' , false);
                $this->db->insert('machine_use', $user);
                $insert_id = $this->db->insert_id();
                
            }
            
            // $url = "http://192.168.1.57/dpr/c_scheduler/send_notif_controller_edit/$insert_id";
            // $curl = curl_init($url);
            // curl_setopt($curl, CURLOPT_URL, $url);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);            
            // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);            
            // $resp = curl_exec($curl);
            // curl_close($curl);
            //behaviour for add mold
        }

        function delete_mu($id_machine_use){
                $this->db->where('id_machine_use', $id_machine_use);
                $this->db->delete('machine_use');
        }

        function batch_delete_mu($id_machine_uses){
                if (empty($id_machine_uses) || !is_array($id_machine_uses)) {
                        return false;
                }
                
                // Filter out invalid IDs
                $valid_ids = array_filter($id_machine_uses, function($id) {
                        return is_numeric($id) && $id > 0;
                });
                
                if (empty($valid_ids)) {
                        return false;
                }
                
                $this->db->where_in('id_machine_use', $valid_ids);
                return $this->db->delete('machine_use');
        }

        public function delete_total_mp($id_mp){
                $this->db->where('id_mp', $id_mp);
                $this->db->delete('total_mp');
        }

        //Machine Use
        function machine_use_by_customer_by_ton_by_month_default($bulan,$tahun)
        {
            $query = $this->db->query("SELECT 
            YEAR(q.tanggal) AS `tahun`,
            MONTH(q.tanggal) AS `bulan`,
            q.customer,
            e.tonnase,
            SUM(CASE WHEN e.tonnase = 10 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t1`,
            SUM(CASE WHEN e.tonnase = 40 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t2`,
            SUM(CASE WHEN e.tonnase = 60 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t3`,
            SUM(CASE WHEN e.tonnase = 80 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t4`,
            SUM(CASE WHEN e.tonnase = 90 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t5`,
            SUM(CASE WHEN e.tonnase = 100 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t6`,
            SUM(CASE WHEN e.tonnase = 110 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t7`,
            SUM(CASE WHEN e.tonnase = 120 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t8`,
            SUM(CASE WHEN e.tonnase = 140 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t9`,
            SUM(CASE WHEN e.tonnase = 160 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t10`,
            SUM(CASE WHEN e.tonnase = 180 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t11`,
            SUM(CASE WHEN e.tonnase = 200 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END) AS `t12`
            FROM t_production_op AS q
            LEFT JOIN t_no_mesin AS e ON e.no_mesin = q.mesin
            WHERE MONTH(q.tanggal) = '$bulan' AND YEAR(q.tanggal) = '$tahun'
            GROUP BY q.customer
            ORDER BY e.tonnase ASC");
         return $query;
        }

        function getAvailCapacity($bulan,$tahun)
        {
            $query = $this->db->query("SELECT * FROM year_day AS q
            WHERE `q`.`tahun` = '$tahun' AND `q`.`bulan` = '$bulan'");
         return $query;
        }

         function getTotalMachineByTon($bulan,$tahun)
        {
            $query = $this->db->query("SELECT
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 10 THEN 1 END) AS ton1,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 40 THEN 1 END) AS ton2,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 60 THEN 1 END) AS ton3,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 80 THEN 1 END) AS ton4,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 90 THEN 1 END) AS ton5,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 100 THEN 1 END) AS ton6,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 110 THEN 1 END) AS ton7,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 120 THEN 1 END) AS ton8,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 140 THEN 1 END) AS ton9,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 160 THEN 1 END) AS ton10,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 180 THEN 1 END) AS ton11,
            COUNT(DISTINCT no_mesin,CASE WHEN q.`tanggal` LIKE '$tahun-$bulan%' AND q.`tonnase` = 200 THEN 1 END) AS ton12
            FROM (SELECT
  `w`.`tanggal`          AS `tanggal`,
  `q`.`tonnase`          AS `tonnase`,
  `q`.`no_mesin`         AS `no_mesin`
FROM (`t_production_op` `w`
   LEFT JOIN `t_no_mesin` `q`
     ON ((`w`.`mesin` = `q`.`no_mesin`)))) AS q");
         return $query;
        }

        function getTotalMachine()
        {
            $query = $this->db->query("SELECT 
            COUNT(CASE WHEN q.`tonnase` = 10 THEN 1 END) AS ton1,
            COUNT(CASE WHEN q.`tonnase` = 40 THEN 1 END) AS ton2,
            COUNT(CASE WHEN q.`tonnase` = 60 THEN 1 END) AS ton3,
            COUNT(CASE WHEN q.`tonnase` = 80 THEN 1 END) AS ton4,
            COUNT(CASE WHEN q.`tonnase` = 90 THEN 1 END) AS ton5,
            COUNT(CASE WHEN q.`tonnase` = 100 THEN 1 END) AS ton6,
            COUNT(CASE WHEN q.`tonnase` = 110 THEN 1 END) AS ton7,
            COUNT(CASE WHEN q.`tonnase` = 120 THEN 1 END) AS ton8,
            COUNT(CASE WHEN q.`tonnase` = 140 THEN 1 END) AS ton9,
            COUNT(CASE WHEN q.`tonnase` = 160 THEN 1 END) AS ton10,
            COUNT(CASE WHEN q.`tonnase` = 180 THEN 1 END) AS ton11,
            COUNT(CASE WHEN q.`tonnase` = 200 THEN 1 END) AS ton12
            FROM `t_no_mesin` AS q");
         return $query;
        }

        public function update($data,$id) {
        return $this->db->update('total_mp', $data, array('id_mp' => $id));
    }

    function total_machine_use_byton_bycustomer($tahun)
        {
            $query = $this->db->query("SELECT q.`customer`,
                SUM((CASE WHEN e.tonnase = 10 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t1`,
                SUM((CASE WHEN e.tonnase = 40 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t2`,
                SUM((CASE WHEN e.tonnase = 60 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t3`,
                SUM((CASE WHEN e.tonnase = 80 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t4`,
                SUM((CASE WHEN e.tonnase = 90 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t5`,
                SUM((CASE WHEN e.tonnase = 100 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t6`,
                SUM((CASE WHEN e.tonnase = 110 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t7`,
                SUM((CASE WHEN e.tonnase = 120 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t8`,
                SUM((CASE WHEN e.tonnase = 140 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t9`,
                SUM((CASE WHEN e.tonnase = 160 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t10`,
                SUM((CASE WHEN e.tonnase = 180 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t11`,
                SUM((CASE WHEN e.tonnase = 200 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t12`
            FROM t_production_op AS q
            LEFT JOIN t_no_mesin AS e ON e.no_mesin = q.mesin
            WHERE YEAR(q.`tanggal`) = '$tahun'
            GROUP BY q.customer");
         return $query;
        }

         function getTotalAvHour($tahun)
        {
            $query = $this->db->query("SELECT
            SUM(q.`av_hour1`) AS av1,
            SUM(q.`av_hour2`) AS av2,
            SUM(q.`av_hour3`) AS av3,
            SUM(q.`av_hour4`) AS av4,
            SUM(q.`av_hour5`) AS av5,
            SUM(q.`av_hour6`) AS av6,
            SUM(q.`av_hour7`) AS av7,
            SUM(q.`av_hour8`) AS av8,
            SUM(q.`av_hour9`) AS av9,
            SUM(q.`av_hour10`) AS av10,
            SUM(q.`av_hour11`) AS av11,
            SUM(q.`av_hour12`) AS av12
            FROM `av_hour_by_month_year` AS q
            WHERE q.`tahun` = '$tahun'");
         return $query;
        }

        function getTotalByMonthByCustomer($tahun)
        {
            $query = $this->db->query("SELECT q.`customer`, 
            SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours1,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours2,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours3,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours4,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours5,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours6,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours7,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours8,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours9,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours10,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours11,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS tot_hours12
            FROM t_production_op AS q
            WHERE YEAR(q.`tanggal`) = '$tahun' 
            GROUP BY q.`customer`   
            ");
         return $query;
        }

        function hitungTotalHourPerbulan($tahun)
        {
            $query = $this->db->query("SELECT q.tahun, 
            SUM((CASE WHEN q.`bulan` = 1 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours1,
            SUM((CASE WHEN q.`bulan` = 2 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours2,
            SUM((CASE WHEN q.`bulan` = 3 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours3,
            SUM((CASE WHEN q.`bulan` = 4 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours4,
            SUM((CASE WHEN q.`bulan` = 5 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours5,
            SUM((CASE WHEN q.`bulan` = 6 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours6,
            SUM((CASE WHEN q.`bulan` = 7 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours7,
            SUM((CASE WHEN q.`bulan` = 8 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours8,
            SUM((CASE WHEN q.`bulan` = 9 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours9,
            SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours10,
            SUM((CASE WHEN q.`bulan` = 11 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours11,
            SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`machine_use` ELSE 0 END)) AS tot_hours12
            FROM eff_mesin_new_rev1 AS q
            WHERE q.tahun = '$tahun'    
            ");
         return $query;
        }

        function hitungTotalLTPerbulan($tahun)
        {
            $query = $this->db->query("SELECT 
            SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime1,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime2,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime3,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime4,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime5,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime6,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime7,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime8,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime9,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime10,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime11,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN `q`.`qty_lt` ELSE 0 END)) AS losstime12
            FROM t_production_op AS q
            WHERE YEAR(q.`tanggal`) = '$tahun'   
            ");
         return $query;
        }

        function hitungTotalAVHourPerbulan($tahun)
        {
            $query = $this->db->query("SELECT q.`tahun`,
            SUM((CASE WHEN q.bulan = 1 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour1,
            SUM((CASE WHEN q.bulan = 2 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour2,
            SUM((CASE WHEN q.bulan = 3 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour3,
            SUM((CASE WHEN q.bulan = 4 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour4,
            SUM((CASE WHEN q.bulan = 5 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour5,
            SUM((CASE WHEN q.bulan = 6 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour6,
            SUM((CASE WHEN q.bulan = 7 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour7,
            SUM((CASE WHEN q.bulan = 8 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour8,
            SUM((CASE WHEN q.bulan = 9 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour9,
            SUM((CASE WHEN q.bulan = 10 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour10,
            SUM((CASE WHEN q.bulan = 11 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour11,
            SUM((CASE WHEN q.bulan = 12 THEN (q.total * q.jumlah_mesin) END)) AS total_avhour12
            FROM year_day AS q   
            WHERE q.`tahun` = '$tahun'
            ");
         return $query;
        }


        function machine_use_bycustomer_bymonth_IEI($tahun)
        {
            $query = $this->db->query("SELECT 
            q.`tonnase`,
        SUM((CASE WHEN (q.`bulan` = '1') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t1`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '1') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit1`,
        SUM((CASE WHEN (q.`bulan` = '2') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t2`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '2') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit2`,
        SUM((CASE WHEN (q.`bulan` = '3') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t3`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '3') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit3`,
        SUM((CASE WHEN (q.`bulan` = '4') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t4`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '4') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit4`,
        SUM((CASE WHEN (q.`bulan` = '5') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t5`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '5') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit5`,
        SUM((CASE WHEN (q.`bulan` = '6') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t6`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '6') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit6`,
        SUM((CASE WHEN (q.`bulan` = '7') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t7`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '7') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit7`,
        SUM((CASE WHEN (q.`bulan` = '8') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t8`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '8') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit8`,
        SUM((CASE WHEN (q.`bulan` = '9') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t9`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '9') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit9`,
        SUM((CASE WHEN (q.`bulan` = '10' ) THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t10`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '10') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit10`,
        SUM((CASE WHEN (q.`bulan` = '11') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t11`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '11') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit11`,
        SUM((CASE WHEN (q.`bulan` = '12') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t12`,
        ROUND((SUM((CASE WHEN (q.`bulan` = '12') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit12`
        FROM (SELECT
YEAR(`w`.`tanggal`)    AS `tahun`,
MONTH(`w`.`tanggal`)   AS `bulan`,

`w`.`qty_lt`           AS `qty_lt`,

`w`.`production_time`  AS `production_time`,
`w`.`customer`         AS `customer`,

`q`.`tonnase`          AS `tonnase`
FROM (`t_production_op` `w`
LEFT JOIN `t_no_mesin` `q`
 ON ((`w`.`mesin` = `q`.`no_mesin`)))) AS q
        LEFT JOIN`year_day` AS w ON w.`bulan` = q.`bulan` AND w.`tahun` = q.`tahun`
            WHERE (q.customer = 'PT. Indonesia Epson Industry' AND q.`tahun` = '$tahun') OR (q.customer = 'PT Indonesia Epson Industri' AND q.`tahun` = '$tahun')
            GROUP BY q.tonnase
            ORDER BY q.tonnase ASC  
            ");
         return $query;
        }

        function avail_capacity_machine_total($tahun)
        {
            $query = $this->db->query("SELECT
            SUM((CASE WHEN (q.`bulan` = '1' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour1,
            SUM((CASE WHEN (q.`bulan` = '2' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour2,
            SUM((CASE WHEN (q.`bulan` = '3' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour3,
            SUM((CASE WHEN (q.`bulan` = '4' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour4,
            SUM((CASE WHEN (q.`bulan` = '5' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour5,
            SUM((CASE WHEN (q.`bulan` = '6' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour6,
            SUM((CASE WHEN (q.`bulan` = '7' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour7,
            SUM((CASE WHEN (q.`bulan` = '8' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour8,
            SUM((CASE WHEN (q.`bulan` = '9' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour9,
            SUM((CASE WHEN (q.`bulan` = '10' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour10,
            SUM((CASE WHEN (q.`bulan` = '11' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour11,
            SUM((CASE WHEN (q.`bulan` = '12' AND q.`tahun` = '$tahun') THEN `q`.`total` ELSE 0 END)) AS av_hour12
            FROM year_day AS q  
            ");
         return $query;
        }

        function total_ton_per_month($tahun)
        {
            $query = $this->db->query("SELECT 
                q.`tonnase`, w.`total`,
            SUM((CASE WHEN (q.`bulan` = '1') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t1`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '1') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit1`,
            SUM((CASE WHEN (q.`bulan` = '2') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t2`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '2') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit2`,
            SUM((CASE WHEN (q.`bulan` = '3') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t3`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '3') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit3`,
            SUM((CASE WHEN (q.`bulan` = '4') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t4`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '4') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit4`,
            SUM((CASE WHEN (q.`bulan` = '5') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t5`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '5') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit5`,
            SUM((CASE WHEN (q.`bulan` = '6') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t6`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '6') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit6`,
            SUM((CASE WHEN (q.`bulan` = '7') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t7`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '7') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit7`,
            SUM((CASE WHEN (q.`bulan` = '8') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t8`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '8') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit8`,
            SUM((CASE WHEN (q.`bulan` = '9') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t9`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '9') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit9`,
            SUM((CASE WHEN (q.`bulan` = '10') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t10`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '10') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit10`,
            SUM((CASE WHEN (q.`bulan` = '11') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t11`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '11') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit11`,
            SUM((CASE WHEN (q.`bulan` = '12') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) AS `t12`,
            ROUND((SUM((CASE WHEN (q.`bulan` = '12') THEN (`q`.`production_time` + `q`.`qty_lt`) ELSE 0 END)) / w.`total`),2) AS `unit12`
            FROM (SELECT
YEAR(`w`.`tanggal`)    AS `tahun`,
MONTH(`w`.`tanggal`)   AS `bulan`,

`w`.`qty_lt`           AS `qty_lt`,

`w`.`production_time`  AS `production_time`,
`w`.`customer`         AS `customer`,

`q`.`tonnase`          AS `tonnase`
FROM (`t_production_op` `w`
LEFT JOIN `t_no_mesin` `q`
 ON ((`w`.`mesin` = `q`.`no_mesin`)))) AS q
            LEFT JOIN`year_day` AS w ON w.`bulan` = q.`bulan` AND w.`tahun` = q.`tahun`
            WHERE (q.customer = 'PT. Indonesia Epson Industry' AND q.`tahun` = '$tahun') OR (q.customer = 'PT Indonesia Epson Industri' AND q.`tahun` = '$tahun')
            ORDER BY q.tonnase ASC       
            ");
         return $query;
        }

         function grafikMachineByCustomerByYear($tahun)
        {
            $query = $this->db->query("SELECT YEAR(pp.`tanggal`) AS `tahun`,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech1,
            SUM((CASE WHEN MONTH(pp.tanggal) = 01 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo1,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech2,
            SUM((CASE WHEN MONTH(pp.tanggal) = 02 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo2,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech3,
            SUM((CASE WHEN MONTH(pp.tanggal) = 03 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo3,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech4,
            SUM((CASE WHEN MONTH(pp.tanggal) = 04 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo4,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech5,
            SUM((CASE WHEN MONTH(pp.tanggal) = 05 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo5,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech6,
            SUM((CASE WHEN MONTH(pp.tanggal) = 06 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo6,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech7,
            SUM((CASE WHEN MONTH(pp.tanggal) = 07 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo7,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech8,
            SUM((CASE WHEN MONTH(pp.tanggal) = 08 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo8,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech9,
            SUM((CASE WHEN MONTH(pp.tanggal) = 09 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo9,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech10,
            SUM((CASE WHEN MONTH(pp.tanggal) = 10 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo10,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech11,
            SUM((CASE WHEN MONTH(pp.tanggal) = 11 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo11,
            
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'SOODE OPTIC PTE. LTD.' THEN pp.production_time ELSE 0 END)) AS sodeoptik12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT.Takata Automotive Safety S.I.' THEN pp.production_time ELSE 0 END)) AS taka12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Yamaha Music Manufacture Asia' THEN pp.production_time ELSE 0 END)) AS ymma12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. TOA GALVA Industries' THEN pp.production_time ELSE 0 END)) AS toa12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. TD Automotive Compressor Indonesia' THEN pp.production_time ELSE 0 END)) AS taci12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Shindengen Indonesia' THEN pp.production_time ELSE 0 END)) AS shindengen12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Panasonic Manufacturing Indonesia' THEN pp.production_time ELSE 0 END)) AS panasonic12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Nusa Keihin' THEN pp.production_time ELSE 0 END)) AS nusa12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. NOK Indonesia' THEN pp.production_time ELSE 0 END)) AS nok12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. LUMINA' THEN pp.production_time ELSE 0 END)) AS lumina12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Leoco Indonesia' THEN pp.production_time ELSE 0 END)) AS leoco12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Komodo Armament Indonesia' THEN pp.production_time ELSE 0 END)) AS koi12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. J-TECK Indonesia' THEN pp.production_time ELSE 0 END)) AS jteck12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Indonesia Epson Industry' THEN pp.production_time ELSE 0 END)) AS epson12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Honda Lock Indonesia' THEN pp.production_time ELSE 0 END)) AS honda12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Hamaden Indonesia' THEN pp.production_time ELSE 0 END)) AS hamaden12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. ETA Indonesia' THEN pp.production_time ELSE 0 END)) AS eta12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Denso Indonesia' THEN pp.production_time ELSE 0 END)) AS denso12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Cipta Nusa Farmasi' THEN pp.production_time ELSE 0 END)) AS cipta12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Bando Electric' THEN pp.production_time ELSE 0 END)) AS bando12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Autotech' THEN pp.production_time ELSE 0 END)) AS autotech12,
            SUM((CASE WHEN MONTH(pp.tanggal) = 12 AND pp.customer = 'PT. Abo Madalex Indonesia' THEN pp.production_time ELSE 0 END)) AS abo12
            FROM `t_production_op` AS pp
            WHERE YEAR(pp.`tanggal`) = '$tahun'
            ");
         return $query;
        }

        function grafik_total_machine_use_bycustomer($tahun)
        {
            $query = $this->db->query("SELECT YEAR(pp.`tanggal`) AS `tahun`, pp.`customer`,
            SUM(pp.`production_time` + `pp`.`qty_lt`) AS machine_use
            FROM `t_production_op` AS pp
            WHERE YEAR(pp.`tanggal`) = '$tahun'
            GROUP BY pp.customer
            ORDER BY SUM(pp.`production_time` + `pp`.`qty_lt`) DESC
            ");
         return $query;
        }

        function grafik_total_machine_use_byton($tahun)
        {
            $query = $this->db->query("SELECT YEAR(pp.`tanggal`) AS `tahun`,
            SUM((CASE WHEN pp.tonnase = 10 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton10,
            SUM((CASE WHEN pp.tonnase = 20 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton20,
            SUM((CASE WHEN pp.tonnase = 40 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton40,
            SUM((CASE WHEN pp.tonnase = 60 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton60,
            SUM((CASE WHEN pp.tonnase = 80 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton80,
            SUM((CASE WHEN pp.tonnase = 90 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton90,
            SUM((CASE WHEN pp.tonnase = 100 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton100,
            SUM((CASE WHEN pp.tonnase = 110 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton110,
            SUM((CASE WHEN pp.tonnase = 120 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton120,
            SUM((CASE WHEN pp.tonnase = 140 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton140,
            SUM((CASE WHEN pp.tonnase = 160 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton160,
            SUM((CASE WHEN pp.tonnase = 180 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton180,
            SUM((CASE WHEN pp.tonnase = 200 THEN (pp.production_time + pp.`qty_lt`) ELSE 0 END)) AS ton200
            FROM (SELECT
 
 `w`.`tanggal`          AS `tanggal`,
 YEAR(`w`.`tanggal`)    AS `tahun`,
MONTH(`w`.`tanggal`)   AS `bulan`,
 `w`.`qty_lt`           AS `qty_lt`,

 `w`.`production_time`  AS `production_time`,
 
 `q`.`tonnase`          AS `tonnase`
FROM (`t_production_op` `w`
  LEFT JOIN `t_no_mesin` `q`
    ON ((`w`.`mesin` = `q`.`no_mesin`)))) AS pp
            WHERE YEAR(pp.`tanggal`) = '$tahun'
            ");
         return $query;
        }

        function grafik_machine_use_byton_bymonth($bulan,$tahun)
        {
            $query = $this->db->query(" SELECT YEAR(pp.`tanggal`) AS `tahun`, MONTH(pp.`tanggal`) AS `bulan`, pp.tonnase,
            ROUND(SUM(pp.production_time + pp.`qty_lt`),1) AS machine_use, 
            q.total_mc, w.total, (q.`total_mc` * w.total) AS av_hour, 
            ROUND((SUM(pp.production_time + pp.`qty_lt`) / (q.`total_mc` * w.total))*100,1) AS persentase 
            FROM (SELECT
 
  `w`.`tanggal`          AS `tanggal`,
  YEAR(`w`.`tanggal`)    AS `tahun`,
MONTH(`w`.`tanggal`)   AS `bulan`,
  `w`.`qty_lt`           AS `qty_lt`,
 
  `w`.`production_time`  AS `production_time`,
  
  `q`.`tonnase`          AS `tonnase`
FROM (`t_production_op` `w`
   LEFT JOIN `t_no_mesin` `q`
     ON ((`w`.`mesin` = `q`.`no_mesin`)))) AS pp
            LEFT JOIN `total_mesin_byton` AS q ON q.tonnase = pp.`tonnase` 
            LEFT JOIN year_day AS w ON w.`bulan` = pp.`bulan` AND w.`tahun` = pp.`tahun`
            WHERE MONTH(pp.`tanggal`) = '$bulan' AND YEAR(pp.`tanggal`) = '$tahun'
            GROUP BY pp.tonnase
            ");
         return $query;
        }


        function grafik_machine_use_bycustomer_bymonth($bulan,$tahun)
        {
            $query = $this->db->query("SELECT 
            YEAR(q.`tanggal`) AS `tahun`,
            MONTH(`q`.`tanggal`) AS `bulan`,
            q.customer,
            SUM(q.production_time) AS prod_time,
            SUM(q.qty_lt) AS lt,
            SUM(q.ot_mp) AS ot,
            SUM(`q`.`production_time` + `q`.`qty_lt`) AS `machine_use`,
            w.`total_machine_use` AS total_mc_hour,
            ROUND((SUM(`q`.`production_time` + `q`.`qty_lt`) / w.total_machine_use) * 100,2) AS persen
            FROM t_production_op AS q
            LEFT JOIN `total_mc_use_bymonth` AS w ON w.`bulan` = MONTH(`q`.`tanggal`) AND w.`tahun` = YEAR(q.`tanggal`)
            WHERE MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun'
            GROUP BY q.customer
            ORDER BY SUM(`q`.`production_time` + `q`.`qty_lt`) DESC
            ");
         return $query;
        }

        function layar_monitoring($tanggal,$shift)
        {
            $query = $this->db->query("SELECT *, q.`kp_pr` AS kode_product, q.`np_pr` AS nama_product FROM `operasi_mesin` AS q
            LEFT JOIN t_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`shift` = w.`shift`
            WHERE q.`tanggal` = '$tanggal' AND q.`shift` = '$shift'
            ORDER BY q.`no_mesin` ASC
            ");
         return $query;
        }
        

        function total_running($tanggal,$shift)
        {
            $query = $this->db->query("SELECT COUNT(DISTINCT(q.`no_mesin`)) AS total_running
            FROM `operasi_mesin` AS q
            LEFT JOIN t_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`shift` = w.`shift`
            WHERE q.`tanggal` = '$tanggal' AND q.`shift` = '$shift' AND q.`running` = '1'
            ORDER BY q.`no_mesin` ASC
            ");
         return $query;
        }

        function total_idle($tanggal,$shift)
        {
            $query = $this->db->query("SELECT COUNT(DISTINCT(q.`no_mesin`)) AS total_idle
            FROM `operasi_mesin` AS q
            LEFT JOIN t_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`shift` = w.`shift`
            WHERE q.`tanggal` = '$tanggal' AND q.`shift` = '$shift' AND q.`running` = '0'
            ORDER BY q.`no_mesin` ASC
            ");
         return $query;
        }
        
        //View detail layar
        function view_detail_layar($tanggal,$no_mesin)
        {
            $query = $this->db->query("SELECT q.`tanggal`, q.`no_mesin`,q.`kp_pr`, q.`np_pr`,q.`shift`, w.`qty_ok`, w.`target_mc`
                FROM `operasi_mesin` AS q
                LEFT JOIN t_production_op AS w ON q.`tanggal` = w.`tanggal` AND q.`no_mesin` = w.`mesin` AND q.`shift` = w.`shift`
                WHERE q.`tanggal` = '$tanggal' AND q.`running` = '1' AND q.`no_mesin` = '$no_mesin'
                GROUP BY q.`shift`
                ORDER BY q.`shift` ASC
            ");
         return $query;
        }
        
        function check_prodplan_data($id_bom)
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


}