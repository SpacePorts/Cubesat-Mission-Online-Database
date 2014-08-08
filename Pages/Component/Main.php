<?php
require_once ROOT . "/Database/PartTable.php";

require ROOT . "/HtmlFragments/HtmlTableFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlSearchFragment.php";
require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";

use Zend\Db\Sql\Where;

class Pages extends PageBase {
	private $_partTable;
	private $_htmlTableFragment;

	private $_searchForm;
	private $_search;
	private $_pagination;

	function __construct() {
		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
		$this->_partTable = new PartTable();


		$this->_searchForm = new HtmlFormFragment(SITE_URL,"GET","","search_form");
		$this->_search = new HtmlSearchFragment("sat_column","search",Get("search"),Get("sat_column"));
		$this->_pagination = new HtmlPaginationFragment(7,"page",SITE_URL);

		$this->_search->AddSearchOption("component-formal","Formal Specification");
	}
		
	function HeaderContent()
	{

	}

	function GetPageID()
	{
		return  "Component";
	}


	function BodyContent()
	{

		$this->_searchForm->AddFragment("search","Search:",$this->_search);
		$this->_searchForm->AddHiddenInput("page-id", $this->GetPageID());
		$this->_searchForm->AddSubmitButton("search","pull-right");
		
		$lwhere = new Where();

		if(Get('search') != "")
			$lwhere->Like("formal_specification","%".Get("search")."%");

		$lpartEntries = $this->_partTable->Find(0,10,$lwhere);

		$this->_htmlTableFragment->AddHeadRow(array("Formal Specification"));
		for($x = 0; $x < count($lpartEntries); $x++)
		{
			$this->_htmlTableFragment->AddBodyRow(array(
				"<a href='" . PAGE_GET_URL . "-Single&component_id=".$lpartEntries[$x]->GetID()."'>" .$lpartEntries[$x]->GetFormalSpecification() . "</a>"
			));

		}


		$this->_searchForm->Output();
		?> <a href="<?php echo PAGE_GET_URL . "-Modify"; ?>">Add Component</a> <?php
		$this->_htmlTableFragment->Output();

	}

}
?>
