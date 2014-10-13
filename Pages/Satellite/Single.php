<?php
// ####################################
// 140825-DVB Add COSPAR column
// ####################################

require_once ROOT . "/Database/SatelliteTable.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";
require "Navigation.php";

class Single extends PageBase{
	private $_satelliteTable;

	function __construct() {
      $this->_satelliteTable = new SatelliteTable();
	}

	function HeaderContent($libraries)
	{

	}

	function GetPageID()
	{
		return  "Satellite-Single";
	}


	function BodyContent()
	{
		$satellite = $this->_satelliteTable->GetRowById($_GET["sat_id"]);
		Navigation(Get("sat_id"),Get("page-id"));
		?>

		<?php  if(Get("single") != "single") : ?>

		<?php endif; ?>
		<h1><?php echo $satellite->GetName();   ?> <small> <?php echo $satellite->GetStatus(); ?></small></h1>

		<p><font size="+1">COSPAR: </font> <?php echo $satellite->GetCOSPAR(); ?> </p>


		<h2>Parts</h2>

		<ul>
		<?php
		$lparts = $satellite->GetParts();
		for($x =0;$x < count($lparts);$x++): ?>
		<li><?php echo $lparts[$x]->GetFormalSpecification();?></li>
		<?php endfor; ?>
		</ul>

		<h2>Description</h2>

		<?php echo $satellite->GetContent();

	}

}
?>
