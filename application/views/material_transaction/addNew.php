 <title>DPR | Add Supply Material</title>
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
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <?php
                            $tanggal_new =  $tanggal;
                            $tanggal_new_fix = date("d-M-y", strtotime($tanggal_new));
                        ?>
                        <h5>Add Material Transaction | Tanggal <?php echo $tanggal_new_fix ?></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">

                   
                <?= form_open('c_material_transaction/add_material_transaction'); ?>  
                <input type="hidden" name="tanggal" class="form-control" required="" value="<?php echo $tanggal ?>">

                        <table class="table table-striped table-bordered table-hover dataTables-example" id="customFields" >
                            <thead>
                                <tr>
                                    <th style="background:#1ab394;color: white;text-align: center;"></th>
                                    <th style="background:#1ab394;color: white;text-align: center;">MC</th>
            <!--                         <td style="background:#1ab394;color: white;text-align: center;">Tonnage MC</td> -->
                                    <th style="background:#1ab394;color: white;text-align: center;">Prod. Code</th>
                                    
                                    <th style="background:#1ab394;color: white;text-align: center;">Virgin</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Regrind</td>
                                    <th style="background:#1ab394;color: white;text-align: center;">Lot Material</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Master Batch</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Stock Mtrl</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Ket.</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Keb. Mtrl</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Prod. Name</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">Material Name</th>
                                    <th style="background:#1ab394;color: white;text-align: center;">PIC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = -1; $no = 0 ; foreach($data_tabel->result_array() as $data)
                                {  $no++; $id++; ?>
                                <tr>
                                    <td class='align-middle'><input type='button' value='X' class="delete"></td>
                                    <td class="text-center align-middle"><?= $data['no_mesin']; ?><input type="hidden" name="user[<?= $id; ?>][no_mesin]" value="<?= $data['no_mesin']; ?>"></td>
                                    <!-- <td><input type="search form-control" name="user[<?= $id; ?>][no_mesin]" class="autocompleteMesin" style="width:50px;"></td> -->
                                    <td>
                                        <input type="text" class="form-control" name="" id="getDetail<?php echo $id ?>" oninput="getSubTotal(<?php echo $id; ?>)">
                                        <input type="hidden" name="user[<?= $id; ?>][kode_produk]" class="form-control" id="kode_produk<?php echo $id; ?>" readonly></td>
                                    </td>
                                    
                                    <td><input type="text" name="user[<?= $id; ?>][virgin]" class="form-control" style="width:100px"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][regrind]" class="form-control" style="width:100px"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][lot_material]" class="form-control"></td>
                                    <td><input type="text" name="user[<?= $id; ?>][master_batch]" class="form-control"></td>
                                    <td><input type="number" name="user[<?= $id; ?>][stock_mtrl]" class="form-control" style="width:100px"></td>
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

                                    <td><input type="number" name="user[<?= $id; ?>][kebutuhan_mtrl]" id="kebutuhan_mtrl<?php echo $id; ?>" class="form-control" style="width:100px" readonly></td>
                                    <td><input type="text" name="user[<?= $id; ?>][nama_produk]" id="nama_produk<?php echo $id; ?>" class="form-control" style="width:200px" readonly>
                                        <input type="hidden" name="" class="form-control" value="<?php echo $id ?>" readonly></td>
                                    <td><input type="text" name="user[<?= $id; ?>][material]" id="material_name<?php echo $id; ?>" class="form-control" style="width:200px" readonly></td>
                                    <td><input type="text" name="user[<?= $id; ?>][pic]" class="form-control" style="width:150px;" value="<?php echo $pic ?>" readonly></td>

                                    <!-- <td><?= $data['no_mesin']; ?><input type="hidden" name="user[<?= $id; ?>][no_mesin]" value="<?= $data['no_mesin']; ?>"></td>
                                    <td><input type="search form-control" name="" class="autocompleteBom" style="width: 100%;height: 100%" required="" id="id_bom<?= $id; ?>" > 
                                    <input type="hidden" name="user[<?= $id; ?>][id_bom]" id="id_bomS<?= $id; ?>" class="form-control" style="height: 100%"></td>
                                    <td><input type="text" class="form-control" name="user[<?= $id; ?>][ct_running]" onkeyup="ubahBom(<?= $id; ?>)"></td>
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
                                    <td><input type="text" name="user[<?= $id; ?>][line]" class="form-control" value="">
                                    </td> -->


                                </tr>
                            <?php  $total = $no; } ?>
                            </tbody>
                        </table>
                        <input type="hidden" name="" id="totalAkhir" value="<?= $total; ?>">
                        <div class="col-sm-12 d-flex justify-content-center">
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



