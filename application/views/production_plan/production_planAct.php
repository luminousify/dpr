 <title>DPR | Production Plan <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>

    <link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

    <script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 

    <script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'></script>


<!-- Script -->
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
<script>
  $(document).ready(function(){
            $('.autocompleteProductEdit').autocomplete({
                source: "<?php echo site_url('c_production_plan/get_autocompleteEdit');?>",
                select: function (event, ui) {
                    $('#nama_produk_edit').val(ui.item.nama_product); 
                    $('#material_name_edit').val(ui.item.nama_product_release); 
                    $('#ct_mesin_edit').val(ui.item.cyt_mc);                 
                }

            });
  });

  $(document).ready(function(){
            $('.autocompleteMesinEdit').autocomplete({
                source: "<?php echo site_url('c_production_plan/get_autocompleteMesinEdit');?>",
                select: function (event, ui) {
                    $('#tonnase_edit').val(ui.item.tonnase);             
                }

            });
  });

</script>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?= $action; ?> Production Plan</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
    <div class="table-responsive">
     <?php echo form_open('c_production_plan/'.$action.'/prod_plan_harian/production_plan'); ?> 
     <!-- ke function add / nama_table / redirect kemana -->
      
            <?php  
            $posisi = $this->session->userdata('posisi');
            if ($posisi == 'ppic') { ?>
              <div class="card rounded">
                <div class="card-header">
                  <h3>PPIC</h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                      <thead>
                        <tr>
                          <td style="background:#1ab394;color: white;text-align: center;">Date</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Code</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Name</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Material Name</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Qty</td>
                          <td style="background:#1ab394;color: white;text-align: center;">MC No.</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Tonnage MC</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Cycle Time</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Schedule Jam</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Target</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Group</td>
                          <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                        </tr>
                      </thead>
                      
                      <tbody>
                        <?php if($action ==  'Edit') {
                          foreach($data_tabel->result() as $data):                     
                          echo '<tr>';
                          echo '<td><input type="date" name="user[0][tanggal]" value="'.$data->tanggal.'" class="form-control"><input type="hidden" name="id" value="'.$data->id.'"><input type="hidden" name="where" value="id"></td>';
                          echo '<td><input type="search form-control" name="user[0][kode_produk]" value="'.$data->kode_produk.'" class="autocompleteProductEdit">';
                          echo '<td><input type="text" name="user[0][nama_produk]" id="nama_produk_edit" value="'.$data->nama_produk.'" class="form-control" readonly>';
                          echo '<td><input type="text" name="user[0][material_name]" id="material_name_edit" value="'.$data->material_name.'" class="form-control" readonly>';
                          echo '<td><input type="text" name="user[0][prod_qty]" value="'.$data->prod_qty.'" class="form-control">';
                          echo '<td><input type="search form-control" name="user[0][no_mesin]" value="'.$data->no_mesin.'" class="autocompleteMesinEdit">';
                          echo '<td><input type="text" id="tonnase_edit" name="user[0][tonnase]" value="'.$data->tonnase.'" class="form-control" readonly>';
                          echo '<td><input type="text" name="user[0][ct_mesin]" id="ct_mesin_edit" value="'.$data->ct_mesin.'" 
                          class="form-control" readonly>';
                          echo '<td><input type="time" name="user[0][jam]" value="'.$data->jam.'" 
                          class="form-control">';
                          echo '<td><input type="time" name="user[0][target]" value="'.$data->target.'" 
                          class="form-control">';
                          ?>
                          <td><select class="form-control" name="user[0][group]" style="width:70px;">
                            <option <?php if($data->group =='A'){echo "selected"; } ?> value='A'>A</option>
                            <option <?php if($data->group =='B'){echo "selected"; } ?> value='B'>B</option>
                            <option <?php if($data->group =='C'){echo "selected"; } ?> value='C'>C</option>
                          </td>
                          <?php
                          echo '</tr>';
                          endforeach;
                        } ?>
                      </tbody>
                    </table>
                  </select>
                </div>
              </div>
              <br>
              <div class="card rounded">
                <div class="card-header">
                  <h3>TM</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered stripe row-border order-column table-responsive" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Material Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Material Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Mold Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Mold Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Setting Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Setting Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Masalah</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC (TM)</td>
                        <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($action ==  'Edit') {
                        foreach($data_tabel->result() as $data):
                        echo '<tr>';
                        echo '<td><input type="time" value="'.$data->set_up_material_start.'" class="form-control" readonly></td>';
                        echo '<td><input type="time" value="'.$data->set_up_material_finish.'" class="form-control" readonly></td>';
                        echo '<td><input type="time" value="'.$data->set_up_mold_start.'" class="form-control" readonly>';
                        echo '<td><input type="time" value="'.$data->set_up_mold_finish.'" class="form-control" readonly>';
                        echo '<td><input type="time" value="'.$data->set_up_setting_start.'" class="form-control" readonly>';
                        echo '<td><input type="time" value="'.$data->set_up_start_setting_finish.'" class="form-control" readonly>';
                        echo '<td><input type="text" value="'.$data->masalah.'" class="form-control" readonly>';
                        echo '<td><input type="text" value="'.$data->pic_tm.'" class="form-control" readonly>';
                        echo '</tr>';
                          endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br>
              <div class="card rounded">
                <div class="card-header">
                  <h3>QA</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Judgement QA Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC (QA)</td>
                        <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($action ==  'Edit') {
                        foreach($data_tabel->result() as $data):
                        echo '<tr>';
                        echo '<td><input type="time" value="'.$data->judgement_qa_finish.'" class="form-control" readonly>';
                        echo '<td><input type="text" value="'.$data->pic_qa.'" class="form-control" readonly>';
                        echo '</tr>';
                          endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>

            <?php  } else if ($posisi == 'tm') { ?>
              <div class="card rounded">
                <div class="card-header">
                  <h3>PPIC</h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                      <thead>
                        <tr>
                          <td style="background:#1ab394;color: white;text-align: center;">Date</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Code</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Name</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Material Name</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Qty</td>
                          <td style="background:#1ab394;color: white;text-align: center;">MC No.</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Tonnage MC</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Cycle Time</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Schedule Jam</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Target</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Group</td>
                          <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($action ==  'Edit') {
                          foreach($data_tabel->result() as $data):
                          echo '<tr>';
                          echo '<td><input type="date" value="'.$data->tanggal.'" class="form-control" readonly></td>';
                          echo '<td><input type="text" value="'.$data->kode_produk.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->nama_produk.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->material_name.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->prod_qty.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->no_mesin.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->tonnase.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->ct_mesin.'" class="form-control" readonly>';
                          echo '<td><input type="time" value="'.$data->jam.'" class="form-control" readonly>';
                          echo '<td><input type="time" value="'.$data->target.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->group.'" class="form-control" readonly>';
                          echo '</tr>';
                            endforeach;
                        } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <br>
              <div class="card rounded">
                <div class="card-header">
                  <h3>TM</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered stripe row-border order-column table-responsive" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Material Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Material Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Mold Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Mold Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Setting Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Setting Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Masalah</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC (TM)</td>
                        <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($action ==  'Edit') {
                        $nama = $this->session->userdata('nama_actor');
                        foreach($data_tabel->result() as $data):
                        echo '<tr>';
                        echo '<td><input type="time" name="user[0][set_up_material_start]" value="'.$data->set_up_material_start.'" class="form-control">';
                        echo '<td><input type="time" name="user[0][set_up_material_finish]" value="'.$data->set_up_material_finish.'" class="form-control"><input type="hidden" name="id" value="'.$data->id.'"><input type="hidden" name="where" value="id"></td>';
                        echo '<td><input type="time" name="user[0][set_up_mold_start]" value="'.$data->set_up_mold_start.'" class="form-control">';
                        echo '<td><input type="time" name="user[0][set_up_mold_finish]" value="'.$data->set_up_mold_finish.'" class="form-control">';
                        echo '<td><input type="time" name="user[0][set_up_setting_start]" value="'.$data->set_up_setting_start.'" class="form-control">';
                        echo '<td><input type="time" name="user[0][set_up_start_setting_finish]" value="'.$data->set_up_start_setting_finish.'" class="form-control">';
                        echo '<td><input type="text" name="user[0][masalah]" value="'.$data->masalah.'" class="form-control">';
                        echo '<td><input type="text" name="user[0][pic_tm]" value="'.$nama.'" class="form-control">';
                        echo '</tr>';
                          endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br>
              <div class="card rounded">
                <div class="card-header">
                  <h3>QA</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Judgement QA Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC (QA)</td>
                        <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($action ==  'Edit') {
                        foreach($data_tabel->result() as $data):
                        echo '<tr>';
                        echo '<td><input type="time" value="'.$data->judgement_qa_finish.'" class="form-control" readonly>';
                        echo '<td><input type="text" value="'.$data->pic_qa.'" class="form-control" readonly>';
                        echo '</tr>';
                          endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php } else { ?>
              <div class="card rounded">
                <div class="card-header">
                  <h3>PPIC</h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                      <thead>
                        <tr>
                          <td style="background:#1ab394;color: white;text-align: center;">Date</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Code</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Name</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Material Name</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Prod. Qty</td>
                          <td style="background:#1ab394;color: white;text-align: center;">MC No.</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Tonnage MC</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Cycle Time</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Schedule Jam</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Target</td>
                          <td style="background:#1ab394;color: white;text-align: center;">Group</td>
                          <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($action ==  'Edit') {
                          foreach($data_tabel->result() as $data):
                          echo '<tr>';
                          echo '<td><input type="date" value="'.$data->tanggal.'" class="form-control" readonly></td>';
                          echo '<td><input type="text" value="'.$data->kode_produk.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->nama_produk.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->material_name.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->prod_qty.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->no_mesin.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->tonnase.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->ct_mesin.'" class="form-control" readonly>';
                          echo '<td><input type="time" value="'.$data->jam.'" class="form-control" readonly>';
                          echo '<td><input type="time" value="'.$data->target.'" class="form-control" readonly>';
                          echo '<td><input type="text" value="'.$data->group.'" class="form-control" readonly>';
                          echo '</tr>';
                            endforeach;
                        } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <br>
              <div class="card rounded">
                <div class="card-header">
                  <h3>TM</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered stripe row-border order-column table-responsive" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Material Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Material Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Mold Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Mold Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Setting Start</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Set Up Setting Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">Masalah</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC (TM)</td>
                        <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($action ==  'Edit') {
                        foreach($data_tabel->result() as $data):
                        echo '<tr>';
                        echo '<td><input type="time" value="'.$data->set_up_material_start.'" class="form-control" readonly></td>';
                        echo '<td><input type="time" value="'.$data->set_up_material_finish.'" class="form-control" readonly></td>';
                        echo '<td><input type="time" value="'.$data->set_up_mold_start.'" class="form-control" readonly>';
                        echo '<td><input type="time" value="'.$data->set_up_mold_finish.'" class="form-control" readonly>';
                        echo '<td><input type="time" value="'.$data->set_up_setting_start.'" class="form-control" readonly>';
                        echo '<td><input type="time" value="'.$data->set_up_start_setting_finish.'" class="form-control" readonly>';
                        echo '<td><input type="text" value="'.$data->masalah.'" class="form-control" readonly>';
                        echo '<td><input type="text" value="'.$data->pic_tm.'" class="form-control" readonly>';
                        echo '</tr>';
                          endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br>
              <div class="card rounded">
                <div class="card-header">
                  <h3>QA</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered stripe row-border order-column" rules="all" id="customFields" style="width: 100%">
                    <thead>
                      <tr>
                        <td style="background:#1ab394;color: white;text-align: center;">Judgement QA Finish</td>
                        <td style="background:#1ab394;color: white;text-align: center;">PIC (QA)</td>
                        <td style="background:#1ab394;color: white;text-align: center;"><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($action ==  'Edit') {
                        $nama = $this->session->userdata('nama_actor');
                        foreach($data_tabel->result() as $data):
                        echo '<tr>';
                        echo '<td><input type="time" name="user[0][judgement_qa_finish]" value="'.$data->judgement_qa_finish.'" class="form-control"><input type="hidden" name="id" value="'.$data->id.'"><input type="hidden" name="where" value="id"></td>';
                        echo '<td><input type="text" name="user[0][pic_qa]" value="'.$nama.'" class="form-control">';
                        echo '</tr>';
                          endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php }              
        ?>

        <div class="col-sm12 d-flex justify-content-center">
          <input type="submit" name="simpan" value="<?= $action; ?>" class="btn btn-success mt-4">
        </div>
      <?php echo form_close(); ?>



    </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

        </div>
        </div>






<!-- <?php //$this->load->view('layout/footer'); ?> -->

    <!-- Page-Level Scripts -->
  <script>
  //Action Add
  var save = -1;
  var i = -1;
  function addMoreRows(frm) {
  i++
  save++;
  var recRow    = '<tr><td><input type="date" name="user['+save+'][tanggal]" class="form-control" value="<?php echo date('Y-m-d') ?>"</td>' +
    '<td><input type="search form-control" name="user['+save+'][kode_produk]" class="autocompleteProduct" id="id_bom"></td>' +
    '<td><input type="text" name="user['+save+'][nama_produk]" id="nama_produk' + i + '" class="form-control" style="width:100%" readonly></td>' +
    '<td><input type="text" name="user['+save+'][material_name]" id="material_name' + i + '" class="form-control" readonly></td>' +
    '<td><input type="text" name="user['+save+'][prod_qty]" class="form-control"></td>' +
    '<td><input type="search form-control" name="user['+save+'][no_mesin]" class="autocompleteMesin"></td>' +
    '<td><input type="text" id="tonnase' + i + '" name="user['+save+'][tonnase]" class="form-control" readonly></td>' +
    '<td><input type="text" name="user['+save+'][ct_mesin]" id="ct_mesin' + i + '" class="form-control" readonly></td>' +
    '<td><input type="time" name="user['+save+'][jam]" class="form-control"><input type="hidden" name="user['+save+'][divisi]" class="form-control" value="<?= $data['bagian']; ?>"</td>' +
    '<td><input type="time" name="user['+save+'][target]" class="form-control"></td>' +
    '<td><select class="form-control" name="user['+save+'][group]" style="width:70px;"><option value="A">A</option><option value="B">B</option><option value="C">C</select></td>' +
    '<td><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></p></td><tr/>';

  $(document).ready(function(){
            $('.autocompleteProduct').autocomplete({
                source: "<?php echo site_url('c_production_plan/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#nama_produk'+i ).val(ui.item.nama_product); 
                    $('#material_name'+i).val(ui.item.nama_product_release); 
                    $('#ct_mesin'+i).val(ui.item.cyt_mc);                 
                }

            });
  });
  $(document).ready(function(){
            $('.autocompleteMesin').autocomplete({
                source: "<?php echo site_url('c_production_plan/get_autocompleteMesin');?>",
                select: function (event, ui) {
                    $('#tonnase'+i).val(ui.item.tonnase);               
                }
            });
  });
  jQuery('#customFields').append(recRow);
  }

  $("#customFields").on('click','.remCF',function(){
          $(this).parent().parent().remove();
  });

  

  
</script>

    
