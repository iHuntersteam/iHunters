<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="view/normalize.css">
	<link rel="stylesheet"  href="view/style.css" />
	<link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'>
</head>
<body>
	<section class="section-main">
		<section class="authentication">
			<div class="logo">
				<p><a href="index.php?c=<?=$_GET['c']?>">iHUNTERS</a> </p>
			</div>
			
			<ul>
				<li><a href="index.php?c=users&action=login">Войти</a></li>
				<li><a href="index.php?c=users">Выйти</a></li>
				<li><a href="index.php?c=users&action=register">Регистрация</a></li>
			</ul>
		</section>
		<section class="content">
			<?=$content?>
		</section>
	</section>
</body>
</html>