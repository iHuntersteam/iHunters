<?php 


abstract class C_Controller
{
	
	protected abstract function render();
	protected abstract function before();

	public function request($action)
	{
		$this->before();
		$this->$action();
		$this->render();

	}
	protected function isGet()
		{
			return $_SERVER['REQUEST_METHOD']=='GET';
		}

	protected function isPost()
		{
			return $_SERVER['REQUEST_METHOD']=='POST';
		}
	

	protected function template($file,$var=array())
	{
		foreach ($var as $key => $value) {
			
			$$key=$value;
		}
		ob_start();
		include $file;
		return ob_get_clean();
	}
}




 ?>