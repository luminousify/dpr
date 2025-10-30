<title>DPR | DPR Online</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="d-flex">
                        <div class="mr-auto p-2">
                            <h5>Report Daily OK</h5>
                        </div>

                    </div>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <?= form_open('c_report/report_daily_ok'); ?>
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-4"> <b>Tanggal Dari (mm/dd/yyyy)</b>
                                    <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>">
                                </div>
                                <div class="col-sm-4"> <b>Tanggal Sampai (mm/dd/yyyy)</b>
                                    <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>">
                                </div>
                                <div class="col"> <b>Shift</b>
                                    <select name="shift" class="form-control">
                                        <option <?php if ($shift == 'All') {
                                                    echo "selected";
                                                } ?> value='All'>All</option>
                                        <option <?php if ($shift == '1') {
                                                    echo "selected";
                                                } ?> value='1'>1</option>
                                        <option <?php if ($shift == '2') {
                                                    echo "selected";
                                                } ?> value='2'>2</option>
                                        <option <?php if ($shift == '3') {
                                                    echo "selected";
                                                } ?> value='3'>3</option>
                                    </select>
                                </div>
                                <div class="col"> <br /><input type="submit" name="show" class="btn btn-primary" value="Show"></div>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>


                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Part Code</th>
                                    <th>Nama Part</th>
                                    <th>TOTAL OK</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grand_total = 0;
                                foreach ($data_tabel->result_array() as $data) {
                                    $grand_total += $data['total_ok'];

                                    $background = '#02b2c2';

                                    echo '<tr >';
                                    echo '<td style="text-align: center;"><b>' . $data['kode_product'] . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . $data['nama_product'] . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . number_format($data['total_ok']) . '</b></td>';

                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <!-- Grand Total Row -->
                                <tr style="background-color: #1ab394; color: white; font-weight: bold;">
                                    <td colspan="2" style="text-align: right; padding: 10px;"><b>GRAND TOTAL:</b></td>
                                    <td style="text-align: center; padding: 10px;"><b><?php echo number_format($grand_total); ?></b></td>
                                </tr>
                                <!-- Search Row -->
                                <tr style="text-align: center;">
                                    <th>Part Code</th>
                                    <th>Nama Part</th>
                                    <th>Total OK</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                    <!-- Modal -->
                    <form action="<?php echo base_url('c_report/report_daily_ok') ?>" method="post">

                        <?php
                        $nama = $this->session->userdata('nama_actor');
                        ?>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Verifikasi Kasie</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Modal Data verif kanit -->


                    <!-- Modal Data verif kasi -->
                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Data Verifikasi Kasi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php $this->load->view('layout/footer'); ?>
                    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
                    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
                    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>
                    <!-- Page-Level Scripts -->
                    <script>
                        $(document).ready(function() {
                            // Add search inputs to footer
                            $('.dataTables-example tfoot th').each(function() {
                                var title = $(this).text();
                                $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                            });

                            // Initialize DataTable with fixed columns
                            var table = $('.dataTables-example').DataTable({
                                pageLength: 10,
                                responsive: false,
                                scrollX: true,
                                scrollCollapse: true,
                                paging: true,
                                fixedColumns: {
                                    leftColumns: 3,
                                    rightColumns: 2
                                },
                                dom: '<"html5buttons"B>lTfgitp',
                                buttons: [{
                                        extend: 'copy'
                                    },
                                    {
                                        extend: 'csv'
                                    },
                                    {
                                        extend: 'excel',
                                        title: 'DailyReport'
                                    },
                                    {
                                        extend: 'pdf',
                                        title: 'DailyReport'
                                    },
                                    {
                                        extend: 'print',
                                        customize: function(win) {
                                            $(win.document.body)
                                                .addClass('white-bg')
                                                .css('font-size', '10px');

                                            $(win.document.body).find('table')
                                                .addClass('compact')
                                                .css('font-size', 'inherit');
                                        }
                                    }
                                ]
                            });

                            // Apply search functionality
                            table.columns().every(function() {
                                var column = this;
                                $('input', this.footer()).on('keyup change', function() {
                                    if (column.search() !== this.value) {
                                        column.search(this.value).draw();
                                    }
                                });
                            });

                            // Handle window resize for better responsiveness
                            $(window).on('resize', function() {
                                table.columns.adjust();
                            });
                        });
                    </script>