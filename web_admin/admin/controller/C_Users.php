<?php 	
class C_Users extends C_Base
{
	private $username;
	private $password;
	private $password_confirm;
	private $email;
	private $msg;
	

	public function actionIndex()
	{
		$this->title .="Вход в систему";
		$this->content=$this->template('view/user.php');
	}


	public function actionLogin()
	{
		$this->title .="Авторизация";
		$mUser=M_Users::getInstance();
		
		if($this->isPost())
		{
			$user=$mUser->getByLogin($_POST['username']);
			$data=$mUser->login($_POST['username'],$_POST['password']);
			if($data==true)
			{
				$this->msg="Вы успешно зарегистрировались.<br>Терперь вы можете войти в систему";
				//header("location: index.php?c=persons");
				//exit();
			}
			else
			{
				$this->msg="Проверьте верно ли введены логин и пароль";
			}

			$this->username=$_POST['username'];
			$this->password=$_POST['password'];
		}
		
		$this->content=$this->template('view/login.php', 
			array('username' =>$this->username,
				'password'=>$this->password,
				'msg'=>$this->msg,
				'title'=>$this->title ));
	}
	public function actionRegister()
	{
		$this->title.="Регистрация";
		$mUser=M_Users::getInstance();

		if ($this->isPost()) 
		{
			$user=$mUser->getByLogin($_POST['username']);
			$data=$mUser->register(
				$_POST['username'],
				$_POST['password'],
				$_POST['password_confirm'],
				$_POST['email']);

			if($data==true)
				{
					header("location:index.php?c=users&action=login");
					exit();
				}
				else
				{
					
					$this->msg="Регистрация не удалась";

					if($_POST['password']!==$_POST['password_confirm']) 
					{
						$this->msg="Пароли не совпадают";
					}
					if($user==true)
					{
						$this->msg="Пользователь c таким именем уже существует";
					}
				}
			
			$this->username=$_POST['username'];
			$this->password=$_POST['password'];
			$this->password_confirm=$_POST['password_confirm'];
			$this->email=$_POST['email'];
		}
		
		$this->content=$this->template('view/register.php', 
										array('username' =>$this->username,
											'password'=>$this->password,
											'password_confirm'=>$this->password_confirm,
											'email'=>$this->email,
											'msg'=>$this->msg,
											'title'=>$this->title));
	}
}


 ?>