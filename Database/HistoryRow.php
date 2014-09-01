<?php 

require_once "Database.php";
require_once "PartTable.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class HistoryRow extends Row
{
	private $_data;
	private $_user;
	private $_table;
	private $_itemId;
	private $_historyId;
	private $_dateTime;

	public function __construct($historyData)
	{
		parent::__construct();
		$this->_data = $historyData["data"];
		$this->_user= $historyData["user_id"];
		$this->_table= $historyData["table"];
		$this->_itemId= $historyData["item_id"];
		$this->_historyId= $historyData["history_id"];
		$this->_dateTime = $historyData["date"];
	}

	public function GetId()
	{
		return $this->_historyId;
	}

	public function GetData()
	{
		return json_decode($this->_data,true);
	}

	public function GetTable()
	{
		return $this->_table;
	}

	public function GetDateTime()
	{
		return $this->_dateTime;
	}

	
}

?>
