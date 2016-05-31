<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($keywords as $keyword):?>
	<div class="row">
		<?=$keyword['name']?>
		<ul>
			<li>
				<a href="index.php?c=keywords&action=edit&id=<?=$keyword['id']?>">	редактировать
				</a>
			</li>
			<li>
				<a href="index.php?c=keywords&action=delete&id=<?=$keyword['person_id']?>">	удалить
				</a>
			</li>
		</ul>
	</div>
	<?php endforeach ?>
</div>
<div class="edit">
	<ul>
		<li><a href="index.php?c=keywords&action=add&id=<?=$keyword['person_id']?>">добавить</a></li>
	</ul>
</div>