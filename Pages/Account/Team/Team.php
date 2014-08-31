<?php
require ROOT. "/Database/UserRow.php";
require_once ROOT. "/Database/TeamTable.php";

require ROOT . "/Utility/Url.php";

require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";


use Zend\Db\Sql\Where;
class Team extends PageBase {
	private $_user;
	private $_teamTable;
	private $_htmlTableFragment;

	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();
		$this->_teamTable = new TeamTable();

		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
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
		 $lAddUrl->AddPair("page-id","Account-Team-Modify");
		 ?>
		 <h3>Teams</h3>
		 <a href="<?php echo  $lAddUrl->Output(); ?>">Add Team</a>
		 <?php

		 $lwhere = new Where();
		 $teams = $this->_teamTable->find(0,-1,$lwhere,$this->_user);

		 $this->_htmlTableFragment->AddHeadRow(array("name","lat long"));

		 for($x = 0; $x < count($teams);$x++)
		 {
		 		$lteamSingle = new Url();
		 		$lteamSingle->AddPair("page-id","Account-Team-Single");
		 		$lteamSingle->AddPair("team-id", $teams[$x]->GetId());

		 		$this->_htmlTableFragment->AddBodyRow(array(
				"<a href='". $lteamSingle->Output()."'>".$teams[$x]->GetName() . "</a>",
				"<a href='". $lteamSingle->Output()."'>".$teams[$x]->GetLatLong()  . "</a>"));
		 	
		 }
		   $this->_htmlTableFragment->Output();
	}
}



?>
