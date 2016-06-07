<?php 

class M_Users 
{
	private static $instance;
	private $msql;

	public static function getInstance()
	{
		if(self::$instance==null)
		{
			self::$instance= new M_Users();
		}
		return self::$instance;
	}
	
	function __construct()
	{
		$this->msql=M_Mysql::getInstance();
	}

	public function register($username,$password,$password_comfirm,$email)
	{
		$username=trim($username);
		$password=trim($password);
		$email=trim($email);
		$check_username=$this->getByLogin($username);

		if($password !== $password_comfirm) return false;
		if($check_username==true) return false;


		$table="users";
		$object=["username"=>$username,"password"=>md5($password),'email'=>$email];
		$this->msql->Insert($table,$object);

		return true;
	}
	public function getByLogin($username)
	{
		$query="SELECT * FROM users WHERE username LIKE '$username'";
		$rows=$this->msql->Select($query);
		return $rows[0];
	}
	public function login($username,$password,$remember=true)
	{
		$user=$this->getByLogin($username);
		
		if($user==null) return false;

		if($user['username']!=$username) return false;
		if($user['password']!=md5($password)) return false;

		if($remember)
		{
			$expire = time() + 3600 * 24 * 10;
			setcookie('username', $username, $expire);
			setcookie('password', md5($password), $expire);
		}
		return true;	
	}
}






 ?>