<?=$menu?>
<h2><?=$title?></h2>
<?php if(isset($_SESSION['username'])):?>
<div class="add">
	<ul>
		<li>
			<a href="index.php?c=persons&action=add" class="btn">добавить</a>
		</li>
	</ul>
</div>
<?php endif;?>
<div class="table">
	<div class="row top">
		<p>Наименование</p>
	</div>
	<?php foreach ($persons as $person): ?>
	<div class="row">
		<p> <span><?=$person['name']?></span></p>
		<?php if(isset($_SESSION['username'])):?>
	 		<ul class="delete_edit">
	 			<li>
					<a href="index.php?c=persons&action=edit&id=<?=$person['id']?>"">редактировать</a>
				</li>
				<li>
					<a href="index.php?c=persons&action=delete&id=<?=$person['id']?>">удалить</a>
				</li>
			</ul>
		<?php endif;?>
	</div>
	<?php endforeach ?>
</div>
