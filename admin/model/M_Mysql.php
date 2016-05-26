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
	public function Select($sql)
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
}








 ?>