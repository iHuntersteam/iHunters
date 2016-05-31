<?php 


class C_Sites extends C_Base
{
	
	public function actionIndex()
	{
		$this->title .="Сайты";
		$mSites=M_Sites::getInstance();
		$sites=$mSites->allSites();
		$this->content=$this->template('view/sites.php', array('sites'=>$sites,'title'=>$this->title));
	}
	public function actionAdd()
	{
		$this->title .="Добавить сайт";

		if (!empty($this->isPost())) 
		{
			if(M_Sites::addSite($_POST['name']))
			{
				header("location: index.php?c=sites");
				exit();
			}
			$this->name=$_POST['name'];

		}

		$this->content=$this->template('view/sitesAdd.php', array('name'=>$this->name,'title'=>$this->title));
	}
	public function actionEdit()
	{
		if($this->isPost())
		{
			if(isset($_POST['edit']))
			{
				
			}
		}
	}
	public function actionDelete()
	{
		if(!empty($this->isPost()))
		{
			
			if(M_Sites::deleteSite($_POST['check']))
			{
				header("location:index.php?c=sites");
				exit();
			}
		}
		
		$this->content=$this->template('view/sites.php', array('sites'=>$sites));	
			
	}
	
}

?>