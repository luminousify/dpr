 <title>DPR | Master Work Days <?= $action; ?></title>
 <?php $this->load->view('layout/sidebar'); ?>



 <div class="wrapper wrapper-content animated fadeInRight">
   <div class="row">
     <div class="col-lg-12">
       <div class="ibox ">
         <div class="ibox-title">
           <h5><?= $action; ?> Master Data Work Days</h5>
           <div class="ibox-tools">
             <a class="collapse-link">
               <i class="fa fa-chevron-up"></i>
             </a>
           </div>
         </div>
         <div class="ibox-content">
           <div class="table-responsive">
             <?php echo form_open('c_new/' . $action . '/year_day/master_work_days'); ?>
             <!-- ke function add / nama_table / redirect kemana -->
             <table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
               <tr style="background:#1ab394;color: white;text-align: center;">
                 <td>Tahun</td>
                 <td>Bulan</td>
                 <td>Working Days</td>
                 <td>Daily WH</td>
                 <td>Availabe Capacity</td>
                 <td>Number of machines</td>
                 <td><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
               </tr>
               <?php if ($action ==  'Edit') {
                  foreach ($data_tabel->result() as $data):
                    echo '<tr>';
                    echo '<td><input type="text" name="user[0][tahun]" value="' . $data->tahun . '" class="form-control" id="tahun" readonly><input type="hidden" name="id" value="' . $data->id . '"><input type="hidden" name="where" value="id"></td>';
                    echo '<td><select name="user[0][bulan]" class="form-control" id="bulan" onchange="getTahunBulan();"><option value="">Pilih Bulan</option><option value="1">01<option value="02">02<option value="3">03<option value="4">04<option value="5">05<option value="6">06<option value="7">07<option value="8">08<option value="9">09<option value="10">10<option value="11">11<option value="12">12</select>';
                    echo '<td><input type="text" name="user[0][working_day]" id="working_day" onkeyup= getAvailCapacity(); value="' . $data->working_day . '" class="form-control"></td>';
                    echo '<td><input type="text" name="user[0][daily_wh]" id="daily_wh" value="' . $data->daily_wh . '" class="form-control" readonly><input type="text" name="user[0][tahun_bulan]" class="form-control" id="tahun_bulan" readonly  >';
                    echo '<td><input type="text" name="user[0][total]" id="avail_capacity" value="' . $data->total . '" class="form-control" readonly></td>';
                    echo '<td><input type="text" name="user[0][jumlah_mesin]" id="jumlah_mesin" value="' . $data->jumlah_mesin . '" class="form-control" ></td>';
                    echo '</tr>';
                  endforeach;
                } ?>
             </table>

             <input type="submit" name="simpan" value="<?= $action; ?>" class="btn btn-success">
             <?php echo form_close(); ?>



           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

 </div>
 </div>






 <?php $this->load->view('layout/footer'); ?>

 <!-- Page-Level Scripts -->
 <script>
   var save = -1;

   function addMoreRows(frm) {
     save++;
     var recRow = '<tr><td><input type="text" name="user[' + save + '][tahun]" id="tahun" class="form-control" value="<?php echo date('Y') ?>"></td>' +
       '<td><select name="user[' + save + '][bulan]" class="form-control" id="bulan" onchange="getTahunBulan();"><option value=""></option><option value="1">01<option value="2">02<option value="3">03<option value="4">04<option value="5">05<option value="6">06<option value="7">07<option value="8">08<option value="9">09<option value="10">10<option value="11">11<option value="12">12</select></td>' +
       '<td><input type="text" name="user[' + save + '][working_day]" class="form-control" id="working_day" onkeyup= getAvailCapacity();></td>' +
       '<td><input type="text" name="user[' + save + '][daily_wh]" class="form-control" id="daily_wh" value="21" readonly  ><input type="hidden" name="user[' + save + '][tahun_bulan]" class="form-control" id="tahun_bulan" readonly  ></td>' +
       '<td><input type="text" name="user[' + save + '][total]" class="form-control" id="avail_capacity" readonly ></td>' +
       '<td><input type="text" name="user[' + save + '][jumlah_mesin]" class="form-control" id="jumlah_mesin"  ></td>' +
       '<td><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></p></td><tr/>';
     jQuery('#customFields').append(recRow);
   }

   $("#customFields").on('click', '.remCF', function() {
     $(this).parent().parent().remove();
   });

   function keyupct(ct) {
     var ct_mc = parseFloat($('#ct_mc' + ct).val());
     var ct_mp = parseFloat($('#ct_mp' + ct).val());
     var hitung_jam = Math.floor(3600 / (ct_mc + ct_mp));
     $('#tg_jam' + ct).val(hitung_jam);
     var hitung_shift = hitung_jam * 7;
     $('#tg_shift' + ct).val(hitung_shift);
   }

   function getAvailCapacity() {
     var working_day = $('#working_day').val();
     var daily_wh = $('#daily_wh').val();
     var hitung_total = working_day * daily_wh;
     $('#avail_capacity').val(hitung_total);
   }

   function getTahunBulan() {
     var tahun = $('#tahun').val();
     var bulan = $('#bulan').val();
     var tahunBulan = tahun + '-' + bulan;
     $('#tahun_bulan').val(tahunBulan);
   }
 </script>