﻿<h3><?=$title?></h3>
<?php foreach($errors as $error):?>
<p class="errors"><?=$error?></p>
<?php endforeach;?>
<div class="table">
	<div class="row">
		<form method="post" class="register">
			<p>
				<span>Логин:</span>
				<input type="text" name="username" required>
			</p>	
			<p>
				<span>Email:</span>
				<input type="email" name="email">
			</p>	
			<p>
				<span>Пароль:</span>
				<input type="password" name="password" required>
			</p>	
			<p>
				<span>Подтвердить пароль:</span>
				<input type="password" name="password_confirm" required>
			</p>
			<p>
				<input type="submit" class="btn" value="зарегистрироваться">
			</p>	
		</form>
	</div>
</div>

