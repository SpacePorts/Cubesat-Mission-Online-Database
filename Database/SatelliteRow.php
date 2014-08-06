<?php 
require_once "Database.php";
require_once "PartTable.php";


use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

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

	public function AddParts($parts)
	{
		$sql = new Sql($this->_adapter,"relation_satellite_part");

		$ldelete = $sql->delete();
		$ldelete->where(array("sat_id" => $this->GetId()));
		$sql->prepareStatementForSqlObject($ldelete)->execute();

		for($x =0; $x <	count($parts);$x++)
		{
			$linsert = $sql->insert();
			$linsert->values(array("sat_id"=>$this->GetId(),"part_id"=>$parts[$x]->GetId()));
			$sql->prepareStatementForSqlObject($linsert)->execute();
		}
	}


	public function GetParts()
	{

		$sql = new Sql($this->_adapter,"part");
		
		$lselect = $sql->select();
		$lselect->join("relation_satellite_part","relation_satellite_part.part_id = part.part_id");
		$lselect->where(array("relation_satellite_part.sat_id" => $this->GetId()));

	  	$lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

		$resultSet = new ResultSet;
		$resultSet->initialize($lresults);

		$parts = array();
		foreach ($resultSet as $row) {
			array_push($parts,new PartRow($row));
		}

		return $parts;

	}




}

?>