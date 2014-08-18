<?php 
require_once "Database.php";
require_once "PartRow.php";
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;


use Zend\Db\Sql\Ddl\Column;

class PartTable extends Table
{

   function __construct() {
       parent::__construct();
      $this->_partVendorTable = new PartVendorTable();
   }

   public function GetTable(){
    return "part";
   }

   public function GetColumnStructure()
   {
    $lpartId = new Column\Integer("part_id");
    $lpartId->setOption('auto_increment', true);
    return array(
      array("column"=>$lpartId,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("description")),
      array("column"=>new Column\Text("formal_specification")));
   }

   public function GetRowById($id)
   {

      $sql = new Sql($this->_adapter,"part");

      $lselect = $sql->Select();
      $lselect->Where(array("part_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      foreach ($resultSet as $row) {
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
      $sql = new Sql($this->_adapter,"part");
      $linsert = $sql->insert();
      $linsert->values(array(
         'description' => $description,
         'formal_specification' => $formal_specification
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());

   }

}

?>