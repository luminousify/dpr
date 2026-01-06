
<title>DPR | Master BOM <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>


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
                        <h5>Add Master Data BOM</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
    <?php if ($this->session->flashdata('gagal')): ?>
        <div class="alert alert-danger mt-2" style="text-align: center;font-size: 12px" role="alert">
            <?php echo $this->session->flashdata('gagal'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success mt-2" style="text-align: center;font-size: 12px" role="alert">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
     <form action="<?php echo site_url('c_new/add_bom')?>" method="post" id="bomForm" onsubmit="return validateBomForm()">
        <div class="card rounded">
            <div class="card-header">
                <h5>BOM</h5>
            </div>
            <div class="card-body">
                  <div class="form-group row">
                    <label for="kode_produk" class="col-sm-2 col-form-label">Pilih Kode Produk / Nama BOM</label>
                    <div class="col-sm-10">
                        <?php  
                        $sql = "SELECT IFNULL(MAX(p.id_bom),0)as id_bom from t_bom as p";
                        $query = $this->db->query($sql);
                        foreach ($query->result() as $row)
                        {
                            $tambah = $row->id_bom;
                            $data  = $tambah+1;      
                        }
                        ?>
                        <input type="hidden" class="form-control" name="user[0][id_bom]" placeholder="" value="<?php echo $data ?>">
                        <input type="hidden" class="form-control" name="user[0][group_bom]" placeholder="" value="<?php echo $data ?>">
                        <input type="hidden" class="form-control" name="user[0][id_product]" id="id_product" placeholder="id prod">
                        <input type="hidden" class="form-control" name="user[0][alias_bom]" value="FG">
                        <input type="hidden" class="form-control" name="user[0][divisi]" value="<?php echo $this->session->userdata('divisi') ?>">
                        <select class="form-control" name="" multiple id="nama_bom"></select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="cyc_bom" class="col-sm-2 col-form-label">Kode Produk / Nama BOM</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user[0][nama_bom]" id="nama_bom_new" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="cyc_bom" class="col-sm-2 col-form-label">Cycle Time BOM</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user[0][cyt_mc_bom]" id="cyc_bom">
                    </div>
                  </div>
            </div>
            <div class="card-header border">
                <h5>RELEASE</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="kode_produk_release" class="col-sm-2 col-form-label">Kode Produk</label>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" name="user_detail[0][id_bom]" id="id_bom_release" value="<?php echo $data ?>">
                        <input type="hidden" class="form-control" name="user_detail[0][id_product]" id="id_product_release">
                        <select class="form-control" name="kode_produk_release" id="kode_produk_release">
                            <option value=''>Please select item ...</option>
                        </select>
                    </div>
                  </div>
                  <div class="row justify-content-center mt-3">
                     <div class="col">
                        <div class="form-group d-flex justify-content-center">
                            <input type="submit" id="submit" name="simpan" class="btn btn-primary mt-3" value="Save">
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
        </div>

        </div>
        </div>
<?php $this->load->view('layout/footer'); ?>

<script>
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
     }).on('change', function() {
         var selectedData = $(this).select2('data');
         if (selectedData && selectedData.length > 0) {
             // Get the first selected item (since it's multiple select, but typically one is selected)
             var selectedItem = selectedData[0];
             $('#nama_bom_new').val(selectedItem.text);
             $('#id_product').val(selectedItem.id);
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
     }).on('change', function() {
         var selectedData = $(this).select2('data');
         if (selectedData && selectedData.length > 0) {
             var selectedItem = selectedData[0];
             $('#id_product_release').val(selectedItem.id);
         }
     });
   });

    // Form validation before submission
    function validateBomForm() {
        var idProduct = $('#id_product').val();
        var namaBom = $('#nama_bom_new').val();
        var idProductRelease = $('#id_product_release').val();
        var errors = [];

        if (!idProduct || idProduct === '') {
            errors.push('Please select a Product/BOM from the dropdown');
        }

        if (!namaBom || namaBom === '') {
            errors.push('Product/BOM name is required');
        }

        if (!idProductRelease || idProductRelease === '') {
            errors.push('Please select a Release Product from the dropdown');
        }

        if (errors.length > 0) {
            alert('Please fix the following errors:\n\n' + errors.join('\n'));
            return false;
        }

        return true;
    }

    // Functions are now handled by select2 change events above
    // Keeping these for backward compatibility if needed elsewhere
    function getKodeProduk(value){
        var selectedData = $('#nama_bom').select2('data');
        if (selectedData && selectedData.length > 0) {
            var selectedItem = selectedData[0];
            $('#nama_bom_new').val(selectedItem.text);
            $('#id_product').val(selectedItem.id);
        }
    }

    function getKodeProdukRelease(value){
        var selectedData = $('#kode_produk_release').select2('data');
        if (selectedData && selectedData.length > 0) {
            var selectedItem = selectedData[0];
            $('#id_product_release').val(selectedItem.id);
        }
    }


</script>


    
