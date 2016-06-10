<?php 

class M_Persons
{
	
	public  static function allPersons()
	{
		$db=M_Mysql::getInstance();
		$rows=$db->select("SELECT * FROM persons ORDER BY id");
		return $rows;
	}

	public static function getPerson($id)
	{
		$db=M_Mysql::getInstance();
		$rows=$db->select("SELECT * FROM persons WHERE id=$id");
		return $rows[0];
	}
	 public static function addPerson($name)
	 {

        $db=M_Mysql::getInstance();
        $db->insert("persons",['name'=>$name]);
        return $db;
    }
     public static function editPerson($id,$name)
    {
    	$db=M_Mysql::getInstance();
    	$rows=$db->update("persons",['name'=>$name],"id=$id");
    	return $rows;
    }
    public  static function deletePerson($id)
	{
		$db=M_Mysql::getInstance();
		$db->delete("persons","id=$id");
		return $db;
	}
}

?>