
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
<div class="select">
	<form method="post" >	
		<select name="select">
		<?php foreach ($persons as $person): ?>
			<option value="<?=$person['id']?>">
				<?=$person['name']?>	
			</option>
		<?php endforeach ?>
		</select>
		<input type="submit" value="выбрать" class="btn">
	</form>
</div>
<div class="keylist">
	<?=$keywordsList?>
</div>

