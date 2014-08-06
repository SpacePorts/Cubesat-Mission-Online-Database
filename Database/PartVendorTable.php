<?php 
require_once "Database.php";
require_once "PartVendorRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class PartVendorTable extends Table
{

   function __construct() {
       parent::__construct();
   }

  public function GetRowById($id)
   {
      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $lselect = $sql->Select();
      $lselect->where(array("relation_part_id"=>$partId));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

  
      foreach ($resultSet as $row) {

        return new PartVendorRow($row);
      }

   }

   public function AddPartVendor($catalogEntry,$venodrId,$PartId,$vendorModelNumber)
   {

      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $linsert = $sql->insert();
      $linsert->values(array(
       'part_id' => $PartId,
       'vendor_catalog_entry' => $catalogEntry,
       'vendor_id' => $venodrId,
       'vendor_model_number' => $vendorModelNumber
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());
   }


   public function GetEntriesByPartId($partId)
   {

      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $lselect = $sql->Select();
      $lselect->where(array("part_id"=>$partId));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $lpartVendor = array();
      foreach ($resultSet as $row) {

          array_push($lpartVendor,new PartVendorRow($row));
      }

       return $lpartVendor;
   }



   public function GetEntriesByVendorId($venodrId)
   {
      $sql = new Sql($this->_adapter,"relation_part_vendor");
      $lselect = $sql->Select();
      $lselect->where(array("vendor_id"=>$venodrId));

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