<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class M_dpr extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
    $this->load->helper(array('url', 'html', 'form'));
  }



  public function tampil_production($table, $tanggal_dari, $tanggal_sampai, $shift)
  {
    $nama = $_SESSION['nama_actor'];
    if ($_SESSION['posisi'] == 'kanit') {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = $this->db->where('shift', $shift);
      }
      $this->db->select('*');
      $this->db->from($table);
      $this->db->where('tanggal >=', $tanggal_dari);
      $this->db->where('tanggal <=', $tanggal_sampai);
      $this->db->where('kanit', $nama);
      $shiftNya;
      $this->db->order_by('cek_kanit', 'DESC');
      // $this->db->order_by('kanit' , 'ASC');
      $this->db->order_by('tanggal', 'ASC');
      $this->db->order_by('mesin', 'ASC');
      return $this->db->get();
    } else {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = $this->db->where('shift', $shift);
      }
      $this->db->select('*');
      $this->db->from($table);
      $this->db->where('tanggal >=', $tanggal_dari);
      $this->db->where('tanggal <=', $tanggal_sampai);
      $shiftNya;

      $this->db->order_by('cek_kanit', 'DESC');
      $this->db->order_by('kanit', 'ASC');
      $this->db->order_by('tanggal', 'ASC');
      $this->db->order_by('mesin', 'ASC');
      return $this->db->get();
    }
  }

  public function tampil_production_rev($tanggal_dari, $tanggal_sampai, $shift)
  {
    $nama = $_SESSION['nama_actor'];

    if ($_SESSION['posisi'] == 'kanit') {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = 'AND q.shift = ' . $shift . '';
      }
      $query = $this->db->query("SELECT q.*, w.line,
              SUM((CASE WHEN e.nama = 'BENDING' THEN e.qty END)) AS bending,
              SUM((CASE WHEN e.nama = 'BERAWAN' THEN e.qty END)) AS berawan,
              SUM((CASE WHEN e.nama = 'BLACKDOT' THEN e.qty END)) AS blackdot,
              SUM((CASE WHEN e.nama = 'BROKEN' THEN e.qty END)) AS broken,
              SUM((CASE WHEN e.nama = 'CRACK' THEN e.qty END)) AS crack,
              SUM((CASE WHEN e.nama = 'DENT' THEN e.qty END)) AS dent,
              SUM((CASE WHEN e.nama = 'DIRTY' THEN e.qty END)) AS dirty,
              SUM((CASE WHEN e.nama = 'DISCOLOUR' THEN e.qty END)) AS discolour,
              SUM((CASE WHEN e.nama = 'EJECTOR MARK' THEN e.qty END)) AS ejector_mark,
              SUM((CASE WHEN e.nama = 'FLASH' THEN e.qty END)) AS flash,
              SUM((CASE WHEN e.nama = 'FLOW GATE' THEN e.qty END)) AS flow_gate,
              SUM((CASE WHEN e.nama = 'FLOW MARK' THEN e.qty END)) AS flow_mark,
              SUM((CASE WHEN e.nama = 'FOREIGN MATERIAL' THEN e.qty END)) AS fm,
              SUM((CASE WHEN e.nama = 'GAS BURN' THEN e.qty END)) AS gas_burn,
              SUM((CASE WHEN e.nama = 'GAS MARK' THEN e.qty END)) AS gas_mark,
              SUM((CASE WHEN e.nama = 'GATE BOLONG' THEN e.qty END)) AS gate_bolong,
              SUM((CASE WHEN e.nama = 'GATE LONG' THEN e.qty END)) AS gate_long,
              SUM((CASE WHEN e.nama = 'HANGUS' THEN e.qty END)) AS hangus,
              SUM((CASE WHEN e.nama = 'HIKE' THEN e.qty END)) AS hike,
              SUM((CASE WHEN e.nama = 'OIL' THEN e.qty END)) AS oil,
              SUM((CASE WHEN e.nama = 'OVERSIZE' THEN e.qty END)) AS oversize,
              SUM((CASE WHEN e.nama = 'PIN PLONG' THEN e.qty END)) AS pin_plong,
              SUM((CASE WHEN e.nama = 'PIN SERET' THEN e.qty END)) AS pin_seret,
              SUM((CASE WHEN e.nama = 'SCRATCH' THEN e.qty END)) AS scratch,
              SUM((CASE WHEN e.nama = 'SETTINGAN' THEN e.qty END)) AS settingan,
              SUM((CASE WHEN e.nama = 'SHORT SHOOT' THEN e.qty END)) AS short_shoot,
              SUM((CASE WHEN e.nama = 'SILVER' THEN e.qty END)) AS silver,
              SUM((CASE WHEN e.nama = 'SINK MARK' THEN e.qty END)) AS sink_mark,
              SUM((CASE WHEN e.nama = 'UNDERCUT' THEN e.qty END)) AS undercut,
              SUM((CASE WHEN e.nama = 'UNDERSIZE' THEN e.qty END)) AS under_size,
              SUM((CASE WHEN e.nama = 'VOID' THEN e.qty END)) AS void,
              SUM((CASE WHEN e.nama = 'WAVING' THEN e.qty END)) AS waving,
              SUM((CASE WHEN e.nama = 'WELD LINE' THEN e.qty END)) AS weld_line,
              SUM((CASE WHEN e.nama = 'WHITE DOT' THEN e.qty END)) AS white_dot,
              SUM((CASE WHEN e.nama = 'WHITE MARK' THEN e.qty END)) AS white_mark,
              ROUND(SUM((CASE WHEN e.nama = 'ADJUST PARAMETER' THEN e.qty END)),2) AS adjust_parameter,
              ROUND(SUM((CASE WHEN e.nama = 'PRE HEATING MATERIAL' THEN e.qty END)),2) AS pre_heating_material,
              ROUND(SUM((CASE WHEN e.nama = 'CLEANING HOPPER & BARREL' THEN e.qty END)),2) AS cleaning,
              ROUND(SUM((CASE WHEN e.nama = 'SET UP MOLD' THEN e.qty END)),2) AS set_up_mold,
              ROUND(SUM((CASE WHEN e.nama = 'SET UP PARAMETER MACHINE' THEN e.qty END)),2) AS set_up_par_machine,
              ROUND(SUM((CASE WHEN e.nama = 'IPQC INSPECTION' THEN e.qty END)),2) AS ipqc_inspection,
              ROUND(SUM((CASE WHEN e.nama = 'NO PACKING' THEN e.qty END)),2) AS no_packing,
              ROUND(SUM((CASE WHEN e.nama = 'NO MATERIAL' THEN e.qty END)),2) AS no_material,
              ROUND(SUM((CASE WHEN e.nama = 'MATERIAL PROBLEM' THEN e.qty END)),2) AS material_problem,
              ROUND(SUM((CASE WHEN e.nama = 'NO OPERATOR' THEN e.qty END)),2) AS no_operator,
              ROUND(SUM((CASE WHEN e.nama = 'DAILY CHECK LIST' THEN e.qty END)),2) AS daily_check_list,
              ROUND(SUM((CASE WHEN e.nama = 'OVERHOULE MOLD' THEN e.qty END)),2) AS overhoule_mold,
              ROUND(SUM((CASE WHEN e.nama = 'MOLD PROBLEM' THEN e.qty END)),2) AS mold_problem,
              ROUND(SUM((CASE WHEN e.nama = 'TRIAL' THEN e.qty END)),2) AS trial,
              ROUND(SUM((CASE WHEN e.nama = 'MACHINE' THEN e.qty END)),2) AS machine,
              ROUND(SUM((CASE WHEN e.nama = 'HOPPER DRYER' THEN e.qty END)),2) AS hopper_dryer,
              ROUND(SUM((CASE WHEN e.nama = 'ROBOT' THEN e.qty END)),2) AS robot,
              ROUND(SUM((CASE WHEN e.nama = 'MTC' THEN e.qty END)),2) AS mtc,
              ROUND(SUM((CASE WHEN e.nama = 'COOLING TOWER' THEN e.qty END)),2) AS cooling_tower,
              ROUND(SUM((CASE WHEN e.nama = 'COMPRESSOR' THEN e.qty END)),2) AS compressor,
              ROUND(SUM((CASE WHEN e.nama = 'LISTRIK' THEN e.qty END)),2) AS listrik,
              ROUND(SUM((CASE WHEN e.nama = 'QC LOLOS' THEN e.qty END)),2) AS qc_lolos,
              ROUND(SUM(
                CASE 
                  WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`production_time` / q.`nwt_mp`
                  WHEN COALESCE(q.`ot_mp`, 0) > 0 THEN q.`production_time` / q.`ot_mp`
                  ELSE 0
                END
              ) * 100, 2) AS mach_use,
              ROUND(SUM(
                CASE 
                  WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`ot_mp` / q.`nwt_mp`
                  ELSE 0
                END
              ) * 100, 2) AS persen_ot,
              q.`runner`,q.`loss_purge`,q.`keterangan`, 
              GROUP_CONCAT(DISTINCT ct.`cutting_tools_id`) AS cutting_tools_ids,
              GROUP_CONCAT(DISTINCT cts.`code`) AS cutting_tools_codes
              FROM `v_production_op` AS q
              LEFT JOIN t_operator AS w ON q.`kanit` = w.`nama_operator`
              LEFT JOIN t_production_op_dl AS e ON q.`id_production` = e.`id_production`
              LEFT JOIN t_production_op_cutting_tools_usage AS ct ON q.`id_production` = ct.`id_production`
              LEFT JOIN cutting_tools AS cts ON ct.`cutting_tools_id` = cts.`id`
              WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai' AND q.`kanit` = '$nama'
              $shiftNya
              GROUP BY q.`id_production`
              ORDER BY q.`cek_kanit` DESC, q.`tanggal`, q.`mesin` ASC");
      return $query;
    } else {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = 'AND q.shift = ' . $shift . '';
      }
      $query = $this->db->query("SELECT q.*, w.line,
            SUM((CASE WHEN e.nama = 'BENDING' THEN e.qty END)) AS bending,
            SUM((CASE WHEN e.nama = 'BERAWAN' THEN e.qty END)) AS berawan,
            SUM((CASE WHEN e.nama = 'BLACKDOT' THEN e.qty END)) AS blackdot,
            SUM((CASE WHEN e.nama = 'BROKEN' THEN e.qty END)) AS broken,
            SUM((CASE WHEN e.nama = 'CRACK' THEN e.qty END)) AS crack,
            SUM((CASE WHEN e.nama = 'DENT' THEN e.qty END)) AS dent,
            SUM((CASE WHEN e.nama = 'DIRTY' THEN e.qty END)) AS dirty,
            SUM((CASE WHEN e.nama = 'DISCOLOUR' THEN e.qty END)) AS discolour,
            SUM((CASE WHEN e.nama = 'EJECTOR MARK' THEN e.qty END)) AS ejector_mark,
            SUM((CASE WHEN e.nama = 'FLASH' THEN e.qty END)) AS flash,
            SUM((CASE WHEN e.nama = 'FLOW GATE' THEN e.qty END)) AS flow_gate,
            SUM((CASE WHEN e.nama = 'FLOW MARK' THEN e.qty END)) AS flow_mark,
            SUM((CASE WHEN e.nama = 'FOREIGN MATERIAL' THEN e.qty END)) AS fm,
            SUM((CASE WHEN e.nama = 'GAS BURN' THEN e.qty END)) AS gas_burn,
            SUM((CASE WHEN e.nama = 'GAS MARK' THEN e.qty END)) AS gas_mark,
            SUM((CASE WHEN e.nama = 'GATE BOLONG' THEN e.qty END)) AS gate_bolong,
            SUM((CASE WHEN e.nama = 'GATE LONG' THEN e.qty END)) AS gate_long,
            SUM((CASE WHEN e.nama = 'HANGUS' THEN e.qty END)) AS hangus,
            SUM((CASE WHEN e.nama = 'HIKE' THEN e.qty END)) AS hike,
            SUM((CASE WHEN e.nama = 'OIL' THEN e.qty END)) AS oil,
            SUM((CASE WHEN e.nama = 'OVERSIZE' THEN e.qty END)) AS oversize,
            SUM((CASE WHEN e.nama = 'PIN PLONG' THEN e.qty END)) AS pin_plong,
            SUM((CASE WHEN e.nama = 'PIN SERET' THEN e.qty END)) AS pin_seret,
            SUM((CASE WHEN e.nama = 'SCRATCH' THEN e.qty END)) AS scratch,
            SUM((CASE WHEN e.nama = 'SETTINGAN' THEN e.qty END)) AS settingan,
            SUM((CASE WHEN e.nama = 'SHORT SHOOT' THEN e.qty END)) AS short_shoot,
            SUM((CASE WHEN e.nama = 'SILVER' THEN e.qty END)) AS silver,
            SUM((CASE WHEN e.nama = 'SINK MARK' THEN e.qty END)) AS sink_mark,
            SUM((CASE WHEN e.nama = 'UNDERCUT' THEN e.qty END)) AS undercut,
            SUM((CASE WHEN e.nama = 'UNDERSIZE' THEN e.qty END)) AS under_size,
            SUM((CASE WHEN e.nama = 'VOID' THEN e.qty END)) AS void,
            SUM((CASE WHEN e.nama = 'WAVING' THEN e.qty END)) AS waving,
            SUM((CASE WHEN e.nama = 'WELD LINE' THEN e.qty END)) AS weld_line,
            SUM((CASE WHEN e.nama = 'WHITE DOT' THEN e.qty END)) AS white_dot,
            SUM((CASE WHEN e.nama = 'WHITE MARK' THEN e.qty END)) AS white_mark,
            ROUND(SUM((CASE WHEN e.nama = 'ADJUST PARAMETER' THEN e.qty END)),2) AS adjust_parameter,
            ROUND(SUM((CASE WHEN e.nama = 'PRE HEATING MATERIAL' THEN e.qty END)),2) AS pre_heating_material,
            ROUND(SUM((CASE WHEN e.nama = 'CLEANING HOPPER & BARREL' THEN e.qty END)),2) AS cleaning,
            ROUND(SUM((CASE WHEN e.nama = 'SET UP MOLD' THEN e.qty END)),2) AS set_up_mold,
            ROUND(SUM((CASE WHEN e.nama = 'SET UP PARAMETER MACHINE' THEN e.qty END)),2) AS set_up_par_machine,
            ROUND(SUM((CASE WHEN e.nama = 'IPQC INSPECTION' THEN e.qty END)),2) AS ipqc_inspection,
            ROUND(SUM((CASE WHEN e.nama = 'NO PACKING' THEN e.qty END)),2) AS no_packing,
            ROUND(SUM((CASE WHEN e.nama = 'NO MATERIAL' THEN e.qty END)),2) AS no_material,
            ROUND(SUM((CASE WHEN e.nama = 'MATERIAL PROBLEM' THEN e.qty END)),2) AS material_problem,
            ROUND(SUM((CASE WHEN e.nama = 'NO OPERATOR' THEN e.qty END)),2) AS no_operator,
            ROUND(SUM((CASE WHEN e.nama = 'DAILY CHECK LIST' THEN e.qty END)),2) AS daily_check_list,
            ROUND(SUM((CASE WHEN e.nama = 'OVERHOULE MOLD' THEN e.qty END)),2) AS overhoule_mold,
            ROUND(SUM((CASE WHEN e.nama = 'MOLD PROBLEM' THEN e.qty END)),2) AS mold_problem,
            ROUND(SUM((CASE WHEN e.nama = 'TRIAL' THEN e.qty END)),2) AS trial,
            ROUND(SUM((CASE WHEN e.nama = 'MACHINE' THEN e.qty END)),2) AS machine,
            ROUND(SUM((CASE WHEN e.nama = 'HOPPER DRYER' THEN e.qty END)),2) AS hopper_dryer,
            ROUND(SUM((CASE WHEN e.nama = 'ROBOT' THEN e.qty END)),2) AS robot,
            ROUND(SUM((CASE WHEN e.nama = 'MTC' THEN e.qty END)),2) AS mtc,
            ROUND(SUM((CASE WHEN e.nama = 'COOLING TOWER' THEN e.qty END)),2) AS cooling_tower,
            ROUND(SUM((CASE WHEN e.nama = 'COMPRESSOR' THEN e.qty END)),2) AS compressor,
            ROUND(SUM((CASE WHEN e.nama = 'LISTRIK' THEN e.qty END)),2) AS listrik,
            ROUND(SUM((CASE WHEN e.nama = 'QC LOLOS' THEN e.qty END)),2) AS qc_lolos,
            ROUND(SUM(
              CASE 
                WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`production_time` / q.`nwt_mp`
                WHEN COALESCE(q.`ot_mp`, 0) > 0 THEN q.`production_time` / q.`ot_mp`
                ELSE 0
              END
            ) * 100, 2) AS mach_use,
            ROUND(SUM(
              CASE 
                WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`ot_mp` / q.`nwt_mp`
                ELSE 0
              END
            ) * 100, 2) AS persen_ot,
            q.`runner`,q.`loss_purge`,q.`keterangan`, 
            GROUP_CONCAT(DISTINCT ct.`cutting_tools_id`) AS cutting_tools_ids,
            GROUP_CONCAT(DISTINCT cts.`code`) AS cutting_tools_codes
            FROM `v_production_op` AS q
            LEFT JOIN t_operator AS w ON q.`kanit` = w.`nama_operator`
            LEFT JOIN t_production_op_dl AS e ON q.`id_production` = e.`id_production`
            LEFT JOIN t_production_op_cutting_tools_usage AS ct ON q.`id_production` = ct.`id_production`
            LEFT JOIN cutting_tools AS cts ON ct.`cutting_tools_id` = cts.`id`
            WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
            $shiftNya
            GROUP BY q.`id_production`
            ORDER BY q.`cek_kanit` DESC, q.`kanit`, q.`tanggal`, q.`mesin` ASC");
      return $query;
    }
  }

  // New method for monthly export
  public function tampil_production_by_month($bulan, $tahun, $shift)
  {
    $nama = $_SESSION['nama_actor'];

    if ($_SESSION['posisi'] == 'kanit') {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = 'AND q.shift = ' . $shift . '';
      }

      $query = $this->db->query("SELECT q.*, w.line,
              SUM((CASE WHEN e.nama = 'BENDING' THEN e.qty END)) AS bending,
              SUM((CASE WHEN e.nama = 'BERAWAN' THEN e.qty END)) AS berawan,
              SUM((CASE WHEN e.nama = 'BLACKDOT' THEN e.qty END)) AS blackdot,
              SUM((CASE WHEN e.nama = 'BROKEN' THEN e.qty END)) AS broken,
              SUM((CASE WHEN e.nama = 'CRACK' THEN e.qty END)) AS crack,
              SUM((CASE WHEN e.nama = 'DENT' THEN e.qty END)) AS dent,
              SUM((CASE WHEN e.nama = 'DIRTY' THEN e.qty END)) AS dirty,
              SUM((CASE WHEN e.nama = 'DISCOLOUR' THEN e.qty END)) AS discolour,
              SUM((CASE WHEN e.nama = 'EJECTOR MARK' THEN e.qty END)) AS ejector_mark,
              SUM((CASE WHEN e.nama = 'FLASH' THEN e.qty END)) AS flash,
              SUM((CASE WHEN e.nama = 'FLOW GATE' THEN e.qty END)) AS flow_gate,
              SUM((CASE WHEN e.nama = 'FLOW MARK' THEN e.qty END)) AS flow_mark,
              SUM((CASE WHEN e.nama = 'FOREIGN MATERIAL' THEN e.qty END)) AS fm,
              SUM((CASE WHEN e.nama = 'GAS BURN' THEN e.qty END)) AS gas_burn,
              SUM((CASE WHEN e.nama = 'GAS MARK' THEN e.qty END)) AS gas_mark,
              SUM((CASE WHEN e.nama = 'GATE BOLONG' THEN e.qty END)) AS gate_bolong,
              SUM((CASE WHEN e.nama = 'GATE LONG' THEN e.qty END)) AS gate_long,
              SUM((CASE WHEN e.nama = 'HANGUS' THEN e.qty END)) AS hangus,
              SUM((CASE WHEN e.nama = 'HIKE' THEN e.qty END)) AS hike,
              SUM((CASE WHEN e.nama = 'OIL' THEN e.qty END)) AS oil,
              SUM((CASE WHEN e.nama = 'OVERSIZE' THEN e.qty END)) AS oversize,
              SUM((CASE WHEN e.nama = 'PIN PLONG' THEN e.qty END)) AS pin_plong,
              SUM((CASE WHEN e.nama = 'PIN SERET' THEN e.qty END)) AS pin_seret,
              SUM((CASE WHEN e.nama = 'SCRATCH' THEN e.qty END)) AS scratch,
              SUM((CASE WHEN e.nama = 'SETTINGAN' THEN e.qty END)) AS settingan,
              SUM((CASE WHEN e.nama = 'SHORT SHOOT' THEN e.qty END)) AS short_shoot,
              SUM((CASE WHEN e.nama = 'SILVER' THEN e.qty END)) AS silver,
              SUM((CASE WHEN e.nama = 'SINK MARK' THEN e.qty END)) AS sink_mark,
              SUM((CASE WHEN e.nama = 'UNDERCUT' THEN e.qty END)) AS undercut,
              SUM((CASE WHEN e.nama = 'UNDERSIZE' THEN e.qty END)) AS under_size,
              SUM((CASE WHEN e.nama = 'VOID' THEN e.qty END)) AS void,
              SUM((CASE WHEN e.nama = 'WAVING' THEN e.qty END)) AS waving,
              SUM((CASE WHEN e.nama = 'WELD LINE' THEN e.qty END)) AS weld_line,
              SUM((CASE WHEN e.nama = 'WHITE DOT' THEN e.qty END)) AS white_dot,
              SUM((CASE WHEN e.nama = 'WHITE MARK' THEN e.qty END)) AS white_mark,
              ROUND(SUM((CASE WHEN e.nama = 'ADJUST PARAMETER' THEN e.qty END)),2) AS adjust_parameter,
              ROUND(SUM((CASE WHEN e.nama = 'PRE HEATING MATERIAL' THEN e.qty END)),2) AS pre_heating_material,
              ROUND(SUM((CASE WHEN e.nama = 'CLEANING HOPPER & BARREL' THEN e.qty END)),2) AS cleaning,
              ROUND(SUM((CASE WHEN e.nama = 'SET UP MOLD' THEN e.qty END)),2) AS set_up_mold,
              ROUND(SUM((CASE WHEN e.nama = 'SET UP PARAMETER MACHINE' THEN e.qty END)),2) AS set_up_par_machine,
              ROUND(SUM((CASE WHEN e.nama = 'IPQC INSPECTION' THEN e.qty END)),2) AS ipqc_inspection,
              ROUND(SUM((CASE WHEN e.nama = 'NO PACKING' THEN e.qty END)),2) AS no_packing,
              ROUND(SUM((CASE WHEN e.nama = 'NO MATERIAL' THEN e.qty END)),2) AS no_material,
              ROUND(SUM((CASE WHEN e.nama = 'MATERIAL PROBLEM' THEN e.qty END)),2) AS material_problem,
              ROUND(SUM((CASE WHEN e.nama = 'NO OPERATOR' THEN e.qty END)),2) AS no_operator,
              ROUND(SUM((CASE WHEN e.nama = 'DAILY CHECK LIST' THEN e.qty END)),2) AS daily_check_list,
              ROUND(SUM((CASE WHEN e.nama = 'OVERHOULE MOLD' THEN e.qty END)),2) AS overhoule_mold,
              ROUND(SUM((CASE WHEN e.nama = 'MOLD PROBLEM' THEN e.qty END)),2) AS mold_problem,
              ROUND(SUM((CASE WHEN e.nama = 'TRIAL' THEN e.qty END)),2) AS trial,
              ROUND(SUM((CASE WHEN e.nama = 'MACHINE' THEN e.qty END)),2) AS machine,
              ROUND(SUM((CASE WHEN e.nama = 'HOPPER DRYER' THEN e.qty END)),2) AS hopper_dryer,
              ROUND(SUM((CASE WHEN e.nama = 'ROBOT' THEN e.qty END)),2) AS robot,
              ROUND(SUM((CASE WHEN e.nama = 'MTC' THEN e.qty END)),2) AS mtc,
              ROUND(SUM((CASE WHEN e.nama = 'COOLING TOWER' THEN e.qty END)),2) AS cooling_tower,
              ROUND(SUM((CASE WHEN e.nama = 'COMPRESSOR' THEN e.qty END)),2) AS compressor,
              ROUND(SUM((CASE WHEN e.nama = 'LISTRIK' THEN e.qty END)),2) AS listrik,
              ROUND(SUM((CASE WHEN e.nama = 'QC LOLOS' THEN e.qty END)),2) AS qc_lolos,
              ROUND(SUM(
                CASE 
                  WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`production_time` / q.`nwt_mp`
                  WHEN COALESCE(q.`ot_mp`, 0) > 0 THEN q.`production_time` / q.`ot_mp`
                  ELSE 0
                END
              ) * 100, 2) AS mach_use,
              ROUND(SUM(
                CASE 
                  WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`ot_mp` / q.`nwt_mp`
                  ELSE 0
                END
              ) * 100, 2) AS persen_ot,
              q.`runner`,q.`loss_purge`,q.`keterangan`, 
              GROUP_CONCAT(DISTINCT ct.`cutting_tools_id`) AS cutting_tools_ids,
              GROUP_CONCAT(DISTINCT cts.`code`) AS cutting_tools_codes
              FROM `v_production_op` AS q
              LEFT JOIN t_operator AS w ON q.`kanit` = w.`nama_operator`
              LEFT JOIN t_production_op_dl AS e ON q.`id_production` = e.`id_production`
              LEFT JOIN t_production_op_cutting_tools_usage AS ct ON q.`id_production` = ct.`id_production`
              LEFT JOIN cutting_tools AS cts ON ct.`cutting_tools_id` = cts.`id`
              WHERE YEAR(q.tanggal) = '$tahun' AND MONTH(q.tanggal) = '$bulan'
              AND q.kanit = '$nama'
              $shiftNya
              GROUP BY q.`id_production`
              ORDER BY q.`cek_kanit` DESC, q.`tanggal`, q.`mesin` ASC");
      return $query;
    } else {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = 'AND q.shift = ' . $shift . '';
      }

      $query = $this->db->query("SELECT q.*, w.line,
              SUM((CASE WHEN e.nama = 'BENDING' THEN e.qty END)) AS bending,
              SUM((CASE WHEN e.nama = 'BERAWAN' THEN e.qty END)) AS berawan,
              SUM((CASE WHEN e.nama = 'BLACKDOT' THEN e.qty END)) AS blackdot,
              SUM((CASE WHEN e.nama = 'BROKEN' THEN e.qty END)) AS broken,
              SUM((CASE WHEN e.nama = 'CRACK' THEN e.qty END)) AS crack,
              SUM((CASE WHEN e.nama = 'DENT' THEN e.qty END)) AS dent,
              SUM((CASE WHEN e.nama = 'DIRTY' THEN e.qty END)) AS dirty,
              SUM((CASE WHEN e.nama = 'DISCOLOUR' THEN e.qty END)) AS discolour,
              SUM((CASE WHEN e.nama = 'EJECTOR MARK' THEN e.qty END)) AS ejector_mark,
              SUM((CASE WHEN e.nama = 'FLASH' THEN e.qty END)) AS flash,
              SUM((CASE WHEN e.nama = 'FLOW GATE' THEN e.qty END)) AS flow_gate,
              SUM((CASE WHEN e.nama = 'FLOW MARK' THEN e.qty END)) AS flow_mark,
              SUM((CASE WHEN e.nama = 'FOREIGN MATERIAL' THEN e.qty END)) AS fm,
              SUM((CASE WHEN e.nama = 'GAS BURN' THEN e.qty END)) AS gas_burn,
              SUM((CASE WHEN e.nama = 'GAS MARK' THEN e.qty END)) AS gas_mark,
              SUM((CASE WHEN e.nama = 'GATE BOLONG' THEN e.qty END)) AS gate_bolong,
              SUM((CASE WHEN e.nama = 'GATE LONG' THEN e.qty END)) AS gate_long,
              SUM((CASE WHEN e.nama = 'HANGUS' THEN e.qty END)) AS hangus,
              SUM((CASE WHEN e.nama = 'HIKE' THEN e.qty END)) AS hike,
              SUM((CASE WHEN e.nama = 'OIL' THEN e.qty END)) AS oil,
              SUM((CASE WHEN e.nama = 'OVERSIZE' THEN e.qty END)) AS oversize,
              SUM((CASE WHEN e.nama = 'PIN PLONG' THEN e.qty END)) AS pin_plong,
              SUM((CASE WHEN e.nama = 'PIN SERET' THEN e.qty END)) AS pin_seret,
              SUM((CASE WHEN e.nama = 'SCRATCH' THEN e.qty END)) AS scratch,
              SUM((CASE WHEN e.nama = 'SETTINGAN' THEN e.qty END)) AS settingan,
              SUM((CASE WHEN e.nama = 'SHORT SHOOT' THEN e.qty END)) AS short_shoot,
              SUM((CASE WHEN e.nama = 'SILVER' THEN e.qty END)) AS silver,
              SUM((CASE WHEN e.nama = 'SINK MARK' THEN e.qty END)) AS sink_mark,
              SUM((CASE WHEN e.nama = 'UNDERCUT' THEN e.qty END)) AS undercut,
              SUM((CASE WHEN e.nama = 'UNDERSIZE' THEN e.qty END)) AS under_size,
              SUM((CASE WHEN e.nama = 'VOID' THEN e.qty END)) AS void,
              SUM((CASE WHEN e.nama = 'WAVING' THEN e.qty END)) AS waving,
              SUM((CASE WHEN e.nama = 'WELD LINE' THEN e.qty END)) AS weld_line,
              SUM((CASE WHEN e.nama = 'WHITE DOT' THEN e.qty END)) AS white_dot,
              SUM((CASE WHEN e.nama = 'WHITE MARK' THEN e.qty END)) AS white_mark,
              ROUND(SUM((CASE WHEN e.nama = 'ADJUST PARAMETER' THEN e.qty END)),2) AS adjust_parameter,
              ROUND(SUM((CASE WHEN e.nama = 'PRE HEATING MATERIAL' THEN e.qty END)),2) AS pre_heating_material,
              ROUND(SUM((CASE WHEN e.nama = 'CLEANING HOPPER & BARREL' THEN e.qty END)),2) AS cleaning,
              ROUND(SUM((CASE WHEN e.nama = 'SET UP MOLD' THEN e.qty END)),2) AS set_up_mold,
              ROUND(SUM((CASE WHEN e.nama = 'SET UP PARAMETER MACHINE' THEN e.qty END)),2) AS set_up_par_machine,
              ROUND(SUM((CASE WHEN e.nama = 'IPQC INSPECTION' THEN e.qty END)),2) AS ipqc_inspection,
              ROUND(SUM((CASE WHEN e.nama = 'NO PACKING' THEN e.qty END)),2) AS no_packing,
              ROUND(SUM((CASE WHEN e.nama = 'NO MATERIAL' THEN e.qty END)),2) AS no_material,
              ROUND(SUM((CASE WHEN e.nama = 'MATERIAL PROBLEM' THEN e.qty END)),2) AS material_problem,
              ROUND(SUM((CASE WHEN e.nama = 'NO OPERATOR' THEN e.qty END)),2) AS no_operator,
              ROUND(SUM((CASE WHEN e.nama = 'DAILY CHECK LIST' THEN e.qty END)),2) AS daily_check_list,
              ROUND(SUM((CASE WHEN e.nama = 'OVERHOULE MOLD' THEN e.qty END)),2) AS overhoule_mold,
              ROUND(SUM((CASE WHEN e.nama = 'MOLD PROBLEM' THEN e.qty END)),2) AS mold_problem,
              ROUND(SUM((CASE WHEN e.nama = 'TRIAL' THEN e.qty END)),2) AS trial,
              ROUND(SUM((CASE WHEN e.nama = 'MACHINE' THEN e.qty END)),2) AS machine,
              ROUND(SUM((CASE WHEN e.nama = 'HOPPER DRYER' THEN e.qty END)),2) AS hopper_dryer,
              ROUND(SUM((CASE WHEN e.nama = 'ROBOT' THEN e.qty END)),2) AS robot,
              ROUND(SUM((CASE WHEN e.nama = 'MTC' THEN e.qty END)),2) AS mtc,
              ROUND(SUM((CASE WHEN e.nama = 'COOLING TOWER' THEN e.qty END)),2) AS cooling_tower,
              ROUND(SUM((CASE WHEN e.nama = 'COMPRESSOR' THEN e.qty END)),2) AS compressor,
              ROUND(SUM((CASE WHEN e.nama = 'LISTRIK' THEN e.qty END)),2) AS listrik,
              ROUND(SUM((CASE WHEN e.nama = 'QC LOLOS' THEN e.qty END)),2) AS qc_lolos,
              ROUND(SUM(
                CASE 
                  WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`production_time` / q.`nwt_mp`
                  WHEN COALESCE(q.`ot_mp`, 0) > 0 THEN q.`production_time` / q.`ot_mp`
                  ELSE 0
                END
              ) * 100, 2) AS mach_use,
              ROUND(SUM(
                CASE 
                  WHEN COALESCE(q.`nwt_mp`, 0) > 0 THEN q.`ot_mp` / q.`nwt_mp`
                  ELSE 0
                END
              ) * 100, 2) AS persen_ot,
              q.`runner`,q.`loss_purge`,q.`keterangan`, 
              GROUP_CONCAT(DISTINCT ct.`cutting_tools_id`) AS cutting_tools_ids,
              GROUP_CONCAT(DISTINCT cts.`code`) AS cutting_tools_codes
              FROM `v_production_op` AS q
              LEFT JOIN t_operator AS w ON q.`kanit` = w.`nama_operator`
              LEFT JOIN t_production_op_dl AS e ON q.`id_production` = e.`id_production`
              LEFT JOIN t_production_op_cutting_tools_usage AS ct ON q.`id_production` = ct.`id_production`
              LEFT JOIN cutting_tools AS cts ON ct.`cutting_tools_id` = cts.`id`
              WHERE YEAR(q.tanggal) = '$tahun' AND MONTH(q.tanggal) = '$bulan'
              $shiftNya
              GROUP BY q.`id_production`
              ORDER BY q.`cek_kanit` DESC, q.`kanit`, q.`tanggal`, q.`mesin` ASC");
      return $query;
    }
  }

  public function tampil_production_ok_only($tanggal_dari, $tanggal_sampai, $shift)
  {
    $nama = $_SESSION['nama_actor'];

    if ($_SESSION['posisi'] == 'kanit') {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = 'AND q.shift = ' . $shift . '';
      }

      $query = $this->db->query("
            SELECT 
                 q.`kode_product`,q.`nama_product`,SUM(q.qty_ok) AS total_ok
            FROM `v_production_op` AS q
            WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
            AND q.`kanit` = '$nama'
            $shiftNya
            GROUP BY q.`kode_product`
            ORDER BY q.`cek_kanit` DESC, q.`tanggal`, q.`mesin` ASC");
      return $query;
    } else {
      if ($shift == 'All') {
        $shiftNya = '';
      } else {
        $shiftNya = 'AND q.shift = ' . $shift . '';
      }

      $query = $this->db->query("
            SELECT 
               q.`kode_product`,q.`nama_product`,SUM(q.qty_ok) AS total_ok
            FROM `v_production_op` AS q
            WHERE q.`tanggal` BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
            $shiftNya
            GROUP BY q.`kode_product`
            ORDER BY q.`cek_kanit` DESC, q.`kanit`, q.`tanggal`, q.`mesin` ASC");
      return $query;
    }
  }


  function tampil_select_group($table, $where, $where_id, $order)
  {
    $q = "SELECT * FROM $table where $where = '$where_id' order by $order ASC ";
    $query = $result = $this->db->query($q);
    if ($result->num_rows() > 0) {
      return $result->result_array();
    } else {
      return array();
    }
  }

  public function onlineReport($tanggal, $jenis)
  {
    $pilihan      = $this->input->post('pilihan');
    if ($pilihan == 1) {
      $pilihanNya = 'p.id_product ,';
    } else if ($pilihan == 2) {
      $pilihanNya = 'p.id_product , p.mesin ,';
    } else if ($pilihan == 3) {
      $pilihanNya = 'p.id_product , p.mesin , p.shift , ';
    } else if ($pilihan == 4) {
      $pilihanNya = 'p.id_product , p.mesin , p.shift , p.kanit ,';
    } else {
      $pilihanNya = 'p.id_product ,';
    }

    $query = $this->db->query("SELECT *  , SUM(p.`$jenis`) AS total, 
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '01') THEN `p`.`$jenis` ELSE NULL END)) AS `t1$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '02') THEN `p`.`$jenis` ELSE NULL END)) AS `t2$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '03') THEN `p`.`$jenis` ELSE NULL END)) AS `t3$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '04') THEN `p`.`$jenis` ELSE NULL END)) AS `t4$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '05') THEN `p`.`$jenis` ELSE NULL END)) AS `t5$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '06') THEN `p`.`$jenis` ELSE NULL END)) AS `t6$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '07') THEN `p`.`$jenis` ELSE NULL END)) AS `t7$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '08') THEN `p`.`$jenis` ELSE NULL END)) AS `t8$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '09') THEN `p`.`$jenis` ELSE NULL END)) AS `t9$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '10') THEN `p`.`$jenis` ELSE NULL END)) AS `t10$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '11') THEN `p`.`$jenis` ELSE NULL END)) AS `t11$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '12') THEN `p`.`$jenis` ELSE NULL END)) AS `t12$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '13') THEN `p`.`$jenis` ELSE NULL END)) AS `t13$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '14') THEN `p`.`$jenis` ELSE NULL END)) AS `t14$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '15') THEN `p`.`$jenis` ELSE NULL END)) AS `t15$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '16') THEN `p`.`$jenis` ELSE NULL END)) AS `t16$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '17') THEN `p`.`$jenis` ELSE NULL END)) AS `t17$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '18') THEN `p`.`$jenis` ELSE NULL END)) AS `t18$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '19') THEN `p`.`$jenis` ELSE NULL END)) AS `t19$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '20') THEN `p`.`$jenis` ELSE NULL END)) AS `t20$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '21') THEN `p`.`$jenis` ELSE NULL END)) AS `t21$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '22') THEN `p`.`$jenis` ELSE NULL END)) AS `t22$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '23') THEN `p`.`$jenis` ELSE NULL END)) AS `t23$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '24') THEN `p`.`$jenis` ELSE NULL END)) AS `t24$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '25') THEN `p`.`$jenis` ELSE NULL END)) AS `t25$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '26') THEN `p`.`$jenis` ELSE NULL END)) AS `t26$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '27') THEN `p`.`$jenis` ELSE NULL END)) AS `t27$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '28') THEN `p`.`$jenis` ELSE NULL END)) AS `t28$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '29') THEN `p`.`$jenis` ELSE NULL END)) AS `t29$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '30') THEN `p`.`$jenis` ELSE NULL END)) AS `t30$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,9,2) = '31') THEN `p`.`$jenis` ELSE NULL END)) AS `t31$jenis`
                              FROM `v_production_op` AS p 
                              WHERE SUBSTR(p.tanggal,1,7) = '$tanggal'
                              GROUP BY $pilihanNya  SUBSTR(p.tanggal,1,7)
                              order by p.`mesin` ");

    return $query;
  }

  public function onlineGrafik($tanggal, $jenis)
  {
    $pilihan      = $this->input->post('pilihan');
    if ($pilihan == 1) {
      $pilihanNya = 'p.id_product ,';
    } else if ($pilihan == 2) {
      $pilihanNya = 'p.id_product , p.mesin ,';
    } else if ($pilihan == 3) {
      $pilihanNya = 'p.id_product , p.mesin , p.shift , ';
    } else if ($pilihan == 4) {
      $pilihanNya = 'p.id_product , p.mesin , p.shift , p.kanit ,';
    } else {
      $pilihanNya = 'p.id_product ,';
    }

    $query = $this->db->query("SELECT * ,  MAX(total) AS totalitas FROM (SELECT p.* ,  SUM(p.$jenis) AS total 
        FROM `v_production_op` AS p 
        WHERE SUBSTR(p.tanggal,1,7) = '$tanggal'
        GROUP BY $pilihanNya  SUBSTR(p.tanggal,1,7)) AS p
        GROUP BY $pilihanNya  SUBSTR(p.tanggal,1,7)
        ORDER BY MAX(total) DESC
        LIMIT 10");
    return $query;
  }


  public function onlineYear($tanggal, $jenis)
  {
    $tahun = substr($tanggal, 0, 4);
    $query = $this->db->query("SELECT * , SUM(p.`$jenis`) AS total, 
                    SUM(p.`qty_lt`) AS total_lt, SUM(p.`nwt_mp`) AS total_nwt,  SUM(p.`ot_mp`) AS total_ot,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '01') THEN `p`.`$jenis` ELSE NULL END)) AS `t1$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '02') THEN `p`.`$jenis` ELSE NULL END)) AS `t2$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '03') THEN `p`.`$jenis` ELSE NULL END)) AS `t3$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '04') THEN `p`.`$jenis` ELSE NULL END)) AS `t4$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '05') THEN `p`.`$jenis` ELSE NULL END)) AS `t5$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '06') THEN `p`.`$jenis` ELSE NULL END)) AS `t6$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '07') THEN `p`.`$jenis` ELSE NULL END)) AS `t7$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '08') THEN `p`.`$jenis` ELSE NULL END)) AS `t8$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '09') THEN `p`.`$jenis` ELSE NULL END)) AS `t9$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '10') THEN `p`.`$jenis` ELSE NULL END)) AS `t10$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '11') THEN `p`.`$jenis` ELSE NULL END)) AS `t11$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '12') THEN `p`.`$jenis` ELSE NULL END)) AS `t12$jenis`
                              FROM `v_production_op` AS p 
                              WHERE SUBSTR(p.tanggal,1,4) = '$tahun'
                              GROUP BY p.id_product ,  SUBSTR(p.tanggal,1,4)
                              order by p.`mesin` ");

    return $query;
  }

  public function reportByCustMonthly($tanggal, $jenis)
  {
    $tahun = substr($tanggal, 0, 4);
    $bulan = substr($tanggal, 5, 2);
    $query = $this->db->query("SELECT
         q.kp_pr AS kode_product,
    q.nama_product AS nama_product,
    pr.customer,

        SUM(CASE WHEN DAY(q.tanggal) = 1 THEN `q`.`$jenis` ELSE 0 END) AS `t1$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 2 THEN `q`.`$jenis` ELSE 0 END) AS `t2$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 3 THEN `q`.`$jenis` ELSE 0 END) AS `t3$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 4 THEN `q`.`$jenis` ELSE 0 END) AS `t4$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 5 THEN `q`.`$jenis` ELSE 0 END) AS `t5$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 6 THEN `q`.`$jenis` ELSE 0 END) AS `t6$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 7 THEN `q`.`$jenis` ELSE 0 END) AS `t7$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 8 THEN `q`.`$jenis` ELSE 0 END) AS `t8$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 9 THEN `q`.`$jenis` ELSE 0 END) AS `t9$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 10 THEN `q`.`$jenis` ELSE 0 END) AS `t10$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 11 THEN `q`.`$jenis` ELSE 0 END) AS `t11$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 12 THEN `q`.`$jenis` ELSE 0 END) AS `t12$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 13 THEN `q`.`$jenis` ELSE 0 END) AS `t13$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 14 THEN `q`.`$jenis` ELSE 0 END) AS `t14$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 15 THEN `q`.`$jenis` ELSE 0 END) AS `t15$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 16 THEN `q`.`$jenis` ELSE 0 END) AS `t16$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 17 THEN `q`.`$jenis` ELSE 0 END) AS `t17$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 18 THEN `q`.`$jenis` ELSE 0 END) AS `t18$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 19 THEN `q`.`$jenis` ELSE 0 END) AS `t19$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 20 THEN `q`.`$jenis` ELSE 0 END) AS `t20$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 21 THEN `q`.`$jenis` ELSE 0 END) AS `t21$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 22 THEN `q`.`$jenis` ELSE 0 END) AS `t22$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 23 THEN `q`.`$jenis` ELSE 0 END) AS `t23$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 24 THEN `q`.`$jenis` ELSE 0 END) AS `t24$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 25 THEN `q`.`$jenis` ELSE 0 END) AS `t25$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 26 THEN `q`.`$jenis` ELSE 0 END) AS `t26$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 27 THEN `q`.`$jenis` ELSE 0 END) AS `t27$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 28 THEN `q`.`$jenis` ELSE 0 END) AS `t28$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 29 THEN `q`.`$jenis` ELSE 0 END) AS `t29$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 30 THEN `q`.`$jenis` ELSE 0 END) AS `t30$jenis`,
        SUM(CASE WHEN DAY(q.tanggal) = 31 THEN `q`.`$jenis` ELSE 0 END) AS `t31$jenis`,
        SUM(`q`.`$jenis`) AS 'total'
    FROM
        v_production_op q
    LEFT JOIN t_bom b ON b.id_bom = q.id_bom
    LEFT JOIN t_product pr ON pr.id_product = b.id_product
    WHERE
        YEAR(q.tanggal) = '$tahun' AND MONTH(q.tanggal) = '$bulan'
    GROUP BY
        q.kp_pr, q.nama_product, pr.customer
    ");
    return $query;
  }

  public function onlineReportDisposeYear($tahun, $jenis)
  {
    $query = $this->db->query("SELECT * , SUM(p.`$jenis`) AS total,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '01') THEN `p`.`$jenis` ELSE NULL END)) AS `t1$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '02') THEN `p`.`$jenis` ELSE NULL END)) AS `t2$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '03') THEN `p`.`$jenis` ELSE NULL END)) AS `t3$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '04') THEN `p`.`$jenis` ELSE NULL END)) AS `t4$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '05') THEN `p`.`$jenis` ELSE NULL END)) AS `t5$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '06') THEN `p`.`$jenis` ELSE NULL END)) AS `t6$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '07') THEN `p`.`$jenis` ELSE NULL END)) AS `t7$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '08') THEN `p`.`$jenis` ELSE NULL END)) AS `t8$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '09') THEN `p`.`$jenis` ELSE NULL END)) AS `t9$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '10') THEN `p`.`$jenis` ELSE NULL END)) AS `t10$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '11') THEN `p`.`$jenis` ELSE NULL END)) AS `t11$jenis`,
                    SUM((CASE WHEN (SUBSTR(`p`.`tanggal`,6,2) = '12') THEN `p`.`$jenis` ELSE NULL END)) AS `t12$jenis`
                              FROM `v_production_op` AS p
                              WHERE SUBSTR(p.tanggal,1,4) = '$tahun'
                              GROUP BY p.mesin , SUBSTR(p.tanggal,1,4)
                              order by p.`mesin` ");

    return $query;
  }

  public function onlineGrafikDisposeYear($tahun, $jenis)
  {
    $query = $this->db->query("SELECT * ,  MAX(total) AS totalitas FROM (SELECT p.* ,  SUM(p.$jenis) AS total
        FROM `v_production_op` AS p
        WHERE SUBSTR(p.tanggal,1,4) = '$tahun'
        GROUP BY p.mesin , SUBSTR(p.tanggal,1,4)) AS p
        GROUP BY p.mesin , SUBSTR(p.tanggal,1,4)
        ORDER BY MAX(total) DESC
        LIMIT 10");
    return $query;
  }

  function cek_login()
  {
    if (empty($_SESSION['user_name'])) {
      redirect('login_control/index');
    }
  }

  //Productivity
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

  public function tampil_productionGrafik($tahun)
  {

    $query = $this->db->query("SELECT w.tahun,
      ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`nett` END)) * 100) AS persen_nett1,
            ((AVG(CASE WHEN w.`bulan` = '01' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '01' THEN w.`gross` END)) * 100) AS persen_gross1,
      ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`nett` END)) * 100) AS persen_nett2,
            ((AVG(CASE WHEN w.`bulan` = '02' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '02' THEN w.`gross` END)) * 100) AS persen_gross2,
            ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`nett` END)) * 100) AS persen_nett3,
            ((AVG(CASE WHEN w.`bulan` = '03' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '03' THEN w.`gross` END)) * 100) AS persen_gross3,
            ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`nett` END)) * 100) AS persen_nett4,
            ((AVG(CASE WHEN w.`bulan` = '04' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '04' THEN w.`gross` END)) * 100) AS persen_gross4,
            ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`nett` END)) * 100) AS persen_nett5,
            ((AVG(CASE WHEN w.`bulan` = '05' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '05' THEN w.`gross` END)) * 100) AS persen_gross5,
            ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`nett` END)) * 100) AS persen_nett6,
            ((AVG(CASE WHEN w.`bulan` = '06' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '06' THEN w.`gross` END)) * 100) AS persen_gross6,
            ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`nett` END)) * 100) AS persen_nett7,
            ((AVG(CASE WHEN w.`bulan` = '07' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '07' THEN w.`gross` END)) * 100) AS persen_gross7,
            ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`nett` END)) * 100) AS persen_nett8,
            ((AVG(CASE WHEN w.`bulan` = '08' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '08' THEN w.`gross` END)) * 100) AS persen_gross8,
            ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`nett` END)) * 100) AS persen_nett9,
            ((AVG(CASE WHEN w.`bulan` = '09' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '09' THEN w.`gross` END)) * 100) AS persen_gross9,
            ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`nett` END)) * 100) AS persen_nett10,
            ((AVG(CASE WHEN w.`bulan` = '10' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '10' THEN w.`gross` END)) * 100) AS persen_gross10,
            ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`nett` END)) * 100) AS persen_nett11,
            ((AVG(CASE WHEN w.`bulan` = '11' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '11' THEN w.`gross` END)) * 100) AS persen_gross11,
            ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`nett` END)) * 100) AS persen_nett12,
            ((AVG(CASE WHEN w.`bulan` = '12' THEN w.`cyt_mc` END)/ AVG(CASE WHEN w.`bulan` = '12' THEN w.`gross` END)) * 100) AS persen_gross12,
            q.*
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            LEFT JOIN target_produksi AS q ON q.`tahun` = w.`tahun`
            WHERE w.tahun = '$tahun'");
    return $query;
  }

  public function tampil_worst_nett($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.cyt_mc, w.cyt_quo, w.`kode_product`, w.gross, w.nett,
            (w.`cyt_mc` / w.`nett`) * 100 AS persen_nett,
            (w.`cyt_mc` / w.`gross`) * 100 AS persen_gross
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            ORDER BY (w.`cyt_mc` / w.`nett`) * 100 ASC
            LIMIT 10");
    return $query;
  }

  public function tampil_worst_gross($bulan, $tahun)
  {
    $query = $this->db->query("SELECT w.`nama_product`, w.`kode_product`,w.gross, w.nett, w.cyt_mc, w.cyt_quo,
            (w.`cyt_mc` / w.`gross`) * 100 AS persen_gross,
            (w.`cyt_mc` / w.`nett`) * 100 AS persen_nett
            FROM `v_productivity_detail_bypart_bymesin_bymonth` AS w
            WHERE w.bulan = '$bulan' AND w.tahun = '$tahun'
            ORDER BY (w.`cyt_mc` / w.`gross`) * 100 ASC
            LIMIT 10");
    return $query;
  }


  public function tampil_detail_productivity($tahun)
  {
    $query = $this->db->query("SELECT 
            p.kode_product, p.nama_product, p.`cavity`, p.cyt_mc, p.cyt_quo, p.`mesin`,
            (CASE WHEN p.bulan = 01 THEN p.nett ELSE 0 END) AS nett_1,
            (CASE WHEN p.bulan = 01 THEN p.gross ELSE 0 END) AS gross_1,
            (CASE WHEN p.bulan = 01 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett1,
            (CASE WHEN p.bulan = 01 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross1,
            (CASE WHEN p.bulan = 02 THEN p.nett ELSE 0 END) AS nett_2,
            (CASE WHEN p.bulan = 02 THEN p.gross ELSE 0 END) AS gross_2,
            (CASE WHEN p.bulan = 02 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett2,
            (CASE WHEN p.bulan = 02 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross2,
            (CASE WHEN p.bulan = 03 THEN p.nett ELSE 0 END) AS nett_3,
            (CASE WHEN p.bulan = 03 THEN p.gross ELSE 0 END) AS gross_3,
            (CASE WHEN p.bulan = 03 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett3,
            (CASE WHEN p.bulan = 03 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross3,
            (CASE WHEN p.bulan = 04 THEN p.nett ELSE 0 END) AS nett_4,
            (CASE WHEN p.bulan = 04 THEN p.gross ELSE 0 END) AS gross_4,
            (CASE WHEN p.bulan = 04 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett4,
            (CASE WHEN p.bulan = 04 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross4,
            (CASE WHEN p.bulan = 05 THEN p.nett ELSE 0 END) AS nett_5,
            (CASE WHEN p.bulan = 05 THEN p.gross ELSE 0 END) AS gross_5,
            (CASE WHEN p.bulan = 05 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett5,
            (CASE WHEN p.bulan = 05 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross5,
            (CASE WHEN p.bulan = 06 THEN p.nett ELSE 0 END) AS nett_6,
            (CASE WHEN p.bulan = 06 THEN p.gross ELSE 0 END) AS gross_6,
            (CASE WHEN p.bulan = 06 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett6,
            (CASE WHEN p.bulan = 06 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross6,
            (CASE WHEN p.bulan = 07 THEN p.nett ELSE 0 END) AS nett_7,
            (CASE WHEN p.bulan = 07 THEN p.gross ELSE 0 END) AS gross_7,
            (CASE WHEN p.bulan = 07 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett7,
            (CASE WHEN p.bulan = 07 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross7,
            (CASE WHEN p.bulan = 08 THEN p.nett ELSE 0 END) AS nett_8,
            (CASE WHEN p.bulan = 08 THEN p.gross ELSE 0 END) AS gross_8,
            (CASE WHEN p.bulan = 08 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett8,
            (CASE WHEN p.bulan = 08 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross8,
            (CASE WHEN p.bulan = 09 THEN p.nett ELSE 0 END) AS nett_9,
            (CASE WHEN p.bulan = 09 THEN p.gross ELSE 0 END) AS gross_9,
            (CASE WHEN p.bulan = 09 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett9,
            (CASE WHEN p.bulan = 09 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross9,
            (CASE WHEN p.bulan = 10 THEN p.nett ELSE 0 END) AS nett_10,
            (CASE WHEN p.bulan = 10 THEN p.gross ELSE 0 END) AS gross_10,
            (CASE WHEN p.bulan = 10 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett10,
            (CASE WHEN p.bulan = 10 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross10,
            (CASE WHEN p.bulan = 11 THEN p.nett ELSE 0 END) AS nett_11,
            (CASE WHEN p.bulan = 11 THEN p.gross ELSE 0 END) AS gross_11,
            (CASE WHEN p.bulan = 11 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett11,
            (CASE WHEN p.bulan = 11 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross11,
            (CASE WHEN p.bulan = 12 THEN p.nett ELSE 0 END) AS nett_12,
            (CASE WHEN p.bulan = 12 THEN p.gross ELSE 0 END) AS gross_12,
            (CASE WHEN p.bulan = 12 THEN ((p.`cyt_mc` / p.`nett`) * 100) ELSE 0 END) AS persen_nett12,
            (CASE WHEN p.bulan = 12 THEN ((p.`cyt_mc` / p.`gross`) * 100) ELSE 0 END) AS persen_gross12
            FROM 
            `v_productivity_detail_bypart_bymesin_bymonth` AS p 
            WHERE p.tahun = '$tahun' AND p.`kode_product` IS NOT NULL
            ORDER BY p.`nama_product` ASC");
    return $query;
  }

  public function tampil_detail_productivity_bypart_bymonth($bulan, $tahun)
  {
    $query = $this->db->query("SELECT CONCAT(YEAR(w.`tanggal`),'-',MONTH(w.`tanggal`)) AS 'Mo',w.`kode_product`,w.`nama_product`,
           w.`Mac`,
           w.target_mc,
           w.qty_ok,
           w.qty_ng,
           w.max_CTStd,
           w.min_CTSet,
           w.max_CTSet,
           w.wh,
           w.qty_nwt,
           w.qty_ot,
             w.`max_CTStd2`, w.`nett_prod`, w.`gross_prod`, w.`cyt_mc`,
                   (w.`cyt_mc` / w.`nett_prod`) * 100 AS persen_nett, (w.`cyt_mc` / w.`gross_prod`) * 100 AS persen_gross
                   FROM `productivity_by_month` AS w
                   WHERE MONTH(w.`tanggal`) = '$bulan' AND YEAR(w.`tanggal`) = '$tahun'
                   GROUP BY w.`kode_product`");
    return $query;
  }

  public function tampil_detail_worst($kode_product, $bulan, $tahun)
  {
    $query = $this->db->query("SELECT * FROM v_production_op AS w
            WHERE MONTH(w.`tanggal`) = '$bulan' AND YEAR(w.`tanggal`) = '$tahun' AND w.`kode_product` = '$kode_product'");
    return $query;
  }

  public function view_detail_prod_bypart_bymonth($kode_product, $bulan, $tahun)
  {
    $query = $this->db->query("SELECT p.*, q.cyt_quo FROM v_production_op AS p 
            LEFT JOIN t_product AS q ON q.kode_product = p.kode_product
            WHERE p.kode_product = '$kode_product ' AND MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'
            ORDER BY p.tanggal ASC");
    return $query;
  }

  function add()
  {
    $id_production  = $this->input->post("id_production");
    $input  = date('Y-m-d h:i:s');
    $ql = $this->db->select('id_production')->from('t_production_op')
      ->where('id_production', $id_production)
      ->get();
    if ($ql->num_rows() > 0) {
      $this->session->set_flashdata('gagal', 'Gagal menambahkan, data yang anda input sudah ada!');
      redirect('c_dpr/add_dpr');
    } else {
      $this->load->library('dpr_batch_builder');

      $user_rows = Dpr_batch_builder::build_production_op_rows($_POST['user'] ?? array(), array(
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

  public function get_last_id() {
    return $this->db->insert_id();
  }

  public function verif_kasi($dari, $sampai, $shift)
  {
    if ($shift == 'All') {
      $shiftNya = '';
    } else {
      $shiftNya = 'AND q.shift = ' . $shift . '';
    }
    $query = $this->db->query("SELECT * FROM v_production_op AS q
        WHERE (q.`tanggal` BETWEEN '$dari' AND '$sampai') AND q.`gross_prod` > q.`ct_mc`
        $shiftNya
        ORDER BY q.`cek_kanit` DESC, q.kanit, q.tanggal, q.mesin ASC");
    return $query;
  }

  public function update($data, $id)
  {
    return $this->db->update('t_production_op', $data, array('id_production_op' => $id));
  }

  public function update_verif_kasi($data, $tanggal, $shift)
  {
    return $this->db->update('t_production_op', $data, array('tanggal' => $tanggal, 'shift' => $shift));
  }

  public function data_verif_kanit($dari, $sampai, $shift)
  {
    if ($shift == 'All') {
      $shiftNya = '';
    } else {
      $shiftNya = 'AND q.shift = ' . $shift . '';
    }
    $query = $this->db->query("SELECT qq.tanggal,qq.kanit, qq.shift, SUM(qq.total_sudah_verif) AS total_verif, SUM(qq.total_belum_verif) AS total_belum_verif,SUM(qq.total_sudah_verif + qq.total_belum_verif) AS total_keseluruhan,
SUM(qq.target_dpr) AS target_dpr
            FROM (
              
              SELECT q.tanggal,q.shift, q.kanit, COUNT(q.`id_production_op`) AS total_sudah_verif, 'total_belum_verif', 'target_dpr' FROM t_production_op AS q
              WHERE (q.`tanggal` BETWEEN '$dari' AND '$sampai') AND q.`cek_kanit` = 1 $shiftNya
              GROUP BY q.`kanit`
              UNION
              SELECT q.tanggal,q.shift, q.kanit, 'total_sudah_verif', COUNT(q.`id_production_op`) AS total_belum_verif, 'target_dpr' FROM t_production_op AS q
              WHERE (q.`tanggal` BETWEEN '$dari' AND '$sampai') AND q.`cek_kanit` IS NULL $shiftNya
              GROUP BY q.`kanit`
              UNION
              SELECT q.`tanggal`, q.`shift`,q.`group`, 'total_sudah_verif', 'total_belum_verif', COUNT(q.`id_machine_use`) AS target_dpr FROM `machine_use` AS q
        WHERE q.`tanggal` BETWEEN '$dari' AND '$sampai' AND q.`running` = 1 $shiftNya
        GROUP BY q.`group`) AS qq
              GROUP BY qq.kanit
              ORDER BY SUM(qq.total_sudah_verif) DESC, qq.shift ASC");
    return $query;
  }

  public function verif_kasi_filter($dari, $sampai, $shift)
  {
    if ($shift == 'All') {
      $shiftNya = '';
    } else {
      $shiftNya = 'AND q.shift = ' . $shift . '';
    }
    $query = $this->db->query("SELECT * FROM v_production_op AS q
        WHERE (q.`tanggal` BETWEEN '$dari' AND '$sampai') AND q.`gross_prod` > q.`ct_mc`
        $shiftNya
        ORDER BY q.`cek_kanit` DESC, q.kanit, q.tanggal, q.mesin ASC");
    return $query;
  }


  public function data_verifikasi_kasi($dari, $sampai)
  {
    $query = $this->db->query("SELECT q.`tanggal`,q.`shift`, COUNT(q.`id_production`) AS total_dpr, COUNT(q.`cek_kasi`) AS total_cek_kasi, q.`pic_kasi` FROM v_production_op AS q
              WHERE (q.`tanggal` BETWEEN '$dari' AND '$sampai')
              GROUP BY q.`shift` ");
    return $query;
  }

  // Get cutting tools for autocomplete
  public function get_cutting_tools_autocomplete($term) {
    $this->db->select('id, code');
    $this->db->from('cutting_tools');
    if ($term !== null && $term !== '') {
      $this->db->like('code', $term);
    }
    $query = $this->db->get();
    return $query->result_array();
  }

  // Get cutting tools used for a production record (for view page)
  public function get_cutting_tools_by_production($id_production) {
    $this->db->select('ct.id, ct.code, ct.code_group');
    $this->db->from('t_production_op_cutting_tools_usage as usage');
    $this->db->join('cutting_tools as ct', 'usage.cutting_tools_id = ct.id');
    $this->db->where('usage.id_production', $id_production);
    return $this->db->get()->result_array();
  }

  // Delete all cutting tool usages for a production record
  public function delete_cutting_tools_by_production($id_production) {
    $this->db->where('id_production', $id_production);
    $this->db->delete('t_production_op_cutting_tools_usage');
  }

  // Sync cutting tool usages for a production record
  public function sync_cutting_tools_by_production($id_production, $cutting_tools) {
    $this->load->library('dpr_batch_builder');
    // Remove all existing usages first
    $this->db->where('id_production', $id_production);
    $this->db->delete('t_production_op_cutting_tools_usage');
    // Insert new usages if any
    $tool_rows = Dpr_batch_builder::build_cutting_tools_rows(is_array($cutting_tools) ? $cutting_tools : array(), $id_production);
    if (! empty($tool_rows)) {
      $this->db->insert_batch('t_production_op_cutting_tools_usage', $tool_rows);
    }
  }
  
  /**
   * Get NG (Not Good) product reports for a specific date
   * 
   * @param string $date Date in Y-m-d format
   * @return object Query result
   */
  public function get_ng_reports($date) {
    $formatted_date = date('Y-m-d', strtotime($date));
    $year = date('Y', strtotime($date));
    $month = (int)date('m', strtotime($date));
    $day = (int)date('d', strtotime($date));
    $alt_date = "$year-$month-$day";

    $query = $this->db->query("SELECT 
        b.kp_pr AS kode_product,
        b.np_pr AS nama_product,
        tp.sp AS unit,
        dl.nama AS ng_type,
        p.mesin,
        SUM(dl.qty) AS qty_ng
    FROM t_production_op p
    JOIN select_bom b ON b.id_bom = p.id_bom
    LEFT JOIN t_product tp ON tp.kode_product = b.kp_pr
    LEFT JOIN t_production_op_dl dl ON dl.id_production = p.id_production AND dl.type = 'NG'
    WHERE (p.tanggal = '$formatted_date' OR p.tanggal = '$alt_date') 
      AND dl.qty > 0
    GROUP BY p.id_production, b.kp_pr, b.np_pr, tp.sp, dl.nama, p.mesin
    ORDER BY p.mesin, b.kp_pr, dl.nama");

    return $query;
  }
  
  /**
   * Get Monthly NG Reports aggregated by NG type
   * 
   * @param string $year Year (e.g., '2024')
   * @param string $month Month (e.g., '01')
   * @return object Query result with aggregated NG data by type
   */
  public function get_ng_monthly_reports($year, $month) {
    $query = $this->db->query("SELECT
        sub.kode_product,
        sub.nama_product,
        sub.unit,
        sub.mesin,
        GROUP_CONCAT(
            CONCAT(sub.ng_type, ': ', sub.qty_type_total)
            ORDER BY sub.ng_type
            SEPARATOR ', '
        ) AS keterangan,
        SUM(sub.qty_type_total) AS qty_ng
    FROM (
        SELECT
            b.kp_pr AS kode_product,
            b.np_pr AS nama_product,
            tp.sp AS unit,
            p.mesin,
            dl.nama AS ng_type,
            SUM(dl.qty) AS qty_type_total
        FROM t_production_op p
        JOIN select_bom b ON b.id_bom = p.id_bom
        LEFT JOIN t_product tp ON tp.kode_product = b.kp_pr
        LEFT JOIN t_production_op_dl dl ON dl.id_production = p.id_production AND dl.type = 'NG'
        WHERE YEAR(p.tanggal) = '$year'
          AND MONTH(p.tanggal) = '$month'
          AND dl.qty > 0
        GROUP BY b.kp_pr, b.np_pr, tp.sp, dl.nama, p.mesin
        ORDER BY CAST(p.mesin AS UNSIGNED) ASC, b.kp_pr ASC, dl.nama ASC
    ) AS sub
    GROUP BY sub.kode_product, sub.nama_product, sub.unit, sub.mesin
    ORDER BY CAST(sub.mesin AS UNSIGNED) ASC, sub.kode_product ASC");

    return $query;
  }
}
