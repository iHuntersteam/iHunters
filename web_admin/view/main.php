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
	<ul class="menu">
		<li>
			<a href="index.php?c=sites"> сайты</a>
		</li>
		<li>
			<a href="index.php?c=persons">личности</a>
		</li>	
		<li>
			<a href="index.php?c=keywords">ключевые слова</a>
		</li>		
	</ul>
	<section class="content">
		<?=$content?>
	</section>
	
</section>
</body>
</html>