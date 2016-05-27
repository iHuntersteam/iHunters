<?php 

class C_Persons extends C_Base
{	
	public function action_Index()
	{
		$this->title .=": Личности";
		$mPersons=M_Persons::getInstance();
		$persons=$mPersons->allPersons();
		$this->content=$this->template('view/persons.php', array('persons'=>$persons));
	}
	
	public function action_Get()
	{
		$this->title .=": Личность";

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
	public function action_Add()
	{
		if($this->isPost())
		{
			if(isset($_POST['add']))
			{

			}
		}
	}
	public function action_Edit()
	{
		if($this->isPost())
		{
			if(isset($_POST['edit']))
			{
				
			}
		}
	}
	public function action_Delete()
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