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
	
	public function actionGet()
	{
		$this->title .="Личность";

		if ($this->isGet()) 
		{
			if (isset($_GET['id'])) 
			{
				$this->id=$_GET['id'];
				$person=M_Persons::getPerson($this->id);
			}
		}
		
		$this->content=$this->template('view/person.php', array('name'=>$person['name']));
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

		$this->content=$this->template('view/personsAdd.php', array('name'=>$this->name,'title'=>$this->title));
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
		if($this->isPost())
		{
			if(isset($_POST['delete']))
			{
				
			}
		}
	}
}

?>