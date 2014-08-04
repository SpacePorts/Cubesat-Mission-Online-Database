<?php
require_once ROOT . "/Database/MissionTable.php";
require ROOT . "/HtmlFragments/HtmlIframePanelFragment.php";


class Pages {

	private $_mission;
	private $_missionTable;
	private $_satelliteFrame;

	function __construct() {

		$this->_missionTable = new MissionTable();

		$this->_mission = $this->_missionTable->GetRowById(Get("mission_id"));

		$this->_satelliteFrame = new HtmlIframePanelFragment();
	
	}
	public function HeaderContent()
	{

		?>
				<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/Iframe.js"></script>
 	<?php
	}

	public function GetPageID()
	{
		return "Mission-Single";
	}

	function Ajax($error,&$output)
	{
	
	}


	public function BodyContent()
	{
		$satellites = $this->_mission->GetSatellites();
		for($x = 0; $x < count($satellites);$x++)
		{
			$this->_satelliteFrame->AddIframe(SITE_URL . "?page-id=Cubesat-Single&single=single&sat_id=" .$satellites[$x]->GetId(),$satellites[$x]->GetName());
		}

		?>
			<a href="<?php echo SITE_URL; ?>?page-id=Mission-Modify&mission_id=<?php echo $_GET["mission_id"];?>">Modify</a>
			<h1><?php echo $this->_mission->GetName(); ?> <small> <?php echo $this->_mission->GetObjective(); ?></small></h1>

		<?php

		?><h2>Satellites</h2><?php 
		$this->_satelliteFrame->Output();

		?><h2>Content</h2><?php
		echo $this->_mission->GetContent();

	}
}
?>
