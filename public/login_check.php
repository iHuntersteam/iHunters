<?php

use Bakautov\Auth\AuthProvider;

require_once('AuthProvider.php');

$provider = new AuthProvider();

$provider->login(['username' => 'norval48', 'password' => 'pass']);