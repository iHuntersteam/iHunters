<h2>Справочник "Сайты"</h2>
	<div class="table">
		<div class="row top ">
				Наименование
		</div>
		<?php foreach ($sites as $site): ?>
		<div class="row">
			<?=$site['name']?>
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