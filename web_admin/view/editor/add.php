<?=$menu?>
<h3><?=$title?></h3>
<div class="table">
	<div class="row top ">
		<p>Наименование<br><?=$msg?></p> 
	</div>
	<div class="row">
		<form method="post">
			<input type="text" name="name" autofocus">
			<input type="hidden" name="id">
			<input type="submit" value="добавить" class="btn">
		</form>
	</div>
</div>
