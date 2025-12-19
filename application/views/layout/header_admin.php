<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- <title>DPR Application</title> -->
    <meta name="description" content="Blueprint: Tooltip Menu" />
    <meta name="keywords" content="Tooltip Menu, navigation, tooltip, menu, css, web development, template" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/icons/tape.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/component.css" />
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap/bootstrap.min.css">
   <link href="<?php echo base_url('css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
   <link href="<?php echo base_url('css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/scripts/jquery.dataTables.min.css') ?>">
   <link rel="stylesheet" type="text/css" href="<?= base_url('assets/scripts/dataTables.fixedColumns.min.css') ?>">
  
 <script type="text/javascript" language="javascript" src="<?= base_url('assets/scripts/jquery-3.5.1.js') ?>"></script>
  <script type="text/javascript" language="javascript" src="<?= base_url('assets/scripts/jquery.dataTables.min.js'); ?>"></script>
  <script type="text/javascript" language="javascript" src="<?= base_url('assets/scripts/dataTables.fixedColumns.min.js'); ?>"></script>

  <!-- select -->
  <script type="text/javascript" src="<?php echo base_url(); ?>/autocomplete/js/jquery.multiple.select.js"></script>
<link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url(); ?>/autocomplete/css/multiple-select.css" />

<!-- MODALS -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style_modal.css"> 
   <script src="<?php echo base_url(); ?>js/modernizr.custom.js"></script>



  </head>
<style type="text/css">
  html {
    zoom: 100%;
   /* -moz-transform: scale(0.80);
    -webkit-transform: scale(0.80);
    transform: scale(0.80);*/
  }

  
input[type=text] , input[type=date] , input[type=search] , input[type=password] {
      height: 29px;
      padding: 12px 12px;
    }

  .demo-table3 {
    border-collapse: collapse;
    font-size: 14px;
    /*min-width: 537px;*/
  }

  .demo-table3 th, 
  .demo-table3 td {
    /*border: 1px solid #e1edff;*/
    padding: 7px 17px;

  }

  /* Table Header */
  .demo-table3 thead th {
    background-color: #508abb;
    color: #FFFFFF;
    border-color: #6ea1cc !important;
    text-transform: uppercase;
  }

    /* Table Body */
  .demo-table3 tbody td {
    color: #353535;
  }

  .demo-table3 tbody tr:hover td {
    background-color: #ffffa2;
    border-color: #ffff0f;
  }


/*input[type=text] , input[type=date] , input[type=search] , input[type=password] {
      height: 29px;
      padding: 12px 12px;
    } */
