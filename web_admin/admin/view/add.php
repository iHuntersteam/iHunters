<ul class="breadcrumbs">
	<li><a href="index.php?c=<?=$_GET['c']?>">назад</a></li>/
	<li>добавление</li>
</ul>

<h3><?=$title?></h3>
<div class="table">
	<div class="row top ">
		Наименование<?="<br>".$msg?>
	</div>
	<div class="row">
		<form method="post">
			<input type="text" name="name" autofocus placeholder="<?=$value?>">
			<input type="hidden" name="id">
			<input type="submit" value="добавить" class="btn">
		</form>
	</div>
</div>
