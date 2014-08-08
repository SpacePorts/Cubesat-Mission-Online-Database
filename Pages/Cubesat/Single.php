<?php
require_once ROOT . "/Database/SatelliteTable.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";
class Single extends PageBase{
	private $_satelliteTable;

	function __construct() {
      $this->_satelliteTable = new SatelliteTable();
	}
		
	function HeaderContent()
	{

	}

	function GetPageID()
	{
		return  "CubesatSingle";
	}


	function BodyContent()
	{

		$satellite = $this->_satelliteTable->GetRowById($_GET["sat_id"]); ?>



			<?php  if(Get("single") != "single") : ?>
			<a href="<?php echo SITE_URL; ?>?page-id=Cubesat-Modify&sat_id=<?php echo $_GET["sat_id"];?>">Modify</a>
			<?php endif; ?>
			<h1><?php echo $satellite->GetName(); ?> <small> <?php echo $satellite->GetStatus(); ?></small></h1>

			
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
