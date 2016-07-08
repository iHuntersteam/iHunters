<?php 	

class C_Users extends C_Base
{
	private $username;
	private $password;
	private $password_confirm;
	private $email;
	

	public function actionIndex()
	{
		$this->title .="Вход в систему";
		$this->content=$this->template('view/users/user.php');
	}


	public function actionLogin()
	{
		$this->title .="Авторизация";
		$mUser=M_Users::getInstance();
		$uid=$mUser->getUid();
		$errors=[];

		if($uid !==null)
		{
			header("location: index.php?c=statistic");
			exit();
		}
		
		if($this->isPost())
		{
			$user=$mUser->getByLogin($_POST['username']);
			$data=$mUser->login($_POST['username'],
				$_POST['password'],$_POST['remember']);
			if($data==true)
			{
				$_SESSION['username']=$_POST['username'];
				$user=$mUser->getByLogin($_SESSION['username']);
				header("location: index.php?c=statistic");
				exit();
			}
			else
			{
				$errors[]="Проверьте верно ли введены логин и пароль";
			}

			$this->username=$_POST['username'];
			$this->password=$_POST['password'];
		}
		
		$this->content=$this->template('view/auth/login.php', 
			['username' =>$this->username,'password'=>$this->password,'errors'=>$errors,
				'title'=>$this->title ]);
	}
	public function actionRegister()
	{
		$this->title.="Регистрация";
		$mUser=M_Users::getInstance();
		$errors=array();

		if ($this->isPost()) 
		{
			$user=$mUser->getByLogin($_POST['username']);
			$data=$mUser->register(
				$_POST['username'],$_POST['password'],$_POST['password_confirm'],$_POST['email']);
			
			if($data==true)
				{
					$errors[]="Вы успешно зарегистрировались.<br>Терперь вы можете войти в систему";
					//header("location:index.php?c=users&action=login");
					//exit();
				}
				else
				{
					
					$errors[]="Регистрация не удалась";

					if($_POST['password']!==$_POST['password_confirm']) 
					{
						$errors[]="Пароли не совпадают";
					}
					if($user==true)
					{
						$errors[]="Пользователь c таким именем уже существует";
					}
				}
			
			$this->username=$_POST['username'];
			$this->password=$_POST['password'];
			$this->password_confirm=$_POST['password_confirm'];
			$this->email=$_POST['email'];
		}
		
		$this->content=$this->template('view/auth/register.php', 
			['username' =>$this->username,'password'=>$this->password,'password_confirm'=>$this->password_confirm,'email'=>$this->email,
			 'errors'=>$errors,'title'=>$this->title]);
	}
	public function actionLogout()
	{
		$mUser=M_Users::getInstance();
		$mUser->logout();
		header("location: index.php");
		exit();
	}
}
?>