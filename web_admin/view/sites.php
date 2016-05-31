<h2><?=$title?></h2>
<div class="table">
	<div class="row top ">
		Наименование
	</div>
	<?php foreach ($sites as $site): ?>
	<div class="row">
		<form method="post">
			<input type="radio" name="check" value="<?=$site['id']?>"> <?=$site['name']?>
			
		
	</div>
	<?php endforeach ?><button>Удалить</button></form>
</div>
<div class="edit">
<ul>
	<li><a href="index.php?c=sites&action=add">добавить</a></li>
	<li><a href="index.php?c=sites&action=delete&id=<?=$site['id']?>">удалить</a>
</li>
	<li><a href="index.php?c=sites&action=edit&id=<?=$site['id']?>"">редактировать</a></li>
</ul>
</div>
	<pre>
		<?=var_dump($_POST['check'])?>
	</pre>