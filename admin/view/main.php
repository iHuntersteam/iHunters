<!DOCTYPE html PUBLIC>
<html>
<head>
	<title><?=$title?></title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="view/normalize.css">
	<link rel="stylesheet"  href="view/style.css" />
</head>
<body>
<section class="section-main">
	<ul>
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
	<section>
		<?=$content?>
	</section>
	
</section>
</body>
</html>