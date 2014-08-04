<?php 
require_once "Database.php";
require_once "MissionRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class MissionTable extends Table
{

   function __construct() {
       parent::__construct();
   }

   public function GetRowById($id)
   {
   	  $sqlSelect = new SqlSelect("mission",$this->_db);
   	  $sqlSelect->Where()->EqualTo("mission_id",$id);

   	  $stmt =  $sqlSelect->Execute();
      while ($row = $stmt->fetch()) {
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