<?php

namespace App\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class Controller
{
	//protected abstract function render();

	public function request($action, $parameters = [])
	{
		$this->$action($parameters);
	}

	protected function view($view, $parameters = [])
	{


		$loader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . '/app/Views');
		$twig = new Twig_Environment($loader,[
				'cache' => $_SERVER['DOCUMENT_ROOT'] . '/tmp/cache',
				'auto_reload' => true,
				'debug' => true
			]);

		return $twig->render($view, $parameters);

	}

	protected function isGet()
	{
		return $_SERVER['REQUEST_METHOD'] == "GET";
	}

	protected function isPost()
	{
		return $_SERVER['REQUEST_METHOD'] == "POST";
	}

	public function __call($method, $params)
	{
		die("Unknown method $method");
	}
}