 <title>DPR | Edit Runner & Loss Purge</title>
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
                        <h5>Edit Runner</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                                    <thead>
                                      <tr style="text-align: center;">
                                        <th class="align-middle text-center">Tanggal</th>
                                        <th class="align-middle text-center">No. M/C</th> 
                                        <th class="align-middle text-center"><b>Kode Part</b></th> 
                                        <th class="align-middle text-center"><b>Nama Produk</b></th> 
                                        <th class="align-middle text-center"><b>Shift</b></th> 
                                        <th class="align-middle text-center"><b>Loss Purge + Setting</b></th> 
                                        <th class="align-middle text-center"><b>Runner</b></th> 
                                        <th class="align-middle text-center"><b>Edit</b></th>
                                        <!-- <th rowspan="2" class="align-middle text-center"><b>Action</b></th> -->
                                      </tr>
                                     </thead>
                                     <tbody>
                                   <?php 
                                    foreach($data_tabel->result_array() as $data) 
                                    { 
                                        
                                        echo '<tr>';
                                        echo '<td>'.$data['tanggal'].'</td>';
                                        echo '<td class="align-middle text-center">'.$data['mesin'].'</td>';
                                        echo '<td>'.$data['kode_product'].'</td>';
                                        echo '<td>'.$data['nama_product'].'</td>';
                                        echo '<td>'.$data['shift'].'</td>';
                                        echo '<td>'.$data['loss_purge'].'</td>';
                                        echo '<td>'.$data['runner'].'</td>';
                                        ?>
                                        <td style="text-align: center;" class="align-middle"><button class="btn btn-sm btn-info btn-circle bi bi-pen-fill mr-1 btn-edit" data-id="<?= $data['id_production_op']?>" data-tanggal="<?= $data['tanggal']?>" data-kode_product="<?= $data['kode_product']?>" data-nama_product="<?= $data['nama_product']?>" data-shift="<?= $data['shift']?>" data-loss_purge="<?= $data['loss_purge']?>" data-runner="<?= $data['runner']?>"><center><i class="fa fa-pencil"></i></center></button></td>     
                                        <?php }
                                        echo '</tr>';
                                    
                                ?> 
                                 </tbody>
                                 <tfoot>
                                    <tr style="text-align: center;">
                                        <th class="align-middle text-center">Tanggal</th>
                                        <th class="align-middle text-center">No. M/C</th> 
                                        <th class="align-middle text-center"><b>Kode Part</b></th> 
                                        <th class="align-middle text-center"><b>Nama Produk</b></th> 
                                        <th class="align-middle text-center"><b>Shift</b></th> 
                                        <th class="align-middle text-center"><b>Loss Purge + Setting</b></th> 
                                        <th class="align-middle text-center"><b>Runner</b></th> 
                                        <th class="align-middle text-center"><b>Edit</b></th>
                                        <!-- <th rowspan="2" class="align-middle text-center"><b>Action</b></th> -->
                                      </tr>
                                 </tfoot>
                            </table>
                    </div>
        </div>

        </div>
        </div>


<form action="<?php echo base_url('c_runner/update_runner') ?>" method="post">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Runner & Loss Purge</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ($data_tabel->result_array() as $modal) : ?>  
                <?php endforeach; ?>
                <input type="hidden" class="id" name="id" id="id">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Kode Produk</label>
                        <input type="text" class="form-control kode_product" id="kode_product" name="kode_product" value="<?php echo $modal['kode_product'] ?>" readonly>
                        <input type="hidden" class="form-control tanggal" id="tanggal" name="tanggal" value="<?php echo $modal['tanggal'] ?>" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control nama_product" id="nama_product" name="nama_product" value="<?php echo $modal['nama_product'] ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Shift</label>
                        <input type="text" class="form-control shift" id="shift" name="shift" value="<?php echo $modal['shift'] ?>" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Loss Purge</label>
                        <input type="text" class="form-control loss_purge" id="loss_purge" name="loss_purge" value="<?php echo $modal['loss_purge'] ?>">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Runner</label>
                        <input type="text" class="form-control runner" id="runner" name="runner" value="<?php echo $modal['runner'] ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
                
            </div>
            
            </div>
        </div>
        </div>
    </form>



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

        $(document).ready(function(){
          $("#nama_bom").select2({
             ajax: { 
               url: '<?= base_url() ?>c_new/get_kodeProductRelease',
               type: "post",
               dataType: 'json',
               delay: 250,
               data: function (params) {
                  return {
                    searchTerm: params.term // search term
                  };
               },
               processResults: function (response) {
                  return {
                     results: response
                  };
               },
               cache: true
             }
         });
       });

        $(document).ready(function(){
          $("#kode_produk_release").select2({
             ajax: { 
               url: '<?= base_url() ?>c_new/get_kodeProductRelease',
               type: "post",
               dataType: 'json',
               delay: 250,
               data: function (params) {
                  return {
                    searchTerm: params.term // search term
                  };
               },
               processResults: function (response) {
                  return {
                     results: response
                  };
               },
               cache: true
             }
         });
       });

        function getKodeProduk(value){
            $('#nama_bom_new').val($('#nama_bom').text());
            $('#id_product').val($('#nama_bom').val());
        }

        function getKodeProdukRelease(value){
            $('#nama_bom_release').val($('#kode_produk_release').text());
            $('#id_product_release').val($('#kode_produk_release').val());
        }

        $(document).ready(function(){
 
        // get Edit Product
          $('.btn-edit').click(function(){
              // get data from button edit
              const id                    = $(this).data('id');
              const tanggal                = $(this).data('tanggal');
              const kode_product          = $(this).data('kode_product');
              const nama_product          = $(this).data('nama_product');
              const shift                 = $(this).data('shift');
              const loss_purge            = $(this).data('loss_purge');
              const runner                = $(this).data('runner');
              // Set data to Form Edit
              $('.id').val(id);
              $('.tanggal').val(tanggal);
              $('.kode_product').val(kode_product);
              $('.nama_product').val(nama_product);
              $('.shift').val(shift);
              $('.loss_purge').val(loss_purge);
              $('.runner').val(runner);
              $('#editModal').modal('show');
          });
           
      });

    </script>

    
