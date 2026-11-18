<title>DPR Export - View Month Data</title>

<!-- DataTables CSS -->
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<!-- DataTables Buttons CSS (CDN) -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .export-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #1ab394;
    }
</style>

<body>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>DPR Export - View & Export Data</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="export-title">
                            DPR Data for <?= $month_name; ?> <?= $year; ?> (Shift: <?= $shift; ?>)
                            <br>
                            <small>Date Range: <?= $dari; ?> to <?= $sampai; ?></small>
                        </div>

                        <?php if ($row_count > 0) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="exportTable" style="width:100%">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th><b>Tanggal</b></th>
                                            <th><b>Mesin</b></th>
                                            <th><b>Shift</b></th>
                                            <th><b>Group Shift</b></th>
                                            <th><b>Shift Hr</b></th>
                                            <th><b>W H</b></th>
                                            <th><b>O T</b></th>
                                            <th><b>Total LT</b></th>
                                            <th><b>Product ID</b></th>
                                            <th><b>Product Name</b></th>
                                            <th><b>Matl Lot No</b></th>
                                            <th><b>Tool</b></th>
                                            <th><b>OK</b></th>
                                            <th><b>WIP</b></th>
                                            <th><b>Target Qty</b></th>
                                            <th><b>Total Defect</b></th>
                                            <th><b>CT Std</b></th>
                                            <th><b>CT Actual</b></th>
                                            <th><b>N-Prod</b></th>
                                            <th><b>G-Prod</b></th>
                                            <th><b>NG-Bending</b></th>
                                            <th><b>NG-Berawan</b></th>
                                            <th><b>NG-Blackdot</b></th>
                                            <th><b>NG-Broken</b></th>
                                            <th><b>NG-Crack</b></th>
                                            <th><b>NG-Dent</b></th>
                                            <th><b>NG-Dirty</b></th>
                                            <th><b>NG-Discolour</b></th>
                                            <th><b>NG-Ejector Mark</b></th>
                                            <th><b>NG-Flash</b></th>
                                            <th><b>NG-Flow Gate</b></th>
                                            <th><b>NG-Flow Mark</b></th>
                                            <th><b>NG-Foreign Material</b></th>
                                            <th><b>NG-Gas Burn</b></th>
                                            <th><b>NG-Gas Mark</b></th>
                                            <th><b>NG-Gate Bolong</b></th>
                                            <th><b>NG-Gate Long</b></th>
                                            <th><b>NG-Hangus</b></th>
                                            <th><b>NG-Hike</b></th>
                                            <th><b>NG-Oil</b></th>
                                            <th><b>NG-Oversize</b></th>
                                            <th><b>NG-Pin Plong</b></th>
                                            <th><b>NG-Pin Seret</b></th>
                                            <th><b>NG-Scratch</b></th>
                                            <th><b>NG-Settingan</b></th>
                                            <th><b>NG-Short Shoot</b></th>
                                            <th><b>NG-Silver</b></th>
                                            <th><b>NG-Sink Mark</b></th>
                                            <th><b>NG-Undercut</b></th>
                                            <th><b>NG-Undersize</b></th>
                                            <th><b>NG-Void</b></th>
                                            <th><b>NG-Waving</b></th>
                                            <th><b>NG-Weld Line</b></th>
                                            <th><b>NG-White Dot</b></th>
                                            <th><b>NG-White Mark</b></th>
                                            <th><b>LT-Adjust Parameter</b></th>
                                            <th><b>LT-Pre Heating Material</b></th>
                                            <th><b>LT-Cleaning Hopper & Barrel</b></th>
                                            <th><b>LT-Set Up Mold</b></th>
                                            <th><b>LT-Set Up Parameter Machine</b></th>
                                            <th><b>LT-IPQC Inspection</b></th>
                                            <th><b>LT-No Packing</b></th>
                                            <th><b>LT-No Material</b></th>
                                            <th><b>LT-Material Problem</b></th>
                                            <th><b>LT-No Operator</b></th>
                                            <th><b>LT-Daily Checklist</b></th>
                                            <th><b>LT-Overhoule Mold</b></th>
                                            <th><b>LT-Mold Problem</b></th>
                                            <th><b>LT-Trial</b></th>
                                            <th><b>LT-Machine</b></th>
                                            <th><b>LT-Hopper Dryer</b></th>
                                            <th><b>LT-Robot</b></th>
                                            <th><b>LT-MTC</b></th>
                                            <th><b>LT-Cooling Tower</b></th>
                                            <th><b>LT-Compressor</b></th>
                                            <th><b>LT-Listrik</b></th>
                                            <th><b>LT-QC Lolos</b></th>
                                            <th><b>Calc DT</b></th>
                                            <th><b>Delta DT</b></th>
                                            <th><b>Mach. Use</b></th>
                                            <th><b>Matl Kg/pcs</b></th>
                                            <th><b>% OT</b></th>
                                            <th><b>Runner</b></th>
                                            <th><b>NG Start Up</b></th>
                                            <th><b>Loss Purging Matl</b></th>
                                            <th><b>Set Up Awal</b></th>
                                            <th><b>Trial</b></th>
                                            <th><b>Operator</b></th>
                                            <th><b>Kanit</b></th>
                                            <th><b>Customer</b></th>
                                            <th><b>Keterangan</b></th>
                                            <th><b>Cutting Tool</b></th>
                                            <th><b>Verif. Kanit</b></th>
                                            <th><b>Verif. Kasi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($data_tabel->result_array() as $row) {
                                            $cdt = (($row['nwt_mp'] + $row['ot_mp']) - ($row['production_time'] + $row['qty_lt']));
                                            $cdt_new = round($cdt, 1);
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><b><?= $no; ?></b></td>
                                                <td><b><?= $row['tanggal']; ?></b></td>
                                                <td><b><?= $row['mesin']; ?></b></td>
                                                <td><b><?= $row['shift']; ?></b></td>
                                                <td><b><?= $row['group']; ?></b></td>
                                                <td><b><?= $row['nwt_mp']; ?></b></td>
                                                <td><b><?= $row['production_time']; ?></b></td>
                                                <td><b><?= $row['ot_mp']; ?></b></td>
                                                <td><b><?= $row['qty_lt']; ?></b></td>
                                                <td><b><?= $row['kode_product']; ?></b></td>
                                                <td><b><?= $row['nama_product']; ?></b></td>
                                                <td><?= $row['lot_global']; ?></td>
                                                <td><b><?= $row['tooling']; ?></b></td>
                                                <td><b><?= $row['qty_ok']; ?></b></td>
                                                <td><b></b></td>
                                                <td><b><?= $row['target_mc']; ?></b></td>
                                                <td><b><?= $row['qty_ng']; ?></b></td>
                                                <td><b><?= $row['ct_mc']; ?></b></td>
                                                <td><b><?= $row['ct_mc_aktual']; ?></b></td>
                                                <td><b><?= $row['nett_prod']; ?></b></td>
                                                <td><b><?= $row['gross_prod']; ?></b></td>
                                                <td><b><?= $row['bending']; ?></b></td>
                                                <td><b><?= $row['berawan']; ?></b></td>
                                                <td><b><?= $row['blackdot']; ?></b></td>
                                                <td><b><?= $row['broken']; ?></b></td>
                                                <td><b><?= $row['crack']; ?></b></td>
                                                <td><b><?= $row['dent']; ?></b></td>
                                                <td><b><?= $row['dirty']; ?></b></td>
                                                <td><b><?= $row['discolour']; ?></b></td>
                                                <td><b><?= $row['ejector_mark']; ?></b></td>
                                                <td><b><?= $row['flash']; ?></b></td>
                                                <td><b><?= $row['flow_gate']; ?></b></td>
                                                <td><b><?= $row['flow_mark']; ?></b></td>
                                                <td><b><?= $row['fm']; ?></b></td>
                                                <td><b><?= $row['gas_burn']; ?></b></td>
                                                <td><b><?= $row['gas_mark']; ?></b></td>
                                                <td><b><?= $row['gate_bolong']; ?></b></td>
                                                <td><b><?= $row['gate_long']; ?></b></td>
                                                <td><b><?= $row['hangus']; ?></b></td>
                                                <td><b><?= $row['hike']; ?></b></td>
                                                <td><b><?= $row['oil']; ?></b></td>
                                                <td><b><?= $row['oversize']; ?></b></td>
                                                <td><b><?= $row['pin_plong']; ?></b></td>
                                                <td><b><?= $row['pin_seret']; ?></b></td>
                                                <td><b><?= $row['scratch']; ?></b></td>
                                                <td><b><?= $row['settingan']; ?></b></td>
                                                <td><b><?= $row['short_shoot']; ?></b></td>
                                                <td><b><?= $row['silver']; ?></b></td>
                                                <td><b><?= $row['sink_mark']; ?></b></td>
                                                <td><b><?= $row['undercut']; ?></b></td>
                                                <td><b><?= $row['under_size']; ?></b></td>
                                                <td><b><?= $row['void']; ?></b></td>
                                                <td><b><?= $row['waving']; ?></b></td>
                                                <td><b><?= $row['weld_line']; ?></b></td>
                                                <td><b><?= $row['white_dot']; ?></b></td>
                                                <td><b><?= $row['white_mark']; ?></b></td>
                                                <td><b><?= $row['adjust_parameter']; ?></b></td>
                                                <td><b><?= $row['pre_heating_material']; ?></b></td>
                                                <td><b><?= $row['cleaning']; ?></b></td>
                                                <td><b><?= $row['set_up_mold']; ?></b></td>
                                                <td><b><?= $row['set_up_par_machine']; ?></b></td>
                                                <td><b><?= $row['ipqc_inspection']; ?></b></td>
                                                <td><b><?= $row['no_packing']; ?></b></td>
                                                <td><b><?= $row['no_material']; ?></b></td>
                                                <td><b><?= $row['material_problem']; ?></b></td>
                                                <td><b><?= $row['no_operator']; ?></b></td>
                                                <td><b><?= $row['daily_check_list']; ?></b></td>
                                                <td><b><?= $row['overhoule_mold']; ?></b></td>
                                                <td><b><?= $row['mold_problem']; ?></b></td>
                                                <td><b><?= $row['trial']; ?></b></td>
                                                <td><b><?= $row['machine']; ?></b></td>
                                                <td><b><?= $row['hopper_dryer']; ?></b></td>
                                                <td><b><?= $row['robot']; ?></b></td>
                                                <td><b><?= $row['mtc']; ?></b></td>
                                                <td><b><?= $row['cooling_tower']; ?></b></td>
                                                <td><b><?= $row['compressor']; ?></b></td>
                                                <td><b><?= $row['listrik']; ?></b></td>
                                                <td><b><?= $row['qc_lolos']; ?></b></td>
                                                <td><b><?= $cdt_new; ?></b></td>
                                                <td><b></b></td>
                                                <td><b><?= $row['mach_use']; ?></b></td>
                                                <td><b></b></td>
                                                <td><b><?= $row['persen_ot']; ?></b></td>
                                                <td><b><?= $row['runner']; ?></b></td>
                                                <td><b></b></td>
                                                <td><b><?= $row['loss_purge']; ?></b></td>
                                                <td><b></b></td>
                                                <td><b></b></td>
                                                <td><b><?= $row['operator']; ?></b></td>
                                                <td><b><?= $row['kanit']; ?></b></td>
                                                <td><b><?= $row['customer']; ?></b></td>
                                                <td><b><?= $row['keterangan']; ?></b></td>
                                                <td><b><?= isset($row['cutting_tools_codes']) ? '<b>' . $row['cutting_tools_codes'] . '</b>' : ''; ?></b></td>
                                                <td><b><?= $row['cek_kanit'] == 1 ? '✓' : ''; ?></b></td>
                                                <td><b><?= $row['cek_kasi'] == 1 ? '✓' : ''; ?></b></td>
                                            </tr>
                                        <?php
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th><b>Tanggal</b></th>
                                            <th><b>Mesin</b></th>
                                            <th><b>Shift</b></th>
                                            <th><b>Group Shift</b></th>
                                            <th><b>Shift Hr</b></th>
                                            <th><b>W H</b></th>
                                            <th><b>O T</b></th>
                                            <th><b>Total LT</b></th>
                                            <th><b>Product ID</b></th>
                                            <th><b>Product Name</b></th>
                                            <th><b>Matl Lot No</b></th>
                                            <th><b>Tool</b></th>
                                            <th><b>OK</b></th>
                                            <th><b>WIP</b></th>
                                            <th><b>Target Qty</b></th>
                                            <th><b>Total Defect</b></th>
                                            <th><b>CT Std</b></th>
                                            <th><b>CT Actual</b></th>
                                            <th><b>N-Prod</b></th>
                                            <th><b>G-Prod</b></th>
                                            <th><b>NG-Bending</b></th>
                                            <th><b>NG-Berawan</b></th>
                                            <th><b>NG-Blackdot</b></th>
                                            <th><b>NG-Broken</b></th>
                                            <th><b>NG-Crack</b></th>
                                            <th><b>NG-Dent</b></th>
                                            <th><b>NG-Dirty</b></th>
                                            <th><b>NG-Discolour</b></th>
                                            <th><b>NG-Ejector Mark</b></th>
                                            <th><b>NG-Flash</b></th>
                                            <th><b>NG-Flow Gate</b></th>
                                            <th><b>NG-Flow Mark</b></th>
                                            <th><b>NG-Foreign Material</b></th>
                                            <th><b>NG-Gas Burn</b></th>
                                            <th><b>NG-Gas Mark</b></th>
                                            <th><b>NG-Gate Bolong</b></th>
                                            <th><b>NG-Gate Long</b></th>
                                            <th><b>NG-Hangus</b></th>
                                            <th><b>NG-Hike</b></th>
                                            <th><b>NG-Oil</b></th>
                                            <th><b>NG-Oversize</b></th>
                                            <th><b>NG-Pin Plong</b></th>
                                            <th><b>NG-Pin Seret</b></th>
                                            <th><b>NG-Scratch</b></th>
                                            <th><b>NG-Settingan</b></th>
                                            <th><b>NG-Short Shoot</b></th>
                                            <th><b>NG-Silver</b></th>
                                            <th><b>NG-Sink Mark</b></th>
                                            <th><b>NG-Undercut</b></th>
                                            <th><b>NG-Undersize</b></th>
                                            <th><b>NG-Void</b></th>
                                            <th><b>NG-Waving</b></th>
                                            <th><b>NG-Weld Line</b></th>
                                            <th><b>NG-White Dot</b></th>
                                            <th><b>NG-White Mark</b></th>
                                            <th><b>LT-Adjust Parameter</b></th>
                                            <th><b>LT-Pre Heating Material</b></th>
                                            <th><b>LT-Cleaning Hopper & Barrel</b></th>
                                            <th><b>LT-Set Up Mold</b></th>
                                            <th><b>LT-Set Up Parameter Machine</b></th>
                                            <th><b>LT-IPQC Inspection</b></th>
                                            <th><b>LT-No Packing</b></th>
                                            <th><b>LT-No Material</b></th>
                                            <th><b>LT-Material Problem</b></th>
                                            <th><b>LT-No Operator</b></th>
                                            <th><b>LT-Daily Checklist</b></th>
                                            <th><b>LT-Overhoule Mold</b></th>
                                            <th><b>LT-Mold Problem</b></th>
                                            <th><b>LT-Trial</b></th>
                                            <th><b>LT-Machine</b></th>
                                            <th><b>LT-Hopper Dryer</b></th>
                                            <th><b>LT-Robot</b></th>
                                            <th><b>LT-MTC</b></th>
                                            <th><b>LT-Cooling Tower</b></th>
                                            <th><b>LT-Compressor</b></th>
                                            <th><b>LT-Listrik</b></th>
                                            <th><b>LT-QC Lolos</b></th>
                                            <th><b>Calc DT</b></th>
                                            <th><b>Delta DT</b></th>
                                            <th><b>Mach. Use</b></th>
                                            <th><b>Matl Kg/pcs</b></th>
                                            <th><b>% OT</b></th>
                                            <th><b>Runner</b></th>
                                            <th><b>NG Start Up</b></th>
                                            <th><b>Loss Purging Matl</b></th>
                                            <th><b>Set Up Awal</b></th>
                                            <th><b>Trial</b></th>
                                            <th><b>Operator</b></th>
                                            <th><b>Kanit</b></th>
                                            <th><b>Customer</b></th>
                                            <th><b>Keterangan</b></th>
                                            <th><b>Cutting Tool</b></th>
                                            <th><b>Verif. Kanit</b></th>
                                            <th><b>Verif. Kasi</b></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-6">
                                    <p><strong>Total Records:</strong> <?= number_format($row_count); ?> rows</p>
                                    <p><strong>Period:</strong> <?= $month_name; ?> <?= $year; ?></p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= base_url('index.php/c_export/index'); ?>" class="btn btn-warning">
                                        <i class="fa fa-arrow-left"></i> Back to Export Page
                                    </a>
                                </div>
                            </div>

                        <?php } else { ?>
                            <div class="alert alert-warning">
                                <h4><i class="fa fa-warning"></i> No Data Found</h4>
                                <p>No DPR data found for the selected criteria.</p>
                                <p>
                                    <strong>Month:</strong> <?= $month_name; ?> <?= $year; ?><br>
                                    <strong>Shift:</strong> <?= $shift; ?><br>
                                    <strong>Date Range:</strong> <?= $dari; ?> to <?= $sampai; ?>
                                </p>
                                <a href="<?= base_url('index.php/c_export/index'); ?>" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> Back to Export Page
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (must load before DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables Core (CDN - compatible versions) -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <!-- DataTables Buttons (CDN - compatible with DataTables 1.13.x) -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <!-- Supporting libraries for export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#exportTable').DataTable({
                pageLength: 25,
                responsive: true,
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                order: [[1, 'asc']], // Sort by date
                columnDefs: [{
                    targets: 0, // First column (No.)
                    render: function(data, type, row, meta) {
                        // Calculate absolute row number across pages
                        var info = $('#exportTable').DataTable().page.info();
                        return (info.page * info.length) + meta.row + 1;
                    }
                }],
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        title: 'DPR_<?= $month_name; ?>_<?= $year; ?>'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i> CSV',
                        title: 'DPR_<?= $month_name; ?>_<?= $year; ?>'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        title: 'DPR_<?= $month_name; ?>_<?= $year; ?>'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        title: 'DPR_<?= $month_name; ?>_<?= $year; ?>'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        title: 'DPR_<?= $month_name; ?>_<?= $year; ?>',
                        customize: function(win) {
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
</body>
