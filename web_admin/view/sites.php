<h2><?=$title?></h2>
<ul class="add">
	<li><a href="index.php?c=sites&action=add" class="btn">добавить</a></li>
</ul>
<div class="table">
	<div class="row top ">
		<p>Наименование</p>
	</div>
	<?php foreach ($sites as $site): ?>
	<div class="row">
				
		<p><?=$site['name']?></p> 
			
			<ul class="delete_edit">
				<li>
					<a href="index.php?c=sites&action=edit&id=<?=$site['id']?>">
						редактировать
					</a>
				</li>
				<li>
					<a href="index.php?c=sites&action=delete&id=<?=$site['id']?>">
						удалить
					</a>
				</li>
			</ul>
		</div>
	<?php endforeach ?>
</div>
<div class="edit">
</div>
