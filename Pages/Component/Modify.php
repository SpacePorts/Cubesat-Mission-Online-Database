<?php
require_once ROOT . "/Database/PartTable.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlIframePanelFormListFormFragment.php";


require_once ROOT . "/Database/UserTable.php";

class Modify extends PageBase {
	private $_user;

	private $_partTable;
	private $_form;
	private $_component;
	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();
		$this->_partTable = new PartTable();
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);

		if(Get("component_id") != "")
		{
			$this->_component = $this->_partTable->GetRowById(Get("component_id"));
		}

	}

	function HeaderContent($libraries)
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

		if(empty($_POST["component_formal_specification"]))
			$error->AddErrorPair("component_formal_specification","Formal Specification required");


		if(!isset($_POST["component_description"]))
			$_POST["component_description"] = "Description Required";


		if(!$error->HasError())
		{
			if(isset($_POST["component_id"]))
			{
				$this->_component = $this->_partTable->GetRowById($_POST["component_id"]);
				$this->_component->SetFormalSpecification($_POST["component_formal_specification"]);
				$this->_component->Update();
			}
			else
			{
				$this->_component = $this->_partTable->AddPart($_POST["component_description"],$_POST["component_formal_specification"]);
			}

			if(Post("single") == "single")
				$output["redirect"] = SITE_URL. "?page-id=Component-Modify&component_id=".$this->_component->GetId() . "&single=single";
			else
				$output["redirect"] = SITE_URL . "?page-id=Component-Modify&component_id=".$this->_component->GetId();
		}

	}

	function BodyContent()
	{
		if(Get("single"))
			$this->_form->AddHiddenInput("single","single");

		if(isset($this->_component))
		{
			$this->_form->AddHiddenInput("component_id",$this->_component->GetId());
			$this->_form->AddTextInput("component_formal_specification","Formal Specification:*",$this->_component->GetFormalSpecification());
			$this->_form->AddTextInput("component_description","Component Description:*",$this->_component->GetDescription());
			$this->_form->AddSubmitButton("Modify Spaceport","pull-right");
		}
		else
		{

			$this->_form->AddTextInput("component_formal_specification","Formal Specification:*");
			$this->_form->AddTextInput("component_description","Component Description:*");
			$this->_form->AddSubmitButton("Add Spaceport","pull-right");
		}


		$this->_form->Output();
	}

}
?>
