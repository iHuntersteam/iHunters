<?php 

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
				$person=M_Persons::getPerson($this->id);
				$keywords=M_Keywords::allKeywords($this->id);
				$keywordsList=$this->template('view/keywordsList.php',
				array('keywords'=>$keywords,'name'=>$person['name'],'id'=>$person['id']));
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
			$this->id=$_GET['id'];
		
		if(!empty($this->isPost()))
		{
			if(M_Keywords::addKeywords($_POST['name'],$this->id))
			{
				header("location: index.php?c=keywords");
				exit();
			}
			$this->name=$_POST['name'];
			
		}
	}
		$this->content=$this->template('view/add.php',
			array('name'=>$this->name,'id'=>$this->id,'title'=>$this->title));
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
					else
					{
						header("location: index.php?c=keywords");
						die();
					}
					$this->name=$_POST['name'];
				}
			}

		$this->content=$this->template('view/edit.php', array('name'=>$keyword['name'],'title'=>$this->title));
	}
	
	public function actionDelete()
	{
		$this->title .="Удаление из справочника";
			
			if(isset($_GET['id']))
			{
				$this->id=$_GET['id'];
				$keyword=M_Keywords::getKeyword($this->id);
			
				if($this->isPost())
				{
					if(isset($_POST['yes']))
					{
						$this->id=$_GET['id'];
						M_Keywords::deleteKeyword($this->id);
						header("location: index.php?c=keywords");
						die();
					}
					else
					{
						header("location: index.php?c=keywords");
						die();
					}
				}
			}
		
		$this->content=$this->template('view/delete.php', array('name'=>$keyword['name'],'title'=>$this->title ));
	}
}
?>
