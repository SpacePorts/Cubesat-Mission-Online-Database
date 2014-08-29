<?php
require_once ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/Database/UserTable.php";

class Register  extends PageBase{
	private $_form;
	private $_userTable;
	function __construct() {
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_userTable = new UserTable();


	}
	
	function HeaderContent()
	{

	}

	function Ajax($error,&$output)
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
			$output["redirect"] = PAGE_GET_URL . "&registered=registered";
		}
	}


	function BodyContent()
	{
		if(Get("registered") == "registered")
		{
			?>
			<div class="jumbotron">
			  <h1>Welcome!</h1>
			  <p>you can now sign in with the top right button.</p>
			</div>
			<?php

		}
		else
		{
			$this->_form->AddTextInput("username","Username:");
			$this->_form->AddPasswordInput("password","Password:");
			$this->_form->AddPasswordInput("re_enter_password","Confirm Password:*");
			$this->_form->AddTextInput("email","Email:*");
			$this->_form->AddInput("captcha","Capcha:*",recaptcha_get_html(CAPTCHA_PUBLIC_KEY));
			$this->_form->AddSubmitButton("Sign Up");
			$this->_form->Output();
		}

	}
}
?>
