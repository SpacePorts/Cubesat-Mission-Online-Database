<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class InstrumentationTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "instrumentation";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Text("cameras")),
      array("column"=>new Column\Text("telescopes")));
  }


}

?>