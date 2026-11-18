<title>DPR | Master Supply</title>
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
    tr {background-color: white} 
}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Action</h2>
        <div class="row">
            <div class="col">
                <a href="<?= base_url('c_new/master_supplyAct'); ?>"><button class="btn btn-success">Add Master Supply</button></a> 
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
                    <h5>Master Data Supply</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                
                <div class="ibox-content">
                    <?php if ($this->session->flashdata('edit')): ?>
                        <div class="alert alert-success mt-2" style="text-align: center;font-size: 12px" role="alert">
                            <?php echo $this->session->flashdata('edit'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('tambah')): ?>
                        <div class="alert alert-success mt-2" style="text-align: center;font-size: 12px" role="alert">
                            <?php echo $this->session->flashdata('tambah'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('hapus')): ?>
                        <div class="alert alert-success mt-2" style="text-align: center;font-size: 12px" role="alert">
                            <?php echo $this->session->flashdata('hapus'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success mt-2" style="text-align: center;font-size: 12px" role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger mt-2" style="text-align: center;font-size: 12px" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>No</th>
                                    <th>Kode Product</th>
                                    <th>Nama Product</th>
                                    <th>Atas</th>
                                    <th>Bawah</th>
                                    <th>Total</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $edit       = base_url('c_new/edit_master_supply/');
                                $delete     = base_url('c_new/Delete/master_supply/master_supply/id/');
                                $no = 0;  
                                foreach($data_tabel as $row): $no++;
                                // Debug: Check actual values causing double slash
                                // Clean the ID to remove any leading slashes or unwanted characters
                                $clean_id = ltrim($row->id, '/');
                                $clean_id = trim($clean_id);
                                
                                // ID cleaning is done above, no debug output needed
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo isset($row->kode_product) ? $row->kode_product : ''; ?></td>
                                    <td><?php echo isset($row->nama_product) ? $row->nama_product : ''; ?></td>
                                    <td><?php echo isset($row->atas) ? $row->atas : ''; ?></td>
                                    <td><?php echo isset($row->bawah) ? $row->bawah : ''; ?></td>
                                    <td><?php echo (isset($row->atas) ? $row->atas : 0) + (isset($row->bawah) ? $row->bawah : 0); ?></td>
                                    <td>
                                        <center>
                                            <a href="<?= $edit.$clean_id; ?>"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a>
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                            <a href="<?= $delete.$clean_id; ?>" onclick="return confirm('Are you sure delete ?')" ><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a>
                                        </center>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="text-align: center;">
                                    <th>No</th>
                                    <th>Kode Product</th>
                                    <th>Nama Product</th>
                                    <th>Atas</th>
                                    <th>Bawah</th>
                                    <th>Total</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables-example tfoot th').each( function () {
            $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
        } );
        $('.dataTables-example').DataTable({
            pageLength: 10,
            responsive: false,
            scrollX:        true,
            scrollCollapse: true,
            paging:         true,
            columns: [
                null,  // No
                null,  // Kode Product
                null,  // Nama Product
                null,  // Atas
                null,  // Bawah
                null,  // Total
                { orderable: false },  // Edit
                { orderable: false }   // Delete
            ],
            fixedColumns:   {
                left: 3,
                right: 2
            },
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { 
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(:last-child):not(:nth-last-child(2))'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:last-child):not(:nth-last-child(2))'
                    }
                },
                {
                    extend: 'excel', 
                    title: 'MasterSupply',
                    exportOptions: {
                        columns: ':not(:last-child):not(:nth-last-child(2))'
                    }
                },
                {
                    extend: 'pdf', 
                    title: 'MasterSupply',
                    exportOptions: {
                        columns: ':not(:last-child):not(:nth-last-child(2))'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child):not(:nth-last-child(2))'
                    },
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
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;
     
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            }
        });
    });
</script>
