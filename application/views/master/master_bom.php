 <title>DPR | Master BOM</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">


<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        height: auto;
        margin: 0 auto;
    }
    tr { background-color: white; }
    tr.bom-discontinued td { opacity: 0.45; }
    tr.bom-discontinued td:last-child,
    tr.bom-discontinued td:nth-last-child(2),
    tr.bom-discontinued td:nth-last-child(3) { opacity: 1; }
    .custom-switch .custom-control-label { cursor: pointer; }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Action</h2>
                    <div class="row">
                    	<div class="col">
                            <a href="<?= base_url('c_new/master_bomAct'); ?>"><button class="btn btn-success">Add Master BOM</button></a>
                    	</div>
                    </div>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Master Data BOM</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success" style="text-align: center;" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

            <!-- Status Filter -->
            <div class="row" style="margin-bottom:10px;">
                <div class="col-md-3">
                    <label><b>Filter Status</b></label>
                    <select id="bomStatusFilter" class="form-control">
                        <option value="active">Active</option>
                        <option value="discontinued">Discontinued</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama BOM</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Nama Proses</th>
                        <th>Cyt MC BOM</th>
                        <th>Kode Produk BOM Detail</th>
                        <th>Nama Produk BOM Detail</th>
                        <th><b>Edit</b></th>
                        <th><b>Delete</b></th>
                        <th><b>Discontinue</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $edit   = base_url('c_new/edit_master_bom');
                    $delete = base_url('c_new/delete_master_bom');
                    $no = 0;
                    foreach ($data_tabel as $data):
                        $no++;
                        $is_discontinued = (isset($data->aktif_non_aktif) && $data->aktif_non_aktif === 'NON AKTIF');
                        $row_class = $is_discontinued ? ' class="bom-discontinued"' : '';
                        $checked   = $is_discontinued ? 'checked' : '';
                    ?>
                        <tr<?= $row_class ?> data-status="<?= $is_discontinued ? 'discontinued' : 'active' ?>">
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($data->nama_bom) ?></td>
                            <td><?= htmlspecialchars($data->kode_product) ?></td>
                            <td><?= htmlspecialchars($data->nama_product) ?></td>
                            <td><?= htmlspecialchars($data->nama_proses) ?></td>
                            <td><?= $data->cyt_mc_bom ?></td>
                            <td><?= htmlspecialchars($data->kode_productBOMDetail) ?></td>
                            <td><?= htmlspecialchars($data->nama_productBOMDetail) ?></td>
                            <td class="text-center"><a href="<?= $edit.'/'.$data->id_bom ?>"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-pencil"></i></button></a></td>
                            <td class="text-center"><a href="<?= $delete.'/'.$data->id_bom ?>" onclick="return confirm('Are you sure?')"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input bom-discontinue-switch"
                                        id="bomSwitch<?= $data->id_bom ?>"
                                        data-id="<?= $data->id_bom ?>"
                                        <?= $checked ?>>
                                    <label class="custom-control-label" for="bomSwitch<?= $data->id_bom ?>"></label>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama BOM</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Nama Proses</th>
                        <th>Cyt MC BOM</th>
                        <th>Kode Produk BOM Detail</th>
                        <th>Nama Produk BOM Detail</th>
                        <th><b>Edit</b></th>
                        <th><b>Delete</b></th>
                        <th><b>Discontinue</b></th>
                    </tr>
                </tfoot>
                    </table>

                    
            




            </div>

                    </div>
                </div>
            </div>
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
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each(function () {
                $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
            });

            var table = $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                fixedColumns: { left: 3, right: 3 },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy' },
                    { extend: 'csv' },
                    { extend: 'excel', title: 'ExampleFile' },
                    { extend: 'pdf', title: 'ExampleFile' },
                    { extend: 'print', customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }}
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var that = this;
                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
            });

            // Status filter dropdown
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var filter = $('#bomStatusFilter').val();
                var row    = table.row(dataIndex).node();
                var status = $(row).data('status');
                if (filter === 'all') return true;
                return status === filter;
            });

            $('#bomStatusFilter').on('change', function () {
                table.draw();
            });

            // Apply default filter (active only) on load
            table.draw();

            // Toggle switch AJAX
            $('.dataTables-example').on('change', '.bom-discontinue-switch', function () {
                var id_bom    = $(this).data('id');
                var newStatus = $(this).is(':checked') ? 1 : 0;
                var $switch   = $(this);
                var $row      = $switch.closest('tr');

                $.ajax({
                    url: "<?php echo site_url('c_new/update_bom_discontinue_status'); ?>",
                    type: 'POST',
                    data: { id_bom: id_bom, discontinue: newStatus },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            if (newStatus === 1) {
                                $row.addClass('bom-discontinued').data('status', 'discontinued').attr('data-status', 'discontinued');
                            } else {
                                $row.removeClass('bom-discontinued').data('status', 'active').attr('data-status', 'active');
                            }
                            table.draw(false);
                        } else {
                            alert('Failed to update status.');
                            $switch.prop('checked', !$switch.is(':checked'));
                        }
                    },
                    error: function () {
                        alert('Request failed.');
                        $switch.prop('checked', !$switch.is(':checked'));
                    }
                });
            });
        });
    </script>

    
