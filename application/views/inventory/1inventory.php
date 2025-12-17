 <title>DPR | Inventory</title>
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
                        <h5>Inventory</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable1" style="width:100%" >
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Product</th>
                                                    <th>Nama Product</th>
                                                    <th>View Product Analysis</th>
                                                    <th>View Total Prod.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $id = -1; $no = 0 ; foreach($data_tabel->result_array() as $data)
                                                {  $no++; $id++; ?>
                                                <tr>
                                                    <th><?= $no; ?></th>
                                                    <td><?= $data['kode_product']; ?></td>
                                                    <td><?= $data['nama_product']; ?></td>
                                                    <td><a href="<?= base_url('c_inventory/view_total_prod/'.$data['id_product']); ?>"><center><button class="btn btn-primary btn-circle"><i class="fa fa-search"></i></button></center></a></td>
                                                    <td><a href="<?= base_url('c_inventory/view_analisis/'.$data['id_product']); ?>"><center><button class="btn btn-success btn-circle"><i class="fa fa-search"></i></button></center></a></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Product</th>
                                                    <th>Nama Product</th>
                                                    <th>View Product Analysis</th>
                                                    <th>View Total Prod.</th>
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
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>
    <!-- Page-Level Scripts -->


<script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 2,
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

