<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class CommunicationTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "communication";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Text("xmit")),
      array("column"=>new Column\Text("rcv")),
      array("column"=>new Column\Text("xcvr")));
  }


}

?>