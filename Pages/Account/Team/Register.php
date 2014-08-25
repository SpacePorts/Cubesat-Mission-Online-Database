<?php
require ROOT. "/HtmlFragments/HtmlFormFragment.php";

require ROOT. "/Database/UserRow.php";
require ROOT . "/Utility/Url.php";

use Respect\Validation\Validator as v;

class Register extends PageBase {
	private $_user;
	private $_form;
	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);

	}


	public function IsUserLegal()
	{
		if(isset($this->_user))
		{
			if($this->_user->GetType() == UserRow::PRODUCER || $this->_user->GetType() == UserRow::ADMIN)
			return true;
		}
		return false;
	}

	function HeaderContent()
	{

	}


	function Ajax($error,&$output)
	{
		if(!v::string()->notEmpty()->validate(Post("team_name")))
			$error->AddErrorPair("team_name","Require Team Name");

		if(!$error->HasError())
		{
			
		}
		//$output["redirect"] 
	
	}

	function BodyContent()
	{

		$this->_form->AddTextInput("team_name","Name:*");
		$this->_form->AddTextInput("team_latlong","Lat Long:");
		$this->_form->AddSubmitButton("Register"); 
		$this->_form->Output();

	}
}



?>
