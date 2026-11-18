<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_export extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_dpr', 'mm');
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->library('PHPExcel');
        $this->load->library('session');

        $this->data = [
            'user_name'  => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
            'bagian'     => isset($_SESSION['divisi']) ? $_SESSION['divisi'] : '',
            'posisi'     => isset($_SESSION['posisi']) ? $_SESSION['posisi'] : '',
        ];
        $this->mm->cek_login();
    }

    // Export DPR Data
    public function index()
    {
        // Handle CSV export POST request FIRST, before other handlers
        if ($this->input->post('export_csv')) {
            log_message('info', 'CSV Export POST request received');

            // Get month and year from form
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $shift = $this->input->post('shift');

            log_message('info', "CSV Export parameters - Month: $month, Year: $year, Shift: $shift");

            // Validate inputs
            if (empty($month) || empty($year)) {
                $this->session->set_flashdata('error', 'Please select both month and year.');
                redirect('c_export/index');
            }

            // Convert month/year to date range (first day to last day of month)
            $dari = date('Y-m-01', strtotime("$year-$month-01"));
            $sampai = date('Y-m-t', strtotime("$year-$month-01"));

            log_message('info', "Converted to date range: $dari to $sampai");

            // Validate session variables
            if (!isset($_SESSION['nama_actor']) || !isset($_SESSION['posisi'])) {
                $this->session->set_flashdata('error', 'Session expired. Please login again.');
                log_message('error', 'CSV Export failed: Session variables not set');
                redirect('c_export/index');
            }

            try {
                // Log session info for debugging
                $session_nama = isset($_SESSION['nama_actor']) ? $_SESSION['nama_actor'] : 'NOT SET';
                $session_posisi = isset($_SESSION['posisi']) ? $_SESSION['posisi'] : 'NOT SET';
                log_message('info', "CSV EXPORT - Session: nama_actor=$session_nama, posisi=$session_posisi");
                log_message('info', "CSV EXPORT - Date range: $dari to $sampai, shift=$shift");

                // Use EXACTLY the same function call as DPR page
                $data_tabel = $this->mm->tampil_production_rev($dari, $sampai, $shift);
                $row_count = $data_tabel->num_rows();

                log_message('info', "CSV EXPORT - Query returned $row_count rows");

                if ($row_count > 0) {
                    log_message('info', 'Calling export_to_csv function');
                    $this->export_to_csv($data_tabel->result_array(), $month, $year, $shift);
                } else {
                    $error_msg = "No data found for the selected criteria.<br>";
                    $error_msg .= "Month: $month, Year: $year, Shift: $shift<br>";
                    $error_msg .= "Date range searched: $dari to $sampai";
                    $this->session->set_flashdata('error', $error_msg);
                    log_message('error', 'CSV Export failed: No data found for the selected criteria');
                    redirect('c_export/index');
                }
            } catch (Exception $e) {
                $error_msg = 'CSV Export failed: ' . $e->getMessage();
                $this->session->set_flashdata('error', $error_msg);
                log_message('error', 'CSV Export exception: ' . $e->getMessage());
                redirect('c_export/index');
            }
        }

        // Handle view_export POST request - redirect to DataTable view
        if ($this->input->post('view_export')) {
            log_message('info', 'View & Export POST request received');

            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $shift = $this->input->post('shift');

            // Validate inputs
            if (empty($month) || empty($year)) {
                $this->session->set_flashdata('error', 'Please select both month and year.');
                redirect('c_export/index');
            }

            // Redirect to view_month_data with parameters
            redirect('c_export/view_month_data/' . $month . '/' . $year . '/' . urlencode($shift));
        }

        // Handle export POST request FIRST, before loading any views
        if ($this->input->post('export')) {
            log_message('info', 'Export POST request received');

            // Get month and year from form
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $shift = $this->input->post('shift');

            log_message('info', "Export parameters - Month: $month, Year: $year, Shift: $shift");

            // Validate inputs
            if (empty($month) || empty($year)) {
                $this->session->set_flashdata('error', 'Please select both month and year.');
                redirect('c_export/index');
            }

            // Convert month/year to date range (first day to last day of month)
            $dari = date('Y-m-01', strtotime("$year-$month-01"));
            $sampai = date('Y-m-t', strtotime("$year-$month-01"));

            log_message('info', "Converted to date range: $dari to $sampai");

            // Validate session variables
            if (!isset($_SESSION['nama_actor']) || !isset($_SESSION['posisi'])) {
                $this->session->set_flashdata('error', 'Session expired. Please login again.');
                log_message('error', 'Export failed: Session variables not set');
                redirect('c_export/index');
            }

            try {
                // Log session info for debugging
                $session_nama = isset($_SESSION['nama_actor']) ? $_SESSION['nama_actor'] : 'NOT SET';
                $session_posisi = isset($_SESSION['posisi']) ? $_SESSION['posisi'] : 'NOT SET';
                log_message('info', "EXPORT PAGE - Session: nama_actor=$session_nama, posisi=$session_posisi");
                log_message('info', "EXPORT PAGE - Date range: $dari to $sampai, shift=$shift");

                // Use EXACTLY the same function call as DPR page
                $data_tabel = $this->mm->tampil_production_rev($dari, $sampai, $shift);
                $row_count = $data_tabel->num_rows();

                log_message('info', "EXPORT PAGE - Query returned $row_count rows");

                if ($row_count > 0) {
                    log_message('info', 'Calling export_dpr_excel function');
                    $this->export_dpr_excel($data_tabel->result_array(), $month, $year, $shift, $dari, $sampai);
                } else {
                    $error_msg = "No data found for the selected criteria.<br>";
                    $error_msg .= "Month: $month, Year: $year, Shift: $shift<br>";
                    $error_msg .= "Date range searched: $dari to $sampai";
                    $this->session->set_flashdata('error', $error_msg);
                    log_message('error', 'Export failed: No data found for the selected criteria');
                    redirect('c_export/index');
                }
            } catch (Exception $e) {
                $error_msg = 'Export failed: ' . $e->getMessage();
                $this->session->set_flashdata('error', $error_msg);
                log_message('error', 'Export exception: ' . $e->getMessage());
                redirect('c_export/index');
            }
        }

        // Get current month and year
        $current_month = date('m');
        $current_year = date('Y');

        // Generate year range (current year - 5 to current year + 1)
        $years = array();
        for ($y = $current_year - 5; $y <= $current_year + 1; $y++) {
            $years[] = $y;
        }

        $data = [
            'data'   => $this->data,
            'aktif'  => 'export',
            'month'  => $current_month,
            'year'   => $current_year,
            'years'  => $years,
            'shift'  => 'All',
        ];
        $this->load->view('layout/sidebar', $data);
        $this->load->view('export/dpr', $data);
    }

    // View month data in DataTable for export
    public function view_month_data($month = null, $year = null, $shift = 'All')
    {
        // If parameters not provided in URL, check POST
        if ($month === null) {
            $month = $this->input->post('month');
        }
        if ($year === null) {
            $year = $this->input->post('year');
        }
        if ($shift === 'All' && $this->input->post('shift')) {
            $shift = $this->input->post('shift');
        }

        // Validate inputs
        if (empty($month) || empty($year)) {
            $this->session->set_flashdata('error', 'Please select both month and year.');
            redirect('c_export/index');
        }

        // Convert month/year to date range (first day to last day of month)
        $dari = date('Y-m-01', strtotime("$year-$month-01"));
        $sampai = date('Y-m-t', strtotime("$year-$month-01"));

        log_message('info', "View month data - Month: $month, Year: $year, Shift: $shift");
        log_message('info', "Converted to date range: $dari to $sampai");

        try {
            // Use EXACTLY the same function call as DPR page
            $data_tabel = $this->mm->tampil_production_rev($dari, $sampai, $shift);
            $row_count = $data_tabel->num_rows();

            log_message('info', "Query returned $row_count rows");

            // Get month name for display
            $month_name = date('F', strtotime("$year-$month-01"));

            $data = [
                'data'              => $this->data,
                'aktif'             => 'export',
                'data_tabel'        => $data_tabel,
                'row_count'         => $row_count,
                'month'             => $month,
                'year'              => $year,
                'month_name'        => $month_name,
                'shift'             => $shift,
                'dari'              => $dari,
                'sampai'            => $sampai,
            ];

            $this->load->view('layout/sidebar', $data);
            $this->load->view('export/view_month_dpr', $data);

        } catch (Exception $e) {
            $error_msg = 'Failed to load data: ' . $e->getMessage();
            $this->session->set_flashdata('error', $error_msg);
            log_message('error', 'View month data exception: ' . $e->getMessage());
            redirect('c_export/index');
        }
    }

    // Excel export function for DPR
    private function export_dpr_excel($data, $month, $year, $shift, $first_day, $last_day)
    {
        // Suppress deprecated warnings for PHPExcel compatibility with PHP 7.4+
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

        try {
            // Explicitly load PHPExcel_IOFactory if not already loaded
            if (!class_exists('PHPExcel_IOFactory')) {
                require_once(APPPATH . 'libraries/PHPExcel/IOFactory.php');
            }

            log_message('info', 'PHPExcel_IOFactory loaded successfully');

            $objPHPExcel = new PHPExcel();

            // Get month name
            $month_name = date('F', strtotime("$year-$month-01"));

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("DPR System")
                                         ->setLastModifiedBy("DPR System")
                                         ->setTitle("DPR Export")
                                         ->setSubject("Daily Production Report")
                                         ->setDescription("DPR data for $month_name $year");

            // Add header
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'DPR Export - Daily Production Report')
                        ->setCellValue('A2', 'Period: ' . $month_name . ' ' . $year)
                        ->setCellValue('A3', 'Date Range: ' . $first_day . ' to ' . $last_day)
                        ->setCellValue('A4', 'Shift: ' . $shift)
                        ->setCellValue('A5', '');

            // Set column headers - all 104 columns matching DPR table
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A6', 'No')
                        ->setCellValue('B6', 'Tanggal')
                        ->setCellValue('C6', 'Mesin')
                        ->setCellValue('D6', 'Shift')
                        ->setCellValue('E6', 'Group Shift')
                        ->setCellValue('F6', 'Shift Hr')
                        ->setCellValue('G6', 'W H')
                        ->setCellValue('H6', 'O T')
                        ->setCellValue('I6', 'Total LT')
                        ->setCellValue('J6', 'Product ID')
                        ->setCellValue('K6', 'Product Name')
                        ->setCellValue('L6', 'Matl Lot No')
                        ->setCellValue('M6', 'Tool')
                        ->setCellValue('N6', 'OK')
                        ->setCellValue('O6', 'WIP')
                        ->setCellValue('P6', 'Target Qty')
                        ->setCellValue('Q6', 'Total Defect')
                        ->setCellValue('R6', 'CT Std')
                        ->setCellValue('S6', 'CT Actual')
                        ->setCellValue('T6', 'N-Prod')
                        ->setCellValue('U6', 'G-Prod')
                        ->setCellValue('V6', 'NG-Bending')
                        ->setCellValue('W6', 'NG-Berawan')
                        ->setCellValue('X6', 'NG-Blackdot')
                        ->setCellValue('Y6', 'NG-Broken')
                        ->setCellValue('Z6', 'NG-Crack')
                        ->setCellValue('AA6', 'NG-Dent')
                        ->setCellValue('AB6', 'NG-Dirty')
                        ->setCellValue('AC6', 'NG-Discolour')
                        ->setCellValue('AD6', 'NG-Ejector Mark')
                        ->setCellValue('AE6', 'NG-Flash')
                        ->setCellValue('AF6', 'NG-Flow Gate')
                        ->setCellValue('AG6', 'NG-Flow Mark')
                        ->setCellValue('AH6', 'NG-Foreign Material')
                        ->setCellValue('AI6', 'NG-Gas Burn')
                        ->setCellValue('AJ6', 'NG-Gas Mark')
                        ->setCellValue('AK6', 'NG-Gate Bolong')
                        ->setCellValue('AL6', 'NG-Gate Long')
                        ->setCellValue('AM6', 'NG-Hangus')
                        ->setCellValue('AN6', 'NG-Hike')
                        ->setCellValue('AO6', 'NG-Oil')
                        ->setCellValue('AP6', 'NG-Oversize')
                        ->setCellValue('AQ6', 'NG-Pin Plong')
                        ->setCellValue('AR6', 'NG-Pin Seret')
                        ->setCellValue('AS6', 'NG-Scratch')
                        ->setCellValue('AT6', 'NG-Settingan')
                        ->setCellValue('AU6', 'NG-Short Shoot')
                        ->setCellValue('AV6', 'NG-Silver')
                        ->setCellValue('AW6', 'NG-Sink Mark')
                        ->setCellValue('AX6', 'NG-Undercut')
                        ->setCellValue('AY6', 'NG-Undersize')
                        ->setCellValue('AZ6', 'NG-Void')
                        ->setCellValue('BA6', 'NG-Waving')
                        ->setCellValue('BB6', 'NG-Weld Line')
                        ->setCellValue('BC6', 'NG-White Dot')
                        ->setCellValue('BD6', 'NG-White Mark')
                        ->setCellValue('BE6', 'LT-Adjust Parameter')
                        ->setCellValue('BF6', 'LT-Pre Heating Material')
                        ->setCellValue('BG6', 'LT-Cleaning Hopper & Barrel')
                        ->setCellValue('BH6', 'LT-Set Up Mold')
                        ->setCellValue('BI6', 'LT-Set Up Parameter Machine')
                        ->setCellValue('BJ6', 'LT-IPQC Inspection')
                        ->setCellValue('BK6', 'LT-No Packing')
                        ->setCellValue('BL6', 'LT-No Material')
                        ->setCellValue('BM6', 'LT-Material Problem')
                        ->setCellValue('BN6', 'LT-No Operator')
                        ->setCellValue('BO6', 'LT-Daily Checklist')
                        ->setCellValue('BP6', 'LT-Overhoule Mold')
                        ->setCellValue('BQ6', 'LT-Mold Problem')
                        ->setCellValue('BR6', 'LT-Trial')
                        ->setCellValue('BS6', 'LT-Machine')
                        ->setCellValue('BT6', 'LT-Hopper Dryer')
                        ->setCellValue('BU6', 'LT-Robot')
                        ->setCellValue('BV6', 'LT-MTC')
                        ->setCellValue('BW6', 'LT-Cooling Tower')
                        ->setCellValue('BX6', 'LT-Compressor')
                        ->setCellValue('BY6', 'LT-Listrik')
                        ->setCellValue('BZ6', 'LT-QC Lolos')
                        ->setCellValue('CA6', 'Calc DT')
                        ->setCellValue('CB6', 'Delta DT')
                        ->setCellValue('CC6', 'Mach. Use')
                        ->setCellValue('CD6', 'Matl Kg/pcs')
                        ->setCellValue('CE6', '% OT')
                        ->setCellValue('CF6', 'Runner')
                        ->setCellValue('CG6', 'NG Start Up')
                        ->setCellValue('CH6', 'Loss Purging Matl')
                        ->setCellValue('CI6', 'Set Up Awal')
                        ->setCellValue('CJ6', 'Trial')
                        ->setCellValue('CK6', 'Operator')
                        ->setCellValue('CL6', 'Kanit')
                        ->setCellValue('CM6', 'Customer')
                        ->setCellValue('CN6', 'Keterangan')
                        ->setCellValue('CO6', 'Cutting Tool')
                        ->setCellValue('CP6', 'Verif. Kanit')
                        ->setCellValue('CQ6', 'Verif. Kasi');

            // Style the header row
            $objPHPExcel->getActiveSheet()->getStyle('A6:CQ6')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A6:CO6')->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('E0E0E0');

            // Sort data to match DPR page ordering (cek_kanit DESC, tanggal ASC, mesin ASC)
            usort($data, function($a, $b) {
                // First sort by cek_kanit (DESC - verified first)
                if ($a['cek_kanit'] != $b['cek_kanit']) {
                    return ($b['cek_kanit'] - $a['cek_kanit']);
                }
                // Then sort by tanggal (ASC)
                if ($a['tanggal'] != $b['tanggal']) {
                    return strcmp($a['tanggal'], $b['tanggal']);
                }
                // Finally sort by mesin (ASC)
                return strcmp($a['mesin'], $b['mesin']);
            });

            // Log first few IDs to verify order
            $first_ids = array_slice(array_column($data, 'id'), 0, 5);
            log_message('info', 'EXPORT PAGE - First 5 IDs after sorting: ' . implode(', ', $first_ids));

            // Add data - all 104 columns
            $row = 7;
            $no = 1;
            foreach ($data as $item) {
                // Calculate Calc DT
                $cdt = (($item['nwt_mp'] + $item['ot_mp']) - ($item['production_time'] + $item['qty_lt']));
                $cdt_new = round($cdt, 1);

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $row, $no)
                            ->setCellValue('B' . $row, $item['tanggal'])
                            ->setCellValue('C' . $row, $item['mesin'])
                            ->setCellValue('D' . $row, $item['shift'])
                            ->setCellValue('E' . $row, $item['group'])
                            ->setCellValue('F' . $row, $item['nwt_mp'])
                            ->setCellValue('G' . $row, $item['production_time'])
                            ->setCellValue('H' . $row, $item['ot_mp'])
                            ->setCellValue('I' . $row, $item['qty_lt'])
                            ->setCellValue('J' . $row, $item['kode_product'])
                            ->setCellValue('K' . $row, $item['nama_product'])
                            ->setCellValue('L' . $row, $item['lot_global'])
                            ->setCellValue('M' . $row, $item['tooling'])
                            ->setCellValue('N' . $row, $item['qty_ok'])
                            ->setCellValue('O' . $row, '')
                            ->setCellValue('P' . $row, $item['target_mc'])
                            ->setCellValue('Q' . $row, $item['qty_ng'])
                            ->setCellValue('R' . $row, $item['ct_mc'])
                            ->setCellValue('S' . $row, $item['ct_mc_aktual'])
                            ->setCellValue('T' . $row, $item['nett_prod'])
                            ->setCellValue('U' . $row, $item['gross_prod'])
                            ->setCellValue('V' . $row, $item['bending'])
                            ->setCellValue('W' . $row, $item['berawan'])
                            ->setCellValue('X' . $row, $item['blackdot'])
                            ->setCellValue('Y' . $row, $item['broken'])
                            ->setCellValue('Z' . $row, $item['crack'])
                            ->setCellValue('AA' . $row, $item['dent'])
                            ->setCellValue('AB' . $row, $item['dirty'])
                            ->setCellValue('AC' . $row, $item['discolour'])
                            ->setCellValue('AD' . $row, $item['ejector_mark'])
                            ->setCellValue('AE' . $row, $item['flash'])
                            ->setCellValue('AF' . $row, $item['flow_gate'])
                            ->setCellValue('AG' . $row, $item['flow_mark'])
                            ->setCellValue('AH' . $row, $item['fm'])
                            ->setCellValue('AI' . $row, $item['gas_burn'])
                            ->setCellValue('AJ' . $row, $item['gas_mark'])
                            ->setCellValue('AK' . $row, $item['gate_bolong'])
                            ->setCellValue('AL' . $row, $item['gate_long'])
                            ->setCellValue('AM' . $row, $item['hangus'])
                            ->setCellValue('AN' . $row, $item['hike'])
                            ->setCellValue('AO' . $row, $item['oil'])
                            ->setCellValue('AP' . $row, $item['oversize'])
                            ->setCellValue('AQ' . $row, $item['pin_plong'])
                            ->setCellValue('AR' . $row, $item['pin_seret'])
                            ->setCellValue('AS' . $row, $item['scratch'])
                            ->setCellValue('AT' . $row, $item['settingan'])
                            ->setCellValue('AU' . $row, $item['short_shoot'])
                            ->setCellValue('AV' . $row, $item['silver'])
                            ->setCellValue('AW' . $row, $item['sink_mark'])
                            ->setCellValue('AX' . $row, $item['undercut'])
                            ->setCellValue('AY' . $row, $item['under_size'])
                            ->setCellValue('AZ' . $row, $item['void'])
                            ->setCellValue('BA' . $row, $item['waving'])
                            ->setCellValue('BB' . $row, $item['weld_line'])
                            ->setCellValue('BC' . $row, $item['white_dot'])
                            ->setCellValue('BD' . $row, $item['white_mark'])
                            ->setCellValue('BE' . $row, $item['adjust_parameter'])
                            ->setCellValue('BF' . $row, $item['pre_heating_material'])
                            ->setCellValue('BG' . $row, $item['cleaning'])
                            ->setCellValue('BH' . $row, $item['set_up_mold'])
                            ->setCellValue('BI' . $row, $item['set_up_par_machine'])
                            ->setCellValue('BJ' . $row, $item['ipqc_inspection'])
                            ->setCellValue('BK' . $row, $item['no_packing'])
                            ->setCellValue('BL' . $row, $item['no_material'])
                            ->setCellValue('BM' . $row, $item['material_problem'])
                            ->setCellValue('BN' . $row, $item['no_operator'])
                            ->setCellValue('BO' . $row, $item['daily_check_list'])
                            ->setCellValue('BP' . $row, $item['overhoule_mold'])
                            ->setCellValue('BQ' . $row, $item['mold_problem'])
                            ->setCellValue('BR' . $row, $item['trial'])
                            ->setCellValue('BS' . $row, $item['machine'])
                            ->setCellValue('BT' . $row, $item['hopper_dryer'])
                            ->setCellValue('BU' . $row, $item['robot'])
                            ->setCellValue('BV' . $row, $item['mtc'])
                            ->setCellValue('BW' . $row, $item['cooling_tower'])
                            ->setCellValue('BX' . $row, $item['compressor'])
                            ->setCellValue('BY' . $row, $item['listrik'])
                            ->setCellValue('BZ' . $row, $item['qc_lolos'])
                            ->setCellValue('CA' . $row, $cdt_new)
                            ->setCellValue('CB' . $row, '')
                            ->setCellValue('CC' . $row, $item['mach_use'])
                            ->setCellValue('CD' . $row, '')
                            ->setCellValue('CE' . $row, $item['persen_ot'])
                            ->setCellValue('CF' . $row, $item['runner'])
                            ->setCellValue('CG' . $row, '')
                            ->setCellValue('CH' . $row, $item['loss_purge'])
                            ->setCellValue('CI' . $row, '')
                            ->setCellValue('CJ' . $row, '')
                            ->setCellValue('CK' . $row, $item['operator'])
                            ->setCellValue('CL' . $row, $item['kanit'])
                            ->setCellValue('CM' . $row, $item['customer'])
                            ->setCellValue('CN' . $row, $item['keterangan'])
                            ->setCellValue('CO' . $row, isset($item['cutting_tools_codes']) ? $item['cutting_tools_codes'] : '')
                            ->setCellValue('CP' . $row, $item['cek_kanit'] == 1 ? '✓' : '')
                            ->setCellValue('CQ' . $row, $item['cek_kasi'] == 1 ? '✓' : '');
                $row++;
                $no++;
            }

            // Auto-size columns
            foreach (range('A', 'CQ') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Set active sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Clean output buffer to prevent header issues
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            log_message('info', 'Output buffer cleaned, preparing to send headers');

            // Save to file with month-based filename
            $filename = 'DPR_Export_' . $month_name . '_' . $year . '.xls';

            log_message('info', "Sending Excel file: $filename");

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            header('Expires: 0');

            log_message('info', 'Headers sent, creating writer');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

            log_message('info', 'Writer created, saving to output');

            $objWriter->save('php://output');

            log_message('info', 'Excel file saved successfully');

            exit;

        } catch (Exception $e) {
            log_message('error', 'Excel generation error: ' . $e->getMessage());
            show_error('Failed to generate Excel file: ' . $e->getMessage());
        }
    }

    // CSV export function for DPR
    private function export_to_csv($data, $month, $year, $shift)
    {
        try {
            // Get month name for filename
            $month_name = date('F', strtotime("$year-$month-01"));

            log_message('info', "Starting CSV export for $month_name $year");

            // Sort data to match DPR page ordering (cek_kanit DESC, tanggal ASC, mesin ASC)
            usort($data, function($a, $b) {
                // First sort by cek_kanit (DESC - verified first)
                if ($a['cek_kanit'] != $b['cek_kanit']) {
                    return ($b['cek_kanit'] - $a['cek_kanit']);
                }
                // Then sort by tanggal (ASC)
                if ($a['tanggal'] != $b['tanggal']) {
                    return strcmp($a['tanggal'], $b['tanggal']);
                }
                // Finally sort by mesin (ASC)
                return strcmp($a['mesin'], $b['mesin']);
            });

            // Clean output buffer to prevent header issues
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Set CSV headers
            $filename = 'DPR_Export_' . $month_name . '_' . $year . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            header('Expires: 0');

            // Open output stream
            $output = fopen('php://output', 'w');

            // Add BOM for UTF-8 (helps Excel recognize UTF-8)
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Define all 104 column headers matching the view exactly
            $headers = [
                'No.', 'Tanggal', 'Mesin', 'Shift', 'Group Shift', 'Shift Hr', 'W H', 'O T', 'Total LT',
                'Product ID', 'Product Name', 'Matl Lot No', 'Tool', 'OK', 'WIP', 'Target Qty', 'Total Defect',
                'CT Std', 'CT Actual', 'N-Prod', 'G-Prod',
                'NG-Bending', 'NG-Berawan', 'NG-Blackdot', 'NG-Broken', 'NG-Crack', 'NG-Dent', 'NG-Dirty',
                'NG-Discolour', 'NG-Ejector Mark', 'NG-Flash', 'NG-Flow Gate', 'NG-Flow Mark',
                'NG-Foreign Material', 'NG-Gas Burn', 'NG-Gas Mark', 'NG-Gate Bolong', 'NG-Gate Long',
                'NG-Hangus', 'NG-Hike', 'NG-Oil', 'NG-Oversize', 'NG-Pin Plong', 'NG-Pin Seret',
                'NG-Scratch', 'NG-Settingan', 'NG-Short Shoot', 'NG-Silver', 'NG-Sink Mark',
                'NG-Undercut', 'NG-Undersize', 'NG-Void', 'NG-Waving', 'NG-Weld Line',
                'NG-White Dot', 'NG-White Mark',
                'LT-Adjust Parameter', 'LT-Pre Heating Material', 'LT-Cleaning Hopper & Barrel',
                'LT-Set Up Mold', 'LT-Set Up Parameter Machine', 'LT-IPQC Inspection',
                'LT-No Packing', 'LT-No Material', 'LT-Material Problem', 'LT-No Operator',
                'LT-Daily Checklist', 'LT-Overhoule Mold', 'LT-Mold Problem', 'LT-Trial',
                'LT-Machine', 'LT-Hopper Dryer', 'LT-Robot', 'LT-MTC', 'LT-Cooling Tower',
                'LT-Compressor', 'LT-Listrik', 'LT-QC Lolos',
                'Calc DT', 'Delta DT', 'Mach. Use', 'Matl Kg/pcs', '% OT', 'Runner',
                'NG Start Up', 'Loss Purging Matl', 'Set Up Awal', 'Trial',
                'Operator', 'Kanit', 'Customer', 'Keterangan', 'Cutting Tool',
                'Verif. Kanit', 'Verif. Kasi'
            ];

            // Write header row
            fputcsv($output, $headers);

            // Write data rows
            $no = 1;
            foreach ($data as $item) {
                // Calculate Calc DT
                $cdt = (($item['nwt_mp'] + $item['ot_mp']) - ($item['production_time'] + $item['qty_lt']));
                $cdt_new = round($cdt, 1);

                // Prepare row data with all 104 columns
                $row = [
                    $no,
                    $item['tanggal'],
                    $item['mesin'],
                    $item['shift'],
                    $item['group'],
                    $item['nwt_mp'],
                    $item['production_time'],
                    $item['ot_mp'],
                    $item['qty_lt'],
                    $item['kode_product'],
                    $item['nama_product'],
                    $item['lot_global'],
                    $item['tooling'],
                    $item['qty_ok'],
                    '', // WIP - always empty
                    $item['target_mc'],
                    $item['qty_ng'],
                    $item['ct_mc'],
                    $item['ct_mc_aktual'],
                    $item['nett_prod'],
                    $item['gross_prod'],
                    $item['bending'],
                    $item['berawan'],
                    $item['blackdot'],
                    $item['broken'],
                    $item['crack'],
                    $item['dent'],
                    $item['dirty'],
                    $item['discolour'],
                    $item['ejector_mark'],
                    $item['flash'],
                    $item['flow_gate'],
                    $item['flow_mark'],
                    $item['fm'],
                    $item['gas_burn'],
                    $item['gas_mark'],
                    $item['gate_bolong'],
                    $item['gate_long'],
                    $item['hangus'],
                    $item['hike'],
                    $item['oil'],
                    $item['oversize'],
                    $item['pin_plong'],
                    $item['pin_seret'],
                    $item['scratch'],
                    $item['settingan'],
                    $item['short_shoot'],
                    $item['silver'],
                    $item['sink_mark'],
                    $item['undercut'],
                    $item['under_size'],
                    $item['void'],
                    $item['waving'],
                    $item['weld_line'],
                    $item['white_dot'],
                    $item['white_mark'],
                    $item['adjust_parameter'],
                    $item['pre_heating_material'],
                    $item['cleaning'],
                    $item['set_up_mold'],
                    $item['set_up_par_machine'],
                    $item['ipqc_inspection'],
                    $item['no_packing'],
                    $item['no_material'],
                    $item['material_problem'],
                    $item['no_operator'],
                    $item['daily_check_list'],
                    $item['overhoule_mold'],
                    $item['mold_problem'],
                    $item['trial'],
                    $item['machine'],
                    $item['hopper_dryer'],
                    $item['robot'],
                    $item['mtc'],
                    $item['cooling_tower'],
                    $item['compressor'],
                    $item['listrik'],
                    $item['qc_lolos'],
                    $cdt_new,
                    '', // Delta DT - always empty
                    $item['mach_use'],
                    '', // Matl Kg/pcs - always empty
                    $item['persen_ot'],
                    $item['runner'],
                    '', // NG Start Up - always empty
                    $item['loss_purge'],
                    '', // Set Up Awal - always empty
                    '', // Trial - always empty
                    $item['operator'],
                    $item['kanit'],
                    $item['customer'],
                    $item['keterangan'],
                    isset($item['cutting_tools_codes']) ? $item['cutting_tools_codes'] : '',
                    $item['cek_kanit'] == 1 ? '✓' : '',
                    $item['cek_kasi'] == 1 ? '✓' : ''
                ];

                fputcsv($output, $row);
                $no++;
            }

            // Close output stream
            fclose($output);

            log_message('info', "CSV export completed successfully: $filename");

            exit;

        } catch (Exception $e) {
            log_message('error', 'CSV generation error: ' . $e->getMessage());
            show_error('Failed to generate CSV file: ' . $e->getMessage());
        }
    }
}
