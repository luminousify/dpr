 <title>DPR | DPR Online</title>
 <?php $this->load->view('layout/sidebar'); ?>

 <link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

 <link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

 <style>
     th,
     td {
         white-space: nowrap;
     }

     div.dataTables_wrapper {
         width: auto;
         /*width: 1000px;*/
         height: auto;
         margin: 0 auto;
     }

     tr {
         background-color: white
     }
     }
 </style>
 <div class="wrapper wrapper-content animated fadeInRight">
     <div class="row">
         <div class="col-lg-12">
             <div class="ibox ">
                 <div class="ibox-title">
                     <div class="d-flex">
                         <div class="mr-auto p-2">
                             <h5>DPR Online</h5>
                         </div>
                         <div class="p-2">
                             <?php
                                $posisi = $this->session->userdata('posisi');
                                if ($posisi == 'Kasie') { ?>

                                 <a href="<?php echo base_url('c_dpr/add_dpr') ?>" class="btn btn-sm btn-info">Add DPR Online</a>
                                 <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#myModal2">Data Verifikasi Kanit</button>
                                 <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">Verifikasi DPR</button>
                                 <!-- <a href="<?php echo base_url('c_dpr/verifikasi_dpr_by_kasi') ?>" class="btn btn-sm btn-danger">Verifikasi DPR Coba</a> -->
                             <?php } else if ($posisi == 'admin') { ?>
                                 <a href="<?php echo base_url('c_dpr/add_dpr') ?>" class="btn btn-sm btn-info">Add DPR Online</a>
                                 <button class="btn btn-sm btn-warning ml-2" data-toggle="modal" data-target="#myModal2">Data Verifikasi Kanit</button>
                                 <button class="btn btn-sm btn-success ml-2" data-toggle="modal" data-target="#myModal3">Data Verifikasi Kasi</button>
                             <?php } else {
                                    echo "";
                                }
                                ?>
                         </div>
                     </div>
                     <div class="ibox-tools">
                         <a class="collapse-link">
                             <i class="fa fa-chevron-up"></i>
                         </a>
                     </div>
                </div>
                <div class="ibox-content">
                    <!-- Success/Error Messages -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?= form_open('c_dpr/dpr'); ?>
                    <div class="card rounded mb-4">
                         <div class="card-header">
                             <h2>Filter Data</h2>
                         </div>
                         <div class="card-body">
                             <div class="row" style="margin-left:2px;">
                                 <div class="col-sm-4"> <b>Tanggal Dari (mm/dd/yyyy)</b>
                                     <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>">
                                 </div>
                                 <div class="col-sm-4"> <b>Tanggal Sampai (mm/dd/yyyy)</b>
                                     <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>">
                                 </div>
                                 <div class="col"> <b>Shift</b>
                                     <select name="shift" class="form-control">
                                         <option <?php if ($shift == 'All') {
                                                        echo "selected";
                                                    } ?> value='All'>All</option>
                                         <option <?php if ($shift == '1') {
                                                        echo "selected";
                                                    } ?> value='1'>1</option>
                                         <option <?php if ($shift == '2') {
                                                        echo "selected";
                                                    } ?> value='2'>2</option>
                                         <option <?php if ($shift == '3') {
                                                        echo "selected";
                                                    } ?> value='3'>3</option>
                                     </select>
                                 </div>
                                 <div class="col"> <br /><input type="submit" name="show" class="btn btn-primary" value="Show"></div>
                             </div>
                         </div>
                     </div>
                     <?= form_close(); ?>


                     <div class="table-responsive">
                         <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                             <thead>
                                 <tr style="text-align: center;">
                                     <?php
                                        $posisi = $this->session->userdata('posisi');
                                        if ($posisi == 'kanit') { ?>
                                         <th>No.</th>
                                         <th><b>Tanggal</b></th>
                                         <th><b>Mesin</b></th>
                                         <th><b>Shift</b></th>
                                         <th><b>Kanit</b></th>
                                         <th><b>CT Std</b></th>
                                         <th><b>Gross</b></th>
                                         <th><b>Nett</b></th>
                                         <th><b>Prod Name</b></th>
                                         <th><b>Qty OK</b></th>
                                         <th><b>Qty NG</b></th>
                                         <th><b>LT</b></th>
                                         <th><b>Keterangan</b></th>
                                         <th><b>Cutting Tool</b></th>
                                         <th><b>Verif. Kanit</b></th>
                                         <th><b>View</b></th>
                                     <?php } else { ?>
                                         <th>No.</th>
                                         <th><b>Tanggal</b></th>
                                         <th><b>Mesin</b></th>
                                         <th><b>Shift</b></th>
                                         <th><b>Group Shift</b></th>
                                         <th><b>Shift Hr</b></th>
                                         <th><b>W H</b></th>
                                         <th><b>O T</b></th>
                                         <th><b>Total LT</b></th>
                                         <th><b>Product ID</b></th>
                                         <th><b>Product Name</b></th>
                                         <th><b>Matl Lot No</b></th>
                                         <th><b>Tool</b></th>
                                         <th><b>OK</b></th>
                                         <th><b>WIP</b></th>
                                         <th><b>Target Qty</b></th>
                                         <th><b>Total Defect</b></th>
                                         <th><b>CT Std</b></th>
                                         <th><b>CT Actual</b></th>
                                         <th><b>N-Prod</b></th>
                                         <th><b>G-Prod</b></th>
                                         <th><b>NG-Bending</b></th>
                                         <th><b>NG-Berawan</b></th>
                                         <th><b>NG-Blackdot</b></th>
                                         <th><b>NG-Broken</b></th>
                                         <th><b>NG-Crack</b></th>
                                         <th><b>NG-Dent</b></th>
                                         <th><b>NG-Dirty</b></th>
                                         <th><b>NG-Discolour</b></th>
                                         <th><b>NG-Ejector Mark</b></th>
                                         <th><b>NG-Flash</b></th>
                                         <th><b>NG-Flow Gate</b></th>
                                         <th><b>NG-Flow Mark</b></th>
                                         <th><b>NG-Foreign Material</b></th>
                                         <th><b>NG-Gas Burn</b></th>
                                         <th><b>NG-Gas Mark</b></th>
                                         <th><b>NG-Gate Bolong</b></th>
                                         <th><b>NG-Gate Long</b></th>
                                         <th><b>NG-Hangus</b></th>
                                         <th><b>NG-Hike</b></th>
                                         <th><b>NG-Oil</b></th>
                                         <th><b>NG-Oversize</b></th>
                                         <th><b>NG-Pin Plong</b></th>
                                         <th><b>NG-Pin Seret</b></th>
                                         <th><b>NG-Scratch</b></th>
                                         <th><b>NG-Settingan</b></th>
                                         <th><b>NG-Short Shoot</b></th>
                                         <th><b>NG-Silver</b></th>
                                         <th><b>NG-Sink Mark</b></th>
                                         <th><b>NG-Undercut</b></th>
                                         <th><b>NG-Undersize</b></th>
                                         <th><b>NG-Void</b></th>
                                         <th><b>NG-Waving</b></th>
                                         <th><b>NG-Weld Line</b></th>
                                         <th><b>NG-White Dot</b></th>
                                         <th><b>NG-White Mark</b></th>
                                         <th><b>LT-Adjust Parameter</b></th>
                                         <th><b>LT-Pre Heating Material</b></th>
                                         <th><b>LT-Cleaning Hopper & Barrel</b></th>
                                         <th><b>LT-Set Up Mold</b></th>
                                         <th><b>LT-Set Up Parameter Machine</b></th>
                                         <th><b>LT-IPQC Inspection</b></th>
                                         <th><b>LT-No Packing</b></th>
                                         <th><b>LT-No Material</b></th>
                                         <th><b>LT-Material Problem</b></th>
                                         <th><b>LT-No Operator</b></th>
                                         <th><b>LT-Daily Checklist</b></th>
                                         <th><b>LT-Overhoule Mold</b></th>
                                         <th><b>LT-Mold Problem</b></th>
                                         <th><b>LT-Trial</b></th>
                                         <th><b>LT-Machine</b></th>
                                         <th><b>LT-Hopper Dryer</b></th>
                                         <th><b>LT-Robot</b></th>
                                         <th><b>LT-MTC</b></th>
                                         <th><b>LT-Cooling Tower</b></th>
                                         <th><b>LT-Compressor</b></th>
                                         <th><b>LT-Listrik</b></th>
                                         <th><b>LT-QC Lolos</b></th>
                                         <th><b>Calc DT</b></th>
                                         <th><b>Delta DT</b></th>
                                         <th><b>Mach. Use</b></th>
                                         <th><b>Matl Kg/pcs</b></th>
                                         <th><b>% OT</b></th>
                                         <th><b>Runner</b></th>
                                         <th><b>NG Start Up</b></th>
                                         <th><b>Loss Purging Matl</b></th>
                                         <th><b>Set Up Awal</b></th>
                                         <th><b>Trial</b></th>
                                         <th><b>Operator</b></th>
                                         <th><b>Kanit</b></th>
                                         <th><b>Customer</b></th>
                                         <th><b>Keterangan</b></th>
                                         <th><b>Cutting Tool</b></th>
                                         <th><b>Verif. Kanit</b></th>
                                         <th><b>Verif. Kasi</b></th>
                                         <th><b>View</b></th>
                                         <?php
                                            $user = $this->session->userdata('posisi');
                                            if ($user == 'admin') { ?>
                                             <th><b>Edit</b></th>
                                             <th><b>Delete</b></th>
                                         <?php } else {
                                                echo "";
                                            }
                                            ?>
                                     <?php }
                                        ?>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php
                                    $total = 0;
                                    $total_nwt = 0;
                                    $total_ot = 0;
                                    $total_ng = 0;
                                    $total_lt = 0;
                                    $view       = base_url('index.php/c_new/view_production_reporting_op');
                                    $edit       = base_url('index.php/c_new/edit_production_reporting_op');
                                    $delete     = base_url('index.php/c_new/del_production_reporting_op');
                                    $update       = base_url('index.php/c_dpr/update_verif_kanit');
                                    $user       = $this->session->userdata('posisi');
                                    $nama       = $this->session->userdata('nama_actor');
                                    $no = 0;
                                    foreach ($data_tabel->result_array() as $data) {
                                        $no++;
                                        // qty_lt is now in MINUTES, convert to hours for CDT calculation
                                        $cdt = (($data['nwt_mp'] + $data['ot_mp']) - ($data['production_time'] + ($data['qty_lt'] / 60)));
                                        $cdt_new = round($cdt, 1);
                                        $hasil = $data['cek_kanit'];
                                        $hasil2 = $data['cek_kasi'];

                                        if ($user == 'kanit') {
                                            if ($hasil == 1) {
                                                $background = '#9dff9d';
                                            } else {
                                                $background = '#02b2c2';
                                            }
                                        } else {
                                            if ($hasil2 == 1) {
                                                $background = '#9dff9d';
                                            } else {
                                                $background = '#02b2c2';
                                            }
                                        }

                                        if ($user == 'kanit') {
                                            echo '<tr >';
                                            echo '<td style="background-color:' . $background . '">' . $no . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['tanggal'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['mesin'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['shift'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['kanit'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['ct_mc'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">a' . $data['gross_prod'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['nett_prod'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['kode_product'] . ' - ' . $data['nama_product'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['qty_ok'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['qty_ng'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['qty_lt'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['keterangan'] . '</td>';
                                            echo '<td style="background-color:' . $background . '">' . (!empty($data['cutting_tools_codes']) ? '<b>' . $data['cutting_tools_codes'] . '</b>' : '') . '</td>';
                                            if ($hasil == null) {
                                                echo '<td style="background-color:' . $background . '"><a href="' . $update . '/' . $data['id_production_op'] . '"><center><button class="btn btn-primary btn-circle update"><i class="fa fa-check"></i></button></center></a>
                        </td>';
                                            } else {
                                                echo '<td style="background-color:' . $background . '"><center><h5 style="color:#000000;font-size:14px"><strong>Sudah Verifikasi</strong></h5></center>
                    </td>';
                                            }
                                            echo '<td style="background-color:' . $background . '"><a href="' . $view . '/' . $data['id_production'] . '"><center><button class="btn btn-success btn-circle"><i class="fa fa-search"></i></button></center></a></td>';
                                        } else {
                                            echo '<tr >';
                                            echo '<td style="background-color:' . $background . '"><b>' . $no . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['tanggal'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['mesin'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['shift'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['group'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['nwt_mp'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['production_time'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['ot_mp'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['qty_lt'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['kode_product'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['nama_product'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '">' . $data['lot_global'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['tooling'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['qty_ok'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b></b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['target_mc'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['qty_ng'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['ct_mc'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['ct_mc_aktual'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['nett_prod'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['gross_prod'] . '</b></td>';

                                            echo '<td style="background-color:' . $background . '"><b>' . $data['bending'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['berawan'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['blackdot'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['broken'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['crack'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['dent'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['dirty'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['discolour'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['ejector_mark'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['flash'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['flow_gate'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['flow_mark'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['fm'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['gas_burn'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['gas_mark'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['gate_bolong'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['gate_long'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['hangus'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['hike'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['oil'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['oversize'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['pin_plong'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['pin_seret'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['scratch'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['settingan'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['short_shoot'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['silver'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['sink_mark'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['undercut'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['under_size'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['void'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['waving'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['weld_line'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['white_dot'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['white_mark'] . '</b></td>';

                                            echo '<td style="background-color:' . $background . '"><b>' . $data['adjust_parameter'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['pre_heating_material'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['cleaning'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['set_up_mold'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['set_up_par_machine'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['ipqc_inspection'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['no_packing'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['no_material'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['material_problem'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['no_operator'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['daily_check_list'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['overhoule_mold'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['mold_problem'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['trial'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['machine'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['hopper_dryer'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['robot'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['mtc'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['cooling_tower'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['compressor'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['listrik'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['qc_lolos'] . '</b></td>';

                                            echo '<td style="background-color:' . $background . '"><b>' . $cdt_new . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b></b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['mach_use'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b></b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['persen_ot'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['runner'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b></b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['loss_purge'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b></b></td>';
                                            echo '<td style="background-color:' . $background . '"><b></b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['operator'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['kanit'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['customer'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '"><b>' . $data['keterangan'] . '</b></td>';
                                            echo '<td style="background-color:' . $background . '">' . (!empty($data['cutting_tools_codes']) ? '<b>' . $data['cutting_tools_codes'] . '</b>' : '') . '</td>';

                                            //verif kanit
                                            if ($data['cek_kanit'] == 1) {
                                                $outputnya = '<center><button class="btn btn-primary btn-circle"><i class="fa fa-check"></i></button></center>';
                                            } else {
                                                $outputnya = '<center><button class="btn btn-danger btn-circle"><i class="fa fa-close"></i></button></center>';
                                            }
                                            echo '<td style="background-color:' . $background . '">' . $outputnya . '</td>';

                                            //verif kasi
                                            if ($data['cek_kasi'] == 1) {
                                                $outputnya = '<center><button class="btn btn-sm btn-primary btn-circle" style="font-size : 12px"><i class="fa fa-check"></i></button> by ' . $data['pic_kasi'] . '</center>';
                                            } else {
                                                $outputnya = '<center><button class="btn btn-danger btn-circle"><i class="fa fa-close"></i></button></center>';
                                            }
                                            if ($user !==  'kanit') {
                                                echo '<td style="background-color:' . $background . '">' . $outputnya . '</td>';
                                            } else {
                                                echo "";
                                            }

                                            //action
                                            echo '<td style="background-color:' . $background . '"><a href="' . $view . '/' . $data['id_production'] . '"><center><button class="btn btn-success btn-circle"><i class="fa fa-search"></i></button></center></a></td>';
                                            if ($user ==  'admin') {
                                                echo '<td style="background-color:' . $background . '"><a href="' . $edit . '/' . $data['id_production'] . '"><center><button class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></button></center></a></td>';
                                                echo '<td style="background-color:' . $background . '"><a href="' . $delete . '/' . $data['id_production'] . '"><center><button class="btn btn-danger btn-circle delete"><i class="fa fa-trash"></i></button></center></a></td>';
                                            } else {
                                                echo "";
                                            }
                                            echo '</tr>';
                                        }
                                        $total      += $data['qty_ok'];
                                        $total_nwt  += $data['nwt_mp'];
                                        $total_ot   += $data['ot_mp'];
                                        $total_ng   += $data['qty_ng'];
                                        $total_lt   += $data['qty_lt'];
                                    }
                                    ?>
                             </tbody>
                             <tfoot>
                                 <tr style="text-align: center;">
                                     <?php
                                        $posisi = $this->session->userdata('posisi');
                                        if ($posisi == 'kanit') { ?>
                                         <th>No.</th>
                                         <th><b>Tanggal</b></th>
                                         <th><b>Mesin</b></th>
                                         <th><b>Shift</b></th>
                                         <th><b>Kanit</b></th>
                                         <th><b>CT Std</b></th>
                                         <th><b>Gross</b></th>
                                         <th><b>Nett</b></th>
                                         <th><b>Prod Name</b></th>
                                         <th><b>Qty OK</b></th>
                                         <th><b>Qty NG</b></th>
                                         <th><b>LT</b></th>
                                         <th><b>Keterangan</b></th>
                                         <th><b>Cutting Tool</b></th>
                                         <th><b>Verif. Kanit</b></th>
                                         <th><b>View</b></th>
                                     <?php } else { ?>
                                         <th>No.</th>
                                         <th><b>Tanggal</b></th>
                                         <th><b>Mesin</b></th>
                                         <th><b>Shift</b></th>
                                         <th><b>Group Shift</b></th>
                                         <th><b>Shift Hr</b></th>
                                         <th><b>W H</b></th>
                                         <th><b>O T</b></th>
                                         <th><b>Total LT</b></th>
                                         <th><b>Product ID</b></th>
                                         <th><b>Product Name</b></th>
                                         <th><b>Matl Lot No</b></th>
                                         <th><b>Tool</b></th>
                                         <th><b>OK</b></th>
                                         <th><b>WIP</b></th>
                                         <th><b>Target Qty</b></th>
                                         <th><b>Total Defect</b></th>
                                         <th><b>CT Std</b></th>
                                         <th><b>CT Actual</b></th>
                                         <th><b>N-Prod</b></th>
                                         <th><b>G-Prod</b></th>
                                         <th><b>NG-Bending</b></th>
                                         <th><b>NG-Berawan</b></th>
                                         <th><b>NG-Blackdot</b></th>
                                         <th><b>NG-Broken</b></th>
                                         <th><b>NG-Crack</b></th>
                                         <th><b>NG-Dent</b></th>
                                         <th><b>NG-Dirty</b></th>
                                         <th><b>NG-Discolour</b></th>
                                         <th><b>NG-Ejector Mark</b></th>
                                         <th><b>NG-Flash</b></th>
                                         <th><b>NG-Flow Gate</b></th>
                                         <th><b>NG-Flow Mark</b></th>
                                         <th><b>NG-Foreign Material</b></th>
                                         <th><b>NG-Gas Burn</b></th>
                                         <th><b>NG-Gas Mark</b></th>
                                         <th><b>NG-Gate Bolong</b></th>
                                         <th><b>NG-Gate Long</b></th>
                                         <th><b>NG-Hangus</b></th>
                                         <th><b>NG-Hike</b></th>
                                         <th><b>NG-Oil</b></th>
                                         <th><b>NG-Oversize</b></th>
                                         <th><b>NG-Pin Plong</b></th>
                                         <th><b>NG-Pin Seret</b></th>
                                         <th><b>NG-Scratch</b></th>
                                         <th><b>NG-Settingan</b></th>
                                         <th><b>NG-Short Shoot</b></th>
                                         <th><b>NG-Silver</b></th>
                                         <th><b>NG-Sink Mark</b></th>
                                         <th><b>NG-Undercut</b></th>
                                         <th><b>NG-Undersize</b></th>
                                         <th><b>NG-Void</b></th>
                                         <th><b>NG-Waving</b></th>
                                         <th><b>NG-Weld Line</b></th>
                                         <th><b>NG-White Dot</b></th>
                                         <th><b>NG-White Mark</b></th>
                                         <th><b>LT-Adjust Parameter</b></th>
                                         <th><b>LT-Pre Heating Material</b></th>
                                         <th><b>LT-Cleaning Hopper & Barrel</b></th>
                                         <th><b>LT-Set Up Mold</b></th>
                                         <th><b>LT-Set Up Parameter Machine</b></th>
                                         <th><b>LT-IPQC Inspection</b></th>
                                         <th><b>LT-No Packing</b></th>
                                         <th><b>LT-No Material</b></th>
                                         <th><b>LT-Material Problem</b></th>
                                         <th><b>LT-No Operator</b></th>
                                         <th><b>LT-Daily Checklist</b></th>
                                         <th><b>LT-Overhoule Mold</b></th>
                                         <th><b>LT-Mold Problem</b></th>
                                         <th><b>LT-Trial</b></th>
                                         <th><b>LT-Machine</b></th>
                                         <th><b>LT-Hopper Dryer</b></th>
                                         <th><b>LT-Robot</b></th>
                                         <th><b>LT-MTC</b></th>
                                         <th><b>LT-Cooling Tower</b></th>
                                         <th><b>LT-Compressor</b></th>
                                         <th><b>LT-Listrik</b></th>
                                         <th><b>LT-QC Lolos</b></th>
                                         <th><b>Calc DT</b></th>
                                         <th><b>Delta DT</b></th>
                                         <th><b>Mach. Use</b></th>
                                         <th><b>Matl Kg/pcs</b></th>
                                         <th><b>% OT</b></th>
                                         <th><b>Runner</b></th>
                                         <th><b>NG Start Up</b></th>
                                         <th><b>Loss Purging Matl</b></th>
                                         <th><b>Set Up Awal</b></th>
                                         <th><b>Trial</b></th>
                                         <th><b>Operator</b></th>
                                         <th><b>Kanit</b></th>
                                         <th><b>Customer</b></th>
                                         <th><b>Keterangan</b></th>
                                         <th><b>Cutting Tool</b></th>
                                         <th><b>Verif. Kanit</b></th>
                                         <th><b>Verif. Kasi</b></th>
                                         <th><b>View</b></th>
                                         <?php
                                            $user = $this->session->userdata('posisi');
                                            if ($user == 'admin') { ?>
                                             <th><b>Edit</b></th>
                                             <th><b>Delete</b></th>
                                         <?php } else {
                                                echo "";
                                            }
                                            ?>
                                     <?php }
                                        ?>
                                 </tr>
                             </tfoot>
                         </table>
                     </div>

                     <div class="row" style="margin-left:5px;">
                         <div class="col-lg">
                             <div class="widget style1 navy-bg">
                                 <div class="row vertical-align">
                                     <div class="col-3">
                                         <h2 class="font-bold">OK</h2>
                                     </div>
                                     <div class="col-9 text-right">
                                         <h2 class="font-bold"><?= $total; ?></h2>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg">
                             <div class="widget style1 red-bg">
                                 <div class="row vertical-align">
                                     <div class="col-3">
                                         <h2 class="font-bold">NG</h2>
                                     </div>
                                     <div class="col-9 text-right">
                                         <h2 class="font-bold"><?= $total_ng; ?></h2>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg">
                             <div class="widget style1 yellow-bg">
                                 <div class="row vertical-align">
                                     <div class="col-3">
                                         <h2 class="font-bold">LT</h2>
                                     </div>
                                     <div class="col-9 text-right">
                                         <h2 class="font-bold"><?= $total_lt; ?></h2>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg">
                             <div class="widget style1 lazur-bg">
                                 <div class="row vertical-align">
                                     <div class="col-3">
                                         <h2 class="font-bold">NWT</h2>
                                     </div>
                                     <div class="col-9 text-right">
                                         <h2 class="font-bold"><?= $total_nwt; ?></h2>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-2">
                             <div class="widget style1 navy-bg">
                                 <div class="row vertical-align">
                                     <div class="col-3">
                                         <h2 class="font-bold">OT</h2>
                                     </div>
                                     <div class="col-9 text-right">
                                         <h2 class="font-bold"><?= $total_ot; ?></h2>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

 </div>
 </div>


 <!-- Modal -->
 <form action="<?php echo base_url('c_dpr/edit_verif_bykasi') ?>" method="post">
     <?php foreach ($verif_kasi->result_array() as $data_new): ?>
     <?php endforeach; ?>

     <?php
        $nama = $this->session->userdata('nama_actor');
        ?>
     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Verifikasi Kasie</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>

                 <?php
                    if ($verif_kasi->result_array() == null) { ?>
                     <div class="modal-body text-center">
                         <h5>Tidak ada data!</h5>
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     </div>
                 <?php } else { ?>
                     <div class="modal-body">
                         <input type="hidden" class="form-control" name="tanggal" value="<?php echo $data_new['tanggal'] ?>">
                         <input type="hidden" class="form-control" name="shift" value="<?php echo $data_new['shift'] ?>">
                         <input type="hidden" class="form-control" name="pic_kasi" value="<?php echo $nama ?>">
                         <table class="table table-striped table-bordered table-hover dataTables-example4" style="width:100%">
                             <thead>
                                 <tr>
                                     <th><b>Verif. Kasi</b></th>
                                     <th><b>Verif. <br>Kanit</b></th>
                                     <!-- <th>No.</th>  -->
                                     <th><b>Mesin</b></th>
                                     <th><b>Tanggal</b></th>
                                     <th><b>Shift</b></th>
                                     <th><b>Kanit</b></th>
                                     <!-- <th><b>Mesin</b></th>  -->
                                     <th><b>CT Std (Sec)</b></th>
                                     <th><b>Gross (Sec)</b></th>
                                     <!-- <th><b>Product ID</b></th>  -->
                                     <th><b>Product Name</b></th>
                                     <!-- <th><b>Proses</b></th> -->
                                     <th><b>Qty OK</b></th>
                                     <th><b>Qty NG</b></th>
                                     <!-- <th><b>NWT (Jam)</b></th> 
                                        <th><b>CDT (Jam)</b></th>  -->
                                     <th><b>LT (Jam)</b></th>
                                     <!-- <th class="align-middle text-center"><b>Production Time (Jam)</b></th>  
                                        <th><b>Total Prod</b></th>
                                        <th><b>Runner (KG)</b></th> 
                                        <th><b>Loss Purge( KG)</b></th>
                                        <th><b>Lot Production</b></th>   
                                        <th><b>OT (Jam)</b></th>
                                        <th><b>CT Act (Sec)</b></th> 
                                        <th><b>Nett (Sec)</b></th>  -->
                                     <th><b>Ket.</b></th>
                                     <<!-- th><b>Operator</b></th>
                                         <th><b>PIC</b></th>
                                         <th><b>Customer</b></th> -->
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php
                                    $user       = $this->session->userdata('posisi');
                                    $nama       = $this->session->userdata('nama_actor');
                                    $no = 0;
                                    foreach ($verif_kasi->result_array() as $data) {
                                        $no++;
                                        $hasil = $data['cek_kanit'];
                                        $hasil2 = $data['cek_kasi'];
                                        if ($hasil == 1 && $hasil2 == 1) {
                                            $background = '#9dff9d';
                                        } else if ($hasil2 == 1) {
                                            $background = '#9dff9d';
                                        } else {
                                            $background = '#FFB6C1';
                                        }


                                        echo '<tr >';
                                        if ($data['cek_kasi'] == 1) {
                                            $outputnya = '<center><button class="btn btn-sm btn-primary btn-circle" style="font-size : 12px"><i class="fa fa-check"></i></button> by ' . $data['pic_kasi'] . '</center>';
                                        } else {
                                            $outputnya = '<center><button class="btn btn-danger btn-circle"><i class="fa fa-close"></i></button></center>';
                                        }
                                        echo '<td style="background-color:' . $background . '">' . $outputnya . '</td>';
                                        if ($data['cek_kanit'] == 1) {
                                            $outputnya = '<center><button class="btn btn-primary btn-circle"><i class="fa fa-check"></i></button></center>';
                                        } else {
                                            $outputnya = '<center><button class="btn btn-danger btn-circle"><i class="fa fa-close"></i></button></center>';
                                        }
                                        echo '<td style="background-color:' . $background . '">' . $outputnya . '</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['mesin'] . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$no.'</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['tanggal'] . '</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['shift'] . '</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['kanit'] . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['mesin'].'</td>';
                                        echo '<td style="background-color:' . $background . '">' . number_format($data['CTStd2'], 2) . '</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['gross_prod'] . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['kp_pr'].'</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['np_pr'] . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['proses'].'</td>';
                                        echo '<td style="background-color:' . $background . '">' . number_format($data['qty_ok']) . '</td>';
                                        echo '<td style="background-color:' . $background . '">' . number_format($data['qty_ng']) . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['nwt_mp'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$cdt_new.'</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['qty_lt'] . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['production_time'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.number_format($data['qty_ok'] + $data['qty_ng'] ).'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['runner'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['loss_purge'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['lot_global'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['ot_mp'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['ct_mc_aktual'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['nett_prod'].'</td>';
                                        echo '<td style="background-color:' . $background . '">' . $data['keterangan'] . '</td>';
                                        echo '<td style="background-color:' . $background . '">' . (!empty($data['cutting_tools_codes']) ? '<b>' . $data['cutting_tools_codes'] . '</b>' : '') . '</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['operator'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['pic'].'</td>';
                                        // echo '<td style="background-color:'.$background.'">'.$data['customer'].'</td>';                      
                                        echo '</tr>';
                                    }
                                    ?>
                             </tbody>
                         </table>
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <?php
                            if ($data_new['cek_kasi'] == 1) {
                                echo "";
                            } else { ?>
                             <button type="submit" class="btn btn-primary verif">Verifikasi</button>
                         <?php }
                            ?>
                     </div>
                 <?php }
                    ?>
             </div>
         </div>
     </div>
 </form>
 <!-- Modal Data verif kanit -->
 <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Data Verifikasi Kanit</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <table class="table table-striped table-bordered table-hover dataTables-example3" style="width:100%">
                     <thead>
                         <tr>
                             <th class="text-center align-middle">No.</th>
                             <th class="text-center align-middle"><b>Tanggal</b></th>
                             <th class="text-center align-middle"><b>Shift</b></th>
                             <th class="text-center align-middle"><b>Kanit</b></th>
                             <!-- <th class="text-center align-middle"><b>Total <br> Harus Input</b></th>  -->
                             <th class="text-center align-middle"><b>Total <br> Input</b></th>
                             <th class="text-center align-middle"><b>Total <br>Verifikasi</b></th>
                             <th class="text-center align-middle"><b>Total Belum <br>Verifikasi</b></th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            $no = 0;
                            foreach ($data_verif_kanit->result_array() as $data) {
                                $no++;
                                $hasil = $data['total_belum_verif'];
                                if ($hasil == 0) {
                                    $background = '#9dff9d';
                                } else {
                                    $background = '#FFB6C1';
                                }

                                echo '<tr >';
                                echo '<td style="background-color:' . $background . '">' . $no . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['tanggal'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['shift'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['kanit'] . '</td>';
                                // echo '<td style="background-color:'.$background.'">'.$data['target_dpr'].'</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['total_keseluruhan'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['total_verif'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['total_belum_verif'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                     </tbody>
                 </table>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal Data verif kasi -->
 <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Data Verifikasi Kasi</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <table class="table table-striped table-bordered table-hover dataTables-example3" style="width:100%">
                     <thead>
                         <tr>
                             <th class="text-center align-middle">No.</th>
                             <th class="text-center align-middle"><b>Tanggal</b></th>
                             <th class="text-center align-middle"><b>Shift</b></th>
                             <th class="text-center align-middle"><b>Total <br>DPR</b></th>
                             <th class="text-center align-middle"><b>Total <br>Verifikasi Kasi</b></th>
                             <th class="text-center align-middle"><b>PIC</b></th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            $no = 0;
                            foreach ($data_verif_kasi->result_array() as $data) {
                                $no++;
                                $dpr = $data['total_dpr'];
                                $verif_kasi = $data['total_cek_kasi'];
                                if ($dpr == $verif_kasi) {
                                    $background = '#9dff9d';
                                } else {
                                    $background = '#FFB6C1';
                                }

                                echo '<tr >';
                                echo '<td style="background-color:' . $background . '">' . $no . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['tanggal'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['shift'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['total_dpr'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['total_cek_kasi'] . '</td>';
                                echo '<td style="background-color:' . $background . '">' . $data['pic_kasi'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                     </tbody>
                 </table>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
     $(document).ready(function() {
         $('.dataTables-example tfoot th').each(function() {
             $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
         });
         $('.dataTables-example').DataTable({
             pageLength: 10,
             responsive: false,
             // scrollY:        "300px",
             scrollX: true,
             scrollCollapse: true,
             paging: true,
             fixedColumns: {
                 left: 3,
                 right: 2
             },
             dom: '<"html5buttons"B>lTfgitp',
             buttons: [{
                     extend: 'copy'
                 },
                 {
                     extend: 'csv'
                 },
                 {
                     extend: 'excel',
                     title: 'ExampleFile'
                 },
                 {
                     extend: 'pdf',
                     title: 'ExampleFile'
                 },

                 {
                     extend: 'print',
                     customize: function(win) {
                         $(win.document.body).addClass('white-bg');
                         $(win.document.body).css('font-size', '10px');

                         $(win.document.body).find('table')
                             .addClass('compact')
                             .css('font-size', 'inherit');
                     }
                 }
             ],
             initComplete: function() {
                 // Apply the search
                 this.api().columns().every(function() {
                     var that = this;

                     $('input', this.footer()).on('keyup change clear', function() {
                         if (that.search() !== this.value) {
                             that
                                 .search(this.value)
                                 .draw();
                         }
                     });
                 });
             }


         });
     });

     $(document).ready(function() {
         $('.dataTables-example3 tfoot th').each(function() {
             $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
         });
         $('.dataTables-example3').DataTable({
             pageLength: 10,
             responsive: false,
             // scrollY:        "300px",
             scrollX: true,
             scrollCollapse: true,
             paging: true,
             fixedColumns: {
                 left: 2,
                 // right: 2
             },
             dom: '<"html5buttons"B>lTfgitp',
             buttons: [{
                     extend: 'copy'
                 },
                 {
                     extend: 'csv'
                 },
                 {
                     extend: 'excel',
                     title: 'ExampleFile'
                 },
                 {
                     extend: 'pdf',
                     title: 'ExampleFile'
                 },

                 {
                     extend: 'print',
                     customize: function(win) {
                         $(win.document.body).addClass('white-bg');
                         $(win.document.body).css('font-size', '10px');

                         $(win.document.body).find('table')
                             .addClass('compact')
                             .css('font-size', 'inherit');
                     }
                 }
             ],
             initComplete: function() {
                 // Apply the search
                 this.api().columns().every(function() {
                     var that = this;

                     $('input', this.footer()).on('keyup change clear', function() {
                         if (that.search() !== this.value) {
                             that
                                 .search(this.value)
                                 .draw();
                         }
                     });
                 });
             }


         });
     });

     $(document).ready(function() {
         $('.dataTables-example4 tfoot th').each(function() {
             $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
         });
         $('.dataTables-example4').DataTable({
             pageLength: 10,
             responsive: false,
             // scrollY:        "300px",
             scrollX: true,
             scrollCollapse: true,
             paging: true,
             fixedColumns: {
                 left: 4,
                 // right: 2
             },
             dom: '<"html5buttons"B>lTfgitp',
             buttons: [{
                     extend: 'copy'
                 },
                 {
                     extend: 'csv'
                 },
                 {
                     extend: 'excel',
                     title: 'ExampleFile'
                 },
                 {
                     extend: 'pdf',
                     title: 'ExampleFile'
                 },

                 {
                     extend: 'print',
                     customize: function(win) {
                         $(win.document.body).addClass('white-bg');
                         $(win.document.body).css('font-size', '10px');

                         $(win.document.body).find('table')
                             .addClass('compact')
                             .css('font-size', 'inherit');
                     }
                 }
             ],
             initComplete: function() {
                 // Apply the search
                 this.api().columns().every(function() {
                     var that = this;

                     $('input', this.footer()).on('keyup change clear', function() {
                         if (that.search() !== this.value) {
                             that
                                 .search(this.value)
                                 .draw();
                         }
                     });
                 });
             }


         });
     });

     $(document).ready(function() {
         $('.delete').click(function() {
             return confirm("Are you sure you want to delete?");
         });
     });

     $(document).ready(function() {
         $('.update').click(function() {
             return confirm("Anda yakin ingin verifikasi data ini?");
         });
     });

     $(document).ready(function() {
         $('.verif').click(function() {
             return confirm("Anda yakin ingin verifikasi data ini? Pastikan shift sudah sesuai!");
         });
     });
 </script>