<?php
require_once "Database.php";
require_once "PartVendorTable.php";

class PartRow extends Row
{
	private $_id;
	private $_description;
	private $_formalSpecification;

	private $_partVendorTable;

	public function __construct($PartData)
	{
		parent::__construct();
		$this->_partVendorTable = new PartVendorTable();
		$this->_id = $PartData["part_id"];
		$this->_description = $PartData["description"];
		$this->_formalSpecification = $PartData["formal_specification"];
	}

	public function GetId(){
		return $this->_id;
	}

	public function GetDescription(){
		return $this->_description;
	}

	public function SetDescription($description){
	$this->_description = $description;
	}

	public function GetFormalSpecification(){
		return $this->_formalSpecification;
	}

	public function SetFormalSpecification($specification)
	{
		$this->_formalSpecification = $specification;
	}

	public function Update()
	{
		$lsqlUpdate = new SqlUpdate("part",$this->_db);
		$lsqlUpdate->Where()->EqualTo("part_id",$this->_id);
		$lsqlUpdate->AddPair("description",$this->_description);
		$lsqlUpdate->AddPair("formal_specification",$this->_formalSpecification);
		$lsqlUpdate->Execute();
	}

	
   public function GetPartVendor()
   {
   		return $this->_partVendorTable->GetEntriesByPart($this);
   }



}

?>