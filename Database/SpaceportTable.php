<?php 
require_once "Database.php";
require_once "SpaceportRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Ddl\Column;
class SpaceportTable extends Table
{

   function __construct() {
       parent::__construct();
   }

   public function GetTable(){
    return "spaceport";
   }

   public function GetColumnStructure()
   {
    $spaceportId = new Column\Integer("spaceport_id");
    $spaceportId->setOption('auto_increment', true);
    return array(
      array("column"=>$spaceportId,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("latlong")),
      array("column"=>new Column\Text("url")),
      array("column"=>new Column\Text("description")),
      array("column"=>new Column\Text("url_googlemap")),
      array("column"=>new Column\Text("address1")),
      array("column"=>new Column\Text("address2")),
      array("column"=>new Column\Text("name")),
      array("column"=>new Column\Text("state")),
      array("column"=>new Column\Text("country")),
      array("column"=>new Column\Text("city")),
      array("column"=>new Column\Text("zip")));
   }

   public function GetRowById($id)
   {

      $sql = new Sql($this->_adapter,"spaceport");

      $lselect = $sql->Select();
      $lselect->Where(array("spaceport_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
        return new SpaceportRow($row);
      }

   }

   public function Find($page,$numerOfEntires,$where)
   {

      $sql = new Sql($this->_adapter,"spaceport");
      $lselect = $sql->Select();
      $lselect->where($where);

      if($numerOfEntires != -1)
      $lselect->limit($numerOfEntires); 

      $lselect->offset($page * $numerOfEntires); 

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $spaceports = array();
      foreach ($resultSet as $row) {

          array_push($spaceports,new SpaceportRow($row));
      }

      return $spaceports;
   }

   function FindCount($where)
   {
      $sql = new Sql($this->_adapter,"spaceport");
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

   public function AddSpaceport($name,$latlong,$url,$description,$url_googlemap,$address1,$address2,$state,$country,$city,$zip)
   {

      $sql = new Sql($this->_adapter,"spaceport");
      $linsert = $sql->insert();
      $linsert->values(array(
       'name' => $name,
       'latlong' => $latlong,
       'url' => $url,
       'description' => $description,
       'url_googlemap' => $url_googlemap,
       "address1"=>$address1,
       "address2"=>$address2,
       "state" =>$state,
       "country"=>$country,
       "city"=>$city,
       "zip"=>$zip
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());
   }


}

?>