 <title>DPR | Master Operator & Kanit</title>
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
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Action</h2>
                    <div class="row">
                    	<div class="col">
                            <a href="<?= base_url('c_new/master_opAct'); ?>"><button class="btn btn-success">Add Master Operator & Kanit</button></a>
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
                        <h5>Master Data Operator & Kanit</h5>
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                <thead>
                  <tr style="text-align: center;">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>No. WA</th>
                    <th>NIK</th>
                    <th>Password</th>
                    <th>Divisi</th>
                    <th>Line</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                 </thead>
                 <tbody>
                <?php 
                $edit       = base_url('c_new/master_opAct/t_operator/id_operator/');
                $delete     = base_url('c_new/Delete/master_op/t_operator/id_operator/');
                $no = 0;  foreach($data_tabel as $row): $no++;

                if ($row->status == 1) {
                    $warnaNya = 'primary';
                    $textNya = 'Active';
                }
                else{
                    $warnaNya = 'danger';
                    $textNya = 'Non-Active';
                }
                ?>
                  <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo ($row->nama_operator) ?></td>
                      <td><?php echo ($row->jabatan) ?></td>
                      <td><?php echo ($row->no_wa) ?></td>
                      <td><?php echo ($row->nik) ?></td>
                      <td><?php echo ($row->password_op) ?></td>
                      <td><?php echo ($row->divisi) ?></td>
                      <td><?php echo ($row->line) ?></td>
                      <td class="align-middle text-center">
                          <span class="badge badge-pill badge-<?php echo $warnaNya ?>" style="font-size:14px"><?php echo $textNya ?></span>
                      </td>
                      <td>
                        <center>
                         <a href="<?= $edit.'/'.$row->id_operator; ?>"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a>
                      </td>
                      <td>
                        <center>
                         <a href="<?= $delete.'/'.$row->id_operator; ?>" onclick="return confirm('Are you sure delete ?')" ><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a>
                      </td>
                  </tr>
                <?php endforeach; ?>
                 </tbody>
                 <tfoot>
                  <tr style="text-align: center;">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>No. WA</th>
                    <th>NIK</th>
                    <th>Password</th>
                    <th>Divisi</th>
                    <th>Line</th>
                    <th>Status</th>
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

        </div>
        </div>






<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>



    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
			        // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                        right: 2
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Data_2025_12_09'},
                    {extend: 'pdf', title: 'Data_2025_12_09'},

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

    
