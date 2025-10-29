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
	  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">

	  <script src="<?php echo base_url().'assets/js/jquery-3.3.1.js'?>" type="text/javascript"></script> 
		<script src="<?php echo base_url().'assets/js/bootstrap.js'?>" type="text/javascript"></script>
		<script src="<?php echo base_url().'assets/js/jquery-ui.js'?>" type="text/javascript"></script>


	  <title>DPR Online Change Password</title>
     
<body>
	<nav class="navbar fixed-top navbar-expand-lg navbar-light p-3" style="background-color:#5bc0de">
        <div class="container">
            <a class="navbar-brand text-white" href="<?php echo base_url('login_op/input_dpr') ?>" style="font-size:20px;font-weight:600">DPR ONLINE</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mr-4" style="font-size:14px;font-weight:600">
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
	<div class="container-fluid mt-4">
		<div class="row justify-content-center">
            <div class="col-sm-8 mt-4">
                <div class="border rounded">
                        <div class="card">
                            <div class="card-body">
                                <h5>
                                    <?php 
                                    if($this->session->flashdata('salah') !='')
                                    {
                                        echo '<div class="alert alert-danger" style="text-align:center" role="alert">';
                                        echo $this->session->flashdata('salah');
                                        echo '</div>';
                                    }
                                    ?>

                                    <?php 
                                    if($this->session->flashdata('sama') !='')
                                    {
                                        echo '<div class="alert alert-danger" style="text-align:center" role="alert">';
                                        echo $this->session->flashdata('sama');
                                        echo '</div>';
                                    }
                                    ?>

                                    <?php 
                                    if($this->session->flashdata('tidak_sama') !='')
                                    {
                                        echo '<div class="alert alert-danger" style="text-align:center" role="alert">';
                                        echo $this->session->flashdata('tidak_sama');
                                        echo '</div>';
                                    }
                                    ?>

                                    <?php 
                                    if($this->session->flashdata('success_change_password') !='')
                                    {
                                        echo '<div class="alert alert-success" style="text-align:center" role="alert">';
                                        echo $this->session->flashdata('success_change_password');
                                        echo '</div>';
                                    }
                                    ?>
                                </h5>
                                <br>
                                <?php foreach($data_user->result() as $du): ?>
                                <?php endforeach; ?>
                                <form method="post" action="<?= base_url('login_op/change_passwordAct/'.$du->id_operator); ?>">
                                    <div class="position-relative row form-group">
                                        <label for="password" class="col-sm-2 col-form-label">Old Password</label>
                                        <div class="col-sm-10">
                                            <input name="password_old" id="password_old" placeholder="Fill in here ..." type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="position-relative row form-group">
                                        <label for="newPass" class="col-sm-2 col-form-label">New Password</label>
                                        <div class="col-sm-10">
                                            <input name="newPass" id="newPass" placeholder="Fill in here ..." type="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="position-relative row form-group">
                                        <label for="confirmPass" class="col-sm-2 col-form-label">Confirm Password</label>
                                        <div class="col-sm-10">
                                            <input name="confirmPass" id="confirmPass" placeholder="Fill in here ..." type="password" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row form-check">
                                        <div class="col-sm-12 pb-3 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary mt-3" >Change Password</button>
                                        </div>
                                    </div>                                
                                </form>
                            </div>
                        </div>
                    </div>        
            </div>
        </div>
		
	</div>
		


<style type="text/css">
body {
	 width: 100%;
     min-height: 200px;
     font-family: 'Tahoma';
     background: #d5f0f3;
     background-image: url('../assets/MCH.jpg');
     background-repeat: no-repeat, repeat;
     background-size: cover;
	 }
	 
.nounderline {
  text-decoration: none !important
}

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px) 
</style>


<style type="text/css">
body {
	 width: 100%;
     min-height: 200px;
     font-family: 'Tahoma';
     background: #d5f0f3;
     background-image: url('../assets/MCH.jpg');
     background-repeat: no-repeat, repeat;
     background-size: cover;
	 }
	 
.nounderline {
  text-decoration: none !important
}

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px) 


</style>
<style type="text/css">
body {
	width: 100%;
   min-height: 200px;
  font-family: 'Tahoma';

  background: #d5f0f3;

}

table, td, th {
  border: 1px solid black;
  /*padding:5px;*/

}

table {
  /*width: 80%;*/
  border-collapse: collapse;
  width: 100%;
  /*min-height: 200px;*/
}
td
{
	/*font-weight: bold;*/
	font-size: 12px;
}


@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* Force table to not be like tables anymore */
	table,  td, tr { 
		/*display: block; */
	}
	
	/* Hide table headers (but not display: none;, for accessibility) */
	/*thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}*/
	
	tr { border: 1px solid #ccc; }
	
	td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding : 1%;
		/*padding-left: 50%; */
	}
	
	td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
	}


</style>
  </body>
</html>

