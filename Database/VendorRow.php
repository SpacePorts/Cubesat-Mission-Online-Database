<?php
require_once "Database.php";

use Zend\Db\Sql\Sql;
class VendorRow extends Row
{
	private $_id;
	private $_name;
	private $_url;
	private $_contact_info;
	private $_type;
	public function __construct($vendorData)
	{
		parent::__construct();
		$this->_id = $vendorData["vendor_id"];
		$this->_name = $vendorData["name"];
		$this->_url = $vendorData["url"];
		$this->_contact_info = $vendorData["contact_info"];
		$this->_type = $vendorData["type"];
	}

	public function GetId()
	{
		return $this->_id;
	}

	public function GetName(){
		return $this->_name;
	}

	public function SetName($name)
	{
		$this->_name = $name;
	}

	public function GetUrl()
	{
		return $this->_url;
	}

	public function SetUrl($url)
	{
		$this->_url = $url;

	}

	public function GetContactInfo()
	{
		return $this->_contact_info;
	}

	public function SetContactInfo($info)
	{
		$this->_contact_info = $info;
	}

	public function GetType(){
		return $this->_type;
	}

	public function SetType($type)
	{
		$this->_type = $type;
	}

	public function Update()
	{
		$sql = new Sql($this->_adapter,"vendor");
		$lupdate = $sql->update();
		$lupdate->set(array(
			"name" => $this->_name,
			"url" => $this->_url,
			"contact_info" => $this->_contact_info,
			"type" => $this->_type));
		$lupdate->Where(array("vendor_id" => $this->_id));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}

}

?>