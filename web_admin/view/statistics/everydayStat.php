<?=$menu?>
<h3><?=$title?></h3>
<div class="select">
	<form method="post" >	
		<select name="select_site">
		<?php foreach ($sites as $site): ?>
			<option value="<?=$site['id']?>">
				<?=$site['name']?>	
			</option>
		<?php endforeach ?>
		</select>	
		<select name="select_person">
		<?php foreach ($persons as $person): ?>
			<option value="<?=$person['id']?>">
				<?=$person['name']?>	
			</option>
		<?php endforeach ?>
		</select>
		<p>
			<input type="date" name="startdate">
			<input type="date" name="finishdate">
		</p>
		<input type="submit" value="выбрать" class="btn">
	</form>
</div>
<?=$everydayStatList?>
