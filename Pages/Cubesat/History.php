<?php

require ROOT . "/Database/SatelliteTable.php";
require_once ROOT . "/Database/PartTable.php";

require ROOT . "/HtmlFragments/HtmlIframePanelFormListFormFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlDropdownFragment.php";
require ROOT . "/HtmlFragments/HtmlComparisonFragment.php";

require_once ROOT . "/Database/UserTable.php";

require ROOT . "/Database/HistoryTable.php";

require ROOT . "/Utility/Url.php";
require "Navigation.php";

use Respect\Validation\Validator as v;
use Zend\Db\Sql\Where;

class History extends PageBase {
	private $_satTable;
	private $_historyTable;
	private $_comparisonFragment;

	private $_satelliteHistory;

	private $_currentSatellite;


	
	function __construct() {
		$this->_comparisonFragment = new HtmlComparisonFragment();
		$this->_historyTable = new HistoryTable();
		$this->_satTable = new SatelliteTable();
		$this->_currentSatellite = $this->_satTable->GetRowById(Get("sat_id"));
		$this->_satelliteHistory = $this->_historyTable->GetHistory($this->_satTable->GetTable(),$this->_currentSatellite->GetId());


	}
		
	function HeaderContent()
	{
		?>
			<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/Iframe.js"></script>
		<?php


	}

	function GetPageID()
	{
		
	}

	public function IsUserLegal()
	{
		return true;
		
	}


	function Ajax($error,&$output)
	{
	
		

	}

	function BodyContent()
	{
		$lcompareOneURL = new Url();
		$lcompareTwoURL = new Url();

		$lcompareOneURL->AddPair("page-id","Cubesat-Modify");
		$lcompareOneURL->AddPair("single","single");

		$lcompareTwoURL->AddPair("page-id","Cubesat-Modify");
		$lcompareTwoURL->AddPair("single","single");
		


		if(Get("compare1") == ""  || Get("compare1") == "current")
			$lcompareOneURL->AddPair("sat_id",$this->_currentSatellite->GetId());
		else
			$lcompareOneURL->AddPair("history-id",Get("compare1"));

		if(Get("compare2") == "")
			$lcompareTwoURL->AddPair("history-id",$this->_satelliteHistory[0]->GetId());
		else if(Get("compare2") == "current" )
			$lcompareTwoURL->AddPair("sat_id",$this->_currentSatellite->GetId());
		else
			$lcompareTwoURL->AddPair("history-id",Get("compare2"));
	?>

	<?php
		/*$this->_comparisonFragment->AddComparisonPair("Name",$this->_compareOneSatellite->GetName(),$this->_compareTwoSatellite->GetName());
		$this->_comparisonFragment->AddComparisonPair("Content",$this->_compareOneSatellite->GetContent(),$this->_compareTwoSatellite->GetContent());
		$this->_comparisonFragment->AddComparisonPair("COSPAR",$this->_compareOneSatellite->GetCOSPAR(),$this->_compareTwoSatellite->GetCOSPAR());
		$this->_comparisonFragment->AddComparisonPair("Wiki",$this->_compareOneSatellite->GetWiki(),$this->_compareTwoSatellite->GetWiki());
		$this->_comparisonFragment->AddComparisonPair("Status",$this->_compareOneSatellite->GetStatus(),$this->_compareTwoSatellite->GetStatus());
		$this->_comparisonFragment->AddComparisonPair("TLE",$this->_compareOneSatellite->GetTle(),$this->_compareTwoSatellite->GetTle());
		$this->_comparisonFragment->AddComparisonPair("Orbit",$this->_compareOneSatellite->GetOrbit(),$this->_compareTwoSatellite->GetOrbit());
		*/
		Navigation(Get("sat_id"),Get("page-id"));

		?></br><?php
		$this->_comparisonFragment->Output();

		$lcurrentURL = new Url();
		$lcurrentURL->AddPair("page-id","Cubesat-History");
		$lcurrentURL->AddPair("sat_id",$this->_currentSatellite->GetId());


		?>
		<div class="row">
			<div class="col-xs-6">
				<iframe seamless class="read-only" src="<?php echo $lcompareOneURL->Output(); ?>"></iframe>
			</div>
			<div class="col-xs-6">
			<iframe seamless class="read-only" src="<?php echo $lcompareTwoURL->Output(); ?>"></iframe>
			</div>
		</div>

		<form method="GET" action="<?php echo SITE_URL; ?>" >
			<h2>Compare</h2>
			<input type='submit' value='Compare'/>
			<input type="hidden" name="page-id" value="Cubesat-History" />
			<input type="hidden" name="sat_id" value="<?php echo $this->_currentSatellite->GetId(); ?>" />

			<ul class="list-group">

				<?php

				?>  <li class="list-group-item"><input type="radio" name="compare1" value="current" <?php if(Get("compare1") == "current" || Get("compare1") == "") echo "checked"; ?>><input type="radio" name="compare2" value="current" <?php if(Get("compare2") == "current" || Get("compare2") == "") echo "checked"; ?>>current<?php
				
				for($x = 0; $x < count($this->_satelliteHistory);$x++)
				{
				?>
				<li class="list-group-item"><input type="radio" name="compare1" value="<?php echo  $this->_satelliteHistory[$x]->GetId() ?>" <?php if(Get("compare1") == $this->_satelliteHistory[$x]->GetId()) echo "checked"; ?>><input type="radio" name="compare2" value="<?php echo  $this->_satelliteHistory[$x]->GetId() ?>" <?php if($x == 0 &&  Get("compare2") == "" || Get("compare2") == $this->_satelliteHistory[$x]->GetId()) echo "checked"; ?>> <?php echo $this->_satelliteHistory[$x]->GetDateTime();?></li>
				<?php
				}
				?>

			</ul>
		</form>

		<?php
	}

}
?>
