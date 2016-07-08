<?php 


class C_Sites extends C_Base
{
	
	private $name;
	private $id;

	public function actionIndex()
	{
		$this->title .="Сайты";
		$sites=M_Sites::allSites();
		$totalUrls=M_Sites::allUrls();
		$totalScanUrls=M_Sites::allScanUrls();
		$totalNoScanUrls=M_Sites::allNoScanUrls();
		$this->content=$this->template('view/sites/sites.php', ['sites'=>$sites,'totalUrls'=>$totalUrls,'totalScanUrls'=>$totalScanUrls,
				'totalNoScanUrls'=>$totalNoScanUrls,'title'=>$this->title,'menu'=>$this->menu]);
	}
	
	public function actionAdd()
	{
		$this->title .="Добаление сайта";
		$msg="[формат добавления-<b>https://site.ru</b>]";

		if (!empty($this->isPost())) 
		{
			if(M_Sites::addSite($_POST['name']))
			{
				header("location: index.php?c=sites");
				exit();
			}
			$this->name=$_POST['name'];

		}

		$this->content=$this->template('view/editor/add.php', 
			['name'=>$this->name,'title'=>$this->title,'msg'=>$msg,'value'=>$this->value,'menu'=>$this->menu]);
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

		$this->content=$this->template('view/editor/edit.php', ['name'=>$site['name'],'title'=>$this->title,'menu'=>$this->menu]);
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
		
		$this->content=$this->template('view/editor/delete.php', ['name'=>$site['name'],'title'=>$this->title,'menu'=>$this->menu ]);
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
		$this->content=$this->template('view/sites/siteStatistic.php', ['name'=>$site['name'],'allUrls'=>$allUrls,'noScanUrls'=>$noScanUrls,
			'scanUrls'=>$scanUrls, 'title'=>$this->title,'menu'=>$this->menu]);
	}
	
}

?>