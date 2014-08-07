<?php
require_once ROOT . "/Database/PartTable.php";



require ROOT . "/HtmlFragments/HtmlTableFragment.php";
class Pages {
	private $_partTable;
	private $_part;
	private $_htmlTableFragment;

	function __construct() {
		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
		
		$this->_partTable = new PartTable();
		if(Get("component_id") != "")
			$this->_part = $this->_partTable->GetRowById(Get("component_id"));
	}
		
	function HeaderContent()
	{

	}

	function GetPageID()
	{
		return  "Component-Single";
	}


	function BodyContent()
	{
		$partVendor = $this->_part->GetPartVendor();
		$this->_htmlTableFragment->AddHeadRow(array("Vendor","Model Number"));
		for($x = 0; $x < count($partVendor); $x++)
		{
			$this->_htmlTableFragment->AddBodyRow(array(
				"<a href='" . SITE_URL . "page-id=Component-Modify-PartVendor&part_vendor_id=".$partVendor[$x]->GetId()."'>" .$partVendor[$x]->GetVendor() . "</a>",
				"<a href='" . SITE_URL . "page-id=Component-Modify-PartVendor&part_vendor_id=".$partVendor[$x]->GetId()."'>" .$partVendor[$x]->GetModelNumber() . "</a>"
			));

		}

		?>
		<h1><?php echo $this->_part->GetFormalSpecification(); ?></h1>
		<h2>Description:</h2>
		<?php echo $this->_part->GetDescription(); ?>
		<h2>Models:</h2>
		<a href="<?php echo SITE_URL . "?page-id=Component-Modify-PartVendor"; ?>">Add Model</a>
		<?php $this->_htmlTableFragment->Output(); ?>


		<?php

	}

}
?>
