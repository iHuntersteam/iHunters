<h3><?=$title?></h3>
<div class="table">
	<div class="row">
		<p><?=$name?></p> 
	</div>
	<?php foreach ($pages as $page):?>
	<div class="row">
		<p><span>Общее количество ссылок:</span><?=count($page['url'])?></p> 
	</div>
	<?php  endforeach;?>
</div>