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
	<h2>Справочник "Личности"</h2>
	<div class="table">
		<div class="row top ">
				Наименование
		</div>
		<?php foreach ($persons as $person): ?>
		<div class="row">
			<?=$person['name']?>
		</div>
		<?php endforeach ?>
	</div>