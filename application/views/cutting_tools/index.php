<title>DPR | Master Cutting Tools</title>
<?php $this->load->view('layout/sidebar'); ?>
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper { width: auto; height: auto; margin: 0 auto; }
    tr { background-color: white }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Master Cutting Tools</h2>
        <div class="row">
            <div class="col">
                <button class="btn btn-success" data-toggle="collapse" data-target="#addForm">Add Cutting Tool</button>
            </div>
        </div>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Master Data Cutting Tools</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <?php if($this->session->flashdata('tambah')): ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('tambah'); ?></div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('hapus')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('hapus'); ?></div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('edit')): ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('edit'); ?></div>
                    <?php endif; ?>
                    <div id="addForm" class="collapse" style="margin-bottom:20px;">
                        <form action="<?= site_url('c_new/add_cutting_tool') ?>" method="post" class="form-inline">
                            <div class="form-group">
                                <input type="text" name="code" class="form-control" placeholder="Code (e.g. AA1)" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="code_group" class="form-control" placeholder="Group (e.g. A, B, C)" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="cuttingToolsTable" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Group</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr style="text-align: center;">
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Group</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $no=1; foreach($cutting_tools as $tool): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($tool['code']); ?></td>
                                    <td><?= htmlspecialchars($tool['code_group']); ?></td>
                                    <td style="text-align:center;">
                                        <button class="btn btn-warning btn-xs edit-btn" data-id="<?= $tool['id'] ?>">Edit</button>
                                        <a href="<?= site_url('c_new/delete_cutting_tool/'.$tool['id']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this cutting tool?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="editForm" action="<?= site_url('c_new/update_cutting_tool') ?>" method="post">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="editModalLabel">Edit Cutting Tool</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="edit-id">
              <div class="form-group">
                <label for="edit-code">Code</label>
                <input type="text" name="code" id="edit-code" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="edit-code-group">Group</label>
                <input type="text" name="code_group" id="edit-code-group" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?= base_url(); ?>template/js/fixedColumns.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    // Add search inputs to each footer cell
    $('#cuttingToolsTable tfoot th').each(function () {
        $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
    });
    var table = $('#cuttingToolsTable').DataTable({
        pageLength: 10,
        responsive: false,
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        fixedColumns: {
            left: 1,
            right: 1
        },
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'copy' },
            { extend: 'csv' },
            { extend: 'excel', title: 'CuttingTools' },
            { extend: 'pdf', title: 'CuttingTools' },
            { extend: 'print', customize: function(win) {
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            }}
        ],
        initComplete: function() {
            this.api().columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        }
    });
    $('.edit-btn').on('click', function(){
        var id = $(this).data('id');
        $.get('<?= site_url('c_new/edit_cutting_tool/') ?>'+id, function(data){
            var tool = JSON.parse(data);
            $('#edit-id').val(tool.id);
            $('#edit-code').val(tool.code);
            $('#edit-code-group').val(tool.code_group);
            $('#editModal').modal('show');
        });
    });
});
</script>