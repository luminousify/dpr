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
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">

	  <script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 
		<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
		<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>


	  <title>DPR Online</title>

<form action="<?php echo site_url('c_operator/add')?>" method="post" id="my-form">
<body>
	<nav class="navbar fixed-top navbar-expand-lg navbar-light p-3" style="background-color:#5bc0de">
        <div class="container">
            <a class="navbar-brand text-white" href="<?php echo base_url('login_op/input_dpr') ?>" style="font-size:20px;font-weight:600">DPR ONLINE</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size:14px;font-weight:600">
			        <ul class="navbar-nav ml-auto" style="font-size:14px;font-weight:600">
			          <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Logged in as :
                          <i class="bi bi-person-fill ml-2 mr-1"></i> <?php echo $this->session->userdata('nama_operator') ?>
                        </a>
                        <div class="dropdown-menu mt-3" aria-labelledby="navbarDropdown2">
                          <a class="dropdown-item" href="<?php echo base_url('login_op/change_password') ?>">Change Password</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="<?php echo base_url('login_op/logout') ?>">Logout</a>
                        </div>
                      </li>
			        </ul>
			      </div>
        </div>           
    </nav>
    <br>
    <br>
	<div class="container-fluid mt-4">
		<div class="align-self-center mt-5">
			<div class="row justify-content-center ">
			  <!-- <div class="col-sm-6"> -->
			    <div class="card rounded card border mb-3">
			      <div class="card-body ">
			     <div class="card rounded card border-primary mb-3">
			      <div class="card-body ">
			      	<?php if ($this->session->flashdata('gagal')): ?>
               	<div class="alert alert-danger" style="text-align: center;" role="alert">
                  <?php echo $this->session->flashdata('gagal'); ?>
                </div>
              <?php endif; ?>
			      	<div class="card-header bg-primary">
						    <center> <b>PRODUCTION ( <?= $divisi; ?> ) </b></center>
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
    <input 
        type="number" 
        name="user[0][nwt_mp]" 
        id="nwt" 
        class="form-control" 
        required 
        oninput="lot(this.value)" 
    />
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
												<label><b> Kanit <font style="color: red">*</font></b></label>
											   <select name="user[0][kanit]" style="width:95%;height: 100%" class="form-control" id="kanit" required=""  >
								          <option value="<?= $nama_kanit?>"><?= $nama_kanit ?></option>
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
											  <input type="search form-control" name="" id="barcode_sparepart_code" class="autocompleteBom" style="width: 100%;height: 100%" required="" id="id_bom" onchange="cekNoNamaBOM()">
		 										<input type="hidden" name="" id="tes">
		 										<input type="hidden" name="user[0][id_bom]" id="id_bomS" readonly="" class="form-control" style="height: 100%">
										</div>
							  	</div>
							  	<div class="col-sm-2">
							  		<div class="form-group">
											  <label><b>Mesin <font style="color: red">*</font></b></label>
