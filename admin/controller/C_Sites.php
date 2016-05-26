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
}

?>