<?php 
require_once "Database.php";
require_once "PartVendorRow.php";
require_once "VendorRow.php";
require_once "PartRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;


use Zend\Db\Sql\Ddl\Column;

class PartVendorTable extends Table
{

   function __construct() {
       parent::__construct();
   }

  public function GetTable(){
   return "relation_part_vendor";
  }

  public function GetColumnStructure()
  {
    $lrelationPartId = new Column\Integer("relation_part_id");
    $lrelationPartId->setOption('auto_increment', true);
    return array(
    array("column"=>$lrelationPartId,"constraints"=>array("PRIMARY KEY")),
    array("column"=>new Column\Integer("part_id")),
   array("column"=>new Column\Integer("vendor_id")),
   array("column"=>new Column\Text("vendor_catalog_entry")),
    array("column"=>new Column\Text("vendor_model_number")));
  }

  public function GetRowById($id)
   {
      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $lselect = $sql->Select();
      $lselect->where(array("relation_part_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

  
      foreach ($resultSet as $row) {

        return new PartVendorRow($row);
      }

   }

   public function AddPartVendor($catalogEntry,$vendor,$part,$vendorModelNumber)
   {

      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $linsert = $sql->insert();
      $linsert->values(array(
       'part_id' => $part->GetId(),
       'vendor_catalog_entry' => $catalogEntry,
       'vendor_id' => $vendor->GetId(),
       'vendor_model_number' => $vendorModelNumber
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());
   }


   public function GetEntriesByPart($part)
   {

      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $lselect = $sql->Select();
      $lselect->where(array("part_id"=>$part->GetId()));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $lpartVendor = array();
      foreach ($resultSet as $row) {

          array_push($lpartVendor,new PartVendorRow($row));
      }

       return $lpartVendor;
   }



   public function GetEntriesByVendor($vendor)
   {
      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $lselect = $sql->Select();
      $lselect->where(array("vendor_id"=>$vendor->GetById()));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $lpartVendor = array();
      foreach ($resultSet as $row) {

          array_push($lpartVendor,new PartVendorRow($row));
      }

       return $lpartVendor;
   }
}

?>