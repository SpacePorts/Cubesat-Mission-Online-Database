<?php 
require_once "Database.php";
require_once "MissionRow.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class MissionTable extends Table
{

   function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "mission";
  }

  public function GetColumnStructure()
  {
    $lmissionID = new Column\Integer("mission_id");
    $lmissionID->setOption('auto_increment', true);
    return array(
      array("column"=>$lmissionID,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("objective")),
      array("column"=>new Column\Text("wiki")),
      array("column"=>new Column\Text("name")),
      array("column"=>new Column\Text("content")));
  }



   public function GetRowById($id)
   {
     $sql = new Sql($this->_adapter,"mission");

      $lselect = $sql->Select();
      $lselect->Where(array("mission_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
        return new MissionRow($row);
      }

   }

   public function Find($page,$numerOfEntires,$where)
   {

     $sql = new Sql($this->_adapter,"mission");
      $lselect = $sql->Select();
      $lselect->where($where);

      if($numerOfEntires != -1)
      $lselect->limit($numerOfEntires); 

      $lselect->offset($page * $numerOfEntires); 

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $missions = array();
      foreach ($resultSet as $row) {

          array_push($missions,new MissionRow($row));
      }

       return $missions;
   }

   function FindCount($where)
   {
      $sql = new Sql($this->_adapter,"mission");
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


   public function AddMission($name,$objective,$wiki,$content)
   {
      $sql = new Sql($this->_adapter,"mission");
      $linsert = $sql->insert();
      $linsert->values(array(
         'name' => $name,
         'objective' => $objective,
         'wiki' => $wiki,
         'content' => $content
      ));

     $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());

   }
}

?>