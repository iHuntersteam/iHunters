<h2><?=$title?></h2>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($persons as $person): ?>
	<div class="row">
	 <?=$person['name']?>
	 	<ul>
	 		<li>
				<a href="index.php?c=persons&action=edit&id=<?=$person['id']?>"">
					редактировать
				</a>
			</li>
			
			<li>
				<a href="index.php?c=persons&action=delete&id=<?=$person['id']?>">
					удалить
				</a>
			</li>
			
		</ul>
	</div>
	<?php endforeach ?>
</div>
<div class="edit">
<ul>
	<li><a href="index.php?c=persons&action=add">добавить</a></li>
	
</ul>
</div>
	