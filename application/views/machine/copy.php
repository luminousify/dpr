 <title>DPR | Copy Op. Mesin</title>
<?php $this->load->view('layout/sidebar'); ?>

<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">

<script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 


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

<div class="ibox-title">
                        <h5>Copy Transaksi Tanggal : <?= $tanggal; ?> & Kanit : <?= $group; ?> & Line : <?= $line; ?> ( Tambah Data )</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="card rounded p-3">
                        
                            <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label><b>Tanggal</b></label>
                                    <input type="date" name="tanggal" class="form-control" id="tanggal" onchange="getNWT()" required="">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label><b>Shift</b></label>
                                    <select name="shift" class="form-control" id="shift" required="">
                                        <option value="">-Pilih Salah Satu-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label><b>Line</b></label>
                                    <select name="line" class="form-control" id="line" required="">
                                        <option value="">-Pilih Salah Satu-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                               <div class="form-group">
                                <label><b>Kanit</b></label>
                                <select name="group" class="form-control" required="" id="group" required="">
                                   <option value="">-Pilih Salah Satu-</option>
                                    <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
                                </select>
                               </div>
                            </div>
                            <div class="col mt-4">
                                <input type="button" name="" class="btn btn-warning" value="Cek Data" onclick="cekData(this.value)">
                            </div>
                            </div>
                            <div class="row ml-2">
                            <p><strong>*Catatan :</strong> <font style='color:blue;'><b>Silahkan pilih <strong>tanggal, shift & kanit</strong> terlebih dahulu lalu klik <strong>Cek Data</strong>.</font></p>
                        </div>
                        </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col">
                                 <p id="inites" style="text-align:center;"></p>
                            </div>
                        </div>
<input type="hidden" name="" id="tes">
<script type="text/javascript">

    $(document).ready(function(){
        $('#tesX').hide();
    });

    function cekData(id)
    {
        var tanggal = $('#tanggal').val();
        var shift   = $('#shift').val();
        var group   = $('#group').val();
        var line   = $('#line').val();
        $.ajax({
              type    : "POST",
              url     : "<?php echo site_url(); ?>/c_machine/cekData", 
              dataType: "JSON",
              data    : "tanggal=" + tanggal + "&shift=" + shift + "&group=" + group + "&line=" + line,
              success : function(response){
                var len = response.length;
                 if(len > 0){
                    $("#tes").val(response[0].id_machine_use)
                    $('#tesX').hide();
                    $('#inites').html("<font style='color:red;font-size:16px'><b>Data Sudah ada , jangan di input lagi yaa .. silahkan gunakan fitur edit jika terjadi perubahan</b></font>");
                 }
                 else
                {
                    $('#tesX').show();
                    $('#inites').html("")
                    $('#tanggalSave').val(tanggal);
                    $('#shiftSave').val(shift);
                    $('#groupSave').val(group);

                }
                


              }});
        //$("#tes").val(2);
    }

