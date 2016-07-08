<?php 	
$mUser=M_Users::getInstance();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<link rel="stylesheet" href="view/css/normalize.css">
	<link rel="stylesheet"  href="view/css/style.css" />
	<link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'>
</head>
<body>
	<section class="section-main">
		<section class="header">
			<div class="logo">
				<p><a href="index.php?c=<?=$_GET['c']?>">iHUNTERS</a> </p>
			</div>
			<section class="authentication">
				<?php if(isset($_SESSION['username'])):?>
				<?php $uid=$mUser->getUid();?>
				<ul>
					<li><a href="index.php?c=users&action=logout">Выйти</a></li>
					<li><span>[<?=$_SESSION['username']?>]</span> </li>
				</ul>
				<?php else:?>
				<ul>
					<li><a href="index.php?c=users&action=login">Войти</a></li>
					<li><a href="index.php?c=users&action=register">Регистрация</a></li>
				</ul>
				<?php endif;?>
			</section>
		</section>
		<section class="content">
			<?=$content?>
		</section>
	</section>
</body>
</html>