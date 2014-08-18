<?php 
require_once "Database.php";
require_once "VendorRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Ddl\Column;
class VendorTable extends Table
{

   function __construct() {
       parent::__construct();
   }

  public function GetTable(){
    return "vendor";
   }

   public function GetColumnStructure()
   {
    $vendorId = new Column\Integer("vendor_id");
    $vendorId->setOption('auto_increment', true);
    return array(
      array("column"=>$vendorId,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\Text("name")),
      array("column"=>new Column\Text("url")),
      array("column"=>new Column\Text("contact_info")),
      array("column"=>new Column\Text("type")));
   }

   function GetRowById($id)
   {
      $sql = new Sql($this->_adapter,"vendor");
      $lselect = $sql->Select();
      $lselect->where(array("vendor_id"=>$id));

      $lresults = $sql->prepareStatementForSqlObject($lselect)->execute();
      
      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

  
      foreach ($resultSet as $row) {

        return new VendorRow($row);
      }

   }

   function Find($page,$numerOfEntires,$where)
   {

      $sql = new Sql($this->_adapter,"vendor");
      $lselect = $sql->Select();
      $lselect->where($where);

      if($numerOfEntires != -1)
      {
         $lselect->limit($numerOfEntires); 
         $lselect->offset($page * $numerOfEntires); 
      }


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

      $sql = new Sql($this->_adapter,"vendor");
      $linsert = $sql->insert();
      $linsert->values(array(
       'name' => $name,
       'url' => $url,
       'contact_info' => $contact_info,
       'type' => $type
      ));

      $lresults =  $sql->prepareStatementForSqlObject($linsert)->execute();

      return $this->GetRowById($this->_adapter->getDriver()->getLastGeneratedValue());

   }

   
}

?>