<title>DPR | Edit DPR Online</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
<link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">
<script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 
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
<?php foreach($data_production->result() as $data) { ?> 
<?php } ?>

<body>
	<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox ">
          <div class="ibox-title">
            <h5>Edit DPR Online</h5>
          	<div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
    			<div class="ibox-content">
    				<div class="container-fluid">
    	<form action="<?php echo site_url('c_new/edit_spp_online')?>" method="post" id="my-form">
        <div class="align-self-center">
          
              <!-- <div class="col-sm-6"> -->
                 <div class="card rounded card border-primary mb-3">
                  <div class="card-body ">
                    <div class="card-header bg-primary">
                            <center> <b>PRODUCTION ( <?= $divisi; ?> ) </b></center>
                          </div>
                  <!-- START ROW -->
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label><b>Tanggal <font style="color: red">*</font></b></label>
                                    <input type="date" name="tanggal" class="form-control" required="" id="tanggal" onchange="lot(this.value)" value="<?= $data->tanggal; ?>">
                            </div>
                        </div>
                        
                    </div>
                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Shift <font style="color: red">*</font></b></label>
                                              <select name="shift" class="form-control" required="" id="shift" onchange="lot(this.value)" value="<?= $data->shift; ?>">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    </select>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>NWT <font style="color: red">*</font></b></label>
                                              <input type="text" name="nwt_mp" id="nwt" class="form-control"  required="" value="<?= $data->nwt_mp; ?>">
                                        </div>
                                </div>
                                <div class="col">
                                  <div class="form-group">
                                                <label><b>OT</b></label>
                                              <input type="text" name="ot_mp" id="ot_mp" class="form-control" value="<?= $data->nwt_mp; ?>">
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Operator <font style="color: red">*</font></b></label>
                                              <input type="text" name="operator" class="form-control" value="<?= $data->operator; ?>" required="" readonly>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Kanit <font style="color: red">*</font></b></label>
                                               <select name="kanit" style="width:100%;" class="form-control" id="kanit" required=""  >
                                          <option value="">-Choose-</option>
                                          <option <?php if( $data->kanit =='ABDUL BASIR B W'){echo "selected"; } ?> value='ABDUL BASIR B W'>ABDUL BASIR B W</option>
                          <option <?php if( $data->kanit =='AGUS MARTANTO'){echo "selected"; } ?> value='AGUS MARTANTO'>AGUS MARTANTO</option>
                          <option <?php if( $data->kanit =='AGUS SALIM'){echo "selected"; } ?> value='AGUS SALIM'>AGUS SALIM</option>
                          <option <?php if( $data->kanit =='ALBERTO HUTABARAT'){echo "selected"; } ?> value='ALBERTO HUTABARAT'>ALBERTO HUTABARAT</option>
                          <option <?php if( $data->kanit =='ASROFI'){echo "selected"; } ?> value='ASROFI'>ASROFI</option>
                          <option <?php if( $data->kanit =='MARIDIN'){echo "selected"; } ?> value='MARIDIN'>MARIDIN</option>
                          <option <?php if( $data->kanit =='MITUHU'){echo "selected"; } ?> value='MITUHU'>MITUHU</option>
                          <option <?php if( $data->kanit =='RIDWAN EFENDI'){echo "selected"; } ?> value='RIDWAN EFENDI'>RIDWAN EFENDI</option>
                          <option <?php if( $data->kanit =='SUKIRMAN'){echo "selected"; } ?> value='SUKIRMAN'>SUKIRMAN</option>
                                          <!--<?php// foreach ($kanit as $b) {
                                            //if($b[nama_operator] == $data->kanit)
                                            //  {
                                            //      echo "selected; value='$b[nama_operator]'";
                                            //}
                                            //else{
                                            //  echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";
                                            //} 
                                            }?>
                                         -->
                                          </select>
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                              <label><b>No. & Nama BOM <font style="color: red">*</font></b></label>
                                              <input type="search form-control" name="" id="barcode_sparepart_code" class="autocompleteBom" style="width: 100%;" required="" id="id_bom" value="<?= $data->nama_bom; ?>">
                                                <input type="hidden" name="" id="tes">
                                                <input type="hidden" name="id_bom" id="id_bomS" readonly="" class="form-control" value="<?= $data->id_bom;?>">
                                                <input type="hidden" name="id_prod"  readonly="" class="form-control" value="<?= $data->id_production;?>">
                                        </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                              <label><b>Mesin <font style="color: red">*</font></b></label>
