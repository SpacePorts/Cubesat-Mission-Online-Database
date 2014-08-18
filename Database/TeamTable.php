<?php
require_once "Database.php";
require_once "TeamRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Ddl\Column;
class TeamTable extends Table
{

   function __construct() {
       parent::__construct();
   }

  public function GetTable(){
    return "team";
   }

   public function GetColumnStructure()
   {
    $teamId = new Column\Integer("team_id");
    $teamId->setOption('auto_increment', true);
    return array(
      array("column"=>$teamId,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("name")),
      array("column"=>new Column\Text("latlong")));
   }

   public function GetRowById($id)
   {
   		$statment = new SqlStatment("team",$this->_db);
   		$statment->EqualTo("team_id",$id);
   		$stmt =  $statment->Execute();

  		while ($row = $stmt->fetch()) {
  			return new TeamRow($row );
  		}
   }


   public function Find($page,$numerOfEntires,$where)
   {
      $sql = new Sql($this->_adapter,"team");
      $lselect = $sql->Select();
      $lselect->where($where);

      if($numerOfEntires != -1)
      $lselect->limit($numerOfEntires); 

      $lselect->offset($page * $numerOfEntires); 

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $teams = array();
      foreach ($resultSet as $row) 
      {
          array_push($teams,new TeamRow($row));
      }

      return $teams;

   }

   public function AddTeam($name,$latLong)
   {

   }
}

?>