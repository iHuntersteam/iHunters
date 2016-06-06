<h3><?=$title?></h3>
<div class="table">
	<div class="row">
		<p><?=$name?></p> 
	</div>
	<?php foreach ($allUrls as $url):?>
	<div class="row">
		<p><span>Общее количество ссылок:</span><?=$url?></p> 
	</div>
	<?php  endforeach;?>
	<?php foreach ($noScanUrls as $noScanUrl):?>
	<div class="row">
		<p><span>Не отсканированные ссылки:</span><?=$noScanUrl?></p> 
	</div>
	<?php  endforeach;?>
	<?php foreach ($scanUrls as $scanUrl):?>
	<div class="row">
		<p><span>Отсканированые ссылки:</span><?=$scanUrl?></p> 
	</div>
	<?php  endforeach;?>
</div>