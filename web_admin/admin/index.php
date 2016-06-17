<?php 

	function classesAutoload($name)
	{
		$file='controller/'.$name.'.php';

		if(!is_file($file))
		{
			$file='model/'.$name.'.php';
			if(!is_file($file)) return;
		}
		include_once($file);
	}

	spl_autoload_register('classesAutoload');
	
	$action="action";
	$action .=(isset($_GET['action']))? $_GET['action']: 'Index';		
	
	if(isset($_GET['c']))
	{
		switch ($_GET['c']) 
		{
			case 'sites':
				$controller=new C_Sites();
			break;
			case 'persons':
				$controller=new C_Persons();
			break;
			case 'keywords':
				$controller=new C_Keywords();
			break;
			case 'users':
				$controller=new C_Users();
			break;
			case 'statistic':
				$controller=new C_Statistic();
			break;
			default:
				$controller=new C_Users();
			break;
		}
	} 
	else $controller=new C_Users();

	$controller->request($action);
?>