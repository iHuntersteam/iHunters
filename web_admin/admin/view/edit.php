<ul class="breadcrumbs">
	<li><a href="index.php?c=<?=$_GET['c']?>">Вернуться к списку</a></li>/
	<li>редактирование</li>
</ul>
<h3><?=$title?></h3>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<div class="row">
		<form method="post">
			<input type="text" name="name" value="<?=$name?>" autofocus>
			<input type="submit" value="изменить" class="btn">
		</form>
	</div>
</div>