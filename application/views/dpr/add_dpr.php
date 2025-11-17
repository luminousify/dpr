<title>DPR | Add DPR Online</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
<link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">
<script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"> 
</script> 
<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>

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

<body>
    <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox ">
          <div class="ibox-title">
            <h5>Add DPR Online</h5>
            <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
                <div class="ibox-content">
                    <div class="container-fluid">
        <form action="<?php echo site_url('c_dpr/add')?>" method="post" id="my-form">
        <div class="align-self-center">
    
              <!-- <div class="col-sm-6"> -->
                <div class="card rounded card border mb-3">
                  <div class="card-body ">
                
                    <?php if ($this->session->flashdata('gagal')): ?>
                <div class="alert alert-danger" style="text-align: center;" role="alert">
                  <?php echo $this->session->flashdata('gagal'); ?>
                </div>
              <?php endif; ?>
                    <div class="card-header bg-primary">
                <center> <b>PRODUCTION ( <?= $data['bagian']; ?> ) </b></center>
              </div>
            <!-- START ROW -->
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label><b>Tanggal <font style="color: red">*</font></b></label>
                      <input type="date" name="user[0][tanggal]" class="form-control" required="" id="tanggal" onchange="lot(this.value)">
                  </div>
                </div>
                
              </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                        <label><b>Shift <font style="color: red">*</font></b></label>
                        <select name="user[0][shift]" class="form-control" required="" id="shift" onchange="lot(this.value)">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          </select>
                    </div>
                  </div>
                  <div class="col">
                   <div class="form-group">
                        <label><b>NWT <font style="color: red">*</font></b></label>
                        <input type="text" name="user[0][nwt_mp]" id="nwt" class="form-control" value="8" required="" pattern="[0-9]+(\.[0-9]+)?" title="Please enter a valid number (e.g., 8 or 8.5). Comma is not allowed." oninput="this.value = this.value.replace(/,/g, '')">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                        <label><b>OT</b></label>
                        <input type="text" name="user[0][ot_mp]" id="ot_mp" class="form-control" value="0">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                        <label><b>Operator <font style="color: red">*</font></b></label>
                        <select name="user[0][operator]" class="form-control" id="operator" required=""  >
                            <option value="">-Choose-</option>
                            <?php foreach ($operator as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
                        </select>
                    </div>
                  </div>
                  <div class="col">
                   <div class="form-group">
                        <label><b>Kanit <font style="color: red">*</font></b></label>
                         <select name="user[0][kanit]" class="form-control" id="kanit" required=""  >
                            <option value="">-Choose-</option>
                            <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
                        </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                        <label><b>Group <font style="color: red">*</font></b></label>
                        <select name="user[0][group]" class="form-control" required="">
                              <option value="">-Choose-</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                        </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                        <label><b>No. & Nama BOM <font style="color: red">*</font></b></label>
                        <input type="search form-control" name="" id="barcode_sparepart_code" class="autocompleteBom" style="width: 100%" required="" id="id_bom" onchange="cekNoNamaBOM()">
                        <input type="hidden" name="" id="tes">
                        <input type="hidden" name="user[0][id_bom]" id="id_bomS" readonly="" class="form-control">
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                        <label><b>Mesin <font style="color: red">*</font></b></label>
<!--                         <select name="user[0][mesin]" id="mesin" style="width:100px;height: 100%" required x-moz-errormessage="Not Empty" class="form-control" onchange="lot(this.value)">
                        </select> -->
                        <input type="text" name="user[0][mesin]" id="mesin" required x-moz-errormessage="Not Empty" class="form-control" onkeyup="lot(this.value)">
                    </div>
                  </div>

                  <div class="col-sm-2">
                    <div class="form-group">
                    <label><b>Proses</b></label>
                        <input type="text" name="user[0][proses]" id="proses" class="form-control" readonly="">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                    <label><b>Customer</b></label>
                        <input type="text" name="user[0][customer]" id="customer" class="form-control" readonly="">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                        <label><b>Production Time </b></label>
                        <input type="text" name="user[0][production_time]" id="production_time" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                        <label><b>Cavity</b></label>
                         <input type="text" name="user[0][cavity]" id="cavity" class="form-control" value="1">
                         <input type="hidden" name="user[0][cavity2]" id="cavity2" class="form-control" >
                        </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                        <label><b>Tooling</b></label>
                        <input type="text" name="user[0][tooling]" class="form-control">
                    </div>
                  </div>
                  <div class="col">
                   <div class="form-group">
                        <label><b>Lot Global</b></label>
                         <p id="lotGlobal"></p>
                          <input type="hidden" name="user[0][lot_global]" id="lotGlobalSave" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                        <label><b>CT MC</b></label>
                         <input type="text" name="user[0][ct_mc]" id="ct_mc" readonly="" class="form-control">
                         <input type="hidden" id="ct_quo" value="">
                    </div>
                  </div>
                  <div class="col">
                   <div class="form-group">
                        <label><b>CT MP</b></label> 
                         <input type="text" name="user[0][ct_mp]" id="ct_mp" readonly="" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="input-group">
                         <input type="text" name="user[0][ct_mc_aktual]" id="ct_mc_aktual" class="form-control" aria-describedby="inputGroupPrepend2" >
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Sec</span>
                        </div>
                    </div>
                  </div>
                  <div class="col">
                   <div class="input-group">
                         <input type="text" name="user[0][ct_mp_aktual]" id="ct_mp_aktual" class="form-control" aria-describedby="inputGroupPrepend2" value="0" >
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Sec</span>
                        </div>
                    </div>
                  </div>
                </div>

                 <div class="row">
                  <div class="col">
                   <div class="form-group">
                        <label><b>Target MC</b></label>
                          <input type="text" name="user[0][target_mc]" id="target_mc" class="form-control" readonly="">
                    </div>
                  </div>
                  <div class="col">
                   <div class="form-group">
                        <label><b>Target MP</b></label>
                          <input type="text" name="user[0][target]" id="target_mp" class="form-control" readonly="">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                        <label><b>Qty OK <font style="color: red">*</font></b></label>
                         <div class="input-group">
                         <input type="text" name="user[0][qty_ok]" required="" class="form-control" onkeyup="lot(this.value)" aria-describedby="inputGroupPrepend2" id="qty" >
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Pcs</span>
                         </div>
                        </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                        <label><b>Keterangan</b></label>
                         <input type="text" name="user[0][keterangan]" class="form-control">
                    </div>
                  </div>
                </div>

                <!-- end body -->
                </div>
            </div>
          
       <!-- START RELEASE -->
       <hr>
       <div class="card rounded card border-success mb-3">
            <div class="card-body ">
              <div class="card-header bg-success">
                  <center> <b>RELEASE</b></center>
                </div>
              <div class="row">
                  <div class="col">
                    <div id="release">
                    </div>
                  </div>
                </div>
            </div>
          </div>
       

      <!-- START DEFECT -->
        <hr>
            <div class="card rounded card border-warning mb-3">
            <div class="card-body ">
              <div class="card-header bg-warning">
                <center> <b>DEFECT </b></center>
              </div>
              <div class="row">
                  <div class="col">
                   <div class="form-group">
                        <label><b>Nama</b></label>
                        <input type="text" name="" id="namaNG" class="autocompletedefect form-control formNG"  >
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col">
                    <div class="form-group">
                         <div class="input-group">
                         <input type="number" name="" id="qtyNG" class="form-control formNG" aria-describedby="inputGroupPrepend2" >
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Pcs</span>
                         </div>
                        </div>
                        <input type="hidden" name="" id="kategoriNG" class="form-control formNG" placeholder="qty"> 
                        <input type="hidden" name="" id="typeNG" class="formNG">
                        <input type="hidden" name="" id="satuanNG" class="formNG">
                    </div>
                  </div>
                  <div class="col">
                    <button class="btn btn-success" class="form-control" onclick="addNG(); return false;">( + )</button>
                  </div>
              
                  <div class="col-sm-12">
                  <div class="table-responsive">
                  <table class="table table-bordered" >
                    <thread>
                    <tr style="background-color: #bee5eb">
                      <td><b>X</b></td>
                      <td><b>No</b></td>
                      <td><b>Nama</b></td>
                      <td><b>Kategori</b></td>
                      <td><b>Qty</b></td>
                      <td><b>Satuan</b></td>
                    </tr>
                    </thread>
                    <tbody id="tableNG">
                    </tbody>
                  </table>
                </div>
                  <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Total NG</span>
                         </div>
                         <input type="text" name="user[0][qty_ng]" id="amountNG" class="form-control" aria-describedby="inputGroupPrepend2" readonly="" value="0" >
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Pcs</span>
                         </div>
                        </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END DEFECT -->
          <hr>
          <div class="card rounded card border-success mb-3">
            <div class="card-body ">
              <div class="card-header bg-success">
                <center> <b>LOSS TIME</b></center>
              </div>
              
              <div class="row">
                  <div class="col">
                   <div class="form-group">
                        <label><b>Nama</b></label>
                        <input type="text" name="" id="namaLT" class="autocompletelosstime form-control formLT" placeholder="nama">
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col">
                   <div class="form-group">
                        <div class="input-group">
                         <input type="number" name="" id="qtyLT" class="form-control formLT"  aria-describedby="inputGroupPrepend2" step="0.01" placeholder="0.5">
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Jam</span>
                         </div>
                         <!-- <input type="number" name="" id="qtyLT_jam" class="form-control ml-3"  aria-describedby="inputGroupPrepend2" readonly>
                         <div class="input-group-prepend2">
                          <span class="input-group-text" id="inputGroupPrepend2">Jam</span>
                         </div> -->
                        </div>
                        <input type="hidden" name="" id="kategoriLT" class="form-control formLT" placeholder="qty" style="width:25%;height: 100%"> 
                        <input type="hidden" name="" id="typeLT" class="formNG">
                        <input type="hidden" name="" id="satuanLT" class="formNG">
                    </div>
                  </div>
                  <div class="col">
                    <button class="btn btn-success" class="form-control" onclick="addLT(); return false;">( + )</button>
                  </div>
                  <div class="col-sm-12">
                  <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr style="background-color: #bee5eb">
                      <td><b>X</b></td>
                      <td><b>No</b></td>
                      <td><b>Nama</b></td>
                      <td><b>Kategori</b></td>
                      <td><b>Qty</b></td>
                      <td><b>Satuan</b></td>
                    </tr>
                    <tbody id="tableLT">
                    </tbody>
                  </table>
                </div>
                  <div class="form-group">
                    
                        <div class="input-group">
                          <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Total LT</span>
                         </div>
                         <input type="text" id="amountLT" readonly="" class="form-control"  aria-describedby="inputGroupPrepend2" value="0.00">
                         <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend2">Jam</span>
                         </div>
                        </div>
                        <input type="hidden" name="user[0][qty_lt]" id="qty_lt_minutes" value="0">
                        <input type="hidden" name="" id="amountIdle"  class="form-control" value="0" readonly>
                        <input type="text" name="" id="cal_dt"  class="form-control" value="0" readonly>
                    </div>
              </div>
                </div>
            </div>
          </div>
      
          <!-- end loss time -->
          <hr>
          <div class="card rounded card border-primary mb-3">
            <div class="card-body ">
             
              <div class="card-header bg-primary">
                <center> <b>CUTTING TOOL</b></center>
              </div>
              <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label><b>Cutting Tool</b></label>
                      <div class="input-group">
                        <input type="text" id="cutting_tools_code" class="autocompletecuttingtools form-control" placeholder="Input Cutting Tool Code">
                        <input type="hidden" id="cutting_tools_id">
                        <div class="input-group-append">
                          <button type="button" class="btn btn-primary" id="addCuttingToolBtn">Add</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="table-responsive">
                      <table class="table table-bordered" id="cuttingToolsTable">
                        <thead>
                          <tr><th>No</th><th>Code</th><th>Action</th></tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
            </div>
          </div>

          <!-- end save -->
          <hr>
          <div class="card rounded card border-success mb-3">
            <div class="card-body ">
              <div class="card-header bg-success">
                <center> <b>SAVE DATA</b></center>
              </div>
              <div class="row">
                  <div class="col">
                   <div class="form-group">
                        <label><b>Nett Produksi </b></label>
                          <input type="text" name="user[0][nett_prod]" id="nett_produksi" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col">
                   <div class="form-group">
                        <label><b>Gross Produksi</b></label>
                          <input type="text" name="user[0][gross_prod]" id="gross_produksi" class="form-control" readonly>
                    </div>
                  </div>
                </div>
              <div class="row">
                 <div class="col">
                    <div class="form-group">
                        <label><b>Runner</b></label>
                       <input type="text" name="runner" class="form-control">
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                        <label><b>Loss Purge</b></label>
                       <input type="text" name="user[0][loss_purge]" class="form-control">
                    </div>
                  </div>
                  
                  <div class="col">
                    <div class="form-group">
                        <label><b>Transaksi<font style="color: red">*</font></b></label>
                       <input type="hidden" name="" id="waktu" value="<?= date('his');  ?>">
                       <input type="text" name="id_production" id="id_production" class="form-control" readonly="">
                    </div>
                  </div>
              </div>
              <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label><b>Mtrl Lot No.</b></label>
                       <input type="text" name="user[0][lot_material_no]" class="form-control">
                       <input type="hidden" name="user[0][pic]" value="<?= $data['user_name']; ?>" > 
                    </div>
                  </div>
                </div>
               <div class="row justify-content-center">
                  <div class="col">
                    <div class="form-group d-flex justify-content-center">
                        <div id="save"><input type="submit" id="submit" name="" class="btn btn-primary" value="Save"><span id="loading" class="ml-2" style="vertical-align: middle;margin-top:35px"></span></div>
                    </div>
                  </div>
              </div>
             </div>
          </div>

          <!-- end save -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
        $(document).ready(function() {
            $('.delete').click(function() {
            return confirm("Are you sure you want to delete?");
            });
        });
        $(document).ready(function(){
            // Recalculate target_mc when NWT or OT changes
            $('#nwt, #ot_mp').on('input change', function() {
                setTarget();
            });
            
            var id_get = $('#id_bomS').val();
            $.ajax({
                      type    : "",
                      url     : "", 
                      data    : "id_bom=" + 1531,
                     success : function(data){
                          var url = "<?php echo base_url(); ?>" + "c_operator/showRelease/" + id_bom;
                    $('#release').load(url,'refresh');
                    }});
            $('.autocompleteBom').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    $('#id_bomS').val(ui.item.id_bom);
                    $('#ct_mc_aktual').html(ui.item.cyt_mc_bom + ' <br/> ' + ui.item.cyt_mp_bom);
                    $('#ct_mc').val(ui.item.cyt_mc_bom);
                    $('#ct_mp').val(ui.item.cyt_mp_bom);
                    // Store cycle time quote for target calculation
                    $('#ct_quo').val(ui.item.cyt_quo || ui.item.cyt_mc_bom); // Fallback to cyt_mc_bom if cyt_quo is not available
                    $('#id_pr').val(ui.item.id_pr); 
                    $('#customer').val(ui.item.customer);
                    $('#proses').val(ui.item.kode_proses);
                    if(ui.item.cavity_product == null)
                    {
                       $('#cavity').val(1); 
                       $('#cavity2').val(1); 
                    }
                    else
                    {
                       $('#cavity').val(ui.item.cavity_product); 
                       $('#cavity2').val(ui.item.cavity_product);
                    }
                   

                    var nwt = $('#nwt_mp').val();
                    var ot = $('#ot_mp').val();
                    var target = (((parseFloat(nwt) + parseInt(ot)) * 3600) / (parseInt(ui.item.cyt_mc_bom) + parseInt(ui.item.cyt_mp_bom)));
                    //var target = (nwt + ot)
                    $('#Target').val(parseInt(target));

                    // Calculate target_mc using cycle time quote when BOM is selected
                    setTarget();

                    var id_bom = ui.item.id_bom;
                    $('#tes').val(ui.item.hasil);


                    $.ajax({
                      type    : "POST",
                      url     : "<?php echo site_url('c_operator/getdatabomMesinDPR');?>",
                      data    : "id_bom=" + id_bom,
                      success : function(data){
                          $("#mesin").html(data); 
                    }});

                    $.ajax({
                      type    : "POST",
                      url     : "<?php echo site_url('c_operator/getdataRelease');?>", 
                      data    : "id_bom=" + id_bom,
                     success : function(data){
                          var url = "<?php echo base_url(); ?>" + "c_operator/showRelease/" + id_bom;
                    $('#release').load(url,'refresh');
                    }});
                }

            });



            $('.autocompletedefect').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocompleteDefect');?>",
                select: function (event, ui) {
                    $('#kategoriNG').val(ui.item.kategori);
                    $('#satuanNG').val(ui.item.satuan);
                    $('#typeNG').val(ui.item.type);
                    $('#namaNGOk').val(ui.item.label);

              }

          });


           $('.autocompletelosstime').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocompleteLosstime');?>",
                select: function (event, ui) {
                    $('#kategoriLT').val(ui.item.kategori);
                    $('#satuanLT').val(ui.item.satuan);
                    $('#typeLT').val(ui.item.type);
              }

          });

           $('.autocompletecuttingtools').autocomplete({
                source: "<?php echo site_url('c_dpr/get_autocomplete_cutting_tools'); ?>",
                minLength: 1,
                select: function (event, ui) {
                    if(ui.item && ui.item.id) {
                        $('#cutting_tools_id').val(ui.item.id);
                    } else {
                        $('#cutting_tools_id').val('');
                    }
                },
                change: function(event, ui) {
                    if(!ui.item) {
                        $('#cutting_tools_id').val('');
                        $('#cutting_tools_code').val('');
                    }
                }
            });

            var cuttingTools = [];
            var cuttingToolsCodes = [];
            function renderCuttingToolsTable() {
              var tbody = '';
              for (var i = 0; i < cuttingTools.length; i++) {
                tbody += '<tr>' +
                  '<td>'+(i+1)+'</td>'+
                  '<td>'+cuttingToolsCodes[i]+'</td>'+
                  '<td><button type="button" class="btn btn-danger btn-sm removeCuttingToolBtn" data-index="'+i+'">Remove</button></td>'+
                  '<input type="hidden" name="cutting_tools_id[]" value="'+cuttingTools[i]+'">'+
                  '</tr>';
              }
              $('#cuttingToolsTable tbody').html(tbody);
            }
            $('#addCuttingToolBtn').click(function() {
              var id = $('#cutting_tools_id').val();
              var code = $('#cutting_tools_code').val();
              if (id && code && cuttingTools.indexOf(id) === -1) {
                cuttingTools.push(id);
                cuttingToolsCodes.push(code);
                renderCuttingToolsTable();
                $('#cutting_tools_id').val('');
                $('#cutting_tools_code').val('');
              }
            });
            $('#cuttingToolsTable').on('click', '.removeCuttingToolBtn', function() {
              var idx = $(this).data('index');
              cuttingTools.splice(idx,1);
              cuttingToolsCodes.splice(idx,1);
              renderCuttingToolsTable();
            });

        });

    function setTarget(id)
    {
        var ct_quo = $('#ct_quo').val(); // Use cycle time quote instead of standard
        var cavity = $('#cavity').val();
        var nwt = $('#nwt').val();
        var ot = $('#ot_mp').val();
        let nwt_plus_ot = parseFloat(nwt) + parseInt(ot);
        // alert(nwt_plus_ot);
        if (ct_quo > 0 && cavity > 0 && nwt_plus_ot > 0) {
            var hasil = ((3600/ct_quo)*(cavity*nwt_plus_ot));
            $('#target_mc').val(hasil.toFixed(2));
        } else {
            $('#target_mc').val('');
        }
        $('#target_mp').val(0);
    }
    
    function lot(id) 
        {

         var tanggal         = $("#tanggal").val();
        var ambil_tahun     = tanggal.substr(2,2);
        var ambil_bulan     = tanggal.substr(8,2);
        var ambil_tanggal   = tanggal.substr(5,2);
        //alert(ambil_tanggal);
        var mesin           = $("#mesin").val();
        var sh              = $("#shift").val();
        var id_bomS         = $("#id_bomS").val();
            if(sh == '1'){ var shift = 'A';}
            else if(sh == '2'){ var shift = 'B';}
            else if(sh == '3'){ var shift = 'C';}
        var lotproduksi_save     =  ambil_tahun + ambil_tanggal + ambil_bulan + mesin + shift;
        $('#lotGlobalSave').val(lotproduksi_save);
        $('#lotGlobal').html('<b>'+lotproduksi_save+'</b>');
        var id_production = ambil_tahun + ambil_tanggal + ambil_bulan + $('#waktu').val() + id_bomS;
        $('#id_production').val(id_production);
        GrossNett();

         if($('#id_bomS').val() == '' && $('#mesin_save').val() == '')
                    {
                        $('#save').css("display","none");
                    }
                    else
                    {
                        $('#save').css("display","");
                    }
    for(var i = 0; i < 10 ; i++) 
    {
        let usageNya = $('.iniUsage'+i).val();
        $('.ubahQtyRelease'+i).val(usageNya * id);
        $('.ubahQtyReleaseText'+i).html('<b>' + usageNya * id + '</b>')
    }
            
    }


    // function GrossNett()
    // {
    //     var qty = parseFloat($('#qty').val());
    //     var ct_aktual = $('#ct_mc_aktual').val();
    //     var cavity = $('#cavity').val();
    //     var cavity2 = $('#cavity2').val();
    //     var defect = parseFloat($('#amountNG').val());
    //     var kalkulasi = qty + defect;
    //     var hasil_time = (kalkulasi / cavity)*(ct_aktual/3600);
    //     //var hasil_time = (kalkulasi / cavity * ct_aktual);
    //     var hasil = hasil_time.toFixed(1);
    //     $('#production_time').val(hasil);
    //     //$('#nett_produksi').val(hasil);
    //     var LT = $('#amountLT').val();
    //     var LT_new = $('#amountLT').val()/60;
    //     var calcDT = $('#amountIdle').val()/60;

    //     var nwt = $('#nwt').val();
    //     var ot = $('#ot_mp').val();
    //     var nwt_new = parseInt(nwt) + parseInt(ot);

    //     var calDT_new = nwt_new - hasil;
    //     var calDT_new_lagi = calDT_new - LT;
    //     //var cek = nwt - calDT_new;
    //     var nilaiGross = 3600*(nwt_new-calDT_new_lagi)/qty*cavity;
    //     var nilaiGross_2 = 3600*nwt_new/qty*cavity2;

    //     if(calDT_new_lagi < 0){
    //       $('#cal_dt').val(0);
    //     }
    //     else{
    //       $('#cal_dt').val(calDT_new_lagi.toFixed(1));
    //     }
    //     // $('#cal_dt').val(calDT_new_lagi.toFixed(1));

    //     if(nwt_new = 8){
    //       if(hasil > 8){
    //         $('#gross_produksi').val(nilaiGross_2.toFixed(2));
    //       } 
    //       else
    //       {
    //         $('#gross_produksi').val(nilaiGross.toFixed(2));
    //       }
    //     }
    //     else{
    //       if (hasil > 5) {
    //         $('#gross_produksi').val(nilaiGross_2.toFixed(2));
    //       }
    //       else
    //       {
    //         $('#gross_produksi').val(nilaiGross.toFixed(2));
    //       }
    //     }
    //     // $('#cal_dt').val(calDT_new_lagi.toFixed(1));
    //     // $('#gross_produksi').val(nilaiGross.toFixed(2));

    //     //Nett
    //     if(defect != 0){
		// 	var nilaiNett = nilaiGross;
		// }
		// else{
		// 	var nilaiNett = (hasil * 3600 / kalkulasi )* cavity2;
		// }
    //     // var nilaiNett = (hasil * 3600 / kalkulasi )* cavity2;
    //     $('#nett_produksi').val(nilaiNett.toFixed(2));
    //     //alert(calDT_new);
    // }

    function GrossNett()
    {
        var qty = parseFloat($('#qty').val());
        var ct_aktual = $('#ct_mc_aktual').val();
        var cavity = $('#cavity').val();
        var cavity2 = $('#cavity2').val();
        var defect = parseFloat($('#amountNG').val());
        var kalkulasi = qty + defect;
        var hasil_time = (kalkulasi / cavity)*(ct_aktual/3600);

        console.log("cavity1: " + cavity);
        console.log("cavity2: " + cavity2);
        //6000 / 11 = 545
        //17 / 3600 = 0.0047 
        //2.6



        //var hasil_time = (kalkulasi / cavity * ct_aktual);
        var hasil = hasil_time.toFixed(1);
        $('#production_time').val(hasil);
        //$('#nett_produksi').val(hasil);
        // LT input is in HOURS (user-friendly), calculations use hours directly
        var LT = parseFloat($('#amountLT').val()) || 0; // Loss Time in hours (user input)
        var LT_new = LT; // Already in hours
        var calcDT = $('#amountIdle').val()/60;

        var nwt = $('#nwt').val();
        var ot = $('#ot_mp').val();
        var nwt_new = parseFloat(nwt) + parseInt(ot);

        var calDT_new = nwt_new - hasil;
        var calDT_new_lagi = calDT_new - LT_new; // LT_new is in hours
        //var cek = nwt - calDT_new;
        var raw_nilaiGross = 3600*(nwt_new-calDT_new_lagi)/qty*cavity;
        var raw_nilaiGross_2 = 3600*nwt_new/qty*cavity2;
        var nilaiGross = Math.round(raw_nilaiGross);
        var nilaiGross_2 = Math.round(raw_nilaiGross_2);
        if(calDT_new_lagi < 0){
          $('#cal_dt').val(0);
        }
        else{
          $('#cal_dt').val(calDT_new_lagi.toFixed(1));
        }
        // $('#cal_dt').val(calDT_new_lagi.toFixed(1));

        if(nwt_new == 8){
          if(hasil > 8){
            $('#gross_produksi').val(nilaiGross_2.toFixed(2));
          } 
          else
          {
            $('#gross_produksi').val(nilaiGross.toFixed(2));
          }
        }
        else{
          if (hasil > 5) {
            $('#gross_produksi').val(nilaiGross_2.toFixed(2));
          }
          else
          {
            $('#gross_produksi').val(nilaiGross.toFixed(2));
          }
        }
        // $('#cal_dt').val(calDT_new_lagi.toFixed(1));
        // $('#gross_produksi').val(nilaiGross.toFixed(2));

        //Nett
        if(defect != 0){
			var nilaiNett = nilaiGross;
		}
		else{
			var nilaiNett = Math.round((hasil * 3600 / kalkulasi )* cavity2);
		}
        // var nilaiNett = (hasil * 3600 / kalkulasi )* cavity2;
        $('#nett_produksi').val(nilaiNett.toFixed(2));
        //alert(calDT_new);
        if(LT == 0 ){LT = 0}
        var WorkHour = (parseFloat(hasil) + parseFloat(LT)) - parseFloat(ot)
        var Overtime = ot
        var TotStopTime = LT
        var OK = qty
        var ProductCavity = cavity2
        let grossProduction = 0;
if ((WorkHour + Overtime - TotStopTime) !== 0) {
    grossProduction = 3600 / ((parseFloat(OK) / parseFloat(ProductCavity)) / (parseFloat(WorkHour) + parseFloat(Overtime)));
}

let NProd = 0;
if (WorkHour + Overtime - TotStopTime !== 0) {
    NProd = 3600 / ((parseFloat(OK) / ProductCavity) / (parseFloat(WorkHour) + parseFloat(Overtime) - parseFloat(TotStopTime)));
}

var nilaiGross = customRound(raw_nilaiGross);

$('#nett_produksi').val(customRound(NProd).toFixed(2));
$('#gross_produksi').val(customRound(grossProduction).toFixed(2));


        console.log("debug : "+ WorkHour, Overtime, TotStopTime, OK, ProductCavity);
        console.log("debug WorkHour: " + WorkHour);
        console.log("debug hasil: " + hasil);
        console.log("debug LT: " + LT);
        // (kalkulasi / cavity)*(ct_aktual/3600)
        console.log("debug Kalkulasi : "+kalkulasi)
        console.log("debug cavity : "+cavity)
        console.log("debug ct_aktual : "+ct_aktual)
        console.log("debug Overtime: " + Overtime);
        console.log("debug TotStopTime: " + TotStopTime);
        console.log("debug OK: " + OK);
        console.log("debug ProductCavity: " + ProductCavity);
        console.log("result gross: "+grossProduction)
        console.log("result nett: "+NProd)
    }

    function customRound(value) {
    var remainder = value % 1;
    if (remainder <= 0.5) {
        return Math.floor(value);
    } else {
        return Math.ceil(value);
    }
}

    $(document).ready(function () {
        $("#my-form").submit(function (e) {
            $("#submit").attr("disabled", true);
            $('#loading').html("Loading, please wait...");
            document.getElementById("loading").style.color = "red"; 
            return true;
        });
    });
