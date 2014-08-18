<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class RelationSatellitePartTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "relation_satellite_part";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Integer("sat_id")),
      array("column"=>new Column\Integer("part_id")));
  }


}

?>