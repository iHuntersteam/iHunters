<?php 

class M_Persons
{
	private static $instance;
	
	public static function getInstance()
	{
		if(self::$instance==null)
		{
			self::$instance= new M_Persons;
		}
		return self::$instance;
		
	}
	public function allPersons()
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM persons ORDER BY id");
		return $rows;
	}

	public function getPerson($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->Select("SELECT * FROM persons WHERE id=$id");
		return $rows[0];
	}
	 public function addPerson($name)
	 {

        $db=M_Mysql::getInstance();
        $db->insert("persons",['name'=>$name]);
        return $db;
    }
}

?>