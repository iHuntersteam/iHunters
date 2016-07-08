<?php 
session_start();
$mUser=M_Users::getInstance();
$mUser->clearSession();

abstract class C_Base extends C_Controller
{
	protected $title;
	protected $content;
	protected $menu;


	function __construct(){}

	public function before()
	{
		$this->title="";
		$this->content='';
		$this->menu=$this->template('view/nav_menu.php');
	}
	public function render()
	{
		$page=$this->template('view/main.php',
			['title'=>$this->title,'content'=>$this->content,'menu'=>$this->menu]);

		echo $page;
	}

	public function __call($name,$params)
	{
		$content="<strong> Ошибка 404! <strong></br> 
		Ой!Что-то пошло не так. При обработке вашего запроса произошла ошибка!";
		$this->content=$this->Template('view/error.php',['content'=>$content]);
	}
}

?>