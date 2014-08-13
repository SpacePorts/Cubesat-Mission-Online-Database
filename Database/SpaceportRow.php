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

	private $_state;
	private $_country;
	private $_city;
	private $_addressOne;
	private $_addressTwo;
	private $_zip;

	public function __construct($spaceportData)
	{
		parent::__construct();
		$this->_id = $spaceportData["spaceport_id"];
		$this->_name = $spaceportData["name"];
		$this->_latLong = $spaceportData["latlong"];
		$this->_url = $spaceportData["url"];
		$this->_description = $spaceportData["description"];
		$this->_googleMapUrl = $spaceportData["url_googlemap"];

		$this->_addressOne = $spaceportData["address1"];
		$this->_addressTwo = $spaceportData["address2"];
		$this->_city = $spaceportData["city"];
		$this->_country = $spaceportData["country"];
		$this->_state = $spaceportData["state"];
		$this->_zip = $spaceportData["zip"];
	}
	public function GetId()
	{
		return $this->_id;
	}

	public function SetAddressOne($addressOne)
	{
		$this->_addressOne = $addressOne;
	}

	public function GetAddressOne()
	{
		return $this->_addressOne;
	}

	public function SetAddressTwo($addressTwo)
	{
		$this->_addressTwo = $addressTwo;
	}

	public function GetAddressTwo()
	{
		return $this->_addressTwo;
	}

	public function Setstate($state)
	{
		$this->_state = $state;
	}

	public function GetState()
	{
		return $this->_state;
	}

	public function SetCountry($country)
	{
		$this->_country = $country;
	}

	public function GetCountry()
	{
		return $this->_country;
	}

	public function SetCity($city)
	{
		$this->_city = $city;
	}

	public function GetCity()
	{
		return $this->_city;
	}

	public function SetZip($zip)
	{
		$this->_zip = $zip;
	}

	public function GetZip()
	{
		return $this->_zip;
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
			"url_googlemap"=>$this->_googleMapUrl,
			"address1"=>$this->_addressOne,
			"address2"=>$this->_addressTwo,
			"state"=>$this->_state,
			"country"=>$this->_country,
			"city"=>$this->_city,
			"zip"=>$this->_zip));
		$lupdate->Where(array("spaceport_id" => $this->_id));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}

}
