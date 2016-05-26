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
	
}

?>