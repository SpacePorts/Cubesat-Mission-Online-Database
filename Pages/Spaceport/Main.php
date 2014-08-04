<?php
require ROOT . "/Database/SpaceportTable.php";

require ROOT . "/HtmlFragments/HtmlTableFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlSearchFragment.php";
require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";

use Zend\Db\Sql\Where;

class Pages {

	private $_htmlTableFragment;
	private $_spaceportTable;

	private $_searchForm;
	private $_search;
	private $_pagination;


	function __construct() {
		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
		$this->_spaceportTable = new SpaceportTable();

		$this->_searchForm = new HtmlFormFragment(SITE_URL,"GET","","search_form");
		$this->_search = new HtmlSearchFragment("sat_column","search",Get("search"),Get("sat_column"));
		$this->_pagination = new HtmlPaginationFragment(7,"page",SITE_URL);

		$this->_search->AddSearchOption("vendor-name","Name");
	}
		
	function HeaderContent()
	{

	}

	function GetPageId()
	{
		return "Spaceport";
	}


	function BodyContent()
	{
	?>
	<div class="center_container">

	
		<?php

			$this->_searchForm->AddFragment("search","Search:",$this->_search);
		$this->_searchForm->AddHiddenInput("page-id", $this->GetPageID());
		$this->_searchForm->AddSubmitButton("search","pull-right");
		$this->_searchForm->Output();

		$lwhere = new Where();
		if(Get("search") != "")
		$lwhere->Like("name", "%" . Get("search") . "%");

		$Spaceport = $this->_spaceportTable->Find(0,10,$lwhere);

		?> 	<a href="<?php echo PAGE_GET_URL . "-Modify"; ?>">Add Spaceport</a> <?php
		$this->_htmlTableFragment->AddHeadRow(array("Name","URL","Google Map"));
		for($x = 0; $x < count($Spaceport); $x++)
		{
			$this->_htmlTableFragment->AddBodyRow(array(
				"<a href='" . PAGE_GET_URL . "-Single&sat_id=".$Spaceport[$x]->GetId()."'>" .$Spaceport[$x]->GetName() . "</a>",
				$Spaceport[$x]->GetUrl(),
				$Spaceport[$x]->GetGoogleMapUrl()
				));

		}
		$this->_htmlTableFragment->Output();

		?>

		</div>
		<?php
	}

}
?>
