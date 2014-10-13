<?php
require ROOT . "/Database/VendorTable.php";

class Single extends PageBase {
	private $_vendorTable;
	private $_vendorRow;

	function __construct() {
		$this->_vendorTable = new VendorTable();
		$this->_vendorRow = $this->_vendorTable->GetRowById($_GET["vendor-id"]);
	}

	function HeaderContent($libraries)
	{

	}


	function BodyContent()
	{
	 ?>

		<div class="center_container">
			<a href="<?php echo SITE_URL; ?>?page-id=Vendor-Modify&vendor-id=<?php echo $this->_vendorRow->GetId();?>">Modify</a>

			<h1><?php echo $this->_vendorRow->GetName(); ?> :  <?php echo $this->_vendorRow->GetType(); ?></h1>
			<h2>Type</h2>
			<?php echo $this->_vendorRow->GetType(); ?>
			<h2>Contact Info</h2>
			<?php echo $this->_vendorRow->GetContactInfo(); ?>
		</div>
		<?php


	}
}
?>
