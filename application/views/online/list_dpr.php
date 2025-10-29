<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
	  <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
	  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
	  <link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-icons.css'?>">
	  <!--Datatables Bootstrap -->
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <!--Fixed Column -->
    <link href="<?php echo base_url('assets/css/fixedColumns.bootstrap4.min.css') ?>" rel="stylesheet">
    

    <!--JQuery 3.5.1 -->
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery-3.5.1.min.js') ?>"></script>

    <!--JS Datatables -->
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery.dataTables.min.js') ?>"></script>

    <!--JS Bootstrap4 Datatables -->
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/dataTables.bootstrap4.min.js') ?>"></script>

		<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
		<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>


	  <title>Hasil Input DPR Online</title>
	  <style>
	  th, td { 
        white-space: nowrap; 
    }
        div.dataTables_wrapper {
            margin: auto;
            width: auto;
        }
    }
	</style>
<body>
	<nav class="navbar fixed-top navbar-expand-lg navbar-light p-3" style="background-color:#5bc0de">
        <div class="container">
            <a class="navbar-brand text-white" href="<?php echo base_url('login_op/input_dpr') ?>" style="font-size:20px;font-weight:600">DPR ONLINE</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size:14px;font-weight:600">
			        <ul class="navbar-nav ml-auto" style="font-size:14px;font-weight:600">
			          <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Logged in as :
                          <i class="bi bi-person-fill ml-2 mr-1"></i> <?php echo $this->session->userdata('nama_operator') ?>
                        </a>
                        <div class="dropdown-menu mt-3" aria-labelledby="navbarDropdown2">
                          <a class="dropdown-item" href="<?php echo base_url('login_op/change_password') ?>">Change Password</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="<?php echo base_url('login_op/logout') ?>">Logout</a>
                        </div>
                      </li>
			        </ul>
			      </div>
        </div>           
    </nav>
    <br>
    <br>
	<div class="container-fluid mt-2">
		<div class="row justify-content-center">
			<div class="col-sm-12">
				<br>
				<br>
				<center><h3>List DPR Online</h3></center>
				<hr>
				<div class="isi">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="list_dpr" width="100%" >
                            <thead style="color:#3F6AD8">
                                <tr style="background-color:#F0F8FF;text-align: center;">
                                    <th width="30" style="vertical-align: middle;">No</th>
                                    <th width="60" style="vertical-align: middle;">Tanggal</th>
                                    <th style="vertical-align: middle;">Shift</th>
                                    <th style="vertical-align: middle;">Mesin</th>
                                    <th>Operator</th>
                                    <th style="vertical-align: middle;">Kanit</th>
                                    <th style="vertical-align: middle;">No & Nama BOM</th>
                                    <th style="vertical-align: middle;">Qty OK</th>
                                    <th style="vertical-align: middle;">Qty NG</th>
                                    <th style="vertical-align: middle;">Qty LT</th>
                                    <th style="vertical-align: middle;">Keterangan</th>
                                    <th style="vertical-align: middle;">Action</th>
                                </tr>
                            </thead>                                        
                            <tbody>
                                <?php $i=1; 
                                foreach ($list_dpr->result() as $list) : ?>
                                <tr>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $i++ ?>
                                    </td>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->tanggal; ?>
                                    </td>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->shift; ?>
                                    </td>
                                     <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->mesin; ?>
                                    </td>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->operator; ?>
                                    </td>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->kanit; ?>
                                    </td>       
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->nama_bom; ?>
                                    </td>  
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->qty_ok; ?>
                                    </td>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->qty_ng; ?>
                                    </td>
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->qty_lt; ?>
                                    </td>       
                                    <td style="text-align: center;" class="align-middle">
                                        <?php echo $list->keterangan; ?>
                                    </td>                                      
                                    <td class="align-middle text-center">
                                    	<a href="<?php echo site_url('login_op/view_dpr_detail/'.$list->id_production) ?>" class="btn btn-warning">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                
                </div>  
			</div>
		</div>
		
		
	</div>
</html>
<script>
	$(document).ready(function() {
        $('#list_dpr').DataTable({
                scrollX:        true,
                scrollY:        true,
                paging:         true,
                autoWidth:      true,
        });  
    });
</script>

