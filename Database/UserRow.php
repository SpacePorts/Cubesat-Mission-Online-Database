<?php 
require_once "Database.php";


use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class UserRow extends Row
{
	 const ADMIN = "administrator";
	 const PRODUCER = "production";
	 const END_USER = "end_user";

	private $_username;
	private $_email;
	private $_type;
	private $_id;
	private $_userPassword;

	public function __construct($userData)
	{
		parent::__construct();
		
		$this->_username = $userData["user_name"];
		$this->_email = $userData["email"];
		$this->_type = $userData["type"];
		$this->_id = $userData["user_id"];
		$this->_userPassword = $userData["password"];
	}

	public static function RetrieveFromSession()
	{
		if(isset($_SESSION["user"]))
		{
			$luser =  unserialize($_SESSION["user"]);
			return $luser;
		}
	}

	public function StoreInSession()
	{
		$_SESSION["user"] = serialize($this);
	}

	public function GetUsername()
	{
		return $this->_username;
	}

	public function GetEmail()
	{
		return $this->_email;
	}

	public function GetAvatarImageToken()
	{
		return $this->_id . "user-avatar";
	}

	public function RetrieveTeams()
	{
		
	}

	/*
	*Gets the type of user
	*/
	public function GetType()
	{
		return $this->_type;
	}

	/*
	*updates the user password
	*/
	public function UpdateUserPassword($password)
	{
		$sql = new Sql($this->_adapter,"user");
		$lupdate = $sql->update();
		$lupdate->where(array("user_id" => $this->_id));
		$lupdate->set(array('password' =>  sha1($password.PASSWORD_SALT)));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}

	public function VerifyPassword($password)
	{
		if($this->_userPassword == sha1($password.PASSWORD_SALT))
		{
			return true;
		}
		return false;
	}
}

?>