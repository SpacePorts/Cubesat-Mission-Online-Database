<?php
require_once ROOT . "/Database/SatelliteTable.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";

require ROOT . "/HtmlFragments/HtmlDropdownFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlSearchFragment.php";
require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";

use Zend\Db\Sql\Where;
class Pages {

	private $_htmlTableFragment;
	private $_satelliteTable;

	private $_statusSelect;

	private $_searchForm;
	private $_search;
	private $_pagination;

	function __construct() {


		$this->_pagination = new HtmlPaginationFragment(7,"page",SITE_URL);

		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
		$this->_satelliteTable = new SatelliteTable();
		$this->_searchForm = new HtmlFormFragment(SITE_URL,"GET","","search_form");
		$this->_search = new HtmlSearchFragment("sat_column","search",Get("search"),Get("sat_column"));
		$this->_statusSelect = new HtmlDropdownFragment("sat_status",Get("sat_status"));	

		$this->_statusSelect->AddOption("","All");
		$this->_statusSelect->AddOption("active","active");
		$this->_statusSelect->AddOption("in-orbit","in orbit");
		$this->_statusSelect->AddOption("in-development","in development");
		$this->_statusSelect->AddOption("data-collection","data collection");
		$this->_statusSelect->AddOption("data-analysis","data analysis");
		$this->_statusSelect->AddOption("inactive","inactive");
		$this->_statusSelect->AddOption("de-orbited","de orbited");
		$this->_statusSelect->AddOption("entry-closed","entry closed");

		$this->_search->AddSearchOption("sat-tle","Tle");
		$this->_search->AddSearchOption("sat-name","Name");
		$this->_search->AddSearchOption("sat-orbit","Orbit");
	}
		
	function HeaderContent()
	{

	}

	function GetPageID()
	{
		return  "Cubesat";
	}


	function BodyContent()
	{
		$this->_searchForm->AddFragment("search","Search:",$this->_search);
		$this->_searchForm->AddFragment("sat_status","Status:",$this->_statusSelect);
		$this->_searchForm->AddHiddenInput("page-id", $this->GetPageID());
		$this->_searchForm->AddSubmitButton("search","pull-right");

		$lwhere = new Where();

		switch (Get("sat_column")) {
			case "sat-tle":
					$lwhere->Like("tle","%".Get("search")."%");
				break;
			case "sat-orbit":
					$lwhere->Like("orbit","%".Get("search")."%");
				break;
		
			default:
				$lwhere->Like("name","%".Get("search")."%");
				break;
		}
		if(Get("sat_status") != "")
		$lwhere->EqualTo("status",Get("sat_status"));

		$sat_rows = $this->_satelliteTable->Find($this->_pagination->GetPage(),20,$lwhere);
		$this->_pagination->CalculatePageMax($this->_satelliteTable->FindCount($lwhere),20);


		$this->_htmlTableFragment->AddHeadRow(array("Tle","Name","Status","Orbit"));
		for($x = 0; $x < count($sat_rows); $x++)
		{
			$this->_htmlTableFragment->AddBodyRow(array(
				"<a href='". PAGE_GET_URL ."-Single&sat_id=".$sat_rows[$x]->GetID()."'>" . $sat_rows[$x]->GetTle() . "</a>",
				"<a href='". PAGE_GET_URL ."-Single&sat_id=".$sat_rows[$x]->GetID()."'>" . $sat_rows[$x]->GetName() . "</a>",
				"<a href='". PAGE_GET_URL ."-Single&sat_id=".$sat_rows[$x]->GetID()."'>" . $sat_rows[$x]->GetStatus() . "</a>",
				"<a href='". PAGE_GET_URL ."-Single&sat_id=".$sat_rows[$x]->GetID()."'>" . $sat_rows[$x]->GetOrbit() . "</a>"
				));

		}


		?>
		<div class="center_container">
		<?php
		$this->_searchForm->Output();
		?> <a href="<?php echo PAGE_GET_URL . "-Modify"; ?>">Add Satellite</a> <?php
		$this->_htmlTableFragment->Output();
		$this->_pagination->Output();
		?>
		</div>
		<?php
	
	}

}
?>