</style>
  <body style="background-color: #ecf0f5;">
      <ul id="cbp-tm-menu" class="cbp-tm-menu" style="position: relative;" id="navbarNavAltMarkup">
        <li><a href="<?php echo base_url(); ?>index.php/c_new/home">Home</a></li>
        <li>
          <a href="#">Master Data</a></a>
          <ul class="cbp-tm-submenu" >
            <li><a href="<?php echo base_url(); ?>index.php/c_master/v_product" class="cbp-tm-icon-archive">Product</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/c_master/v_bom" class="cbp-tm-icon-cog">Bill Of Material</a></li>
                  <li><a href="<?php echo base_url(); ?>index.php/c_master/v_mesin" class="cbp-tm-icon-archive">Machine</a></li>
                  <li><a href="<?php echo base_url(); ?>index.php/c_master/v_operator" class="cbp-tm-icon-users">Operator & kanit</a></li>
                  <li><a href="<?php echo base_url(); ?>index.php/c_master/v_defect" class="cbp-tm-icon-cog">Defect & Loss time</a></li>
                  <li><a href="<?php echo base_url(); ?>index.php/c_master/v_change_password" class="cbp-tm-icon-users">Change Password</a></li>
          </ul>
        </li>
        <li><a href="<?php echo base_url(); ?>index.php/c_new/v_planning">Planning</a></li>
        <!-- <li><a href="<?php //echo base_url(); ?>index.php/c_new/v_production">Production</a></li> -->
        <li>
          <a href="#">Transaksi</a>
          <ul class="cbp-tm-submenu">
            <li><a href="<?php echo base_url(); ?>index.php/c_new/v_receiving" class="cbp-tm-icon-archive">Receiving</a></li>
                  <li><a href="<?php echo base_url(); ?>index.php/c_new/v_receiving_approved" class="cbp-tm-icon-pencil">Receiving Approval</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/c_new/v_shipping" class="cbp-tm-icon-location">Shipping</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/c_new/adjustment_view" class="cbp-tm-icon-archive">BI</a></li>
          </ul>
        </li>
        <li>
          <a href="#">Inventory</a>
          <ul class="cbp-tm-submenu">
            <li><a href="<?php echo base_url(); ?>index.php/c_new/v_stock" class="cbp-tm-icon-archive">By Product</a></li>
              <li><a href="<?php echo base_url(); ?>index.php/c_new/v_control_lot" class="cbp-tm-icon-mobile">Control Sisa Lot</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/c_new/v_control_lot_manual/sisa_lotx" class="cbp-tm-icon-screen">EI vs Sisa Lot</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/c_new/v_control_lot_manual/sisa_lotngx" class="cbp-tm-icon-archive">EI vs Sisa Lot NG</a></li>
          </ul>
        </li>
        <li>
          <a href="#">Report</a>
          <ul class="cbp-tm-submenu">
            <li><a href="<?php echo base_url('index.php/c_new/v_ok_shift'); ?>" class="cbp-tm-icon-users">Production By Shift</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_ok_day'); ?>" class="cbp-tm-icon-archive">Production By Day</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_ok_machine'); ?>" class="cbp-tm-icon-cog">Production By Machine</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_ok_jam_kerja_by_machine'); ?>" class="cbp-tm-icon-cog">Production By NWT</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_reporting_production_again'); ?>" class="cbp-tm-icon-archive">Production Report</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_data_summary'); ?>" class="cbp-tm-icon-screen">Production By Summary</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_data_defect'); ?>" class="cbp-tm-icon-cog">Production By  Defect</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_data_stoptime'); ?>" class="cbp-tm-icon-link">Production By Loss Time</a></li>
                  <li><a href="<?php echo base_url('index.php/c_new/v_bi_rc_ship'); ?>" class="cbp-tm-icon-archive">Reporting BI - RI - Ship</a></li>
                  <!-- <li><a href="<?php //echo base_url('index.php/c_new/v_bi_rc_ship_total'); ?>" class="cbp-tm-icon-archive">BI - Receiving - Shiping By Product (Total)</a></li> -->
                  <!-- li><a href="<?php //echo base_url('index.php/c_new/v_bi_rc_ship_bom'); ?>" class="cbp-tm-icon-archive">BI - Receiving - Shiping By BOM</a></li> -->
            <li><a href="<?php echo base_url('index.php/c_new/v_summary_shipping'); ?>" class="cbp-tm-icon-archive">Reporting Summary RI & Ship</a></li>
             <li><a href="<?php echo base_url('index.php/c_new/v_production_op'); ?>" class="cbp-tm-icon-screen">DPR Online Operator</a></li>
          </ul>
        </li>
        <li><a href="<?php echo base_url('index.php/c_new/v_production_op'); ?>" >DPR Online</a></li>
        <li>
          <a href="#">Label</a>
          <ul class="cbp-tm-submenu">
          <li><a href="<?php echo base_url('index.php/c_label/label_dpr'); ?>">DPR</a></li>
          <li><a href="<?php echo base_url('index.php/c_label/label_kanban'); ?>">Kanban</a></li>
          </ul>
        </li>
        <li>
          <a href="#">History</a>
          <ul class="cbp-tm-submenu">
          <li><a href="<?php echo base_url('index.php/c_label/history/info'); ?>">Info</a></li>
          <li><a href="<?php echo base_url('index.php/c_label/history/history_info'); ?>">Lot Berubah</a></li>
          </ul>
        </li>
        <li><a onclick="return confirm('Apakah anda yakin akan LOGOUT ?')" href="<?php echo base_url(); ?>index.php/login_control/logout">Logout</a></li>
  </ul>
      <!-- <div class="filler-below"></div> -->
    <!-- </div> -->
    <script src="<?php echo base_url(); ?>js/bootstrap.js"></script> 
    <script src="<?php echo base_url(); ?>js/cbpTooltipMenu.min.js"></script>
    <script>
      var menu = new cbpTooltipMenu( document.getElementById( 'cbp-tm-menu' ) );
    </script>
</html>
