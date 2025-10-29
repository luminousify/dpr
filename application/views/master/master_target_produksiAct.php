 <title>DPR | Master Target Produksi <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>


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
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?= $action; ?> Master Data Target Produksi</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
    <div class="table-responsive">
     <?php echo form_open('c_new/'.$action.'/target_produksi/master_target_produksi'); ?> 
     <!-- ke function add / nama_table / redirect kemana -->
    	<table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
        <tr style="background:#1ab394;color: white;text-align: center;">
          <td>Tahun</td>
          <td>Target Prod. Nett</td>
          <td>Target Prod. Gross</td>
          <td><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
        </tr>
        <?php if($action ==  'Edit') {
        	foreach($data_tabel->result() as $data):
        	echo '<tr>';
        	echo '<td><input type="text" name="user[0][tahun]" value="'.$data->tahun.'" class="form-control" id="tahun" readonly><input type="hidden" name="id" value="'.$data->id.'"><input type="hidden" name="where" value="id"></td>';
			    echo '<td><input type="text" name="user[0][target_nett]" id="target_nett" value="'.$data->target_nett.'" class="form-control">';
			    echo '<td><input type="text" name="user[0][target_gross]" id="target_gross" value="'.$data->target_gross.'" class="form-control"></td>';
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
	var recRow    = '<tr><td><input type="text" name="user['+save+'][tahun]" id="tahun" class="form-control" value="<?php echo date('Y') ?>" readonly></td>' +
		'<td><input type="text" name="user['+save+'][target_nett]" class="form-control" id="target_nett" ></td>' +
    	'<td><input type="text" name="user['+save+'][target_gross]" class="form-control" id="target_gross"></td>' +
		'<td><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></p></td><tr/>';
	jQuery('#customFields').append(recRow);
	}

	$("#customFields").on('click','.remCF',function(){
	        $(this).parent().parent().remove();
	    });

  function keyupct(ct)
  {
    var ct_mc       = parseFloat($('#ct_mc'+ct).val());
    var ct_mp       = parseFloat($('#ct_mp'+ct).val());
    var hitung_jam  = Math.floor(3600 / (ct_mc + ct_mp));
    $('#tg_jam'+ct).val(hitung_jam);
    var hitung_shift = hitung_jam * 7;
    $('#tg_shift'+ct).val(hitung_shift);
  }

  function getTahunBulan()
  {
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();
    var tahunBulan  = tahun+'-'+bulan;
    $('#tahun_bulan').val(tahunBulan);
  }


    </script>

    
