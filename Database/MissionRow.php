<?php
require_once "Database.php";
require_once "SatelliteTable.php";
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
class MissionRow extends Row
{
	private $_id;
	private $_name;
	private $_objective;
	private $_wiki;
	private $_content;
	public function __construct($missionData)
	{
		parent::__construct();
		$this->_id = $missionData["mission_id"];
		$this->_name = $missionData["name"];
		$this->_objective = $missionData["objective"];
		$this->_wiki = $missionData["wiki"];
		$this->_content = $missionData["content"];
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

	public function GetContent(){
		return $this->_content;
	}

	public function SetContent($content)
	{
		$this->_content = $content;
	}
	public function GetObjective()
	{
		return $this->_objective;
	}
	public function SetObjective($objective)
	{
		$this->_objective = $objective;
	}

	public function GetWiki(){
		return $this->_wiki;
	}

	public function SetWiki($wiki)
	{
		$this->_wiki = $wiki;
	}

	public function AddSatellites($satellites)
	{
		$sql = new Sql($this->_adapter,"relation_mission_sat");

		$ldelete = $sql->delete();
		$ldelete->where(array("mission_id" => $this->GetId()));
		$sql->prepareStatementForSqlObject($ldelete)->execute();

		for($x =0; $x <	count($satellites);$x++)
		{
			$linsert = $sql->insert();
			$linsert->values(array("mission_id"=>$this->GetId(),"sat_id"=>$satellites[$x]->GetId()));
			$sql->prepareStatementForSqlObject($linsert)->execute();
		}
	}

	public function GetSatellites()
	{
		$sql = new Sql($this->_adapter,"satellite");
		
		$lselect = $sql->select();
		 $lselect->join("relation_mission_sat","relation_mission_sat.sat_id = satellite.sat_id");
		$lselect->where(array("relation_mission_sat.mission_id" => $this->GetId()));

	  	$lresults = $sql->prepareStatementForSqlObject($lselect)->execute();

		$resultSet = new ResultSet;
		$resultSet->initialize($lresults);

		$satellites = array();
		foreach ($resultSet as $row) {
			array_push($satellites,new SatelliteRow($row));
		}

		return $satellites;
	}

	public function Update()
	{
		$sql = new Sql($this->_adapter,"mission");
		$lupdate = $sql->update();
		$lupdate->set(array(
			"mission_id" => $this->_id,
			"name" => $this->_name,
			"objective" => $this->_objective,
			"wiki" => $this->_wiki,
			"content"=>$this->_content));
		$lupdate->Where(array("mission_id" => $this->_id));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}

}

?>