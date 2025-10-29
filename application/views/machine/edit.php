 <title>DPR | Edit Op. Mesin</title>
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

<?= form_open('c_machine/edit_proses/'.$id); ?> 
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
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
                            </thead>
                            <tbody>
                                <?php $id = -1; $no = 0 ; foreach($data_tabel->result_array() as $data)
                                {  $no++; $id++; ?>
                                <tr>
                                    <td><?= $data['no_mesin']; ?><input type="hidden" name="user[<?= $id; ?>][no_mesin]" value="<?= $data['no_mesin']; ?>"></td>
                                    <td><?= $data['tonnase']; ?><input type="hidden" name="user[<?= $id; ?>][tonnase]" value="<?= $data['tonnase']; ?>"></td>
                                    <td><input type="search form-control" name="" class="autocompleteBom" style="width: 100%;height: 100%"  id="id_bom<?= $id; ?>" value="<?= $data['kp_pr'].'( '.$data['np_pr'].')'; ?>" onchange="ubahBom(<?= $id; ?>)">
                                    <input type="text" name="user[<?= $id; ?>][id_bom]" id="id_bomS<?= $id; ?>"  class="form-control" style="height: 100%" value="<?= $data['id_bom']; ?>" required>
                                    
                                    </td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][ct_running]" value="<?= $data['ct_running']; ?>" onkeyup="ubahBom(<?= $id; ?>)"></td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][operator]" value="<?= $data['operator']; ?>"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][man_power]" class="form-control"  value="<?= $data['man_power']; ?>"></td>
                                    <td><select name="user[<?= $id; ?>][jenis_mesin]" class="form-control">
                                            <option value="">-</option>
                                            <option value="AUTO" <?= $data['jenis_mesin'] == 'AUTO' ? 'selected' : ''; ?>>AUTO</option>
                                            <option value="SEMI" <?= $data['jenis_mesin'] == 'SEMI' ? 'selected' : ''; ?>>SEMI</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][keterangan]" value="<?= $data['keterangan']; ?>"></td>
                                    <td>
                                        <select name="user[<?= $id; ?>][running]" class="form-control">
                                            <option value="1" <?= $data['running'] == '1' ? 'selected' : ''; ?>>Running</option>
                                            <option value="0" <?= $data['running'] == '0' ? 'selected' : ''; ?>>Off</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="user[<?= $id; ?>][line]" class="form-control" onchange="ubahRun(<?= $id; ?>)" id="run<?= $id; ?>">
                                            <option value="A" <?= $data['line'] == 'A' ? 'selected' : ''; ?>>A</option>
                                            <option value="B" <?= $data['line'] == 'B' ? 'selected' : ''; ?>>B</option>
                                            <option value="C" <?= $data['line'] == 'C' ? 'selected' : ''; ?>>C</option>
                                            <option value="D" <?= $data['line'] == 'D' ? 'selected' : ''; ?>>D</option>
                                        </select>
                                    </td>

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
<input type="submit" name="" class="btn btn-primary" value="Save">
<?= form_close(); ?>


                     </div>
                </div>
</div>
</div>
</div>
</div>




<?php $this->load->view('layout/footer'); ?>
    <script>
        $(document).ready(function(){

            $('.autocompleteBom').autocomplete({
                source: "<?php echo site_url('c_operator/get_autocomplete');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    // $('#id_bomS').val(ui.item.id_bom);
                    $('#ct_mc_aktual').html(ui.item.cyt_mc_bom + ' <br/> ' + ui.item.cyt_mp_bom);
                    $('#ct_mc').val(ui.item.cyt_mc_bom);
                    $('#ct_mp').val(ui.item.cyt_mp_bom);
                    $('#id_pr').val(ui.item.id_pr); 
                    $('#cavity').val(ui.item.cavity_product); 

                    var nwt = $('#nwt_mp').val();
                    var ot = $('#ot_mp').val();
                    var target = (((parseInt(nwt) + parseInt(ot)) * 3600) / (parseInt(ui.item.cyt_mc_bom) + parseInt(ui.item.cyt_mp_bom)));
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



     

        });

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
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

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
    </script>



