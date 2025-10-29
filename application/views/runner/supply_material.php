 <title>DPR | Material Transaction</title>
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
                        <h5>Daily Supply Material</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                        <div class="ibox-content">
                            <?php if ($this->session->flashdata('update')): ?>
                                <div class="alert alert-success" style="text-align: center;" role="alert">
                                    <?php echo $this->session->flashdata('update'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('delete')): ?>
                                <div class="alert alert-success" style="text-align: center;" role="alert">
                                    <?php echo $this->session->flashdata('delete'); ?>
                                </div>
                            <?php endif; ?>
                            <?= form_open('c_runner/supply_material/'); ?>  
                            <div class="card rounded mb-4">
                                <div class="card-header">
                                    <h2>Filter Data</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row" style="margin-left:2px;">
                                        <div class="col"> <b>Tanggal Dari (mm/dd/yyyy)</b> 
                                            <input type="date" name="tanggal" class="form-control" value="<?= $tanggal; ?>"> 
                                        </div>
                                        <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
                                    </div>
                                </div>
                                <div class="row ml-4">
                                    <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>hari ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                                </div>
                            </div>
                            <?= form_close(); ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>MC No.</th>
                                            <th>Prod. ID</th>
                                            <th>Prod. Name</th>
                                            <th>Material Name</th>
                                            <th>Virgin</th>
                                            <th>Regrind</th>
                                            <th>Lot Material</th>
                                            <th>Master Batch</th>
                                            <th>PIC</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $edit       = base_url('c_runner/supply_materialAct/material_transaction/id');
                                        $delete     = base_url('c_runner/Delete/supply_material/material_transaction/id');
                                            $no = 0 ; foreach($material_transaction as $data) 
                                            {  $no++;
                                                $posisi = $this->session->userdata('posisi');
                                                echo '<tr>';
                                                echo '<td>'.$no;'</td>';
                                                echo '<td>'.$data->tanggal,'</td>';
                                                echo '<td>'.$data->no_mesin,'</td>';
                                                echo '<td>'.$data->kode_produk,'</td>';
                                                echo '<td>'.$data->nama_produk,'</td>';
                                                echo '<td>'.$data->material,'</td>';
                                                echo '<td>'.$data->virgin,'</td>';
                                                echo '<td>'.$data->regrind,'</td>';
                                                echo '<td>'.$data->lot_material,'</td>';
                                                echo '<td>'.$data->master_batch,'</td>';
                                                echo '<td>'.$data->pic,'</td>';
                                                echo '<td><center><a href="'.$edit.'/'.$data->id.'"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a></td>';
                                                echo '<td><center><a href="'.$delete.'/'.$data->id.'"><button class="btn btn-danger btn-circle delete" type="button"><i class="fa fa-trash"></i></button></a>
                                                    </td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>MC No.</th>
                                            <th>Prod. ID</th>
                                            <th>Prod. Name</th>
                                            <th>Material Name</th>
                                            <th>Virgin</th>
                                            <th>Regrind</th>
                                            <th>Lot Material</th>
                                            <th>Master Batch</th>
                                            <th>PIC</th>
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

        $(document).ready(function() {
            $('.delete').click(function() {
            return confirm("Are you sure you want to delete?");
            });
        });
    </script>

    
