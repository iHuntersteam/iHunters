<?php 

class C_Statistic extends C_Base
{
	
	public function actionIndex()
	{
		$this->title .="Статистика";
		$this->content=$this->template('view/statistics/statistic.php',['title'=>$this->title,'menu'=>$this->menu]);
		
	}
	public function actionOverallStat()
	{
		$this->title .="Общая статистика";
		$sites=M_Sites::allSites();
		
		if($this->isPost())
		{
			if(isset($_POST['select']))
			{
				$this->id=$_POST['select'];
				$statistic=M_Statistic::siteAllPersonRank($this->id);
				$site=M_Sites::getSite($this->id);
				$this->statistic=$this->template('view/statistics/statisticList.php',['statistic'=>$statistic,'name'=>$site['name']]);
			}
		}
		$this->content=$this->template('view/statistics/overallStat.php',['sites'=>$sites,'statistic'=>$this->statistic,'menu'=>$menu,'title'=>$this->title,'menu'=>$this->menu]);

	}

	public function actionEverydayStat()
	{
		$this->title .="Ежедневная статистика";
		$sites=M_Sites::allSites();
		$persons=M_Persons::allPersons();
		
		if($this->isPost())
		{
			
			$start=new DateTime($_POST['startdate']);
			$finish=new DateTime($_POST['finishdate']);
			$interval=new DateInterval('P1D');
			$range=new DatePeriod($start,$interval,$finish);
			$site=M_Sites::getSite($_POST['select_site']);
			$person=M_Persons::getPerson($_POST['select_person']);
			$everydayStatList=$this->template('view/statistics/everydayStatList.php',['range'=>$range,'site_id'=>$site['id'],'person_id'=>$person['id'],
				'site_name'=>$site['name'],'person_name'=>$person['name']]);

		}

		$menu=$this->template('view/nav_menu.php');
		$this->content=$this->template('view/statistics/everydayStat.php',['sites'=>$sites,'persons'=>$persons,'menu'=>$this->menu,'title'=>$this->title,'everydayStatList'=>$everydayStatList]);
	}
}

?>