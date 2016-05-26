<h2>Справочник "Личности"</h2>
	<div class="table">
		<div class="row top ">
				Наименование
		</div>
		<?php foreach ($persons as $person): ?>
		<div class="row">
			<a href="index.php?c=persons&action=get&id=<?=$person['id']?>"> <?=$person['name']?></a>
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