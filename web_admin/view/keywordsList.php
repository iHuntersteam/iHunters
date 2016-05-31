<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($keywords as $keyword):?>
	<div class="row">
		<?=$keyword['name']?>
	</div>
	<?php endforeach ?>
</div>
<div class="edit">
	<ul>
		<li><a href="index.php?c=keywords&action=add&id=<?=$keyword['person_id']?>">добавить</a></li>
		<li><a href="index.php?c=keywords&action=delete&id=<?=$keyword['person_id']?>">удалить</a></li>
		<li><a href="index.php?c=keywords&action=edit&id=<?=$keyword['person_id']?>">редактировать</li>
	</ul>
</div>