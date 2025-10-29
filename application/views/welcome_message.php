<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
	  <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
	  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <title>Menu Utama</title>
  </head>
  <body>
    <div class="container-fluid">

		<div class="align-self-center mt-5">
			<div class="row justify-content-center mt-5 pt-5">
			  <div class="col-sm-6 mt-1">
			    <div class="card rounded">
			      <div class="card-body">
			        <a href="<?= base_url('login_op'); ?>" class="nounderline"><button class="btn btn-block btn-success mr-2" style="font-size: 40px;text-decoration:none;height:150px">DPR ONLINE</button></a>
			      </div>
			    </div>
			  </div>
			  <div class="col-sm-6 mt-1">
			    <div class="card rounded">
			      <div class="card-body">
			        <a href="<?= base_url('login_control'); ?>" class="nounderline"><button class="btn btn-block btn-primary" style="font-size: 40px;text-decoration:none;height:150px">DPR REPORT</font></button></a>
			      </div>
			    </div>
			  </div>
			</div>
		</div>
	</div>
		


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url('assets/scripts/jquery-3.5.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/scripts/popper.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/scripts/bootstrap.min.js') ?>"></script>
  </body>
</html>

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