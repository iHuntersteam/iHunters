<?php 

/**
* 
*/
class C_Statistic extends C_Base
{
	
	public function actionIndex()
	{
		
		$this->title .="Общая статистика";
		$sites=M_Sites::allSites();
		$persons=M_Persons::allPersons();
		
		if($this->isPost())
		{
			if(isset($_POST['select']))
			{
				$this->id=$_POST['select'];
				$statistic=M_Statistic::siteAllPersonRank($this->id);
				$site=M_Sites::getSite($this->id);
				$this->statistic=$this->template('view/statistic.php',['statistic'=>$statistic,'name'=>$site['name']]);
			}
		}
		
		$this->menu=$this->template('view/menu.php');
		$this->content=$this->template('view/overallStat.php',['sites'=>$sites,
			'statistic'=>$this->statistic,
			'menu'=>$this->menu,
			'title'=>$this->title,
			'persons'=>$persons]);

	}
}

?>