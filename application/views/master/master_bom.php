 <title>DPR | Master BOM</title>
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
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $edit       = base_url('c_new/edit_master_bom');
                    $delete     = base_url('c_new/delete_master_bom');
                        $no = 0 ; foreach($data_tabel as $data) 
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data->nama_bom,'</td>';
                            echo '<td>'.$data->kode_product,'</td>';
                            echo '<td>'.$data->nama_product,'</td>';
                            echo '<td>'.$data->nama_proses,'</td>';
                            echo '<td>'.$data->cyt_mc_bom,'</td>';
                            echo '<td>'.$data->kode_productBOMDetail,'</td>';
                            echo '<td>'.$data->nama_productBOMDetail,'</td>';
                            echo '<td><center><a href="'.$edit.'/'.$data->id_bom.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-pencil"></i></button></a></td>';
                            echo '<td><center><a href="'.$delete.'/'.$data->id_bom.'" onclick="return confirm(\'Are you sure?\')"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></td>';
                            echo '</tr>';
                        }
                    ?>
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
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

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

    
