<?php

require "vendor/autoload.php";

use App\Route;
use App\Controller\Users;

$route = isset($_GET['_route']) ? preg_replace('/index.php\?_route=(.*)/', '$1', $_GET['_route']) : '';
$router = new Route($route);
$router->dispatch();


?>
