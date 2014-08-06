<?php
require_once ROOT . "/Database/PartTable.php";
class Pages {
	private $_partTable;
	private $_part;

	function __construct() {
		$this->_partTable = new PartTable();
		if(Get("component_id") != "")
			$this->_part = $this->_partTable->GetRowById(Get("component_id"));
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
		?>
		<h1><?php echo $this->_part->GetFormalSpecification(); ?></h1>
		<h2>Description:</h2>
		<?php echo $this->_part->GetDescription(); ?>
		<?php

	}

}
?>
