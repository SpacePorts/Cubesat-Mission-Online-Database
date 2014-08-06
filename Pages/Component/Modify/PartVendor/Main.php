<?php
require ROOT . "/HtmlFragments/HtmlFormFragment.php";

//Get()
class Pages {
	private $_form;
	function __construct() {
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);

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
