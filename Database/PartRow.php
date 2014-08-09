<?php
require_once "Database.php";
require_once "PartVendorTable.php";

use Zend\Db\Sql\Sql;

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

		$sql = new Sql($this->_adapter,"part");
		$lupdate = $sql->update();
		$lupdate->set(array(
			"description" =>$this->_description,
			"formal_specification" => $this->_formalSpecification));
		$lupdate->Where(array("part_id" => $this->_id));

		$sql->prepareStatementForSqlObject($lupdate)->execute();
	}

	
   public function GetPartVendor()
   {
   		return $this->_partVendorTable->GetEntriesByPart($this);
   }



}

?>