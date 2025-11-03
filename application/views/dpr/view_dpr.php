<title>DPR | Add DPR Online</title>
<?php $this->load->view('layout/sidebar'); ?>

<!-- Essential sidebar JavaScript files -->
<script src="<?= base_url(); ?>template/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?= base_url(); ?>template/js/inspinia.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">


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
<body>
	<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox ">
          <div class="ibox-title">
            <h5>View DPR Online</h5>
            <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
                <div class="ibox-content">
	<div class="container-fluid">
		<div class="align-self-center">
			  <!-- <div class="col-sm-6"> -->
			     <div class="card rounded card border-primary mb-3">
			      <div class="card-body ">
			      	<div class="card-header bg-primary">
						    <center> <b>PRODUCTION ( <?= $data->divisi; ?> )</b></center>
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
											  <!-- <select name="user[0][kanit]" style="width:95%;height: 100%" class="form-control" id="kanit" required=""  >
								          <option value="">-Choose-</option>
								          <?php// foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'";
									          //if("$b[nama_operator]"==$data->kanit){echo "selected=\"selected\"";}
									          //echo ">$b[nama_operator]</option>";}?>
								          </select>
								        	-->
										</div>
							    </div>
							  </div>

							  <div class="row">
							  	<div class="col-sm-4">
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

							  	<div class="col-sm-2">
							  		<div class="form-group">
									  <label><b>Proses</b></label>
							          <input type="text" name="user[0][proses]" id="proses" class="form-control" value="<?= $data->proses; ?>" readonly="">
										</div>
							  	</div>
							  	<div class="col-sm-4">
							  		<div class="form-group">
									  <label><b>Customer</b></label>
							          <input type="text" name="user[0][customer]" id="customer" class="form-control" value="<?= $data->customer; ?>" readonly="">
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
											   <input type="text" name="user[0][ct_mc_aktual]" class="form-control" aria-describedby="inputGroupPrepend2" value="<?= $data->ct_mc_aktual; ?>" readonly >
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
													<td><?= round($ng->qty / 60, 2); ?></td>
													<td>Jam</td>
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
							  	<input type="text" id="amountLT" readonly="" class="form-control"  aria-describedby="inputGroupPrepend2" value="<?= round($data->qty_lt / 60, 2); ?>">
							  	<div class="input-group-prepend">
							          <span class="input-group-text" id="inputGroupPrepend2">Jam</span>
							         </div>
								</div>
								<input type="hidden" name="user[0][qty_lt]" id="qty_lt_minutes" value="<?= $data->qty_lt; ?>">
										</div>
							</div>
							  </div>
						</div>
					</div>
					<!-- end loss time -->
					<hr>
					<!-- CUTTING TOOLS TABLE -->
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label><b>Cutting Tools Digunakan</b></label>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr style="background-color: #bee5eb">
												<th>No</th>
												<th>Kode</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($cutting_tools)) { $no=1; foreach($cutting_tools as $tool) { ?>
												<tr>
													<td><?= $no++; ?></td>
													<td><?= htmlspecialchars($tool['code']); ?></td>
												</tr>
											<?php }} else { ?>
												<tr><td colspan="2" class="text-center">Tidak ada cutting tools digunakan.</td></tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- end save -->
					<!-- DATA LAINNYA -->
					<hr>
					<div class="card rounded card border-primary mb-3">
			      <div class="card-body ">
			      	<div class="card-header bg-primary">
						    <center> <b>DATA LAINNYA</b></center>
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
											  <label><b>Runner<font style="color: red">*</font></b></label>
											 <input type="number" name="runner" class="form-control" value="<?= $data->runner; ?>" readonly>
										</div>
							  	</div>

							  	<div class="col">
							  		<div class="form-group">
											  <label><b>Lost Purge + Setting</b></label>
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
											  <a href="<?= base_url('c_dpr/dpr'); ?>"><button class="btn btn-warning">Back</button></a>
										</div>
							  	</div>
							</div>
						 </div>
					</div>

			    </div>
			  </div>
</html>

<?php } ?>

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