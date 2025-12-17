 <title>DPR | Edit Master Product</title>
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
                        <h5>Edit Master Product</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <?php foreach($data_product->result() as $dp) { ?> 
                        <?php } ?>
                        <form action="<?php echo site_url('c_new/update_master_product')?>" method="post" id="my-form">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="kode_produk">Product ID</label>
                                    <input type="text" class="form-control" name="kode_product" value="<?php echo $dp->kode_product ?>">
                                    <input type="hidden" class="form-control" name="id_product" value="<?php echo $dp->id_product ?>">
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="nama_product">Nama Produk</label>
                                    <input type="text" class="form-control" name="nama_product" value="<?php echo $dp->nama_product ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="MomID">Mom ID</label>
                                    <input type="text" class="form-control" name="MomID" value="<?php echo $dp->MomID ?>">
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="MomProduct">Mom Produk</label>
                                    <input type="text" class="form-control" name="MomProduct" value="<?php echo $dp->MomProduct ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="kode_proses">Kode Proses</label>
                                    <input type="text" class="form-control" name="kode_proses" value="<?php echo $dp->kode_proses ?>">
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="kode_produk">Nama Proses</label>
                                    <select name="nama_proses" class="form-control">
                                        <option <?php if( $dp->nama_proses =='RMW L'){echo "selected"; } ?> value='RMW L'>RMW L</option>
                                        <option <?php if( $dp->nama_proses =='RMW L AS'){echo "selected"; } ?> value='RMW L AS'>RMW L AS</option>
                                        <option <?php if( $dp->nama_proses =='P1'){echo "selected"; } ?> value='P1'>P1</option>
                                        <option <?php if( $dp->nama_proses =='P2'){echo "selected"; } ?> value='P2'>P2</option>
                                        <option <?php if( $dp->nama_proses =='P3'){echo "selected"; } ?> value='P3'>P3</option>
                                        <option <?php if( $dp->nama_proses =='P4'){echo "selected"; } ?> value='P4'>P4</option>
                                        <option <?php if( $dp->nama_proses =='P5'){echo "selected"; } ?> value='P5'>P5</option>
                                        <option <?php if( $dp->nama_proses =='P6'){echo "selected"; } ?> value='P6'>P6</option>
                                        <option <?php if( $dp->nama_proses =='PLATING'){echo "selected"; } ?> value='PLATING'>PLATING</option>
                                        <option <?php if( $dp->nama_proses =='FINAL'){echo "selected"; } ?> value='FINAL'>FINAL</option>
                                        <option <?php if( $dp->nama_proses =='ASSY'){echo "selected"; } ?> value='ASSY'>ASSY</option>
                                        <option <?php if( $dp->nama_proses =='BARREL'){echo "selected"; } ?> value='BARREL'>BARREL</option>
                                        <option <?php if( $dp->nama_proses =='WASHING'){echo "selected"; } ?> value='WASHING'>WASHING</option>
                                        <option <?php if( $dp->nama_proses =='STAMPING'){echo "selected"; } ?> value='STAMPING'>STAMPING</option>
                                        <option <?php if( $dp->nama_proses =='INSPECTION'){echo "selected"; } ?> value='INSPECTION'>INSPECTION</option>
                                        <option <?php if( $dp->nama_proses =='BG'){echo "selected"; } ?> value='BG'>BABY GRIND</option>
                                    </select>
                                </div>
                            </div>
                     
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="usage">Usage</label>
                                    <input type="text" class="form-control" name="usage" value="<?php echo $dp->usage ?>">
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="cavity">Cavity</label>
                                    <input type="text" class="form-control" name="cavity" value="<?php echo $dp->cavity ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="satuan">Satuan</label>
                                    <input type="text" class="form-control" name="sp" value="<?php echo $dp->sp ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="type">Type</label>
                                    <select name="type" class="form-control">
                                        <option <?php if( $dp->type =='RMW'){echo "selected"; } ?> value='RMW'>RMW</option>
                                        <option <?php if( $dp->type =='WIP'){echo "selected"; } ?> value='WIP'>WIP</option>
                                        <option <?php if( $dp->type =='FG'){echo "selected"; } ?> value='FG'>FG</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="cyt_mc">Cyt MC</label>
                                    <input type="text" class="form-control" name="cyt_mc" value="<?php echo $dp->cyt_mc ?>">
                                </div>
                                <div class="form-group col">
                                    <label for="cyt_mc">Cyt Quo</label>
                                    <input type="text" class="form-control" name="cyt_quo" value="<?php echo $dp->cyt_quo ?>">
                                </div>
                                <div class="form-group col">
                                  <label for="customer">Customer</label>
                                    <input type="text" class="form-control" name="customer" value="<?php echo $dp->customer ?>">
                                </div>
                                <div class="form-group col">
                                    <label for="AccID">Acc ID</label>
                                    <input type="text" class="form-control" name="AccID" value="<?php echo $dp->AccID ?>">
                                </div>
                                <div class="form-group col">
                                  <label for="AccInv">Acc Inv</label>
                                    <input type="text" class="form-control" name="AccInv" value="<?php echo $dp->AccInv ?>">
                                </div>
                                <div class="form-group col">
                                  <label for="cost">Cost</label>
                                    <input type="number" name="cost" class="form-control" value="<?php echo isset($dp->cost) ? $dp->cost : '0'; ?>" step="0.01" min="0">
                                </div>

                            </div>

                              <br>
                              <div class="row justify-content-center">
                                <div class="col mt-2">
                                    <div class="form-group d-flex justify-content-center">
                                        <input type="submit" id="submit" name="" class="btn btn-primary" value="Edit">
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

    
