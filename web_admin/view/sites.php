<h2><?=$title?></h2>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($sites as $site): ?>
	<div class="row">
				
		<?=$site['name']?>
			
			<ul>
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
<ul>
	<li><a href="index.php?c=sites&action=add">добавить</a></li>
</ul>
</div>
