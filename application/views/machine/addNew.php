 <title>DPR | Add Op. Mesin</title>
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
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Daftar Operasi Mesin & Man Power</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">

                  
<!-- <button class="btn btn-primary" onclick="addMoreRows(this.form);">Add Rows</button> -->
<?= form_open('c_machine/add'); ?>  
<div class="row">
    <div class="col">
        <div class="form-group">
            <label><b>Tanggal</b></label>
            <input type="date" name="tanggal" class="form-control" onchange="getNWT()" id="tgl_input" required="">
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <label><b>Shift</b></label>
            <select name="shift" id="shift" class="form-control">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
    </div>
    <div class="col">
       <div class="form-group">
        <label><b>Group</b></label>
        <select id="group" name="group" class="form-control" required=""  >
           <option value="">-Choose-</option>
            <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
        </select>
       </div>
    </div>
    <div class="col">
    <label><b></b></label><br/><br/>
       <a class="btn btn-danger" style="color:white" onclick="checkDataProdPlan()">Check</a>
    </div>
</div>
                        <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%" id="customFields" >
                            <thead>
                                <tr>
                                    <th style="width:70px;">No. MC</th>
                                    <th> Ton</th>
                                    <th >Nama BOM</th>
                                    <th style="width:80px;">CT Running</th>
                                    <th style="width:100px;">Operator</th>
                                    <th style="width:70px;">Man Power</th>
                                    <th style="width:80px;">Auto / Semi</th>
                                    <th style="width:150px;">Ket.</th>
                                    <th style="width:150px;">Run</th>
                                    <th style="width:70px;">Line</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $id = -1; 
                                $no = 0; 
                                $total = 0;
                                
                                // Check if data_tabel exists and has results
                                if (isset($data_tabel) && $data_tabel && $data_tabel->num_rows() > 0) {
                                    foreach($data_tabel->result_array() as $data) {
                                        $no++; 
                                        $id++; 
                                ?>
                                <tr>
                                    <td><?= $data['no_mesin']; ?><input type="hidden" id="no_mesin<?= $id; ?>" name="user[<?= $id; ?>][no_mesin]" value="<?= $data['no_mesin']; ?>"></td>
                                    <td><?= $data['tonnase']; ?><input type="hidden" name="user[<?= $id; ?>][tonnase]" value="<?= $data['tonnase']; ?>"></td>
                                    <td><input type="text" name="" class="form-control" oninput="getDataItem(<?php echo $id ?>)"  id="id_bom<?= $id; ?>" > 
                                    <input type="hidden" name="user[<?= $id; ?>][id_bom]" id="id_bomS<?= $id; ?>" class="form-control" style="height: 100%">
                                    <input type="hidden" name="user[<?= $id; ?>][cavity]" id="cavity<?php echo $id; ?>" class="form-control">
                                    <input type="hidden" name="user[<?= $id; ?>][ct_std]" id="ct_mc<?php echo $id; ?>" class="form-control">
                                    </td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][ct_running]">
                                        <input type="hidden" name="" id="hari<?= $id; ?>" class="form-control">
                                        <input type="hidden" name="" id="nwt<?= $id; ?>" class="form-control">
                                        <input type="hidden" name="user[<?= $id; ?>][target]" id="target_mc<?= $id; ?>" class="form-control">
                                        <input type="hidden" name="user[<?= $id; ?>][target_ppic]" id="target_ppic<?= $id; ?>" class="form-control">
                                    </td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][operator]"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][man_power]" class="form-control" value="0.5"></td>
                                    <td><select name="user[<?= $id; ?>][jenis_mesin]" class="form-control">
                                            <option value="">-</option>
                                            <option value="AUTO" selected>AUTO</option>
                                            <option value="SEMI">SEMI</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][keterangan]"></td>
                                    <td>
                                        <select name="user[<?= $id; ?>][running]" class="form-control">
                                            <option value="1">Running</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="user[<?= $id; ?>][line]" class="form-control" value="<?= $line; ?>">
                                    </td>


                                </tr>
                            <?php  
                                        $total = $no; 
                                    } // end foreach
                                } else {
                                    // Show message if no data found
                                    echo '<tr><td colspan="10" style="text-align: center; padding: 20px;">';
                                    echo '<strong>No machines found for Line: ' . (isset($line) && $line !== '' ? htmlspecialchars($line) : 'N/A') . '</strong><br>';
                                    echo 'Please check:<br>';
                                    echo '1. Master data machines are set up for this line<br>';
                                    echo '2. Machines are marked as active (aktif = 1)<br>';
                                    echo '3. Machines are assigned to your division (' . (isset($this->data['bagian']) ? htmlspecialchars($this->data['bagian']) : 'N/A') . ')';
                                    echo '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <input type="hidden" name="" id="totalAkhir" value="<?= $total; ?>">
                        <div class="d-flex justify-content-center">
                            <input type="submit" name="" class="btn btn-primary" value="Save">
                        </div>
                        
<?= form_close(); ?>
                     </div>
                </div>
</div>
</div>
</div>
</div>




<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/scripts/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/scripts/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/scripts/buttons.html5.min.js"></script>
    <script>
        function getNWT(){
            const days = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
            var tgl_input = $('#tgl_input').val();
            const d = new Date(tgl_input);
            let day = days[d.getDay()];
            //alert(day);
            
            if (day == 'Sabtu' || day == 'Minggu') {
                for (var i = 0; i < 24; i++) {
                    $('#hari' + i).val(day);
                    $('#nwt' + i).val(5);
                }
            }
            else{
                for (var i = 0; i < 24; i++) {
                    $('#hari' + i).val(day);
                    $('#nwt' + i).val(8);
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

                    // var nwt = $('#nwt_mp').val();
                    // var ot = $('#ot_mp').val();
                    // var target = (((parseInt(nwt) + parseInt(ot)) * 3600) / (parseInt(ui.item.cyt_mc_bom) + parseInt(ui.item.cyt_mp_bom)));
                    // //var target = (nwt + ot)
                    // $('#Target').val(parseInt(target));  

                    //hitung target
                    var nwt = $('#nwt'+ x).val();
                    var target = ((3600/ui.item.cyt_mc_bom)*(ui.item.cavity_product*nwt));  
                    $('#target_mc' + x).val(target.toFixed(2));          
                }
                });
            });
            console.log("running")
        }
    	

        function ubahBom(id)
        {
            var bom = $('#id_bom'+id).val();
            var res = bom.substring(0, 3);
            $('#id_bomS'+id).val(res);
        }

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
                    {
                        extend: 'excel',
                        title: 'Machine_Operation_<?= isset($line) ? 'Line_' . $line . '_' : ''; ?><?= date('Y-m-d'); ?>',
                        filename: 'Machine_Operation_<?= isset($line) ? 'Line_' . $line . '_' : ''; ?><?= date('Y-m-d'); ?>',
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    // Extract value from input fields
                                    var $cell = $(node);
                                    var $input = $cell.find('input[type="text"]:not([type="hidden"])');
                                    var $select = $cell.find('select');
                                    var $hidden = $cell.find('input[type="hidden"]');
                                    
                                    // Get visible text content (excluding input/select elements)
                                    var visibleText = $cell.clone().children('input, select, hidden').remove().end().text().trim();
                                    
                                    // If there's visible text content (like machine number, tonnage), use it
                                    if (visibleText && visibleText.length > 0) {
                                        return visibleText;
                                    }
                                    
                                    // If there's a text input, get its value
                                    if ($input.length > 0) {
                                        return $input.val() || '';
                                    }
                                    
                                    // If there's a select, get selected option text
                                    if ($select.length > 0) {
                                        return $select.find('option:selected').text() || '';
                                    }
                                    
                                    // If only hidden inputs, return empty
                                    if ($hidden.length > 0 && $input.length === 0 && $select.length === 0) {
                                        return '';
                                    }
                                    
                                    // Otherwise return the cell text content (for regular cells)
                                    return data.replace(/<[^>]*>/g, '').trim();
                                }
                            }
                        }
                    },
                    {extend: 'pdf', title: 'Machine_Operation_<?= isset($line) ? 'Line_' . $line . '_' : ''; ?><?= date('Y-m-d'); ?>'},

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


    var save = $('#totalAkhir').val();
    function addMoreRows(frm) {
      save++;
    var recRow    = '<tr><td><input type="text" name="user['+save+'][no_mc]" class="form-control"  ></td>' +
        '<td><input type="text" name="user['+save+'][ton]" class="form-control"  ></td>' +
    '<td><input type="text" name="user['+save+'][MomID]" class="form-control"  ></td>' +
    '<td><input type="text" name="user['+save+'][MomProduct]" class="form-control"  ></td>' +
    '<td><input type="text" name="user['+save+'][AccID]" class="form-control"  ></td>' +
        '<td><input type="text" name="user['+save+'][kode_proses]" class="form-control"  ></td>' +
        '<td><select name="user['+save+'][nama_proses]" style="width:100px;" class="form-control"  ><option value=""></option><option value="RMW L">RMW L</option><option value="RMW L AS">RMW L AS</option><option value="P1">P1</option><option value="P2">P2</option><option value="P3">P3</option><option value="P4">P4</option><option value="P5">P5</option><option value="P6">P6</option><option value="PLATING">PLATING</option><option value="FINAL">FINAL</option><option value="ASSY">ASSY</option><option value="BARREL">BARREL</option><option value="WASHING">WASHING</option><option value="STAMPING">STAMPING</option><option value="INSPECTION">INSPECTION</option>><option value="BG">BABY GRIND</option></select></td>' +
        '<td><select name="user['+save+'][type]" style="width:80px;" class="form-control"  ><option value="RMW">RMW</option><option value="WIP">WIP</option><option value="FG">FG</option></select></td>' +
    '<td><input type="text" name="user['+save+'][usage]" value="0" class="form-control"   /></td>' +
    '<td><input type="text" name="user['+save+'][sp]" class="form-control"   /></td>' +
   
        '<td><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></p></td><tr/>';
    jQuery('#customFields').append(recRow);
    }

    $("#customFields").on('click','.remCF',function(){
            $(this).parent().parent().remove();
        });

    //Function to check whether date and current bom already have data on production_plan or not.
    function checkDataProdPlan()
    {
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
            for (var i = 0; i < jsonData.length; i++) {
            var kp_pr = jsonData[i].kp_pr;
            var no_mesin = jsonData[i].no_mesin;
            var kode_produk = jsonData[i].kode_produk;
            var material_name = jsonData[i].material_name;
            var prod_qty = jsonData[i].prod_qty;
            var cavity = jsonData[i].cavity;
            var id_bom = jsonData[i].id_bom;
            var cyt_mc = jsonData[i].cyt_mc;
            for (var j = 0; j < 23; j++) {
                if(no_mesin === $('#no_mesin'+[j]).val()){
                    console.log(no_mesin+" on " + i + " match with "+$('#no_mesin'+[j]).val() + "("+j+")" )
                    $('#id_bom'+[j]).val(kp_pr)
                    // $('#kp').html(ui.item.kp_pr); 
                    $('#id_bomS'+ [j]).val(id_bom);
                    $('#ct_mc'+ [j]).val(cyt_mc);
                    $('#cavity'+ [j]).val(cavity); 

                    // var nwt = $('#nwt_mp').val();
                    // var ot = $('#ot_mp').val();
                    // var target = (((parseInt(nwt) + parseInt(ot)) * 3600) / (parseInt(ui.item.cyt_mc_bom) + parseInt(ui.item.cyt_mp_bom)));
                    // //var target = (nwt + ot)
                    // $('#Target').val(parseInt(target));  

                    //hitung target
                    var nwt = $('#nwt'+ [j]).val();
                    var target = prod_qty;
                    $('#target_ppic' + [j]).val(target);
                    console.log( $('#id_bomS'+ [j]).val())
                    console.log( $('#ct_mc'+ [j]).val())
                    console.log( $('#cavity'+ [j]).val())
                    console.log( $('#target_mc'+ [j]).val())
                    
                }   
            }
           
        }
        alert("Production Plan Data Found!");
                         
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



