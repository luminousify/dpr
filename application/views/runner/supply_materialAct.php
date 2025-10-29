 <title>DPR | Material Transaction <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>

<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">


<!-- Script -->
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
                        <h5><?= $action; ?> Daily Supply Material</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
   
     <?php echo form_open('c_runner/'.$action.'/material_transaction/supply_material'); ?> 
     <!-- ke function add / nama_table / redirect kemana -->
      
            <div class="card rounded">
                <div class="card-header mb-2">
                  <h3>Mixer</h3>
                </div>
                <div class="isi mb-1">
                  <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Date</td>
                        <td style="background:#1ab394;color: white;text-align: center;">MC No.</td>
<!--                         <td style="background:#1ab394;color: white;text-align: center;">Tonnage MC</td> -->
                        <td style="background:#1ab394;color: white;text-align: center;">Prod. Code</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Prod. Name</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Material Name</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Virgin</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Regrind</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Lot Material</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Master Batch</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC</td>
                      </tr>
                    </thead>
                    
                    <tbody>
                      <?php if($action ==  'Edit') {
                        foreach($data_tabel->result() as $data):                     
                        echo '<tr>';
                        echo '<td><input type="date" name="user[0][tanggal]" value="'.$data->tanggal.'" class="form-control"><input type="hidden" name="id" value="'.$data->id.'"><input type="hidden" name="where" value="id"></td>';
                        echo '<td><input type="search form-control" name="user[0][no_mesin]" value="'.$data->no_mesin.'" class="autocompleteMesinEdit" style="width:50px;">';
                        // echo '<td><input type="text" id="tonnase_edit" name="user[0][tonnase]" value="'.$data->tonnase.'" class="form-control" readonly>';
                        echo '<td><input type="search form-control" name="user[0][kode_produk]" value="'.$data->kode_produk.'" class="autocompleteProductEdit">';
                        echo '<td><input type="text" name="user[0][nama_produk]" id="nama_produk_edit" value="'.$data->nama_produk.'" class="form-control" style="width:200px" readonly>';
                        echo '<td><input type="text" name="user[0][material]" id="material_name_edit" value="'.$data->material.'" class="form-control" style="width:200px" readonly>';
                        echo '<td><input type="number" name="user[0][virgin]" value="'.$data->virgin.'" class="form-control">';
                        echo '<td><input type="number" name="user[0][regrind]" value="'.$data->regrind.'" 
                        class="form-control">';
                        echo '<td><input type="text" name="user[0][lot_material]" value="'.$data->lot_material.'" 
                        class="form-control">';
                        echo '<td><input type="text" name="user[0][master_batch]" value="'.$data->master_batch.'" 
                        class="form-control">';
                        echo '<td><input type="search form-control" name="user[0][pic]" value="'.$data->pic.'" class="autocompletePICEdit" style="width:150px;">';
                        echo '</tr>';
                        endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>

        <div class="col-sm12 d-flex justify-content-center">
          <input type="submit" name="simpan" value="<?= $action; ?>" class="btn btn-success mt-4">
        </div>
      <?php echo form_close(); ?>



    </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

        </div>
        </div>






<?php $this->load->view('layout/footer'); ?>

    <!-- Page-Level Scripts -->
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>
  <script>
  

  $(document).ready(function(){
            $('.autocompleteProduct').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#nama_produk'+i ).val(ui.item.nama_product); 
                    $('#material_name'+i).val(ui.item.nama_product_release); 
                    $('#ct_mesin'+i).val(ui.item.cyt_mc);                 
                }

            });
  });
  $(document).ready(function(){
            $('.autocompleteMesin').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompleteMesin');?>",
                select: function (event, ui) {
                    $('#tonnase'+i).val(ui.item.tonnase);               
                }
            });
  });

  $(document).ready(function(){
            $('.autocompletePIC').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompletePIC');?>",
            });
  });


  $(document).ready(function(){
            $('#customFields tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            $('#customFields').DataTable({
                pageLength: 70,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: false,
                    paging:         false,
                    fixedColumns:   {
                        left: 2,
                        right: 1
                    },

            });
        });
  $(document).ready(function(){
            $('.autocompleteProductEdit').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompleteEdit');?>",
                select: function (event, ui) {
                    $('#nama_produk_edit').val(ui.item.nama_product); 
                    $('#material_name_edit').val(ui.item.nama_product_release); 
                    $('#ct_mesin_edit').val(ui.item.cyt_mc);                 
                }

            });
  });

  $(document).ready(function(){
            $('.autocompleteMesinEdit').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompleteMesinEdit');?>",
                select: function (event, ui) {
                    $('#tonnase_edit').val(ui.item.tonnase);             
                }

            });
  });

  $(document).ready(function(){
            $('.autocompletePICEdit').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompletePICEdit');?>",
            });
  });
</script>

    
