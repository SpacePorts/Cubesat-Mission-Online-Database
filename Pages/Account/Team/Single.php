<?php
require ROOT. "/Database/UserRow.php";
require_once ROOT. "/Database/TeamTable.php";

require ROOT . "/Utility/Url.php";

require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";


use Zend\Db\Sql\Where;
class Single extends PageBase {
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

	function HeaderContent($libraries)
	{

	}

	function BodyContent()
	{

	}
}



?>