</script>



<script type="text/javascript">
    function cekNoNamaBOM(){
      var id_bom = $("#id_bomS").val();
      if(id_bom == ''){
        alert('Silahkan pilih No & Nama Bom terlebih dahulu!');
        $('#barcode_sparepart_code').val('');
      }
      else
      {
      }
      
    }

    var save = -1; var sv = 0;
    function addNG(id)
    {
         save++; sv++;
         //var nama = { toString: function () { return $('#namaNG').val(); }}
         //var x = split($("#namaNG").val());
         var nama     = $("#namaNG").val();
         var kategori = $('#kategoriNG').val();
         var type     = $('#typeNG').val();
         var satuan   = $('#satuanNG').val();
         var qty      = $('#qtyNG').val();
         var markup = "<tr><td><input type='button' value='X'></td><td>"+sv+"</td>"+
         "<td><input type='hidden' name='detail["+save+"][nama]' value='"+nama+"'/>"+nama+"</td>"+
         "<td><input type='hidden' name='detail["+save+"][kategori]' value='"+kategori+"''>"+kategori+"</td>"+
         "<td><input type='hidden' name='detail["+save+"][qty]' value="+qty+" class='nilai'>"+qty+"</td>"+
         "<td><input type='hidden' name='detail["+save+"][satuan]' value="+satuan+">"+satuan+"<input type='hidden' name='detail["+save+"][type]' value="+type+"></td>"+
         "</tr>";
         if(kategori == ''){
			alert('Silahkan pilih nama defect/losstime terlebih dahulu!');
		}
		else
		{
			$("#tableNG").append(markup);
		}
         total();
         GrossNett(); 
         //alert(nama);
         $('.formNG').val('');
    }


    var saveLT = -1; var svLT = 0;
    function addLT(id)
    {
        saveLT++; svLT++;
        var nama     = $('#namaLT').val();
        var kategori = $('#kategoriLT').val();
        var type     = $('#typeLT').val();
        var satuan   = $('#satuanLT').val();
        var qty_hours = parseFloat($('#qtyLT').val()); // User inputs hours
        var qty      = qty_hours * 60; // Convert to minutes for storage
        var markup = "<tr><td><input type='button' value='X'></td><td>"+svLT+"</td>"+
        "<td><input type='hidden' name='detailLT["+saveLT+"][nama]' value='"+nama+"''>"+nama+"</td>"+
        "<td><input type='hidden' name='detailLT["+saveLT+"][kategori]' value='"+kategori+"'>"+kategori+"</td>"+
        "<td><input type='hidden' name='detailLT["+saveLT+"][qty]' value="+qty+" class='nilai'>"+qty_hours+"</td>"+
        "<td><input type='hidden' name='detailLT["+saveLT+"][satuan]' value="+satuan+">Jam<input type='hidden' name='detailLT["+saveLT+"][type]' value="+type+"></td>"+
        "</tr>";
        if(kategori == ''){
			alert('Silahkan pilih nama defect/losstime terlebih dahulu!');
		}
		else
		{
			$("#tableLT").append(markup);
		}
        
        totalLT();
        if(kategori == 'START/STOP')
        {
            totalStartStop(qty);
        }
        GrossNett(); 
        $('.formLT').val('');
    }



