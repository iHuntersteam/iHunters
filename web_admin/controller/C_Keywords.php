<?php 

class C_Keywords extends C_Base
{
	
	private $id;
	private $name;


	public function actionIndex()
	{
		$this->title .="Ключевые слова";
		$persons=M_Persons::allPersons();
		
		if($this->isPost())
		{
			if(isset($_POST['select']))
			{
				$this->id=$_POST['select'];
				$person=M_Persons::getPerson($this->id);
				$keywords=M_Keywords::allKeywords($this->id);
				$keywordsList=$this->template('view/keywords/keywordsList.php',['keywords'=>$keywords,'name'=>$person['name'],'id'=>$person['id']]);
			}
		
		}

		$this->content=$this->template('view/keywords/keywordsPage.php',['persons'=>$persons,'title'=>$this->title,'keywordsList'=>$keywordsList,'menu'=>$this->menu]);
		
	}
	public function actionAdd()
	{
		
		$this->title .="Добавление ключевого слова";

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
		$this->content=$this->template('view/editor/add.php',['name'=>$this->name,'id'=>$this->id,'title'=>$this->title,'menu'=>$this->menu]);
	}
	public function actionEdit()
	{
		$this->title .="Редактироние ключевого слова";

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

		$this->content=$this->template('view/editor/edit.php', ['name'=>$keyword['name'],'title'=>$this->title,'menu'=>$this->menu]);
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
		
		$this->content=$this->template('view/editor/delete.php',['name'=>$keyword['name'],'title'=>$this->title,'menu'=>$this->menu ]);
	}
}
?>
