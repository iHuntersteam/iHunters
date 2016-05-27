<?php 
 include_once("model/M_Persons.php");

class C_Keywords extends C_Base
{
	
	public function action_Index()
	{
		$this->title .=": Ключевые слова";
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
