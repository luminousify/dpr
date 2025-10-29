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


	  <title>Hasil Input DPR Online</title>
<?php foreach($data_production->result() as $data) { ?> 
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
	<div class="container-fluid">
		<div class="align-self-center mt-5">
			<div class="row justify-content-center ">
			  <!-- <div class="col-sm-6"> -->
			    <div class="card rounded card border mb-3">
			      <div class="card-body ">
			     <div class="card rounded card border-primary mb-3">
			      <div class="card-body ">
			      	<?php if ($this->session->flashdata('success')): ?>
               	<div class="alert alert-success" style="text-align: center;" role="alert">
                  <?php echo $this->session->flashdata('success'); ?>
                </div>
              <?php endif; ?>
			      	<div class="card-header bg-primary">
						    <center> <b>PRODUCTION ( <?= $divisi; ?> )</b></center>
						  </div>
			      <!-- START ROW -->
			      	<div class="row">
			      		<div class="col">
			      			<div class="form-group">
			      				<label><b>Tanggal <font style="color: red">*</font></b></label>
			      					<input type="date" name="user[0][tanggal]" class="form-control" required="" id="tanggal" onchange="lot(this.value)" value="<?= $data->tanggal; ?>" readonly>
			      			</div>
			      		</div>
			      	</div>
							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Shift <font style="color: red">*</font></b></label>
												<input type="text" name="user[0][shift]" class="form-control" value="<?= $data->shift; ?>" readonly>
										</div>
							  	</div>
							    <div class="col">
							     <div class="form-group">
												<label><b>NWT <font style="color: red">*</font></b></label>
											  <input type="text" name="user[0][nwt_mp]" id="nwt_mp" class="form-control" required="" value="<?= $data->nwt_mp; ?>" readonly>
										</div>
							    </div>
							    <div class="col">
							      <div class="form-group">
												<label><b>OT</b></label>
											  <input type="text" name="user[0][ot_mp]" id="ot_mp" class="form-control" value="<?= $data->ot_mp; ?>" readonly>
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Operator <font style="color: red">*</font></b></label>
											  <input type="text" name="user[0][operator]" class="form-control" value="<?= $data->operator; ?>" required="" readonly>
										</div>
							  	</div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Kanit <font style="color: red">*</font></b></label>
												<input type="text" name="user[0][kanit]" class="form-control" value="<?= $data->kanit; ?>" readonly>
											   <!--<select name="user[0][kanit]" style="width:95%;height: 100%" class="form-control" id="kanit" required=""  >
								          <option value="">-Choose-</option>
								          <?php // foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'";
									        //  if("$b[nama_operator]"==$data->kanit){echo "selected=\"selected\"";}
									        //  echo ">$b[nama_operator]</option>";}?>
								          </select>
								         -->
										</div>
							    </div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Group <font style="color: red">*</font></b></label>
												<input type="text" name="user[0][group]" class="form-control" value="<?= $data->group; ?>" readonly>
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col-sm-6">
							  		<div class="form-group">
											  <label><b>No. & Nama BOM <font style="color: red">*</font></b></label>
											  <input type="text" name="" id="barcode_sparepart_code" class="form-control" required="" id="id_bom" value="<?= $data->nama_bom; ?>" readonly>
		 										<input type="hidden" name="" id="tes">
		 										<input type="hidden" name="user[0][id_bom]" id="id_bomS" readonly="" class="form-control" style="height: 100%" value="<?= $data->id_bom;?>">
										</div>
							  	</div>
							  	<div class="col-sm-2">
							  		<div class="form-group">
											  <label><b>Mesin <font style="color: red">*</font></b></label>
													<input type="text" name="user[0][mesin]" class="form-control" value="<?= $data->mesin; ?>" readonly>
											  <!-- <select name="user[0][mesin]" id="mesin" style="width:100px;height: 100%" required x-moz-errormessage="Not Empty" class="form-control" onchange="lot(this.value)">
							          </select>
							       	 	-->
										</div>
							  	</div>
							  	<div class="col-sm-4">
							  		<div class="form-group">
									  <label><b>Customer</b></label>
							          <input type="text" name="user[0][customer]" id="customer" class="form-control" readonly="" value="<?= $data->customer; ?>">
										</div>
							  	</div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Production Time </b></label>
							          <input type="text" name="user[0][production_time]" id="production_time" class="form-control" value="<?= $data->production_time; ?>" readonly>
										</div>
							  	</div>
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Cavity</b></label>
											   <input type="text" name="user[0][cavity]" id="cavity" class="form-control" value="<?= $data->cavity; ?>" readonly>
										</div>
							  	</div>
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Tooling</b></label>
							          <input type="text" name="user[0][tooling]" class="form-control" value="<?= $data->tooling; ?>" readonly>
										</div>
							  	</div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Lot Global</b></label>
											   <!--<p id="lotGlobal"><?= $data->lot_global; ?></p>-->
													<input type="text" name="lotGlobalSave" id="lotGlobalSave" class="form-control" value="<?= $data->lot_global; ?>" readonly>
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>CT MC</b></label>
											   <input type="text" name="user[0][ct_mc]" id="ct_mc" readonly="" class="form-control" value="<?= $data->ct_mc; ?>">
										</div>
							  	</div>
							    <div class="col">
							     <div class="form-group">
												<label><b>CT MP</b></label> 
											   <input type="text" name="user[0][ct_mp]" id="ct_mp" readonly="" class="form-control" value="<?= $data->ct_mp; ?>">
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
										<div class="input-group">
											   <input type="text" name="user[0][ct_mc_aktual]" class="form-control" aria-describedby="inputGroupPrepend2" value="<?= $data->ct_mc_aktual; ?>" readonly>
											   <div class="input-group-prepend">
								          <span class="input-group-text" id="inputGroupPrepend2">Sec</span>
								        </div>
										</div>
							  	</div>
							    <div class="col">
							     <div class="input-group">
											   <input type="text" name="user[0][ct_mp_aktual]" class="form-control" aria-describedby="inputGroupPrepend2" value="<?= $data->ct_mp_aktual; ?>" readonly>
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
													<input type="text" name="user[0][target_mc]" id="target_mc" class="form-control" readonly="" value="<?= $data->target_mc; ?>">
										</div>
							    </div>
							    <div class="col">
							     <div class="form-group">
												<label><b>Target MP</b></label>
													<input type="text" name="user[0][target]" id="Target" class="form-control" readonly="" value="<?= $data->target; ?>">
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Nett Produksi</b></label>
											   <div class="input-group">
											   <input type="text" name="user[0][nett_produksi]" required="" class="form-control" value="<?= $data->nett_prod; ?>" readonly>
												</div>
										</div>
							  	</div>
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Gross Produksi</b></label>
											   <input type="text" name="user[0][gross_produksi]" class="form-control" value="<?= $data->gross_prod; ?>" readonly>
										</div>
							  	</div>
							  </div>

							  <div class="row">
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Qty OK <font style="color: red">*</font></b></label>
											   <div class="input-group">
											   <input type="text" name="user[0][qty_ok]" required="" class="form-control" onkeyup="lot(this.value)" aria-describedby="inputGroupPrepend2" value="<?= $data->qty_ok; ?>" id="qty" readonly>
											   <div class="input-group-prepend">
								          <span class="input-group-text" id="inputGroupPrepend2">Pcs</span>
								         </div>
												</div>
										</div>
							  	</div>
							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Keterangan</b></label>
											   <input type="text" name="user[0][keterangan]" class="form-control" value="<?= $data->keterangan; ?>" readonly>
										</div>
							  	</div>
							  </div>

							  <h5>Cutting Tools Used</h5>
							  <?php if (!empty($cutting_tools_usage)) { ?>
							    <ul>
							      <?php foreach ($cutting_tools_usage as $tool) { ?>
							        <li><?= htmlspecialchars($tool->code) ?></li>
							      <?php } ?>
							    </ul>
							  <?php } else { ?>
							    <p>No cutting tool usage recorded for this production.</p>
							  <?php } ?>

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
							  	<div class="col-sm-12">
							    	<div class="table-responsive">
							  			<div id="release">

							  		</div>
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
											   <input type="text" name="user[0][qty_ng]" id="amountNG" class="form-control" aria-describedby="inputGroupPrepend2" readonly="" value="<?= $data->qty_ng; ?>" >
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
											   <input type="text" name="user[0][qty_lt]" id="amountLT" readonly="" class="form-control"  aria-describedby="inputGroupPrepend2" value="<?= $data->qty_lt; ?>">
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
											  <label><b>Runner<font style="color: red">*</font></b></label>
											 <input type="number" name="runner" class="form-control" value="<?= $data->runner; ?>" readonly>
										</div>
							  	</div>

							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Loss Purge</b></label>
											 <input type="text" name="loss_purge" class="form-control" value="<?= $data->loss_purge; ?>" readonly>
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
											  <label><b>Mtrl Lot No.<font style="color: red">*</font></b></label>
											 <input type="text" name="user[0][lot_material_no]" class="form-control" value="<?= $data->lot_material_no; ?>" readonly>
										</div>
							  	</div>
							  </div>
							 <div class="row justify-content-center">
							  	<div class="col">
							  		<div class="form-group d-flex justify-content-center">
											  <a href="<?= base_url('login_op/input_dpr'); ?>"><button class="btn btn-primary mr-2">Input Baru</button></a>
											  <!--<a href="#"><button class="btn btn-warning mr-2">Selesai</button></a>-->
											  <a href="<?= base_url('login_op/list_dpr'); ?>"><button class="btn btn-success">Cek Input DPR</button></a>
										</div>
							  	</div>
							</div>
						 </div>
					</div>

					<!-- end save -->
			    </div>
			  </div>
</html>

<?php } ?>
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
	$(document).ready(function(){
		  id_bom = $('#id_bomS').val();
			$.ajax({
					          type    : "POST",
					          url     : "<?php echo site_url('c_operator/getdatabomMesinDPR');?>",
					          data    : "id_bom=" + id_bom,
					          success : function(data){
					              $("#mesin").html(data); 
					        }});

		            		



});
</script>