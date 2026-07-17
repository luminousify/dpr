<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- PWA hooks (Ticket 4) -->
    <link rel="manifest" href="<?php echo base_url(); ?>manifest.json">
    <meta name="theme-color" content="#5bc0de">
    <!-- iOS Safari standalone + icon (CONTEXT.md Decision 2: iOS is a target) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="DPR">
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/icons/icon-192.png">

    <!-- Critical CSS only - loaded synchronously -->
	  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">

	  <!-- Non-critical CSS - can be loaded with lower priority -->
	  <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet" media="all">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>" media="all">
    <link rel="stylesheet" href="<?php echo base_url().'assets/css/select2.min.css'?>" media="all">

  <title>DPR Online</title>

  <style type="text/css">
    html { zoom: 100%; }

    input[type=text], input[type=date], input[type=search], input[type=password] {
      height: 42px;
      padding: 10px 12px;
    }

    .form-section { position: relative; }
    .section-badge {
      font-size: 12px;
      padding: 4px 10px;
      border-radius: 999px;
      background: #f5f5f5;
      color: #555;
      vertical-align: middle;
      margin-left: 8px;
    }

    .flash-updated { animation: flash-bg 0.6s ease; }
    @keyframes flash-bg {
      0% { background: #fff8e1; }
      100% { background: transparent; }
    }

    .field-error {
      color: #c0392b;
      font-size: 12px;
      margin-top: 4px;
    }

    .is-invalid {
      border-color: #c0392b !important;
      box-shadow: 0 0 0 0.1rem rgba(192,57,43,0.15);
    }

    @media (max-width: 768px) {
      .card-body .row > [class^="col"] {
        flex: 0 0 100%;
        max-width: 100%;
      }
      .card { margin-bottom: 0.75rem !important; }
      .table-responsive { overflow-x: auto; }
      label { display: block; }
    }
  </style>

<form action="<?php echo site_url('c_operator/add')?>" method="post" id="my-form">
<body>
	<nav class="navbar fixed-top navbar-expand-lg navbar-light p-3" style="background-color:#5bc0de">
        <div class="container">
            <a class="navbar-brand text-white" href="<?php echo base_url('login_op/input_dpr_pwa') ?>" style="font-size:20px;font-weight:600">DPR ONLINE
              <!-- Ticket 5: offline pending badge. Hidden until countPending() returns > 0. -->
              <span id="pwa-pending-badge" class="badge badge-warning ml-2" style="display:none; font-size:12px;" title="Record tersimpan lokal, belum tersinkron ke server">
                Pending: <span id="pwa-pending-count">0</span>
              </span>
            </a>
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
			     <div class="card rounded card border-primary mb-3 form-section">
			      <div class="card-body ">
			      	<?php if ($this->session->flashdata('gagal')): ?>
               	<div class="alert alert-danger" style="text-align: center;" role="alert">
                  <?php echo $this->session->flashdata('gagal'); ?>
                </div>
              <?php endif; ?>
			      	<div class="card-header bg-primary d-flex justify-content-center align-items-center">
						    <center class="w-100 mb-0"> <b>PRODUCTION ( <?= $divisi; ?> ) </b></center>
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
											  <select name="user[0][group]" class="form-control" id="group" required="">
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
												<label><b>Lot Global</b></label>
											   <p id="lotGlobal"></p>
													<input type="hidden" name="user[0][lot_global]" id="lotGlobalSave" class="form-control">
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>CT MC STD</b></label>
							          <input type="text" name="user[0][ct_mc]" id="ct_mc" readonly="" class="form-control">
							          <input type="hidden" id="ct_quo" value="">
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
							  </div>

							   <div class="row">
							  	<div class="col">
							     <div class="form-group">
												<label><b>Target MC</b></label>
													<input type="text" name="user[0][target_mc]" id="target_mc" class="form-control" readonly="">
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
			     <div class="card rounded card border-warning mb-3 form-section">
			      <div class="card-body ">
			      	<div class="card-header bg-warning d-flex justify-content-between align-items-center">
						    <center class="w-100 mb-0"> <b>DEFECT </b></center>
                <span id="ng-total-badge" class="section-badge">Total NG: 0</span>
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
					<div class="card rounded card border-success mb-3 form-section">
			      <div class="card-body ">
			      	<div class="card-header bg-success d-flex justify-content-between align-items-center">
						    <center class="w-100 mb-0"> <b>LOSS TIME</b></center>
                <span id="lt-total-badge" class="section-badge">Total LT: 0 Jam</span>
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
							   	<input type="text" id="amountLT" readonly="" class="form-control"  aria-describedby="inputGroupPrepend2" value="0.00">
							   	<div class="input-group-prepend">
							          <span class="input-group-text" id="inputGroupPrepend2">Jam</span>
							         </div>
								</div>
								<input type="hidden" name="user[0][qty_lt]" id="qty_lt_minutes" value="0">
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
						    <center> <b>TOOLING</b></center>
					  </div>
						  <div class="row">
						      <div class="col">
						        <div class="form-group">
						          <label><b>Tooling</b></label>
						          <div class="input-group">
						            <input type="text" id="cutting_tools_code" class="autocompletecuttingtools form-control" placeholder="Input Tooling Code">
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
				<!-- END TOOLING SECTION -->
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
											<label style="font-weight:normal;text-align:left;max-width:600px">
												<input type="checkbox" name="confirm_duplicate" value="1">
												Konfirmasi duplikat &mdash; centang hanya jika (tanggal, mesin, shift, produk) memang perlu diinput lebih dari sekali
											</label>
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
  <script src="<?php echo base_url().'assets/scripts/select2.min.js'?>" type="text/javascript"></script>

	<!-- Global Configuration for external JS -->
	<script type="text/javascript">
		// Define base URLs for use in external JS
		var BASE_URL = "<?php echo base_url(); ?>";
		var SITE_URL = "<?php echo site_url(); ?>";
	</script>

	<!-- Optimized external JavaScript with debouncing -->
	<?php
		$input_dpr_js_path = 'assets/scripts/input_dpr.js';
		$input_dpr_js_version = @filemtime(FCPATH . $input_dpr_js_path) ?: time();
	?>
	<script src="<?php echo base_url($input_dpr_js_path) . '?v=' . $input_dpr_js_version; ?>" type="text/javascript"></script>

	<!-- Ticket 5: IndexedDB wrapper. Loaded before the SW registration and
	     before the submit-intercept so DPRDb is defined when the intercept binds. -->
	<?php
		$db_js_path = 'assets/scripts/db.js';
		$db_js_version = @filemtime(FCPATH . $db_js_path) ?: time();
	?>
	<script src="<?php echo base_url($db_js_path) . '?v=' . $db_js_version; ?>" type="text/javascript"></script>

	<!-- PWA Service Worker registration (Ticket 4). Scope defaults to /dpr/. -->
	<script type="text/javascript">
		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function () {
				navigator.serviceWorker.register(BASE_URL + 'sw.js')
					.then(function (reg) {
						console.log('[PWA] Service Worker registered. Scope:', reg.scope);
					})
					.catch(function (err) {
						console.error('[PWA] Service Worker registration failed:', err);
					});
			});
		}
	</script>

	<!-- Ticket 5: offline form save. Intercepts submit, saves the full form to
	     IndexedDB (no server call), shows a local receipt, and keeps the
	     "Pending: N" badge in sync. Layered ON TOP of input_dpr.js; the shared
	     file is not modified (legacy form at login_op/input_dpr stays intact). -->
	<script type="text/javascript">
		(function () {
			// If db.js failed to load (e.g. blocked), do not wire the intercept -
			// the form would fall through to the legacy server POST instead of
			// silently losing data.
			if (typeof DPRDb === 'undefined') {
				console.error('[PWA] DPRDb unavailable - offline save disabled');
				return;
			}

			var $badge = $('#pwa-pending-badge');
			var $badgeCount = $('#pwa-pending-count');

			// ---- Pending badge -------------------------------------------------
			function refreshBadge() {
				DPRDb.countPending().then(function (n) {
					if (n > 0) {
						$badgeCount.text(n);
						$badge.show();
					} else {
						$badge.hide();
					}
				}).catch(function (err) {
					console.warn('[PWA] countPending failed', err);
				});
			}

			// ---- Offline-safe id_production -----------------------------------
			// input_dpr.js lot() already builds id_production as
			//   YY + DD + MM + HHMMSS(#waktu) + id_bom
			// but #waktu is server-seeded with NO uniqueness counter. Two saves
			// within the same wall-clock second collide on the keyPath. Here we
			// insert a 3-digit per-second counter (mirroring the server's
			// sprintf('%03d', $i) fallback in c_operator::add), kept in
			// localStorage so it is monotonic across saves. The prefix shape
			// (YY DD MM ...) is unchanged, so server-side reporting still works.
			// input_dpr.js and the legacy form are NOT touched.
			function uniqueIdProduction() {
				var tanggal = $('#tanggal').val() || '';
				var YY = tanggal.substr(2, 2);
				var MM = tanggal.substr(8, 2);
				var DD = tanggal.substr(5, 2);
				var waktu = $('#waktu').val() || '';
				var id_bomS = $('#id_bomS').val() || '';

				var seqState;
				try {
					seqState = JSON.parse(localStorage.getItem('dpr_id_seq') || '{}');
				} catch (e) {
					seqState = {};
				}
				if (typeof seqState !== 'object' || seqState === null || seqState.waktu !== waktu) {
					seqState = { waktu: waktu, seq: 0 };
				}
				var counter = ('00' + seqState.seq).slice(-3);
				seqState.seq += 1;
				try {
					localStorage.setItem('dpr_id_seq', JSON.stringify(seqState));
				} catch (e) {
					// localStorage quota / disabled: proceed anyway - the counter
					// is best-effort; HHMMSS still changes once per second.
				}

				return YY + DD + MM + waktu + counter + id_bomS;
			}

			// ---- Form serialization to nested object --------------------------
			// Convert serializeArray() output ([{name,value},...]) into the nested
			// object shape the legacy POST would produce:
			//   user[0][tanggal]      -> form.user["0"].tanggal
			//   detail[1][nama]       -> form.detail["1"].nama
			//   cutting_tools_ids[]   -> form.cutting_tools_ids = [...]
			//   runner                 -> form.runner
			// Ticket 6 will JSON.stringify(record.form) and POST to api/Sync/submit,
			// whose drain worker (Ticket 3) consumes the same structure. Storing
			// the nested object (not raw pairs) keeps Ticket 3 a trivial parse.
			function formToObject(pairs) {
				var root = {};
				for (var i = 0; i < pairs.length; i++) {
					var name = pairs[i].name;
					if (!name) {
						continue;
					}
					insertNested(root, splitName(name), pairs[i].value);
				}
				return root;
			}

			// "user[0][tanggal]" -> ["user", "0", "tanggal"]
			// "cutting_tools_ids[]" -> ["cutting_tools_ids", ""]
			// "runner" -> ["runner"]
			function splitName(name) {
				var out = [];
				var open = name.indexOf('[');
				if (open === -1) {
					return [name];
				}
				out.push(name.slice(0, open));
				var body = name.slice(open);
				var re = /\[([^\]]*)\]/g;
				var m;
				while ((m = re.exec(body)) !== null) {
					out.push(m[1]);
				}
				return out;
			}

			function isNumericKey(k) {
				return k !== '' && /^\d+$/.test(k);
			}

			function insertNested(root, segments, value) {
				var cur = root;
				for (var i = 0; i < segments.length; i++) {
					var seg = segments[i];
					var isLeaf = (i === segments.length - 1);
					var nextSeg = segments[i + 1];

					// Empty bracket "[]" -> append to an array.
					if (seg === '') {
						if (!Array.isArray(cur)) {
							return; // malformed for this form; skip
						}
						if (isLeaf) {
							cur.push(value);
						} else {
							// No observed field appends then nests further; guard anyway.
							var child = (nextSeg === '' || isNumericKey(nextSeg)) ? [] : {};
							cur.push(child);
							cur = child;
						}
						continue;
					}

					if (isLeaf) {
						cur[seg] = value;
					} else {
						if (cur[seg] === undefined || cur[seg] === null) {
							cur[seg] = (nextSeg === '' || isNumericKey(nextSeg)) ? [] : {};
						}
						cur = cur[seg];
					}
				}
			}

			// ---- HTML escape for the receipt ----------------------------------
			function esc(s) {
				if (s === null || s === undefined) {
					return '';
				}
				return String(s).replace(/[&<>"']/g, function (c) {
					return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
				});
			}

			// ---- Receipt rendering --------------------------------------------
			function firstUser(formObj) {
				if (!formObj || !formObj.user) {
					return {};
				}
				// formToObject stores numeric keys as strings ("0").
				return formObj.user[0] || formObj.user['0'] || {};
			}

			function showReceipt(record) {
				var u = firstUser(record.form) || {};
				var html = '';
				html += '<div class="alert alert-success"><b>Record tersimpan di perangkat ini.</b><br>';
				html += '<span class="badge badge-warning">Pending sync</span> ';
				html += 'Akan terkirim otomatis ke server saat perangkat online.</div>';
				html += '<table class="table table-sm table-bordered mb-0">';
				html += '<tr><th style="width:40%">ID Produksi</th><td><b>' + esc(record.id_production) + '</b></td></tr>';
				html += '<tr><th>Tanggal</th><td>' + esc(u.tanggal) + '</td></tr>';
				html += '<tr><th>Shift</th><td>' + esc(u.shift) + '</td></tr>';
				html += '<tr><th>Mesin</th><td>' + esc(u.mesin) + '</td></tr>';
				html += '<tr><th>Operator</th><td>' + esc(u.operator) + '</td></tr>';
				html += '<tr><th>Qty OK</th><td>' + esc(u.qty_ok) + '</td></tr>';
				html += '<tr><th>Qty NG</th><td>' + esc(u.qty_ng) + '</td></tr>';
				html += '<tr><th>Nett Produksi</th><td>' + esc(u.nett_prod) + '</td></tr>';
				html += '<tr><th>Gross Produksi</th><td>' + esc(u.gross_prod) + '</td></tr>';
				html += '</table>';
				$('#pwaReceiptBody').html(html);
				$('#pwaReceiptModal').modal('show');
			}

			// ---- Reset the form for the next entry ----------------------------
			// Clears dynamic rows (NG/LT/cutting tools/release) which .reset()
			// cannot reach, then resets static fields to server-rendered
			// defaults and re-runs the shared calc/total initializers.
			function resetFormForNextEntry() {
				$('#tableNG').empty();
				$('#tableLT').empty();
				$('#cuttingToolsTable tbody').empty();
				$('#release').empty();
				try {
					$('#my-form')[0].reset();
				} catch (e) {
					// Older browsers: ignore reset failure, manual clears above stand.
				}
				if (typeof initializeCalculationFields === 'function') {
					initializeCalculationFields();
				}
				if (typeof total === 'function') {
					total();
				}
				if (typeof totalLT === 'function') {
					totalLT();
				}
				$('#id_production').val('');
				$('#lotGlobal').html('');
				$('#lotGlobalSave').val('');
				var tanggalEl = document.getElementById('tanggal');
				if (tanggalEl) {
					tanggalEl.valueAsDate = new Date();
				}
				// Hide any inline validation errors left over from the previous entry.
				$('.field-error').remove();
				$('.is-invalid').removeClass('is-invalid');
			}

			// ---- Submit intercept ---------------------------------------------
			// Bind AFTER input_dpr.js so its validation handler runs first and
			// has already recalculated nett/gross. jQuery fires same-element
			// handlers in bind order; this one always preventDefault()s so the
			// legacy POST to c_operator/add never fires on the PWA page.
			$(document).ready(function () {
				refreshBadge();

				$('#my-form').on('submit', function (e) {
					// Always intercept on the PWA page: save locally, never POST.
					e.preventDefault();

					// Re-validate; the shared handler ran first but may have bailed
					// (return false does not stop later same-element handlers).
					if (typeof validateForm === 'function' && !validateForm()) {
						$('#submit').prop('disabled', false).val('Save');
						$('#loading').html('');
						return;
					}
					var baseId = ($('#id_production').val() || '').toString().trim();
					if (!baseId) {
						alert('ID Produksi tidak tergenerate. Silakan pilih BOM kembali atau refresh halaman.');
						$('#submit').prop('disabled', false).val('Save');
						$('#loading').html('');
						return;
					}

					// Generate the offline-unique id and reflect it back into the
					// field so the receipt, the form, and the stored record agree.
					var uniqueId = uniqueIdProduction();
					$('#id_production').val(uniqueId);

					var record = {
						id_production: uniqueId,
						status: 'pending',
						created_at: new Date().toISOString(),
						form: formToObject($('#my-form').serializeArray())
					};

					var $submit = $('#submit');
					$submit.prop('disabled', true).val('Saving...');
					$('#loading').html('Menyimpan ke perangkat...').css('color', 'blue');

					DPRDb.putPending(record).then(function () {
						$submit.prop('disabled', false).val('Save');
						$('#loading').html('');
						showReceipt(record);
						refreshBadge();
					}).catch(function (err) {
						console.error('[PWA] IndexedDB save failed', err);
						$submit.prop('disabled', false).val('Save');
						$('#loading').html('');
						alert('Gagal menyimpan record secara lokal. Silakan coba lagi.');
					});
				});

				// "Input Baru" on the receipt: close modal + reset for next record.
				$(document).on('click', '#pwaReceiptNew', function () {
					$('#pwaReceiptModal').modal('hide');
					resetFormForNextEntry();
				});

				// If the operator returns to the page (e.g. app foregrounded on
				// iOS), refresh the badge so a sync that happened elsewhere is
				// reflected. Ticket 6 will own actual sync triggering.
				window.addEventListener('pageshow', refreshBadge);
			});
		})();
	</script>

		</form>

	<!-- Ticket 5: local receipt modal. Populated from the IndexedDB record on
	     successful offline save. Deliberately a lightweight confirmation, not
	     a full clone of views/online/input_hasil_dpr.php (per handoff). -->
	<div class="modal fade" id="pwaReceiptModal" tabindex="-1" role="dialog" aria-labelledby="pwaReceiptTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header bg-success text-white">
					<h5 class="modal-title" id="pwaReceiptTitle">Tersimpan di Perangkat</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="pwaReceiptBody"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-primary" id="pwaReceiptNew">Input Baru</button>
				</div>
			</div>
		</div>
	</div>
  </body>
</html>
