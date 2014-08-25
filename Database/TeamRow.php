<?php 
require_once "Database.php";
require_once "UserRow.php";
require_once "PartRow.php";
require_once "SatelliteRow.php";
require_once "MissionRow.php";

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class TeamRow extends Row
{
	private $_name;
	private $_latLong;
	private $_teamId;

	public function __construct($teamData)
	{
		parent::__construct();
		$this->_latLong = $teamData["latlong"];
		$this->_name = $teamData["name"];
		$this->_teamId = $teamData["team_id"];
	}

	public function GetId()
	{
		return $this->_teamId;
	}

	public function GetName()
	{
		return $this->_name;
	}

	public function GetLatLong()
	{
		return $this->_latLong;
	}

	public function AddUser($user)
	{
		$lsql = new Sql($this->_adapter,"relation_teams_users");
		$linsert = $lsql->insert();
		$linsert->values(array(
			"team_id" => $this->_teamId,
			"user_id" => $user->GetId() 
		));

		 $lsql->prepareStatementForSqlObject($linsert)->execute();
	}

	public function RemoveUser($user)
	{
		$lsql = new Sql($this->_adapter,"relation_teams_users");
		$ldelete = $lsql->delete();
		$ldelete->where(array(
			"team_id" => $this->_teamId,
			"user_id" => $user->GetId() 
		));

		 $lsql->prepareStatementForSqlObject($ldelete)->execute();
	}

/*	public function AddPart($part)
	{


	}

	public function RemovePart($part)
	{

	}
*/
	public function AddSatellite($satellite)
	{
		$lsql = new Sql($this->_adapter,"relation_teams_sats");
		$linsert = $lsql->insert();
		$linsert->values(array(
			"team_id" => $this->_teamId,
			"sat_id" => $satellite->GetId() 
		));

		 $lsql->prepareStatementForSqlObject($linsert)->execute();
	}

	public function RemoveSatellite($satellite)
	{
		$lsql = new Sql($this->_adapter,"relation_teams_sats");
		$ldelete = $lsql->delete();
		$ldelete->where(array(
			"team_id" => $this->_teamId,
			"sat_id" => $satellite->GetId() 
		));

		$lsql->prepareStatementForSqlObject($ldelete)->execute();
	}

	public function GetSatellites()
	{
		$lsql = new Sql($this->_adapter,"satellite");
		$lselect = $lsql->select();
		$lselect->join("relation_teams_sats","relation_teams_sats.sat_id = satellite.sat_id");
	}

	public function AddMission($mission)
	{
		$lsql = new Sql($this->_adapter,"relation_teams_mission");
		$linsert = $lsql->insert();
		$linsert->values(array(
			"team_id" => $this->_teamId,
			"mission_id" => $mission->GetId() 
		));

		 $lsql->prepareStatementForSqlObject($linsert)->execute();
	}

	public function RemoveMission($mission)
	{
		$lsql = new Sql($this->_adapter,"relation_teams_users");
		$ldelete = $lsql->delete();
		$ldelete->where(array(
			"team_id" => $this->_teamId,
			"mission_id" => $mission->GetId() 
		));

		 $lsql->prepareStatementForSqlObject($ldelete)->execute();
	}

	public function GetMissions()
	{

	}

}

?>