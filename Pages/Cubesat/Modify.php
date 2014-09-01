<?php
// ####################################
// 140825-DVB Add COSPAR column
// ####################################
require ROOT . "/Database/SatelliteTable.php";
require_once ROOT . "/Database/PartTable.php";

require ROOT . "/HtmlFragments/HtmlIframePanelFormListFormFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlDropdownFragment.php";

require_once ROOT . "/Database/UserTable.php";

require ROOT . "/Database/HistoryTable.php";

require ROOT . "/Utility/Url.php";

use Respect\Validation\Validator as v;
use Zend\Db\Sql\Where;

class Modify extends PageBase {
	private $_satTable;
	private $_satellite;

	private $_partTable;

	private $_PartSelection;
	private $_form;
	private $_statusSelect;

	private $_historyTable;
	
	function __construct() {
		$this->_partTable = new PartTable();
		$this->_satTable = new SatelliteTable();
		$this->_user = UserRow::RetrieveFromSession();
		$this->_historyTable = new HistoryTable();

		if(isset($_REQUEST["sat_id"]))
		{
			$this->_satellite = $this->_satTable->GetRowById($_REQUEST["sat_id"]);
		}
		else if(Get("history-id") != "")
		{

			$this->_satellite = new SatelliteRow($this->_historyTable->GetRowById(Get("history-id"))->GetData());
		}


		if(isset($this->_satellite))
			$this->_statusSelect = new HtmlDropdownFragment("sat_status",$this->_satellite->GetStatus());		
		else
			$this->_statusSelect = new HtmlDropdownFragment("sat_status");		

		$this->_PartSelection = new HtmlIframePanelFormListFormFragment("component_id","component_search","component_formal_specification",PAGE_GET_AJAX_URL,SITE_URL . "?page-id=Component-Modify&single=single");

		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_statusSelect->AddOption("NULL","--Select Status--");
		$this->_statusSelect->AddOption("active","active");
		$this->_statusSelect->AddOption("in-orbit","in orbit");
		$this->_statusSelect->AddOption("in-development","in development");
		$this->_statusSelect->AddOption("data-collection","data collection");
		$this->_statusSelect->AddOption("data-analysis","data analysis");
		$this->_statusSelect->AddOption("inactive","inactive");
		$this->_statusSelect->AddOption("de-orbited","de orbited");
		$this->_statusSelect->AddOption("entry-closed","entry closed");

	}
		
	function HeaderContent()
	{
		?>
			<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/Iframe.js"></script>
		<?php

	}

	function GetPageID()
	{
		return  "Cubesat-Modify";
	}

	public function IsUserLegal()
	{
		if(isset($this->_user))
		{
			if($this->_user->GetType() == UserRow::PRODUCER || $this->_user->GetType() == UserRow::ADMIN)
			{
				return true;
			}
		}
		return false;
	}


	function Ajax($error,&$output)
	{
	
			switch (Post("type"))
			{
				case 'sat_form':
						if(Post("sat_name") == "")
							$error->AddErrorPair("sat_name","name required");

						if(Post("sat_status") == "")
							$error->AddErrorPair("sat_status","Status required");
						if(!($this->_statusSelect->IsOptionValid(Post("sat_status"))))
							$error->AddErrorPair("sat_status","Please select a Status");
						
						if(Post("sat_tle") == "")
							$error->AddErrorPair("sat_tle","TLE required");

						if(Post("sat_orbit") == "")
							$error->AddErrorPair("sat_orbit","Orbit required");

						if(Post("component_id") == "")
							$error->AddErrorPair("sat_parts","Satellite Parts required");

						if(!$error->HasError())
						{
							if(isset($this->_satellite))
							{
								$this->_satellite->SetName(Post("sat_name"));
								$this->_satellite->SetContent(Post("sat_content"));
								$this->_satellite->SetCOSPAR(Post("sat_COSPAR"));
								$this->_satellite->SetWiki(Post("sat_wiki"));
								$this->_satellite->SetStatus(Post("sat_status"));
								$this->_satellite->SetTle(Post("sat_tle"));
								$this->_satellite->SetOrbit(Post("sat_orbit"));
								$this->_satellite->Update();
							}
							else
							{
								$this->_satellite = $this->_satTable->AddSatellite(Post("sat_name"),Post("sat_content"),Post("sat_COSPAR"),Post("sat_tle"),Post("sat_orbit"),Post("sat_wiki"),Post("sat_status"));
								
							}
							$this->_historyTable->AddHistoryItem($this->_user,$this->_satTable->GetTable(),"sat_id",$this->_satellite->GetId());

							$lcomponents =   array();
							if(Post("component_id") != "")
								$lcomponents = array_unique(Post("component_id"));
								
			
							for($x = 0; $x < count($lcomponents);$x++)
							{
								$lcomponents[$x] = $this->_partTable->GetRowById($lcomponents[$x]);
							}

							//NEEDS REWORKING REALLY SLOW
							$this->_satellite->AddParts($lcomponents);

							if(Post("single") == "single")
								$output["redirect"] = SITE_URL . "?page-id=Cubesat-Modify&sat_id=".$this->_satellite->GetId() . "&single=single";
							else
								$output["redirect"] = SITE_URL . "?page-id=Cubesat-Modify&sat_id=".$this->_satellite->GetId();
							
						}
					
				break;
				
				case "component_search":

					$lwhere = new Where();
					$lwhere->Like("formal_specification","%".Post("search")."%");
					$parts = $this->_partTable->find(0,10,$lwhere);

					for($x = 0; $x < count($parts);$x++)
					{
						$this->_PartSelection->addSearchPair( $parts[$x]->GetFormalSpecification(),SITE_URL . "?page-id=Component-Modify&single=single&component_id=" .$parts[$x]->GetId());
					}
					$this->_PartSelection->Ajax($output);

				break;
			}

	}

