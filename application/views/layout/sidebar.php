<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">



    <link href="<?= base_url(); ?>template/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="<?= base_url(); ?>template/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="<?= base_url(); ?>template/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="<?= base_url(); ?>template/css/animate.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/css/select2.min.css" rel="stylesheet">

</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <!-- <img alt="image" class="rounded-circle" src="img/profile_small.jpg"/> -->
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="block m-t-xs font-bold"><?= $data['user_name']; ?> - <?= $_SESSION['nama_actor'] ?></span>
                                <span class="text-muted text-xs block"><?= $data['bagian']; ?> <b class="caret"></b></span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                                <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>
                                <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('login_control/logout') ?>">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            DPR
                        </div>
                    </li>

                    <?php
                    $posisi = $this->session->userdata('posisi');
                    if ($posisi == 'kanit') { ?>
                        <li class="<?php if ($aktif == 'dashboard') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_new/home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>
                        <li class="<?php if ($aktif == 'machine') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_machine/index"><i class="fa fa-cubes"></i> <span class="nav-label">Daftar Op. Mesin</span> </a>
                        </li>
                        <li class="<?php if ($aktif == 'dpr') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_dpr/dpr"><i class="fa fa-pencil-square-o"></i> <span class="nav-label">DPR</span> </a>
                        </li>
                        <li class="<?php if ($aktif == 'production_plan') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_production_plan/production_plan"><i class="fa fa-database"></i> <span class="nav-label">Production Plan Harian</span> </a>
                        </li>
                        <li class="<?php if ($aktif == 'report') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-table"></i> <span class="nav-label">Report</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_ok/ok">Total Produksi OK</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_ng/ng">Total Produksi NG</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_lt/lt">Total Produksi LT</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/production_time/production_time">Total Produksi NWT</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_ot/ot">Total Produksi OT</a></li>

                            </ul>
                        </li>
                    <?php  } else  if ($posisi == 'ppic') { ?>
                        <li class="<?php if ($aktif == 'dashboard') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_new/home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>
                        <li class="<?php if ($aktif == 'production_plan') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_production_plan/production_plan"><i class="fa fa-database"></i> <span class="nav-label">Production Plan Harian</span> </a>
                        </li>

                    <?php  } else  if ($posisi == 'tm') { ?>
                        <li class="<?php if ($aktif == 'dashboard') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_new/home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>
                        <li class="<?php if ($aktif == 'production_plan') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_production_plan/production_plan"><i class="fa fa-database"></i> <span class="nav-label">Production Plan Harian</span> </a>
                        </li>

                    <?php  } else  if ($posisi == 'qa') { ?>
                        <li class="<?php if ($aktif == 'dashboard') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_new/home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>
                        <li class="<?php if ($aktif == 'production_plan') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_production_plan/production_plan"><i class="fa fa-database"></i> <span class="nav-label">Production Plan Harian</span> </a>
                        </li>

                    <?php  } else  if ($posisi == 'mixer') { ?>
                        <li class="<?php if ($aktif == 'dashboard') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_new/home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>
                        <!-- <li class="<?php if ($aktif == 'runner') {
                                            echo 'active';
                                        } else {
                                            echo '';
                                        } ?>">
                            <a href="<?= base_url(); ?>c_runner/runner"><i class="fa fa-id-badge"></i> <span class="nav-label">Supply Material</span>  </a>
                        </li> -->
                        <li class="<?php if ($aktif == 'material_transaction') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-table"></i> <span class="nav-label">Material Transaction</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <!-- <li><a href="<?= base_url(); ?>c_material_transaction/material_transaction">Supply Material Harian</a></li> -->
                                <li><a href="<?= base_url(); ?>c_material_transaction/material_transaction_coba">Daily Supply Material</a></li>
                                <!-- <li><a href="<?= base_url(); ?>c_material_transaction/report_material_transaction">Report Supply Material</a></li> -->
                            </ul>
                        </li>



                    <?php  } else { ?>
                        <li class="<?php if ($aktif == 'dashboard') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_new/home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>

                        <li class="<?php if ($aktif == 'master') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Master Data</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="<?= base_url(); ?>c_new/master_product">Product</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_bom">BOM</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_mesin">Mesin</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_op">Operator & Kanit</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_defect">Defect & Losstime</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_user">User</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_work_days">Work Days</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_target_produksi">Target Produksi</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_target_ppm">PPM Target</a></li>
                                <li><a href="<?= base_url(); ?>c_new/master_f_cost">F - Cost Target</a></li>
                                <li><a href="<?= site_url('c_new/cutting_tools') ?>">
                                        <i class="fa fa-wrench"></i> <span>Master Cutting Tools</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?php if ($aktif == 'transaksi') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-laptop"></i> <span class="nav-label">Transaksi</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="<?= base_url(); ?>c_transaksi/receiving">Receiving</a></li>
                                <li><a href="<?= base_url(); ?>c_transaksi/shipping">Shipping</a></li>
                            </ul>
                        </li>
                        <li class="<?php if ($aktif == 'machine') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_machine/index"><i class="fa fa-cubes"></i> <span class="nav-label">Daftar Op. Mesin</span> </a>
                        </li>

                        <li class="<?php if ($aktif == 'layar') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_machine/layar"><i class="fa fa-television"></i> <span class="nav-label">Screen Monitoring</span> </a>
                        </li>

                        <li class="<?php if ($aktif == 'production_plan') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_production_plan/production_plan"><i class="fa fa-database"></i> <span class="nav-label">Production Plan Harian</span> </a>
                        </li>
                        <li class="<?php if ($aktif == 'dpr') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_dpr/dpr"><i class="fa fa-pencil-square-o"></i> <span class="nav-label">DPR</span> </a>
                        </li>
                        <li class="<?php if ($aktif == 'report') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-table"></i> <span class="nav-label">Report</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_ok/qty_ok">Total Produksi OK</a></li>
                                <li><a href="<?= base_url(); ?>c_report/report_daily_ok">Total Produksi OK Daily</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_ng/qty_ng">Total Produksi NG</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/qty_lt/qty_lt">Total Produksi LT</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/production_time/production_time">Total Produksi NWT</a></li>
                                <li><a href="<?= base_url(); ?>c_dpr/report/ot_mp/ot_mp">Total Produksi OT</a></li>
                                <!-- <li><a href="<?= base_url(); ?>c_dpr/productivity">Productivity</a></li> -->

                            </ul>
                        </li>
                        <li class="<?php if ($aktif == 'inventory') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="<?= base_url(); ?>c_inventory/index"><i class="fa fa-pie-chart"></i> <span class="nav-label">History Part</span> </a>
                        </li>
                        <li class="<?php if ($aktif == 'global') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-sitemap"></i> <span class="nav-label">Report Global</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li>
                                    <!-- <a href="<?= base_url(); ?>c_report/productivity_quartal">Productivity</a> -->
                                    <a href="#"><span class="nav-label">Report Productivity</span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level collapse">
                                        <li><a href="<?= base_url(); ?>c_report/productivity_quartal">Productivity</a></li>
                                        <li><a href="<?= base_url(); ?>c_dpr/productivity_detail">Productivity Detail</a></li>
                                        <li><a href="<?= base_url(); ?>c_dpr/productivity_detail_by_part_by_month">Productivity Detail By Part By Month</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/worst_gross_nett">Worst Gross & Nett</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/productivity_detail">Prod. Detail By Machine</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/productivity_detail_by_part_by_month">Prod. Detail By Part</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/summary_prod_by_part">Summary Prod. By Part</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/productivity_by_month">Productivity by Month</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/productivity_by_part_by_machine_by_month">Productivity by Part by Machine by Month</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#"><span class="nav-label">Prod. Qty & PPM</span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level collapse">
                                        <li><a href="<?= base_url(); ?>c_report/production_qty_quartal">Production Qty & PPM</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/production_qty_ppm_detail">Production Qty & PPM Detail</a></li>
                                        <li><a href="<?= base_url(); ?>c_report/worst_defect_by_ppm">Worst Defect by PPM</a></li>
                                    </ul>
                                </li>

                                <!-- <li><a href="<?= base_url(); ?>c_report/productionPPM">Production Qty & PPM</a></li> -->
                                <li><a href="<?= base_url(); ?>c_report/efesiencyMesin">Efesiency Mesin</a></li>
                                <li><a href="<?= base_url(); ?>c_report/defect">Defect</a></li>
                                <li><a href="<?= base_url(); ?>c_report/cutting_tool">Cutting Tool</a></li>
                                <li><a href="<?= base_url(); ?>c_report/losstime">Loss Time</a></li>
                                <li><a href="<?= base_url(); ?>c_report/sevendata">7 Data</a></li>
                                <li><a href="<?= base_url(); ?>c_report/reportCT">Cycle Time</a></li>
                                <li><a href="<?= base_url(); ?>c_machine/machine_use">Machine Use</a></li>
                                <li><a href="<?= base_url(); ?>c_report/kanit_perform">Kanit Performance</a></li>
                                <li><a href="<?= base_url(); ?>c_report/report_akunting">Accounting Report</a></li>
                                <li><a href="<?= base_url(); ?>c_report/report_qty_by_cust">Qty by Customer</a></li>
                                <li><a href="<?= base_url(); ?>c_report/report_qty_by_cust_yearly">Qty by Customer Yearly</a></li>
                            </ul>
                        </li>
                        <li class="<?php if ($aktif == 'runner') {
                                        echo 'active';
                                    } else {
                                        echo '';
                                    } ?>">
                            <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Supply Material</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="<?= base_url(); ?>c_runner/runner">Runner</a></li>
                                <li><a href="<?= base_url(); ?>c_material_transaction/material_transaction_coba">Daily Supply Material</a></li>
                                <li>
                                    <a href="#"><span class="nav-label">Report Supply Material</span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level collapse">
                                        <li><a href="<?= base_url(); ?>c_material_transaction/report_material_transaction_by_machine">By Machine</a></li>
                                        <li><a href="<?= base_url(); ?>c_material_transaction/report_material_transaction_by_part">By Part</a></li>
                                        <li><a href="<?= base_url(); ?>c_material_transaction/report_material_transaction_by_material">By Material</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    <?php  }
                    ?>
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li style="padding: 20px">
                            <span class="m-r-sm text-muted welcome-message">Welcome To DPR Website</span>
                        </li>
                        <!--  <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages dropdown-menu-right">
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="img/a7.jpg">
                                </a>
                                <div class="media-body">
                                    <small class="float-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="img/a4.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="float-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="img/profile.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="float-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html" class="dropdown-item">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="profile.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="float-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="grid_options.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html" class="dropdown-item">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li> -->


                        <li>
                            <a href="<?php echo base_url('login_control/logout') ?>">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                        <li>
                            <a class="right-sidebar-toggle">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>