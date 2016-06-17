<h3><?=$title?></h3>
<div class="table">
	<div class="row">
		<p><?=$name?></p> 
	</div>
</div>
<div class="statistic">
	<?php foreach ($allUrls as $url):?>
	<p>Общее количество ссылок:<span><?=$url?></span></p> 
	<?php  endforeach;?>
	<?php foreach ($noScanUrls as $noScanUrl):?>
	<p>Не отсканированные ссылки:<span><?=$noScanUrl?></span></p> 
	<?php  endforeach;?>
	<?php foreach ($scanUrls as $scanUrl):?>
	<p>Отсканированые ссылки:<span><?=$scanUrl?></span></p> 
	<?php  endforeach;?>
</div>
