<ul class="breadcrumbs">
	<li><a href="index.php?c=<?=$_GET['c']?>">Вернуться к списку<</a></li>/
	<li>удаление</li>
</ul>
<h3><?=$title?></h3>
<div class="table">
	<div class="row top ">
	<p>Вы действительно хотите удалить<br><strong><?=$name?></strong>?</p>	
	</div>
	<div class="row">
		<form method="post">
			<input  type="submit"  name="yes" class="btn" value="да">
			<input type="submit" name="not" class="btn" value="нет">
		</form>
	</div>
</div>
