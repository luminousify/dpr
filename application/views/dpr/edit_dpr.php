<title>DPR | Edit DPR Online</title>
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

<!-- Essential sidebar JavaScript files -->
<script src="<?= base_url(); ?>template/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?= base_url(); ?>template/js/inspinia.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

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
        <form action="<?php echo site_url('c_new/edit_dpr_online')?>" method="post" id="my-form">
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
                            <center> <b>PRODUCTION ( <?= $divisi; ?> ) </b></center>
                          </div>
                  <!-- START ROW -->
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label><b>Tanggal <font style="color: red">*</font></b></label>
                                    <input type="date" name="user[0][tanggal]" class="form-control" required="" id="tanggal" onchange="lot(this.value)" value="<?= $data->tanggal; ?>">
                            </div>
                        </div>
                        
                    </div>
                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Shift <font style="color: red">*</font></b></label>
                                              <select name="user[0][shift]" class="form-control" required="" id="shift" onchange="lot(this.value)" >
                                                <option <?php if( $data->shift =='1'){echo "selected"; } ?> value='1'>1</option>
                                                <option <?php if( $data->shift =='2'){echo "selected"; } ?> value='2'>2</option>
                                                <option <?php if( $data->shift =='3'){echo "selected"; } ?> value='3'>3</option>
                                                    </select>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>NWT <font style="color: red">*</font></b></label>
                                              <input type="text" name="user[0][nwt_mp]" id="nwt" class="form-control" required="" value="<?= $data->nwt_mp; ?>" pattern="[0-9]+(\.[0-9]+)?" title="Please enter a valid number (e.g., 8 or 8.5). Comma is not allowed." oninput="this.value = this.value.replace(/,/g, '')">
                                        </div>
                                </div>
                                <div class="col">
                                  <div class="form-group">
                                                <label><b>OT</b></label>
                                              <input type="text" name="user[0][ot_mp]" id="ot_mp" class="form-control" value="<?= $data->ot_mp; ?>">
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
                                               <select name="user[0][kanit]" class="form-control" id="kanit" required=""  >
                                                  <option value="">-Choose-</option>
                                                  <?php foreach($kanit as $k): ?>
                                                    <option <?php if($data->kanit == $k['nama_actor']){echo "selected";} ?> value='<?= $k['nama_actor'] ?>'><?= $k['nama_actor'] ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Group <font style="color: red">*</font></b></label>
                                               <select name="user[0][group]" class="form-control" id="group" required=""  >
                                                  <option value="">-Choose-</option>
                                                  <option <?php if( $data->group =='A'){echo "selected"; } ?> value='A'>A</option>
                                                  <option <?php if( $data->group =='B'){echo "selected"; } ?> value='B'>B</option>
                                                  <option <?php if( $data->group =='C'){echo "selected"; } ?> value='C'>C</option>
                                               </select>
                                        </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                              <label><b>No. & Nama BOM <font style="color: red">*</font></b></label>
                                              <input type="search form-control" name="" id="barcode_sparepart_code" class="autocompleteBom" style="width: 100%;" required="" id="id_bom" value="<?= $data->nama_bom; ?>">
                                                <input type="hidden" name="" id="tes">
                                                <input type="text" name="user[0][id_bom]" id="id_bomS" readonly="" class="form-control" value="<?= $data->id_bom;?>">
                                                <input type="hidden" name="id_prod" id="id_production"  readonly="" class="form-control" value="<?= $data->id_production;?>">
                                        </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                              <label><b>Mesin <font style="color: red">*</font></b></label>
