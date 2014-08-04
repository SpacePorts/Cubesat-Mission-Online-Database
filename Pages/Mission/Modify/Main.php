<?php
require_once ROOT . "/Database/MissionTable.php";
require_once ROOT . "/Database/SatelliteTable.php";
require_once ROOT . "/Database/UserTable.php";

require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlIframePanelFormListFormFragment.php";

use Respect\Validation\Validator as v;
use Zend\Db\Sql\Where;

class Pages {

	private $_user;

	private $_satelliteTable;
	private $_mission;
	private $_missionTable;
	private $_form;
	private $_satelliteFrame;

	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();

		$this->_missionTable = new MissionTable();
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_satelliteFrame = new HtmlIframePanelFormListFormFragment("sat_id","sat_search","sat_name",PAGE_GET_AJAX_URL,SITE_URL . "?page-id=Cubesat-Modify&single=single");
		$this->_satelliteTable = new SatelliteTable();
		if(isset($_REQUEST["mission_id"]))
		{
			$this->_mission = $this->_missionTable->GetRowById($_REQUEST["mission_id"]);
		}

	}
	public function HeaderContent()
	{
		?>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/Iframe.js"></script>
 	
		<?php
	}

	public function GetPageID()
	{
		return "Mission-Modify";
	}


	function Ajax($error,&$output)
	{
		if($this->_user->GetType() == UserRow::PRODUCER)
		{
			switch (Post("type")) {
				case 'mission-form':

					if(!v::string()->notEmpty()->validate(Post("mission_name")))
						$error->AddErrorPair("mission_name","Require Mission Name");

					if(!v::string()->notEmpty()->validate(Post("mission_objective")))
						$error->AddErrorPair("mission_objective","Require Mission Objective");

					if(!v::string()->notEmpty()->validate(Post("mission_content")))
						$error->AddErrorPair("mission_content","Require Content");

					$lsatellites =   array();
					if(Post("sat_id") != "")
						$lsatellites = array_unique(Post("sat_id"));

					for($x = 0; $x < count($lsatellites);$x++)
					{
						$lsatellites[$x] = $this->_satelliteTable->GetRowById($lsatellites[$x]);
					}

					if(!$error->HasError())
					{
						if(isset($this->_mission))
						{
							$this->_mission->SetName(Post("mission_name"));
							$this->_mission->SetObjective(Post("mission_objective"));
							$this->_mission->SetWiki(Post("mission_wiki"));
							$this->_mission->SetContent(Post("mission_content"));
							$this->_mission->Update();
						}
						else
						{
							$this->_mission = $this->_missionTable->AddMission(Post("mission_name"),Post("mission_objective"),Post("mission_wiki"),Post("mission_content"));
							$output["redirect"] = SITE_URL . "?page-id=Mission-Modify&mission_id=".$this->_mission->GetId();
						}

						$this->_mission->AddSatellites($lsatellites);
					}
				break;
				case "sat_search":
					$lwhere = new Where();
					$lwhere->Like("name","%".Post("search")."%");
					$satellites = $this->_satelliteTable->find(0,10,$lwhere);
					$searchData = array();
					for($x = 0; $x < count($satellites);$x++)
					{
						array_push($searchData, array("action"=> SITE_URL . "?page-id=Cubesat-Modify&sat_id=".$satellites[$x]->GetId()."&single=single","name"=> $satellites[$x]->GetName()));
					}
					$output["search"] = $searchData;
				break;
				
				default:
					# code...
					break;
			}
		}

	}


	public function BodyContent()
	{

		$this->_form->AddHiddenInput("type","mission-form");
		if(isset($this->_mission))
		{
			$satellites = $this->_mission->GetSatellites();
			for($x =0; $x < count($satellites);$x++)
			{
				$this->_satelliteFrame->AddIFrame(SITE_URL . "?page-id=Cubesat-Modify&sat_id=".$satellites[$x]->GetId()."&single=single");
			}


			$this->_form->AddHiddenInput("mission_id",$this->_mission->GetId());
			$this->_form->AddTextInput("mission_name","Name:*",$this->_mission->GetName());
			$this->_form->AddTextInput("mission_objective","Objective:*",$this->_mission->GetObjective());
			$this->_form->AddTextarea("mission_content","Content:*",$this->_mission->GetContent());
			$this->_form->AddTextInput("mission_wiki","Wiki:",$this->_mission->GetWiki());
			$this->_form->AddFragment("satellites","Satellite",$this->_satelliteFrame);
			$this->_form->AddSubmitButton("Modify Mission","pull-right");
		}
		else
		{
			$this->_satelliteFrame->AddIFrame(SITE_URL . "?page-id=Cubesat-Modify&single=single","sat_id","sat_search");

			$this->_form->AddTextInput("mission_name","Name:*");
			$this->_form->AddTextInput("mission_objective","Objective:*");
			$this->_form->AddTextarea("mission_content","Content:*");
			$this->_form->AddTextInput("mission_wiki","Wiki:");
			$this->_form->AddFragment("satellites","Satellite",$this->_satelliteFrame);
			$this->_form->AddSubmitButton("Add Mission","pull-right");
		}
	
		$this->_form->Output();

	}
}
?>
