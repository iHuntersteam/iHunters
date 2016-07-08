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
		$query="SELECT * FROM persons WHERE id='%d'";
		$id=$db->mres($id);
		$result=sprintf($query,$id);
		$rows=$db->select($result);
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
    	$where="id=%d";
		$id=$db->mres($id);
		$result=sprintf($where,$id);
    	$rows=$db->update("persons",['name'=>$name],$result);
    	return $rows;
    }
    public  static function deletePerson($id)
	{
		$db=M_Mysql::getInstance();
		$where="id=%d";
		$id=$db->mres($id);
		$result=sprintf($where,$id);
		$db->delete("persons",$result);
		return $db;
	}
}

?>