<!--                                               <select name="user[0][mesin]" id="mesin" style="width:100px;height: 100%" required x-moz-errormessage="Not Empty" class="form-control" onchange="lot(this.value)">
                                      </select> -->
                                      <input type="text" name="user[0][mesin]" id="mesin" required x-moz-errormessage="Not Empty" class="form-control" onkeyup="lot(this.value)" value="<?= $data->mesin; ?>">
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
                                      <input type="text" name="user[0][production_time]" id="production_time" class="form-control" value="<?= $data->production_time;?>" readonly>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Cavity Actual</b></label>
                                               <input type="text" name="user[0][cavity]" id="cavity" class="form-control" value="<?= $data->cavity;?>">
                                      </select>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Cavity Std</b></label>
                                               <input type="text" name="user[0][cavity2]" id="cavity2" class="form-control" value="<?= $data->cavity2;?>" >
                                      </select>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Tooling</b></label>
                                      <input type="text" name="user[0][tooling]" class="form-control" value="<?= $data->tooling;?>">
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Lot Global</b></label>
                                               <p id="lotGlobal"><?= $data->lot_global;?></p>
                                                    <input type="hidden" name="user[0][lot_global]" id="lotGlobalSave" class="form-control" value="<?= $data->lot_global;?>">
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
                                               <input type="text" name="user[0][ct_mc_aktual]" id="ct_mc_aktual" class="form-control" aria-describedby="inputGroupPrepend2" onkeyup="setTarget(this.value)" value="<?= $data->ct_mc_aktual; ?>">
                                               <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Sec</span>
                                        </div>
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="input-group">
                                               <input type="text" name="user[0][ct_mp_aktual]" id="ct_mp_aktual" class="form-control" aria-describedby="inputGroupPrepend2" value="<?= $data->ct_mp_aktual; ?>" >
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
                                                    <input type="text" name="user[0][target]" id="target_mp" class="form-control" readonly="" value="<?= $data->target; ?>">
                                        </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Qty OK <font style="color: red">*</font></b></label>
                                               <div class="input-group">
                                               <input type="text" name="user[0][qty_ok]" required="" class="form-control" onkeyup="lot(this.value)" aria-describedby="inputGroupPrepend2" id="qty" value="<?= $data->qty_ok; ?>">
                                               <div class="input-group-prepend">
                                          <span class="input-group-text" id="inputGroupPrepend2">Pcs</span>
                                         </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Keterangan</b></label>
                                               <input type="text" name="user[0][keterangan]" class="form-control" value="<?= $data->keterangan; ?>">
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
                                    <!--<button class="btn btn-primary" id="tambah">Add</button>-->
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
                                            <?php
                                            //$this->load->view('dpr/loadNG');  
                                            ?>
                                            <?php $i = -1; $no = 0; foreach($data_productionNG->result() as $ng) {  $no++; $i++?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo base_url('c_new/delete_detail_dl/'.$ng->id_DL.'/'.$ng->id_production.'/NG') ?>" class="btn btn-sm btn-light border border-dark delete" style="font-size:14px">X

                                                    </td>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $ng->nama; ?></td>
                                                    <td><?= $ng->kategori; ?></td>
                                                    <td><?= $ng->qty; ?> <input type="hidden" value="<?= $ng->qty; ?>" class="nilai"></td>
                                                    <td><?= $ng->satuan; ?></td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                        <input type="hidden" name="id_production_new"  readonly="" class="form-control" value="<?= $data->id_production;?>">
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
                                                <input type="text" name="" id="namaLT" class="autocompletelosstime form-control formLT">
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
                                                </div>
                                                <input type="hidden" name="" id="kategoriLT" class="form-control formLT" placeholder="qty" style="width:25%;height: 100%"> 
                                                <input type="hidden" name="" id="typeLT" class="formNG">
                                                <input type="hidden" name="" id="satuanLT" class="formNG">

                                        </div>
                                </div>
                                <div class="col">
                                    <button class="btn btn-success" class="form-control" onclick="addLT(); return false;">( + )</button>
                                    <!--<button class="btn btn-primary" id="tambahLT">Add</button>-->
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
                                        <tbody id="tableLT">
                                            <?php
                                            //$this->load->view('dpr/loadLT');  
                                            ?>
                                            <?php $i = -1; $no = 0; foreach($data_productionLT->result() as $lt) {  $no++; $i++?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo base_url('c_new/delete_detail_dl/'.$lt->id_DL.'/'.$lt->id_production.'/LT') ?>" class="btn btn-sm btn-light border border-dark delete" style="font-size:14px">X
                                                        </td>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $lt->nama; ?></td>
                                                    <td><?= $lt->kategori; ?></td>
                                                    <td><?= round($lt->qty / 60, 2); ?> <input type="hidden" value="<?= $lt->qty; ?>" class="nilai"></td>
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
                            <center> <b>CUTTING TOOLS DIGUNAKAN</b></center>
                          </div>
                          <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cutting_tool_select"><b>Tambah Cutting Tool</b></label>
                                        <div class="input-group">
                                            <select id="cutting_tool_select" class="form-control">
                                              <option value="">Pilih Cutting Tool</option>
                                              <?php foreach($cutting_tools as $tool): ?>
                                                <option value="<?= $tool['id'] ?>"><?= htmlspecialchars($tool['code']) ?></option>
                                              <?php endforeach; ?>
                                            </select>
                                            <div class="input-group-append">
                                              <button type="button" class="btn btn-success" id="add_cutting_tool_btn">Tambah</button>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="cutting_tools_table">
                                            <thead>
                                              <tr style="background-color: #bee5eb">
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Aksi</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <?php if (!empty($used_cutting_tools)) { $no=1; foreach($used_cutting_tools as $tool) { ?>
                                                <tr data-id="<?= $tool['id'] ?>">
                                                  <td><?= $no++; ?></td>
                                                  <td><?= htmlspecialchars($tool['code']); ?></td>
                                                  <td><button type="button" class="btn btn-danger btn-sm remove-tool">Hapus</button>
                                                    <input type="hidden" name="cutting_tools_ids[]" value="<?= $tool['id'] ?>">
                                                  </td>
                                                </tr>
                                              <?php }} ?>
                                            </tbody>
                                          </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- end cutting tools -->
                    <hr>
                    <div class="card rounded card border-primary mb-3">
                  <div class="card-body ">
                    <div class="card-header bg-primary">
                            <center> <b>SAVE DATA</b></center>
                          </div>
                          <div class="row">
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Nett Produksi </b></label>
                                                    <input type="text" name="user[0][nett_prod]" id="nett_produksi" class="form-control" value="<?= $data->nett_prod;?>"  >
                                        </div>
                                </div>
                                <div class="col">
                                 <div class="form-group">
                                                <label><b>Gross Produksi</b></label>
                                                    <input type="text" name="user[0][gross_prod]" id="gross_produksi" class="form-control" value="<?= $data->gross_prod;?>">
                                        </div>
                                </div>
                              </div>
                          <div class="row">
                             <div class="col">
                                    <div class="form-group">
                                              <label><b>Runner</b></label>
                                             <input type="text" name="runner" class="form-control" value="<?= $data->runner;?>">
                                        </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Loss Purge + Setting</b></label>
                                             <input type="text" name="user[0][loss_purge]" class="form-control" value="<?= $data->loss_purge;?>">
                                        </div>
                                </div>
                                
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Transaksi<font style="color: red">*</font></b></label>
                                             <input type="hidden" name="" id="waktu" value="<?= date('his');  ?>">
                                             <input type="text" name="id_production" id="id_production" class="form-control" readonly="" value="<?= $data->id_production;?>">
                                        </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                              <label><b>Mtrl Lot No.</b></label>
                                             <input type="text" name="user[0][lot_material_no]" class="form-control" value="<?= $data->lot_material_no;?>">
                                             <input type="hidden" name="user[0][pic]" value="<?= $data->pic;?>" > 
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

    function setTarget(id)
    {
        var ct_aktual = $('#ct_mc_aktual').val();
        var cavity = $('#cavity').val();
        var nwt = $('#nwt').val();
        var ot = $('#ot_mp').val();
        let nwt_plus_ot = parseFloat(nwt) + parseInt(ot);
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
        //var id_production = ambil_tahun + ambil_tanggal + ambil_bulan + $('#waktu').val() + id_bomS;
        //$('#id_production').val(id_production);
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
    //     var nwt_new = parseFloat(nwt) + parseFloat(ot);
    //     // alert(nwt_new);

    //     var calDT_new = nwt_new - hasil;
    //     var calDT_new_lagi = calDT_new - LT;
    //     //var cek = nwt - calDT_new;
    //     //Gross jika Prod Time <= 8
    //     var nilaiGross = 3600*(nwt_new-calDT_new)/qty*cavity2;
    //     //Gross jika Prod Time > 8
    //     var nilaiGross_2 = 3600*nwt_new/qty*cavity2;
    //     console.log("nwt new : "+nwt_new);
    //     console.log("hasil : "+hasil);
    //     console.log("nilaiGross : " + nilaiGross)
    //     console.log("nilaiGross 2 : " + nilaiGross_2)

    //     if(calDT_new_lagi < 0){
    //       $('#cal_dt').val(0);
    //     }
    //     else{
    //       $('#cal_dt').val(calDT_new_lagi.toFixed(1));
    //     }
    //     //$('#cal_dt').val(calDT_new_lagi.toFixed(1));
    //     if(nwt_new = 8){ //nwt new = nwt+ot 
    //       if(hasil > 8){//hasil =  (kalkulasi (qty+defect) / cavity)*(ct_aktual/3600)
    //         $('#gross_produksi').val(nilaiGross_2.toFixed(0));
    //         } 
    //         else
    //         {
    //           if(LT == 0){
    //             $('#gross_produksi').val(nilaiGross.toFixed(0));

    //           }else{
    //             $('#gross_produksi').val(nilaiGross_2.toFixed(0));

    //           }
              
    //         }
    //       }
    //       else{
    //         if (hasil > 5) {
    //           $('#gross_produksi').val(nilaiGross_2.toFixed(0));
    //         }
    //         else
    //         {
    //           $('#gross_produksi').val(nilaiGross.toFixed(0));
    //         }
    //       }
    //     //$('#gross_produksi').val(nilaiGross.toFixed(2));

    //     if(defect != 0){
		// 	var nilaiNett = nilaiGross;
		// }
		// else{
		// 	var nilaiNett = (hasil * 3600 / kalkulasi )* cavity2;
		// }        //$('#nett_produksi').val(nilaiNett.toFixed(2));
    //     $('#nett_produksi').val(nilaiNett.toFixed(0));
    //     //alert(calDT_new);
    // }

    function GrossNett()
    {
        // Helper function for safe division
        function safeDivision(numerator, denominator) {
            if (!Number.isFinite(numerator) || !Number.isFinite(denominator) || denominator === 0) {
                return 0;
            }
            return numerator / denominator;
        }

        // Helper function to get numeric value safely
        function getNumericValue(selector) {
            var value = $(selector).val();
            if (value === undefined || value === null || value === '') {
                return 0;
            }
            var parsed = parseFloat(value);
            return Number.isNaN(parsed) ? 0 : parsed;
        }

        var qty = getNumericValue('#qty');
        var ct_aktual = getNumericValue('#ct_mc_aktual');
        var cavity = getNumericValue('#cavity');
        var cavity2 = getNumericValue('#cavity2');
        var defect = getNumericValue('#amountNG');
        var kalkulasi = qty + defect;
        
        // Set minimum values for cavities to prevent division by zero
        if (cavity <= 0) cavity = 1;
        if (cavity2 <= 0) cavity2 = cavity;

        // Validate required inputs for calculation
        if (qty <= 0 || kalkulasi <= 0 || ct_aktual <= 0 || cavity <= 0 || cavity2 <= 0) {
            $('#production_time').val('0');
            $('#gross_produksi').val('0.00');
            $('#nett_produksi').val('0.00');
            $('#cal_dt').val('0');
            return;
        }

        // Calculate production time safely
        var hasil_time = safeDivision(kalkulasi, cavity) * safeDivision(ct_aktual, 3600);
        var hasil = Number.isFinite(hasil_time) ? hasil_time.toFixed(1) : '0';
        $('#production_time').val(hasil);

        // Handle loss time - zero loss time is valid
        var LT = getNumericValue('#amountLT');
        var calcDT = safeDivision(getNumericValue('#amountIdle'), 60);

        var nwt = getNumericValue('#nwt');
        var ot = getNumericValue('#ot_mp');
        var nwt_new = nwt + ot;

        var calDT_new = nwt_new - parseFloat(hasil);
        var calDT_new_lagi = calDT_new - LT;
        
        // Set cal_dt with validation
        if (calDT_new_lagi < 0 || !Number.isFinite(calDT_new_lagi)) {
            $('#cal_dt').val('0');
        } else {
            $('#cal_dt').val(calDT_new_lagi.toFixed(1));
        }

        // Calculate gross production using the same logic as input_dpr.js for consistency
        var workHour = parseFloat(hasil) + LT - ot;
        var overtime = ot;
        var totalStopTime = LT;
        var availableHours = Math.max(workHour + overtime, 0);
        var effectiveHours = Math.max(workHour + overtime - totalStopTime, 0);
        var denominator = safeDivision(qty, cavity2);

        var grossProduction = 0;
        if (availableHours > 0 && denominator > 0) {
            grossProduction = safeDivision(3600, safeDivision(denominator, availableHours));
        }

        var nettProduction = 0;
        if (effectiveHours > 0 && denominator > 0) {
            nettProduction = safeDivision(3600, safeDivision(denominator, effectiveHours));
        }

        // Ensure final values are finite and non-negative
        if (!Number.isFinite(grossProduction) || grossProduction < 0) {
            grossProduction = 0;
        }
        if (!Number.isFinite(nettProduction) || nettProduction < 0) {
            nettProduction = 0;
        }

        // Apply custom rounding and set values
        $('#gross_produksi').val(customRound(grossProduction).toFixed(2));
        $('#nett_produksi').val(customRound(nettProduction).toFixed(2));

        // Debug information (kept for troubleshooting)
        console.log("cavity1: " + cavity);
        console.log("cavity2: " + cavity2);
        console.log("debug WorkHour: " + workHour);
        console.log("debug hasil: " + hasil);
        console.log("debug LT: " + LT);
        console.log("debug Kalkulasi: " + kalkulasi);
        console.log("debug cavity: " + cavity);
        console.log("debug ct_aktual: " + ct_aktual);
        console.log("debug Overtime: " + overtime);
        console.log("debug TotStopTime: " + totalStopTime);
        console.log("debug OK: " + qty);
        console.log("debug ProductCavity: " + cavity2);
        console.log("result gross: " + grossProduction);
        console.log("result nett: " + nettProduction);
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
         total();
         GrossNett(); 
         //alert(nama);
         $('.formNG').val('');
        /*var id_production = $('#id_production').val();
        $.ajax({
                  type    : "POST",
                  url     : "<?php// echo base_url(); ?>index.php/c_new/add_table_ng/t_production_op_detail",
                  data    :  "detail[0][id_production]=" + id_production, 
                  success : function(data){ 
                     var url = "<?php// echo base_url(); ?>" + "index.php/c_new/loadNG/" + id_production;
                       $('#tableNG').load(url,'refresh');
                  }}); */

    }


    /*$("#tambah").click(function() {
        var id_production = $('#id_production').val();
        var nama = $('#namaNG').val();
        var kategori = $('#kategoriNG').val();
        var type = $('#typeNG').val();
        var satuan = $('#satuanNG').val();
        var qty = $('#qtyNG').val();
        $.ajax({
                  type    : "POST",
                  url     : "<?php// echo site_url('c_new/add_table_ng');?>",
                  data    :  "id_production=" + id_production + "&nama=" + nama + "&kategori=" + kategori + "&type=" + type + "&satuan=" + satuan + "&qty=" + qty,
                  success : function(data){ 
                  var url = "<?php //echo base_url(); ?>" + "c_new/loadNG/" + id_production;
                       $('#tableNG').load(url,'refresh');
                       $('#namaNG').val('');
                       $('#qtyNG').val('');
                       
                  }});
        return false; //agar ga ke refresh 
    });

    $("#tambahLT").click(function() {
        totalLT();
        if(kategori == 'START/STOP')
        {
            totalStartStop(qty);
        }
        GrossNett(); 
        var id_production = $('#id_production').val();
        var nama = $('#namaLT').val();
        var kategori = $('#kategoriLT').val();
        var type = $('#typeLT').val();
        var satuan = $('#satuanLT').val();
        var qty = $('#qtyLT').val();
        $.ajax({
                  type    : "POST",
                  url     : "<?php// echo site_url('c_new/add_table_lt');?>",
                  data    :  "id_production=" + id_production + "&nama=" + nama + "&kategori=" + kategori + "&type=" + type + "&satuan=" + satuan + "&qty=" + qty,
                  success : function(data){ 
                  var url = "<?php// echo base_url(); ?>" + "c_new/loadLT/" + id_production;
                       $('#tableLT').load(url,'refresh');
                       $('#namaLT').val('');
                       $('#qtyLT').val('');
                       
                  }});
        return false; //agar ga ke refresh 
    });
    */






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
        
        totalLT();
        if(kategori == 'START/STOP')
        {
            totalStartStop(qty);
        }
        GrossNett(); 
        $('.formLT').val('');
        /*
        var id_production = $('#id_production').val();
        $.ajax({
                  type    : "POST",
                  url     : "<?php// echo base_url(); ?>index.php/c_new/add_table_lt/t_production_op_detail",
                  data    :  "detail[0][id_production]=" + id_production, 
                  success : function(data){ 
                     var url = "<?php// echo base_url(); ?>" + "index.php/c_new/loadLT/" + id_production;
                       $('#tableLT').load(url,'refresh');
                  }});
                  */
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

<script>
$(document).ready(function() {
  // Add cutting tool
  $('#add_cutting_tool_btn').click(function() {
    var select = $('#cutting_tool_select');
    var id = select.val();
    var code = select.find('option:selected').text();
    if (!id) return;
    // Prevent duplicate
    if ($('#cutting_tools_table tbody tr[data-id="'+id+'"], input[name="cutting_tools_ids[]"][value="'+id+'"]').length) return;
    var rowCount = $('#cutting_tools_table tbody tr').length + 1;
    var row = '<tr data-id="'+id+'">'+
      '<td>'+rowCount+'</td>'+
      '<td>'+code+'</td>'+
      '<td><button type="button" class="btn btn-danger btn-sm remove-tool">Hapus</button>'+
      '<input type="hidden" name="cutting_tools_ids[]" value="'+id+'"></td>'+
      '</tr>';
    $('#cutting_tools_table tbody').append(row);
    select.val('');
  });
  // Remove cutting tool
  $('#cutting_tools_table').on('click', '.remove-tool', function() {
    $(this).closest('tr').remove();
    // Re-number rows
    $('#cutting_tools_table tbody tr').each(function(idx) {
      $(this).find('td:first').text(idx+1);
    });
  });
});
</script>
