<?php
require_once "Database.php";
require_once "TeamRow.php";
require_once "UserRow.php";
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
      $lsql = new Sql($this->_adapter,"team");

      $lselect = $lsql->Select();
      $lselect->Where(array("team_id"=>$id));

      $lresults = $lsql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
        return new TeamRow($row);
      }



   }


   public function Find($page,$numerOfEntires,$where,$user = NULL)
   {
      $sql = new Sql($this->_adapter,"team");
      $lselect = $sql->Select();

      if($numerOfEntires != -1)
      $lselect->limit($numerOfEntires); 

      if(!is_null($user))
      {
    
        $lselect->join("relation_teams_users","relation_teams_users.team_id = team.team_id");
        $where->equalTo("relation_teams_users.user_id",$user->GetId());
      }

      $lselect->where($where);
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
      $lsql = new Sql($this->_adapter,"team");
      $linsert = $lsql->insert();

      $linsert->values(array(
        "name" => $name,
        "latlong" => $latLong 
      ));

      $lsql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());
   }
}

?>