 <title>Report | Kanit Performance</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

<style>
    th, td { white-space: nowrap; } 
    div.dataTables_wrapper {
        width: auto;
        /*width: 1000px;*/
        height: auto;
        margin: 0 auto;
    }
    tr {background-color: white} 
}
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Cycle Time By Product</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <?= form_open('c_report/reportCT'); ?>  
                        <div class="card rounded mb-4">
                            <div class="card-header">
                                <h2>Filter Data</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col"> <b>Tahun</b>
                                        <input type="number" name="tahun" class="form-control" value="<?= isset($tahun) ? $tahun : date('Y'); ?>" min="2000" max="<?= date('Y'); ?>"> </div>
                                    <div class="col d-flex align-items-end">
                                        <button type="submit" name="show" value="Show" class="btn btn-primary">Show</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?= form_close(); ?>
                    <hr>
                    <?php
                    // DEBUG: Remove var_dump for production
                    // if (isset($data_ct_by_product_yearly)) {
                    //     echo '<pre>';
                    //     var_dump($data_ct_by_product_yearly);
                    //     echo '</pre>';
                    // }
                    ?>
                    <table class="table table-bordered table-striped dataTables-example" id="ctByProductTable">
                        <thead>
                            <tr>
                                <th rowspan="2">Kode Product</th>
                                <th rowspan="2">Nama Product</th>
                                <?php
                                $months_full = [
                                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                ];
                                foreach ($months_full as $m) {
                                    echo "<th colspan='3' class='text-center'>{$m}</th>";
                                }
                                ?>
                            </tr>
                            <tr>
                                <?php for ($i = 0; $i < 12; $i++) {
                                    echo "<th>CT Quo</th><th>CT Std</th><th>CT Aktual</th>";
                                } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data_ct_by_product_yearly)) : ?>
                                <?php
                                foreach ($data_ct_by_product_yearly as $row) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['kode_product']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['nama_product']) . '</td>';
                                    for ($i = 1; $i <= 12; $i++) {
                                        $ct_quo = isset($row['ct_quo_' . $i]) ? number_format($row['ct_quo_' . $i], 1) : '-';
                                        $ct_std = isset($row['ct_std_' . $i]) ? number_format($row['ct_std_' . $i], 1) : '-';
                                        $ct_aktual = isset($row['ct_aktual_' . $i]) ? number_format($row['ct_aktual_' . $i], 1) : '-';
                                        echo "<td>{$ct_quo}</td><td>{$ct_std}</td><td>{$ct_aktual}</td>";
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            <?php else : ?>
                                <tr><td colspan="38" class="text-center">No data available for the selected year.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>

<script>
    $(function() {
        // Only initialize DataTable once, with options
        if (!$.fn.DataTable.isDataTable('.dataTables-example')) {
            $('.dataTables-example').DataTable({
                // Place your options here, or leave blank for default
                // Example:
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 10,
                responsive: false,
                scrollX: true,
                scrollCollapse: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'CT_By_Product_<?= isset($tahun) ? $tahun : date('Y') ?>'},
                    {extend: 'pdf', title: 'CT_By_Product_<?= isset($tahun) ? $tahun : date('Y') ?>'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ],
            });
        }
        // Column search in footer (if you use it)
        $('.dataTables-example tfoot th').each(function () {
            $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
        });
        var table = $('.dataTables-example').DataTable();
        table.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change clear', function () {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
    });
</script>

    <script>
        $(document).ready(function() {
            $('.delete').click(function() {
            return confirm("Are you sure you want to delete?");
            });
        });
    </script>
