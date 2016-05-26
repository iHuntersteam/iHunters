<?php 
/**
* 
*/
class M_Keywords
{
	
	private static $instance;
	
	public static function getInstance()
	{
		if(self::$instance==null)
		{
			self::$instance= new M_Keywords;
		}
		return self::$instance;
		
	}
	public function allKeywords($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM keywords WHERE person_id=$id ORDER BY id");
		return $rows;
	}
}

?>