<?php
require ROOT. "/Database/UserRow.php";
require ROOT . "/Utility/Url.php";
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
		 
		 $lAddUrl = new Url();
		 $lAddUrl->AddPair("page-id","Account-Team-Register");
		 ?>
		 <h3>Teams</h3>
		 <a href="<?php echo  $lAddUrl->Output(); ?>">Add Team</a>
		 <?php

	}
}



?>
