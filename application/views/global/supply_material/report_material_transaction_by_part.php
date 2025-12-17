 <title>DPR | Report Material Transaction By Part</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
<link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">
<script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 
<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>



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
                        <h5 class="mr-3">Report Supply Material By Part</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <?= form_open('c_material_transaction/report_material_transaction_by_part'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3"><b>Pilih Tahun-Bulan</b> 
                                    <select name="tahun" value="<?php echo $tahun ?>" class="form-control">
                                        <?php
                                        $tahuns = date('Y')-1;
                                        $now=date('Y');
                                        for($i=$tahuns; $i<=$now; $i++){
                                        $monts = array("01","02","03","04","05","06","07","08","09","10","11","12");
                                           foreach ($monts as $value) { ?>
                                                <option value="<?php echo $i.'-'.$value?>" <?php if($value == $bulan && $i == $tahun) { echo 'selected="selected"';}?>><?php echo $i.'-'.$value; ?></option>;<?php
                                           }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3"> <b>Pilih Kode Produk</b> 
                                    <input type="search" name="" id="kode_produk" class="autocompleteProduct form-control" required="" value="<?php echo $product ?>">
                                    <input type="hidden" name="product" id="hasil_search" class="form-control" value="<?php echo $product ?>">
                                </div>
                                
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Silahkan gunakan filter untuk mencari data tiap mesin.</strong></p>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <!-- <th>No. Mesin</th> -->
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Material</th>
                                            <th>Total Virgin</th>
                                            <th>Total Regrind</th>
                                            <!-- <th>Lot Material</th>
                                            <th>Master Batch</th> -->
                                            <th>Total Runner</th>
                                            <th>Total Loss Purge</th>
                                            <!-- <th>PIC</th> -->
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $view       = base_url('c_material_transaction/material_edit/id');
                                        // $delete     = base_url('c_production_plan/Delete/production_plan/prod_plan_harian/id');
                                            $no = 0 ; foreach($data_report->result_array() as $data) 
                                            {  $no++;
                                                echo '<tr>';
                                                echo '<td class="text-center">'.$no;'</td>';
                                                echo '<td>'.$data['tanggal'],'</td>';
                                                // echo '<td class="text-center">'.$data['no_mesin'],'</td>';
                                                echo '<td>'.$data['kode_produk'],'</td>';
                                                echo '<td>'.$data['nama_produk'],'</td>';
                                                echo '<td>'.$data['material'],'</td>';
                                                echo '<td>'.$data['virgin'],'</td>';
                                                echo '<td>'.$data['regrind'],'</td>';
                                                // echo '<td>'.$data['lot_material'],'</td>';
                                                // echo '<td>'.$data['master_batch'],'</td>';
                                                echo '<td>'.$data['runner'],'</td>';
                                                echo '<td>'.$data['loss_purge'],'</td>';
                                                // echo '<td>'.$data['pic'],'</td>';
                                                // echo '<td></td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <!-- <th>No. Mesin</th> -->
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Material</th>
                                            <th>Total Virgin</th>
                                            <th>Total Regrind</th>
                                            <!-- <th>Lot Material</th>
                                            <th>Master Batch</th> -->
                                            <th>Total Runner</th>
                                            <th>Total Loss Purge</th>
                                            <!-- <th>PIC</th> -->
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </tfoot>
                                </table>



                     </div>

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



    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                        right: 0
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Material Transaction By Product ID'},
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

        $(document).ready(function(){

            $('.autocompleteProduct').autocomplete({
                source: "<?php echo site_url('c_material_transaction/get_autocomplete_product');?>",
                select: function (event, ui) {
                    $('#kp').html(ui.item.kp_pr); 
                    $('#hasil_search').val(ui.item.kode_product);
                }
            });
        });

    </script>




