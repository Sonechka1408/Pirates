<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Администрирование</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js"></script>
	</head>
    <body style="background:#1F1E1E url('/assets/images/welcome-bg.png') top center no-repeat; background-size: cover;">
        <div class="container" style="padding-top:80px;">
			<form class="form-signin form" method="post" role="form">
				<h2 class="form-signin-heading">Авторизация</h2>
				<div class="form-group">
					<input type="text" name="login" class="form-control" placeholder="Логин" required="" autofocus="">
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control" placeholder="Пароль" required="">
				</div>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Вход</button>
			</form>
		</div>
	</body>
</html>