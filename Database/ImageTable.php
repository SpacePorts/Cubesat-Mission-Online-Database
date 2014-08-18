<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class ImageTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "image";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Text("table")),
      array("column"=>new Column\Text("tag")),
      array("column"=>new Column\Integer("index")));
  }


}

?>