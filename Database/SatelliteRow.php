<?php 
require_once "Database.php";
require_once "PartTable.php";

class SatelliteRow extends Row
{
	private $_content;
	private $_id;
	private $_name;
	private $_status;
	private $_wiki;
	private $_tle;
	private $_orbit;

	private $_partTable;

	public function __construct($SatelliteData)
	{
		parent::__construct();
		$this->_partTable = new PartTable();

		$this->_content = $SatelliteData["content"];
		$this->_id = $SatelliteData["sat_id"];
		$this->_name = $SatelliteData["name"];
		$this->_status = $SatelliteData["status"];
		$this->_wiki = $SatelliteData["wiki"];
		$this->_tle = $SatelliteData["tle"];
		$this->_orbit = $SatelliteData["orbit"];
	}

	public function GetTle()
	{
		return $this->_tle;
	}

	public function SetTle($tle)
	{
		$this->_tle = $tle;
	}

	public function GetOrbit(){
		return $this->_orbit;
	}

	public function SetOrbit($orbit)
	{
		$this->_orbit = $orbit;
	}

	public function GetId()
	{
		return $this->_id;
	}

	public function SetContent($content)
	{
		$this->_content = $content;
	}
	public function GetContent()
	{
		return $this->_content;
	}

	public function SetName($name)
	{
		$this->_name = $name;
	}

	public function GetName()
	{
		return $this->_name;
	}

	public static function IsStatusLegal($status)
	{
		  if($status == "active" || 
      $status == "in-orbit" || 
      $status == "in-development" || 
      $status == "data-collection" ||
      $status == "data-analysis" ||
      $status == "inactive" ||
      $status == "de-orbited" ||
      $status == "entry-closed" )
      {
      	return true;
      }
      return false;
	}

	public function SetStatus($status)
	{
		if(self::IsStatusLegal($status))
		$this->_status =$status;
		
	}

	public function GetStatus()
	{
		return $this->_status;
	}

	public function SetWiki($wiki)
	{
		$this->_wiki = $wiki;

	}
	public function GetWiki()
	{
		return $this->_wiki;
	}

	public function Update()
	{
		$sqlUpdate = new SqlUpdate("satellite",$this->_db);
		$sqlUpdate->Where()->EqualTo("sat_id",$this->_id);
		$sqlUpdate->AddPair("name",$this->_name);
		$sqlUpdate->AddPair("content",$this->_content);
		$sqlUpdate->AddPair("wiki",$this->_wiki);
		$sqlUpdate->AddPair("status",$this->_status);
		$sqlUpdate->AddPair("tle",$this->_tle);
		$sqlUpdate->AddPair("orbit",$this->_orbit);
		$sqlUpdate->Execute();
	}

	/**
	*return all the parts that relative to the satellite row
	**/
	public function GetParts()
	{
		$sqlSelect = new SqlSelect("relation_satellite_part",$this->_db);
		$sqlSelect->Where()->EqualTo("sat_id",$this->_id);
	 	$stmt =  $sqlSelect->Execute();

		$parts = array();

		while ($row = $stmt->fetch()) {
			array_push($parts,$this->_partTable->GetRowById($row["part_id"]));
		}
		return $parts;
	}

	public function GetPartsAsId()
	{
		$sqlSelect = new SqlSelect("relation_satellite_part",$this->_db);
		$sqlSelect->Where()->EqualTo("sat_id",$this->_id);
	 	$stmt =  $sqlSelect->Execute();

		$parts = array();

		while ($row = $stmt->fetch()) {
			array_push($parts,$row["part_id"]);
		}
		return $parts;
	}

	public function AddParts($part){
		$SqlInsert = new SqlInsert("relation_satellite_part",$this->_db);
		$SqlInsert->AddPair("sat_id",$this->_id);
		$SqlInsert->AddPair("part_id",$part->GetId());
		$SqlInsert->Execute();
	}




}

?>