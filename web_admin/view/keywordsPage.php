<h2>Ключевые слова</h2>

<form method="post" class="select">	
	<select name="select">
		<?php foreach ($persons as $person): ?>
		<option value="<?=$person['id']?>">
			<?=$person['name']?>	
		</option>
		<?php endforeach ?>
	</select>
<input type="submit" name="submit" value="OK" class="choose">
</form>
<?=var_dump($_POST['select']);?>
<pre>
<?=var_dump($persons[0]['id']);?>
</pre>

<div>
	<?=$keywordsList?>
</div>
