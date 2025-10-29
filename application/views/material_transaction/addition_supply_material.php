 <title>DPR | Addition Supply Material</title>
<?php $this->load->view('layout/sidebar'); ?>

<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

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
                        <h5>Addition Supply Material</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>

    

                <div class="ibox-content" style="margin-top:-20px">
                    <div class="isi">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-info" onclick="addMoreRows(this.form);">Add Rows</button>
                            </div>
                            <form action="<?php echo site_url('c_material_transaction/add_material_transaction')?>" method="post" id="my-form">
                            
                            <div class="card-body">
                                <div class="col">
                                    <div class="form-inline">
                                      <div class="form-group mb-3 mr-4">
                                        <label for="staticEmail2" ><b>Pilih Tanggal :</b></label>
                                        <input type="date" name="tanggal" class="form-control ml-2" required="">
                                      </div>
                                    </div>
                                </div>
                                <div class="row ml-3">
                                        <p><strong>*Catatan :</strong> Silahkan pilih <strong>Tanggal</strong> terlebih dahulu, lalu <strong>isi data supply material</strong> dibawah ini.</p>
                                    </div>
                                    <hr>
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="customFields" >
                            <thead>
                                <tr>
                                    <th style="background:#1ab394;color: white;text-align: center;"></th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">MC No.</th>
            <!--                         <td style="background:#1ab394;color: white;text-align: center;">Tonnage MC</td> -->
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Prod. Code</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Virgin</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Regrind</td>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Lot Material</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Master Batch</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Stock Mtrl Dlm<br> Mesin</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Keterangan</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Kebutuhan Mtrl<br>Perhari</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Prod. Name</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">Material Name</th>
                                    <th style="background:#1ab394;color: white;text-align: center;" class="align-middle">PIC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = -1; $no = 0 ;
                                {  $no++; $id++; ?>
                                <tr>
                                    <td class='align-middle'><input type='button' value='X' class="delete"></td>
                                    <td class="text-center align-middle">
                                        <input type="number" name="user[<?= $id; ?>][no_mesin]" class="form-control"></td>
                                    <td>
                                        <input type="text" class="form-control" name=""  id="getDetail<?php echo $id ?>" oninput="getSubTotal(<?php echo $id; ?>)">
                                        <input type="hidden" name="user[<?= $id; ?>][kode_produk]" class="form-control" id="kode_produk<?php echo $id; ?>" readonly>
                                    </td>
                                    <td><input type="text" name="user[<?= $id; ?>][virgin]" class="form-control" style="width:100px"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][regrind]" class="form-control" style="width:100px"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][lot_material]" class="form-control"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][master_batch]" class="form-control"></td>
                                    <td><input type="number" name="user[<?= $id; ?>][stock_mtrl]" class="form-control"></td>
                                    <td>
                                        <select name="user[<?= $id; ?>][keterangan]" class="form-control" style="width:150px">
                                            <option value="">Pilih Salah Satu</option>
                                            <option value="NO MATERIAL">NO MATERIAL</option>
                                            <option value="NO SCHEDULE">NO SCHEDULE</option>
                                            <option value="NO SUPPLAY">NO SUPPLY</option>
                                            <option value="SUPPLAY">SUPPLY</option>
                                            <option value="SUPPLAY CUKUP">SUPPLY CUKUP</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="user[<?= $id; ?>][kebutuhan_mtrl]" id="kebutuhan_mtrl<?php echo $id; ?>" class="form-control" readonly></td>
                                    <td><input type="text" name="user[<?= $id; ?>][nama_produk]" id="nama_produk<?php echo $id; ?>" class="form-control" style="width:200px" readonly>
                                    </td>
                                    <td><input type="text" name="user[<?= $id; ?>][material]" id="material_name<?php echo $id; ?>" class="form-control" style="width:200px" readonly></td>
                                    <td><input type="text" name="user[<?= $id; ?>][pic]" class="form-control" style="width:150px;" value="<?php echo $this->session->userdata('nama_actor') ?>" readonly></td>
                                </tr>
                            <?php  $total = $no; } ?>
                            </tbody>
                        </table>
                            </div>
                        </div>
                        
                        
                        



                </div>
            <br>
            <div class="form-group d-flex justify-content-center">
                <div id="save">
                    <input type="hidden" name="" id="totalAkhir" value="<?= $total; ?>">
                    <input type="submit" id="submit" name="" class="btn btn-primary" value="Save"><span id="loading" class="ml-2" style="vertical-align: middle"></span>
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
<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#customFields').on('click', 'input[type="button"]', function(e){
            $(this).closest('tr').remove()
            })
        });

        $(document).ready(function() {
            $('.delete').click(function() {
            return confirm("Are you sure you want to delete?");
            });
        });

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
            $('.dataTables-example').DataTable({
                pageLength: 70,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: false,
                    paging:         false,
                    fixedColumns:   {
                        left: 2,
                        right: 0
                    },

            });
        });

        // $(document).ready(function(){
        //     $('.autocompleteProduct').autocomplete({
        //         source: "<?php echo site_url('c_material_transaction/get_autocomplete');?>",
        //         select: function (event, ui) {
        //             $('#nama_produk' + <?php echo $id; ?> ).val(ui.item.nama_product); 
        //             $('#material_name' + <?php echo $id; ?>).val(ui.item.nama_product_release); 
        //             // $('#ct_mesin').val(ui.item.cyt_mc);                 
        //         }

        //     });
        // });

        function getSubTotal(x){
            $(document).ready(function(){
                $('#getDetail' + x).autocomplete({
                    source: "<?php echo site_url('c_material_transaction/get_autocomplete');?>",
                    select: function (event, ui) {
                        $('#nama_produk' + x).val(ui.item.nama_product); 
                        $('#material_name' + x).val(ui.item.nama_product_release); 
                        $('#kode_produk' + x).val(ui.item.kode_product); 
                        $('#kebutuhan_mtrl' + x).val(ui.item.keb_mtrl); 
                        // $('#ct_mesin').val(ui.item.cyt_mc);                 
                    }

                });
             });
        }
        $(document).ready(function(){
            $('.autocompletePIC').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompletePIC');?>",
            });
        });
    </script>