<!--                                               <select name="mesin" id="mesin" style="width:100px;height: 100%" required x-moz-errormessage="Not Empty" class="form-control" onchange="lot(this.value)">
                                      </select> -->
                                      <input type="text" name="mesin" id="mesin" required x-moz-errormessage="Not Empty" class="form-control" onkeyup="lot(this.value)" value="<?= $data->mesin; ?>">
                                        </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Production Time </b></label>
                                      <input type="text" name="production_time" id="production_time" class="form-control" value="<?= $data->production_time; ?>" readonly>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Cavity</b></label>
                                               <input type="text" name="cavity" id="cavity" class="form-control" value="<?= $data->cavity; ?>">
                                       
                                      </select>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Tooling</b></label>
                                      <input type="text" name="tooling" x-moz-errormessage="Not Empty" class="form-control" value="<?= $data->tooling; ?>">
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Lot Global</b></label>
                                               <p id="lotGlobal"><?= $data->lot_global; ?></p>
                                                    <input type="hidden" name="lot_global" id="lotGlobalSave" class="form-control" value="<?= $data->lot_global; ?>">
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>CT MC</b></label>
                                               <input type="text" name="ct_mc" id="ct_mc" readonly="" class="form-control" value="<?= $data->ct_mc; ?>">
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>CT MP</b></label> 
                                               <input type="text" name="ct_mp" id="ct_mp" readonly="" class="form-control" value="<?= $data->ct_mp; ?>">
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                        <div class="input-group">
                                               <input type="text" name="ct_mc_aktual" id="ct_mc_aktual" class="form-control" aria-describedby="inputGroupPrepend2" onkeyup="setTarget(this.value)" value="<?= $data->ct_mc_aktual; ?>">
                                               <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Sec</span>
                                        </div>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="input-group">
                                               <input type="text" name="ct_mp_aktual" id="ct_mp_aktual" class="form-control" aria-describedby="inputGroupPrepend2" value="<?= $data->ct_mp_aktual; ?>" >
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
                                                    <input type="text" name="target_mc" id="target_mc" class="form-control" readonly="" value="<?= $data->target_mc; ?>">
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Target MP</b></label>
                                                    <input type="text" name="target" id="target_mp" class="form-control" readonly="" value="<?= $data->target; ?>">
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Nett Produksi </b></label>
                                                    <input type="text" name="nett_prod" id="nett_produksi" class="form-control" value="<?= $data->nett_prod; ?>" readonly>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Gross Produksi</b></label>
                                                    <input type="text" name="gross_prod" id="gross_produksi" class="form-control" value="<?= $data->gross_prod; ?>" readonly>
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Qty OK <font style="color: red">*</font></b></label>
                                               <div class="input-group">
                                               <input type="text" name="qty_ok" required="" class="form-control" onkeyup="lot(this.value)" aria-describedby="inputGroupPrepend2" id="qty" value="<?= $data->qty_ok; ?>" >
                                               <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Pcs</span>
                                         </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Keterangan</b></label>
                                               <input type="text" name="keterangan" class="form-control" value="<?= $data->keterangan; ?>">
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
                                    <?php
                                    if ($data_productionRelease->result() != null || 0) { ?>
                                        <table class="table table-bordered">
                                          <tr>
                                            <th>No</th>
                                            <th>Kode Product</th>
                                            <th>Nama Product</th>
                                            <th>Proses</th>
                                            <th>Usage</th>
                                            <th>Qty</th>
                                          </tr>
                                          <?php $i = -1; $no = 0; foreach($data_productionRelease->result() as $ress) {  $no++; $i++?>
                                          <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $ress->kp_mr; ?></td>
                                            <td><?= $ress->np_mr; ?></td>
                                            <td><?= $ress->npr_mr; ?></td>
                                            <td><?= $ress->usage_mr; ?></td>
                                            <td><?= $ress->qty; ?></td>
                                          </tr>
                                          <?php } ?>
                                        </table>
                                        
                                    <?php  } else { ?>
                                        <div id="release">
                                          </div>
                                            <?php  }
                                    ?>
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
                                <!-- <div class="col">
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
                                </div> -->
                            
                                    <div class="col-sm-12">
                                <div class="table-responsive">
                                <table class="table table-bordered" >
                                    <thread>
                                        <tr style="background-color: #bee5eb">
                                            <td><b>No</b></td>
                                            <td><b>Nama</b></td>
                                            <td><b>Kategori</b></td>
                                            <td><b>Qty</b></td>
                                            <td><b>Satuan</b></td>
                                        </tr>
                                      </thread>
                                        <tbody id="tableNG">
                                            <?php $i = -1; $no = 0; foreach($data_productionNG->result() as $ng) {  $no++; $i++?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $ng->nama; ?></td>
                                                    <td><?= $ng->kategori; ?></td>
                                                    <td><?= $ng->qty; ?></td>
                                                    <td><?= $ng->satuan; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Total NG</span>
                                         </div>
                                               <input type="text" name="qty_ng" id="amountNG" class="form-control" aria-describedby="inputGroupPrepend2" readonly="" value="<?= $data->qty_ng; ?>" >
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
                                <div class="col-sm-12">
                                <div class="table-responsive">
                                <table class="table table-bordered">
                                        <tr style="background-color: #bee5eb">
                                            <td><b>No</b></td>
                                            <td><b>Nama</b></td>
                                            <td><b>Kategori</b></td>
                                            <td><b>Qty</b></td>
                                            <td><b>Satuan</b></td>
                                        </tr>
                                        <tbody id="tableLT">
                                            <?php $i = -1; $no = 0; foreach($data_productionLT->result() as $ng) {  $no++; $i++?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $ng->nama; ?></td>
                                                    <td><?= $ng->kategori; ?></td>
                                                    <td><?= $ng->qty; ?></td>
                                                    <td><?= $ng->satuan; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                        
                                              <div class="input-group">
                                                <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Total LT</span>
                                         </div>
                                               <input type="text" name="qty_lt" id="amountLT" readonly="" class="form-control"  aria-describedby="inputGroupPrepend2" value="<?= $data->qty_lt; ?>">
                                               <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Menit</span>
                                         </div>
                                                </div>
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
                            <center> <b>SAVE DATA</b></center>
                          </div>
                          <div class="row">
                             <div class="col">
                                    <div class="form-group">
                                              <label><b>Runner</b></label>
                                             <input type="text" name="runner" class="form-control" value="<?= $data->runner; ?>">
                                        </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Loss Purge</b></label>
                                             <input type="text" name="loss_purge" class="form-control" value="<?= $data->loss_purge; ?>">
                                        </div>
                                </div>
                                
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Transaksi<font style="color: red">*</font></b></label>
                                             <input type="hidden" name="" id="waktu" value="<?= date('his');  ?>">
                                             <input type="text" name="id_production" id="id_production" class="form-control" readonly="" value="<?= $data->id_production; ?>">
                                        </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Mtrl Lot No.</b></label>
                                             <input type="text" name="lot_material_no" class="form-control" value="<?= $data->lot_material_no; ?>">
                                             <input type="hidden" name="pic" value="<?= $nama; ?>" > 
                                        </div>
                                </div>
                              </div>
                             <div class="row justify-content-center">
                                <div class="col">
                                    <div class="form-group d-flex justify-content-center">
                                              <div id="save"><input type="submit" id="submit" name="" class="btn btn-primary" value="Edit"><span id="loading" class="ml-2" style="vertical-align: middle;margin-top:35px"></span></div>
                                        </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- end save -->
                </div>
              </div>
              </form>

    			</div>
    		</div>
    	</div>
  
    
<script type="text/javascript">
        $(document).ready(function(){

            $('.autocompleteBom').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    $('#id_bomS').val(ui.item.id_bom);
                    $('#ct_mc_aktual').html(ui.item.cyt_mc_bom + ' <br/> ' + ui.item.cyt_mp_bom);
                    $('#ct_mc').val(ui.item.cyt_mc_bom);
                    $('#ct_mp').val(ui.item.cyt_mp_bom);
                    $('#id_pr').val(ui.item.id_pr); 
                    $('#cavity').val(ui.item.cavity_product); 

                    var nwt = $('#nwt_mp').val();
                    var ot = $('#ot_mp').val();
                    var target = (((parseInt(nwt) + parseInt(ot)) * 3600) / (parseInt(ui.item.cyt_mc_bom) + parseInt(ui.item.cyt_mp_bom)));
                    //var target = (nwt + ot)
                    $('#Target').val(parseInt(target));

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
                          var url = "<?php echo base_url(); ?>" + "c_operator/showRelease_edit/" + id_bom;
                    $('#release').load(url,'refresh');
                    }});

                    
                    
                }

            });
        });

    function setTarget(id)
    {
        var ct_aktual = $('#ct_mc_aktual').val();
        var cavity = $('#cavity').val();
        var nwt = $('#nwt').val();
        var hasil = ((3600/ct_aktual)*(cavity*nwt));
        $('#target_mc').val(hasil);
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
        //var lotproduksi_save     =  ambil_tahun + ambil_tanggal + ambil_bulan + mesin + shift;
        //$('#lotGlobalSave').val(lotproduksi_save);
        //$('#lotGlobal').html('<b>'+lotproduksi_save+'</b>');
        //var id_production = ambil_tahun + ambil_tanggal + ambil_bulan + $('#waktu').val() + id_bomS;
        //$('#id_production').val(id_production);
        var qty = $('#qty').val();
    var ct_aktual = $('#ct_mc_aktual').val();
    var cavity = $('#cavity').val();
    var hasil_time = (qty / cavity)*ct_aktual/3600;
    var hasil = hasil_time.toFixed(1);
    $('#production_time').val(hasil);
    //$('#nett_produksi').val(hasil);
    var LT = $('#amountLT').val();
    var nwt = $('#nwt').val();
    var nilaiGross = 3600*nwt/qty*cavity;
    $('#gross_produksi').val(nilaiGross.toFixed(2));
    var nilaiNett = hasil * 3600 / qty * cavity;
    $('#nett_produksi').val(nilaiNett.toFixed(2));

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

    $(document).ready(function () {
        $("#my-form").submit(function (e) {
            $("#submit").attr("disabled", true);
            $('#loading').html("Loading, please wait...");
            document.getElementById("loading").style.color = "red"; 
            return true;
        });
    });
</script>


            
  </body>
</html>