<h2><?=$title?></h2>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($persons as $person): ?>
	<div class="row">
		<a href="c=sites&action=get&id=<?=$person['id']?>"> <?=$person['name']?></a>
	</div>
	<?php endforeach ?>
</div>
<div class="edit">
<a href="index.php?c=persons&action=add">Добавить</a>
<a href="index.php?c=sites&action=delete&id=<?=$person['id']?>">Удалить</a>
</div>
	