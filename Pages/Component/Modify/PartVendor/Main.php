<?php
require_once ROOT . "/Database/PartVendorTable.php";
require_once ROOT . "/Database/PartTable.php";
require_once ROOT . "/Database/VendorTable.php";

require_once ROOT . "/HtmlFragments/HtmlFormFragment.php"
class Pages {
	private $_vendorTable;
	private $_partTable;
	private $_partVendorTable;

	private $_form;
	function __construct() {
		$this->_form = new HtmlFormFragment();


	}
		
	function HeaderContent()
	{

	}

	function Ajax($error,&$output)
	{

	}

	function BodyContent()
	{

	}

}
?>
