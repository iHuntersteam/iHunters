<h3><?=$title?></h3>
<?php foreach($errors as $error):?>
<p class="errors"><?=$error?></p>
<?php endforeach;?>
<div class="table">
	<div class="row">
		<form method="post" class="register">
			<p>
				<span>Логин:</span>
				<input type="text" name="username" value="<?=$username?>">
			</p>		
			<p>
				<span>Пароль:</span>
				<input type="password" name="password">
			</p>	
			<p>
				<span>Запомнить меня:</span>
				<input type="checkbox" name="remember">
			</p>	
			<p>
				<input type="submit" class="btn" value="войти">
			</p>	
		</form>
	</div>
</div>
