<?php 


class M_Sites 
{
		
	public static function allSites()
	{
		$db=M_Mysql::getInstance();
		$rows=$db->select("SELECT * FROM sites ORDER BY id");
		return $rows;
	} 
	public static function deleteSite($id)
	{
		$db=M_Mysql::getInstance();
		$db->delete("sites","id=$id");
		return $db;
	}
	 public static function addSite($name)
	 {

        $db=M_Mysql::getInstance();
        $db->insert("sites",['name'=>$name]);
        return $db;
    }
    public static function editSite($id,$name)
    {
    	$db=M_Mysql::getInstance();
    	$rows=$db->update("sites",['name'=>$name],"id=$id");
    	return $rows;
    }
    public static function getSite($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM sites WHERE id=$id");
		return $rows[0];
	}
	public static function getStatisticById($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->select("SELECT * FROM pages WHERE site_id=$id");
		return $rows;
	}
}

?>