	function BodyContent()
	{
		if(Get("single"))
			$this->_form->AddHiddenInput("single","single");
		

		if(isset($this->_satellite))
		{
			$parts = $this->_satellite->GetParts();
			for($x =0; $x < count($parts);$x++)
			{
				
				$this->_PartSelection->AddIFrame(SITE_URL . "?page-id=Component-Modify&single=single&component_id=".$parts[$x]->GetId());
			}


			$this->_form->AddHiddenInput("sat_id",$this->_satellite->GetId());
			$this->_form->AddHiddenInput("type","sat_form");
			$this->_form->AddTextInput("sat_name","Name:*",$this->_satellite->GetName());
			$this->_form->AddTextarea("sat_content","Content:",$this->_satellite->GetContent());
			$this->_form->AddTextarea("sat_COSPAR","COSPAR:",$this->_satellite->GetCOSPAR());
			$this->_form->AddTextInput("sat_wiki","Wiki:",$this->_satellite->GetWiki());
			$this->_form->AddFragment("sat_status","Status:*",$this->_statusSelect);
			$this->_form->AddTextInput("sat_tle","TLE:*",$this->_satellite->GetTle());
			$this->_form->AddTextInput("sat_orbit","Orbit:*",$this->_satellite->GetOrbit());
			$this->_form->AddFragment("sat_parts","Parts:*", $this->_PartSelection);
			$this->_form->AddSubmitButton("Modify Satellite");
		}
		else
		{
	
			$this->_form->AddHiddenInput("type","sat_form");
			$this->_form->AddTextInput("sat_name","Name:*");
			$this->_form->AddTextarea("sat_content","Content:");
			$this->_form->AddTextarea("sat_COSPAR","COSPAR:");
			$this->_form->AddTextInput("sat_wiki","Wiki:");
			$this->_form->AddFragment("sat_status","Status:*",$this->_statusSelect);
			$this->_form->AddTextInput("sat_tle","TLE:*");
			$this->_form->AddTextInput("sat_orbit","Orbit:*");
			$this->_form->AddFragment("sat_parts","Parts:*", $this->_PartSelection);
			$this->_form->AddSubmitButton("Add Satellite");
		}

		$this->_form->Output();
		
		$lhistory = $this->_historyTable->GetHistory($this->_satTable->GetTable(),$this->_satellite->GetId());

		?>
		<h2>History</h2>
		<div class="list-group">

		<?php

		$lcurrentURL = new Url();
		$lcurrentURL->AddPair("page-id","Cubesat-Modify");
		$lcurrentURL->AddPair("sat_id",$this->_satellite->GetId());


		?>
			<a class="list-group-item" href="<?php echo $lcurrentURL->Output(); ?>"> Current</a>
		<?php
		for($x = 0; $x < sizeof($lhistory);$x++)
		{

		$lhistoryURL = new Url();
		$lhistoryURL->AddPair("page-id","Cubesat-Modify");
		$lhistoryURL->AddPair("history-id",$lhistory[$x]->GetId());

		?>
				<a class="list-group-item" href="<?php echo  $lhistoryURL->Output(); ?>"> <?php echo $lhistory[$x]->GetDateTime(); ?></a>
		<?php
		}
		?>


		</div>
		<?php

	}

}
?>
