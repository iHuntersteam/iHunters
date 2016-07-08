<?php 

class M_Keywords
{
	
	public static function allKeywords($person_id)
	{
		$db=M_Mysql::getInstance();
		$query="SELECT * FROM keywords WHERE person_id='%d' ORDER BY id";
		$person_id=$db->mres($person_id);
		$result=sprintf($query,$person_id);
		$rows=$db->select($result);
		
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
		$query="SELECT * FROM keywords WHERE id='%d'";
		$id=$db->mres($id);
		$result=sprintf($query,$id);
		$rows=$db->select($result);
		return $rows[0];
	}
	public static function editKeyword($id,$name)
    {
    	$db=M_Mysql::getInstance();
    	$where="id='%d'";
    	$id=$db->mres($id);
    	$result=sprintf($where,$id);
		$rows=$db->update("keywords",['name'=>$name],$result);
    	return $rows;
    }
    public static function deleteKeyword($id)
	{
		$db=M_Mysql::getInstance();
		$where="id='%d'";
    	$id=$db->mres($id);
    	$result=sprintf($where,$id);
		$db->delete("keywords",$result);
		return true;
	}
}

?>