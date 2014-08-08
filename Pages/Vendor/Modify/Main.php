<?php
require ROOT . "/Database/VendorTable.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";

require_once ROOT . "/Database/UserTable.php";
class Pages  extends PageBase{
	private $_vendorTable;
	private $_form;
	private $_vendor;
	private $_user;

	function __construct() {
		$this->_vendorTable = new VendorTable();
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_user = UserRow::RetrieveFromSession();
		if(isset($_REQUEST["vendor-id"]))
		{
			$this->_vendor = $this->_vendorTable->GetRowById($_REQUEST["vendor-id"]);
		}

	}
	
	public function HeaderContent()
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

	public function Ajax($error,&$output)
	{


		if(empty($_POST["vendor_name"]))
				$error->AddErrorPair("vendor_name","Vendor Name Required");

		if(empty($_POST["vendor_url"]))
			$error->AddErrorPair("vendor_url","Vendor Url Required");

		if(empty($_POST["vendor_contactInfo"]))
			$error->AddErrorPair("vendor_contactInfo","Vendor Contact Info Required");

		if(empty($_POST["vendor_type"]))
			$error->AddErrorPair("vendor_type","Vendor Type Required");

		if(!$error->HasError())
		{
			if(isset($_POST["vendor_id"]))
			{
				$this->_vendor = $this->_vendorTable->GetRowById($_POST["vendor_id"]);
				$this->_vendor->SetName($_POST["vendor_name"]);
				$this->_vendor->SetUrl($_POST["vendor_url"]);
				$this->_vendor->SetContactInfo($_POST["vendor_contactInfo"]);
				$this->_vendor->SetType($_POST["vendor_type"]);
				$this->_vendor->Update();
			}	
			else
			{
				$this->_vendor = $this->_vendorTable->addVendor($_POST["vendor_name"],$_POST["vendor_url"],$_POST["vendor_contactInfo"],$_POST["vendor_type"]);
			}
			if(Post("single") == "single")
				$output["redirect"] = SITE_URL . "?page-id=Vendor-Modify&vendor-id=".$this->_vendor->GetId() . "&single=single";
			else
				$output["redirect"] = SITE_URL . "?page-id=Vendor-Modify&vendor-id=".$this->_vendor->GetId();
		}
		
	}


	function BodyContent()
	{
		if(Get("single"))
			$this->_form->AddHiddenInput("single","single");
		
		if(isset($this->_vendor))
		{
			$this->_form->AddHiddenInput("vendor_id",$this->_vendor->GetId());
			$this->_form->AddTextInput("vendor_name","Name:*",$this->_vendor->GetName());
			$this->_form->AddTextInput("vendor_url","Url:*",$this->_vendor->GetUrl());
			$this->_form->AddTextInput("vendor_contactInfo","Contact Info:*",$this->_vendor->GetContactInfo());
			$this->_form->AddTextInput("vendor_type","Type:*",$this->_vendor->GetType());
			$this->_form->AddSubmitButton("Modify Vendor","pull-right");
		}
		else
		{
			$this->_form->AddTextInput("vendor_name","Name:*");
			$this->_form->AddTextInput("vendor_url","Url:*");
			$this->_form->AddTextInput("vendor_contactInfo","Contact Info:*");
			$this->_form->AddTextInput("vendor_type","Type:*");
			$this->_form->AddSubmitButton("Add Vendor","pull-right");
		}
	
		$this->_form->Output();
	
	}
}
?>
