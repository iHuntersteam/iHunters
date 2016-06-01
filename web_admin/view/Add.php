<h2><?=$title?></h2>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<div class="row">
		<form method="post">
			<input type="text" name="name">
			<input type="hidden" name="id">
			<input type="submit" value="Добавить">
		</form>
	</div>
</div>
<pre>
	<?=var_dump($_POST['id'])?>
</pre>