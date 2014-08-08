<?php
require_once ROOT . "/Database/PartVendorTable.php";
require_once ROOT . "/Database/PartTable.php";
require_once ROOT . "/Database/VendorTable.php";
require_once ROOT . "/Database/UserTable.php";

require_once ROOT . "/HtmlFragments/HtmlDropdownFragment.php";

require_once ROOT . "/HtmlFragments/HtmlFormFragment.php";
use Zend\Db\Sql\Where;
use Respect\Validation\Validator as v;
class Modify extends PageBase {

	private $_user;

	private $_vendorTable;
	private $_partTable;
	private $_partVendorTable;

	private $_partVendor;

	private $_form;
	private $_vendorDropdown;
	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();

		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_vendorTable = new VendorTable();
		$this->_partTable = new PartTable();
		$this->_partVendorTable = new PartVendorTable();
		if(isset($_REQUEST["part_vendor_id"]))
		{
			$this->_partVendor = $this->_partVendorTable->GetRowById($_REQUEST["part_vendor_id"]);
			$this->_vendorDropdown = new HtmlDropdownFragment("vendor_id",$this->_partVendor->GetVendor()->GetId());
		}
		else
		{
			$this->_vendorDropdown = new HtmlDropdownFragment("vendor_id");
		}

		$where = new Where();
		$vendors  = $this->_vendorTable->find(0,-1,$where);
		$this->_vendorDropdown->AddOption("NULL","--Select Vendor--");
		for($x  =0; $x < count($vendors);$x++)
		{
			$this->_vendorDropdown->AddOption($vendors[$x]->GetId(),$vendors[$x]->GetName());
		}
		

	}
		
	function HeaderContent()
	{

	}

	function Ajax($error,&$output)
	{
		if($this->_user->GetType() == UserRow::PRODUCER)
		{
			if(!v::string()->notEmpty()->validate(Post("model_number")))
				$error->AddErrorPair("model_number","Model Number Required");

			if(!v::string()->notEmpty()->validate(Post("catalog_entry")))
				$error->AddErrorPair("catalog_entry","Catalog Entry Required");

			if(!$this->_vendorDropdown->IsOptionValid(Post("vendor_id")))
				$error->AddErrorPair("vendor_id","Vendor Required");

			$component = $this->_partTable->GetRowById(Post("component_id"));
			$vendor = $this->_vendorTable->GetRowById(Post("vendor_id"));

			if(!$error->HasError())
			{
				if(isset($this->_partVendor))
				{
					$this->_partVendor->SetCatalogEntry(Post("catalog_entry"));
					$this->_partVendor->SetModelNumber(Post("model_number"));
					$this->_partVendor->SetVendor($vendor);
					$this->_partVendor->SetPart($component);
					$this->_partVendor->Update();
				}
				else
				{
					$this->_partVendor = $this->_partVendorTable->AddPartVendor(Post("catalog_entry"),$vendor,$component,Post("model_number"));
				}

				if(Post("single") == "single")
					$output["redirect"] = SITE_URL. "?page-id=Component-PartVendor-Modify&part_vendor_id=".$this->_partVendor->GetId() . "&single=single";
				else
					$output["redirect"] = SITE_URL . "?page-id=Component-PartVendor-Modify&part_vendor_id=".$this->_partVendor->GetId();
			}
		}
	}

	function BodyContent()
	{

		if(isset($this->_partVendor))
		{
			$this->_form->AddTextInput("model_number","Model Number:*",$this->_partVendor->GetModelNumber());
			$this->_form->AddTextarea("catalog_entry","Catalog Entry:*",$this->_partVendor->GetCatalogEntry());
			$this->_form->AddHiddenInput("part_vendor_id",$this->_partVendor->GetId());
			$this->_form->AddFragment("vendor_id","Vendor",$this->_vendorDropdown);
			$this->_form->AddHiddenInput("component_id",$this->_partVendor->GetPart()->GetId());
			$this->_form->AddSubmitButton("Modify Part","pull-right");
		}
		else
		{

			$this->_form->AddTextInput("model_number","Model Number:*");
			$this->_form->AddTextarea("catalog_entry","Catalog Entry:*");
			$this->_form->AddHiddenInput("component_id",Get("component_id"));
			$this->_form->AddFragment("vendor_id","Vendor",$this->_vendorDropdown);
			$this->_form->AddSubmitButton("Add Part","pull-right");
		}

		$this->_form->Output();
	}

}
?>
