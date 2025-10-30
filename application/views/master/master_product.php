<title>DPR | Master Product</title>
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
                <div class="col-lg-12">
                    <h2>Action</h2>
                    <div class="row">
                    	<div class="col mb-2">
                            <a href="<?= base_url('c_new/master_productAct'); ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Add Master Product
                            </a> 
                    	</div>
                    </div>
                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Master Data Product</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
            <div class="card rounded p-3 mb-4">
                              <div class="form-inline">
                                <div class="col-sm-9">       
                                  <form action="<?= base_url('c_new/search_master_product'); ?>" method="POST">
                                  <div class="input-group">
                                        <input type="text" class="form-control" name="keyword" placeholder="Cari Kode Product atau Nama Product..." value="<?php echo $keyword ?>" required>
                                        <span class="input-group-btn ml-1">
                                            <button type="submit" class="btn btn-sm btn-success text-white" name="tampil">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </span>
                                  </div>
                                </form>
                                </div>
                              </div>
                              <div class="row ml-3 mt-2">
                                <div class="alert alert-info py-1">
                                    <i class="fa fa-info-circle"></i> <strong>Catatan:</strong> Ketik <strong>Kode Product</strong> atau <strong>Nama Product</strong> untuk menampilkan data produk.
                                </div>
                            </div>
                            </div>

            <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                    <tr>
                        <th style="text-align: center;" class="align-middle">No</th>
				        <th style="text-align: center;" class="align-middle">Product ID</th>
				        <th style="text-align: center;" class="align-middle"><b>Product Name</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Mom ID</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Mom Product</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Kode Proses</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Nama Proses</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Usage</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Cavity</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Satuan</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Type</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Cyt MC</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Cyt Quo</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Customer</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Acc ID</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Acc Inv</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Edit</b></th>
				        <th style="text-align: center;" class="align-middle"><b>Delete</b></th>
                        <th style="text-align: center;" class="align-middle"><b>Discontinue</b></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; 
                                foreach ($data_tabel->result() as $data) : ?>
                                <tr>
                                    <td>
                                        <?php echo $i++ ?>
                                    </td>
                                    <td>
                                        <?php echo $data->kode_product; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->nama_product  ; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->MomID; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->MomProduct; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->kode_proses; ?>
                                    </td>  
                                    <td>
                                        <?php echo $data->nama_proses; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->usage   ; ?>
                                    </td>  
                                    <td>
                                        <?php echo $data->cavity; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->sp; ?>
                                    </td>      
                                    <td>
                                        <?php echo $data->type; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->cyt_mc; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->cyt_quo; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->customer; ?>
                                    </td>
                                     <td>
                                        <?php echo $data->AccID; ?>
                                    </td>
                                    <td>
                                        <?php echo $data->AccInv; ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="<?php echo site_url('c_new/edit_master_product/'.$data->id_product) ?>" class="btn btn-circle btn-primary"><i class="fa fa-pencil-square-o" style="font-size: 15px;"></i></a>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="<?php echo site_url('c_new/delete_master_product/'.$data->id_product) ?>" class="btn btn-danger btn-circle delete"><i class="fa fa-trash" style="font-size: 15px;"></i></a>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="custom-control custom-switch">
                                          <input type="checkbox" class="custom-control-input discontinue-switch" id="discontinueSwitch<?php echo $data->id_product; ?>" data-id="<?php echo $data->id_product; ?>" <?php echo (isset($data->discontinue) && $data->discontinue == 1) ? 'checked' : ''; ?>>
                                          <label class="custom-control-label" for="discontinueSwitch<?php echo $data->id_product; ?>"></label>
                                        </div>
                                    </td>                                    
                                </tr>
                                <?php endforeach; ?>
                   
				</tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Product ID</th>
                        <th><b>Product Name</b></th>
                        <th><b>Mom ID</b></th>
                        <th><b>Mom Product</b></th>
                        <th><b>Kode Proses</b></th>
                        <th><b>Nama Proses</b></th>
                        <th><b>Usage</b></th>
                        <th><b>Cavity</b></th>
                        <th><b>Satuan</b></th>
                        <th><b>Type</b></th>
                        <th><b>Cyt MC</b></th>
                        <th><b>Cyt Quo</b></th>
                        <th><b>Customer</b></th>
                        <th><b>Acc ID</b></th>
                        <th><b>Acc Inv</b></th>
                        <th><b>Edit</b></th>
                        <th><b>Delete</b></th>
                        <th><b>Discontinue</b></th>
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
        $(document).ready(function() 
        {
            $('.delete').click(function() 
            {
            return confirm("Are you sure you want to delete?");
            });
        });

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
                        left: 3
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

            $('.dataTables-example').on('change', '.discontinue-switch', function() {
                var productId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var newStatus = isChecked ? 1 : 0;
                var switch_element = $(this);

                $.ajax({
                    url: "<?php echo site_url('c_new/update_discontinue_status'); ?>",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        id_product: productId,
                        discontinue: newStatus
                    },
                    success: function(response) {
                        if (!response.success) {
                            console.error('Failed to update status: ' + (response.message || 'Unknown error'));
                            // revert the switch
                            switch_element.prop('checked', !isChecked);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred while updating status: ' + error);
                        // revert the switch
                        switch_element.prop('checked', !isChecked);
                    }
                });
            });

        });

    </script>

    
