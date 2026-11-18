<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login DPR</title>

    <link href="<?= base_url(); ?>template/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?= base_url(); ?>template/css/animate.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">
<?php echo form_open('login_control'); ?>
    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">Selamat Datang di Web DPR</h2>

                <p>
                    Website DPR adalah website yang berisi informasi penting terkait Data Hasil Produksi Harian , Bulanan serta Tahunan . 
                    Link ini adalah halaman untuk Admin
                </p>

                <p>
                    Pastikan memiliki Username & Password untuk Login 
                </p>

                <p>
                   Pastikan Divisi yang muncul , sesuai dengan divisi anda
                </p>

                <p>
                    <small>Jika tejadi kendala , silahkan hubungi tim IT (123) </small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" action="index.html">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Username" required="" name="user" onkeyup="getDiv(this.value)">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" required="" name="pass">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Divisi" required="" readonly="" name="divisi" id="divisi">
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
<?php echo form_close(); ?>
        <div class="row">
            <div class="col-md-6">
                Copyright PT Padma Soode Indonesia
            </div>
            <div class="col-md-6 text-right">
               <small>© <?= date('Y'); ?></small>
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
<script type="text/javascript">
	function getDiv(id)
	{
		$.ajax({
          type    : "POST",
          url     : "<?php echo base_url(); ?>/login_control/getDataDetail/user/user_name/", 
          dataType: "JSON",
          data    : "id=" + id,
          success : function(data){
            $("#divisi").val(data.divisi); 
          }});
	}
</script>
