<?php 
 include_once("model/M_Persons.php");

class C_Keywords extends C_Base
{
	
	
	public function action_Index()
	{
		$this->title .=": Ключевые слова";
		$mPersons=M_Persons::getInstance();
		$persons=$mPersons->allPersons();
		
		if(isset($_POST['select']))
		{
			
			$mKeywords=M_Keywords::getInstance();
			$keywords->allKeywords();
			$this->keywords=$this->template('view/keywordsList.php',array('keywords'=>$keywords));
		}
		
		$this->content=$this->template('view/keywordsPage.php',array('persons'=>$persons,'keywordsList'=>$this->keywords));
	}
}

?>
