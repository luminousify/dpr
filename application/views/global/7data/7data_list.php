<title>DPR | Report 7 Data</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Prod. Detail By Machine</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <?= form_open('c_report/sevendata'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3">
                                    <b>Pilih Tahun</b> 
                                    <select name="tahun" class="form-control">
                                        <?php 
                                        $tahuns = date('Y') - 1;
                                        $now = date('Y') + 1;
                                        for($i = $tahuns; $i <= $now; $i++) { ?>   
                                            <option value="<?= $i; ?>" <?= ($i == $tahun) ? 'selected="selected"' : ''; ?>>
                                                <?= $i; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show">
                                </div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>bulan ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?>

                    <!-- Adjusted table based on the var_dump structure -->
                    <table class="table table-striped table-bordered table-hover dataTables-example2" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Mesin</th>
                                <th class="text-center">Month</th>
                                <th class="text-center">SumOfWH</th>
                                <th class="text-center">SumOfOT</th>
                                <th class="text-center">Total Hour Std</th>
                                <th class="text-center">MachEffHour</th>
                                <th class="text-center">TotalST</th>
                                <th class="text-center">No Material</th>
                                <th class="text-center">No Packing</th>
                                <th class="text-center">Material Problem</th>
                                <th class="text-center">Adjust Parameter</th>
                                <th class="text-center">Daily Checklist</th>
                                <th class="text-center">Pre-heating Material</th>
                                <th class="text-center">Cleaning Hopper Barrel</th>
                                <th class="text-center">Setup Mold</th>
                                <th class="text-center">Setup Parameter Machine</th>
                                <th class="text-center">IPQC Inspection</th>
                                <th class="text-center">Machine</th>
                                <th class="text-center">Hopper Dryer</th>
                                <th class="text-center">Robot</th>
                                <th class="text-center">MTC</th>
                                <th class="text-center">Chiller</th>
                                <th class="text-center">Compressor</th>
                                <th class="text-center">Listrik</th>
                                <th class="text-center">Overhole</th>
                                <th class="text-center">QC Lolos</th>
                                <th class="text-center">Mold Problem</th>
                                <th class="text-center">Trial</th>
                                <th class="text-center">Setup Awal Produksi</th>
                                <th class="text-center">MCH Eff Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 0;
                            foreach ($seventable->result_array() as $row) {
                                $no++;
                                echo '<tr>';
                                echo '<td class="text-center">' . $no . '</td>';
                                echo '<td class="text-center">' . $row['no_mesin'] . '</td>';
                                echo '<td class="text-center">' . $row['month'] . '</td>';
                                echo '<td class="text-center">' . $row['SumOfWH'] . '</td>';
                                echo '<td class="text-center">' . $row['SumOfOT'] . '</td>';
                                echo '<td class="text-center">' . $row['total_hour_std'] . '</td>';
                                echo '<td class="text-center">' . $row['MachEffHour'] . '</td>';
                                echo '<td class="text-center">' . $row['TotalST'] . '</td>';
                                echo '<td class="text-center">' . $row['no_material'] . '</td>';
                                echo '<td class="text-center">' . $row['no_packing'] . '</td>';
                                echo '<td class="text-center">' . $row['material_problem'] . '</td>';
                                echo '<td class="text-center">' . $row['adjust_parameter'] . '</td>';
                                echo '<td class="text-center">' . $row['daily_checklist'] . '</td>';
                                echo '<td class="text-center">' . $row['pre_heating_material'] . '</td>';
                                echo '<td class="text-center">' . $row['cleaning_hopper_barrel'] . '</td>';
                                echo '<td class="text-center">' . $row['setup_mold'] . '</td>';
                                echo '<td class="text-center">' . $row['setup_parameter_machine'] . '</td>';
                                echo '<td class="text-center">' . $row['ipqc_inspection'] . '</td>';
                                echo '<td class="text-center">' . $row['machine'] . '</td>';
                                echo '<td class="text-center">' . $row['hopper_dryer'] . '</td>';
                                echo '<td class="text-center">' . $row['robot'] . '</td>';
                                echo '<td class="text-center">' . $row['mtc'] . '</td>';
                                echo '<td class="text-center">' . $row['chiller'] . '</td>';
                                echo '<td class="text-center">' . $row['compressor'] . '</td>';
                                echo '<td class="text-center">' . $row['listrik'] . '</td>';
                                echo '<td class="text-center">' . $row['overhole'] . '</td>';
                                echo '<td class="text-center">' . $row['qc_lolos'] . '</td>';
                                echo '<td class="text-center">' . $row['mold_problem'] . '</td>';
                                echo '<td class="text-center">' . $row['trial'] . '</td>';
                                echo '<td class="text-center">' . $row['setup_awal_produksi'] . '</td>';
                                echo '<td class="text-center">' . $row['mch_eff_percentage'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Mesin</th>
                                <th class="text-center">Month</th>
                                <th class="text-center">SumOfWH</th>
                                <th class="text-center">SumOfOT</th>
                                <th class="text-center">Total Hour Std</th>
                                <th class="text-center">MachEffHour</th>
                                <th class="text-center">TotalST</th>
                                <th class="text-center">No Material</th>
                                <th class="text-center">No Packing</th>
                                <th class="text-center">Material Problem</th>
                                <th class="text-center">Adjust Parameter</th>
                                <th class="text-center">Daily Checklist</th>
                                <th class="text-center">Pre-heating Material</th>
                                <th class="text-center">Cleaning Hopper Barrel</th>
                                <th class="text-center">Setup Mold</th>
                                <th class="text-center">Setup Parameter Machine</th>
                                <th class="text-center">IPQC Inspection</th>
                                <th class="text-center">Machine</th>
                                <th class="text-center">Hopper Dryer</th>
                                <th class="text-center">Robot</th>
                                <th class="text-center">MTC</th>
                                <th class="text-center">Chiller</th>
                                <th class="text-center">Compressor</th>
                                <th class="text-center">Listrik</th>
                                <th class="text-center">Overhole</th>
                                <th class="text-center">QC Lolos</th>
                                <th class="text-center">Mold Problem</th>
                                <th class="text-center">Trial</th>
                                <th class="text-center">Setup Awal Produksi</th>
                                <th class="text-center">MCH Eff Percentage</th>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- /.ibox-content -->
            </div> <!-- /.ibox -->
        </div> <!-- /.col-lg-12 -->
    </div> <!-- /.row -->
</div> <!-- /.wrapper -->

<?php $this->load->view('layout/footer'); ?>

<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        // Initialize DataTable for the adjusted table.
        $('.dataTables-example2').DataTable({
            pageLength: 10,
            responsive: false,
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            fixedColumns: { left: 1 },
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ReportProdDetail'},
                {extend: 'pdf', title: 'ReportProdDetail'},
                {
                    extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            initComplete: function () {
                this.api().columns().every( function () {
                    var that = this;
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
            }
        });
    });
</script>
