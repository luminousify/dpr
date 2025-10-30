 <title>DPR | Production Plan Harian</title>
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

<?php  
$posisi = $this->session->userdata('posisi');
if ($posisi == 'ppic' || $posisi == 'admin') { ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <div class="row">
                <div class="col mt-3">
                   
                    <label>Upload file excel disini (Format : Excel)</label>
                    <?php  
                 
                    if ($cek_isi_db[0]->total == 0) {
                        $tgl_hapus = base_url('c_production_plan/import_excel');
                    } else {
                        foreach ($production_plan as $get_tgl) :
                        endforeach;        
                        $tgl_hapus = base_url('c_production_plan/import_excel_coba/'.$get_tgl->tanggal);            
                    }
                    ?>
                    <!-- <a href="<?= base_url('c_production_plan/production_planAct/'); ?>"><button class="btn btn-success">Add Production Plan</button></a> -->
                    <form class="form-inline" method="post" action="<?php echo $tgl_hapus ?>" enctype="multipart/form-data">
                      <div class="form-group mx-sm-3 mb-1">

                        <input type="file" name="file" class="form-control">
                      </div>
                      <button class="btn btn-sm btn-primary submit" type="submit">Upload</button>
                      &nbsp;
                      
                      
                    </form>
                    <a href="<?= base_url('assets/Format Excel Production Plan.xls'); ?>" download>
                      <button class="btn btn-sm btn-info">Download Format Upload Satuan</button>
                    </a>
                    <a href="<?= base_url('assets/Format Excel Production Plan.xls'); ?>" download>
                      <button class="btn btn-sm btn-info">Download Format Upload Group</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
        </div>
    </div>
<?php  } else{
    echo "";
}
?>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <?= $this->session->flashdata('message');?>
                    <div class="ibox-title">
                        <h5>Production Plan Harian</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                        <div class="ibox-content">
                            <?= form_open('c_production_plan/production_plan/'); ?>  
                            <div class="card rounded mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fa fa-calendar"></i> Filter Data</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning mb-3">
                                        <i class="fa fa-info-circle"></i> <strong>Catatan:</strong> Data yang muncul hanya data pada <strong>hari ini saja</strong>. Jika ingin melihat data yang lain, silahkan gunakan fitur <strong>filter</strong> di bawah.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-2"> 
                                            <label><b><i class="fa fa-calendar"></i> Pilih Tanggal</b></label>
                                            <input type="date" name="tanggal" class="form-control" value="<?= $tanggal; ?>"> 
                                        </div>
                                        <div class="col-md-3 mb-2" style="margin-top:27px;">
                                            <button type="submit" name="show" class="btn btn-primary btn-sm">
                                                <i class="fa fa-search"></i> Show
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?= form_close(); ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Prod. Code</th>
                                            <th>Prod. Name</th>
                                            <th>Material Name</th>
                                            <th>Prod. Qty</th>
                                            <th>MC No.</th>
                                            <th>Tonnage MC</th>
                                            <th>Cycle Time</th>
                                            <th>Schedule Jam</th>
                                            <th>Set-Up Material Start</th>
                                            <th>Set-Up Material Finish</th>
                                            <th>Set-Up Mold Start</th>
                                            <th>Set-Up Mold Finish</th>
                                            <th>Set-Up Setting Start</th>
                                            <th>Set-Up Setting Finish</th>
                                            <th>Judgement QA Finish</th>
                                            <th>Total Lama Set-Up (Hour)</th>
                                            <th>Target (Hour)</th>
                                            <th>Group Set-Up</th>
                                            <th>Problem</th>
                                            <th>Status</th>
                                            <?php  
                                            $posisi = $this->session->userdata('posisi');
                                            if ($posisi == 'ppic') {
                                                echo '<th>Edit</th>';
                                            } else if ($posisi == 'qa') {
                                                echo '<th>Edit</th>';
                                            } else if ($posisi == 'tm') {
                                                echo '<th>Edit</th>';
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                            <?php  
                                            $posisi = $this->session->userdata('posisi');
                                            if ($posisi == 'ppic') {
                                                echo '<th>Delete</th>';
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $edit       = base_url('c_production_plan/production_planAct/prod_plan_harian/id');
                                        $delete     = base_url('c_production_plan/Delete/production_plan/prod_plan_harian/id');
                                            $no = 0 ; foreach($production_plan as $data) 
                                            {  $no++;
                                                $posisi = $this->session->userdata('posisi');
                                                echo '<tr>';
                                                echo '<td>'.$no;'</td>';
                                                echo '<td>'.$data->tanggal,'</td>';
                                                echo '<td>'.$data->kode_produk,'</td>';
                                                echo '<td>'.$data->nama_produk,'</td>';
                                                echo '<td>'.$data->material_name,'</td>';
                                                echo '<td>'.$data->prod_qty,'</td>';
                                                echo '<td>'.$data->no_mesin,'</td>';
                                                echo '<td>'.$data->tonnase,'</td>';
                                                echo '<td>'.$data->ct_mesin,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->jam,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->set_up_material_start,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->set_up_material_finish,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->set_up_mold_start,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->set_up_mold_finish,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->set_up_setting_start,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->set_up_start_setting_finish,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->judgement_qa_finish,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->difference,'</td>';
                                                echo '<td class="align-middle text-center">'.$data->target,'</td>';
                                                
                                                echo '<td>'.$data->group,'</td>';
                                                echo '<td>'.$data->masalah,'</td>';
                                                ?>
                                                    <?php
                                                        if ($data->pic_tm == null && $data->pic_qa == null) {
                                                            echo "<td style='background:#f0ad4e;color: white;'>Belum Diisi TM dan QA</td>";
                                                        } else if ($data->pic_tm == null) {
                                                            echo "<td style='background:#f0ad4e;color: white;'>Belum Diisi TM</td>";
                                                        } else if ($data->pic_qa == null) {
                                                            echo "<td style='background:#f0ad4e;color: white;'>Belum Diisi QA</td>";
                                                        } else {
                                                            if ($data->status_prod == 'Target') {
                                                               echo "<td style='background:#5bc0de;color: white;'>".$data->status_prod,"</td>";
                                                            } else {
                                                                  echo "<td style='background:#d9534f;color: white;'>".$data->status_prod,"</td>";
                                                            } 
                                                        }
                                                    ?>
                                                <?php
                                                if ($posisi == 'ppic') {
                                                    echo '<td><center><a href="'.$edit.'/'.$data->id.'"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a></td>';
                                                } else if ($posisi == 'qa') {
                                                    echo '<td><center><a href="'.$edit.'/'.$data->id.'"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a></td>';
                                                } else if ($posisi == 'tm') {
                                                    echo '<td><center><a href="'.$edit.'/'.$data->id.'"><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></a></td>';
                                                } else {
                                                    echo "";
                                                }
                                                
                                                if ($posisi == 'ppic') {
                                                    echo '<td><center><a href="'.$delete.'/'.$data->id.'"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a>
                                                    </td>';
                                                } else {
                                                    echo "";
                                                }
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Prod. Code</th>
                                            <th>Prod. Name</th>
                                            <th>Material Name</th>
                                            <th>Prod. Qty</th>
                                            <th>MC No.</th>
                                            <th>Tonnage MC</th>
                                            <th>Cycle Time</th>
                                            <th>Schedule Jam</th>
                                            <th>Set-Up Material Finish</th>
                                            <th>Set-Up Mold Finish</th>
                                            <th>Set-Up Start Setting Finish</th>
                                            <th>Judgement QA Finish</th>
                                            <th>Total Lama Set-Up (Hour)</th>
                                            <th>Target (Hour)</th>
                                            
                                            <th>Group Set-Up</th>
                                            <th>Problem</th>
                                            <th>Status</th>
                                            <?php  
                                            $posisi = $this->session->userdata('posisi');
                                            if ($posisi == 'ppic') {
                                                echo '<th>Edit</th>';
                                            } else if ($posisi == 'qa') {
                                                echo '<th>Edit</th>';
                                            } else if ($posisi == 'tm') {
                                                echo '<th>Edit</th>';
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                            <?php  
                                            $posisi = $this->session->userdata('posisi');
                                            if ($posisi == 'ppic') {
                                                echo '<th>Delete</th>';
                                            } else {
                                                echo "";
                                            }
                                            ?>
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
                        right: 2
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

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

    </script>

    
