<?php
require_once "Database.php";


use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class PropertyTable extends Table
{
 function __construct() {
       parent::__construct();
   }


  public function GetTable(){
    return "property";
  }

  public function GetColumnStructure()
  {
    return array(
      array("column"=>new Column\Text("id")),
      array("column"=>new Column\Text("value")));
  }

  public function GetProperty($property)
  {
    $lsql = new Sql($this->_adapter,"property");
    $lselect = $lsql->Select();
    $lselect->where(array("id"=>$property));
    $lresults = $lsql->prepareStatementForSqlObject($lselect)->execute();
    if($lresults == null)
    {
      return "";
    }
    else
    {
      $lresultSet = new ResultSet;
      $lresultSet->initialize($lresults);

      foreach ($lresultSet as $row) {
        return $row["value"];
      }
    }
  }

  public function SetProperty($property,$value)
  {
    $lsql = new Sql($this->_adapter,"property");

      $lselect = $lsql->Select();
      $lselect->where(array("id"=>$value));

      $lresults = $lsql->prepareStatementForSqlObject($lselect)->execute();

      $lresultSet = new ResultSet;
      $lresultSet->initialize($lresults);

      $lvalueExist = false;
      foreach ($lresultSet as $row) {
         $lvalueExist = true;
      }

      if($lvalueExist == false)
      {
        $lsql = new Sql($this->_adapter,"property");
        $linsert = $lsql->Insert();
        $linsert->values(array("id"=>$property,"value"=>$value));
        $lsql->prepareStatementForSqlObject($linsert)->execute();

      }


      $lsql = new Sql($this->_adapter,"property");
      $lupdate =  $lsql->update();
      $lupdate->Where(array("id"=>$property));
      $lupdate->set(array("value"=>$value));
      $lresults = $lsql->prepareStatementForSqlObject($lupdate)->execute();

  }

}

?>