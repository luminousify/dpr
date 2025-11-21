<title>DPR | Master Product <?= $action; ?></title>
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
                        <h5><?= $action; ?> Master Data Product</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
    <?php if ($this->session->flashdata('gagal')): ?>
        <div class="alert alert-danger mt-2" style="text-align: center;font-size: 12px" role="alert">
            <?php echo $this->session->flashdata('gagal'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('tambah')): ?>
        <div class="alert alert-success mt-2" style="text-align: center;font-size: 12px" role="alert">
            <?php echo $this->session->flashdata('tambah'); ?>
        </div>
    <?php endif; ?>
    <div class="table-responsive">
     <?php echo form_open('c_new/'.$action.'/t_product/master_product'); ?> 
     <!-- ke function add / nama_table / redirect kemana -->
    	<table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
        <tr style="background:#1ab394;color: white;text-align: center;">
          <td>Kode Product</td>
          <td>Nama Product</td>
          <td>Mom ID</td>
          <td>Mom Product</td>
          <td>Acc ID</td>
          <td>Acc Inv</td>
          <td>Kode Proses</td>
          <td>Nama Proses</td>
          <td>Usage</td>
          <td>Cavity</td>
          <td>Satuan</td>
          <td>Type</td>
          <td>Cyt MC</td>
          <td>Cyt Quo</td>
          <td>Customer</td>
          <td>Cost</td>
          <td><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
        </tr>
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
	var recRow    = '<tr><td><input type="text" name="user['+save+'][kode_product]" class="form-control"  ></td>' +
		'<td><input type="text" name="user['+save+'][nama_product]" class="form-control"  > <input type="hidden" name="user['+save+'][divisi]" class="form-control" value="<?= $data['bagian']; ?>"</td>' +
        '<td><input type="text" name="user['+save+'][MomID]" class="form-control"  ></td>' +
        '<td><input type="text" name="user['+save+'][MomProduct]" class="form-control"  ></td>' +
        '<td><input type="text" name="user['+save+'][AccID]" class="form-control"  ></td>' +
        '<td><input type="text" name="user['+save+'][AccInv]" class="form-control"  ></td>' +
		'<td><input type="text" name="user['+save+'][kode_proses]" class="form-control"  ></td>' +
		'<td><select name="user['+save+'][nama_proses]" style="width:100px;" class="form-control"  ><option value=""></option><option value="RMW L">RMW L</option><option value="RMW L AS">RMW L AS</option><option value="P1">P1</option><option value="P2">P2</option><option value="P3">P3</option><option value="P4">P4</option><option value="P5">P5</option><option value="P6">P6</option><option value="PLATING">PLATING</option><option value="FINAL">FINAL</option><option value="ASSY">ASSY</option><option value="BARREL">BARREL</option><option value="WASHING">WASHING</option><option value="STAMPING">STAMPING</option><option value="INSPECTION">INSPECTION</option>><option value="BG">BABY GRIND</option></select></td>' +
        '<td><input type="text" name="user['+save+'][usage]" value="0" class="form-control"   /></td>' +
        '<td><input type="text" name="user['+save+'][cavity]" class="form-control"   /></td>' +
        '<td><input type="text" name="user['+save+'][sp]" class="form-control"   /></td>' +
		'<td><select name="user['+save+'][type]" style="width:80px;" class="form-control"  ><option value="RMW">RMW</option><option value="WIP">WIP</option><option value="FG">FG</option></select></td>' +
        '<td><input type="text" name="user['+save+'][cyt_mc]" class="form-control"   /></td>' +
        '<td><input type="text" name="user['+save+'][cyt_quo]" class="form-control"   /></td>' +
        '<td><input type="text" name="user['+save+'][customer]" class="form-control"   /></td>' +
        '<td><input type="number" name="user['+save+'][cost]" value="0" step="0.01" min="0" class="form-control" style="width:80px;" /></td>' +
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
    </script>

    
