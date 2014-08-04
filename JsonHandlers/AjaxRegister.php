<?php
require ROOT . "/Database/UserTable.php";

class Json
{
	private $_userTable;
	function __construct()
	{
		$this->_userTable = new UserTable();
	}

	function Exectute($error,&$output)
	{
		if(empty($_POST["username"]))
		{
			$error->AddErrorPair("username","Username Required");
		}
		else if($this->_userTable->CheckIfUserExist($_POST["username"]))
		{
			$error->AddErrorPair("username","Username Already used");
		}

		if((empty($_POST["password"]) || empty($_POST["re_enter_password"]))  || $_POST["password"] != $_POST["re_enter_password"])
		{
			$error->AddErrorPair("password","Passwords don't match");
		}

		if(empty($_POST["email"]))
		{
			$error->AddErrorPair("email","Email is Required");
		}
		else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
		{
				$error->AddErrorPair("email","Email is Invalid");
		}

		if(empty($_POST["recaptcha_challenge_field"]) || empty($_POST["recaptcha_response_field"]))
		{
			$error->AddErrorPair("captcha","Captcha is Invalid");
		}
		else
		{
			$validRecapche = recaptcha_check_answer(CAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
			if(!$validRecapche)
			{
				$error->AddErrorPair("captcha","Captcha is Invalid");
			}
		}
		

		if(!$error->HasError())
		{
			$this->_userTable->AddUser($_POST["username"],$_POST["email"],$_POST["password"]);
		}
	
	}
}



?>