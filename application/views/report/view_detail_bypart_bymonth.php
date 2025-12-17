 <title>DPR | View Detail Productivity By Part</title>
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
                        <?php foreach($view_detail_bypart->result_array() as $dh): ?>
                        <?php endforeach; ?>
                        <h5>Detail Productivity ( <?php echo $dh['kode_product'] ?> - <?php echo $dh['nama_product'] ?> )</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <!--  -->
                    <hr>
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable1" style="width:100%" >
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>M/C</th>
                                                    <th>Sft</th>
                                                    <th>Qty OK</th>
                                                    <th>Qty NG</th>
                                                    <th>% NG</th>
                                                    <th>Gross Prod</th>
                                                    <th>Nett Prod</th>
                                                    <th>CT Std2</th>
                                                    <th>CT Set</th>
                                                    <th>CT Quo</th>
                                                    <th>WH</th>
                                                    <th>OT</th>
                                                    <th>Mach Use</th>
                                                    <th>CalcDT</th>
                                                    <th>Stop Time</th>
                                                    <th>Operator</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $id = -1; $no = 0 ; foreach($view_detail_bypart->result_array() as $data)
                                                {  $no++; $id++; ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['tanggal']; ?></td>
                                                    <td><?= $data['mesin']; ?></td>
                                                    <td><?= $data['shift']; ?></td>
                                                    <td><?= $data['qty_ok']; ?></td>
                                                    <td><?= $data['qty_ng']; ?></td>
                                                    <td><?= number_format(($data['qty_ng']/$data['qty_ok'])*100,2); ?>%</td>
                                                    <td><?= $data['gross_prod']; ?></td>
                                                    <td><?= $data['nett_prod']; ?></td>
                                                    <td><?= $data['ct_mc']; ?></td>
                                                    <td><?= $data['ct_mc_aktual']; ?></td>
                                                    <td><?= $data['cyt_quo']; ?></td>
                                                    <td><?= $data['production_time']; ?></td>
                                                    <td><?= $data['ot_mh']; ?></td>
                                                    <td><?= number_format(($data['production_time']/$data['nwt_mp'])*100,2); ?>%</td>
                                                    <td></td>
                                                    <td><?= $data['qty_lt']; ?></td>
                                                    <td><?= $data['operator']; ?></td>
                                                    <td><?= $data['keterangan']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>M/C</th>
                                                    <th>Sft</th>
                                                    <th>Qty OK</th>
                                                    <th>Qty NG</th>
                                                    <th>% NG</th>
                                                    <th>Gross Prod</th>
                                                    <th>Nett Prod</th>
                                                    <th>CT Std2</th>
                                                    <th>CT Set</th>
                                                    <th>CT Quo</th>
                                                    <th>WH</th>
                                                    <th>OT</th>
                                                    <th>Mach Use</th>
                                                    <th>CalcDT</th>
                                                    <th>Stop Time</th>
                                                    <th>Operator</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </tfoot>
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

