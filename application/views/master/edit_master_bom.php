 <title>DPR | Edit Master BOM</title>
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
                        <h5>Edit Master Data BOM</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <?php foreach($data_bom->result() as $bom) { ?> 
                        <?php } ?>
                        <form action="<?php echo site_url('c_new/update_master_bom')?>" method="post" id="my-form">
                            <!-- <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="nama_bom">Nama BOM</label>
                                    <input type="text" class="form-control" name="nama_bom" value="<?php// echo $bom->nama_bom ?>" readonly>
                                    <input type="hidden" class="form-control" name="id_bom" value="<?php// echo $bom->id_bom ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                  <label for="kode_produk">Kode Produk</label>
                                    <input type="text" class="form-control" name="kode_produk" value="<?php// echo $bom->kode_product ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nama_produk">Nama Produk</label>
                                    <input type="text" class="form-control" name="nama_produk" value="<?php// echo $bom->nama_product ?>" readonly>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nama_proses">Nama Proses</label>
                                    <input type="text" class="form-control" name="nama_proses" value="<?php// echo $bom->nama_proses ?>">
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="kode_produk">Cycle Time Machine</label>
                                    <input type="text" class="form-control" name="cyt_mc_bom" value="<?php// echo $bom->cyt_mc_bom ?>">
                                </div>
                              </div> 
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="kode_produk_bom_detail">Kode Produk BOM Detail</label>
                                    <input type="text" class="form-control" value="<?php// echo $bom->kode_productBOMDetail ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="kode_produk">Nama Produk BOM Detail</label>
                                    <input type="text" class="form-control" value="<?php// echo $bom->nama_productBOMDetail ?>" readonly>
                                </div>
                              </div>   
                              <div class="row justify-content-center">
                                <div class="col mt-3">
                                    <div class="form-group d-flex justify-content-center">
                                        <input type="submit" id="submit" name="" class="btn btn-primary" value="Edit">
                                    </div>
                                </div> 
                               </div> -->  
                               <div class="card rounded">
                                <div class="card-header">
                                    <h5>BOM</h5>
                                </div>
                                <div class="card-body">
                                      <div class="form-group row">
                                        <label for="kode_produk" class="col-sm-2 col-form-label">Pilih Kode Produk / Nama BOM</label>
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" name="id_product" id="id_product" placeholder="id prod" value="<?php echo $bom->id_product ?>">
                                            <input type="hidden" class="form-control" name="id_bom" id="id_bom" placeholder="id_bom" value="<?php echo $bom->id_bom ?>">
                                            <select class="form-control" name="" id="nama_bom" onchange="getKodeProduk(this.value)"></select>
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <label for="cyc_bom" class="col-sm-2 col-form-label">Kode Produk / Nama BOM</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="nama_bom" id="nama_bom_new" value="<?php echo $bom->nama_bom?>" readonly>
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <label for="cyc_bom" class="col-sm-2 col-form-label">Cycle Time BOM</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="cyt_mc_bom" id="cyc_bom" value="<?php echo $bom->cyt_mc_bom?>">
                                        </div>
                                      </div>
                                </div>
                                <div class="card-header border">
                                    <h5>RELEASE</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="kode_produk_release" class="col-sm-2 col-form-label">Pilih Kode Produk Release</label>
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" name="id_product_release" id="id_product_release" value="<?php echo $bom->id_productBOMDetail ?>">
                                            <select class="form-control" name="kode_produk_release" id="kode_produk_release" onchange="getKodeProdukRelease(this.value)"></select>
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <label for="cyc_bom" class="col-sm-2 col-form-label">Kode Produk / Nama BOM Release</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama_bom_release" value="<?php echo $bom->kode_productBOMDetail ?> ( <?php echo $bom->nama_productBOMDetail?> )" readonly>
                                        </div>
                                      </div>
                                      <div class="row justify-content-center mt-3">
                                         <div class="col">
                                            <div class="form-group d-flex justify-content-center">
                                                <input type="submit" id="submit" name="simpan" class="btn btn-primary mt-3" value="Update">
                                             </div>
                                         </div>
                                    </div>
                                </div>
                            </div>                                          
                        </form>
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

    </script>

    
