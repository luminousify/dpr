 <title>DPR | Runner</title>
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
<!-- END HEADING ----> 
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Runner</h5>
                    </div>
                        <div class="ibox-content">
                            <?= form_open('c_runner/runner'); ?>  
                            <div class="card rounded mb-4">
                                <div class="card-header">
                                    <h2>Filter Data</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row" style="margin-left:2px;">
                                        <div class="col-md-4"> <b>Pilih Tanggal (mm/dd/yyyy)</b> 
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
                                <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                                    <thead>
                                      <tr style="text-align: center;">
                                        <th rowspan="2" class="align-middle text-center">Tanggal</th>
                                        <th rowspan="2" class="align-middle text-center">No. M/C</th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Kode Part</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Nama Produksi</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Material</b></th> 
                                        <th colspan="2"><b>Hasil Shift 1</b></th> 
                                        <th colspan="2"><b>Hasil Shift 2</b></th>
                                        <th colspan="2"><b>Hasil Shift 3</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Total Runner</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Edit</b></th>
                                        <!-- <th rowspan="2" class="align-middle text-center"><b>Action</b></th> -->
                                      </tr>
                                      <tr>
                                        <th><b>Runner</b></th> 
                                        <th><b>Loss</b></th> 
                                        <th><b>Runner</b></th> 
                                        <th><b>Loss</b></th> 
                                        <th><b>Runner</b></th> 
                                        <th><b>Loss</b></th> 
                                      </tr>
                                     </thead>
                                     <tbody>
                                   <?php 
                                    //$view       = base_url('index.php/c_new/view_production_reporting_op');
                                    
                                    //$delete     = base_url('index.php/c_new/del_production_reporting_op');
                                    //$user       = $this->session->userdata('posisi');
                                    foreach($data_tabel->result_array() as $data) 
                                    { 
                                        
                                        echo '<tr>';
                                        echo '<td>'.$data['tanggal'].'</td>';
                                        echo '<td class="align-middle text-center">'.$data['mesin'].'</td>';
                                        echo '<td>'.$data['kode_product'].'</td>';
                                        echo '<td>'.$data['nama_product'].'</td>';
                                        echo '<td>'.$data['material'].'</td>';
                                        echo '<td>'.$data['runner1'].'</td>';
                                        echo '<td>'.$data['loss_purge1'].'</td>';
                                        echo '<td>'.$data['runner2'].'</td>';
                                        echo '<td>'.$data['loss_purge2'].'</td>';
                                        echo '<td>'.$data['runner3'].'</td>';
                                        echo '<td>'.$data['loss_purge3'].'</td>';
                                        echo '<td>'.$data['total_runner'].'</td>';
                                        ?>
                                        <td><a href="<?php echo base_url('c_runner/edit_runner/'.$data['tanggal'].'/'.$data['kode_product_new'].'/'.$data['mesin']) ?>"><center><button class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></button></center></a></td>     
                                        <?php }
                                        echo '</tr>';
                                    
                                ?> 
                                 </tbody>
                                 <tfoot>
                                    <tr style="text-align: center;">
                                        <th rowspan="2" class="align-middle text-center">Tanggal</th>
                                        <th rowspan="2" class="align-middle text-center">No. M/C</th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Kode Part</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Nama Produksi</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Material</b></th> 
                                        <th colspan="2"><b>Hasil Shift 1</b></th> 
                                        <th colspan="2"><b>Hasil Shift 2</b></th>
                                        <th colspan="2"><b>Hasil Shift 3</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Total Runner</b></th> 
                                        <th rowspan="2" class="align-middle text-center"><b>Edit</b></th>
                                        <!-- <th rowspan="2" class="align-middle text-center"><b>Action</b></th> -->
                                      </tr>
                                      <tr>
                                        <th><b>Runner</b></th> 
                                        <th><b>Loss</b></th> 
                                        <th><b>Runner</b></th> 
                                        <th><b>Loss</b></th> 
                                        <th><b>Runner</b></th> 
                                        <th><b>Loss</b></th> 
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

    
