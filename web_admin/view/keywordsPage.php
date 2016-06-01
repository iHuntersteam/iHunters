<h2>Ключевые слова</h2>
<div class="select">
	<form method="post" >	
	<select name="select">
		<?php foreach ($persons as $person): ?>
		<option value="<?=$person['id']?>">
			<?=$person['name']?>	
		</option>
		<?php endforeach ?>
	</select>
<input type="submit" value="OK">
</form>
</div>
<?=$keywordsList?>
