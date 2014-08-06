<?php 
require_once "Database.php";
require_once "PartRow.php";
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class PartTable extends Table
{

   function __construct() {
       parent::__construct();
      $this->_partVendorTable = new PartVendorTable();
   }

   public function GetRowById($id)
   {
      $sqlSelect = new SqlSelect("part",$this->_db);
      $sqlSelect->Where()->EqualTo("part_id",$id);
      $stmt =  $sqlSelect->Execute();
      while ($row = $stmt->fetch()) {
        return new PartRow($row);
      }
   }

   function Find($page,$numerOfEntires,$where)
   {
      $sql = new Sql($this->_adapter,"part");
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

          array_push($satellites,new PartRow($row));
      }

      return $satellites;
   }

   function FindCount($where)
   {
      $sql = new Sql($this->_adapter,"part");
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
   public function AddPart($description,$formal_specification)
   {
     $lsqlInsert = new SqlInsert("part",$this->_db);
     $lsqlInsert->AddPair("description",$description);
     $lsqlInsert->AddPair("formal_specification",$formal_specification);
     $lsqlInsert->Execute();
     
      return $this->GetRowById($this->_db->lastInsertId());

   }

}

?>