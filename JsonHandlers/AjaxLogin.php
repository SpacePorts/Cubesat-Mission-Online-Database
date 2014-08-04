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

		if(empty($_POST["password"]))
		{
			$error->AddErrorPair("password","Password Required");
		}

		if(!$error->HasError())
		{
			$luser = $this->_userTable->CheckoutUser($_POST["username"],$_POST["password"]);
			if(isset($luser))
			{
				$luser->StoreInSession();
			}
			else
			{
					$error->AddErrorPair("username","Username or Password is Invalid");
			}
		}
	}
}




?>