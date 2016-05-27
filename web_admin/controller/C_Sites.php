<?php 


class C_Sites extends C_Base
{
	
	public function action_Index()
	{
		$this->title .=": Панель Администратора";
		$mSites=M_Sites::getInstance();
		$sites=$mSites->allSites();
		$this->content=$this->template('view/sites.php', array('sites'=>$sites));
	}
	public function action_Add()
	{
		if($this->isPost())
		{
			if(isset($_POST['add']))
			{

			}
		}
	}
	public function action_Edit()
	{
		if($this->isPost())
		{
			if(isset($_POST['edit']))
			{
				
			}
		}
	}
	public function action_Delete()
	{
		if($this->isPost())
		{
			if(isset($_POST['delete']))
			{
				
			}
		}
	}
}

?>