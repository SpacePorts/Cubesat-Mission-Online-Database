<?php
require ROOT . "/Database/VendorTable.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";

require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlSearchFragment.php";
require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";


use Zend\Db\Sql\Where;

class Vendor  extends PageBase{
	private $_htmlTableFragment;
	private $_vendorTable;

	private $_searchForm;
	private $_search;
	private $_pagination;

	function __construct() {
		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
		$this->_vendorTable = new VendorTable();

		$this->_searchForm = new HtmlFormFragment(SITE_URL,"GET","","search_form");
		$this->_search = new HtmlSearchFragment("sat_column","search",Get("search"),Get("sat_column"));
		$this->_pagination = new HtmlPaginationFragment(7,"page",SITE_URL);

		$this->_search->AddSearchOption("vendor-name","Name");
		$this->_search->AddSearchOption("vendor-type","Type");
	}

	function HeaderContent($libraries)
	{

	}

	function GetPageId()
	{
		return "Vendor";
	}


	function BodyContent()
	{
	if(!isset($_GET["search"]))
		$_GET["search"] ="";
	?>


	<div class="center_container">

		<?php
		$this->_searchForm->AddFragment("search","Search:",$this->_search);
		$this->_searchForm->AddHiddenInput("page-id", $this->GetPageID());
		$this->_searchForm->AddSubmitButton("search","pull-right");
		$this->_searchForm->Output();

		$lwhere = new Where();
		switch (Get("sat_column")) {
			case "vendor-type":
					$lwhere->Like("type","%".Get("search")."%");
				break;
			default:
					$lwhere->Like("name","%".Get("search")."%");
				break;
		}
		$lvendorRow = $this->_vendorTable->Find(0,10,$lwhere);
		$this->_pagination->CalculatePageMax($this->_vendorTable->FindCount($lwhere),10);

		?>
		<a href="<?php echo PAGE_GET_URL . "-Modify"; ?>">Add Vendor</a>
		<?php

		$this->_htmlTableFragment->AddHeadRow(array("Name","Type"));
		for($x = 0; $x < count($lvendorRow); $x++)
		{
			$this->_htmlTableFragment->AddBodyRow(array(
				"<a href='".PAGE_GET_URL."-Single&vendor-id=". $lvendorRow[$x]->GetID()."'>".$lvendorRow[$x]->GetName()."</a>",
				"<a href='".PAGE_GET_URL."-Single&vendor-id=". $lvendorRow[$x]->GetID()."'>".$lvendorRow[$x]->GetType()."</a>"
				));

		}
		$this->_htmlTableFragment->Output();
		$this->_pagination->Output();
		?>

		</div>
		<?php

	}
}
?>