<script type="text/javascript">

    $(document).ready(function(){
        $('#tesX').hide();
    });

    function cekData(id)
    {
        var tanggal = $('#tanggal').val();
        var pic = $('#pic').val();
        $.ajax({
              type    : "POST",
              url     : "<?php echo site_url(); ?>/c_material_transaction/cekData", 
              dataType: "JSON",
              data    : "tanggal=" + tanggal + "&pic=" + pic,
              success : function(response){
                var len = response.length;
                 if(len > 0){
                    $("#tes").val(response[0].id)
                    $('#tesX').hide();
                    $('#inites').html("<font style='color:red;font-size:16px;text-align:center'><b>Tidak bisa menambahkan data, karena data sudah ada. Silahkan gunakan fitur edit jika terjadi perubahan. Terimakasih!</b></font>");
                 }
                 else
                {
                    $('#tesX').show();
                    $('#inites').html("")
                    $('#tanggalSave').val(tanggal);
                }
              }});
    }

    var save = $('#totalAkhir').val();
    function addMoreRows(frm) {
    save++;
    var recRow    = '<tr><td><input type="button" value="X" class="delete"></td>' +
        '<td class="text-center"><input type="number" name="user['+save+'][no_mesin]" class="form-control text-center"></td>' +
    '<td><input type="text" class="form-control" name="" id="getDetail'+save+'" oninput="getSubTotal('+save+')"><input type="hidden" name="user['+save+'][kode_produk]" class="form-control" id="kode_produk'+save+'" readonly></td>' +
    '<td><input type="text" name="user['+save+'][virgin]" class="form-control"></td>'+
    '<td><input type="text" name="user['+save+'][regrind]" class="form-control"></td>'+
    '<td><input type="text" name="user['+save+'][lot_material]" class="form-control"></td>'+
    '<td><input type="text" name="user['+save+'][master_batch]" class="form-control"></td>'+
    '<td><input type="number" name="user['+save+'][stock_mtrl]" class="form-control"></td>'+
    '<td><select class="form-control" name="user['+save+'][keterangan]"><option value="">Pilih Salah Satu</option><option value="NO MATERIAL">NO MATERIAL</option><option value="NO SCHEDULE">NO SCHEDULE</option><option value="NO SUPPLAY">NO SUPPLAY</option><option value="SUPPLAY">SUPPLAY</option><option value="SUPPLAY CUKUP">SUPPLAY CUKUP</option></select></td>'+
    '<td><input type="number" name="user['+save+'][kebutuhan_mtrl]" id="kebutuhan_mtrl'+save+'" class="form-control" readonly></td>'+
    '<td><input type="text" name="user['+save+'][nama_produk]" id="nama_produk'+save+'" class="form-control" readonly></td>'+
    '<td><input type="text" name="user['+save+'][material]" id="material_name'+save+'" class="form-control" readonly></td>' +    
    '<td><input type="text" name="user['+save+'][pic]" value="<?php echo $this->session->userdata('nama_actor') ?>" class="form-control" readonly></td>';
    jQuery('#customFields').append(recRow);
    }

    $('.autocompletePIC_addrow').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocompletePIC');?>",
            });
</script>
