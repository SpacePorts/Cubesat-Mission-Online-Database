<?php 
require_once "Database.php";
require_once "SatelliteRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Ddl\Column;
class SatelliteTable extends Table
{

   function __construct() {
       parent::__construct();
   }

  public function GetTable(){
    return "satellite";
   }

   public function GetColumnStructure()
   {
      $satId = new Column\Integer("sat_id");
      $satId->setOption('auto_increment', true);
      return array(
      array("column"=>$satId,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("name")),
      array("column"=>new Column\Text("content")),
      array("column"=>new Column\Text("wiki")),
      array("column"=>new Column\Text("status")),
      array("column"=>new Column\Text("tle")),
      array("column"=>new Column\Text("orbit")));
   }


    function GetRowById($id)
    {
      $sql = new Sql($this->_adapter,"satellite");

      $lselect = $sql->Select();
      $lselect->Where(array("sat_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
        return new SatelliteRow($row);
      }

    }

   function Find($page,$numerOfEntires,$where)
   {
      $sql = new Sql($this->_adapter,"satellite");
      $lselect = $sql->Select();
      $lselect->where($where);

      if($numerOfEntires != -1)
      $lselect->limit($numerOfEntires); 

      $lselect->offset($page * $numerOfEntires); 

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $satellites = array();
      foreach ($resultSet as $row) {

          array_push($satellites,new SatelliteRow($row));
      }

      return $satellites;
   }

   function FindCount($where)
   {
      $sql = new Sql($this->_adapter,"satellite");
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

   public function AddSatellite($name,$content,$tle,$orbit,$wiki,$status){

      if(SatelliteRow::IsStatusLegal($status))
      {
        $sqlInsert->AddPair("status", $status);
      }
      else
      {
        //invalid entry
          return 0;
      }


      $sql = new Sql($this->_adapter,"satellite");
      $linsert = $sql->insert();
      $linsert->values(array(
       'name' => $name,
       'content' => $content,
       'wiki' => $wiki,
       'tle' => $tle,
       'orbit' => $orbit
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());
   }

}

?>