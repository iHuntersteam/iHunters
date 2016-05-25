<?php
namespace App;

class Route
{
	protected $_route;
	protected $_controller;
	protected $_action;
	protected $_params = [];
	protected $_id;

	public function __construct($route)
	{
		$this->_route = $route;
	}

	private function parse()
	{ 
		/* проверка запроса на совпадение с регулярным выражением */
		if (isset($this->_route)) {
            $cai = '/^([\w]+)\/([\w]+)\/([\d]+).*$/';  //  controller/action/id
            $ci = '/^([\w]+)\/([\d]+).*$/';           //  controller/id
            $ca = '/^([\w]+)\/([\w]+).*$/';           //  controller/action
            $c = '/^([\w]+).*$/';                    //  controller
            $i = '/^([\d]+).*$/';                    //  id

            $path = $this->_route;
            if (empty($path)) {

            	$this->_controller = 'Stats';
            	$this->_action = 'index';
            } elseif (preg_match($cai, $path, $matches)) {
            	$this->_controller = $matches[1];
            	$this->_action = $matches[2];
            	$this->_id = $matches[3];
            } elseif (preg_match($ci, $path, $matches)) {
            	$this->_controller = $matches[1];
            	$this->_id = $matches[2];
            } elseif (preg_match($ca, $path, $matches)) {
            	$this->_controller = $matches[1];
            	$this->_action = $matches[2];
            } elseif (preg_match($c, $path, $matches)) {
            	$this->_controller = $matches[1];
            	$this->_action = 'index';
            } elseif (preg_match($i, $path, $matches)) {
            	$this->_id = $matches[1];
            } else {
            	$this->_controller = 'Stats';
            	$this->_action = 'index';
            }

            /* получение параметров из пути */
            $query = array();
            $parse = parse_url($path);
                        
            /* если в оригинальном запросе есть $_GET добавляем его в $_GET и $_REQUEST */
            if (!empty($parse['query'])) {
            	parse_str($parse['query'], $query);
            	if (!empty($query)) {
            		$_GET = array_merge($_GET, $query);
            		$_REQUEST = array_merge($_REQUEST, $query);
            	}
            }
        }

        /* определяем методом, которым был сделан запрос */
        $method = $_SERVER["REQUEST_METHOD"];
        switch ($method) {
        	case "GET": /* если запрос был GET, убираем наш полученный путь и соединяем с параметрами из пути */
        	unset($_GET['_route']);
        	$this->_params = array_merge($this->_params, $_GET);
        	break;
        	case "POST":
        	/* игнорирование загрузки файлов */
        	if (!array_key_exists('HTTP_X_FILE_NAME', $_SERVER)) {
        		/* добавляем параметры из POST в свойство */
        		$this->_params = array_merge($this->_params, $_POST);
        	}
        	break;
        }

        if (!empty($this->_id)) {
        	/* переносим id из пути к остальным параметрам */
        	$this->_params['id'] = $this->_id;
        }

        if ($this->_controller == 'index') {
        	$this->_params = array($this->_params);
        }
    }

    public function dispatch()
    {
    	$this->parse(); /* разбираем путь */
    	$controllerName = "App\\Controller\\".ucfirst($this->_controller);
    	$controller = new $controllerName();
    	$controller->request($this->_action, $this->_params);

    }
}