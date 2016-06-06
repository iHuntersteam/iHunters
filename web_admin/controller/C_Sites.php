<?php 


class C_Sites extends C_Base
{
	
	public function actionIndex()
	{
		$this->title .="Сайты";
		$sites=M_Sites::allSites();
		$this->content=$this->template('view/sites.php', 
			array('sites'=>$sites,
				'title'=>$this->title));
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

		$this->content=$this->template('view/add.php', 
			array('name'=>$this->name,
				'title'=>$this->title));
	}
	public function actionEdit()
	{
		$this->title .="Редактировать имя сайта";

		if(isset($_GET['id']))
			{
				$this->id=$_GET['id'];
				$site=M_Sites::getSite($this->id);

				if($this->isPost())
				{
					if (M_Sites::editSite($this->id,$_POST['name'])) 
					{
						header("location: index.php?c=sites");
						die();
					}
					else
					{
						header("location: index.php?c=sites");
						die();
					}
					$this->name=$_POST['name'];
				}
			}

		$this->content=$this->template('view/edit.php', 
			array('name'=>$site['name'],
				'title'=>$this->title));
	}
	public function actionDelete()
	{
		$this->title .="Удаление из справочника";
			
			if(isset($_GET['id']))
			{
				$this->id=$_GET['id'];
				$site=M_Sites::getSite($this->id);
			
				if($this->isPost())
				{
					if(isset($_POST['yes']))
					{
						$this->id=$_GET['id'];
						M_Sites::deleteSite($this->id);
						header("location: index.php?c=sites");
						die();
					}
					else
					{
						header("location: index.php?c=sites");
						die();
					}
				}
			}
		
		$this->content=$this->template('view/delete.php', 
			array('name'=>$site['name'],
				'title'=>$this->title ));
	}
	public function actionStatisticById()
	{
		$this->title .="Статистика сайта";

		if(isset($_GET['id']))
		{
			$this->id=$_GET['id'];
			$site=M_Sites::getSite($this->id);
			$allUrls=M_Sites::getAllUrlById($this->id);
			$noScanUrls=M_Sites::getNoScanUrl($this->id);
			$scanUrls=M_Sites::getScanUrl($this->id);
		}
		$this->content=$this->template('view/siteStatistic.php', 
			array('name'=>$site['name'],
				'allUrls'=>$allUrls,
				'noScanUrls'=>$noScanUrls,
				'scanUrls'=>$scanUrls, 
				'title'=>$this->title ));
	}
	
}

?>