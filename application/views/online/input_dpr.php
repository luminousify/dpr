<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Critical CSS only - loaded synchronously -->
	  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">
	  
	  <!-- Non-critical CSS - can be loaded with lower priority -->
	  <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet" media="all">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>" media="all">

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
        type="text" 
        name="user[0][nwt_mp]" 
        id="nwt" 
        class="form-control" 
        required 
        pattern="[0-9]+(\.[0-9]+)?" 
        title="Please enter a valid number (e.g., 8 or 8.5). Comma is not allowed."
        oninput="this.value = this.value.replace(/,/g, ''); lot(this.value)" 
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
		
	<!-- JavaScript loaded at bottom for better performance -->
	<script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 
	<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
	<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>
	
	<!-- Global configuration for external JS -->
	<script type="text/javascript">
		// Define base URLs for use in external JS
		var BASE_URL = "<?php echo base_url(); ?>";
		var SITE_URL = "<?php echo site_url(); ?>";
	</script>
	
	<!-- Optimized external JavaScript with debouncing -->
	<script src="<?php echo base_url().'assets/scripts/input_dpr.js'?>" type="text/javascript"></script>


			</form>
  </body>
</html>
