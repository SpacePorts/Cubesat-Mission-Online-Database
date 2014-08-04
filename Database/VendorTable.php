<?php 
require_once "Database.php";
require_once "VendorRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class VendorTable extends Table
{

   function __construct() {
       parent::__construct();
   }

   function GetRowById($id)
   {
      $lsqlSelect = new SqlSelect("vendor",$this->_db);
      $lsqlSelect->Where()->EqualTo("vendor_id",$id);

      $lresults = $lsqlSelect->Execute();

      while ($row = $lresults->fetch()) {
         return new VendorRow($row);
      }
   }

   function Find($page,$numerOfEntires,$where)
   {

      $sql = new Sql($this->_adapter,"vendor");
      $lselect = $sql->Select();
      $lselect->where($where);

      if($numerOfEntires != -1)
      $lselect->limit($numerOfEntires); 

      $lselect->offset($page * $numerOfEntires); 

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

      $vendors = array();
      foreach ($resultSet as $row) {

          array_push($vendors,new VendorRow($row));
      }

      return $vendors;
   }

   
   function FindCount($where)
   {
      $sql = new Sql($this->_adapter,"vendor");
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

   function AddVendor($name,$url,$contact_info,$type)
   {
      $lsqlInsert = new SqlInsert("vendor",$this->_db);

      $lsqlInsert->AddPair("name", $name);
      $lsqlInsert->AddPair("url", $url);
      $lsqlInsert->AddPair("contact_info", $contact_info);
      $lsqlInsert->AddPair("type", $type);

      $lsqlInsert->Execute();

      return $this->GetRowById($this->_db->lastInsertId());

   }

   
}

?>