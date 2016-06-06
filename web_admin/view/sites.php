<div class="header">
	<h2><?=$title?></h2>
	<ul>
		<li>
			<a href="index.php?c=sites&action=add" class="btn">
				добавить
			</a>
		</li>
	</ul>
</div>
<div class="table">
	<div class="row top ">
		<p>Наименование</p>
	</div>
	<?php foreach ($sites as $site): ?>
	<div class="row">
		<p><?=$site['name']?>&nbsp
			<span><a href="index.php?c=sites&action=statisticbyid&id=<?=$site['id']?>">
					статистика
					</a>
			</span> 
		</p> 
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

