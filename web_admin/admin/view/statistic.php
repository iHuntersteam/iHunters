<div class="titleName">
	<p><?=$name?></p>
</div>
<div class="table">
	<div class="grid top">
		<p class="name">Имя</p>
		<p class="rank">Количество упоминаний</p>
	</div>
	<?php foreach ($statistic as $stat): ?>
	<div class="grid">
		<p class="name">
		<?php 
			$person=M_Persons::getPerson($stat['person_id']);
			echo $person['name'];
		?>
		</p>
		<p class="rank">
			<?=$stat['rank']?>
		</p>
	</div>
	<?php endforeach ?>
</div>
