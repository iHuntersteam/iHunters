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
<ul>
	<li><a href="index.php?c=persons&action=add">добавить</a></li>
	<li><a href="index.php?c=sites&action=delete&id=<?=$person['id']?>">удалить</a>
</li>
	<li><a href="index.php?c=persons&action=edit&id=<?=$person['id']?>"">редактировать</a></li>
</ul>
</div>
	