<!-- 											   <select name="user[0][mesin]" id="mesin" style="width:100px;height: 100%" required x-moz-errormessage="Not Empty" class="form-control" onchange="lot(this.value)">
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
											   <input type="text" name="user[0][cavity]" id="cavity" class="form-control">
											   <input type="hidden" name="user[0][cavity2]" id="cavity2" class="form-control" >
							           
							          </select>
										</div>
							  	</div>
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Tooling</b></label>
							          <input type="text" name="user[0][tooling]" style="width:100px;height: 100%" x-moz-errormessage="Not Empty" class="form-control">
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
											   <input type="text" name="user[0][ct_mc_aktual]" id="ct_mc_aktual" class="form-control" aria-describedby="inputGroupPrepend2" onkeyup="setTarget(this.value)" >
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
											   <input type="number" name="" id="qtyLT" class="form-control formLT"  aria-describedby="inputGroupPrepend2" onkeypress="convertJam()">
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
											   <input type="text" name="user[0][qty_lt]" id="amountLT" readonly="" class="form-control"  aria-describedby="inputGroupPrepend2" >
											   <div class="input-group-prepend">
								          <span class="input-group-text" id="inputGroupPrepend2">Jam</span>
								         </div>
												</div>
												<input type="hidden" name="" id="amountIdle"  class="form-control" value="0" readonly>
												<input type="hidden" name="" id="cal_dt"  class="form-control" value="0" readonly>
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
					<!-- END CUTTING TOOL SECTION -->
					<hr>
					<div class="card rounded card border-success mb-3">
			      <div class="card-body ">
			      	<div class="card-header bg-success">
						    <center> <b>SAVE DATA</b></center>
						  </div>
						  <div class="row">
							  	<div class="col">
							     <div class="form-group">
												<label><b>Nett Produksi <font style="color: red">*</font> </b></label>
													<input type="text" name="user[0][nett_prod]" id="nett_produksi" class="form-control" readonly>
										</div>
							    </div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Gross Produksi <font style="color: red">*</font></b></label>
													<input type="text" name="user[0][gross_prod]" id="gross_produksi" class="form-control" readonly>
										</div>
							    </div>
							  </div>
						  <div class="row">
						  	 <div class="col">
							  		<div class="form-group">
											  <label><b>Runner <font style="color: red">*</font></b></label>
											 <input type="text" name="runner" class="form-control" required="">
										</div>
							  	</div>

							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Lost Purge + Setting</b></label>
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
											 <input type="hidden" name="user[0][pic]" value="<?= $nama; ?>" > 
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
		


