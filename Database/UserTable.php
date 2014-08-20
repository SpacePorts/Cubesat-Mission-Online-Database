<?php 
require_once "Database.php";
require_once "UserRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Ddl\Column;
class UserTable extends Table
{

   function __construct() {
       parent::__construct();
   }

  public function GetTable(){
    return "user";
   }

   public function GetColumnStructure()
   {
    $userId = new Column\Integer("user_id");
    $userId->setOption('auto_increment', true);
    return array(
      array("column"=>$userId,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("user_name")),
      array("column"=>new Column\Text("password")),
      array("column"=>new Column\Text("email")),
      array("column"=>new Column\Text("type")));
   }

   public function GetRowById($id)
   {
   	  $sql = new Sql($this->_adapter,"user");

      $lselect = $sql->Select();
      $lselect->Where(array("user_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
        return new UserRow($row);
      }
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

	public function Find($page,$numerOfEntires,$where)
	{

		$sql = new Sql($this->_adapter,"user");
		$lselect = $sql->Select();
		$lselect->where($where);

		if($numerOfEntires != -1)
		$lselect->limit($numerOfEntires); 

		$lselect->offset($page * $numerOfEntires); 

		$lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

		$resultSet = new ResultSet;
		$resultSet->initialize($lresults);

		$lusers = array();
		foreach ($resultSet as $row) {
			array_push($lusers,new UserRow($row));
		}

		return $lusers;
	}


	public function FindCount($where)
	{
		$sql = new Sql($this->_adapter,"user");
		$lselect = $sql->Select();
		$lselect->where($where);
		$lselect->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));

		$lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

		$resultSet = new ResultSet;
		$resultSet->initialize($lresults);

		foreach ($resultSet as $row) {
			return $row["num"];
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