$(document).ready(function(){
    $('#tableNG').on('click', 'input[type="button"]', function(e){
   $(this).closest('tr').remove()
   total()
})
    });

    $(document).ready(function(){
    $('#tableLT').on('click', 'input[type="button"]', function(e){
   $(this).closest('tr').remove()
   totalLT()
})
});

    $(document).ready(function(){
                total();  
                totalLT(); 
                       
            });

            function total()
            {
                var sum = 0;
                $('#tableNG > tr').each(function() {
                      var nilai  = $('.nilai').val()
                    var price = parseFloat($(this).find('.nilai').val());
                    sum += price;
                    //$(this).find('.amountNG').val(''+amount);
                    $('#amountNG').val(parseFloat(sum));

                });
                //GrossNett()
                
            }

            function totalLT()
            {
                var sumMinutes = 0;
                $('#tableLT > tr').each(function() {
                    var price = parseFloat($(this).find('.nilai').val());
                    if (!isNaN(price) && price > 0) {
                        sumMinutes += price;
                    }
                });

                var sumHours = sumMinutes / 60;
                $('#amountLT').val(isFinite(sumHours) ? sumHours.toFixed(2) : '0.00');
                $('#qty_lt_minutes').val(isFinite(sumMinutes) ? sumMinutes : 0);
            }

            function totalStartStop(qty)
            {
                var sum2 = parseFloat($('#amountIdle').val());
                      sum2 += parseFloat(qty);
                      $('#amountIdle').val(sum2);

            }
            $('#text_subtotal').text()
    </script>
    </form>

  </body>
</html>
