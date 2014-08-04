<?php
require ROOT . "/Database/SatelliteTable.php";
require ROOT . "/HtmlFragments/HtmlItemSelectionFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlDropdownFragment.php";
require_once ROOT . "/Database/UserTable.php";

class Pages {
	private $_satTable;
	private $_satellite;

	private $_PartSelection;
	private $_form;
	private $_statusSelect;
	
	function __construct() {
		$this->_satTable = new SatelliteTable();
		$this->_user = UserRow::RetrieveFromSession();

		if(isset($_REQUEST["sat_id"]))
		{

			$this->_satellite = $this->_satTable->GetRowById($_REQUEST["sat_id"]);
			$this->_statusSelect = new HtmlDropdownFragment("sat_status",$this->_satellite->GetStatus());			
		}
		else
		{
			$this->_statusSelect = new HtmlDropdownFragment("sat_status");		
		}

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
		$this->_PartSelection = new HtmlItemSelectionFragment(SITE_URL . "JsonHandle.php?json-id=AjaxCubesatPartSearch" ,"sat_parts");
		$this->_PartSelection->Header();
	}

	function GetPageID()
	{
		return  "Cubesat-Modify";
	}


	function Ajax($error,&$output)
	{
		if($this->_user->GetType() == UserRow::PRODUCER)
		{
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

			if(Post("sat_parts") == "")
				$error->AddErrorPair("sat_parts","Satellite Parts required");

			if(!$error->HasError())
			{
				if(isset($this->_satellite))
				{
					$this->_satellite->SetName(Post("sat_name"));
					$this->_satellite->SetContent(Post("sat_content"));
					$this->_satellite->SetWiki(Post("sat_wiki"));
					$this->_satellite->SetStatus(Post("sat_status"));
					$this->_satellite->SetTle(Post("sat_tle"));
					$this->_satellite->SetOrbit(Post("sat_orbit"));
					$this->_satellite->Update();
				}
				else
				{
					$this->_satellite = $this->_satTable->AddSatellite(Post("sat_name"),Post("sat_content"),Post("sat_tle"),Post("sat_orbit"),Post("sat_wiki"),Post("sat_status"));
					
				}
				
				//NEEDS REWORKING REALLY SLOW
				$_POST["sat_parts"] = array_unique($_POST["sat_parts"]);
				$lPartIds = $this->_satellite->GetPartsAsId();

				$lAddParts = array_diff ($_POST["sat_parts"],$lPartIds);
				$lRemoveParts = array_diff ($lPartIds,$_POST["sat_parts"]);

				foreach ($lAddParts as $value) {
					$this->_satellite->AddPartWithId($value);
				}

				foreach ($lRemoveParts as $value) {
					$this->_satellite->DeletePartWithId($value);
				}

				if(Post("single") == "single")
					$output["redirect"] = SITE_URL . "?page-id=Cubesat-Modify&sat_id=".$this->_satellite->GetId() . "&single=single";
				else
					$output["redirect"] = SITE_URL . "?page-id=Cubesat-Modify&sat_id=".$this->_satellite->GetId();
				
			}
		}
	}

	function BodyContent()
	{
		if(Get("single"))
			$this->_form->AddHiddenInput("single","single");
		

		if(isset($this->_satellite))
		{
			$parts = $this->_satellite->GetParts();
			for($x =0; $x < count($this->_satellite->GetParts()); $x++)
			{
				$this->_PartSelection->AddPair($this->_satellite->GetParts()[$x]->GetId(),$this->_satellite->GetParts()[$x]->GetFormalSpecification());
			}
		}

		if(isset($this->_satellite))
		{
			$this->_form->AddHiddenInput("sat_id",$this->_satellite->GetId());
			$this->_form->AddTextInput("sat_name","Name:*",$this->_satellite->GetName());
			$this->_form->AddTextarea("sat_content","Content:",$this->_satellite->GetContent());
			$this->_form->AddTextInput("sat_wiki","Wiki:",$this->_satellite->GetWiki());
			$this->_form->AddFragment("sat_status","Status:*",$this->_statusSelect);
			$this->_form->AddTextInput("sat_tle","TLE:*",$this->_satellite->GetTle());
			$this->_form->AddTextInput("sat_orbit","Orbit:*",$this->_satellite->GetOrbit());
			$this->_form->AddFragment("sat_parts","Parts:*", $this->_PartSelection);
			$this->_form->AddSubmitButton("Modify Satellite");
		}
		else
		{
			$this->_form->AddTextInput("sat_name","Name:*");
			$this->_form->AddTextarea("sat_content","Content:");
			$this->_form->AddTextInput("sat_wiki","Wiki:");
			$this->_form->AddFragment("sat_status","Status:*",$this->_statusSelect);
			$this->_form->AddTextInput("sat_tle","TLE:*");
			$this->_form->AddTextInput("sat_orbit","Orbit:*");
			$this->_form->AddFragment("sat_parts","Parts:*", $this->_PartSelection);
			$this->_form->AddSubmitButton("Add Satellite");
		}

		$this->_form->Output();

	}

}
?>
