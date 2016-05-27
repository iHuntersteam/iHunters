<?php 
/**
* 
*/
class M_Keywords
{
	
	public static function allKeywords($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM keywords WHERE person_id=$id ORDER BY id");
		return $rows;
	}
}

?>