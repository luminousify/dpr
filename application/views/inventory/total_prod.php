 <title>DPR | Inventory</title>
<?php $this->load->view('layout/sidebar'); ?>


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
                        <?php foreach($data_header->result_array() as $dh): ?>
                        <?php endforeach; ?>

                        <?php foreach($total_analisis->result_array() as $total): ?>
                        <?php endforeach; ?>
                        <h5>Total Prod. ( <?php echo $dh['kode_product'] ?> - <?php echo $dh['nama_product'] ?> )</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <?= form_open('c_inventory/total_prod_filter'); ?>  
                    <div class="card rounded">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3"> <b>Pilih Tahun - Bulan</b> 
                                    <select name="tahun" value="<?php echo $tanggal ?>" class="form-control">
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
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:18px; margin-left: -10px;" value="Show"></div>
                                <input type="hidden" name="id_product" class="form-control" value="<?php echo $dh['id_product'] ?>">
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>bulan ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    <hr>
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable1" style="width:100%" >
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lot Number</th>
                                                    <th>Date</th>
                                                    <th>M/C</th>
                                                    <th>Cavity</th>
                                                    <th>Sft</th>
                                                    <th>Qty OK</th>
                                                    <th>Qty NG</th>
                                                    <th>% NG</th>
                                                    <th>Gross Prod</th>
                                                    <th>Nett Prod</th>
                                                    <th>CT Std</th>
                                                    <th>CT Set</th>
                                                    <th>NWT</th>
                                                    <th>CalcDT</th>
                                                    <th>WH</th>
                                                    <th>OT</th>
                                                    <th>Mach Use</th>
                                                    <th>Stop Time</th>
                                                    <th>Operator</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $id = -1; $no = 0 ; 
                                                foreach($data_tabel->result_array() as $data)
                                                {  $no++; $id++; $cdt = $data['nwt_mp'] - $data['production_time'] - $data['qty_lt'] ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['lot_global']; ?></td>
                                                    <td><?= $data['tanggal']; ?></td>
                                                    <td><?= $data['mesin']; ?></td>
                                                    <td><?= $data['cavity']; ?></td>
                                                    <td><?= $data['shift']; ?></td>
                                                    <td><?= $data['qty_ok']; ?></td>
                                                    <td><?= $data['qty_ng']; ?>
                                                    <button type="button" class="btn btn-primary" >
                                                        View Details
                                                    </button>
                                                </td>
                                                    <td><?= number_format(($data['qty_ng']/$data['qty_ok'])*100,2); ?>%</td>
                                                    <td><?= $data['gross_prod']; ?></td>
                                                    <td><?= $data['nett_prod']; ?></td>
                                                    <td><?= $data['ct_mc']; ?></td>
                                                    <td><?= $data['ct_mc_aktual']; ?></td>
                                                    <td><?= $data['nwt_mp']; ?></td>
                                                    <td><?php echo $cdt; ?></td>
                                                    <td><?= $data['production_time']; ?></td>
                                                    <td><?= $data['ot_mh']; ?></td>
                                                    <td><?= $data['nwt_mp'] != 0 ? number_format(($data['production_time']/$data['nwt_mp'])*100,2) : 0; ?>%</td>
                                                    <td><?= $data['qty_lt']; ?></td>
                                                    <td><?= $data['operator']; ?></td>
                                                    <td><?= $data['keterangan']; ?></td>
                                                </tr>
                                                <?php } ?>

                                                <?php $id = -1; $no = 0;
                                                foreach($total_analisis->result_array() as $data2)
                                                { $id++; $no++;  ?>
                                                <tr style="background:#1ab394;color: white;">
                                                    <th style="background:#1ab394;color: white;">Total</th>
                                                    <th style="background:#1ab394;color: white;"></th>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['mesin']; ?></th>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <th style="background:#1ab394;color: white;"><?php echo $data2['total_ok']; ?></th>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['total_ng']; ?></th>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['gross']; ?></th>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['nett']; ?></th>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['total_nwt']; ?></th>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['total_cdt']; ?></th>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['total_prod_time']; ?></th>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <th style="background:#1ab394;color: white;"><?= $data2['total_lt']; ?></th>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                    <td style="background:#1ab394;color: white;"></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>M/C</th>
                                                    <th>Cavity</th>
                                                    <th>Sft</th>
                                                    <th>Qty OK</th>
                                                    <th>Qty NG</th>
                                                    <th>% NG</th>
                                                    <th>Gross Prod</th>
                                                    <th>Nett Prod</th>
                                                    <th>CT Std</th>
                                                    <th>CT Set</th>
                                                    <th>NWT</th>
                                                    <th>CalcDT</th>
                                                    <th>WH</th>
                                                    <th>OT</th>
                                                    <th>Mach Use</th>
                                                    <th>Stop Time</th>
                                                    <th>Operator</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </tfoot> -->
                                        </table>



                     </div>
                </div>
</div>
</div>
</div>
</div>




<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>
    <!-- Page-Level Scripts -->


<script>
        $(document).ready(function(){
            $('#datatable1 tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('#datatable1').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 5,
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

    </script>

