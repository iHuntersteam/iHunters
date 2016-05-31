<?php 
 include_once("model/M_Persons.php");

class C_Keywords extends C_Base
{
	
	public function actionIndex()
	{
		$this->title .="Ключевые слова";
		$mPersons=M_Persons::getInstance();
		$persons=$mPersons->allPersons();
		
		if($this->isPost())
		{
			if(isset($_POST['select']))
			{
				$this->id=$_POST['select'];
				$keywords=M_Keywords::allKeywords($this->id);
				$keywordsList=$this->template('view/keywordsList.php',
				array('keywords'=>$keywords));
			}
		
		}

		
		$this->content=$this->template('view/keywordsPage.php',
			array('persons'=>$persons,'keywordsList'=>$keywordsList));
		
	}
	public function actionAdd()
	{
		
		$this->title .="Добавить ключевые слова";

		if (isset($_GET['id'])) 
		{
			$this->person_id=$_GET['id'];
		
		if(!empty($this->isPost()))
		{
			if(M_Keywords::addKeywords($_POST['name'],$this->person_id))
			{
				header("location: index.php?c=keywords");
				exit();
			}
			$this->name=$_POST['name'];
			
		}
	}
		$this->content=$this->template('view/Add.php',
			array('name'=>$this->name,'person_id'=>$this->person_id,'title'=>$this->title));
	}
	public function actionEdit()
	{
		$this->title .="Редактировать ключевое слово";

		if(isset($_GET['id']))
			{
				$this->id=$_GET['id'];
				$keyword=M_Keywords::getKeyword($this->id);

				if($this->isPost())
				{
					if (M_Keywords::editKeyword($this->id,$_POST['name'])) 
					{
						header("location: index.php?c=keywords");
						die();
					}
					$this->name=$_POST['name'];
				}
			}

		$this->content=$this->template('view/Edit.php', array('name'=>$keyword['name'],'title'=>$this->title));
	}
	
	public function actionDelete()
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
