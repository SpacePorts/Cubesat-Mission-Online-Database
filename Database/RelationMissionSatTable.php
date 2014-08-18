<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class RelationMissionSatTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "relation_mission_sat";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Integer("mission_id")),
      array("column"=>new Column\Integer("sat_id")));
  }


}

?>