<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use Bakautov\Auth\AuthProvider;

require_once('AuthProvider.php');

$provider = new AuthProvider();

$user = $provider->login(['username' => 'norval48', 'password' => 'password']);

var_dump($user);