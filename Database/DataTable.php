<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class DataTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "data";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Float("radio_frequency",12,4)),
      array("column"=>new Column\Float("bandwidth",12,4)),
      array("column"=>new Column\Text("format")),
       array("column"=>new Column\Text("description")),
        array("column"=>new Column\Text("access")));
  }


}

?>