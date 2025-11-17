<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_inventory extends CI_Model
{
        public function __construct()
        {
                $this->load->database();
                $this->load->helper(array('url','html','form'));
        }


        function tampil_product()
        {
                $query = $this->db->query("SELECT * from t_product as p order by p.kode_product ASC");
         return $query;
        }

        function getProduk($id_product)
        {
                $query = $this->db->query("SELECT * FROM t_product AS p 
                        WHERE p.id_product = $id_product");
         return $query;
        }      

        function tampil_production($id)
        {
                $query = $this->db->query("SELECT * from v_production_op as p 
                        WHERE p.id_product = $id AND MONTH(p.tanggal) = MONTH(CURDATE()) AND YEAR(p.tanggal) = YEAR(CURDATE())
                        order by p.tanggal ASC");
         return $query;
        }

        function tampil_production_byfilter($id,$tahun)
        {
                $query = $this->db->query("SELECT * FROM v_production_op AS p 
                        WHERE p.id_product = $id AND p.`tanggal` LIKE '%$tahun%'
                        ORDER BY p.tanggal ASC");
         return $query;
        }

        function cek_login()
            {
                if(empty($_SESSION['user_name']))
                {
                    redirect('login_control/index');
                }
            }

        function tampil_analisis($id_product)
        {
                $query = $this->db->query("
                SELECT q.`id_production`, q.`tanggal`, q.qty_ok,
                       SUM(IFNULL(w.qty,0)) AS qty,
                       SUM(CASE WHEN w.`nama` = 'PIN SERET' THEN w.`qty` ELSE 0 END) AS pin_seret,
                       SUM(CASE WHEN w.`nama` = 'BLACKDOT' THEN w.`qty` ELSE 0 END) AS blackdot,
                       SUM(CASE WHEN w.`nama` = 'HIKE' THEN w.`qty` ELSE 0 END) AS hike,
                       SUM(CASE WHEN w.`nama` = 'SINK MARK' THEN w.`qty` ELSE 0 END) AS sink_mark,
                       SUM(CASE WHEN w.`nama` = 'OVERSIZE' THEN w.`qty` ELSE 0 END) AS oversize,
                       SUM(CASE WHEN w.`nama` = 'UNDERSIZE' THEN w.`qty` ELSE 0 END) AS undersize,
                       SUM(CASE WHEN w.`nama` = 'SETTINGAN' THEN w.`qty` ELSE 0 END) AS settingan,
                       SUM(CASE WHEN w.`nama` = 'WAVING' THEN w.`qty` ELSE 0 END) AS waving,
                       SUM(CASE WHEN w.`nama` = 'DIRTY' THEN w.`qty` ELSE 0 END) AS dirty,
                       SUM(CASE WHEN w.`nama` = 'BROKEN' THEN w.`qty` ELSE 0 END) AS broken,
                       SUM(CASE WHEN w.`nama` = 'OIL' THEN w.`qty` ELSE 0 END) AS oil,
                       SUM(CASE WHEN w.`nama` = 'UNDERCUT' THEN w.`qty` ELSE 0 END) AS undercut,
                       SUM(CASE WHEN w.`nama` = 'FOREIGN MATERIAL' THEN w.`qty` ELSE 0 END) AS fm,
                       SUM(CASE WHEN w.`nama` = 'WHITE MARK' THEN w.`qty` ELSE 0 END) AS white_mark,
                       SUM(CASE WHEN w.`nama` = 'WHITE DOT' THEN w.`qty` ELSE 0 END) AS white_dot,
                       SUM(CASE WHEN w.`nama` = 'PIN PLONG' THEN w.`qty` ELSE 0 END) AS pin_plong,
                       SUM(CASE WHEN w.`nama` = 'BENDING' THEN w.`qty` ELSE 0 END) AS bending,
                       SUM(CASE WHEN w.`nama` = 'SHORT SHOOT' THEN w.`qty` ELSE 0 END) AS short_shoot,
                       SUM(CASE WHEN w.`nama` = 'FLASH' THEN w.`qty` ELSE 0 END) AS flash,
                       SUM(CASE WHEN w.`nama` = 'GATE BOLONG' THEN w.`qty` ELSE 0 END) AS gate_bolong,
                       SUM(CASE WHEN w.`nama` = 'CRACK' THEN w.`qty` ELSE 0 END) AS crack,
                       SUM(CASE WHEN w.`nama` = 'BERAWAN' THEN w.`qty` ELSE 0 END) AS berawan,
                       SUM(CASE WHEN w.`nama` = 'GAS MARK' THEN w.`qty` ELSE 0 END) AS gas_mark,
                       SUM(CASE WHEN w.`nama` = 'EJECTOR MARK' THEN w.`qty` ELSE 0 END) AS ejector_mark,
                       SUM(CASE WHEN w.`nama` = 'HANGUS' THEN w.`qty` ELSE 0 END) AS hangus,
                       SUM(CASE WHEN w.`nama` = 'GAS BURN' THEN w.`qty` ELSE 0 END) AS gas_burn,
                       SUM(CASE WHEN w.`nama` = 'SCRATCH' THEN w.`qty` ELSE 0 END) AS scratch,
                       SUM(CASE WHEN w.`nama` = 'DISCOLOUR' THEN w.`qty` ELSE 0 END) AS discolour,
                       SUM(CASE WHEN w.`nama` = 'SILVER' THEN w.`qty` ELSE 0 END) AS silver,
                       SUM(CASE WHEN w.`nama` = 'DENT' THEN w.`qty` ELSE 0 END) AS dent,
                       SUM(CASE WHEN w.`nama` = 'FLOW GATE' THEN w.`qty` ELSE 0 END) AS flow_gate,
                       SUM(CASE WHEN w.`nama` = 'GATE LONG' THEN w.`qty` ELSE 0 END) AS gate_long,
                       SUM(CASE WHEN w.`nama` = 'WELD LINE' THEN w.`qty` ELSE 0 END) AS weld_line,
                       SUM(CASE WHEN w.`nama` = 'VOID' THEN w.`qty` ELSE 0 END) AS void,
                       SUM(CASE WHEN w.`nama` = 'FLOW MARK' THEN w.`qty` ELSE 0 END) AS flow_mark
                FROM v_production_op AS q
                LEFT JOIN t_production_op_dl AS w ON w.`id_production` = q.`id_production` AND w.`type` = 'NG'
                WHERE q.`id_product` = $id_product AND MONTH(q.tanggal) = MONTH(CURDATE()) AND YEAR(q.tanggal) = YEAR(CURDATE())
                GROUP BY q.`id_production`, q.`tanggal`, q.qty_ok
                ORDER BY q.tanggal, q.id_production
                ");
                return $query;
        }

       /**
 * DPR Inventory Model
 *
 * This method feeds $data_tabel (first grid in the view)
 * -------------------------------------------------------
 * @param  int    $id_product   product id (e.g. 818)
 * @param  string $periode      'YYYY-MM'  (e.g. '2025-01')
 * @return CI_DB_result         ->result_array() / ->result()
 */
public function tampil_analisis_byfilter($id_product, $periode)
{
    $sql = "
    SELECT  p.id_production,          -- handy key, not displayed
            p.tanggal                      AS tanggal,
            p.qty_ok                       AS qty_ok,
            IFNULL(d.total_ng,0)           AS qty,

            /* --- individual defects (aliases match the view) ------- */
            IFNULL(d.pin_seret      ,0) AS pin_seret,
            IFNULL(d.blackdot       ,0) AS blackdot,
            IFNULL(d.hike           ,0) AS hike,
            IFNULL(d.sink_mark      ,0) AS sink_mark,
            IFNULL(d.oversize       ,0) AS oversize,
            IFNULL(d.undersize      ,0) AS undersize,
            IFNULL(d.settingan      ,0) AS settingan,
            IFNULL(d.waving         ,0) AS waving,
            IFNULL(d.dirty          ,0) AS dirty,
            IFNULL(d.broken         ,0) AS broken,
            IFNULL(d.oil            ,0) AS oil,
            IFNULL(d.undercut       ,0) AS undercut,
            IFNULL(d.fm             ,0) AS fm,
            IFNULL(d.white_mark     ,0) AS white_mark,
            IFNULL(d.white_dot      ,0) AS white_dot,
            IFNULL(d.pin_plong      ,0) AS pin_plong,
            IFNULL(d.bending        ,0) AS bending,
            IFNULL(d.short_shoot    ,0) AS short_shoot,
            IFNULL(d.flash          ,0) AS flash,
            IFNULL(d.gate_bolong    ,0) AS gate_bolong,
            IFNULL(d.crack          ,0) AS crack,
            IFNULL(d.berawan        ,0) AS berawan,
            IFNULL(d.gas_mark       ,0) AS gas_mark,
            IFNULL(d.ejector_mark   ,0) AS ejector_mark,
            IFNULL(d.hangus         ,0) AS hangus,
            IFNULL(d.gas_burn       ,0) AS gas_burn,
            IFNULL(d.scratch        ,0) AS scratch,
            IFNULL(d.discolour      ,0) AS discolour,
            IFNULL(d.silver         ,0) AS silver,
            IFNULL(d.dent           ,0) AS dent,
            IFNULL(d.flow_gate      ,0) AS flow_gate,
            IFNULL(d.gate_long      ,0) AS gate_long,
            IFNULL(d.weld_line      ,0) AS weld_line,
            IFNULL(d.void           ,0) AS void,
            IFNULL(d.flow_mark      ,0) AS flow_mark

    FROM        v_production_op p
    LEFT JOIN (
        /* ---- 1-row-per-production pivot of NG detail ------------- */
        SELECT  id_production,
                SUM(qty) AS total_ng,

                /* every defect used in the view */
                SUM(CASE WHEN nama='PIN SERET'         THEN qty ELSE 0 END) AS pin_seret,
                SUM(CASE WHEN nama='BLACKDOT'          THEN qty ELSE 0 END) AS blackdot,
                SUM(CASE WHEN nama='HIKE'              THEN qty ELSE 0 END) AS hike,
                SUM(CASE WHEN nama='SINK MARK'         THEN qty ELSE 0 END) AS sink_mark,
                SUM(CASE WHEN nama='OVERSIZE'          THEN qty ELSE 0 END) AS oversize,
                SUM(CASE WHEN nama='UNDERSIZE'         THEN qty ELSE 0 END) AS undersize,
                SUM(CASE WHEN nama='SETTINGAN'         THEN qty ELSE 0 END) AS settingan,
                SUM(CASE WHEN nama='WAVING'            THEN qty ELSE 0 END) AS waving,
                SUM(CASE WHEN nama='DIRTY'             THEN qty ELSE 0 END) AS dirty,
                SUM(CASE WHEN nama='BROKEN'            THEN qty ELSE 0 END) AS broken,
                SUM(CASE WHEN nama='OIL'               THEN qty ELSE 0 END) AS oil,
                SUM(CASE WHEN nama='UNDERCUT'          THEN qty ELSE 0 END) AS undercut,
                SUM(CASE WHEN nama='FOREIGN MATERIAL'  THEN qty ELSE 0 END) AS fm,
                SUM(CASE WHEN nama='WHITE MARK'        THEN qty ELSE 0 END) AS white_mark,
                SUM(CASE WHEN nama='WHITE DOT'         THEN qty ELSE 0 END) AS white_dot,
                SUM(CASE WHEN nama='PIN PLONG'         THEN qty ELSE 0 END) AS pin_plong,
                SUM(CASE WHEN nama='BENDING'           THEN qty ELSE 0 END) AS bending,
                SUM(CASE WHEN nama='SHORT SHOOT'       THEN qty ELSE 0 END) AS short_shoot,
                SUM(CASE WHEN nama='FLASH'             THEN qty ELSE 0 END) AS flash,
                SUM(CASE WHEN nama='GATE BOLONG'       THEN qty ELSE 0 END) AS gate_bolong,
                SUM(CASE WHEN nama='CRACK'             THEN qty ELSE 0 END) AS crack,
                SUM(CASE WHEN nama='BERAWAN'           THEN qty ELSE 0 END) AS berawan,
                SUM(CASE WHEN nama='GAS MARK'          THEN qty ELSE 0 END) AS gas_mark,
                SUM(CASE WHEN nama='EJECTOR MARK'      THEN qty ELSE 0 END) AS ejector_mark,
                SUM(CASE WHEN nama='HANGUS'            THEN qty ELSE 0 END) AS hangus,
                SUM(CASE WHEN nama='GAS BURN'          THEN qty ELSE 0 END) AS gas_burn,
                SUM(CASE WHEN nama='SCRATCH'           THEN qty ELSE 0 END) AS scratch,
                SUM(CASE WHEN nama='DISCOLOUR'         THEN qty ELSE 0 END) AS discolour,
                SUM(CASE WHEN nama='SILVER'            THEN qty ELSE 0 END) AS silver,
                SUM(CASE WHEN nama='DENT'              THEN qty ELSE 0 END) AS dent,
                SUM(CASE WHEN nama='FLOW GATE'         THEN qty ELSE 0 END) AS flow_gate,
                SUM(CASE WHEN nama='GATE LONG'         THEN qty ELSE 0 END) AS gate_long,
                SUM(CASE WHEN nama='WELD LINE'         THEN qty ELSE 0 END) AS weld_line,
                SUM(CASE WHEN nama='VOID'              THEN qty ELSE 0 END) AS void,
                SUM(CASE WHEN nama='FLOW MARK'         THEN qty ELSE 0 END) AS flow_mark
        FROM    t_production_op_dl
        WHERE   type = 'NG'
        GROUP   BY id_production
    ) d  ON d.id_production = p.id_production

    WHERE   p.id_product = ?
      AND   p.tanggal LIKE CONCAT(?, '%')       /* yyyy-mm filter */
    ORDER BY p.tanggal, p.id_production
    ";

    /* use query bindings to avoid SQL-injection */
    return $this->db->query($sql, array($id_product, $periode));
}


        function tampil_total_analisis($id_product){
                $query = $this->db->query("SELECT SUM(q.qty_ok) AS total_qty, SUM(w.qty) AS total_ng,
                SUM(CASE WHEN w.`nama` = 'PIN SERET' THEN w.`qty` ELSE 0 END) AS pin_seret,
                SUM(CASE WHEN w.`nama` = 'BLACKDOT' THEN w.`qty` ELSE 0 END) AS blackdot,
                SUM(CASE WHEN w.`nama` = 'HIKE' THEN w.`qty` ELSE 0 END) AS hike,
                SUM(CASE WHEN w.`nama` = 'SINK MARK' THEN w.`qty` ELSE 0 END) AS sink_mark,
                SUM(CASE WHEN w.`nama` = 'OVERSIZE' THEN w.`qty` ELSE 0 END) AS oversize,
                SUM(CASE WHEN w.`nama` = 'UNDERSIZE' THEN w.`qty` ELSE 0 END) AS undersize,
                SUM(CASE WHEN w.`nama` = 'SETTINGAN' THEN w.`qty` ELSE 0 END) AS settingan,
                SUM(CASE WHEN w.`nama` = 'WAVING' THEN w.`qty` ELSE 0 END) AS waving,
                SUM(CASE WHEN w.`nama` = 'DIRTY' THEN w.`qty` ELSE 0 END) AS dirty,
                SUM(CASE WHEN w.`nama` = 'BROKEN' THEN w.`qty` ELSE 0 END) AS broken,
                SUM(CASE WHEN w.`nama` = 'OIL' THEN w.`qty` ELSE 0 END) AS oil,
                SUM(CASE WHEN w.`nama` = 'UNDERCUT' THEN w.`qty` ELSE 0 END) AS undercut,
                SUM(CASE WHEN w.`nama` = 'FOREIGN MATERIAL' THEN w.`qty` ELSE 0 END) AS fm,
                SUM(CASE WHEN w.`nama` = 'WHITE MARK' THEN w.`qty` ELSE 0 END) AS white_mark,
                SUM(CASE WHEN w.`nama` = 'WHITE DOT' THEN w.`qty` ELSE 0 END) AS white_dot,
                SUM(CASE WHEN w.`nama` = 'PIN PLONG' THEN w.`qty` ELSE 0 END) AS pin_plong,
                SUM(CASE WHEN w.`nama` = 'BENDING' THEN w.`qty` ELSE 0 END) AS bending,
                SUM(CASE WHEN w.`nama` = 'SHORT SHOOT' THEN w.`qty` ELSE 0 END) AS short_shoot,
                SUM(CASE WHEN w.`nama` = 'FLASH' THEN w.`qty` ELSE 0 END) AS flash,
                SUM(CASE WHEN w.`nama` = 'GATE BOLONG' THEN w.`qty` ELSE 0 END) AS gate_bolong,
                SUM(CASE WHEN w.`nama` = 'CRACK' THEN w.`qty` ELSE 0 END) AS crack,
                SUM(CASE WHEN w.`nama` = 'BERAWAN' THEN w.`qty` ELSE 0 END) AS berawan,
                SUM(CASE WHEN w.`nama` = 'GAS MARK' THEN w.`qty` ELSE 0 END) AS gas_mark,
                SUM(CASE WHEN w.`nama` = 'EJECTOR MARK' THEN w.`qty` ELSE 0 END) AS ejector_mark,
                SUM(CASE WHEN w.`nama` = 'HANGUS' THEN w.`qty` ELSE 0 END) AS hangus,
                SUM(CASE WHEN w.`nama` = 'GAS BURN' THEN w.`qty` ELSE 0 END) AS gas_burn,
                SUM(CASE WHEN w.`nama` = 'SCRATCH' THEN w.`qty` ELSE 0 END) AS scratch,
                SUM(CASE WHEN w.`nama` = 'DISCOLOUR' THEN w.`qty` ELSE 0 END) AS discolour,
                SUM(CASE WHEN w.`nama` = 'SILVER' THEN w.`qty` ELSE 0 END) AS silver,
                SUM(CASE WHEN w.`nama` = 'DENT' THEN w.`qty` ELSE 0 END) AS dent,
                SUM(CASE WHEN w.`nama` = 'FLOW GATE' THEN w.`qty` ELSE 0 END) AS flow_gate,
                SUM(CASE WHEN w.`nama` = 'GATE LONG' THEN w.`qty` ELSE 0 END) AS gate_long,
                SUM(CASE WHEN w.`nama` = 'WELD LINE' THEN w.`qty` ELSE 0 END) AS weld_line,
                SUM(CASE WHEN w.`nama` = 'VOID' THEN w.`qty` ELSE 0 END) AS void,
                SUM(CASE WHEN w.`nama` = 'FLOW MARK' THEN w.`qty` ELSE 0 END) AS flow_mark
                FROM v_production_op AS q
                LEFT JOIN t_production_op_dl AS w ON w.`id_production` = q.`id_production` AND w.`type` = 'NG'
                WHERE q.`id_product` = $id_product AND MONTH(q.tanggal) = MONTH(CURDATE()) AND YEAR(q.tanggal) = YEAR(CURDATE())");
                return $query;
        }

        function tampil_total_analisis_byfilter($id_product, $periode)
{
    $sql = "
    SELECT  /* ------------ overall quantity ------------- */
            SUM(p.qty_ok)                         AS total_qty,
            SUM(COALESCE(d.total_ng,0))           AS total_ng,

            /* ------------ one SUM() per defect ---------- */
            SUM(COALESCE(d.pin_seret   ,0))  AS pin_seret,
            SUM(COALESCE(d.blackdot    ,0))  AS blackdot,
            SUM(COALESCE(d.hike        ,0))  AS hike,
            SUM(COALESCE(d.sink_mark   ,0))  AS sink_mark,
            SUM(COALESCE(d.oversize    ,0))  AS oversize,
            SUM(COALESCE(d.undersize   ,0))  AS undersize,
            SUM(COALESCE(d.settingan   ,0))  AS settingan,
            SUM(COALESCE(d.waving      ,0))  AS waving,
            SUM(COALESCE(d.dirty       ,0))  AS dirty,
            SUM(COALESCE(d.broken      ,0))  AS broken,
            SUM(COALESCE(d.oil         ,0))  AS oil,
            SUM(COALESCE(d.undercut    ,0))  AS undercut,
            SUM(COALESCE(d.fm          ,0))  AS fm,
            SUM(COALESCE(d.white_mark  ,0))  AS white_mark,
            SUM(COALESCE(d.white_dot   ,0))  AS white_dot,
            SUM(COALESCE(d.pin_plong   ,0))  AS pin_plong,
            SUM(COALESCE(d.bending     ,0))  AS bending,
            SUM(COALESCE(d.short_shoot ,0))  AS short_shoot,
            SUM(COALESCE(d.flash       ,0))  AS flash,
            SUM(COALESCE(d.gate_bolong ,0))  AS gate_bolong,
            SUM(COALESCE(d.crack       ,0))  AS crack,
            SUM(COALESCE(d.berawan     ,0))  AS berawan,
            SUM(COALESCE(d.gas_mark    ,0))  AS gas_mark,
            SUM(COALESCE(d.ejector_mark,0))  AS ejector_mark,
            SUM(COALESCE(d.hangus      ,0))  AS hangus,
            SUM(COALESCE(d.gas_burn    ,0))  AS gas_burn,
            SUM(COALESCE(d.scratch     ,0))  AS scratch,
            SUM(COALESCE(d.discolour   ,0))  AS discolour,
            SUM(COALESCE(d.silver      ,0))  AS silver,
            SUM(COALESCE(d.dent        ,0))  AS dent,
            SUM(COALESCE(d.flow_gate   ,0))  AS flow_gate,
            SUM(COALESCE(d.gate_long   ,0))  AS gate_long,
            SUM(COALESCE(d.weld_line   ,0))  AS weld_line,
            SUM(COALESCE(d.void        ,0))  AS void,
            SUM(COALESCE(d.flow_mark   ,0))  AS flow_mark

    FROM    v_production_op p
    LEFT JOIN (
        /* ---- pivot NG-detail to one row per production ---- */
        SELECT  id_production,
                SUM(qty) AS total_ng,

                SUM(CASE WHEN nama='PIN SERET'         THEN qty ELSE 0 END) AS pin_seret,
                SUM(CASE WHEN nama='BLACKDOT'          THEN qty ELSE 0 END) AS blackdot,
                SUM(CASE WHEN nama='HIKE'              THEN qty ELSE 0 END) AS hike,
                SUM(CASE WHEN nama='SINK MARK'         THEN qty ELSE 0 END) AS sink_mark,
                SUM(CASE WHEN nama='OVERSIZE'          THEN qty ELSE 0 END) AS oversize,
                SUM(CASE WHEN nama='UNDERSIZE'         THEN qty ELSE 0 END) AS undersize,
                SUM(CASE WHEN nama='SETTINGAN'         THEN qty ELSE 0 END) AS settingan,
                SUM(CASE WHEN nama='WAVING'            THEN qty ELSE 0 END) AS waving,
                SUM(CASE WHEN nama='DIRTY'             THEN qty ELSE 0 END) AS dirty,
                SUM(CASE WHEN nama='BROKEN'            THEN qty ELSE 0 END) AS broken,
                SUM(CASE WHEN nama='OIL'               THEN qty ELSE 0 END) AS oil,
                SUM(CASE WHEN nama='UNDERCUT'          THEN qty ELSE 0 END) AS undercut,
                SUM(CASE WHEN nama='FOREIGN MATERIAL'  THEN qty ELSE 0 END) AS fm,
                SUM(CASE WHEN nama='WHITE MARK'        THEN qty ELSE 0 END) AS white_mark,
                SUM(CASE WHEN nama='WHITE DOT'         THEN qty ELSE 0 END) AS white_dot,
                SUM(CASE WHEN nama='PIN PLONG'         THEN qty ELSE 0 END) AS pin_plong,
                SUM(CASE WHEN nama='BENDING'           THEN qty ELSE 0 END) AS bending,
                SUM(CASE WHEN nama='SHORT SHOOT'       THEN qty ELSE 0 END) AS short_shoot,
                SUM(CASE WHEN nama='FLASH'             THEN qty ELSE 0 END) AS flash,
                SUM(CASE WHEN nama='GATE BOLONG'       THEN qty ELSE 0 END) AS gate_bolong,
                SUM(CASE WHEN nama='CRACK'             THEN qty ELSE 0 END) AS crack,
                SUM(CASE WHEN nama='BERAWAN'           THEN qty ELSE 0 END) AS berawan,
                SUM(CASE WHEN nama='GAS MARK'          THEN qty ELSE 0 END) AS gas_mark,
                SUM(CASE WHEN nama='EJECTOR MARK'      THEN qty ELSE 0 END) AS ejector_mark,
                SUM(CASE WHEN nama='HANGUS'            THEN qty ELSE 0 END) AS hangus,
                SUM(CASE WHEN nama='GAS BURN'          THEN qty ELSE 0 END) AS gas_burn,
                SUM(CASE WHEN nama='SCRATCH'           THEN qty ELSE 0 END) AS scratch,
                SUM(CASE WHEN nama='DISCOLOUR'         THEN qty ELSE 0 END) AS discolour,
                SUM(CASE WHEN nama='SILVER'            THEN qty ELSE 0 END) AS silver,
                SUM(CASE WHEN nama='DENT'              THEN qty ELSE 0 END) AS dent,
                SUM(CASE WHEN nama='FLOW GATE'         THEN qty ELSE 0 END) AS flow_gate,
                SUM(CASE WHEN nama='GATE LONG'         THEN qty ELSE 0 END) AS gate_long,
                SUM(CASE WHEN nama='WELD LINE'         THEN qty ELSE 0 END) AS weld_line,
                SUM(CASE WHEN nama='VOID'              THEN qty ELSE 0 END) AS void,
                SUM(CASE WHEN nama='FLOW MARK'         THEN qty ELSE 0 END) AS flow_mark
        FROM   t_production_op_dl
        WHERE  type='NG'
        GROUP  BY id_production
    ) d ON d.id_production = p.id_production

    WHERE  p.id_product = ?
      AND  p.tanggal    LIKE CONCAT(?, '%')
    ";

    return $this->db->query($sql, array($id_product, $periode));
}

        function total_analisis_byfilter($id_product,$tahun)
        {
                $query = $this->db->query("SELECT p.`kode_product`, p.`nama_product`, p.`cavity`, p.`mesin`,
                SUM(p.`nwt_mp`) AS total_nwt,
                SUM(p.`qty_ok`) AS total_ok,
                SUM(p.`qty_ng`) AS total_ng,
                SUM(p.`qty_lt`) AS total_lt,
                SUM(p.`qty_ng` + p.`qty_ok`) AS tot_produksi_ok_ng,
                ROUND(SUM(p.`production_time`),1) AS total_prod_time,
                ROUND(SUM(p.`nwt_mp` - p.`production_time` - p.`qty_lt`),1) AS total_cdt,
                ROUND(CASE WHEN SUM(p.`qty_ok`) > 0 THEN 3600 *  (SUM(p.`nwt_mp`) - ROUND(SUM(p.`nwt_mp` - p.`production_time` - p.`qty_lt`),1)) / SUM(p.`qty_ok`) * p.`cavity` ELSE 0 END) AS gross,
                ROUND(CASE WHEN SUM(p.`qty_ng` + p.`qty_ok`) > 0 THEN ROUND(SUM(p.`production_time`),1) * 3600 / SUM(p.`qty_ng` + p.`qty_ok`) * p.`cavity` ELSE 0 END) AS nett
                FROM v_production_op AS p 
                WHERE p.id_product = '$id_product' AND p.`tanggal` LIKE '%$tahun%'
                GROUP BY p.`kode_product`, p.`mesin`
                ORDER BY p.tanggal ASC");
         return $query;
        }

        function total_analisis_default($id_product)
        {
                $query = $this->db->query("SELECT p.`kode_product`, p.`nama_product`, p.`cavity`, p.`mesin`,
                SUM(p.`nwt_mp`) AS total_nwt,
                SUM(p.`qty_ok`) AS total_ok,
                SUM(p.`qty_ng`) AS total_ng,
                SUM(p.`qty_lt`) AS total_lt,
                SUM(p.`qty_ng` + p.`qty_ok`) AS tot_produksi_ok_ng,
                ROUND(SUM(p.`production_time`),1) AS total_prod_time,
                ROUND(SUM(p.`nwt_mp` - p.`production_time` - p.`qty_lt`),1) AS total_cdt,
                ROUND(CASE WHEN SUM(p.`qty_ok`) > 0 THEN 3600 *  (SUM(p.`nwt_mp`) - ROUND(SUM(p.`nwt_mp` - p.`production_time` - p.`qty_lt`),1)) / SUM(p.`qty_ok`) * p.`cavity` ELSE 0 END) AS gross,
                ROUND(CASE WHEN SUM(p.`qty_ng` + p.`qty_ok`) > 0 THEN ROUND(SUM(p.`production_time`),1) * 3600 / SUM(p.`qty_ng` + p.`qty_ok`) * p.`cavity` ELSE 0 END) AS nett
                FROM v_production_op AS p 
                WHERE p.id_product = '$id_product' AND MONTH(p.`tanggal`) = MONTH(CURDATE()) AND YEAR(p.`tanggal`) = YEAR(CURDATE())
                GROUP BY p.`kode_product`, p.`mesin`
                ORDER BY p.tanggal ASC");
         return $query;
     }
        

}