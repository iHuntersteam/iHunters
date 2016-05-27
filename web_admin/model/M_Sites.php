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
}

?>