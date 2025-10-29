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
			      	<?php if ($this->session->flashdata('gagal')): ?>
		                <div class="alert alert-danger mt-2" style="text-align: center;font-size: 12px" role="alert">
		                    <?php echo $this->session->flashdata('gagal'); ?>
		                </div>
		            <?php endif; ?>
			        <?php echo form_open('login_op/ceklogin'); ?>
								<table style="margin:2%;">
									<tr>
										<td colspan="2"><center><b>Login Operator</b> v.1.0</center><br/></td>
									</tr>
									<tr>
										<td><b>NIK</b></td>
										<td><input name="nik" type="text" class="form-control form-control-lg" style="height:100%" onkeyup="getDiv(this.value)" /></td>
									</tr>
									<tr>
										<td><b>Password</b></td>
										<td><input name="password" type="password" class="form-control form-control-lg" onfocus="this.value=''" style="height:100%"  /></td>
									</tr>
									<tr>
										<td><b>Divisi</b></td>
										<td><input name="divisi" id="divisi" type="text" class="form-control form-control-lg" style="height:100%" readonly /></td>
									</tr>
									<tr>
										<td></td>
										<td><input type="submit" name="submit" value="Login" class="btn btn-success" /></td>
									</tr>
								</table>
								<?php echo form_close(); ?>
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

<script type="text/javascript">
	function getDiv(id)
	{
		$.ajax({
          type    : "POST",
          url     : "<?php echo base_url(); ?>index.php/c_operator/getDataDetail/t_operator/nik/", 
          dataType: "JSON",
          data    : "id=" + id,
          success : function(data){
            $("#divisi").val(data.divisi); 
          }});
	}
</script>