<script type="text/javascript">
		$(document).ready(function(){
			document.getElementById("tanggal").valueAsDate = new Date();
			
			// Set date restrictions
			var today = new Date();
			var maxDate = today.toISOString().split('T')[0];
			
			// Calculate 2 days before
			var minDate = new Date();
			minDate.setDate(minDate.getDate() - 2);
			var minDateString = minDate.toISOString().split('T')[0];
			
			// Apply restrictions to date input
			document.getElementById("tanggal").setAttribute('max', maxDate);
			document.getElementById("tanggal").setAttribute('min', minDateString);

		    $('.autocompleteBom').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    $('#id_bomS').val(ui.item.id_bom);
                    $('#ct_mc_aktual').html(ui.item.cyt_mc_bom + ' <br/> ' + ui.item.cyt_mp_bom);
                    $('#ct_mc').val(ui.item.cyt_mc_bom);
                    $('#ct_mp').val(ui.item.cyt_mp_bom);
                    $('#customer').val(ui.item.customer);
                    $('#proses').val(ui.item.kode_proses);
                    $('#id_pr').val(ui.item.id_pr); 
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
                minLength: 0,  // Allow empty search to show all options
                select: function (event, ui) {
                    $('#kategoriNG').val(ui.item.kategori);
                    $('#satuanNG').val(ui.item.satuan);
                    $('#typeNG').val(ui.item.type);
                    $('#namaNGOk').val(ui.item.label);
                    $('#namaNG').val(ui.item.label);
                    return false;
                }
            }).on('focus', function() {
                // Trigger search on focus
                $(this).autocomplete('search', '');
            }).on('input', function(e) {
                // Prevent manual input
                e.preventDefault();
                return false;
            }).attr('readonly', 'readonly')  // Make input readonly but still selectable
              .css('background-color', '#FFFFFF'); // Keep white background despite readonly


	       $('.autocompletelosstime').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocompleteLosstime');?>",
                minLength: 0,  // Allow empty search to show all options
                select: function (event, ui) {
                    $('#kategoriLT').val(ui.item.kategori);
                    $('#satuanLT').val(ui.item.satuan);
                    $('#typeLT').val(ui.item.type);
                    $('#namaLT').val(ui.item.label);
                    return false;
                }
            }).on('focus', function() {
                // Trigger search on focus
                $(this).autocomplete('search', '');
            }).on('input', function(e) {
                // Prevent manual input
                e.preventDefault();
                return false;
            }).attr('readonly', 'readonly')  // Make input readonly but still selectable
              .css('background-color', '#FFFFFF'); // Keep white background despite readonly

	 

		});

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
	function setTarget(id)
	{
		var ct_aktual = $('#ct_mc_aktual').val();
		var cavity = $('#cavity').val();
		var nwt = $('#nwt').val();
		var ot = $('#ot_mp').val();
		let nwt_plus_ot = parseInt(nwt) + parseInt(ot);
		// alert(nwt_plus_ot);

		var hasil = ((3600/ct_aktual)*(cavity*nwt_plus_ot));
		$('#target_mc').val(hasil.toFixed(2));
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
 	// 	var qty = parseFloat($('#qty').val());
	// 	var ct_aktual = $('#ct_mc_aktual').val();
	// 	var cavity = $('#cavity').val();
	// 	var cavity2 = $('#cavity2').val();
	// 	var defect = parseFloat($('#amountNG').val());
	// 	//Qty OK + NG
	// 	var kalkulasi = qty + defect;
	// 	//Production Time
	// 	var hasil_time = (kalkulasi / cavity)*(ct_aktual/3600);
	// 	//var hasil_time = (kalkulasi / cavity * ct_aktual);
	// 	var hasil = hasil_time.toFixed(1);
	// 	$('#production_time').val(hasil);
	// 	//$('#nett_produksi').val(hasil);
	// 	var LT = $('#amountLT').val();
	// 	var LT_new = $('#amountLT').val()/60;
	// 	var calcDT = $('#amountIdle').val()/60;
		
	// 	var nwt = $('#nwt').val();
	// 	var ot = $('#ot_mp').val();
	// 	var nwt_new = parseInt(nwt) + parseInt(ot);
	// 	// alert(nwt_new);

	// 	//CDT
	// 	var calDT_new = nwt_new - hasil;
	// 	var calDT_new_lagi = calDT_new - LT;
	// 	//var cek = nwt - calDT_new;

	// 	//Gross jika Prod Time <= 8
	// 	var nilaiGross = 3600*(nwt_new-calDT_new_lagi)/qty*cavity2;
	// 	//Gross jika Prod Time > 8
	// 	var nilaiGross_2 = 3600*nwt_new/qty*cavity2;
	// 	if(calDT_new_lagi < 0){
	// 		$('#cal_dt').val(0);
	// 	}
	// 	else{
	// 		$('#cal_dt').val(calDT_new_lagi.toFixed(1));
	// 	}
	// 	// $('#cal_dt').val(calDT_new_lagi.toFixed(1));

	// 	if(nwt_new = 8){
	// 		if(hasil > 8){
	// 			$('#gross_produksi').val(nilaiGross_2.toFixed(2));
	// 		} 
	// 		else
	// 		{
	// 			$('#gross_produksi').val(nilaiGross.toFixed(2));
	// 		}
	// 	}
	// 	else{
	// 		if (hasil > 5) {
	// 			$('#gross_produksi').val(nilaiGross_2.toFixed(2));
	// 		}
	// 		else
	// 		{
	// 			$('#gross_produksi').val(nilaiGross.toFixed(2));
	// 		}
	// 	}
	// 	// $('#cal_dt').val(calDT_new_lagi.toFixed(1));
	// 	// $('#gross_produksi').val(nilaiGross.toFixed(2));

	// 	//Nett
	// 	if(defect != 0){
	// 		var nilaiNett = nilaiGross;
	// 	}
	// 	else{
	// 		var nilaiNett = (hasil * 3600 / kalkulasi )* cavity2;
	// 	}
	// 	$('#nett_produksi').val(nilaiNett.toFixed(2));
	// 	//alert(calDT_new);
		
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
        var LT = $('#amountLT').val();
        var LT_new = $('#amountLT').val()/60;
        var calcDT = $('#amountIdle').val()/60;

        var nwt = $('#nwt').val();
        var ot = $('#ot_mp').val();
        var nwt_new = parseInt(nwt) + parseInt(ot);

        var calDT_new = nwt_new - hasil;
        var calDT_new_lagi = calDT_new - LT;
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
		/* var WorkHour = (parseFloat(hasil)) */
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
		console.log("debug hasil_time: "+hasil_time);
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

 	function convertJam(){
 		var LT_menit = $("#qtyLT").val();
 		//alert(LT_menit);
 		var nilaiLTJam = LT_menit/6;
 		$('#qtyLT_jam').val(nilaiLTJam.toFixed(2));
 	}
</script>



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
		if(kategori == ''){
			alert('Silahkan pilih nama defect/losstime terlebih dahulu!');
		}
		else
		{
			$("#tableNG").append(markup);
		}
		// $("#tableNG").append(markup);
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
		var qty      = $('#qtyLT').val();
		var markup = "<tr><td><input type='button' value='X'></td><td>"+svLT+"</td>"+
		"<td><input type='hidden' name='detailLT["+saveLT+"][nama]' value='"+nama+"''>"+nama+"</td>"+
		"<td><input type='hidden' name='detailLT["+saveLT+"][kategori]' value='"+kategori+"'>"+kategori+"</td>"+
		"<td><input type='hidden' name='detailLT["+saveLT+"][qty]' value="+qty+" class='nilai'>"+qty+"</td>"+
		"<td><input type='hidden' name='detailLT["+saveLT+"][satuan]' value="+satuan+">"+satuan+"<input type='hidden' name='detailLT["+saveLT+"][type]' value="+type+"></td>"+
		"</tr>";
		if(kategori == ''){
			alert('Silahkan pilih nama defect/losstime terlebih dahulu!');
		}
		else
		{
			$("#tableLT").append(markup);
		}
		// $("#tableLT").append(markup);
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
                var sum = 0;
                $('#tableLT > tr').each(function() {
                	  var nilai  = $('.nilai').val()
                    var price = parseFloat($(this).find('.nilai').val());
                    sum += price;
                    $('#amountLT').val(sum);
                });
            } 

            function totalStartStop(qty)
            {
            	var sum2 = parseFloat($('#amountIdle').val());
	                  sum2 += parseFloat(qty);
	                  $('#amountIdle').val(sum2);

            }
            $('#text_subtotal').text()
