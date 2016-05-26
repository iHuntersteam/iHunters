<h2>Ключевые слова</h2>
<form method="post" class="select">	
<select name="person">	
<?php foreach ($persons as $person): ?>
<option>
<?=$person['name']?>	
</option>
<?php endforeach ?>
</select>
<input type="hidden" name="<?=$person['name']?>">
<input type="submit" name="select" value="Выбрать">
</form>
<?=$keywordsList?>