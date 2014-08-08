<?php
require_once ROOT . "/Database/UserTable.php";

require ROOT . "/HtmlFragments/HtmlTableFragment.php";
require ROOT . "/Database/MissionTable.php";

require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlSearchFragment.php";
require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";

use Zend\Db\Sql\Where;
class Pages extends PageBase {
	private $_user;


	private $_htmlTableFragment;
	private $_missionTable;

	private $_searchForm;
	private $_search;
	private $_pagination;
	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();
		
		if(empty($_GET["mission-column"]))
			$_GET["mission-column"]="";
		if(empty($_GET["search"]))
			$_GET["search"]="";
		if(empty($_GET["sat_column"]))
			$_GET["sat_column"]="";

	 	$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
	 	$this->_missionTable = new MissionTable();

	 	$this->_searchForm = new HtmlFormFragment(SITE_URL,"GET","","search_form");
		$this->_search = new HtmlSearchFragment("mission-column","search",$_GET["search"],$_GET["sat_column"]);
		$this->_pagination = new HtmlPaginationFragment(7,"page",SITE_URL);

		$this->_search->AddSearchOption("mission-name","Name");
		$this->_search->AddSearchOption("mission-objective","Objective");
	 }

	public function GetPageID()
	{
		return "Mission";
	}

	public function HeaderContent()
	{

	

	}

	public function BodyContent()
	{
		$this->_searchForm->AddFragment("search","Search:",$this->_search);
		$this->_searchForm->AddSubmitButton("search","pull-right");
		$this->_searchForm->AddHiddenInput("page-id", $this->GetPageID());
		
		$lwhere = new Where();

		if($_GET["mission-column"] == "mission-objective")
			$lwhere->Like("objective","%".$_GET["search"]."%");
		else 
			$lwhere->Like("name","%".$_GET["search"]."%");

		$missions = $this->_missionTable->Find($this->_pagination->GetPage(),10,$lwhere);
		$this->_pagination->CalculatePageMax($this->_missionTable->FindCount($lwhere),10);

		$this->_htmlTableFragment->AddHeadRow(array("Name","Objective"));
		for($x = 0; $x < count($missions); $x++)
		{
				$this->_htmlTableFragment->AddBodyRow(array(
			"<a href='" . PAGE_GET_URL . "-Single&mission_id=".$missions[$x]->GetID()."'>" .$missions[$x]->GetName() . "</a>",
			"<a href='" . PAGE_GET_URL . "-Single&mission_id=".$missions[$x]->GetID()."'>" .$missions[$x]->GetObjective(). "</a>"
			));
		
		}

		$this->_searchForm->Output();
		?> <a href="<?php echo PAGE_GET_URL . "-Modify"; ?>">Add Mission</a> <?php
		$this->_htmlTableFragment->Output();
		$this->_pagination->Output();

	}
}
?>
