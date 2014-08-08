<?php
require_once ROOT . "/Database/PartVendorTable.php";

class Pages {

	private $_partVendorTable;
	private $_partVendor;
	
	function __construct() {
		$this->_partVendorTable = new PartVendorTable();
		$this->_partVendor = $this->_partVendorTable->GetRowById(Get("part_vendor_id"));
	}
		
	function HeaderContent()
	{

	}

	function Ajax($error,&$output)
	{

	}

	function BodyContent()
	{

		?>
			<a href='<?php echo SITE_URL. "?page-id=Component-Modify-PartVendor&part_vendor_id=".$this->_partVendor->GetId(); ?>'>Modify</a>
			<h2><?php echo $this->_partVendor->GetVendor()->GetName();  ?></h2>
			<h1><?php echo $this->_partVendor->GetModelNumber(); ?></h1>
			<?php echo $this->_partVendor->GetCatalogEntry(); ?>

		<?php
	}

}
?>
