<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class m_report extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
    $this->load->helper(array('url', 'html', 'form'));
  }


  function tampil_select_group($table, $where)
  {
    $q = "SELECT * FROM `t_defectdanlosstime` WHERE `type` = 'LT' 
							GROUP BY `kategori_defect`
							ORDER BY `kategori_defect` ASC ";
    $query = $result = $this->db->query($q);
    if ($result->num_rows() > 0) {
      return $result->result_array();
    } else {
      return array();
    }
  }

  // Daily OK Report - Get total OK by product for date range
  public function get_daily_ok_report($dari, $sampai, $shift = 'All')
  {
    $shift_condition = ($shift == 'All') ? "" : "AND po.shift = '$shift'";
    
    $query = "SELECT 
                p.kode_product,
                p.nama_product,
                SUM(po.qty_ok) as total_ok,
                SUM(po.qty_ng) as total_ng
              FROM t_production_op po
              LEFT JOIN t_bom b ON po.id_bom = b.id_bom
              LEFT JOIN t_product p ON b.id_product = p.id_product
              WHERE po.tanggal BETWEEN '$dari' AND '$sampai'
              $shift_condition
              GROUP BY p.kode_product, p.nama_product
              ORDER BY total_ok DESC";
    
    return $this->db->query($query);
  }


  public function tampil_production($tahun)
  {
    $query = $this->db->query("SELECT *  
			FROM productivity_by_month_detail AS pp
			WHERE pp.tahun = '$tahun'");
    return $query;
  }

  public function tampil_productionGrafik($tahun)
  {

    $query = $this->db->query("SELECT pp.tahun, pp.bulan,
            AVG((CASE WHEN pp.bulan = 01 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross1,
            AVG((CASE WHEN pp.bulan = 01 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett1,
            AVG((CASE WHEN pp.bulan = 02 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross2,
            AVG((CASE WHEN pp.bulan = 02 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett2,
            AVG((CASE WHEN pp.bulan = 03 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross3,
            AVG((CASE WHEN pp.bulan = 03 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett3,
            AVG((CASE WHEN pp.bulan = 04 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross4,
            AVG((CASE WHEN pp.bulan = 04 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett4,
            AVG((CASE WHEN pp.bulan = 05 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross5,
            AVG((CASE WHEN pp.bulan = 05 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett5,
            AVG((CASE WHEN pp.bulan = 06 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross6,
            AVG((CASE WHEN pp.bulan = 06 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett6,
            AVG((CASE WHEN pp.bulan = 07 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross7,
            AVG((CASE WHEN pp.bulan = 07 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett7,
            AVG((CASE WHEN pp.bulan = 08 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross8,
            AVG((CASE WHEN pp.bulan = 08 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett8,
            AVG((CASE WHEN pp.bulan = 09 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross9,
            AVG((CASE WHEN pp.bulan = 09 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett9,
            AVG((CASE WHEN pp.bulan = 10 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross10,
            AVG((CASE WHEN pp.bulan = 10 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett10,
            AVG((CASE WHEN pp.bulan = 11 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross11,
            AVG((CASE WHEN pp.bulan = 11 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett11,
            AVG((CASE WHEN pp.bulan = 12 THEN ((pp.`max_CTStd2` / pp.`gross_prod`) * 100) ELSE 0 END)) AS persen_gross12,
            AVG((CASE WHEN pp.bulan = 12 THEN ((pp.`max_CTStd2` / pp.`nett_prod`) * 100) ELSE 0 END)) AS persen_nett12,
            w.*
            FROM `productivity_by_month` AS pp
            LEFT JOIN target_produksi AS w ON w.`tahun` = pp.`tahun`
            WHERE pp.tahun = '$tahun'
            GROUP BY pp.`bulan`");
    return $query;
  }

  public function tampil_product($kode_product)
  {
    $query = $this->db->query("SELECT * 
      FROM v_production_op AS pp
      WHERE pp.`kode_product` = '$kode_product'");
    return $query;
  }

  public function tampil_kategoriLT($kategori)
  {
    $query = $this->db->query("SELECT * FROM t_production_op_dl AS q
          WHERE q.type = 'LT' AND q.kategori = '$kategori'");
    return $query;
  }


  function MinNett($tahun, $bulan)
  {
    $query = $this->db->query("
        		SELECT d.kode_product , d.nama_product ,  ROUND(MIN(nett_prod),2) AS min_nett , ROUND(MIN(gross_prod),2) AS min_gross
					FROM `productivity_by_month` AS d
					where d.tahun = '$tahun'
			GROUP BY d.tahun , d.id_product
			LIMIT 10");
    return $query;
  }


  function maxPPM($tahun, $bulan)
  {
    $query = $this->db->query("
        		SELECT * , MAX(ppm) AS max_ppm FROM (
		SELECT YEAR(pd.`tanggal`) AS tahun  , MONTH(pd.`tanggal`) AS bulan , pd.`id_product` , pd.`kode_product` , 
		pd.`nama_product` , pd.nama_proses ,  SUM(pd.qty_ok) AS ok , (SUM(pd.qty_ok)+SUM(pd.qty_ng)) AS total , 
		SUM(pd.qty_ng) AS ng , ROUND((SUM(pd.qty_ng)/(SUM(pd.qty_ok) + SUM(pd.qty_ng))*1000000),0) AS ppm
		FROM `productivity_by_day` AS pd
		GROUP BY YEAR(pd.`tanggal`) , MONTH(pd.`tanggal`)) AS cc 
		WHERE cc.tahun = 2021 AND cc.bulan = 10
		LIMIT 10");
    return $query;
  }

  //Eff Mesin

  public function tampil_EffGrafik($tahun, $bulan)
  {
    $query = $this->db->query("SELECT * from eff_mesin_new_rev1 as ss 
      WHERE ss.tahun = $tahun and ss.bulan = $bulan
      GROUP BY ss.tahun , ss.mesin , ss.bulan");
    return $query;
  }


  public function tampil_EffGrafikYear($tahun)
  {
    $query = $this->db->query("SELECT ss.tahunBulan , ss.bulan , 
          CASE
            WHEN ss.bulan = '1' THEN 'Januari'
            WHEN ss.bulan = '2' THEN 'Februari' 
            WHEN ss.bulan = '3' THEN 'Maret' 
            WHEN ss.bulan = '4' THEN 'April' 
            WHEN ss.bulan = '5' THEN 'Mei' 
            WHEN ss.bulan = '6' THEN 'Juni'
            WHEN ss.bulan = '7' THEN 'Juli'
            WHEN ss.bulan = '8' THEN 'Agustus'
            WHEN ss.bulan = '9' THEN 'September'
            WHEN ss.bulan = '10' THEN 'Oktober'
            WHEN ss.bulan = '11' THEN 'November'  
            ELSE 'Desember' 
          END AS bulan_new,
          SUM(ss.totalNWT) AS total_nwt, ROUND(SUM(ss.totalLT),1) AS total_LT ,
          SUM(ss.totalOT) AS total_OT , 
          SUM(ss.machine_use) AS total_machine_use,
          SUM(ss.production_time) AS total_production_time,
          ROUND(((qq.`total` * qq.jumlah_mesin) - SUM(ss.machine_use)),1) AS total_idle,
          ROUND((((SUM(`ss`.`production_time`) + SUM(ss.totalLT)) / (qq.`total` * qq.jumlah_mesin)) * 100),2) AS `machine_use_persen`,
          qq.*, (qq.`total` * qq.jumlah_mesin) AS total_available_capacity
          FROM eff_mesin_new_rev1 AS ss
          LEFT JOIN year_day AS qq ON qq.`bulan` = ss.`bulan` AND qq.`tahun` = ss.`tahun`
          WHERE ss.tahun = '$tahun'
          GROUP BY ss.tahun , ss.bulan
          ");
    return $query;
  }

  public function eff_mesin_rekap($tahun)
  {
    $query = $this->db->query("SELECT r.`tahunBulan`,q.*,r.`total_machine_use`,r.`machine_use_persen` FROM eff_mesin_rekap_rev1 AS q
          LEFT JOIN eff_mesin_rekap_total_by_month r
         ON q.`bulan` = r.`bulan`
                   WHERE q.`tahun` = '$tahun' AND r.`tahunBulan` LIKE '$tahun%'
				  ");
    return $query;
  }

  public function getTotalMesin()
  {
    $query = $this->db->query("SELECT COUNT(q.`no_mesin`) AS total_mesin FROM t_no_mesin AS q
          ");
    return $query;
  }

  public function eff_mesin_rekap_total()
  {
    $query = $this->db->query("SELECT  q.`mesin`, SUM(q.machine_use) AS total_machine_use, q.total, q.machine_use_persen FROM eff_mesin_new AS q
					GROUP BY `q`.`mesin`
					ORDER BY q.mesin ASC
				  ");
    return $query;
  }


  //Akhir eff mesin

  public function tampil_DefectGrafikLTDefault()
  {
    $query = $this->db->query("SELECT
          DATE_FORMAT(`op`.`tanggal`,'%Y') AS `tahun`,
          DATE_FORMAT(`op`.`tanggal`,'%m') AS `bulan`,
          `ll`.`id_DL`         AS `id_DL`,
          `ll`.`id_production` AS `id_production`,
          `ll`.`nama`          AS `nama`,
          `ll`.`kategori`      AS `kategori`,
          `ll`.`type`          AS `type`,
          `ll`.`satuan`        AS `satuan`,
          `ll`.`qty`           AS `qty`,
          REPLACE(REPLACE(`ll`.`kategori`,' ','-'),'/','-') AS `kategori_new`,
          `op`.`tanggal`       AS `tanggal`,
          SUM(`ll`.`qty`)      AS `qtyLT`,
          e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(ll.`qty` / e.`total_machine_use`) * 100,1) AS persen_lt
        FROM (`t_production_op_dl` `ll`
           LEFT JOIN `t_production_op` `op`
             ON ((`op`.`id_production` = `ll`.`id_production`))
             LEFT JOIN total_mc_use_bymonth AS e
          ON e.`tahun` = DATE_FORMAT(`op`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`op`.`tanggal`,'%m'))
        WHERE ((MONTH(`op`.`tanggal`) = MONTH(CURDATE()))
               AND (YEAR(`op`.`tanggal`) = YEAR(CURDATE()))
               AND (`ll`.`type` = 'LT'))
        GROUP BY `ll`.`kategori`
        ORDER BY `qtyLT` DESC");
    return $query;
  }

  public function tampil_DefectGrafikLT($tahun, $bulan)
  {
    $query = $this->db->query("SELECT
            DATE_FORMAT(`op`.`tanggal`,'%Y') AS `tahun`,
            DATE_FORMAT(`op`.`tanggal`,'%m') AS `bulan`,
            `ll`.`id_DL`         AS `id_DL`,
            `ll`.`id_production` AS `id_production`,
            `ll`.`nama`          AS `nama`,
            `ll`.`kategori`      AS `kategori`,
            `ll`.`type`          AS `type`,
            `ll`.`satuan`        AS `satuan`,
            `ll`.`qty`           AS `qty`,
            REPLACE(REPLACE(`ll`.`kategori`,' ','-'),'/','-') AS `kategori_new`,
            `op`.`tanggal`       AS `tanggal`,
            SUM(`ll`.`qty`)      AS `qtyLT`,
            e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(ll.`qty` / e.`total_machine_use`) * 100,1) AS persen_lt
          FROM (`t_production_op_dl` `ll`
             LEFT JOIN `t_production_op` `op`
               ON ((`op`.`id_production` = `ll`.`id_production`))
               LEFT JOIN total_mc_use_bymonth AS e
            ON e.`tahun` = DATE_FORMAT(`op`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`op`.`tanggal`,'%m'))
          WHERE (YEAR(op.`tanggal`) = '$tahun' AND MONTH(op.`tanggal`) = '$bulan'
                 AND (`ll`.`type` = 'LT'))
          GROUP BY `ll`.`kategori`
          ORDER BY `qtyLT` DESC");
    return $query;
  }



  public function tampil_DefectGrafikYear($tahun)
  {

    $query = $this->db->query("SELECT q.*, w.`totalLT`, w.`totalNG`, ROUND(SUM(w.`totalLT` / q.`total_machine_use`) * 100,1) AS persen_lt
            FROM `total_mc_use_bymonth` AS q
            LEFT JOIN total_losstime_bymonth AS w ON q.`tahun` = w.`tahun` AND q.`bulan` = w.`bulan`
            WHERE q.`tahun` = '$tahun'
            GROUP BY q.`tahun`, q.`bulan`");
    return $query;
  }


  public function tampil_DefectGrafikProductLimit($tahun, $bulan)
  {

    $query = $this->db->query("SELECT DATE_FORMAT(`ss`.`tanggal`,'%Y') AS `tahun`, DATE_FORMAT(`ss`.`tanggal`,'%m') AS `bulan`, ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT,
            e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(ss.totalLT / e.`total_machine_use`) * 100,3) AS persen_lt
            FROM (
              SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , 
              SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
              GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
              LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`ss`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`ss`.`tanggal`,'%m')
            WHERE MONTH(ss.tanggal) = '$bulan' AND YEAR(ss.tanggal) = '$tahun'
            GROUP BY ss.id_product
            ORDER BY MAX(ss.totalLT) DESC
            LIMIT 10");
    return $query;
  }

  public function tampil_DefectGrafikProductLimitDefault()
  {

    $query = $this->db->query("SELECT DATE_FORMAT(`ss`.`tanggal`,'%Y') AS `tahun`, DATE_FORMAT(`ss`.`tanggal`,'%m') AS `bulan`, ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT,
            e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(ss.totalLT / e.`total_machine_use`) * 100,3) AS persen_lt
            FROM (
              SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , 
              SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
              GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
              LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`ss`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`ss`.`tanggal`,'%m')
            WHERE MONTH(ss.tanggal) = MONTH(CURDATE()) AND YEAR(ss.tanggal) = YEAR(CURDATE())
            GROUP BY ss.id_product
            ORDER BY MAX(ss.totalLT) DESC
            LIMIT 10");
    return $query;
  }

  public function tampil_DefectGrafikProduct($tahun, $bulan)
  {

    $query = $this->db->query("SELECT DATE_FORMAT(`ss`.`tanggal`,'%Y') AS `tahun`, DATE_FORMAT(`ss`.`tanggal`,'%m') AS `bulan`,ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT,
           e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(ss.totalLT / e.`total_machine_use`) * 100,3) AS persen_lt
           FROM (
           SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
           GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
           LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`ss`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`ss`.`tanggal`,'%m')
           WHERE MONTH(ss.tanggal) = '$bulan' AND YEAR(ss.tanggal) = '$tahun'
           GROUP BY ss.id_product
           ORDER BY MAX(ss.totalLT) DESC
				");
    return $query;
  }

  public function tampil_DefectGrafikProductDefault()
  {

    $query = $this->db->query("SELECT DATE_FORMAT(`ss`.`tanggal`,'%Y') AS `tahun`, DATE_FORMAT(`ss`.`tanggal`,'%m') AS `bulan`,ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT,
            e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(ss.totalLT / e.`total_machine_use`) * 100,3) AS persen_lt
            FROM (
            SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
            GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
            LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`ss`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`ss`.`tanggal`,'%m')
            WHERE MONTH(ss.tanggal) = MONTH(CURDATE()) AND YEAR(ss.tanggal) = YEAR(CURDATE())
            GROUP BY ss.id_product
            ORDER BY MAX(ss.totalLT) DESC
				");
    return $query;
  }

  //Tampil detail LT
  public function tampil_detailLT($kategori, $tahun, $bulan)
  {

    $query = $this->db->query("SELECT q.nama, q.`kategori`, ROUND(SUM(q.qty),1) AS total_defect, e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(q.`qty` / e.`total_machine_use`) * 100,3) AS persen_lt, q.`satuan`, REPLACE(REPLACE(q.nama, ' ', '-'), '/', '-') AS nama_new FROM t_production_op_dl AS q
			LEFT JOIN t_production_op AS w ON q.id_production = w.id_production
      LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`w`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`w`.`tanggal`,'%m')
			WHERE MONTH(w.tanggal) = '$bulan' AND YEAR(w.tanggal) = '$tahun' AND q.type = 'LT' AND q.kategori = REPLACE('$kategori', '-', ' ')
			GROUP BY q.`nama`");
    return $query;
  }

  public function tampil_detailLT_byproduct($kode_product, $tahun, $bulan)
  {
    $query = $this->db->query("SELECT q.*,REPLACE(REPLACE(w.`nama`, ' ', '-'), '/', '-') AS nama_new, w.`nama`, w.`kategori`,SUM(w.`qty`) AS qty_lt,w.`satuan` FROM v_production_op AS q
					LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
					WHERE q.`kode_product` = '$kode_product'  AND MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun' AND w.type = 'LT'
					GROUP BY w.`nama`
					ORDER BY q.mesin ASC");
    return $query;
  }

  public function tampil_detailLT_bykategori($nama_kategori, $bulan)
  {
    $query = $this->db->query("SELECT w.kp_pr, w.np_pr,w.`tanggal`,w.`mesin`, w.`shift`, w.`operator`, w.`kanit`, q.nama, q.`kategori`, q.qty, q.`satuan`, w.`keterangan` FROM t_production_op_dl AS q
      LEFT JOIN v_production_op AS w ON q.id_production = w.id_production
      WHERE MONTH(w.tanggal) = '$bulan' AND YEAR(w.tanggal) = YEAR(CURDATE()) AND q.type = 'LT' AND q.nama = REPLACE(REPLACE('$nama_kategori', '-', ' '), '/', ' ')");
    return $query;
  }

  public function tampil_detailLT_byproduct_byname($kode_product, $nama_kategori, $bulan)
  {
    $query = $this->db->query("SELECT q.*, w.`nama`, w.`kategori`,w.`qty`,w.`satuan` FROM v_production_op AS q
            LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
            WHERE q.`kode_product` = '$kode_product '  AND MONTH(q.`tanggal`) = $bulan AND YEAR(q.`tanggal`) = YEAR(CURDATE()) AND w.type = 'LT' AND w.nama = REPLACE(REPLACE('$nama_kategori', '-', ' '), '/', ' ')
            ORDER BY q.mesin ASC");
    return $query;
  }


  //Header detail LT
  public function tampil_header_detailLT($kategori)
  {
    $query = "SELECT *, REPLACE(REPLACE(q.kategori, ' ', '-'), '/', '-') AS kategori_new FROM t_production_op_dl AS q
			LEFT JOIN t_production_op AS w ON q.id_production = w.id_production
			WHERE q.type = 'LT' AND q.kategori = REPLACE('$kategori', '-', ' ')";
    $result = $this->db->query($query);
    if ($result->num_rows() > 0) {
      return $result;
    } else {
      return $result;
    }
  }

  //Defect
  public function tampil_DefectGrafikProductLimitDefault_defect()
  {

    $query = $this->db->query("SELECT ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT FROM (
					SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
					GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
					WHERE MONTH(ss.tanggal) = MONTH(CURDATE()) AND YEAR(ss.tanggal) = YEAR(CURDATE())
					GROUP BY ss.id_product
					ORDER BY MAX(ss.totalNG) DESC
					LIMIT 10");
    return $query;
  }

  public function tampil_DefectGrafikProductLimitDefault_defectbyfilter($tahun, $bulan)
  {

    $query = $this->db->query("SELECT ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT FROM (
					SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
					GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
					WHERE MONTH(ss.tanggal) = '$bulan' AND YEAR(ss.tanggal) = '$tahun'
					GROUP BY ss.id_product
					ORDER BY MAX(ss.totalNG) DESC
					LIMIT 10");
    return $query;
  }

  public function tampil_DefectGrafikProductDefault_defect()
  {

    $query = $this->db->query("SELECT ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT FROM (
					SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
					GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
					WHERE MONTH(ss.tanggal) = MONTH(CURDATE()) AND YEAR(ss.tanggal) = YEAR(CURDATE())
					GROUP BY ss.id_product
					ORDER BY MAX(ss.totalNG) DESC
				");
    return $query;
  }

  public function tampil_DefectGrafikProductDefault_defectbyfilter($tahun, $bulan)
  {

    $query = $this->db->query("SELECT ss.* , MAX(ss.totalNG) AS maxNG , MAX(ss.totalLT) AS maxLT FROM (
					SELECT pp.`id_product` , pp.`kode_product` , pp.`nama_product` , pp.`tanggal` ,SUM(pp.`qty_ng`) AS totalNG , SUM(pp.`qty_lt`) AS totalLT FROM `v_production_op` AS pp 
					GROUP BY pp.`id_product` , YEAR(pp.`tanggal`) , MONTH(pp.`tanggal`)) AS ss
					WHERE MONTH(ss.tanggal) = '$bulan' AND YEAR(ss.tanggal) = '$tahun'
					GROUP BY ss.id_product
					ORDER BY MAX(ss.totalNG) DESC
				");
    return $query;
  }


  public function tampil_DefectGrafikNGDefault()
  {
    $query = $this->db->query("SELECT ll.* , op.tanggal , YEAR(op.`tanggal`) AS tahun  , MONTH(op.`tanggal`) AS bulan ,  SUM(ll.qty) AS qtyNG FROM `t_production_op_dl` AS ll 
				LEFT JOIN `v_production_op` AS op ON op.`id_production` = ll.`id_production`
				WHERE MONTH(op.tanggal) = MONTH(CURDATE()) AND YEAR(op.tanggal) = YEAR(CURDATE()) AND ll.type = 'NG'
				GROUP BY ll.`kategori` , YEAR(op.`tanggal`) , MONTH(op.`tanggal`) 
				ORDER BY SUM(ll.qty) DESC");

    return $query;
  }

  public function tampil_DefectGrafik($tahun, $bulan)
  {
    $query = $this->db->query("SELECT ll.* , op.tanggal , YEAR(op.`tanggal`) AS tahun  , MONTH(op.`tanggal`) AS bulan ,  SUM(ll.qty) AS qtyNG FROM `t_production_op_dl` AS ll 
				LEFT JOIN `v_production_op` AS op ON op.`id_production` = ll.`id_production`
				WHERE YEAR(op.`tanggal`) = '$tahun' and MONTH(op.`tanggal`) = '$bulan' AND ll.type = 'NG'
				GROUP BY ll.`kategori` , YEAR(op.`tanggal`) , MONTH(op.`tanggal`) 
				ORDER BY SUM(ll.qty) DESC");

    return $query;
  }

  //Detail Defect
  public function tampil_detailNG($kategori)
  {
    $query = $this->db->query("SELECT *, REPLACE(REPLACE(q.nama, ' ', '-'), '/', '-') AS nama_new FROM t_production_op_dl AS q
			LEFT JOIN t_production_op AS w ON q.id_production = w.id_production
			WHERE q.type = 'NG' AND q.kategori = '$kategori'");
    return $query;
  }

  public function tampil_detailNG_new($kategori, $tahun, $bulan)
  {
    $query = $this->db->query("SELECT q.nama, q.`kategori`, SUM(q.qty) AS total_defect, q.`satuan`, REPLACE(REPLACE(q.nama, ' ', '-'), '/', '-') AS nama_new FROM t_production_op_dl AS q
			LEFT JOIN t_production_op AS w ON q.id_production = w.id_production
			WHERE MONTH(w.tanggal) = '$bulan' AND YEAR(w.tanggal) = '$tahun' AND q.type = 'NG' AND q.kategori = '$kategori'
			GROUP BY q.`nama`");
    return $query;
  }

  public function tampil_detailNG_byproduct($kode_product, $tahun, $bulan)
  {
    $query = $this->db->query("SELECT q.*, w.`nama`, w.`kategori`,SUM(w.`qty`) AS qty_ng,w.`satuan` FROM v_production_op AS q
					LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
					WHERE q.`kode_product` = '$kode_product'  AND MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun' AND w.type = 'NG'
					GROUP BY w.`nama`
					ORDER BY q.mesin ASC");
    return $query;
  }

  public function tampil_detailNG_header_byproduct($kode_product, $bulan)
  {
    $query = $this->db->query("SELECT * FROM t_production_op_dl AS q
			LEFT JOIN v_production_op AS w ON q.id_production = w.id_production
			WHERE w.kode_product = '$kode_product' AND MONTH(w.tanggal) = '$bulan' AND YEAR(w.tanggal) = YEAR(CURDATE()) AND q.type = 'NG'");
    return $query;
  }

  public function tampil_detailNG_bykategori($nama_kategori, $bulan)
  {
    $query = $this->db->query("SELECT w.kp_pr, w.np_pr,w.`tanggal`,w.`mesin`, w.`shift`, w.`operator`, w.`kanit`, q.nama, q.`kategori`, q.qty, q.`satuan` FROM t_production_op_dl AS q
      LEFT JOIN v_production_op AS w ON q.id_production = w.id_production
      WHERE MONTH(w.tanggal) = '$bulan' AND YEAR(w.tanggal) = YEAR(CURDATE()) AND q.type = 'NG' AND q.nama = REPLACE(REPLACE('$nama_kategori', '-', ' '), '/', ' ')");
    return $query;
  }

  //Productivity CT Std
  public function tampil_detail_productivity($tahun)
  {
    $query = $this->db->query("SELECT 
            p.kode_product, p.nama_product,p.`cavity`, p.max_CTStd2,
            (CASE WHEN p.bulan = 01 THEN p.nett_prod ELSE 0 END) AS nett_1,
            (CASE WHEN p.bulan = 01 THEN p.gross_prod ELSE 0 END) AS gross_1,
            (CASE WHEN p.bulan = 01 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett1,
            (CASE WHEN p.bulan = 01 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross1,
            (CASE WHEN p.bulan = 02 THEN p.nett_prod ELSE 0 END) AS nett_2,
            (CASE WHEN p.bulan = 02 THEN p.gross_prod ELSE 0 END) AS gross_2,
            (CASE WHEN p.bulan = 02 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett2,
            (CASE WHEN p.bulan = 02 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross2,
            (CASE WHEN p.bulan = 03 THEN p.nett_prod ELSE 0 END) AS nett_3,
            (CASE WHEN p.bulan = 03 THEN p.gross_prod ELSE 0 END) AS gross_3,
            (CASE WHEN p.bulan = 03 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett3,
            (CASE WHEN p.bulan = 03 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross3,
            (CASE WHEN p.bulan = 04 THEN p.nett_prod ELSE 0 END) AS nett_4,
            (CASE WHEN p.bulan = 04 THEN p.gross_prod ELSE 0 END) AS gross_4,
            (CASE WHEN p.bulan = 04 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett4,
            (CASE WHEN p.bulan = 04 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross4,
            (CASE WHEN p.bulan = 05 THEN p.nett_prod ELSE 0 END) AS nett_5,
            (CASE WHEN p.bulan = 05 THEN p.gross_prod ELSE 0 END) AS gross_5,
            (CASE WHEN p.bulan = 05 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett5,
            (CASE WHEN p.bulan = 05 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross5,
            (CASE WHEN p.bulan = 06 THEN p.nett_prod ELSE 0 END) AS nett_6,
            (CASE WHEN p.bulan = 06 THEN p.gross_prod ELSE 0 END) AS gross_6,
            (CASE WHEN p.bulan = 06 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett6,
            (CASE WHEN p.bulan = 06 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross6,
            (CASE WHEN p.bulan = 07 THEN p.nett_prod ELSE 0 END) AS nett_7,
            (CASE WHEN p.bulan = 07 THEN p.gross_prod ELSE 0 END) AS gross_7,
            (CASE WHEN p.bulan = 07 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett7,
            (CASE WHEN p.bulan = 07 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross7,
            (CASE WHEN p.bulan = 08 THEN p.nett_prod ELSE 0 END) AS nett_8,
            (CASE WHEN p.bulan = 08 THEN p.gross_prod ELSE 0 END) AS gross_8,
            (CASE WHEN p.bulan = 08 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett8,
            (CASE WHEN p.bulan = 08 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross8,
            (CASE WHEN p.bulan = 09 THEN p.nett_prod ELSE 0 END) AS nett_9,
            (CASE WHEN p.bulan = 09 THEN p.gross_prod ELSE 0 END) AS gross_9,
            (CASE WHEN p.bulan = 09 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett9,
            (CASE WHEN p.bulan = 09 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross9,
            (CASE WHEN p.bulan = 10 THEN p.nett_prod ELSE 0 END) AS nett_10,
            (CASE WHEN p.bulan = 10 THEN p.gross_prod ELSE 0 END) AS gross_10,
            (CASE WHEN p.bulan = 10 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett10,
            (CASE WHEN p.bulan = 10 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross10,
            (CASE WHEN p.bulan = 11 THEN p.nett_prod ELSE 0 END) AS nett_11,
            (CASE WHEN p.bulan = 11 THEN p.gross_prod ELSE 0 END) AS gross_11,
            (CASE WHEN p.bulan = 11 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett11,
            (CASE WHEN p.bulan = 11 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross11,
            (CASE WHEN p.bulan = 12 THEN p.nett_prod ELSE 0 END) AS nett_12,
            (CASE WHEN p.bulan = 12 THEN p.gross_prod ELSE 0 END) AS gross_12,
            (CASE WHEN p.bulan = 12 THEN ((p.`max_CTStd2` / p.`nett_prod`) * 100) ELSE 0 END) AS persen_nett12,
            (CASE WHEN p.bulan = 12 THEN ((p.`max_CTStd2` / p.`gross_prod`) * 100) ELSE 0 END) AS persen_gross12
            FROM 
            `productivity_by_month` AS p 
            WHERE p.tahun = '$tahun'");
    return $query;
  }


  //5 Most Losstime by month
  public function tampil_losstime_byname_byfilter($tahun, $bulan)
  {
    $query = $this->db->query("SELECT w.`nama`, w.`kategori`,SUM(w.`qty`) AS qty_lt,w.`satuan` FROM v_production_op AS q
				LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
				WHERE MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun' AND w.`type` = 'LT' 
				GROUP BY w.`nama`
				ORDER BY qty_lt DESC");
    return $query;
  }

  public function tampil_losstime_byname_limit_byfilter($tahun, $bulan)
  {
    $query = $this->db->query("SELECT DATE_FORMAT(`q`.`tanggal`,'%Y') AS `tahun`, DATE_FORMAT(`q`.`tanggal`,'%m') AS `bulan`, w.`nama`, w.`kategori`,SUM(w.`qty`) AS qty_lt,w.`satuan`,
            e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(w.qty / e.`total_machine_use`) * 100,3) AS persen_lt 
            FROM v_production_op AS q
                    LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
                    LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`q`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`q`.`tanggal`,'%m')
                    WHERE MONTH(q.`tanggal`) = '$bulan' AND YEAR(q.`tanggal`) = '$tahun' AND w.`type` = 'LT' 
                    GROUP BY w.`nama`
                    ORDER BY qty_lt DESC
                    LIMIT 5");
    return $query;
  }



  public function tampil_losstime_byname_default()
  {
    $query = $this->db->query("SELECT w.`nama`, w.`kategori`,SUM(w.`qty`) AS qty_lt,w.`satuan` FROM t_production_op AS q
				LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
				WHERE MONTH(q.`tanggal`) = MONTH(CURDATE()) AND YEAR(q.`tanggal`) = YEAR(CURDATE()) AND w.`type` = 'LT' 
				GROUP BY w.`nama`
				ORDER BY qty_lt DESC");
    return $query;
  }

  public function tampil_losstime_byname_limit_default()
  {
    $query = $this->db->query("SELECT DATE_FORMAT(`q`.`tanggal`,'%Y') AS `tahun`, DATE_FORMAT(`q`.`tanggal`,'%m') AS `bulan`, w.`nama`, w.`kategori`,SUM(w.`qty`) AS qty_lt,w.`satuan`,
            e.`total_machine_use` AS `total_machine_use`, ROUND(SUM(w.qty / e.`total_machine_use`) * 100,3) AS persen_lt 
            FROM t_production_op AS q
                    LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.`id_production`
                    LEFT JOIN total_mc_use_bymonth AS e ON e.`tahun` = DATE_FORMAT(`q`.`tanggal`,'%Y') AND e.`bulan` = DATE_FORMAT(`q`.`tanggal`,'%m')
                    WHERE MONTH(q.`tanggal`) = MONTH(CURDATE()) AND YEAR(q.`tanggal`) = YEAR(CURDATE()) AND w.`type` = 'LT' 
                    GROUP BY w.`nama`
                    ORDER BY qty_lt DESC
                    LIMIT 5");
    return $query;
  }


  //Productivity worst nett default
  public function tampil_worst_nett($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`,
            (w.`max_CTStd2` / w.`gross_prod`) * 100 AS persen_gross,
            (w.`max_CTStd2` / w.`nett_prod`) * 100 AS persen_nett
            FROM `productivity_by_month` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            GROUP BY w.`kode_product`
            ORDER BY (w.`max_CTStd2` / w.`nett_prod`) * 100 ASC
            LIMIT 10");
    return $query;
  }

  public function tampil_worst_gross($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`,
            (w.`max_CTStd2` / w.`gross_prod`) * 100 AS persen_gross,
            (w.`max_CTStd2` / w.`nett_prod`) * 100 AS persen_nett
            FROM `productivity_by_month` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            GROUP BY w.`kode_product`
            ORDER BY (w.`max_CTStd2` / w.`gross_prod`) * 100 ASC
            LIMIT 10");
    return $query;
  }

  //view detail worst nett
  public function tampil_detail_worst($kode_product, $bulan)
  {
    $query = $this->db->query("SELECT * FROM v_production_op AS w
            WHERE MONTH(w.`tanggal`) = '$bulan' AND YEAR(w.`tanggal`) = YEAR(CURDATE()) AND w.`kode_product` = '$kode_product'");
    return $query;
  }

  //tampil detail productivity by part by month
  public function tampil_detail_productivity_bypart_bymonth_old($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.qty_ok, w.qty_ng, ROUND(w.qty_nwt) AS nwt, ROUND(w.qty_ot) AS ot,
        ROUND(((w.qty_nwt + w.qty_ot) - w.`production_time` - w.`qty_lt`),1) AS total_cdt, ROUND(w.production_time,1) AS prod_time, w.cavity_std,
        w.`kode_product`, w.`cyt_quo`, w.`nett_prod`, w.`gross_prod`, 
                (w.`cyt_quo` / w.`nett_prod`) * 100 AS persen_nett, (w.`cyt_quo` / w.`gross_prod`) * 100 AS persen_gross
                FROM `productivity_by_month_byquo` AS w
        WHERE MONTH(w.`tanggal`) = '$bulan' AND YEAR(w.`tanggal`) = '$tahun'
        GROUP BY w.`kode_product`");
    return $query;
  }

  public function tampil_detail_productivity_bypart_bymonth($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.*, ROUND((w.`cyt_quo` / w.`nett`) * 100,2) AS persen_nett, ROUND((w.`cyt_quo` / w.`gross`) * 100,2) AS persen_gross
        FROM `v_summary_prod_by_part_v4423` AS w
        WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
        GROUP BY w.`kode_product`");
    return $query;
  }


  //view detail by part bymonth
  public function view_detail_prod_bypart_bymonth($kode_product, $bulan, $tahun)
  {
    $query = $this->db->query("SELECT p.*, q.cyt_quo FROM v_production_op AS p 
            LEFT JOIN t_product AS q ON q.kode_product = p.kode_product
            WHERE p.kode_product = '$kode_product ' AND MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'
            ORDER BY p.tanggal ASC");
    return $query;
  }


  //Productivity by CT Quo
  public function tampil_productionGrafik_byCTQuo($tahun)
  {

    $query = $this->db->query("SELECT w.tahun,
SUM((CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)) AS total_quo2,
SUM((CASE WHEN w.`bulan` = '02' THEN w.`gross` END)) AS total_gross2,
SUM((CASE WHEN w.`bulan` = '02' THEN w.`nett` END)) AS total_nett2,
      ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`nett` END)) * 100) AS persen_nett1,
            ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`gross` END)) * 100) AS persen_gross1,
      ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`nett` END)) * 100) AS persen_nett2,
            ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`gross` END)) * 100) AS persen_gross2,
            ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`nett` END)) * 100) AS persen_nett3,
            ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`gross` END)) * 100) AS persen_gross3,
            ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`nett` END)) * 100) AS persen_nett4,
            ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`gross` END)) * 100) AS persen_gross4,
            ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`nett` END)) * 100) AS persen_nett5,
            ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`gross` END)) * 100) AS persen_gross5,
            ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`nett` END)) * 100) AS persen_nett6,
            ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`gross` END)) * 100) AS persen_gross6,
            ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`nett` END)) * 100) AS persen_nett7,
            ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`gross` END)) * 100) AS persen_gross7,
            ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`nett` END)) * 100) AS persen_nett8,
            ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`gross` END)) * 100) AS persen_gross8,
            ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`nett` END)) * 100) AS persen_nett9,
            ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`gross` END)) * 100) AS persen_gross9,
            ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`nett` END)) * 100) AS persen_nett10,
            ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`gross` END)) * 100) AS persen_gross10,
            ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`nett` END)) * 100) AS persen_nett11,
            ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`gross` END)) * 100) AS persen_gross11,
            ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`nett` END)) * 100) AS persen_nett12,
            ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`gross` END)) * 100) AS persen_gross12,
            q.*
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            LEFT JOIN target_produksi AS q ON q.`tahun` = w.`tahun`
            WHERE w.tahun = '$tahun'");
    return $query;
  }

  public function tampil_detail_productivity_byCTQuo($tahun)
  {
    $query = $this->db->query("SELECT 
            p.kode_product, p.nama_product, p.`cavity`, p.cyt_quo, p.`mesin`,
            (CASE WHEN p.bulan = 01 THEN p.nett ELSE 0 END) AS nett_1,
            (CASE WHEN p.bulan = 01 THEN p.gross ELSE 0 END) AS gross_1,
            (CASE WHEN p.bulan = 01 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett1,
            (CASE WHEN p.bulan = 01 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross1,
            (CASE WHEN p.bulan = 02 THEN p.nett ELSE 0 END) AS nett_2,
            (CASE WHEN p.bulan = 02 THEN p.gross ELSE 0 END) AS gross_2,
            (CASE WHEN p.bulan = 02 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett2,
            (CASE WHEN p.bulan = 02 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross2,
            (CASE WHEN p.bulan = 03 THEN p.nett ELSE 0 END) AS nett_3,
            (CASE WHEN p.bulan = 03 THEN p.gross ELSE 0 END) AS gross_3,
            (CASE WHEN p.bulan = 03 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett3,
            (CASE WHEN p.bulan = 03 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross3,
            (CASE WHEN p.bulan = 04 THEN p.nett ELSE 0 END) AS nett_4,
            (CASE WHEN p.bulan = 04 THEN p.gross ELSE 0 END) AS gross_4,
            (CASE WHEN p.bulan = 04 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett4,
            (CASE WHEN p.bulan = 04 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross4,
            (CASE WHEN p.bulan = 05 THEN p.nett ELSE 0 END) AS nett_5,
            (CASE WHEN p.bulan = 05 THEN p.gross ELSE 0 END) AS gross_5,
            (CASE WHEN p.bulan = 05 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett5,
            (CASE WHEN p.bulan = 05 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross5,
            (CASE WHEN p.bulan = 06 THEN p.nett ELSE 0 END) AS nett_6,
            (CASE WHEN p.bulan = 06 THEN p.gross ELSE 0 END) AS gross_6,
            (CASE WHEN p.bulan = 06 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett6,
            (CASE WHEN p.bulan = 06 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross6,
            (CASE WHEN p.bulan = 07 THEN p.nett ELSE 0 END) AS nett_7,
            (CASE WHEN p.bulan = 07 THEN p.gross ELSE 0 END) AS gross_7,
            (CASE WHEN p.bulan = 07 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett7,
            (CASE WHEN p.bulan = 07 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross7,
            (CASE WHEN p.bulan = 08 THEN p.nett ELSE 0 END) AS nett_8,
            (CASE WHEN p.bulan = 08 THEN p.gross ELSE 0 END) AS gross_8,
            (CASE WHEN p.bulan = 08 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett8,
            (CASE WHEN p.bulan = 08 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross8,
            (CASE WHEN p.bulan = 09 THEN p.nett ELSE 0 END) AS nett_9,
            (CASE WHEN p.bulan = 09 THEN p.gross ELSE 0 END) AS gross_9,
            (CASE WHEN p.bulan = 09 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett9,
            (CASE WHEN p.bulan = 09 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross9,
            (CASE WHEN p.bulan = 10 THEN p.nett ELSE 0 END) AS nett_10,
            (CASE WHEN p.bulan = 10 THEN p.gross ELSE 0 END) AS gross_10,
            (CASE WHEN p.bulan = 10 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett10,
            (CASE WHEN p.bulan = 10 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross10,
            (CASE WHEN p.bulan = 11 THEN p.nett ELSE 0 END) AS nett_11,
            (CASE WHEN p.bulan = 11 THEN p.gross ELSE 0 END) AS gross_11,
            (CASE WHEN p.bulan = 11 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett11,
            (CASE WHEN p.bulan = 11 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross11,
            (CASE WHEN p.bulan = 12 THEN p.nett ELSE 0 END) AS nett_12,
            (CASE WHEN p.bulan = 12 THEN p.gross ELSE 0 END) AS gross_12,
            (CASE WHEN p.bulan = 12 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett12,
            (CASE WHEN p.bulan = 12 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross12
            FROM 
            `v_productivity_detail_bypart_bymesin_bymonth` AS p 
            WHERE p.tahun = '$tahun' AND p.`cyt_quo` IS NOT NULL
            ORDER BY p.`nama_product` ASC");
    return $query;
  }

  public function tampil_worst_nett_byquo($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`, w.gross, w.nett,
            (w.`cyt_quo` / w.`gross`) * 100 AS persen_gross,
            (w.`cyt_quo` / w.`nett`) * 100 AS persen_nett
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            ORDER BY (w.`cyt_quo` / w.`nett`) * 100 ASC
            LIMIT 10");
    return $query;
  }

  public function tampil_worst_nett_byquo_all($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`, w.gross, w.nett,
            (w.`cyt_quo` / w.`gross`) * 100 AS persen_gross,
            (w.`cyt_quo` / w.`nett`) * 100 AS persen_nett
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            ORDER BY (w.`cyt_quo` / w.`nett`) * 100 ASC");
    return $query;
  }

  public function tampil_worst_gross_byquo($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`, w.gross, w.nett,
            (w.`cyt_quo` / w.`gross`) * 100 AS persen_gross,
            (w.`cyt_quo` / w.`nett`) * 100 AS persen_nett
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            ORDER BY (w.`cyt_quo` / w.`gross`) * 100 ASC
            LIMIT 10");
    return $query;
  }

  public function tampil_worst_gross_byquo_all($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`, w.gross, w.nett,
            (w.`cyt_quo` / w.`gross`) * 100 AS persen_gross,
            (w.`cyt_quo` / w.`nett`) * 100 AS persen_nett
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            ORDER BY (w.`cyt_quo` / w.`gross`) * 100 ASC");
    return $query;
  }

  public function tampil_detail_worst_byquo($kode_product, $bulan, $tahun)
  {
    $query = $this->db->query(" SELECT w.*, q.cyt_quo FROM v_production_op AS w
         LEFT JOIN t_product AS q ON q.kode_product = w.kode_product
         WHERE MONTH(w.tanggal) = '$bulan' AND YEAR(w.tanggal) = '$tahun' AND w.`kode_product` = '$kode_product'");
    return $query;
  }

  //Production Qty and PPM
  public function tampil_grafikPPM($tahun)
  {
    $query = $this->db->query("SELECT 
    YEAR(q.tanggal) AS tahun, 
    SUM(
      CASE 
        WHEN MONTH(q.tanggal) = 1 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod1,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 1 THEN 0 
        WHEN MONTH(q.tanggal) = 1 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok1,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 1 THEN 0 
        WHEN MONTH(q.tanggal) = 1 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng1,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 1 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 1 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 1 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm1,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 2 THEN 0 
        WHEN MONTH(q.tanggal) = 2 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod2,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 2 THEN 0 
        WHEN MONTH(q.tanggal) = 2 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok2,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 2 THEN 0 
        WHEN MONTH(q.tanggal) = 2 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng2,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 2 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 2 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 2 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm2,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 3 THEN 0 
        WHEN MONTH(q.tanggal) = 3 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod3,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 3 THEN 0 
        WHEN MONTH(q.tanggal) = 3 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok3,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 3 THEN 0 
        WHEN MONTH(q.tanggal) = 3 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng3,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 3 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 3 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 3 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm3,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 4 THEN 0 
        WHEN MONTH(q.tanggal) = 4 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod4,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 4 THEN 0 
        WHEN MONTH(q.tanggal) = 4 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok4,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 4 THEN 0 
        WHEN MONTH(q.tanggal) = 4 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng4,
         CASE 
           WHEN MONTH(CURRENT_DATE()) = 4 THEN 0 
      ELSE ROUND(
        (SUM((CASE WHEN MONTH(q.tanggal) = 4 THEN q.qty_ng ELSE 0 END)) / 
         SUM((CASE WHEN MONTH(q.tanggal) = 4 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
        ) * 1000000
      )
    END AS ppm4,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 5 THEN 0 
        WHEN MONTH(q.tanggal) = 5 THEN (q.qty_ok + q.qty_ng)
           ELSE 0 
         END
    ) AS total_prod5,
    SUM(
         CASE 
        WHEN MONTH(CURRENT_DATE()) = 5 THEN 0 
        WHEN MONTH(q.tanggal) = 5 THEN q.qty_ok
           ELSE 0 
         END
    ) AS ok5,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 5 THEN 0 
        WHEN MONTH(q.tanggal) = 5 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng5,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 5 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 5 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 5 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm5,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 6 THEN 0 
        WHEN MONTH(q.tanggal) = 6 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod6,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 6 THEN 0 
        WHEN MONTH(q.tanggal) = 6 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok6,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 6 THEN 0 
        WHEN MONTH(q.tanggal) = 6 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng6,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 6 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 6 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 6 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm6,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 7 THEN 0 
        WHEN MONTH(q.tanggal) = 7 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod7,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 7 THEN 0 
        WHEN MONTH(q.tanggal) = 7 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok7,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 7 THEN 0 
        WHEN MONTH(q.tanggal) = 7 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng7,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 7 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 7 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 7 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm7,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 8 THEN 0 
        WHEN MONTH(q.tanggal) = 8 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod8,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 8 THEN 0 
        WHEN MONTH(q.tanggal) = 8 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok8,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 8 THEN 0 
        WHEN MONTH(q.tanggal) = 8 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng8,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 8 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 8 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 8 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm8,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 9 THEN 0 
        WHEN MONTH(q.tanggal) = 9 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod9,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 9 THEN 0 
        WHEN MONTH(q.tanggal) = 9 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok9,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 9 THEN 0 
        WHEN MONTH(q.tanggal) = 9 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng9,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 9 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 9 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 9 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm9,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 10 THEN 0 
        WHEN MONTH(q.tanggal) = 10 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod10,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 10 THEN 0 
        WHEN MONTH(q.tanggal) = 10 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok10,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 10 THEN 0 
        WHEN MONTH(q.tanggal) = 10 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng10,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 10 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 10 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 10 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm10,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 11 THEN 0 
        WHEN MONTH(q.tanggal) = 11 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod11,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 11 THEN 0 
        WHEN MONTH(q.tanggal) = 11 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok11,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 11 THEN 0 
        WHEN MONTH(q.tanggal) = 11 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng11,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 11 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 11 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 11 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm11,
    
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 12 THEN 0 
        WHEN MONTH(q.tanggal) = 12 THEN (q.qty_ok + q.qty_ng)
        ELSE 0 
      END
    ) AS total_prod12,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 12 THEN 0 
        WHEN MONTH(q.tanggal) = 12 THEN q.qty_ok
        ELSE 0 
      END
    ) AS ok12,
    SUM(
      CASE 
        WHEN MONTH(CURRENT_DATE()) = 12 THEN 0 
        WHEN MONTH(q.tanggal) = 12 THEN q.qty_ng
        ELSE 0 
      END
    ) AS ng12,
    CASE 
      WHEN MONTH(CURRENT_DATE()) = 12 THEN 0
      ELSE ROUND(
      (SUM((CASE WHEN MONTH(q.tanggal) = 12 THEN q.qty_ng ELSE 0 END)) / 
       SUM((CASE WHEN MONTH(q.tanggal) = 12 THEN (q.qty_ok + q.qty_ng) ELSE 0 END))
      ) * 1000000
      )
    END AS ppm12
FROM v_production_op AS q
WHERE YEAR(q.tanggal) = '$tahun'
");
    return $query;
  }

  //total prod ok
  public function total_prod_ok($tahun)
  {
    $query = $this->db->query("SELECT YEAR(q.tanggal) AS tahun, 
            SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN `q`.`qty_ok` ELSE 0 END)) AS ok1,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN `q`.`qty_ok` ELSE 0 END)) AS ok2,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN `q`.`qty_ok` ELSE 0 END)) AS ok3,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN `q`.`qty_ok` ELSE 0 END)) AS ok4,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN `q`.`qty_ok` ELSE 0 END)) AS ok5,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN `q`.`qty_ok` ELSE 0 END)) AS ok6,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN `q`.`qty_ok` ELSE 0 END)) AS ok7,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN `q`.`qty_ok` ELSE 0 END)) AS ok8,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN `q`.`qty_ok` ELSE 0 END)) AS ok9,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN `q`.`qty_ok` ELSE 0 END)) AS ok10,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN `q`.`qty_ok` ELSE 0 END)) AS ok11,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN `q`.`qty_ok` ELSE 0 END)) AS ok12
            FROM v_production_op AS q
            WHERE YEAR(q.`tanggal`) = '$tahun'");
    return $query;
  }

  //total prod ng
  public function total_prod_ng($tahun)
  {
    $query = $this->db->query("SELECT YEAR(q.tanggal) AS tahun, 
            SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN `q`.`qty_ng` ELSE 0 END)) AS ng1,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN `q`.`qty_ng` ELSE 0 END)) AS ng2,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN `q`.`qty_ng` ELSE 0 END)) AS ng3,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN `q`.`qty_ng` ELSE 0 END)) AS ng4,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN `q`.`qty_ng` ELSE 0 END)) AS ng5,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN `q`.`qty_ng` ELSE 0 END)) AS ng6,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN `q`.`qty_ng` ELSE 0 END)) AS ng7,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN `q`.`qty_ng` ELSE 0 END)) AS ng8,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN `q`.`qty_ng` ELSE 0 END)) AS ng9,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN `q`.`qty_ng` ELSE 0 END)) AS ng10,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN `q`.`qty_ng` ELSE 0 END)) AS ng11,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN `q`.`qty_ng` ELSE 0 END)) AS ng12
            FROM v_production_op AS q
            WHERE YEAR(q.`tanggal`) = '$tahun'");
    return $query;
  }

  //total prod ok + ng
  public function total_produksi($tahun)
  {
    $query = $this->db->query("SELECT YEAR(q.tanggal) AS tahun, 
            SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod1,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod2,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod3,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod4,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod5,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod6,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod7,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod8,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod9,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod10,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod11,
            SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END)) AS total_prod12
            FROM v_production_op AS q
            WHERE YEAR(q.`tanggal`) = '$tahun'");
    return $query;
  }

  //total prod ok + ng
  public function total_ppm($tahun)
  {
    $query = $this->db->query("SELECT YEAR(q.tanggal) AS tahun, 
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 1 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm1,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 2 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm2,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 3 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm3,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 4 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm4,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 5 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm5,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 6 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm6,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 7 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm7,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 8 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm8,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 9 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm9,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 10 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm10,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 11 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm11,
            ROUND((SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN `q`.`qty_ng` ELSE 0 END)) / SUM((CASE WHEN MONTH(q.`tanggal`) = 12 THEN (`q`.`qty_ok` + `q`.`qty_ng`) ELSE 0 END))) * 1000000) AS ppm12
            FROM v_production_op AS q
            WHERE YEAR(q.`tanggal`) = '$tahun'");
    return $query;
  }

  //ppm_target
  public function ppm_fcost_target($tahun)
  {
    $query = $this->db->query("SELECT * FROM ppm_target AS q
            WHERE q.`tahun` = '$tahun'");
    return $query;
  }

  //f_Cost_target
  public function f_cost_target($tahun)
  {
    $query = $this->db->query("SELECT * FROM f_cost_target AS q
            WHERE q.`tahun` = '$tahun'");
    return $query;
  }

  //detail production qty & ppm by cost
  public function production_qty_and_ppm($tahun)
  {
    $query = $this->db->query("SELECT q.kode_product, q.nama_product, w.`cost`, 
            SUM((CASE WHEN q.`bulan` = 01 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 01 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod1,
            SUM((CASE WHEN q.`bulan` = 01 THEN `q`.`qty_ok` ELSE 0 END)) AS ok1,
            SUM((CASE WHEN q.`bulan` = 01 THEN `q`.`qty_ng` ELSE 0 END)) AS ng1,
            SUM((CASE WHEN q.`bulan` = 02 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 02 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod2,
            SUM((CASE WHEN q.`bulan` = 02 THEN `q`.`qty_ok` ELSE 0 END)) AS ok2,
            SUM((CASE WHEN q.`bulan` = 02 THEN `q`.`qty_ng` ELSE 0 END)) AS ng2,
            SUM((CASE WHEN q.`bulan` = 03 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 03 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod3,
            SUM((CASE WHEN q.`bulan` = 03 THEN `q`.`qty_ok` ELSE 0 END)) AS ok3,
            SUM((CASE WHEN q.`bulan` = 03 THEN `q`.`qty_ng` ELSE 0 END)) AS ng3,
            SUM((CASE WHEN q.`bulan` = 04 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 04 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod4,
            SUM((CASE WHEN q.`bulan` = 04 THEN `q`.`qty_ok` ELSE 0 END)) AS ok4,
            SUM((CASE WHEN q.`bulan` = 04 THEN `q`.`qty_ng` ELSE 0 END)) AS ng4,
            SUM((CASE WHEN q.`bulan` = 05 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 05 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod5,
            SUM((CASE WHEN q.`bulan` = 05 THEN `q`.`qty_ok` ELSE 0 END)) AS ok5,
            SUM((CASE WHEN q.`bulan` = 05 THEN `q`.`qty_ng` ELSE 0 END)) AS ng5,
            SUM((CASE WHEN q.`bulan` = 06 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 06 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod6,
            SUM((CASE WHEN q.`bulan` = 06 THEN `q`.`qty_ok` ELSE 0 END)) AS ok6,
            SUM((CASE WHEN q.`bulan` = 06 THEN `q`.`qty_ng` ELSE 0 END)) AS ng6,
            SUM((CASE WHEN q.`bulan` = 07 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 07 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod7,
            SUM((CASE WHEN q.`bulan` = 07 THEN `q`.`qty_ok` ELSE 0 END)) AS ok7,
            SUM((CASE WHEN q.`bulan` = 07 THEN `q`.`qty_ng` ELSE 0 END)) AS ng7,
            SUM((CASE WHEN q.`bulan` = 08 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 08 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod8,
            SUM((CASE WHEN q.`bulan` = 08 THEN `q`.`qty_ok` ELSE 0 END)) AS ok8,
            SUM((CASE WHEN q.`bulan` = 08 THEN `q`.`qty_ng` ELSE 0 END)) AS ng8,
            SUM((CASE WHEN q.`bulan` = 09 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 09 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod9,
            SUM((CASE WHEN q.`bulan` = 09 THEN `q`.`qty_ok` ELSE 0 END)) AS ok9,
            SUM((CASE WHEN q.`bulan` = 09 THEN `q`.`qty_ng` ELSE 0 END)) AS ng9,
            SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod10,
            SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ok` ELSE 0 END)) AS ok10,
            SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` ELSE 0 END)) AS ng10,
            SUM((CASE WHEN q.`bulan` = 11 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 11 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod11,
            SUM((CASE WHEN q.`bulan` = 11 THEN `q`.`qty_ok` ELSE 0 END)) AS ok11,
            SUM((CASE WHEN q.`bulan` = 11 THEN `q`.`qty_ng` ELSE 0 END)) AS ng11,
            SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ok` ELSE 0 END) + (CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ng` ELSE 0 END)) AS tot_prod12,
            SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ok` ELSE 0 END)) AS ok12,
            SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ng` ELSE 0 END)) AS ng12,
            (SUM(q.qty_ok) + SUM(q.`qty_ng`)) AS total_prod, 
            SUM(q.`qty_ok`) AS qty_ok, 
            SUM(q.`qty_ng`) AS qty_ng, 
            ROUND((SUM(q.`qty_ng`) /  (SUM(q.qty_ok) + SUM(q.`qty_ng`))) * 100,2) AS persen_ng,
            ROUND((SUM(q.`qty_ng`) /  (SUM(q.qty_ok) + SUM(q.`qty_ng`))) * 1000000) AS ppm, 
            ROUND(((SUM((CASE WHEN q.`bulan` = 1 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 1 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total1,
            ROUND(((SUM((CASE WHEN q.`bulan` = 2 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 2 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total2,
            ROUND(((SUM((CASE WHEN q.`bulan` = 3 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 3 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total3,
            ROUND(((SUM((CASE WHEN q.`bulan` = 4 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 4 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total4,
            ROUND(((SUM((CASE WHEN q.`bulan` = 5 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 5 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total5,
            ROUND(((SUM((CASE WHEN q.`bulan` = 6 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 6 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total6,
            ROUND(((SUM((CASE WHEN q.`bulan` = 7 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 7 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total7,
            ROUND(((SUM((CASE WHEN q.`bulan` = 8 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 8 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total8,
            ROUND(((SUM((CASE WHEN q.`bulan` = 9 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 9 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total9,
            ROUND(((SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total10,
            ROUND(((SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total11,
            ROUND(((SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total12,
            ROUND(((SUM(q.qty_ok) + SUM(q.`qty_ng`)) * w.cost)) AS sub_total
            FROM `productivity_by_month` AS q
            LEFT JOIN t_product AS w ON w.`kode_product` = q.`kode_product`
            WHERE q.`tahun` = '$tahun'
            GROUP BY q.`kode_product`");
    return $query;
  }

  //total prod qty and ppm
  public function total_prod_qty_and_ppm($tahun)
  {
    $query = $this->db->query("SELECT tahun,
            SUM(tot_prod1) AS total_prod1, SUM(ok1) AS total_ok1, SUM(ng1) AS total_ng1,
            SUM(tot_prod2) AS total_prod2, SUM(ok2) AS total_ok2, SUM(ng2) AS total_ng2,
            SUM(tot_prod3) AS total_prod3, SUM(ok3) AS total_ok3, SUM(ng3) AS total_ng3,
            SUM(tot_prod4) AS total_prod4, SUM(ok4) AS total_ok4, SUM(ng4) AS total_ng4,
            SUM(tot_prod5) AS total_prod5, SUM(ok5) AS total_ok5, SUM(ng5) AS total_ng5,
            SUM(tot_prod6) AS total_prod6, SUM(ok6) AS total_ok6, SUM(ng6) AS total_ng6,
            SUM(tot_prod7) AS total_prod7, SUM(ok7) AS total_ok7, SUM(ng7) AS total_ng7,
            SUM(tot_prod8) AS total_prod8, SUM(ok8) AS total_ok8, SUM(ng8) AS total_ng8,
            SUM(tot_prod9) AS total_prod9, SUM(ok9) AS total_ok9, SUM(ng9) AS total_ng9,
            SUM(tot_prod10) AS total_prod10, SUM(ok10) AS total_ok10, SUM(ng10) AS total_ng10,
            SUM(tot_prod11) AS total_prod11, SUM(ok11) AS total_ok11, SUM(ng11) AS total_ng11,
            SUM(tot_prod12) AS total_prod12, SUM(ok12) AS total_ok12, SUM(ng12) AS total_ng12,
            SUM(total_prod) AS total_prod,
            SUM(qty_ok) AS total_ok,
            SUM(qty_ng) AS total_ng,
            SUM(persen_ng) AS total_persen_ng,
            SUM(ppm) AS total_ppm,
            SUM(sub_total1) AS total_sub_total1,
            SUM(sub_total2) AS total_sub_total2,
            SUM(sub_total3) AS total_sub_total3,
            SUM(sub_total4) AS total_sub_total4,
            SUM(sub_total5) AS total_sub_total5,
            SUM(sub_total6) AS total_sub_total6,
            SUM(sub_total7) AS total_sub_total7,
            SUM(sub_total8) AS total_sub_total8,
            SUM(sub_total9) AS total_sub_total9,
            SUM(sub_total10) AS total_sub_total10,
            SUM(sub_total11) AS total_sub_total11,
            SUM(sub_total12) AS total_sub_total12
            FROM `ppm_detail`
            WHERE tahun = '$tahun'");
    return $query;
  }


  //Worst Defect byPPM
  public function prod_qty_bymonth($bulan, $tahun)
  {
    $query = $this->db->query("SELECT q.tahun, q.bulan,
            (SUM(q.`qty_ok`) + SUM(q.qty_ng)) AS total_prod,
            SUM(q.`qty_ng`) AS total_ng
            FROM`productivity_by_month` AS q
            WHERE q.`bulan` = '$bulan' AND q.`tahun` = '$tahun'");
    return $query;
  }

  //Worst Defect byPPM
  public function worst_10_ng($bulan, $tahun)
  {
    $query = $this->db->query("SELECT q.`kode_product`, q.`nama_product`, q.`qty_ok`, q.`qty_ng`, SUM(q.`qty_ok` + q.`qty_ng`) AS total_prod 
            FROM `productivity_by_month` AS q
            WHERE q.`bulan` = '$bulan' AND q.`tahun` = '$tahun'
            GROUP BY q.`kode_product`
            ORDER BY q.`qty_ng` DESC
            LIMIT 10");
    return $query;
  }

  //total by limit 10
  public function total_bylimit($bulan, $tahun)
  {
    $query = $this->db->query("SELECT SUM(qty_ng) AS total_ng, SUM(qty_ng + qty_ok) AS total_prod
            FROM (SELECT productivity_by_month.qty_ng, productivity_by_month.qty_ok FROM `productivity_by_month` WHERE bulan = '$bulan' AND tahun = '$tahun' 
            ORDER BY productivity_by_month.qty_ng DESC LIMIT 10) AS subt");
    return $query;
  }

  //Detail Prod qty PPm
  public function detail_prod_qty_ppm($tahun)
  {
    $query = $this->db->query("SELECT q.kode_product, q.nama_product, w.`cost`, (SUM(q.qty_ok) + SUM(q.`qty_ng`)) AS total_prod, q.`qty_ok`, q.`qty_ng`, 
              ROUND((SUM(q.`qty_ng`) /  (SUM(q.qty_ok) + SUM(q.`qty_ng`))) * 100,2) AS persen_ng,
              ROUND((SUM(q.`qty_ng`) /  (SUM(q.qty_ok) + SUM(q.`qty_ng`))) * 1000000) AS ppm, 
              ROUND(((SUM((CASE WHEN q.`bulan` = 1 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 1 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total1,
              ROUND(((SUM((CASE WHEN q.`bulan` = 2 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 2 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total2,
              ROUND(((SUM((CASE WHEN q.`bulan` = 3 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 3 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total3,
              ROUND(((SUM((CASE WHEN q.`bulan` = 4 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 4 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total4,
              ROUND(((SUM((CASE WHEN q.`bulan` = 5 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 5 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total5,
              ROUND(((SUM((CASE WHEN q.`bulan` = 6 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 6 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total6,
              ROUND(((SUM((CASE WHEN q.`bulan` = 7 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 7 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total7,
              ROUND(((SUM((CASE WHEN q.`bulan` = 8 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 8 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total8,
              ROUND(((SUM((CASE WHEN q.`bulan` = 9 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 9 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total9,
              ROUND(((SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total10,
              ROUND(((SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total11,
              ROUND(((SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ok` ELSE 0 END)) + SUM((CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ng` ELSE 0 END))) * w.cost)) AS sub_total12,
              ROUND(((SUM(q.qty_ok) + SUM(q.`qty_ng`)) * w.cost)) AS sub_total
              FROM `productivity_by_month` AS q
              LEFT JOIN t_product AS w ON w.`kode_product` = q.`kode_product`
              WHERE q.`tahun` = '$tahun'
              GROUP BY q.`kode_product`");
    return $query;
  }


  // F-Cost Internal Defect (IDR)
  // Note: Product costs (w.cost) are stored in Indonesian Rupiah (IDR), not USD
  // Returns monthly totals of failure cost = qty_ng * cost (in IDR)
  public function f_cost_int_defect($tahun)
  {
    $query = $this->db->query("SELECT q.`tahun`,
            ROUND(SUM(CASE WHEN q.`bulan` = 1 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total1,
            ROUND(SUM(CASE WHEN q.`bulan` = 2 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total2,
            ROUND(SUM(CASE WHEN q.`bulan` = 3 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total3,
            ROUND(SUM(CASE WHEN q.`bulan` = 4 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total4,
            ROUND(SUM(CASE WHEN q.`bulan` = 5 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total5,
            ROUND(SUM(CASE WHEN q.`bulan` = 6 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total6,
            ROUND(SUM(CASE WHEN q.`bulan` = 7 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total7,
            ROUND(SUM(CASE WHEN q.`bulan` = 8 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total8,
            ROUND(SUM(CASE WHEN q.`bulan` = 9 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total9,
            ROUND(SUM(CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total10,
            ROUND(SUM(CASE WHEN q.`bulan` = 11 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total11,
            ROUND(SUM(CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total12
            FROM `productivity_by_month` AS q
            LEFT JOIN t_product AS w ON w.`kode_product` = q.`kode_product`
            WHERE q.`tahun` = '$tahun'");
    return $query;
  }


  //Performance kanit
  public function tampil_ng_lt_bykanit_filter($dari, $sampai, $line)
  {
    $query = $this->db->query("SELECT q.kanit,SUM(q.`qty_ok` + q.`qty_ng`) AS total_produksi, SUM(q.qty_ok) AS total_ok, SUM(q.`qty_ng`) AS total_ng, 
            ROUND(SUM(q.qty_lt),2) AS total_lt, w.`line`,
            REPLACE(REPLACE(`q`.`kanit`,' ','-'),'/','-') AS `kanit_new` FROM v_production_op AS q
            LEFT JOIN t_operator AS w ON q.`kanit` = w.`nama_operator`
            WHERE q.`tanggal` BETWEEN '$dari' AND '$sampai' AND w.`line` = '$line'
            GROUP BY q.`kanit`
            ORDER BY SUM(q.`qty_ng`)  DESC");
    return $query;
  }
  //Detail NG by performance kanit
  public function detail_ng_by_kanit($dari, $sampai, $kanit)
  {
    $query = $this->db->query("SELECT q.*,w.*, SUM(w.qty) AS total_ng FROM t_production_op AS q
            LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.id_production
            WHERE q.`tanggal` BETWEEN '$dari' AND '$sampai' AND q.`kanit` = REPLACE('$kanit','-',' ') AND w.type = 'NG'
            GROUP BY w.nama");
    return $query;
  }

  public function detail_lt_by_kanit($dari, $sampai, $kanit)
  {
    $query = $this->db->query("SELECT q.*,w.*, ROUND(SUM(w.qty),2) AS total_lt FROM t_production_op AS q
            LEFT JOIN t_production_op_dl AS w ON q.`id_production` = w.id_production
            WHERE q.`tanggal` BETWEEN '$dari' AND '$sampai' AND q.`kanit` = REPLACE('$kanit','-',' ') AND w.type = 'LT'
            GROUP BY w.nama");
    return $query;
  }

  //REVISI PRODUCTIVITY BY KUARTAL

  //KUARTAL I
  public function productivity($tahun)
  {
    $query = $this->db->query("SELECT w.tahun,
              ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`nett` END)) * 100) AS persen_nett1,
              ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`gross` END)) * 100) AS persen_gross1,
              ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`nett` END)) * 100) AS persen_nett2,
              ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`gross` END)) * 100) AS persen_gross2,
              ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`nett` END)) * 100) AS persen_nett3,
              ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`gross` END)) * 100) AS persen_gross3,
              ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`nett` END)) * 100) AS persen_nett4,
              ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`gross` END)) * 100) AS persen_gross4,
              q.*
              FROM `v_productivity_q1` AS w
              LEFT JOIN target_produksi AS q ON q.`tahun` = w.`tahun`
              WHERE w.tahun = '$tahun'
              GROUP BY w.tahun");
    return $query;
  }

  public function productivity_q1($tahun)
  {
    $query = $this->db->query("SELECT MAX(w.tahun) as tahun,
              ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`nett` END)) * 100) AS persen_nett1,
              ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`gross` END)) * 100) AS persen_gross1,
              ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`nett` END)) * 100) AS persen_nett2,
              ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`gross` END)) * 100) AS persen_gross2,
              ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`nett` END)) * 100) AS persen_nett3,
              ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`gross` END)) * 100) AS persen_gross3,
              ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`nett` END)) * 100) AS persen_nett4,
              ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`gross` END)) * 100) AS persen_gross4,
              ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`nett` END)) * 100) AS persen_nett5,
              ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`gross` END)) * 100) AS persen_gross5,
              ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`nett` END)) * 100) AS persen_nett6,
              ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`gross` END)) * 100) AS persen_gross6,
              ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`nett` END)) * 100) AS persen_nett7,
              ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`gross` END)) * 100) AS persen_gross7,
              ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`nett` END)) * 100) AS persen_nett8,
              ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`gross` END)) * 100) AS persen_gross8,
              ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`nett` END)) * 100) AS persen_nett9,
              ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`gross` END)) * 100) AS persen_gross9,
              ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`nett` END)) * 100) AS persen_nett10,
              ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`gross` END)) * 100) AS persen_gross10,
              ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`nett` END)) * 100) AS persen_nett11,
              ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`gross` END)) * 100) AS persen_gross11,
              ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`nett` END)) * 100) AS persen_nett12,
              ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`gross` END)) * 100) AS persen_gross12,
              MAX(q.target_nett) as target_nett,
              MAX(q.target_gross) as target_gross
              FROM `v_productivity_q1` AS w
              LEFT JOIN target_produksi AS q ON q.`tahun` = w.`tahun`
              WHERE w.tahun = '$tahun'
              GROUP BY w.tahun");
    return $query;
  }

  public function productivity_q($tahun, $month = null)
{
    // default to December if $month is null or out of range
    $tahun = (int) $tahun;
    if ($tahun < 1900 || $tahun > 3000) {
        throw new InvalidArgumentException("Invalid year: {$tahun}");
    }

    // build the dynamic SELECT parts
    $select_parts = [];
    for ($m = 1; $m <= 12; $m++) {
        // zero-pad month to two digits
        $mm = str_pad($m, 2, '0', STR_PAD_LEFT);
        $select_parts[] = "((AVG(CASE WHEN w.`bulan` = '{$mm}' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '{$mm}' THEN w.`nett` END)) * 100) AS persen_nett{$m}";
        $select_parts[] = "((AVG(CASE WHEN w.`bulan` = '{$mm}' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '{$mm}' THEN w.`gross` END)) * 100) AS persen_gross{$m}";
    }

    // join all the SELECT parts with commas
    $dynamic_select = implode(",\n      ", $select_parts);

    // assemble the full query
    $sql = "
      SELECT
        w.tahun,
        {$dynamic_select},
        q.*
      FROM `v_productivity_q1` AS w
      LEFT JOIN `target_produksi` AS q
        ON q.`tahun` = w.`tahun`
      WHERE w.`tahun` = ?
    ";

    // run the query with binding for safety
    $query = $this->db->query($sql, [ $tahun ]);

    return $query;
}


  //KUARTAL II
  public function productivity_q2($tahun)
  {
    $query = $this->db->query("SELECT w.tahun,
              ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`nett` END)) * 100) AS persen_nett5,
              ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`gross` END)) * 100) AS persen_gross5,
              ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`nett` END)) * 100) AS persen_nett6,
              ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`gross` END)) * 100) AS persen_gross6,
              ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`nett` END)) * 100) AS persen_nett7,
              ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`gross` END)) * 100) AS persen_gross7,
              ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`nett` END)) * 100) AS persen_nett8,
              ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`gross` END)) * 100) AS persen_gross8,
              q.*
              FROM `v_productivity_q2` AS w
              LEFT JOIN target_produksi AS q ON q.`tahun` = w.`tahun`
              WHERE w.tahun = '$tahun'
              GROUP BY w.tahun");
    return $query;
  }

  //KUARTAL III
  public function productivity_q3($tahun)
  {
    $query = $this->db->query("SELECT w.tahun,
              ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`nett` END)) * 100) AS persen_nett9,
              ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`gross` END)) * 100) AS persen_gross9,
              ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`nett` END)) * 100) AS persen_nett10,
              ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`gross` END)) * 100) AS persen_gross10,
              ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`nett` END)) * 100) AS persen_nett11,
              ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`gross` END)) * 100) AS persen_gross11,
              ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`nett` END)) * 100) AS persen_nett12,
              ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_quo` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`gross` END)) * 100) AS persen_gross12,
              q.*
              FROM `v_productivity_q3` AS w
              LEFT JOIN target_produksi AS q ON q.`tahun` = w.`tahun`
              WHERE w.tahun = '$tahun'
              GROUP BY w.tahun");
    return $query;
  }

  //Update Prod Qty & PPM
  public function total_prod_qty_and_ppm_q1($tahun)
  {
    $query = $this->db->query("SELECT tahun,
            SUM(tot_prod1) AS total_prod1, SUM(ok1) AS total_ok1, SUM(ng1) AS total_ng1,
            SUM(tot_prod2) AS total_prod2, SUM(ok2) AS total_ok2, SUM(ng2) AS total_ng2,
            SUM(tot_prod3) AS total_prod3, SUM(ok3) AS total_ok3, SUM(ng3) AS total_ng3,
            SUM(tot_prod4) AS total_prod4, SUM(ok4) AS total_ok4, SUM(ng4) AS total_ng4,
            SUM(total_prod) AS total_prod,
            SUM(qty_ok) AS total_ok,
            SUM(qty_ng) AS total_ng,
            SUM(persen_ng) AS total_persen_ng,
            SUM(ppm) AS total_ppm,
            SUM(sub_total1) AS total_sub_total1,
            SUM(sub_total2) AS total_sub_total2,
            SUM(sub_total3) AS total_sub_total3,
            SUM(sub_total4) AS total_sub_total4
            FROM `ppm_detail_q1`
            WHERE tahun = '$tahun'");
    return $query;
  }

  public function total_prod_qty_and_ppm_q2($tahun)
  {
    $query = $this->db->query("SELECT tahun,
            SUM(tot_prod5) AS total_prod5, SUM(ok5) AS total_ok5, SUM(ng5) AS total_ng5,
            SUM(tot_prod6) AS total_prod6, SUM(ok6) AS total_ok6, SUM(ng6) AS total_ng6,
            SUM(tot_prod7) AS total_prod7, SUM(ok7) AS total_ok7, SUM(ng7) AS total_ng7,
            SUM(tot_prod8) AS total_prod8, SUM(ok8) AS total_ok8, SUM(ng8) AS total_ng8,
            SUM(total_prod) AS total_prod,
            SUM(qty_ok) AS total_ok,
            SUM(qty_ng) AS total_ng,
            SUM(persen_ng) AS total_persen_ng,
            SUM(ppm) AS total_ppm,
            SUM(sub_total5) AS total_sub_total5,
            SUM(sub_total6) AS total_sub_total6,
            SUM(sub_total7) AS total_sub_total7,
            SUM(sub_total8) AS total_sub_total8
            FROM `ppm_detail_q2`
            WHERE tahun = '$tahun'");
    return $query;
  }

  public function total_prod_qty_and_ppm_q3($tahun)
  {
    $query = $this->db->query("SELECT tahun,
            SUM(tot_prod9) AS total_prod9, SUM(ok9) AS total_ok9, SUM(ng9) AS total_ng9,
            SUM(tot_prod10) AS total_prod10, SUM(ok10) AS total_ok10, SUM(ng10) AS total_ng10,
            SUM(tot_prod11) AS total_prod11, SUM(ok11) AS total_ok11, SUM(ng11) AS total_ng11,
            SUM(tot_prod12) AS total_prod12, SUM(ok12) AS total_ok12, SUM(ng12) AS total_ng12,
            SUM(total_prod) AS total_prod,
            SUM(qty_ok) AS total_ok,
            SUM(qty_ng) AS total_ng,
            SUM(persen_ng) AS total_persen_ng,
            SUM(ppm) AS total_ppm,
            SUM(sub_total9) AS total_sub_total9,
            SUM(sub_total10) AS total_sub_total10,
            SUM(sub_total11) AS total_sub_total11,
            SUM(sub_total12) AS total_sub_total12
            FROM `ppm_detail_q3`
            WHERE tahun = '$tahun'");
    return $query;
  }

  // F-Cost Internal Defect Q1 (IDR) - Months 1-4
  // Note: Product costs (w.cost) are stored in Indonesian Rupiah (IDR)
  public function f_cost_int_defect_q1($tahun)
  {
    $query = $this->db->query("SELECT q.`tahun`,
            ROUND(SUM(CASE WHEN q.`bulan` = 1 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total1,
            ROUND(SUM(CASE WHEN q.`bulan` = 2 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total2,
            ROUND(SUM(CASE WHEN q.`bulan` = 3 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total3,
            ROUND(SUM(CASE WHEN q.`bulan` = 4 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total4
            FROM `productivity_by_month_q1` AS q
            LEFT JOIN t_product AS w ON w.`kode_product` = q.`kode_product`
            WHERE q.`tahun` = '$tahun'");
    return $query;
  }

  // F-Cost Internal Defect Q2 (IDR) - Months 5-8
  // Note: Product costs (w.cost) are stored in Indonesian Rupiah (IDR)
  public function f_cost_int_defect_q2($tahun)
  {
    $query = $this->db->query("SELECT q.`tahun`,
            ROUND(SUM(CASE WHEN q.`bulan` = 5 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total5,
            ROUND(SUM(CASE WHEN q.`bulan` = 6 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total6,
            ROUND(SUM(CASE WHEN q.`bulan` = 7 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total7,
            ROUND(SUM(CASE WHEN q.`bulan` = 8 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total8
            FROM `productivity_by_month_q2` AS q
            LEFT JOIN t_product AS w ON w.`kode_product` = q.`kode_product`
            WHERE q.`tahun` = '$tahun'");
    return $query;
  }

  // F-Cost Internal Defect Q3 (IDR) - Months 9-12
  // Note: Product costs (w.cost) are stored in Indonesian Rupiah (IDR)
  public function f_cost_int_defect_q3($tahun)
  {
    $query = $this->db->query("SELECT q.`tahun`,
            ROUND(SUM(CASE WHEN q.`bulan` = 9 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total9,
            ROUND(SUM(CASE WHEN q.`bulan` = 10 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total10,
            ROUND(SUM(CASE WHEN q.`bulan` = 11 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total11,
            ROUND(SUM(CASE WHEN q.`bulan` = 12 THEN `q`.`qty_ng` * w.cost ELSE 0 END),2) AS total12
            FROM `productivity_by_month_q3` AS q
            LEFT JOIN t_product AS w ON w.`kode_product` = q.`kode_product`
            WHERE q.`tahun` = '$tahun'");
    return $query;
  }



  //Report Akunting 1
  function rep_akunting1($tahun, $bulan)
  {
    $query = $this->db->query("
              SELECT q.id_bom, w.cavity, DATE_FORMAT(`q`.`tanggal`,'%Y-%m') AS `mo`,w.`kp_pr`, w.`np_pr`,q.`mesin`, 
              0 AS target_qty, SUM(q.`qty_ok`) AS qty_ok,
              ROUND((3600 * SUM(q.nwt_mp + q.ot_mp)) / (SUM(q.qty_ok) * w.cavity),2) AS g_prod,
              ROUND(SUM(q.production_time) * 3600 / SUM(q.qty_ok + q.qty_ng) * w.cavity,2) AS n_prod,
              w.`cyt_quo` AS SPM_std, q.`ct_mc`, MIN(q.`ct_mc_aktual`) AS min_spm_act, MAX(q.`ct_mc_aktual`) AS max_spm_act, SUM(q.`nwt_mp`) AS shift_hour, ROUND(SUM(q.`production_time` + q.`qty_lt`),1) AS wh,
              SUM(q.`ot_mp`) AS ot, ROUND(SUM(q.`production_time` + q.`qty_lt`) / SUM(q.nwt_mp) * 100,2) AS persen_mc_use, ROUND(SUM(q.nwt_mp) - SUM(q.`production_time` + q.`qty_lt`),1) AS cdt,
              ROUND(SUM(q.qty_lt),1) AS stop_time, SUM(q.`qty_ok`) + SUM(q.qty_ng) AS pres_insp, SUM(q.qty_ng) AS press_def, 
              ROUND(SUM(q.qty_ng) / SUM(q.qty_ok + q.qty_ng) * 100,2) AS persen_defect      
              FROM t_production_op AS q
              LEFT JOIN select_bom AS w ON q.`id_bom` = w.`id_bom`
              WHERE q.`tanggal` LIKE '$tahun-$bulan%'
              GROUP BY q.`id_bom`, q.`mesin`
              ORDER BY q.`mesin`");
    return $query;
  }

  function rep_akunting_new($tanggal_dari, $tanggal_sampai)
  {
    $query = $this->db->query("
              SELECT DATE_FORMAT(`q`.`tanggal`,'%Y-%m') AS `mo`,q.id_bom, q.`mesin`,
              (SELECT z.tonnase FROM t_no_mesin AS z
              WHERE z.no_mesin = q.`mesin`
              GROUP BY z.tonnase) AS tonnase, w.`kp_pr`,w.`np_pr`,w.np_mr,  
              SUM(q.`qty_ok` + q.qty_ng) AS tot_prod, SUM(q.`qty_ok`) AS tot_ok, SUM(q.qty_ng) AS tot_ng,
              w.cavity,w.`cyt_quo` AS ct_quo, q.`ct_mc`,
              ROUND(SUM(q.`production_time` + q.`qty_lt` + q.`ot_mp`),2) AS moh,
              ROUND(SUM(q.`runner` + q.`loss_purge`),1) AS afv_rcd,
              w.`gr_per_pc`, w.`runner_per_shots`,
              ROUND(SUM(w.`gr_per_pc` * (q.`qty_ok` + q.`qty_ng`)) / 1000) AS kg,
              ROUND(w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`),1) AS standar_use_kg,
              ROUND((SUM(q.`qty_ok` + q.`qty_ng`) * (w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`)))/1000,1) AS total_kg,
              e.`virgin`, e.`regrind`,
              ROUND((e.`virgin` + e.`regrind`)- ROUND(SUM(q.`runner` + q.`loss_purge`),1) - ROUND(SUM(w.`gr_per_pc` * (q.`qty_ok` + q.`qty_ng`)) / 1000),1) AS var_kg
              FROM t_production_op AS q
              LEFT JOIN select_bom AS w ON q.`id_bom` = w.`id_bom`
              LEFT JOIN `v_material_transaction` AS e ON DATE_FORMAT(`q`.`tanggal`,'%Y-%m') = e.`mo`
              AND w.`kp_pr` = e.`kode_produk`
              WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
              GROUP BY q.`id_bom`, q.`mesin`
              ORDER BY q.`mesin`
             ");
    return $query;
  }

  function rep_akunting_default()
  {
    $query = $this->db->query("
              SELECT DATE_FORMAT(`q`.`tanggal`,'%Y-%m') AS `mo`,q.id_bom, q.`mesin`,
              (SELECT z.tonnase FROM t_no_mesin AS z
              WHERE z.no_mesin = q.`mesin`
              GROUP BY z.tonnase) AS tonnase, w.`kp_pr`,w.`np_pr`,w.np_mr,  
              SUM(q.`qty_ok` + q.qty_ng) AS tot_prod, SUM(q.`qty_ok`) AS tot_ok, SUM(q.qty_ng) AS tot_ng,
              w.cavity,w.`cyt_quo` AS ct_quo, q.`ct_mc`,
              ROUND(SUM(q.`production_time` + q.`qty_lt` + q.`ot_mp`),2) AS moh,
              ROUND(SUM(q.`runner` + q.`loss_purge`),1) AS afv_rcd,
              w.`gr_per_pc`, w.`runner_per_shots`,
              ROUND(SUM(w.`gr_per_pc` * (q.`qty_ok` + q.`qty_ng`)) / 1000) AS kg,
              ROUND(w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`),1) AS standar_use_kg,
              ROUND((SUM(q.`qty_ok` + q.`qty_ng`) * (w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`)))/1000,1) AS total_kg,
              e.`virgin`, e.`regrind`,
              ROUND((e.`virgin` + e.`regrind`)- ROUND(SUM(q.`runner` + q.`loss_purge`),1) - ROUND(SUM(w.`gr_per_pc` * (q.`qty_ok` + q.`qty_ng`)) / 1000),1) AS var_kg
              FROM t_production_op AS q
              LEFT JOIN select_bom AS w ON q.`id_bom` = w.`id_bom`
              LEFT JOIN `v_material_transaction` AS e ON DATE_FORMAT(`q`.`tanggal`,'%Y-%m') = e.`mo`
              AND w.`kp_pr` = e.`kode_produk`
              WHERE MONTH(q.`tanggal`) = MONTH(CURDATE()) AND YEAR(q.`tanggal`) = YEAR(CURDATE())
              GROUP BY q.`id_bom`, q.`mesin`
              ORDER BY q.`mesin`");
    return $query;
  }

  function rep_akunting_total($tanggal_dari, $tanggal_sampai)
  {
    $query = $this->db->query("
              SELECT DATE_FORMAT(`q`.`tanggal`,'%Y-%m') AS `mo`,
              SUM(q.`qty_ok` + q.qty_ng) AS tot_prod, SUM(q.`qty_ok`) AS tot_ok, SUM(q.qty_ng) AS tot_ng,
              ROUND(SUM(q.`production_time` + q.`qty_lt` + q.`ot_mp`),2) AS moh,
              ROUND(SUM(q.`runner` + q.`loss_purge`),1) AS afv_rcd,
              w.`gr_per_pc`, w.`runner_per_shots`,
              ROUND(SUM(w.`gr_per_pc` * (q.`qty_ok` + q.`qty_ng`)) / 1000) AS kg,
              ROUND(w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`),1) AS standar_use_kg,
              ROUND((SUM(q.`qty_ok` + q.`qty_ng`) * (w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`)))/1000,1) AS total_kg
              
              FROM t_production_op AS q
              LEFT JOIN select_bom AS w ON q.`id_bom` = w.`id_bom`
              WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
              GROUP BY DATE_FORMAT(`q`.`tanggal`,'%Y-%m')");
    return $query;
  }

  function rep_akunting_total_default()
  {
    $query = $this->db->query("
              SELECT DATE_FORMAT(`q`.`tanggal`,'%Y-%m') AS `mo`,
              SUM(q.`qty_ok` + q.qty_ng) AS tot_prod, SUM(q.`qty_ok`) AS tot_ok, SUM(q.qty_ng) AS tot_ng,
              ROUND(SUM(q.`production_time` + q.`qty_lt` + q.`ot_mp`),2) AS moh,
              ROUND(SUM(q.`runner` + q.`loss_purge`),1) AS afv_rcd,
              w.`gr_per_pc`, w.`runner_per_shots`,
              ROUND(SUM(w.`gr_per_pc` * (q.`qty_ok` + q.`qty_ng`)) / 1000) AS kg,
              ROUND(w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`),1) AS standar_use_kg,
              ROUND((SUM(q.`qty_ok` + q.`qty_ng`) * (w.`gr_per_pc` + (w.`runner_per_shots`/w.`cavity`)))/1000,1) AS total_kg
              
              FROM t_production_op AS q
              LEFT JOIN select_bom AS w ON q.`id_bom` = w.`id_bom`
              WHERE MONTH(q.`tanggal`) = MONTH(CURDATE()) AND YEAR(q.`tanggal`) = YEAR(CURDATE())
              GROUP BY DATE_FORMAT(`q`.`tanggal`,'%Y-%m')");
    return $query;
  }

  //Tambahan
  function tampil_summary_by_part($tahun)
  {
    $query = $this->db->query("
               SELECT 
            p.kode_product, p.nama_product,p.`cavity`, p.cyt_quo, p.`mesin`,
            (CASE WHEN p.bulan = 01 THEN p.nett ELSE 0 END) AS nett_1,
            (CASE WHEN p.bulan = 01 THEN p.gross ELSE 0 END) AS gross_1,
            (CASE WHEN p.bulan = 01 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett1,
            (CASE WHEN p.bulan = 01 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross1,
            (CASE WHEN p.bulan = 02 THEN p.nett ELSE 0 END) AS nett_2,
            (CASE WHEN p.bulan = 02 THEN p.gross ELSE 0 END) AS gross_2,
            (CASE WHEN p.bulan = 02 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett2,
            (CASE WHEN p.bulan = 02 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross2,
            (CASE WHEN p.bulan = 03 THEN p.nett ELSE 0 END) AS nett_3,
            (CASE WHEN p.bulan = 03 THEN p.gross ELSE 0 END) AS gross_3,
            (CASE WHEN p.bulan = 03 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett3,
            (CASE WHEN p.bulan = 03 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross3,
            (CASE WHEN p.bulan = 04 THEN p.nett ELSE 0 END) AS nett_4,
            (CASE WHEN p.bulan = 04 THEN p.gross ELSE 0 END) AS gross_4,
            (CASE WHEN p.bulan = 04 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett4,
            (CASE WHEN p.bulan = 04 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross4,
            (CASE WHEN p.bulan = 05 THEN p.nett ELSE 0 END) AS nett_5,
            (CASE WHEN p.bulan = 05 THEN p.gross ELSE 0 END) AS gross_5,
            (CASE WHEN p.bulan = 05 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett5,
            (CASE WHEN p.bulan = 05 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross5,
            (CASE WHEN p.bulan = 06 THEN p.nett ELSE 0 END) AS nett_6,
            (CASE WHEN p.bulan = 06 THEN p.gross ELSE 0 END) AS gross_6,
            (CASE WHEN p.bulan = 06 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett6,
            (CASE WHEN p.bulan = 06 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross6,
            (CASE WHEN p.bulan = 07 THEN p.nett ELSE 0 END) AS nett_7,
            (CASE WHEN p.bulan = 07 THEN p.gross ELSE 0 END) AS gross_7,
            (CASE WHEN p.bulan = 07 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett7,
            (CASE WHEN p.bulan = 07 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross7,
            (CASE WHEN p.bulan = 08 THEN p.nett ELSE 0 END) AS nett_8,
            (CASE WHEN p.bulan = 08 THEN p.gross ELSE 0 END) AS gross_8,
            (CASE WHEN p.bulan = 08 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett8,
            (CASE WHEN p.bulan = 08 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross8,
            (CASE WHEN p.bulan = 09 THEN p.nett ELSE 0 END) AS nett_9,
            (CASE WHEN p.bulan = 09 THEN p.gross ELSE 0 END) AS gross_9,
            (CASE WHEN p.bulan = 09 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett9,
            (CASE WHEN p.bulan = 09 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross9,
            (CASE WHEN p.bulan = 10 THEN p.nett ELSE 0 END) AS nett_10,
            (CASE WHEN p.bulan = 10 THEN p.gross ELSE 0 END) AS gross_10,
            (CASE WHEN p.bulan = 10 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett10,
            (CASE WHEN p.bulan = 10 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross10,
            (CASE WHEN p.bulan = 11 THEN p.nett ELSE 0 END) AS nett_11,
            (CASE WHEN p.bulan = 11 THEN p.gross ELSE 0 END) AS gross_11,
            (CASE WHEN p.bulan = 11 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett11,
            (CASE WHEN p.bulan = 11 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross11,
            (CASE WHEN p.bulan = 12 THEN p.nett ELSE 0 END) AS nett_12,
            (CASE WHEN p.bulan = 12 THEN p.gross ELSE 0 END) AS gross_12,
            (CASE WHEN p.bulan = 12 THEN ((p.`cyt_quo` / p.`nett`) * 100) ELSE 0 END) AS persen_nett12,
            (CASE WHEN p.bulan = 12 THEN ((p.`cyt_quo` / p.`gross`) * 100) ELSE 0 END) AS persen_gross12
            FROM 
            `v_summary_prod_by_part_v4423` AS p 
            WHERE p.tahun = '$tahun' AND p.`cyt_quo` IS NOT NULL
            ORDER BY p.`nama_product` ASC");
    return $query;
  }

  function rep_qtybycust_default($start_date, $end_date)
  {
    $query = $this->db->query("
          SELECT YEAR(q.`tanggal`) AS YY,CONCAT(RIGHT(YEAR(q.`tanggal`),2),'-',MONTH(q.`tanggal`)) AS 'YY-MM',MONTH(q.`tanggal`) 'Mo',q.`customer`,
          SUM(q.`qty_ok` + q.`qty_ng`) total,SUM(q.`qty_ok`) ok,SUM(q.`qty_ng`) ng,
ROUND(SUM(q.`qty_ng`) / SUM(q.`qty_ok` + q.`qty_ng`) * 1000000) AS ppm          
           FROM t_production_op q WHERE DATE(q.`tanggal`) >= '$start_date' AND DATE(q.`tanggal`) <= '$end_date' GROUP BY q.`customer`,MONTH(q.`tanggal`),YEAR(q.`tanggal`) ");
    return $query;
  }

  function rep_qtybycust_yearly($year)
  {
    $query = $this->db->query("
          SELECT 
    p.customer AS 'Customer',
    p.`kp_pr` AS 'Product ID',
    MAX(p.`nama_product`) AS 'Product Name',
    SUM(p.qty_ok) AS 'Total OK',
    SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN p.qty_ok ELSE NULL END) AS '01',
    SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN p.qty_ok ELSE NULL END) AS '02',
    SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN p.qty_ok ELSE NULL END) AS '03',
    SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN p.qty_ok ELSE NULL END) AS '04',
    SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN p.qty_ok ELSE NULL END) AS '05',
    SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN p.qty_ok ELSE NULL END) AS '06',
    SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN p.qty_ok ELSE NULL END) AS '07',
    SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN p.qty_ok ELSE NULL END) AS '08',
    SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN p.qty_ok ELSE NULL END) AS '09',
    SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN p.qty_ok ELSE NULL END) AS '10',
    SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN p.qty_ok ELSE NULL END) AS '11',
    SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN p.qty_ok ELSE NULL END) AS '12'
FROM 
    v_production_op p
WHERE 
    YEAR(p.tanggal) = '$year'
    AND p.qty_ok > 0
GROUP BY 
    p.customer, p.id_bom, p.tooling
ORDER BY 
    p.customer, p.id_bom");
    return $query;
  }

  function read_productivity_by_month($bulan, $tahun)
  {
    $query = $this->db->query("SELECT q.kode_product, q.nama_product, ROUND((q.`max_CTStd2` / q.`gross_prod`) *100, 2) AS tti_gross,
           ROUND((q.`max_CTStd2` / q.`nett_prod`) *100, 2) AS tti_nett  FROM productivity_by_month q WHERE q.`tahun` = '$tahun' AND MONTH(q.`tanggal`) = '$bulan' GROUP BY q.`kode_product`");
    return $query;
  }

  /**
   * Fetches productivity data for a specific month and year
   * @param string $bulan  The month to filter data
   * @param string $tahun  The year to filter data
   * @return object       Query result object
   */
  public function read_productivity_by_part_by_machine_by_month($bulan, $tahun)
{
    // 1) Validate & normalize inputs
    $tahun = (int) $tahun;
    $bulan = (int) $bulan;
    if ($tahun < 1900 || $tahun > 3000) {
        throw new InvalidArgumentException("Invalid year: {$tahun}");
    }
    if ($bulan < 1 || $bulan > 12) {
        throw new InvalidArgumentException("Invalid month: {$bulan}");
    }
    // zero‑pad month for formatting
    $mm = str_pad($bulan, 2, '0', STR_PAD_LEFT);

    // 2) Compute the first day of that month, and the first day of the next month
    $start_date = "{$tahun}-{$mm}-01";
    // use PHP's DateTime to roll over month/year correctly
    $dt = new DateTime($start_date);
    $dt->modify('+1 month');
    $end_date = $dt->format('Y-m-01');

    // 3) Build & run the query with bindings
    $sql = "
        SELECT
    DATE_FORMAT(q.tanggal, '%Y-%m') AS bulan,
    q.kode_product,
    q.nama_product,
    q.mesin,
    SUM(q.target_mc)                            AS target_mc,
    SUM(q.qty_ok)                               AS qty_ok,

    CASE
        WHEN MOD(AVG(q.nett_prod), 1) < 0.5 THEN FORMAT(FLOOR(AVG(q.nett_prod)), 2)
        ELSE FORMAT(CEIL(AVG(q.nett_prod)), 2)
    END                                          AS nett_prod,

    CASE
        WHEN MOD(AVG(q.gross_prod), 1) < 0.5 THEN FORMAT(FLOOR(AVG(q.gross_prod)), 2)
        ELSE FORMAT(CEIL(AVG(q.gross_prod)), 2)
    END                                          AS gross_prod,

    ROUND(MAX(p.cyt_mc), 2)                     AS CTStd,
    ROUND(MAX(p.cyt_quo), 2)                    AS CTStd2,

    ROUND(MIN(q.CTSet), 2)                      AS MinSPMSet,
    ROUND(MAX(q.CTSet), 2)                      AS MaxSPMSet,

    SUM(q.nwt_mp)                               AS nwt_mp,
    ROUND(SUM(q.production_time) + SUM(q.qty_lt), 1) AS production_time,

    SUM(q.ot_mp)                                AS ot_mp,
    ((SUM(q.ot_mp) * 100) / ROUND(SUM(q.production_time), 1)) AS percentage_ot,

    ((ROUND(SUM(q.production_time), 1) * 100) / SUM(q.nwt_mp)) AS mach_use_percentage,

    (SUM(q.nwt_mp) - ROUND(SUM(q.production_time), 1)) AS CalcDT,

    ROUND(
      (SUM(q.nwt_mp) - ROUND(SUM(q.production_time), 1)) - SUM(q.qty_lt),
    2)                                          AS DeltaDT,

    SUM(q.qty_ok)                               AS press_insp,
    SUM(q.qty_ng)                               AS press_def,
    ROUND((SUM(q.qty_ng) * 100) / SUM(q.qty_ok), 2) AS percent_def,

    SUM(q.ct_mp)                                AS ct_mp,
    SUM(q.ct_mp_aktual)                         AS ct_mp_aktual,

    SUM(q.qty_ng)                               AS qty_ng,
    SUM(q.qty_lt)                               AS qty_lt,
    SUM(q.runner)                               AS runner,
    SUM(q.loss_purge)                           AS loss_purge,
    SUM(q.cavity)                               AS cavity,
    SUM(q.cavity2)                              AS cavity2,

    ROUND(AVG(q.CTSet), 2)                      AS CTSet,

    SUM(CASE WHEN e.nama = 'BENDING'              THEN e.qty END) AS bending,
    SUM(CASE WHEN e.nama = 'BERAWAN'              THEN e.qty END) AS berawan,
    SUM(CASE WHEN e.nama = 'BLACKDOT'             THEN e.qty END) AS blackdot,
    SUM(CASE WHEN e.nama = 'BROKEN'               THEN e.qty END) AS broken,
    SUM(CASE WHEN e.nama = 'CRACK'                THEN e.qty END) AS crack,
    SUM(CASE WHEN e.nama = 'DENT'                 THEN e.qty END) AS dent,
    SUM(CASE WHEN e.nama = 'DIRTY'                THEN e.qty END) AS dirty,
    SUM(CASE WHEN e.nama = 'DISCOLOUR'            THEN e.qty END) AS discolour,
    SUM(CASE WHEN e.nama = 'EJECTOR MARK'         THEN e.qty END) AS ejector_mark,
    SUM(CASE WHEN e.nama = 'FLASH'                THEN e.qty END) AS flash,
    SUM(CASE WHEN e.nama = 'FLOW GATE'            THEN e.qty END) AS flow_gate,
    SUM(CASE WHEN e.nama = 'FLOW MARK'            THEN e.qty END) AS flow_mark,
    SUM(CASE WHEN e.nama = 'FOREIGN MATERIAL'     THEN e.qty END) AS fm,
    SUM(CASE WHEN e.nama = 'GAS BURN'             THEN e.qty END) AS gas_burn,
    SUM(CASE WHEN e.nama = 'GAS MARK'             THEN e.qty END) AS gas_mark,
    SUM(CASE WHEN e.nama = 'GATE BOLONG'          THEN e.qty END) AS gate_bolong,
    SUM(CASE WHEN e.nama = 'GATE LONG'            THEN e.qty END) AS gate_long,
    SUM(CASE WHEN e.nama = 'HANGUS'               THEN e.qty END) AS hangus,
    SUM(CASE WHEN e.nama = 'HIKE'                 THEN e.qty END) AS hike,
    SUM(CASE WHEN e.nama = 'OIL'                  THEN e.qty END) AS oil,
    SUM(CASE WHEN e.nama = 'OVERSIZE'             THEN e.qty END) AS oversize,
    SUM(CASE WHEN e.nama = 'PIN PLONG'            THEN e.qty END) AS pin_plong,
    SUM(CASE WHEN e.nama = 'PIN SERET'            THEN e.qty END) AS pin_seret,
    SUM(CASE WHEN e.nama = 'SCRATCH'              THEN e.qty END) AS scratch,
    SUM(CASE WHEN e.nama = 'SETTINGAN'            THEN e.qty END) AS settingan,
    SUM(CASE WHEN e.nama = 'SHORT SHOOT'          THEN e.qty END) AS short_shoot,
    SUM(CASE WHEN e.nama = 'SILVER'               THEN e.qty END) AS silver,
    SUM(CASE WHEN e.nama = 'SINK MARK'            THEN e.qty END) AS sink_mark,
    SUM(CASE WHEN e.nama = 'UNDERCUT'             THEN e.qty END) AS undercut,
    SUM(CASE WHEN e.nama = 'UNDERSIZE'            THEN e.qty END) AS under_size,
    SUM(CASE WHEN e.nama = 'VOID'                 THEN e.qty END) AS void,
    SUM(CASE WHEN e.nama = 'WAVING'               THEN e.qty END) AS waving,
    SUM(CASE WHEN e.nama = 'WELD LINE'            THEN e.qty END) AS weld_line,
    SUM(CASE WHEN e.nama = 'WHITE DOT'            THEN e.qty END) AS white_dot,
    SUM(CASE WHEN e.nama = 'WHITE MARK'           THEN e.qty END) AS white_mark,
    ROUND(SUM(CASE WHEN e.nama = 'ADJUST PARAMETER'         THEN e.qty END), 2) AS adjust_parameter,
    ROUND(SUM(CASE WHEN e.nama = 'PRE HEATING MATERIAL'     THEN e.qty END), 2) AS pre_heating_material,
    ROUND(SUM(CASE WHEN e.nama = 'CLEANING HOPPER & BARREL'  THEN e.qty END), 2) AS cleaning,
    ROUND(SUM(CASE WHEN e.nama = 'SET UP MOLD'              THEN e.qty END), 2) AS set_up_mold,
    ROUND(SUM(CASE WHEN e.nama = 'SET UP PARAMETER MACHINE' THEN e.qty END), 2) AS set_up_par_machine,
    ROUND(SUM(CASE WHEN e.nama = 'IPQC INSPECTION'          THEN e.qty END), 2) AS ipqc_inspection,
    ROUND(SUM(CASE WHEN e.nama = 'NO PACKING'               THEN e.qty END), 2) AS no_packing,
    ROUND(SUM(CASE WHEN e.nama = 'NO MATERIAL'              THEN e.qty END), 2) AS no_material,
    ROUND(SUM(CASE WHEN e.nama = 'MATERIAL PROBLEM'         THEN e.qty END), 2) AS material_problem,
    ROUND(SUM(CASE WHEN e.nama = 'NO OPERATOR'              THEN e.qty END), 2) AS no_operator,
    ROUND(SUM(CASE WHEN e.nama = 'DAILY CHECK LIST'         THEN e.qty END), 2) AS daily_check_list,
    ROUND(SUM(CASE WHEN e.nama = 'OVERHOULE MOLD'           THEN e.qty END), 2) AS overhoule_mold,
    ROUND(SUM(CASE WHEN e.nama = 'MOLD PROBLEM'             THEN e.qty END), 2) AS mold_problem,
    ROUND(SUM(CASE WHEN e.nama = 'TRIAL'                    THEN e.qty END), 2) AS trial,
    ROUND(SUM(CASE WHEN e.nama = 'MACHINE'                  THEN e.qty END), 2) AS machine,
    ROUND(SUM(CASE WHEN e.nama = 'HOPPER DRYER'             THEN e.qty END), 2) AS hopper_dryer,
    ROUND(SUM(CASE WHEN e.nama = 'ROBOT'                    THEN e.qty END), 2) AS robot,
    ROUND(SUM(CASE WHEN e.nama = 'MTC'                      THEN e.qty END), 2) AS mtc,
    ROUND(SUM(CASE WHEN e.nama = 'COOLING TOWER'            THEN e.qty END), 2) AS cooling_tower,
    ROUND(SUM(CASE WHEN e.nama = 'COMPRESSOR'               THEN e.qty END), 2) AS compressor,
    ROUND(SUM(CASE WHEN e.nama = 'LISTRIK'                  THEN e.qty END), 2) AS listrik,
    ROUND(SUM(CASE WHEN e.nama = 'QC LOLOS'                 THEN e.qty END), 2) AS qc_lolos,

    ROUND(AVG(q.production_time / q.nwt_mp) * 100, 2)     AS mach_use,
    ROUND(SUM(q.ot_mp / q.nwt_mp) * 100, 2)                AS persen_ot

FROM v_production_op AS q

LEFT JOIN (
    SELECT nama_operator, line
    FROM t_operator
    GROUP BY nama_operator
) AS w
    ON q.kanit = w.nama_operator

LEFT JOIN t_production_op_dl AS e
    ON q.id_production = e.id_production

LEFT JOIN t_bom AS b
    ON q.id_bom = b.id_bom

LEFT JOIN t_product AS p
    ON b.id_product = p.id_product

WHERE q.tanggal >= '{$start_date}'
  AND q.tanggal  < '{$end_date}'

GROUP BY
    DATE_FORMAT(q.tanggal, '%Y-%m'),
    q.kode_product,
    q.nama_product,
    q.mesin,
    w.line

ORDER BY
    q.mesin        ASC,
    q.nama_product ASC;

    ";

    $query = $this->db->query($sql, [
        $start_date,
        $end_date
    ]);

    return $query;
}

function tampil_7Table(){
  $current_year = date('Y');
  $query = $this->db->query("
          SELECT 
    YEAR(op.tanggal) AS yr,
    MONTH(op.tanggal) AS mnth,
    CONCAT(YEAR(op.tanggal), '-', LPAD(MONTH(op.tanggal), 2, '0')) AS period,
    dl.kategori AS losstime_category,
    ROUND(SUM(dl.qty), 2) AS total_minutes
FROM 
    t_production_op_dl dl
INNER JOIN 
    t_production_op op ON dl.id_production = op.id_production
WHERE 
    dl.type = 'LT'
    AND YEAR(op.tanggal) = ?
GROUP BY 
    yr,
    mnth,
    period,
    dl.kategori
ORDER BY 
    yr, 
    mnth, 
    dl.kategori;", [$current_year]);
    return $query;
}

function tampil_7Table_table(){
  $current_year = date('Y');
  $query = $this->db->query("
      SELECT 
          YEAR(op.tanggal) AS yr,
          MONTH(op.tanggal) AS mnth,
          CONCAT(YEAR(op.tanggal), '-', LPAD(MONTH(op.tanggal), 2, '0')) AS period,
          dl.kategori AS losstime_category,
          dl.`nama`,
          ROUND(SUM(dl.qty), 2) AS total_minutes
      FROM 
          t_production_op_dl dl
      INNER JOIN 
          t_production_op op ON dl.id_production = op.id_production
      WHERE 
          dl.type = 'LT'
          AND YEAR(op.tanggal) = ?
      GROUP BY 
          yr,
          mnth,
          period,
          dl.kategori,
          dl.`nama`
      ORDER BY 
          yr DESC, 
          mnth DESC, 
          dl.kategori
  ", [$current_year]);
  return $query;
}

public function get_7table_data_from_year($tahun)
  {
    $query = $this->db->query("SELECT m.no_mesin,
    mth.month,
    ROUND(SUM(p.production_time), 2) AS SumOfWH,
    ROUND(SUM(p.ot_mp), 2) AS SumOfOT,
    (SELECT total 
       FROM year_day 
      WHERE tahun = '$tahun' 
        AND bulan = mth.month) AS total_hour_std,
    ROUND(SUM(p.production_time), 2) AS MachEffHour,
    ROUND(SUM(p.qty_lt), 2) AS TotalST,
    -- Pivot the lost time details into separate columns:
    ROUND(SUM(CASE WHEN dl.nama = 'NO MATERIAL' THEN dl.qty ELSE 0 END), 2) AS no_material,
    ROUND(SUM(CASE WHEN dl.nama = 'NO PACKING' THEN dl.qty ELSE 0 END), 2) AS no_packing,
    ROUND(SUM(CASE WHEN dl.nama = 'NO OPERATOR' THEN dl.qty ELSE 0 END), 2) AS no_operator,
    ROUND(SUM(CASE WHEN dl.nama = 'MATERIAL PROBLEM' THEN dl.qty ELSE 0 END), 2) AS material_problem,
    ROUND(SUM(CASE WHEN dl.nama = 'ADJUST PARAMETER' THEN dl.qty ELSE 0 END), 2) AS adjust_parameter,
    ROUND(SUM(CASE WHEN dl.nama = 'DAILY CHECK LIST' THEN dl.qty ELSE 0 END), 2) AS daily_checklist,
    ROUND(SUM(CASE WHEN dl.nama = 'PRE HEATING MATERIAL' THEN dl.qty ELSE 0 END), 2) AS pre_heating_material,
    ROUND(SUM(CASE WHEN dl.nama = 'CLEANING HOPPER & BARREL' THEN dl.qty ELSE 0 END), 2) AS cleaning_hopper_barrel,
    ROUND(SUM(CASE WHEN dl.nama = 'SET UP MOLD' THEN dl.qty ELSE 0 END), 2) AS setup_mold,
    ROUND(SUM(CASE WHEN dl.nama = 'SET UP PARAMETER MACHINE' THEN dl.qty ELSE 0 END), 2) AS setup_parameter_machine,
    ROUND(SUM(CASE WHEN dl.nama = 'IPQC INSPECTION' THEN dl.qty ELSE 0 END), 2) AS ipqc_inspection,
    ROUND(SUM(CASE WHEN dl.nama = 'MACHINE' THEN dl.qty ELSE 0 END), 2) AS machine,
    ROUND(SUM(CASE WHEN dl.nama = 'HOPPER DRYER' THEN dl.qty ELSE 0 END), 2) AS hopper_dryer,
    ROUND(SUM(CASE WHEN dl.nama = 'ROBOT' THEN dl.qty ELSE 0 END), 2) AS robot,
    ROUND(SUM(CASE WHEN dl.nama = 'MTC' THEN dl.qty ELSE 0 END), 2) AS mtc,
    ROUND(SUM(CASE WHEN dl.nama = 'COOLING TOWER' THEN dl.qty ELSE 0 END), 2) AS chiller,
    ROUND(SUM(CASE WHEN dl.nama = 'COMPRESSOR' THEN dl.qty ELSE 0 END), 2) AS compressor,
    ROUND(SUM(CASE WHEN dl.nama = 'LISTRIK' THEN dl.qty ELSE 0 END), 2) AS listrik,
    ROUND(SUM(CASE WHEN dl.nama = 'OVERHOULE MOLD' THEN dl.qty ELSE 0 END), 2) AS overhole,
    ROUND(SUM(CASE WHEN dl.nama = 'QC LOLOS' THEN dl.qty ELSE 0 END), 2) AS qc_lolos,
    ROUND(SUM(CASE WHEN dl.nama = 'MOLD PROBLEM' THEN dl.qty ELSE 0 END), 2) AS mold_problem,
    ROUND(SUM(CASE WHEN dl.nama = 'TRIAL' THEN dl.qty ELSE 0 END), 2) AS trial,
    ROUND(SUM(CASE WHEN dl.nama = 'SET UP AWAL PRODUKSI' THEN dl.qty ELSE 0 END), 2) AS setup_awal_produksi,
    ROUND(
        (ROUND(SUM(p.production_time), 2) / 
         (SELECT total FROM year_day WHERE tahun = '$tahun' AND bulan = mth.month)
        )*100,2) AS mch_eff_percentage
FROM t_no_mesin m
CROSS JOIN (
    SELECT 1 AS MONTH UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
    UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
) mth
LEFT JOIN t_production_op p 
    ON m.no_mesin = p.mesin 
    AND YEAR(p.tanggal) = '$tahun'
    AND MONTH(p.tanggal) = mth.month
LEFT JOIN t_production_op_dl dl
    ON p.id_production = dl.id_production
GROUP BY m.no_mesin, mth.month
ORDER BY mth.month, m.no_mesin;
");
    return $query;
  }


public function get_ct_by_product_monthly($start_date, $end_date)
{
    $sql = "
        SELECT
          DATE_FORMAT(v.tanggal, '%Y-%m')      AS month_bucket,
          p.kode_product,
          p.nama_product,
          ROUND(p.cyt_mc,  1)                  AS cycle_time_std,
          ROUND(p.cyt_quo, 1)                  AS cycle_time_quote,
          ROUND(AVG(v.ct_mc_aktual), 1)        AS avg_ct_actual,
          ROUND(AVG(v.ct_mc), 1)               AS avg_ct_mc
        FROM
          v_production_op AS v
          JOIN t_product     AS p ON v.id_product = p.id_product
        WHERE
          v.tanggal BETWEEN ? AND ?
        GROUP BY
          DATE_FORMAT(v.tanggal, '%Y-%m'),
          p.kode_product,
          p.nama_product,
          p.cyt_mc,
          p.cyt_quo
        ORDER BY
          DATE_FORMAT(v.tanggal, '%Y-%m'),
          p.kode_product
    ";
    $query = $this->db->query($sql, array($start_date, $end_date));
    return $query->result_array();
}




public function get_ct_by_product_monthly_detail($month_bucket, $kode_product)
{
    $sql = "
        SELECT
            v.tanggal,
            v.ct_mc_aktual,
            v.ct_mc,
            v.keterangan,
            p.kode_product,
            p.nama_product,
            p.cyt_mc AS cycle_time_std,
            p.cyt_quo AS cycle_time_quote
        FROM
            v_production_op AS v
            JOIN t_product AS p ON v.id_product = p.id_product
        WHERE
            DATE_FORMAT(v.tanggal, '%Y-%m') = ?
            AND p.kode_product = ?
        ORDER BY
            v.tanggal ASC
    ";
    $query = $this->db->query($sql, array($month_bucket, $kode_product));
    return $query->result_array();
}


public function get_ct_by_product_yearly($tahun)
{
    $sql = "
        SELECT
            p.kode_product,
            p.nama_product,
            MONTH(v.tanggal) AS month_num,
            ROUND(p.cyt_quo, 1) AS ct_quo,
            ROUND(p.cyt_mc, 1) AS ct_std,
            ROUND(AVG(v.ct_mc_aktual), 1) AS ct_aktual
        FROM
            v_production_op AS v
            JOIN t_product AS p ON v.id_product = p.id_product
        WHERE
            YEAR(v.tanggal) = ?
        GROUP BY
            p.kode_product,
            p.nama_product,
            MONTH(v.tanggal),
            p.cyt_quo,
            p.cyt_mc
        ORDER BY
            p.kode_product, MONTH(v.tanggal)
    ";
    $query = $this->db->query($sql, array($tahun));
    return $query->result_array();
}




public function get_cutting_tool_summary($cutting_tool_id = null, $start_date = null, $end_date = null)
{
    // Don't filter cutting tools by date, just show all cutting tools
    $where_cutting_tool = "";
    if ($cutting_tool_id) {
        $where_cutting_tool = "AND ct.id = $cutting_tool_id";
    }
    
    // Query to get all cutting tools first, separate from product data
    $sql = "
        SELECT
            ct.id AS cutting_tools_id,
            ct.code AS cutting_tool_code,
            ct.code_group AS code_group,
            NULL AS kode_product,
            NULL AS nama_product,
            0 AS total_qty_ok,
            0 AS total_qty_ng,
            0 AS total_nett_prod,
            (SELECT COUNT(*) FROM t_production_op_cutting_tools_usage tpu WHERE tpu.cutting_tools_id = ct.id) AS usage_count
        FROM
            cutting_tools ct
        WHERE
            1=1 $where_cutting_tool
        
        UNION
        
        SELECT
            tpu.cutting_tools_id,
            ct.code AS cutting_tool_code,
            ct.code_group AS code_group,
            op.kode_product,
            op.nama_product,
            SUM(IFNULL(op.qty_ok, 0)) AS total_qty_ok,
            SUM(IFNULL(op.qty_ng, 0)) AS total_qty_ng,
            SUM(IFNULL(op.nett_prod, 0)) AS total_nett_prod,
            0 AS usage_count
        FROM
            cutting_tools ct
        LEFT JOIN
            t_production_op_cutting_tools_usage tpu ON tpu.cutting_tools_id = ct.id
        LEFT JOIN
            v_production_op op ON tpu.id_production = op.id_production 
            AND op.tanggal BETWEEN '$start_date' AND '$end_date'
        WHERE
            op.kode_product IS NOT NULL
            AND op.kode_product NOT LIKE 'PSI-%'
            $where_cutting_tool
        GROUP BY
            tpu.cutting_tools_id, ct.code, ct.code_group, op.kode_product, op.nama_product
        
        ORDER BY
            cutting_tools_id, cutting_tool_code, kode_product
    ";
    
    $query = $this->db->query($sql);
    error_log("Direct SQL query: $sql");
    error_log("Result count: " . $query->num_rows());
    
    return $query->result_array();
}

public function get_cutting_tool_product_list($cutting_tool_id = null, $start_date = null, $end_date = null)
{
    $where_cutting_tool = "";
    if ($cutting_tool_id) {
        $where_cutting_tool = "AND tpu.cutting_tools_id = $cutting_tool_id";
    }
    
    $sql = "
        SELECT
            tpu.cutting_tools_id,
            ct.code AS cutting_tool_code,
            op.kode_product,
            op.nama_product,
            op.tanggal AS production_date,
            op.qty_ok,
            op.qty_ng,
            op.nett_prod,
            op.operator,
            op.shift
        FROM
            t_production_op_cutting_tools_usage tpu
        INNER JOIN
            v_production_op op ON tpu.id_production = op.id_production
        LEFT JOIN
            cutting_tools ct ON tpu.cutting_tools_id = ct.id
        WHERE
            op.tanggal BETWEEN '$start_date' AND '$end_date'
            $where_cutting_tool
            AND (op.kode_product NOT LIKE 'PSI-%')
            AND op.kode_product IS NOT NULL
        ORDER BY
            op.tanggal DESC, op.kode_product ASC
    ";
    
    $query = $this->db->query($sql);
    error_log("Product List Query: $sql");
    error_log("Product List Count: " . $query->num_rows());
    
    return $query->result_array();
}

public function debug_cutting_tool_data($month, $year)
{
    // Direct query to check if any data exists for this date range
    $start_date = "$year-$month-01";
    $end_date = date('Y-m-t', strtotime($start_date));
    
    $sql = "
        SELECT 
            tpu.id_ct_usage,
            tpu.cutting_tools_id,
            tpu.id_production,
            vpo.tanggal,
            vpo.qty_ok,
            vpo.qty_ng,
            vpo.nett_prod
        FROM 
            t_production_op_cutting_tools_usage tpu
        JOIN 
            t_production_op vpo ON tpu.id_production = vpo.id_production
        WHERE 
            vpo.tanggal BETWEEN '$start_date' AND '$end_date'
        LIMIT 10
    ";
    
    $query = $this->db->query($sql);
    error_log("Debug Query Results: " . json_encode($query->result_array()));
    
    return $query->result_array();
}

public function get_all_cutting_tools()
{
    $this->db->select('id, code, code_group');
    $this->db->from('cutting_tools');
    $this->db->order_by('code ASC');
    $query = $this->db->get();
    return $query->result_array();
}

public function get_annual_productivity($year = null)
{
    if ($year === null) {
        $year = date('Y');
    }

    // Build dynamic monthly columns
    $select_parts = array(
        "v.kode_product",
        "v.nama_product",
        "YEAR(v.tanggal) AS year"  // Fixed: removed invalid syntax
    );

    // Generate monthly columns dynamically
    for ($month = 1; $month <= 12; $month++) {
        $month_name = strtolower(date('M', mktime(0, 0, 0, $month, 1)));
        $select_parts[] = sprintf("ROUND(MAX(CASE WHEN MONTH(v.tanggal)=%d THEN p.cyt_quo END), 0) AS %s_cyt_quo", $month, $month_name);
        $select_parts[] = sprintf("ROUND(AVG(CASE WHEN MONTH(v.tanggal)=%d THEN v.nett_prod END), 0) AS %s_avg_nett_prod", $month, $month_name);
        $select_parts[] = sprintf("ROUND(AVG(CASE WHEN MONTH(v.tanggal)=%d THEN v.gross_prod END), 0) AS %s_avg_gross_prod", $month, $month_name);
    }

    $sql = "SELECT " . implode(",\n        ", $select_parts) . "
    FROM v_production_op AS v
    JOIN t_product AS p ON p.kode_product = v.kode_product
    WHERE YEAR(v.tanggal) = ?
    GROUP BY v.kode_product, v.nama_product
    ORDER BY v.kode_product";

    $query = $this->db->query($sql, array($year));
    return $query->result_array();
}

/**
 * Regenerate PPM data for a specific year
 * This method populates the ppm_detail table with production data
 */
public function regenerate_ppm_data($tahun)
{
    // Clear existing data for the year
    $this->db->where('tahun', $tahun);
    $this->db->delete('ppm_detail');
    
    // Get production data grouped by product for each month
    $sql = "
        SELECT 
            pr.kode_product,
            pr.nama_product,
            pr.cost,
            YEAR(po.tanggal) AS tahun,
            
            -- Monthly totals for production (OK + NG)
            SUM(CASE WHEN MONTH(po.tanggal) = 1 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod1,
            SUM(CASE WHEN MONTH(po.tanggal) = 2 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod2,
            SUM(CASE WHEN MONTH(po.tanggal) = 3 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod3,
            SUM(CASE WHEN MONTH(po.tanggal) = 4 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod4,
            SUM(CASE WHEN MONTH(po.tanggal) = 5 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod5,
            SUM(CASE WHEN MONTH(po.tanggal) = 6 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod6,
            SUM(CASE WHEN MONTH(po.tanggal) = 7 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod7,
            SUM(CASE WHEN MONTH(po.tanggal) = 8 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod8,
            SUM(CASE WHEN MONTH(po.tanggal) = 9 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod9,
            SUM(CASE WHEN MONTH(po.tanggal) = 10 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod10,
            SUM(CASE WHEN MONTH(po.tanggal) = 11 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod11,
            SUM(CASE WHEN MONTH(po.tanggal) = 12 THEN po.qty_ok + po.qty_ng ELSE 0 END) AS tot_prod12,
            
            -- Monthly OK quantities
            SUM(CASE WHEN MONTH(po.tanggal) = 1 THEN po.qty_ok ELSE 0 END) AS ok1,
            SUM(CASE WHEN MONTH(po.tanggal) = 2 THEN po.qty_ok ELSE 0 END) AS ok2,
            SUM(CASE WHEN MONTH(po.tanggal) = 3 THEN po.qty_ok ELSE 0 END) AS ok3,
            SUM(CASE WHEN MONTH(po.tanggal) = 4 THEN po.qty_ok ELSE 0 END) AS ok4,
            SUM(CASE WHEN MONTH(po.tanggal) = 5 THEN po.qty_ok ELSE 0 END) AS ok5,
            SUM(CASE WHEN MONTH(po.tanggal) = 6 THEN po.qty_ok ELSE 0 END) AS ok6,
            SUM(CASE WHEN MONTH(po.tanggal) = 7 THEN po.qty_ok ELSE 0 END) AS ok7,
            SUM(CASE WHEN MONTH(po.tanggal) = 8 THEN po.qty_ok ELSE 0 END) AS ok8,
            SUM(CASE WHEN MONTH(po.tanggal) = 9 THEN po.qty_ok ELSE 0 END) AS ok9,
            SUM(CASE WHEN MONTH(po.tanggal) = 10 THEN po.qty_ok ELSE 0 END) AS ok10,
            SUM(CASE WHEN MONTH(po.tanggal) = 11 THEN po.qty_ok ELSE 0 END) AS ok11,
            SUM(CASE WHEN MONTH(po.tanggal) = 12 THEN po.qty_ok ELSE 0 END) AS ok12,
            
            -- Monthly NG quantities
            SUM(CASE WHEN MONTH(po.tanggal) = 1 THEN po.qty_ng ELSE 0 END) AS ng1,
            SUM(CASE WHEN MONTH(po.tanggal) = 2 THEN po.qty_ng ELSE 0 END) AS ng2,
            SUM(CASE WHEN MONTH(po.tanggal) = 3 THEN po.qty_ng ELSE 0 END) AS ng3,
            SUM(CASE WHEN MONTH(po.tanggal) = 4 THEN po.qty_ng ELSE 0 END) AS ng4,
            SUM(CASE WHEN MONTH(po.tanggal) = 5 THEN po.qty_ng ELSE 0 END) AS ng5,
            SUM(CASE WHEN MONTH(po.tanggal) = 6 THEN po.qty_ng ELSE 0 END) AS ng6,
            SUM(CASE WHEN MONTH(po.tanggal) = 7 THEN po.qty_ng ELSE 0 END) AS ng7,
            SUM(CASE WHEN MONTH(po.tanggal) = 8 THEN po.qty_ng ELSE 0 END) AS ng8,
            SUM(CASE WHEN MONTH(po.tanggal) = 9 THEN po.qty_ng ELSE 0 END) AS ng9,
            SUM(CASE WHEN MONTH(po.tanggal) = 10 THEN po.qty_ng ELSE 0 END) AS ng10,
            SUM(CASE WHEN MONTH(po.tanggal) = 11 THEN po.qty_ng ELSE 0 END) AS ng11,
            SUM(CASE WHEN MONTH(po.tanggal) = 12 THEN po.qty_ng ELSE 0 END) AS ng12
            
        FROM t_production_op po
        LEFT JOIN t_bom b ON po.id_bom = b.id_bom
        LEFT JOIN t_product pr ON b.id_product = pr.id_product
        WHERE YEAR(po.tanggal) = ? 
        AND pr.kode_product IS NOT NULL
        GROUP BY pr.kode_product, pr.nama_product, pr.cost
    ";
    
    $query = $this->db->query($sql, array($tahun));
    $results = $query->result_array();
    
    // Insert processed data into ppm_detail table
    foreach ($results as $row) {
        // Calculate totals and percentages
        $total_prod = $row['tot_prod1'] + $row['tot_prod2'] + $row['tot_prod3'] + $row['tot_prod4'] + 
                      $row['tot_prod5'] + $row['tot_prod6'] + $row['tot_prod7'] + $row['tot_prod8'] + 
                      $row['tot_prod9'] + $row['tot_prod10'] + $row['tot_prod11'] + $row['tot_prod12'];
                      
        $total_ok = $row['ok1'] + $row['ok2'] + $row['ok3'] + $row['ok4'] + 
                    $row['ok5'] + $row['ok6'] + $row['ok7'] + $row['ok8'] + 
                    $row['ok9'] + $row['ok10'] + $row['ok11'] + $row['ok12'];
                    
        $total_ng = $row['ng1'] + $row['ng2'] + $row['ng3'] + $row['ng4'] + 
                    $row['ng5'] + $row['ng6'] + $row['ng7'] + $row['ng8'] + 
                    $row['ng9'] + $row['ng10'] + $row['ng11'] + $row['ng12'];
        
        // Calculate percentage and PPM
        $total_persen_ng = $total_prod > 0 ? ($total_ng / $total_prod) * 100 : 0;
        $total_ppm = $total_prod > 0 ? ($total_ng / $total_prod) * 1000000 : 0;
        
        // Calculate monthly sub-totals (failure cost)
        $sub_total1 = ($row['tot_prod1'] > 0) ? $row['tot_prod1'] * $row['cost'] : 0;
        $sub_total2 = ($row['tot_prod2'] > 0) ? $row['tot_prod2'] * $row['cost'] : 0;
        $sub_total3 = ($row['tot_prod3'] > 0) ? $row['tot_prod3'] * $row['cost'] : 0;
        $sub_total4 = ($row['tot_prod4'] > 0) ? $row['tot_prod4'] * $row['cost'] : 0;
        $sub_total5 = ($row['tot_prod5'] > 0) ? $row['tot_prod5'] * $row['cost'] : 0;
        $sub_total6 = ($row['tot_prod6'] > 0) ? $row['tot_prod6'] * $row['cost'] : 0;
        $sub_total7 = ($row['tot_prod7'] > 0) ? $row['tot_prod7'] * $row['cost'] : 0;
        $sub_total8 = ($row['tot_prod8'] > 0) ? $row['tot_prod8'] * $row['cost'] : 0;
        $sub_total9 = ($row['tot_prod9'] > 0) ? $row['tot_prod9'] * $row['cost'] : 0;
        $sub_total10 = ($row['tot_prod10'] > 0) ? $row['tot_prod10'] * $row['cost'] : 0;
        $sub_total11 = ($row['tot_prod11'] > 0) ? $row['tot_prod11'] * $row['cost'] : 0;
        $sub_total12 = ($row['tot_prod12'] > 0) ? $row['tot_prod12'] * $row['cost'] : 0;
        
        // Prepare data for insertion
        $data = array(
            'tahun' => $tahun,
            'kode_product' => $row['kode_product'],
            'nama_product' => $row['nama_product'],
            'tot_prod1' => $row['tot_prod1'],
            'ok1' => $row['ok1'],
            'ng1' => $row['ng1'],
            'tot_prod2' => $row['tot_prod2'],
            'ok2' => $row['ok2'],
            'ng2' => $row['ng2'],
            'tot_prod3' => $row['tot_prod3'],
            'ok3' => $row['ok3'],
            'ng3' => $row['ng3'],
            'tot_prod4' => $row['tot_prod4'],
            'ok4' => $row['ok4'],
            'ng4' => $row['ng4'],
            'tot_prod5' => $row['tot_prod5'],
            'ok5' => $row['ok5'],
            'ng5' => $row['ng5'],
            'tot_prod6' => $row['tot_prod6'],
            'ok6' => $row['ok6'],
            'ng6' => $row['ng6'],
            'tot_prod7' => $row['tot_prod7'],
            'ok7' => $row['ok7'],
            'ng7' => $row['ng7'],
            'tot_prod8' => $row['tot_prod8'],
            'ok8' => $row['ok8'],
            'ng8' => $row['ng8'],
            'tot_prod9' => $row['tot_prod9'],
            'ok9' => $row['ok9'],
            'ng9' => $row['ng9'],
            'tot_prod10' => $row['tot_prod10'],
            'ok10' => $row['ok10'],
            'ng10' => $row['ng10'],
            'tot_prod11' => $row['tot_prod11'],
            'ok11' => $row['ok11'],
            'ng11' => $row['ng11'],
            'tot_prod12' => $row['tot_prod12'],
            'ok12' => $row['ok12'],
            'ng12' => $row['ng12'],
            'total_prod' => $total_prod,
            'qty_ok' => $total_ok,
            'qty_ng' => $total_ng,
            'persen_ng' => $total_persen_ng,
            'ppm' => $total_ppm,
            'sub_total1' => $sub_total1,
            'sub_total2' => $sub_total2,
            'sub_total3' => $sub_total3,
            'sub_total4' => $sub_total4,
            'sub_total5' => $sub_total5,
            'sub_total6' => $sub_total6,
            'sub_total7' => $sub_total7,
            'sub_total8' => $sub_total8,
            'sub_total9' => $sub_total9,
            'sub_total10' => $sub_total10,
            'sub_total11' => $sub_total11,
            'sub_total12' => $sub_total12
        );
        
        // Insert into ppm_detail table
        $this->db->insert('ppm_detail', $data);
    }
    
    return count($results);
}

/**
 * Check if PPM data exists for a given year
 */
public function check_ppm_data_exists($tahun)
{
    $this->db->where('tahun', $tahun);
    $query = $this->db->get('ppm_detail');
    return $query->num_rows() > 0;
}

/**
 * Fixed version of tampil_grafikPPM to avoid current month comparison bug
 */
public function tampil_grafikPPM_fixed($tahun)
{
    // Build dynamic monthly columns
    $select_parts = array("YEAR(q.tanggal) AS tahun");
    
    for ($month = 1; $month <= 12; $month++) {
        $select_parts[] = "SUM(CASE WHEN MONTH(q.tanggal) = {$month} THEN (q.qty_ok + q.qty_ng) ELSE 0 END) AS total_prod{$month}";
        $select_parts[] = "SUM(CASE WHEN MONTH(q.tanggal) = {$month} THEN q.qty_ok ELSE 0 END) AS ok{$month}";
        $select_parts[] = "SUM(CASE WHEN MONTH(q.tanggal) = {$month} THEN q.qty_ng ELSE 0 END) AS ng{$month}";
        
        // Add PPM calculation for each month
        $select_parts[] = "ROUND(
            (SUM(CASE WHEN MONTH(q.tanggal) = {$month} THEN q.qty_ng ELSE 0 END) / 
             NULLIF(SUM(CASE WHEN MONTH(q.tanggal) = {$month} THEN (q.qty_ok + q.qty_ng) ELSE 0 END), 0)
            ) * 1000000
        ) AS ppm{$month}";
    }
    
    $sql = "SELECT " . implode(",\n    ", $select_parts) . "
    FROM v_production_op AS q
    WHERE YEAR(q.tanggal) = ?
    GROUP BY YEAR(q.tanggal)";
    
    $query = $this->db->query($sql, array($tahun));
    return $query;
}

}