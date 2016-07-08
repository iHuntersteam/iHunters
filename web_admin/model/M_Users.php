<?php 

class M_Users 
{
	private static $instance;
	private $msql;
	private $sid;
	private $uid;

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
		$this->sid=null;
		$this->uid=null;
	
	}
	//очистка неиспользуемой сессии(20 мин)
	public function clearSession()
	{
		$min=date('Y-m-d H:i:s',time()-60*20);
		$time="time_last<'%s'";
		$where=sprintf($time,$min);
		$this->msql->delete('sessions',$where);
	}


	//получаем пользователя по id
	public function getByLogin($username)
	{
		$query="SELECT * FROM users WHERE username ='%s'";
		$username=$this->msql->mres($username);
		$result=sprintf($query,$username);
		$rows=$this->msql->select($result);
		return $rows[0];
	}

	//регистрируем пользователя
	public function register($username,$password,$password_comfirm,$email)
	{
		$username=trim($username);
		$password=trim($password);
		$password_comfirm=trim($password_comfirm);
		$email=trim($email);
		$check_username=$this->getByLogin($username);
		if($password !== $password_comfirm) return false;
		if($check_username==true) return false;
		$option=['cost'=>10];
		$password=password_hash($password,PASSWORD_BCRYPT,$option);
		$table="users";
		$object=["username"=>$username,"password"=>$password,'email'=>$email];
		$this->msql->insert($table,$object);

		return true;
	}
	//авторизируем пользователя
	public function login($username,$password,$remember=true)
	{
		$user=$this->getByLogin($username);
		$id=$user['id'];
		if($user==null) return false;
		if($user['username']!=$username) return false;
		if(!password_verify($password,$user['password'])) return false;

		if($remember)
		{
			$expire = time()+3600*24*10;
			setcookie('username', $username, $expire);
			setcookie('password', $password, $expire);
		}
		$this->sid=$this->openSession($id);
		
		return true;	
	}
	//выход пользователя
	public function logout()
	{
		setcookie('username','',time()-1);
		setcookie('password','',time()-1);
		unset($_COOKIE['username']);
		unset($_COOKIE['password']);
		unset($_SESSION['username']);
		unset($_SESSION['sid']);
		unset($_SESSION['password']);
		session_destroy();
		$this->sid=null;
		$this->uid=null;
	}
	//проверка активности пользователя
	public function isOnline($id)
	{
		$query="SELECT * FROM sessions WHERE id_user='$id'";
		$rows=$this->msql->select($query);
		return(count($rows)>0);
	}
	//получение id текущего пользователя-результат uid

	public function getUid()
	{
		if($this->uid!=null) return $this->uid;

		$sid=$this->getSid();

		if($sid==null) return null;

		$query="SELECT id_user FROM sessions WHERE sid='$sid'";
		$rows=$this->msql->select($query);

		if(count($rows)==0) return null;

		$this->uid=$rows[0]['id_user'];
		return $this->uid;
	}
	//получаем индетификатор текущей сессии\
	public function getSid()
	{
		if($this->sid !=null) return $this->sid;//проверка кеша

		$sid=$_SESSION['sid'];//ищем индетификатор в сессии

		if($sid !=null)// если нашли пробуем обновить time_last
		{
			$session=array();
			$session['time_last']=date('Y-m-d H:i:s');
			$where="sid='$sid'";
			$affected_rows=$this->msql->update('sessions',$session,$where);

			if($affected_rows==0)
			{
				$query="SELECT count(*) FROM sessions WHERE sid='$sid'";
				$rows=$this->msql->select($query);

				if($rows[0]['count(*)']==0) $sid=null;

			}
		}
		//если не нашли сессию то ищем юзернэйм и хэш пароля в куках(переподключаемся).
		if($sid==null && isset($_COOKIE['username']))
		{
			$user=$this->getByLogin($_COOKIE['username']);
			
			if($user!=null && $user['password']==$_COOKIE['password'])
			{
				$sid=$this->openSession($user['id']);
			}
		}

		if($sid !=null) $this->sid=$sid;

		return $sid;
	}
	// открытие новой сессии.Результат sid
	private function openSession($id)
	{
	 	$sid=$this->generateString(10);//генерируем индетификатор сессии;

	 	$now=date('Y-m-d H:i:s');
	 	$session=array();
	 	$session['id_user']=$id;
	 	$session['sid']=$sid;
	 	$session['time_start']=$now;
	 	$session['time_last']=$now;
	 	$this->msql->insert('sessions',$session);

	 		$_SESSION['sid']=$sid;
	 		return $sid;
	}
	private function generateString($length=10)
	{
			$chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code="";
			$clen=strlen($chars)-1;

			while(strlen($code)<$length)
			{
				$code.=$chars[mt_rand(0,$clen)];
			}
					
			
			return $code;

	}
}
?>