</script>

<script>
$(function() {
  $(".autocompletecuttingtools").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: "<?= site_url('c_dpr/get_autocomplete_cutting_tools') ?>",
        dataType: "json",
        data: { term: request.term },
        success: function(data) {
          response($.map(data, function(item) {
            return { label: item.label, value: item.value, id: item.id };
          }));
        }
      });
    },
    select: function(event, ui) {
      $('#cutting_tools_code').val(ui.item.label);
      $('#cutting_tools_id').val(ui.item.id);
      return false;
    }
  }).autocomplete("instance")._renderItem = function(ul, item) {
    return $("<li>")
      .append("<div>" + item.label + "</div>")
      .appendTo(ul);
  };
  $('#addCuttingToolBtn').click(function() {
    var code = $('#cutting_tools_code').val();
    var id = $('#cutting_tools_id').val();
    if (!id || !code) return;
    // Prevent duplicate
    if ($('input[name="cutting_tools_ids[]"][value="'+id+'"]').length) {
      $('#cutting_tools_code').val('');
      $('#cutting_tools_id').val('');
      return;
    }
    var rowCount = $('#cuttingToolsTable tbody tr').length + 1;
    var row = '<tr data-id="'+id+'">'+
      '<td>'+rowCount+'</td>'+
      '<td>'+code+'</td>'+
      '<td><button type="button" class="btn btn-danger btn-sm remove-tool">Hapus</button>'+ 
      '<input type="hidden" name="cutting_tools_ids[]" value="'+id+'"></td>'+ 
      '</tr>';
    $('#cuttingToolsTable tbody').append(row);
    $('#cutting_tools_code').val('');
    $('#cutting_tools_id').val('');
  });
  $('#cuttingToolsTable').on('click', '.remove-tool', function() {
    $(this).closest('tr').remove();
    // Re-number rows
    $('#cuttingToolsTable tbody tr').each(function(idx) {
      $(this).find('td:first').text(idx+1);
    });
  });
});
</script>

			</form>
  </body>
</html>
