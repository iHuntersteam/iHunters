<?php 

class M_Keywords
{
	
	public static function allKeywords($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->select("SELECT * FROM keywords WHERE person_id=$id ORDER BY id");
		return $rows;
	}
	public static function addKeywords($name,$id)
	{
		$db=M_Mysql::getInstance();
		$db->insert("keywords",['name'=>$name,'person_id'=>$id]);
		return $db;
	}
	 public static function getKeyword($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->select("SELECT * FROM keywords WHERE id=$id");
		return $rows[0];
	}
	public static function editKeyword($id,$name)
    {
    	$db=M_Mysql::getInstance();
    	$rows=$db->update("keywords",['name'=>$name],"id=$id");
    	return $rows;
    }
    public static function deleteKeyword($id)
	{
		$db=M_Mysql::getInstance();
		$db->delete("keywords","id=$id");
		return $db;
	}

}

?>