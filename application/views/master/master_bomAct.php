
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
     <form action="<?php echo site_url('c_new/add_bom')?>" method="post">
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
                        <select class="form-control" name="" multiple id="nama_bom" onchange="getKodeProduk(this.value)"></select>
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
                        <select class="form-control" name="kode_produk_release" id="kode_produk_release" onchange="getKodeProdukRelease(this.value)">
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
        $('#id_product_release').val($('#kode_produk_release').val());
    }


</script>


    
