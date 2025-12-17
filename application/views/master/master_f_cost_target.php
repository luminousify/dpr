 <title>DPR | Master F - Cost Target</title>
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
                            <a href="<?= base_url('c_new/master_f_costAct'); ?>"><button class="btn btn-success">Add Master F - Cost Target</button></a>
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
                        <h5>Master Data F - Cost Target</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
                                <h5>
                                    <?php 
                                    if($this->session->flashdata('berhasil_tambah') !='')
                                    {
                                        echo '<div class="alert alert-success" style="text-align:center" role="alert">';
                                        echo $this->session->flashdata('berhasil_tambah');
                                        echo '</div>';
                                    }
                                    ?>

                                    <?php 
                                    if($this->session->flashdata('berhasil_update') !='')
                                    {
                                        echo '<div class="alert alert-success" style="text-align:center" role="alert">';
                                        echo $this->session->flashdata('berhasil_update');
                                        echo '</div>';
                                    }
                                    ?>
                                </h5>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                <thead>
                  <tr style="text-align: center;">
                    <th>No</th>
                    <th>Tahun</th>
                    <th>F - Cost (IDR) Target</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                 </thead>
                 <tbody>
                <?php 
                $edit       = base_url('c_new/master_f_costAct/f_cost_target/id/');
                $delete     = base_url('c_new/Delete/master_f_cost/f_cost_target/id/');
                $no = 0;  foreach($data_tabel as $row): $no++;?>
                  <tr>
                      <td class="text-center"><?php echo $no; ?></td>
                      <td><?php echo ($row->tahun) ?></td>
                      <td><?php echo ($row->f_cost_target) ?></td>
                      <td>
                        <center>
                         <a href="<?= $edit.'/'.$row->id; ?>"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a>
                      </td>
                      <td>
                        <center>
                         <a href="<?= $delete.'/'.$row->id; ?>" onclick="return confirm('Are you sure delete ?')" ><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a>
                      </td>
                  </tr>
                <?php endforeach; ?>
                 </tbody>
                 <tfoot>
                  <tr style="text-align: center;">
                    <th>No</th>
                    <th>Tahun</th>
                    <th>F - Cost (IDR) Target</th>
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

    
