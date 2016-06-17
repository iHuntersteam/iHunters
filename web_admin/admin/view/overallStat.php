<?=$menu?>
<h3><?=$title?></h3>
<div class="select">
	<form method="post" >	
		<select name="select">
		<?php foreach ($sites as $site): ?>
			<option value="<?=$site['id']?>">
				<?=$site['name']?>	
			</option>
		<?php endforeach ?>
		</select>
		<input type="submit" value="выбрать" class="btn">
	</form>
</div>
<?=$statistic?>


