<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
	  <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
	  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">

	  <script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 
		<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
		<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>


	  <title>DPR Online</title>
<?= form_open('c_operator/add'); ?>  
<body>
	<div class="container-fluid">
		<div class="align-self-center mt-5">
			<div class="row justify-content-center ">
			  <div class="col-sm-6">
			    <div class="card rounded card border-primary mb-3">
			      <div class="card-body ">
			      	<div class="card-header bg-primary">
						    <center> <b>PRODUCTION ( <?= $divisi; ?> )</b></center>
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
											  <input type="text" name="user[0][nwt_mp]" id="nwt_mp" class="form-control" value="7" required="">
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
											  <input type="text" name="user[0][operator]" class="form-control" value="<?= $nama; ?>" required="" readonly>
										</div>
							  	</div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Kanit <font style="color: red">*</font></b></label>
											   <select name="user[0][kanit]" style="width:95%;height: 100%" class="form-control" id="kanit" required=""  >
								          <option value="">-Choose-</option>
								          <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
								          </select>
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>No. & Nama BOM <font style="color: red">*</font></b></label>
											  <input type="search form-control" name="" id="barcode_sparepart_code" class="autocompleteBom" style="width: 100%;height: 100%" required="" id="id_bom">
		 										<input type="hidden" name="" id="tes">
		 										<input type="hidden" name="user[0][id_bom]" id="id_bomS" readonly="" class="form-control" style="height: 100%">
										</div>
							  	</div>
							  </div>
							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Mesin / Tonnase <font style="color: red">*</font></b></label>
											   <select name="user[0][mesin]" id="mesin" style="width:100px;height: 100%" required x-moz-errormessage="Not Empty" class="form-control" onchange="lot(this.value)">
							           
							          </select>
										</div>
							  	</div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Lot Global</b></label>
											   <p id="lotGlobal"></p>
													<input type="hidden" name="lotGlobalSave" id="lotGlobalSave" class="form-control">
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>CT MC</b></label>
											   <input type="text" name="user[0][ct_mc]" id="ct_mc" readonly="" class="form-control">
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
											   <input type="text" name="user[0][ct_mc_aktual]" class="form-control" aria-describedby="inputGroupPrepend2" >
											   <div class="input-group-prepend">
								          <span class="input-group-text" id="inputGroupPrepend2">Sec</span>
								        </div>
										</div>
							  	</div>
							    <div class="col">
							     <div class="input-group">
											   <input type="text" name="user[0][ct_mp_aktual]" class="form-control" aria-describedby="inputGroupPrepend2" >
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
													<input type="text" name="user[0][target]" id="Target" class="form-control" readonly="">
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Qty OK <font style="color: red">*</font></b></label>
											   <div class="input-group">
											   <input type="text" name="user[0][qty_ok]" required="" class="form-control" onkeyup="lot(this.value)" aria-describedby="inputGroupPrepend2" >
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
			 <!-- START DEFECT -->
			      <div class="card rounded card border-success mb-3">
			      <div class="card-body ">
			      	<div class="card-header bg-success">
						    <center> <b>RELEASE</b></center>
						  </div>
						  <div class="row">
						  	<div class="col">
						  		<div id="release"></div>
						  	<div class="col">
						  </div>
					  </div>
					  </div>
					</div>
				</div>
			<!-- START DEFECT -->
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
							  <div class="col">
										<div class="form-group">
											  <label><b>Qty</label>
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
							    	<div class="form-group">
							    		<br/>
							    	<button class="btn btn-success" class="form-control" onclick="addNG(); return false;">( + )</button>
							    	</div>
							    </div>
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
							  	<div class="col-sm-3">
							  		<div class="form-group">
											  <label><b>Qty NG Total</b></label>
											   <input type="text" name="user[0][qty_ng]" id="amountNG" class="form-control " readonly="">
										</div>
							  	</div>
							  </div>
							</div>
						</div>

					<!-- END DEFECT -->

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
							  <div class="col">
							     <div class="form-group">
												<label><b>Qty</b></label>
												<div class="input-group">
											   <input type="number" name="" id="qtyLT" class="form-control formLT"  aria-describedby="inputGroupPrepend2" >
											   <div class="input-group-prepend">
								          <span class="input-group-text" id="inputGroupPrepend2">Menit</span>
								         </div>
												</div>
												<input type="hidden" name="" id="kategoriLT" class="form-control formLT" placeholder="qty" style="width:25%;height: 100%"> 
												<input type="hidden" name="" id="typeLT" class="formNG">
												<input type="hidden" name="" id="satuanLT" class="formNG">
										</div>
							    </div>
							    <div class="col">
							    	<div class="form-group">
							    		<br/>
							    	<button class="btn btn-success" class="form-control" onclick="addLT(); return false;">( + )</button>
							    	</div>
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
							  	<div class="col-sm-3">
							  		<div class="form-group">
											  <label><b>Qty LT Total</b></label>
											   <input type="text" name="user[0][qty_lt]" class="form-control" id="amountLT" readonly="">
										</div>
							  	</div>
							</div>
							  </div>
						</div>
					</div>
					<!-- end loss time -->
					<div class="card rounded card border-primary mb-3">
			      <div class="card-body ">
			      	<div class="card-header bg-primary">
						    <center> <b>SAVE DATA</b></center>
						  </div>
						  <div class="row">
						  	 <div class="col">
							  		<div class="form-group">
											  <label><b>Runner<font style="color: red">*</font></b></label>
											 <input type="number" name="user[0][runner]" class="form-control">
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
											  <label><b>Mtrl Lot No.<font style="color: red">*</font></b></label>
											 <input type="text" name="user[0][lot_material_no]" class="form-control">
											 <input type="hidden" name="user[0][pic]" value="<?= $nama; ?>" > 
										</div>
							  	</div>
							  </div>
							 <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <div id="save"><input type="submit" name="" class="btn btn-primary" value="Save"></div>
										</div>
							  	</div>
							</div>
						 </div>
					</div>

					<!-- end save -->
			    </div>
			  </div>
			</div>


