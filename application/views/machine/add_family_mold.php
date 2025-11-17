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
                            <option value='ABDUL BASIR B W'>ABDUL BASIR B W</option>
                            <option value='AGUS MARTANTO'>AGUS MARTANTO</option>
                            <option value='AGUS SALIM'>AGUS SALIM</option>
                            <option value='ALBERTO HUTABARAT'>ALBERTO HUTABARAT</option>
                            <option value='ASROFI'>ASROFI</option>
                            <option value='MARIDIN'>MARIDIN</option>
                            <option value='MITUHU'>MITUHU</option>
                            <option value='RIDWAN EFENDI'>RIDWAN EFENDI</option>
                            <option value='SUKIRMAN'>SUKIRMAN</option>
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
                    <td><input type="text" class="form-control" name="user[0][operator]"></td>
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
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
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
                     <div class="col-2">
                     <a class="btn btn-danger" style="color:white" onclick="checkDataProdPlan()">Check </a><b><h5> Note : cek schedule ppic terlebih dahulu sebelum melanjutkan proses berikutnya!</b></h5>
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
            $('#btn-add').prop('disabled', true)

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

        function checkDataProdPlan()
    {
        $('#btn-add').prop('disabled', false)
        //current
        var tgl_input = $('#tgl_input').val();
        var shift_input = $('#shift').val();
        var group_input = $('#group').val();
        
        // console.log(tgl_input);
        // console.log(shift_input);
        // console.log(group_input);
        // var res = bom.substring(0, 3);
        

        if(group_input != ''){
            $.ajax({
                      type    : "POST",
                      url     : "<?php echo site_url('c_operator/check_prod_plan');?>",
                      data    : "date=" + tgl_input,
                      
                      success : function(data){
    
                        
            var jsonData = JSON.parse(data);
            var kp_pr = jsonData[0].kp_pr;
            var no_mesin = jsonData[0].no_mesin;
            var kode_produk = jsonData[0].kode_produk;
            var material_name = jsonData[0].material_name;
            var prod_qty = jsonData[0].prod_qty;
            var cavity = jsonData[0].cavity;
            var id_bom = jsonData[0].id_bom;
            var cyt_mc = jsonData[0].cyt_mc;
            if(no_mesin === $('#no_mesin').val()){
                    $('#id_bom').val(kp_pr)
                    $('#id_bomS').val(id_bom);
                    $('#ct_mc').val(cyt_mc);
                    $('#cavity').val(cavity);
                    var nwt = $('#nwt').val();
                    var target = prod_qty;
                    $('#target_ppic' ).val(target);
                    console.log( $('#id_bomS').val())
                    console.log( $('#ct_mc').val())
                    console.log( $('#cavity').val())
                    console.log( $('#target_mc').val())
                    console.log( $('#target_ppic').val())
                    alert("Production Plan Data Found!");
                }
                else{
                    alert("Production Plan Not Found!");
                } 
       
                         
                    }});
        }
        else{
           
        }
        // $.ajax({
        //               type    : "POST",
        //               url     : "<?php echo site_url('c_operator/getdatabomMesinDPR');?>",
        //               data    : "id_bom=" + id_bom,
        //               success : function(data){
        //                   console.log(data);
        //             }});

        //  var nama     = $("#namaNG").val();
        //  var kategori = $('#kategoriNG').val();
        //  var type     = $('#typeNG').val();
        //  var satuan   = $('#satuanNG').val();
        //  var qty      = $('#qtyNG').val();
        //  var markup = "<tr><td><input type='button' value='X'></td><td>"+sv+"</td>"+
        //  "<td><input type='hidden' name='detail["+save+"][nama]' value='"+nama+"'/>"+nama+"</td>"+
        //  "<td><input type='hidden' name='detail["+save+"][kategori]' value='"+kategori+"''>"+kategori+"</td>"+
        //  "<td><input type='hidden' name='detail["+save+"][qty]' value="+qty+" class='nilai'>"+qty+"</td>"+
        //  "<td><input type='hidden' name='detail["+save+"][satuan]' value="+satuan+">"+satuan+"<input type='hidden' name='detail["+save+"][type]' value="+type+"></td>"+
        //  "</tr>";
        //  $('.formNG').val('');
    }

    </script>
