<?php 

class C_Persons extends C_Base
{	
	public function actionIndex()
	{
		$this->title .="Личности";
		$mPersons=M_Persons::getInstance();
		$persons=$mPersons->allPersons();
		$this->content=$this->template('view/persons.php', array('persons'=>$persons,'title'=>$this->title));
	}
	
	public function actionAdd()
	{
		$this->title .="Добавить личность";

		if (!empty($this->isPost())) 
		{
			if(M_Persons::addPerson($_POST['name']))
			{
				header("location: index.php?c=persons");
			}
			$this->name=$_POST['name'];

		}

		$this->content=$this->template('view/add.php', array('name'=>$this->name,'title'=>$this->title));
	}
	public function actionEdit()
	{
		$this->title .="Редактировать имя личности";

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

		$this->content=$this->template('view/edit.php', array('name'=>$person['name'],'title'=>$this->title));
	}
	public function actionDelete()
	{
		$this->title .="Удаление имени личности из справочника";
			
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
		
		$this->content=$this->template('view/delete.php', array('name'=>$person['name'],'title'=>$this->title ));
	}
}

?>