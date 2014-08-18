<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class RelationVendorSpaceportTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "relation_vendor_part";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Integer("vendor_id")),
      array("column"=>new Column\Integer("part_id")));
  }


}

?>