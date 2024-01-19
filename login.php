<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Login</title>


	<?php include('./header.php'); ?>
	<?php
	session_start();
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=home");
	?>

</head>
<style>
	body {
		width: 100%;
		height: calc(100%);
		background: #D4E6F1;
	}
</style>

<body> 
	<div class="container">
		<br><br><br>
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<h2 class=" text-center"><b>SISTEMA ARCHIVOS</b></h2>
				<br>
				<div class="text-center">
					<img src="assets/logo.png" height="50%" width="50%">
				</div> <br>
				<div class="card">
					<h5 class="card-header text-center"><b>Iniciar Sesión</b></h5>
					<div class="card-body ">
						<form id="login-form">
							<div class="form-group">
								<label for="username" class="control-label ">Usuario</label>
								<input type="text" id="username" name="username" class="form-control">
							</div>
							<div class="form-group">
								<label for="password" class="control-label ">Contraseña</label>
								<input type="password" id="password" name="password" class="form-control">
							</div>
							<center><button class="btn-lg btn-block btn-wave col-md-5 text-light border border-primary" style="background-color: #005cb2;">Ingresar</button></center>
						</form>
					</div>
				</div> 
			</div>
			<div class="col-md-4"></div> 
		</div> 
	</div> 
	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a> 
</body>
<script>
	$('#login-form').submit(function(e) {
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success: function(resp) {
				if (resp == 1) {
					location.reload('index.php?page=home');
				} else {
					$('#login-form').prepend('<div class="alert alert-danger">Nombre de usuario o contraseña incorrecta.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>

</html>