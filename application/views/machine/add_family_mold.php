<title>DPR | Family Mold <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>

<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">


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
                        <h5><?= $action; ?> Family Mold</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
        <form action="<?php echo site_url('c_machine/add_fm')?>" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Tanggal</b></label>
                    <input type="date" name="user[0][tanggal]" class="form-control" onchange="getNWT()" id="tgl_input" required="">
                </div>
            </div>
            <div class="col-md-4">
            <div class="form-group">
                    <label><b></b></label><br/><br/>
            
            </div>    
        </div>
        </div>
        <hr>
    <div class="table-responsive">
     
     <!-- ke function add / nama_table / redirect kemana -->
    	<table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
            <thead>
                <tr>
                    <th style="width:70px;">No. MC</th>
                    <th style="width:50px;"> Ton</th>
                    <th >Shift</th>
                    <th >Group</th>
                    <th style="width:100px;">Nama BOM</th>
                    <th style="width:80px;">CT Running</th>
                    <th style="width:100px;">Operator</th>
                    <th style="width:70px;">Man Power</th>
                    <th style="width:80px;">Auto / Semi</th>
                    <th style="width:150px;">Ket.</th>
                    <th style="width:110px;">Run</th>
                    <th style="width:110px;">Line</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control" name="" id="machine_ton" onchange="getTonase(this.value)" style="width: 100%";></select>
                    </td>
                    <td>
                        <input type="text" name="user[0][tonnase]" id="tonase" class="form-control" style="width: 50px"; readonly>
                        <input type="hidden" name="user[0][no_mesin]" id="no_mesin" class="form-control">
                    </td>
                    <td>
                        <select name="user[0][shift]" class="form-control" required="" style="width: 110px;">
                            <option value="">-Choose-</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                        </select>
                    </td>
                    <td>
                        <select name="user[0][group]" id="group" class="form-control" style="width: 110px"; required=""  >
                            <option value="">-Choose-</option>
                            <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
                        </select>
                    </td>
                    <td>
                        <input type="text " name="" class="form-control autocompleteBom" required="" id="id_bom" > 
                        <input type="hidden" name="user[0][id_bom]" id="id_bomS" class="form-control" readonly>
                        <input type="hidden" name="user[0][cavity]" id="cavity" class="form-control">
                        <input type="hidden" name="user[0][ct_std]" id="ct_mc" class="form-control">
                    </td>
                    <td><input type="text" class="form-control" name="user[0][ct_running]" onkeyup="ubahBom()">
                        <input type="hidden" name="" id="hari" class="form-control">
                        <input type="hidden" name="" id="nwt" class="form-control">
                        <input type="hidden" name="user[0][target]" id="target_mc" class="form-control">
                        <input type="hidden" name="user[0][target_ppic]" id="target_ppic" class="form-control">
                    </td>
                    <td><input type="text" class="form-control" name="user[0][operator]" id="operator0"></td>
                    <td><input type="text" name="user[0][man_power]" class="form-control" value="0.5"></td>
                    <td>
                        <select name="user[0][jenis_mesin]" class="form-control">
                            <option value="">-</option>
                            <option value="AUTO" selected>AUTO</option>
                            <option value="SEMI">SEMI</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="user[0][keterangan]" style="width: 150px;"></td>
                    <td>
                        <select name="user[0][running]" class="form-control" style="width: 110px;">
                            <option value="1">Running</option>
                            <option value="0">Off</option>
                        </select>
                    </td>
                    <td>
                        <select name="user[0][line]" class="form-control" style="width: 110px;">
                            <option value="">-Choose-</option>
                            <?php foreach ($lines as $line_value) { echo "<option value='$line_value'>$line_value</option>";}?>
                        </select>
                    </td>
                </tr>
            </tbody>
      </table>




    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-5">

        </div>
                     <div class="col-1">
                        <div class="form-group d-flex justify-content-center">
                            <input type="submit" id="btn-add" name="simpan" value="<?= $action; ?>" class="btn btn-success">
                         
                        </div>
                     </div>
                     <div class="col-4">

</div>
                </div>
      
    </form>
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
        function getNWT(){
            const days = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
            var tgl_input = $('#tgl_input').val();
            const d = new Date(tgl_input);
            let day = days[d.getDay()];
            //alert(day);
            
            if (day == 'Sabtu' || day == 'Minggu') {
                $('#hari').val(day);
                $('#nwt').val(5);
            }
            else{
                $('#hari').val(day);
                 $('#nwt').val(8);
            }
        }
        $(document).ready(function(){
            $('.autocompleteBom').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    $('#id_bomS').val(ui.item.id_bom);
                    $('#ct_mc').val(ui.item.cyt_mc_bom);
                    $('#cavity').val(ui.item.cavity_product); 

                    //hitung target
                    var nwt = $('#nwt').val();
                    var target = ((3600/ui.item.cyt_mc_bom)*(ui.item.cavity_product*nwt));  
                    $('#target_mc').val(target.toFixed(2));                              
                }

            });
        });


        $(document).ready(function(){
              $("#machine_ton").select2({
                 ajax: { 
                   url: '<?= base_url() ?>c_machine/getTonaseMachine',
                   type: "post",
                   dataType: 'json',
                   delay: 250,
                   data: function (params) {
                      return {
                        searchTerm: params.term // search term
                      };
                   },
                   processResults: function (response) {
                      return {
                         results: response
                      };
                   },
                   cache: true
                 }
             });
           });

        function getTonase(value){
            $('#tonase').val($('#machine_ton').val());
            $('#no_mesin').val($('#machine_ton').text());
            //$('#no_mesin').val($('#machine_ton').text());
        }

        function bindOperatorAutocomplete(x) {
            var $op = $('#operator' + x);
            if (!$op.length) return;
            // prevent double-binding when called multiple times
            if ($op.data('uiAutocompleteBound')) return;
            $op.data('uiAutocompleteBound', true);

            $op.autocomplete({
                source: "<?php echo site_url('c_operator/get_autocompleteOperator');?>",
                minLength: 1,
                select: function (event, ui) {
                    if (ui && ui.item && ui.item.value) {
                        $op.val(ui.item.value);
                    }
                    return false;
                }
            });
        }

        $(document).ready(function(){
            bindOperatorAutocomplete(0);
        });

    </script>
