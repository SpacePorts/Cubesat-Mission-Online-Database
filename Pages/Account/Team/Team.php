<?php
require ROOT. "/Database/UserRow.php";
class Team extends PageBase {
	private $_user;

	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();

	}


	public function IsUserLegal()
	{
		if(isset($this->_user))
		{
			return true;
		}
		return false;
	}

	function HeaderContent()
	{

	}

	function BodyContent()
	{
		 include ROOT . "\Pages\Account\SubMenu.php"; 

	}
}



?>
