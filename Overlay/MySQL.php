<?php
require_once "../Config.php";


class MySQL{
	private $DB;

	//INTIALZATION----------------------------------------------------------------------------------------------------------------------------
	function __construct() {
		$this->DB= new mysqli(HOST,USER_NAME,USER_PASSWORD,DB_NAME);
	}

	function __destruct(){
		$this->DB->close();
	}

	public function CheckIfUserExist($name){
		$stmt = $this->DB->prepare("SELECT count(*) as total FROM users WHERE Name = ?");
		$stmt->bind_param('s', $name);

		$stmt->execute();

		$stmt->bind_result($total);
		while($stmt->fetch()){
			if($total == 0)
			{
				$stmt->close();
				return false;
			}
			else
			{
				$stmt->close();
				return true;
			}
		}
	}

	public function AddUser($name,$email,$password)
	{
		$Type = "user";
		$stmt = $this->DB->prepare('INSERT INTO users (username,password,email,type) VALUES (?,?,?,?)');
		$lHashedPassword = sha1($password.PASSWORD_SALT);
		$stmt->bind_param('ssss',$name,$lHashedPassword ,$email,$Type);
		$stmt->execute();

		$stmt->close();
	}

	public function LoginUser($name,$password)
	{
		$password = sha1($password.PASSWORD_SALT);
		$stmt = $this->DB->prepare("SELECT user_id,username,email,type FROM users WHERE username = ? AND password = ?");
		$stmt->bind_param('ss', $name,$password);
		
		$stmt->execute();

		$stmt->bind_result($ClientID,$ClientUserName,$ClientEmail,$ClientType);
		while($stmt->fetch()){
			$_SESSION['ClientID'] =$ClientID;
			$_SESSION['ClientUserName'] = $ClientUserName;
			$_SESSION['ClientEmail'] =$ClientEmail;
			$_SESSION['ClientType'] = $ClientType;
			$stmt->close();
			return true;
		}

		$stmt->close();
		return false;

	}

	

	
}
?>