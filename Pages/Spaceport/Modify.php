<?php
require ROOT . "/Database/SpaceportTable.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";

require_once ROOT . "/Database/UserTable.php";

use Respect\Validation\Validator as v;

class Modify extends PageBase {

	private $_user;

	private $_spaceportTable;
	private $_spacePort;
	private $_form;
	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();

		$this->_spaceportTable = new SpaceportTable();
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);

		if(isset($_REQUEST["spaceport_id"]))
		{
			$this->_spacePort =$this->_spaceportTable->GetRowById($_REQUEST["spaceport_id"]);
		}

	}
		
	function HeaderContent()
	{

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
		
		if(!v::string()->notEmpty()->validate(Post("spaceport_name")))
				$error->AddErrorPair("spaceport_name","Name Required");

		if(!v::string()->notEmpty()->validate(Post("spaceport_latlong")))
			$error->AddErrorPair("spaceport_latlong","Lat Long is require");


		if(!v::string()->notEmpty()->validate(Post("spaceport_description")))
			$error->AddErrorPair("spaceport_description","Description Required");

		if(!$error->HasError())
		{
			if(isset($_POST["spaceport_id"]))
			{
				$this->_spacePort= $this->_spaceportTable->GetRowById(Post("spaceport_id"));
				$this->_spacePort->SetName(Post("spaceport_name"));
				$this->_spacePort->SetLatLong(Post("spaceport_latlong"));
				$this->_spacePort->SetUrl(Post("spaceport_url"));
				$this->_spacePort->SetDescription(Post("spaceport_description"));
				$this->_spacePort->setGoogleMapUrl(Post("spaceport_url_googlemap"));
				$this->_spacePort->Update();
			}	
			else
			{
		
				$this->_spacePort = $this->_spaceportTable->AddSpaceport(Post("spaceport_name"),Post("spaceport_latlong"),Post("spaceport_url"),Post("spaceport_description"),Post("spaceport_url_googlemap"));
			}

			if(Post("single") == "single")
				$output["redirect"] = SITE_URL . "?page-id=Spaceport-Modify&spaceport_id=".$this->_spacePort->GetId() . "&single=single";
			else
				$output["redirect"] = SITE_URL . "?page-id=Spaceport-Modify&spaceport_id=".$this->_spacePort->GetId();
			
		}
		
	}



	function BodyContent()
	{

		if(isset($this->_spacePort))
		{
			$this->_form->AddHiddenInput("spaceport_id",$this->_spacePort->GetId());
			$this->_form->AddTextInput("spaceport_name","Name:*",$this->_spacePort->GetName());
			$this->_form->AddTextInput("spaceport_latlong","Latlong:*",$this->_spacePort->GetLatLong());
			$this->_form->AddTextInput("spaceport_url","Url:",$this->_spacePort->GetUrl());
			$this->_form->AddTextInput("spaceport_description","Description:*",$this->_spacePort->GetDescription());
			$this->_form->AddTextInput("spaceport_url_googlemap","GoogleMap URL:",$this->_spacePort->GetGoogleMapUrl());
			$this->_form->AddSubmitButton("Modify Spaceport");
		}
		else
		{
			$this->_form->AddTextInput("spaceport_name","Name:*");
			$this->_form->AddTextInput("spaceport_latlong","Latlong:*");
			$this->_form->AddTextInput("spaceport_url","Url:");
			$this->_form->AddTextInput("spaceport_description","Description:*");
			$this->_form->AddTextInput("spaceport_url_googlemap","GoogleMap URL:");
			$this->_form->AddSubmitButton("Add Spaceport");
		}
		$this->_form->Output(); 
	}

}
?>
