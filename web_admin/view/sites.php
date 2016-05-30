<h2><?=$title?></h2>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($sites as $site): ?>
	<div class="row">
		<a href="c=sites&action=get&id=<?=$site['id']?>"> <?=$site['name']?></a>
	</div>
	<?php endforeach ?>
</div>
<div class="edit">
<a href="index.php?c=sites&action=add">Добавить</a>
<a href="index.php?c=sites&action=delete&id=<?=$site['id']?>">Удалить</a>
</div>
	