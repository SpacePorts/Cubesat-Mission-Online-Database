<?php 
class PartVendorRow extends Row
{

	private $_partId;
	private $_catalogEntry;
	private $_vendorId;
	private $_modelNumber;


	public function __construct($PartData)
	{
		parent::__construct();
		$this->_partId = $PartData["part_id"];
		$this->_catalogEntry = $PartData["vendor_catalog_entry"];
		$this->_vendorId = $PartData["vendor_id"];
		$this->_modelNumber = $PartData["vendor_model_number"];
	}

	public function GetPartId()
	{
		return $this->_partId;
	}

	public function GetVendorId()
	{
		return $this->_vendorId;
	}

	public function GetModelNumber()
	{
		return $this->_modelNumber;
	}

	public function GetCatalogEntry()
	{
		return $this->_catalogEntry;
	}
}

?>