<?php 

class C_Persons extends C_Base
{	
	
	private $name;
	private $id;

	public function actionIndex()
	{
		$this->title .="Личности";
		$persons=M_Persons::allPersons();
		$this->content=$this->template('view/persons/persons.php', ['persons'=>$persons,'title'=>$this->title,'menu'=>$this->menu]);
	}
	
	public function actionAdd()
	{
		$this->title .="Добавление личности";

		if (!empty($this->isPost())) 
		{
			if(M_Persons::addPerson($_POST['name']))
			{
				header("location: index.php?c=persons");
			}
			$this->name=$_POST['name'];
		}

		$this->content=$this->template('view/editor/add.php', ['name'=>$this->name,'title'=>$this->title,'menu'=>$this->menu]);
	}
	public function actionEdit()
	{
		$this->title .="Редактирование имя личности";

		if(isset($_GET['id']))
			{
				$this->id=$_GET['id'];
				$person=M_Persons::getPerson($this->id);

				if($this->isPost())
				{
					if (M_Persons::editPerson($this->id,$_POST['name'])) 
					{
						header("location: index.php?c=persons");
						die();
					}
					else
					{
						header("location: index.php?c=persons");
						die();
					}
					$this->name=$_POST['name'];
				}
			}

		$this->content=$this->template('view/editor/edit.php', ['name'=>$person['name'],'title'=>$this->title,'menu'=>$this->menu]);
	}
	public function actionDelete()
	{
		$this->title .="Удаление из справочника";
			
			if(isset($_GET['id']))
			{
				$this->id=$_GET['id'];
				$person=M_Persons::getPerson($this->id);
			
				if($this->isPost())
				{
					if(isset($_POST['yes']))
					{
						$this->id=$_GET['id'];
						M_Persons::deletePerson($this->id);
						header("location: index.php?c=persons");
						die();
					}
					else
					{
						header("location: index.php?c=persons");
						die();
					}
				}
			}
		
		$this->content=$this->template('view/editor/delete.php', ['name'=>$person['name'],'title'=>$this->title,'menu'=>$this->menu ]);
	}
}

?>