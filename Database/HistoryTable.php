<?php
require_once "Database.php";
require_once "HistoryRow.php";

use Zend\Db\Sql\Ddl\Column;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver;

class HistoryTable extends Table
{
  function __construct() {
     parent::__construct();
  }


  public function GetTable(){
    return "history";
  }

  public function GetColumnStructure()
  {
    $lhistorID = new Column\Integer("history_id");
    $lhistorID->setOption('auto_increment', true);

    return array(
      array("column"=>$lhistorID,"constraints"=>array("PRIMARY KEY")),
      array("column"=>new Column\DateTime("date")),
      array("column"=>new Column\Integer("user_id")),
      array("column"=>new Column\Text("table")),
      array("column"=>new Column\Integer("item_id")),
       array("column"=>new Column\Text("data")));
  }

  public function AddHistoryItem($user,$table,$itemIdName,$itemId)
  {
      $ldata;

      $ldataSql = new Sql($this->_adapter,$table);
      $lselect =  $ldataSql->select();
      $lselect->where(array($itemIdName=>$itemId));

      $lresults = $ldataSql->prepareStatementForSqlObject($lselect)->execute();

      $resultSet = new ResultSet;
      $resultSet->initialize($lresults);

       foreach ($resultSet as $row) {
        $ldata = $row;
       }

   
      $lhistorySql = new Sql($this->_adapter,"history");

      $linsert = $lhistorySql->insert();
      $linsert->values(array(
        'user_id' => $user->GetId(),
        'table' => $table,
        'item_id' => $itemId,
        'data' =>  json_encode($ldata),
        'date' => date("Y-m-d H:i:s")
      ));

      $lhistorySql->prepareStatementForSqlObject($linsert)->execute();

  }

  public function GetHistory($table,$itemId)
  {
    $lsql = new Sql($this->_adapter,"history");
    $lselect = $lsql->select();

    $lselect->where(array("table" => $table,"item_id" => $itemId));

    $lresults = $lsql->prepareStatementForSqlObject($lselect)->execute();

    $resultSet = new ResultSet;
    $resultSet->initialize($lresults);

    $lhistory = array();
    foreach ($resultSet as $row) {
        array_push($lhistory, new HistoryRow($row));
    }
    return $lhistory;
  }

  public function GetRowById($historyId)
  {
    $lsql = new Sql($this->_adapter,"history");
    $lselect = $lsql->select();

    $lselect->where(array("history_id" => $historyId));

    $lresults = $lsql->prepareStatementForSqlObject($lselect)->execute();

    $resultSet = new ResultSet;
    $resultSet->initialize($lresults);

    foreach ($resultSet as $row) {
     return new HistoryRow($row);
    }

  }


}

?>