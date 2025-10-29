<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report | Kanit Performance</title>
    <link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
    <style>
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            width: auto;
            height: auto;
            margin: 0 auto;
        }
        tr {background-color: white}
    </style>
</head>
<body>
    <?php $this->load->view('layout/sidebar'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Detail Cycle Time By Product</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="mb-3">
                            <b>Product:</b> <span style="font-weight:bold;"><?= htmlspecialchars($kode_product) ?></span> - <span style="font-weight:bold;"><?= htmlspecialchars($data_detail[0]['nama_product'] ?? '') ?></span> &nbsp; | &nbsp;
                            <b>Month:</b> <?= htmlspecialchars($month_bucket) ?>
                            <a href="javascript:history.back()" class="btn btn-secondary btn-sm float-right">Back</a>
                        </div>
                        <table class="table table-bordered table-striped dataTables-example" id="ctDetailByProductTable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>CT MC Aktual</th>
                                    <th>CT MC (bom)</th>
                                    <th>CT Standar</th>
                                    <th>CT Quotation</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data_detail)) : ?>
                                    <?php foreach ($data_detail as $row) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                            <td><?= htmlspecialchars($row['ct_mc_aktual']) ?></td>
                                            <td><?= htmlspecialchars($row['ct_mc']) ?></td>
                                            <td><?= htmlspecialchars($row['cycle_time_std']) ?></td>
                                            <td><?= htmlspecialchars($row['cycle_time_quote']) ?></td>
                                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="6" class="text-center">No data available for the selected period.</td></tr>
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
            if (!$.fn.DataTable.isDataTable('.dataTables-example')) {
                $('.dataTables-example').DataTable({
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
                        {extend: 'excel', title: 'DetailCycleTime'},
                        {extend: 'pdf', title: 'DetailCycleTime'},
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
</body>
</html>
