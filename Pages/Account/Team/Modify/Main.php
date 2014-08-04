<?php
require ROOT . "/HtmlFragments/HtmlFormFragment.php";

use Respect\Validation\Validator as v;
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
		if(!v::string()->notEmpty()->validate(Post("name")))
			$error->AddErrorPair("name","Name Required");
		if(!v::string()->notEmpty()->validate(Post("lat_long")))
			$error->AddErrorPair("name","Lat Long Required");
		if(!$error->HasError())
		{

		}
	}


	function BodyContent()
	{
		include ROOT . "\Pages\Account\SubMenu.php"; 

		$this->_form->AddTextInput("name","name");
		$this->_form->AddTextInput("lat_long","Lat Long");
		$this->_form->AddSubmitButton("Add Team","pull-right");

		?> <div class="center_container"><?php
			$this->_form->Output();
		?></div><?php
	}
}



?>
