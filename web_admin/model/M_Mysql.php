<?php 

define('DB_HOST', 'localhost');
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_NAME','ihunters');


class M_Mysql
{
	
	private static $instance;
	private $link;


	private function __construct()
	{
		$this->link=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
			or die(mysqli_error($this->link));
		
		mysqli_select_db($this->link,DB_NAME)
			or die("Error:Select failed" . mysqli_error($this->link));
		
		mysqli_query($this->link,"SET NAMES UTF8");	
	}

	public static function getInstance()
	{
		if(self::$instance==null)
		{
			self::$instance=new M_Mysql();
		}
		return self::$instance;
	}
	public function select($sql)
	{
		$result=mysqli_query($this->link,$sql);

		if(!$result) die (mysqli_error($this->link));

		$count=mysqli_num_rows($result);
		
		$row=array();
		
		for ($i=0; $i<$count; $i++)
		{
			$row[]=mysqli_fetch_assoc($result);
		} 	
		return $row;
	}
	public  function delete($table,$where)
	{
		$sql=sprintf("DELETE FROM %s WHERE %s",$table,$where);
		$result=mysqli_query($this->link,$sql);

		if (!$result) die(mysqli_error($this->link));

		return mysqli_affected_rows($this->link);
		
	}
	public function insert($table,$object){

		$colunms=array();
		$values=array();

		foreach ($object as $key => $value) {
			
			$key=mysqli_real_escape_string($this->link,$key);
			$colunms[]=$key;
			
			if($value==NULL) $values[]="NULL";
			else{
					$value=mysqli_real_escape_string($this->link,$value);
				 	$values[]="'$value'";
				}
		}
		
		$colums_s=implode(",", $colunms);
		$values_s=implode(",", $values);

		$sql="INSERT INTO $table($colums_s) VALUES($values_s)";

		$result=mysqli_query($this->link,$sql);
			if (!$result) die (mysqli_error($this->link));

		return mysqli_insert_id($this->link);

	}
	public function update($table,$object,$where)
	{
		$sets=array();

		foreach ($object as $key => $value) 
		{
			$key=mysqli_real_escape_string($this->link,$key);

			if($value==null) $sets[]="$key=null";
			else
			{
				$value=mysqli_real_escape_string($this->link,$value);
				$sets[]="$key='$value'";
			}
		}
		$set_s=implode(",",$sets);
		$sql=sprintf("UPDATE %s SET %s WHERE %s",$table,$set_s,$where);
		$result=mysqli_query($this->link,$sql);

		if(!$result) die(mysqli_error($this->link));

		 return mysqli_affected_rows($this->link);
	}
}








 ?>