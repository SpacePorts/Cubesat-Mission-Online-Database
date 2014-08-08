<?php 
require_once "PartTable.php";
require_once "VendorTable.php";


use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class PartVendorRow extends Row
{
	private $_vendorTable;
	private $_partTable;

	private $_partId;
	private $_catalogEntry;
	private $_vendorId;
	private $_modelNumber;
	private $_id;


	public function __construct($PartData)
	{
		parent::__construct();
		$this->_vendorTable = new VendorTable();
		$this->_partTable = new PartTable();

		$this->_id = $PartData["relation_part_id"];
		$this->_partId = $PartData["part_id"];
		$this->_catalogEntry = $PartData["vendor_catalog_entry"];
		$this->_vendorId = $PartData["vendor_id"];
		$this->_modelNumber = $PartData["vendor_model_number"];
	}
	public function GetId()
	{
		return $this->_id;
	}

	public function GetPart()
	{
		return $this->_partTable->GetRowById($this->_partId);
	}

	public function SetPart($part)
	{

		$this->_partId = $part->GetId();
	}

	public function GetVendor()
	{
		return $this->_vendorTable->GetRowById($this->_vendorId);
	}

	public function SetVendor($vendor)
	{
		$this->_vendorId = $vendor->GetId();
	}

	public function GetModelNumber()
	{
		return $this->_modelNumber;
	}

	public function SetModelNumber($number)
	{
		$this->_modelNumber = $number;
	}
	public function GetCatalogEntry()
	{
		return $this->_catalogEntry;
	}
	public function SetCatalogEntry($entry)
	{
		$this->_catalogEntry = $entry;
	}

	public function Update()
	{
		$sql = new Sql($this->_adapter,"relation_part_vendor");
		$lupdate = $sql->update();
		$lupdate->set(array(
			"part_id" => $this->_partId,
			"vendor_catalog_entry" => $this->_catalogEntry,
			"vendor_id" => $this->_vendorId,
			"vendor_model_number" => $this->_modelNumber));
		$lupdate->Where(array("relation_part_id" => $this->_id));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}


}

?>