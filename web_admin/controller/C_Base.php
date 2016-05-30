<?php 

abstract class C_Base extends C_Controller
{
	protected $title;
	protected $content;


	function __construct(){}

	public function before()
	{
		$this->title="";
		$this->content='';
	}
	public function render()
	{
		$page=$this->template('view/main.php',array('title'=>$this->title,'content'=>$this->content));

		echo $page;
	}

	public function __call($name,$params)
	{
		$content="<strong> Ошибка 404! <strong></br> 
		Ой!Что-то пошло не так. При обработке вашего запроса произошла ошибка!";
		$this->content=$this->Template('view/error.php',array('content'=>$content));
	}
}





 ?>