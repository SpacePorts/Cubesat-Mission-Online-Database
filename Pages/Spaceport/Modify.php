<?php
require ROOT . "/Database/SpaceportTable.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlFormSwitchBlock.php";

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

		if(!v::string()->notEmpty()->validate(Post("country")))
			$error->AddErrorPair("country","Country Required");

		if(!v::string()->notEmpty()->validate(Post("state")))
			$error->AddErrorPair("state","State Required");

		if(!v::string()->notEmpty()->validate(Post("city")))
			$error->AddErrorPair("city","City Required");

		if(!v::string()->notEmpty()->validate(Post("address_1")))
			$error->AddErrorPair("address_1","Adress 1 Required");

		if(!v::string()->notEmpty()->validate(Post("zip")))
			$error->AddErrorPair("zip","Zip Required");
		else if(!v::int()->validate(Post("zip")))
			$error->AddErrorPair("zip","Invalid Zip");

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
				$this->_spacePort->SetAddressOne(Post("address_1"));
				$this->_spacePort->SetAddressTwo(Post("address_2"));
				$this->_spacePort->SetState(Post("state"));
				$this->_spacePort->SetCountry(Post("country"));
				$this->_spacePort->SetCity(Post("city"));
				$this->_spacePort->SetZip(Post("zip"));
				$this->_spacePort->Update();
			}	
			else
			{
		
				$this->_spacePort = $this->_spaceportTable->AddSpaceport(
					Post("spaceport_name"),
					Post("spaceport_latlong"),
					Post("spaceport_url"),
					Post("spaceport_description"),
					Post("spaceport_url_googlemap"),
					Post("address_1"),
					Post("address_2"),
					Post("state"),
					Post("country"),
					Post("city"),
					Post("zip"));
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
		
			$this->_form->AddBlock("</br>");
			$this->_form->AddTextInput("country","Country:*",$this->_spacePort->GetCountry());
			$this->_form->AddTextInput("state","State:*",$this->_spacePort->GetState());
			$this->_form->AddTextInput("city","City:*",$this->_spacePort->GetCity());
			$this->_form->AddBlock("</br>");
			$this->_form->AddTextInput("address_1","Address 1:*",$this->_spacePort->GetAddressOne());
			$this->_form->AddTextInput("address_2","Address 2:",$this->_spacePort->GetAddressTwo());
			$this->_form->AddBlock("</br>");
			$this->_form->AddTextInput("zip","Zip:*",$this->_spacePort->GetZip());

			$this->_form->AddSubmitButton("Modify Spaceport");
		}
		else
		{
			$this->_form->AddTextInput("spaceport_name","Name:*");
			$this->_form->AddTextInput("spaceport_latlong","Latlong:*");
			$this->_form->AddTextInput("spaceport_url","Url:");
			$this->_form->AddTextarea("spaceport_description","Description:*");
			$this->_form->AddTextInput("spaceport_url_googlemap","GoogleMap URL:");
			

			$this->_form->AddBlock("</br>");
			$this->_form->AddTextInput("country","Country:*");
			$this->_form->AddTextInput("state","State:*");
			$this->_form->AddTextInput("city","City:*");
			$this->_form->AddBlock("</br>");
			$this->_form->AddTextInput("address_1","Address 1:*");
			$this->_form->AddTextInput("address_2","Address 2:");
			$this->_form->AddBlock("</br>");
			$this->_form->AddTextInput("zip","Zip:*");

			$this->_form->AddSubmitButton("Add Spaceport");
		}
		$this->_form->Output(); 
	}

}
?>
