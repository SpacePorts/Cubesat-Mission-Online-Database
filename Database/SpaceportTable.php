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
		$lsqlSelect = new SqlSelect("spaceport",$this->_db);
		$lsqlSelect->Where()->EqualTo("spaceport_id",$id);
		$results =  $lsqlSelect->Execute();
		while ($row = $results->fetch()) {
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
  	 	$lsqlInsert = new SqlInsert("spaceport",$this->_db);
   	 	$lsqlInsert->AddPair("name",$name);
   	 	$lsqlInsert->AddPair("latlong",$latlong);
   	 	$lsqlInsert->AddPair("url",$url);
   	 	$lsqlInsert->AddPair("description",$description);
   	 	$lsqlInsert->AddPair("url_googlemap",$url_googlemap);
   	 	
   	 	$lsqlInsert->Execute();

     	return $this->GetRowById($this->_db->lastInsertId());
   }


}

?>