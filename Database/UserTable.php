<?php 
require_once "Database.php";
require_once "UserRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class UserTable extends Table
{

   function __construct() {
       parent::__construct();
   }


	//tells if the user exist
	public function CheckIfUserExist($name){
		$sql = new Sql($this->_adapter,"user");
		$lselect = $sql->Select();
		$lselect->Where(array("user_name" => $name));
		$lselect->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));

		$lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

		$resultSet = new ResultSet;
		$resultSet->initialize($lresults);

		foreach ($resultSet as $row) {
			if($row["num"] > 0)
			{
				return true;
			}
			return false;
		}

	}

	//adds a user to the database
	public function AddUser($name,$email,$password)
	{

		$sql = new Sql($this->_adapter,"user");
		$linsert = $sql->insert();
		$linsert->values(array(
			"user_name" => $name,
			"password" => sha1($password.PASSWORD_SALT),
			"email" => $email,
			"type" => "end_user"));
		$sql->prepareStatementForSqlObject($linsert)->execute();

	}

	//checks out the user from the database using name and password
	public function CheckoutUser($name,$password)
	{
   	  $sql = new Sql($this->_adapter,"user");
      $lselect = $sql->Select();
      $lselect->where(array("user_name" => $name,"password" =>sha1($password.PASSWORD_SALT)));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
        return new UserRow($row );
      }

	}

}

?>