<?php 


class M_Sites 
{
	private static $instance;
	
	public static function getInstance()
	{
		if(self::$instance==null)
		{
			self::$instance= new M_Sites;
		}
		return self::$instance;
		
	}
	
	public function allSites()
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM sites ORDER BY id");
		return $rows;
	} 
	public function deleteSite($id)
	{
		$db=M_Mysql::getInstance();
		$db->delete("sites","id=$id");
		return $db;
	}
	 public function addSite($name)
	 {

        $db=M_Mysql::getInstance();
        $db->insert("sites",['name'=>$name]);
        return $db;
    }
    public function editSite($id,$name)
    {
    	$db=M_Mysql::getInstance();
    	$rows=$db->update("sites",['name'=>$name],"id=$id");
    	return $rows;
    }
    public function getSite($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM sites WHERE id=$id");
		return $rows[0];
	}
}

?>