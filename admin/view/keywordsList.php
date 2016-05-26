<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($keywords as $keyword): ?>
	<div class="row">
	<?=$keyword['name']?>
	</div>
	<?php endforeach ?>
</div>
<div class="edit">
	<form method="post" class="form">
		<input type="submit" value="Добавить">
		<input type="submit" value="Удалить">
		<input type="submit" value="Редактировать">
	</form>
</div>