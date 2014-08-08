<?php
require ROOT . "/Database/VendorTable.php";

class Pages extends PageBase {
	private $_vendorTable;

	function __construct() {
		$this->_vendorTable = new VendorTable();
	}
	
	function HeaderContent()
	{

	}


	function BodyContent()
	{
		$vendorRow = $this->_vendorTable->GetRowById($_GET["vendor-id"]); ?>

		<div class="center_container">
			<a href="<?php echo SITE_URL; ?>?page-id=Vendor-Modify&vendor-id=<?php echo $vendorRow->GetId();?>">Modify</a>
			
			<h1><?php echo $vendorRow->GetName(); ?> :  <?php echo $vendorRow->GetType(); ?></h1>
			
			<h2>Parts</h2>

			<ul>
				<li>part1</li>
				<li>part2</li>
				<li>part3</li>
				<li>part4</li>
				<li>part5</li>
			</ul>


		</div>
		<?php


	}
}
?>
