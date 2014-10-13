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

	function HeaderContent($libraries)
	{
		$this->_comparisonFragment->Header($libraries);
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

		$lcompareOneURL->AddPair("page-id","Satellite-Modify");

		$lcompareTwoURL->AddPair("page-id","Satellite-Modify");
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

		$lcompareOneURL->AddPair("sat_id",$this->_currentSatellite->GetId());
		$lcompareTwoURL->AddPair("sat_id",$this->_currentSatellite->GetId());
	?>

	<?php
		Navigation(Get("sat_id"),Get("page-id"));

		?></br><?php
		$this->_comparisonFragment->Output();

		$lcurrentURL = new Url();
		$lcurrentURL->AddPair("page-id","Satellite-History");
		$lcurrentURL->AddPair("sat_id",$this->_currentSatellite->GetId());


		?>
		<div class="row">
			<div class="col-xs-6">
				<div>
					<a href="<?php
					$lcompareOneURL->removeKey("single");
					 echo $lcompareOneURL->Output();
					 	$lcompareOneURL->AddPair("single","single");
					 	 ?>">Modify</a>


				</div>
				<iframe seamless class="read-only" src="<?php echo $lcompareOneURL->Output(); ?> "></iframe>
			</div>
			<div class="col-xs-6">
				<div>
					<a href="<?php
					$lcompareTwoURL->removeKey("single");
					 echo $lcompareTwoURL->Output();
					 	$lcompareTwoURL->AddPair("single","single");
					 	 ?>">Modify</a>

				</div>
				<iframe seamless class="read-only" src="<?php echo $lcompareTwoURL->Output(); ?>"></iframe>
			</div>
		</div>

		<form method="GET" action="<?php echo SITE_URL; ?>" >
			<h2>Compare</h2>
			<input type='submit' value='Compare'/>
			<input type="hidden" name="page-id" value="Satellite-History" />
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