<style type="text/css">
body {
	 width: 100%;
     min-height: 200px;
     font-family: 'Tahoma';
     background: #d5f0f3;
     background-image: url('../assets/MCH.jpg');
     background-repeat: no-repeat, repeat;
     background-size: cover;
	 }
	 
.nounderline {
  text-decoration: none !important
}

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px) 


</style>

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




		});
	
	function lot(id) 
		{

		 var tanggal         = $("#tanggal").val();
		var ambil_tahun     = tanggal.substr(2,2);
        var ambil_bulan     = tanggal.substr(8,2);
        var ambil_tanggal   = tanggal.substr(5,2);
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
</script>



<style type="text/css">
body {
	width: 100%;
   min-height: 200px;
  font-family: 'Tahoma';

  background: #d5f0f3;

}

table, td, th {
  border: 1px solid black;
  /*padding:5px;*/

}

table {
  /*width: 80%;*/
  border-collapse: collapse;
  width: 100%;
  /*min-height: 200px;*/
}
td
{
	/*font-weight: bold;*/
	font-size: 12px;
}


@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* Force table to not be like tables anymore */
	table,  td, tr { 
		/*display: block; */
	}
	
	/* Hide table headers (but not display: none;, for accessibility) */
	/*thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}*/
	
	tr { border: 1px solid #ccc; }
	
	td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding : 1%;
		/*padding-left: 50%; */
	}
	
	td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
	}


</style>

<script type="text/javascript">

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
		$("#tableNG").append(markup);
		//total();
		//alert(nama);
		$('.formNG').val('');

	// 	var o = {
 //  toString: function () { return nama; },
 //  valueOf:  function () { return nama; }
	// };

	// alert("<input type='text' name='detail["+save+"][nama]' value="+String(o)+">"); // "foo"
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
		$("#tableLT").append(markup);
		//totalLT();
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
                
            }

            function totalLT()
            {
                var sum = 0;
                $('#tableLT > tr').each(function() {
                	  var nilai  = $('.nilai').val()
                    var price = parseFloat($(this).find('.nilai').val());
                    sum += price;
                    //$(this).find('.amountNG').val(''+amount);
                    $('#amountLT').val(parseFloat(sum));
                });
                
            } 

            $('#text_subtotal').text()
</script>
			<?= form_close(); ?>
  </body>
</html>

