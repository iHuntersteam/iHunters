<div class="titleName">
	<p><?=$site_name?></p>
	<p><?=$person_name?></p>
</div>
<div class="table">
	<div class="grid top">
		<p class="name">Дата</p>
		<p class="rank">Количество страниц</p>
	</div>
	<?php foreach ($range as $date): ?>
	<div class="grid">
		<p class="name">
			<?=$date->format('d.m.Y')?>
		</p>
		<p class="rank">
			<?php $everydayStat=M_Statistic::rankByDay($site_id,$date->format('Y-m-d'),$person_id);
			print count($everydayStat);
			
			?>
		</p>
	</div>
	<?php endforeach ?>
</div>
