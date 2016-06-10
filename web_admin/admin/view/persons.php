<ul class="menu">
		<li>
			<a href="index.php?c=sites"> сайты</a>
		</li>
		<li>
			<a href="index.php?c=persons">личности</a>
		</li>	
		<li>
			<a href="index.php?c=keywords">ключевые слова</a>
		</li>		
</ul>
<h2><?=$title?></h2>
<div class="add">
	<ul>
		<li>
			<a href="index.php?c=persons&action=add" class="btn">
				добавить
			</a>
		</li>
	</ul>
</div>
<div class="table">
	<div class="row top">
		<p>Наименование</p>
	</div>
	<?php foreach ($persons as $person): ?>
	<div class="row">
	 <p><?=$person['name']?></p> 
	 	<ul class="delete_edit">
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
