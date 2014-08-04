<?php
require_once ROOT . "/Database/PartTable.php";
class Pages {
	private $_partTable;

	function __construct() {
		$this->_partTable = new PartTable();
	}
		
	function HeaderContent()
	{

	}

	function GetPageID()
	{
		return  "Component";
	}


	function BodyContent()
	{

	}

}
?>