</script>
</div>
<form action="<?php echo site_url('c_machine/add')?>" method="post" id="my-form">
<div id="tesX">
    <input type="hidden" name="tanggal" id="tanggalSave">
    <input type="hidden" name="shift" id="shiftSave">
    <input type="hidden" name="group" id="groupSave">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <!-- <button class="btn btn-success" onclick="addMoreRows(this.form);">Tambah Data</button> -->
                        <br/>
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="customFields" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:70px;">MC</th>
                                    <th> Ton</th>
                                    <th >Nama BOM</th>
                                    <th style="width:auto;">CT Run</th>
                                    <th style="width:150px;">Operator</th>
                                    <th style="width:auto;">MP</th>
                                    <th style="width:80px;">Auto / Semi</th>
                                    <th style="width:150px;">Ket.</th>
                                    <th style="width:220px;">Run</th>
                                    <th style="width:100px;">Line</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; $id = -1; $no = 0 ; foreach($data_tabel->result_array() as $data)
                                {  $no++; $id++; ?>
                                <tr>
                                    <td><?= $data['no_mesin']; ?><input type="hidden" name="user[<?= $id; ?>][no_mesin]" value="<?= $data['no_mesin']; ?>"></td>
                                    <td><?= $data['tonnase']; ?><input type="hidden" name="user[<?= $id; ?>][tonnase]" value="<?= $data['tonnase']; ?>"></td>
                                    <td><input type="text " name="" class="form-control input<?= $id; ?>"  id="id_bom<?= $id; ?>" oninput="getDataItem(<?php echo $id ?>)" value="<?= $data['kp_pr'].'( '.$data['np_pr'].')'; ?>">
                                    <input type="hidden" name="user[<?= $id; ?>][id_bom]" id="id_bomS<?= $id; ?>" class="form-control input<?= $id; ?>" value="<?= $data['id_bom']; ?>">
                                    <input type="hidden" name="user[<?= $id; ?>][cavity]" id="cavity<?php echo $id; ?>" class="form-control input<?= $id; ?>" value="<?= $data['cavity_prod']; ?>">
                                    <input type="hidden" name="user[<?= $id; ?>][ct_std]" id="ct_mc<?php echo $id; ?>" class="form-control input<?= $id; ?>" value="<?= $data['ct_std_prod']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input<?= $id; ?>" name="user[<?= $id; ?>][ct_running]" value="<?= $data['ct_running']; ?>">
                                        <input type="hidden" name="" id="hari<?= $id; ?>" class="form-control input<?= $id; ?>">
                                        <input type="hidden" name="" id="nwt<?= $id; ?>" class="form-control input<?= $id; ?>">
                                        <input type="hidden" name="user[<?= $id; ?>][target]" id="target_mc<?= $id; ?>" class="form-control input<?= $id; ?>" value="<?= $data['target']; ?>">
                                        <input type="hidden" name="user[<?= $id; ?>][target_ppic]" id="target_ppic<?= $id; ?>" class="form-control input<?= $id; ?>" value="<?= $data['target_ppic']; ?>">
                                    </td>
                                    <td><input type="text" class="form-control input<?= $id; ?>" name="user[<?= $id; ?>][operator]" value="<?= $data['operator']; ?>"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][man_power]" class="form-control input<?= $id; ?>"  value="<?= $data['man_power']; ?>"></td>
                                    <td><select name="user[<?= $id; ?>][jenis_mesin]" class="form-control input<?= $id; ?>">
                                            <option value="">-</option>
                                            <option value="AUTO" <?= $data['jenis_mesin'] == 'AUTO' ? 'selected' : ''; ?>>AUTO</option>
                                            <option value="SEMI" <?= $data['jenis_mesin'] == 'SEMI' ? 'selected' : ''; ?>>SEMI</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control input<?= $id; ?>" name="user[<?= $id; ?>][keterangan]"></td>
                                    <td>
                                        <select name="user[<?= $id; ?>][running]" class="form-control" onchange="ubahRun(<?= $id; ?>)" id="run<?= $id; ?>">
                                            <option value="1" <?= $data['running'] == '1' ? 'selected' : ''; ?>>Running</option>
                                            <option value="0" <?= $data['running'] == '0' ? 'selected' : ''; ?>>Off</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="user[<?= $id; ?>][line]" class="form-control">
                                            <option value="1" <?= ($data['line'] == '1' || $data['line'] == 1 || $data['line'] == 'A' || $data['line'] == '0' || $data['line'] == 0) ? 'selected' : ''; ?>>1</option>
                                            <option value="2" <?= ($data['line'] == '2' || $data['line'] == 2 || $data['line'] == 'B') ? 'selected' : ''; ?>>2</option>
                                            <option value="3" <?= ($data['line'] == '3' || $data['line'] == 3 || $data['line'] == 'C') ? 'selected' : ''; ?>>3</option>
                                            <option value="4" <?= ($data['line'] == '4' || $data['line'] == 4 || $data['line'] == 'D') ? 'selected' : ''; ?>>4</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php $total = $no; } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:50px;">No. MC</th>
                                    <th style="width:50px;"> Ton</th>
                                    <th style="width:350px;">Nama BOM</th>
                                    <th style="width:70px;">CT Run</th>
                                    <th style="width:100px;">Operator</th>
                                    <th style="width:50px;">MP</th>
                                    <th style="width:80px;">Auto / Semi</th>
                                    <th style="width:150px;">Ket.</th>
                                    <th style="width:100px;">Run</th>
                                    <th style="width:70px;">Line</th>
                                </tr>
                            </tfoot>
                        </table>
                        



                </div>
            </div>
            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="form-group d-flex justify-content-center">
                                            <div id="save">
                                                <input type="hidden" name="" id="totalAkhir" value="<?= $total; ?>">
                                                <input type="submit" id="submit" name="" class="btn btn-primary" value="Save"><span id="loading" class="ml-2" style="vertical-align: middle;margin-top:25px"></span>
                                            </div>
                                        </div>
                                </div>
                            </div>
                         </div>
</form>
        </div>
    </div>
            
</div>
</div>
</div>




<?php $this->load->view('layout/footer'); ?>
    <script>

        function getNWT(){
            const days = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
            var tgl_input = $('#tanggal').val();
            const d = new Date(tgl_input);
            let day = days[d.getDay()];
            //alert(day);
            
            if (day == 'Sabtu' || day == 'Minggu') {
                for (var i = 0; i < 24; i++) {
                    $('#hari' + i).val(day);
                    $('#nwt' + i).val(5);
                    getDataItem(i);
                }
            }
            else{
                for (var i = 0; i < 24; i++) {
                    $('#hari' + i).val(day);
                    $('#nwt' + i).val(8);
                    getDataItem(i);
                }
            }
        }

        function getDataItem(x){
            $(document).ready(function(){
            $('#id_bom'+ x).autocomplete({
                source: "<?php echo site_url('c_operator/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    $('#id_bomS'+ x).val(ui.item.id_bom);
                    $('#ct_mc'+ x).val(ui.item.cyt_mc_bom);
                    $('#cavity'+ x).val(ui.item.cavity_product); 

                    //hitung target
                    var nwt = $('#nwt'+ x).val();
                    var target = ((3600/ui.item.cyt_mc_bom)*(ui.item.cavity_product*nwt));  
                    $('#target_mc' + x).val(target.toFixed(2));          
                }
                });
            });
        }


        function ubahBom(id)
        {
            var bom = $('#id_bom'+id).val();
            var res = bom.split('(')[0]; 
            //var res = bom.substring(0, 3);
            $('#id_bomS'+id).val(res);
        }

        function ubahRun(id)
        {
            var run = $('#run'+id).val();
            if(run == 0)
            {
                $('.input'+id).val('');
            }
            else
            {

            }
        }
 var save = $('#totalAkhir').val();
        function addMoreRows(frm) {
      save++;
    var recRow    = '';
    jQuery('#customFields').append(recRow);
    }

    $("#customFields").on('click','.remCF',function(){
            $(this).parent().parent().remove();
        });



        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Data_2025_12_09'},
                    {extend: 'pdf', title: 'Data_2025_12_09'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
               

            });
        });

        $(document).ready(function () {
            $("#my-form").submit(function (e) {
                $("#submit").attr("disabled", true);
                $('#loading').html("Loading, please wait...");
                document.getElementById("loading").style.color = "red"; 
                return true;
            });
        });

    </script>



