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
			<a href="index.php?c=sites&action=add" class="btn">
				добавить
			</a>
		</li>
	</ul>
</div>
<div class="statistic">
	<?php foreach($totalUrls as $urls):?>
	<p>Общее кол-во ссылок в базе:<span><?=$urls?></span> </p>
	<?php endforeach ?>
	<?php foreach($totalScanUrls as $ScanUrls):?>
	<p>Отсканированных страниц:<span><?=$ScanUrls?></span></p>
	<?php endforeach ?>
	<?php foreach($totalNoScanUrls as $NoScanUrls):?>
	<p>Неотсканированных страниц:<span><?=$NoScanUrls?></span></p>
	<?php endforeach ?>
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

