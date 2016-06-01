
<div class="name">
	<p>
		<?=$name?>
	</p>
	<ul>
		<li>
			<a href="index.php?c=keywords&action=add&id=<?=$id?>" class="btn" >
			добавить
			</a>
		</li>
	</ul>
</div>

<div class="table">
	<div class="row top ">
		<p>Наименование</p>
	</div>
	<?php foreach ($keywords as $keyword):?>
	<div class="row">
		<p><?=$keyword['name']?></p>
		<ul class="delete_edit">
			<li>
				<a href="index.php?c=keywords&action=edit&id=<?=$keyword['id']?>">	редактировать
				</a>
			</li>
			<li>
				<a href="index.php?c=keywords&action=delete&id=<?=$keyword['id']?>">	удалить
				</a>
			</li>
		</ul>
	</div>
	<?php endforeach ?>
</div>

