<?php 
require_once "Database.php";
require_once "SpaceportRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class SpaceportTable extends Table
{

   function __construct() {
       parent::__construct();
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

   public function AddSpaceport($name,$latlong,$url,$description,$url_googlemap)
   {

      $sql = new Sql($this->_adapter,"spaceport");
      $linsert = $sql->insert();
      $linsert->values(array(
       'name' => $name,
       'latlong' => $latlong,
       'url' => $url,
       'description' => $description,
       'url_googlemap' => $url_googlemap
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());
   }


}

?>