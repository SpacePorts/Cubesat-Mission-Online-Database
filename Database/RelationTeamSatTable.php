<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class RelationTeamSatTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "relation_teams_sats";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Integer("team_id")),
      array("column"=>new Column\Integer("sat_id")));
  }


}

?>