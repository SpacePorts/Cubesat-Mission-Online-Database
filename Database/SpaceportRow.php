<?php 
require_once "Database.php";

use Zend\Db\Sql\Sql;

class SpaceportRow extends Row
{
	private $_id;
	private $_name;
	private $_latLong;
	private $_url;
	private $_description;
	private $_googleMapUrl;


	public function __construct($spaceportData)
	{
		parent::__construct();
		$this->_id = $spaceportData["spaceport_id"];
		$this->_name = $spaceportData["name"];
		$this->_latLong = $spaceportData["latlong"];
		$this->_url = $spaceportData["url"];
		$this->_description = $spaceportData["description"];
		$this->_googleMapUrl = $spaceportData["url_googlemap"];

	}
	public function GetId()
	{
		return $this->_id;
	}


	public function GetName()
	{
		return $this->_name;
	}

	public function SetName($name)
	{
		$this->_name = $name;
	}

	public function GetLatLong()
	{
		return $this->_latLong;
	}

	public function SetLatLong($latLong)
	{
		$this->_latLong = $latLong;
	}

	public function GetUrl()
	{
		return $this->_url;
	}

	public function SetUrl($url)
	{
		$this->_url = $url;
	}

	public function GetDescription()
	{
		return $this->_description;
	}

	public function SetDescription($description)
	{
		$this->_description = $description;
	}

	public function GetGoogleMapUrl()
	{
		return $this->_googleMapUrl;
	}

	public function setGoogleMapUrl($googleMapUrl)
	{
		$this->_googleMapUrl = $googleMapUrl;
	}

	public function Update()
	{

		$sql = new Sql($this->_adapter,"spaceport");
		$lupdate = $sql->update();
		$lupdate->set(array(
			"name" => $this->_name,
			"latlong" => $this->_latLong,
			"url" => $this->_url,
			"description"=>$this->_description,
			"url_googlemap"=>$this->_googleMapUrl));
		$lupdate->Where(array("spaceport_id" => $this->_id));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}

}
