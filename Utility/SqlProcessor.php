<?php 

class SqlWhere
{

	private $_where = array();
	private $_values = array();


	//WHERE Clauses
	public function EqualTo($column,$value)
	{
		array_push($this->_where,array("value"=>( $column . " = ?"),"type" => "comparison"));
		array_push($this->_values, $value);
		return $this;
	}

	public function Like($column,$value)
	{
		array_push($this->_where,array("value"=>( $column . " LIKE ?"), "type"=>"comparison"));
		array_push($this->_values, $value);
		return $this;
	}


	public function S_Or()
	{
		array_push($this->_where,array("value"=>"OR","type"=>"operator"));
		 return $this;
	}

	public function S_And()
	{
		array_push($this->_where,array("value"=>"AND","type"=>"operator"));
		return $this;
	}

	public function GetWhereSql()
	{
		$sql ="";
		if(!empty($this->_where))
		{
			$sql .= " WHERE ";
			for($x = 0;$x < count($this->_where);$x++)
			{
				if($x == 0 || $x == count($this->_where)-1)
				{
					if($this->_where[$x]["type"] != "operator")
						$sql.= " " . $this->_where[$x]["value"]   . " ";
				}
				else
				$sql.= " " . $this->_where[$x]["value"]   . " ";

				if($x < count($this->_where)-1 && $this->_where[$x]["type"] == "comparison")
				{
					if($this->_where[$x+1]["type"] != "operator")
					{
						$sql.= " AND ";
					}
				}

		

			}

		}
		return $sql;
	}

	public function GetWhereValues()
	{
		return $this->_values;
	}
}

class SqlInsert
{

	private $_db;
	private $_table;

	private $_column = array();
	private $_values = array();

	function __construct($table,$db)
	{
		$_where = new SqlWhere();
		$this->_table = $table;
		$this->_db = $db;
	}

	function AddPair($column,$value)
	{
		array_push($this->_column, $column);
		array_push($this->_values, $value);
	}

	public function Execute()
	{
		$sql = "INSERT INTO " . $this->_table . " (";
		for($x = 0; $x < count($this->_column); $x++)
		{
			$sql .= $this->_column[$x];
			if($x < count($this->_column)-1)
				$sql .= ",";
		}
		$sql .= ") VALUES (";

		for($x = 0; $x < count($this->_column); $x++)
		{
			$sql .= "?";
			if($x < count($this->_column)-1)
				$sql .= ",";
		}

		$sql .=  ")";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute($this->_values);
	}

}

 class SqlSelect
{
	private $_db;
	private $_table;

	private $_where;

	private $_limit = -1;
	private $_offset = -1;

	function __construct($table,$db)
	{
		$this->_where = new SqlWhere();
		$this->_table = $table;
		$this->_db = $db;
	}

	public function Where(){
		return $this->_where;
	}


	function Limit($val)
	{
		$this->_limit = (int)$val;

	}

	function Offset($val)
	{
		$this->_offset = (int)$val;

	}

	public function Execute()
	{
		$lvalues = array();
		$sql = "SELECT * FROM " . $this->_table;

		$sql .= $this->_where->GetWhereSql();
		$lvalues = array_merge($lvalues, $this->_where->GetWhereValues());
	
		if($this->_limit != -1)
		{
			$sql .=  " LIMIT " .$this->_limit;
		}

		if($this->_offset != -1)
		{
			$sql .=  " OFFSET " .$this->_offset;
		}

		$stmt = $this->_db->prepare($sql);
		$stmt->execute($lvalues);
		return $stmt;
	}

	public function ExecuteWithCount()
	{
		$sql = "SELECT COUNT(*) FROM " . $this->_table;
		$sql .= $this->_where->GetWhereSql();
		$itemValue = $this->_where->GetWhereValues;
	
		if($this->_limit != -1)
		{
			$sql .=  " LIMIT " .$this->_limit;
		}

		if($this->_offset != -1)
		{
			$sql .=  " OFFSET " .$this->_offset;
		}

		$stmt = $this->_db->prepare($sql);
		$stmt->execute($itemValue);
		
		return $stmt->fetchColumn();
	}

}

 class SqlUpdate
{
	private $_db;
	private $_table;

	private $_where;

	private $_column= array();
	private $_values = array();

	function __construct($table,$db)
	{
		$this->_where = new SqlWhere();
		$this->_table = $table;
		$this->_db = $db;
	}

	public function Where(){
		return $this->_where;
	}


	function AddPair($column,$value)
	{
		array_push($this->_column, $column);
		array_push($this->_values, $value);
	}
	
	//UPDATE `cubesat`.`satellite` SET `content`='daf', `wiki`='asdf' WHERE `sat_id`='3';
	public function Execute()
	{
		$sql = "UPDATE ".$this->_table." SET " ;

		for($x = 0; $x < count($this->_column); $x++)
		{
			$sql .= $this->_column[$x] . " = " . "?";
			if($x < count($this->_column)-1)
				$sql .= ",";
		}
		$sql.= $this->_where->GetWhereSql();
		$valueItem = array_merge($this->_values, $this->_where->GetWhereValues());

		$stmt = $this->_db->prepare($sql);
		$stmt->execute($valueItem);


	}
}

class SqlDelete
{
	private $_db;
	private $_table;

	private $_where;


	function __construct($table,$db)
	{
		$this->_where = new SqlWhere();
		$this->_table = $table;
		$this->_db = $db;
	}

	public function Where(){
		return $this->_where;
	}

	//UPDATE `cubesat`.`satellite` SET `content`='daf', `wiki`='asdf' WHERE `sat_id`='3';
	public function Execute()
	{
		$sql = "DELETE FROM ".$this->_table ;

		$sql.= $this->_where->GetWhereSql();
		$itemValue = $this->_where->GetWhereValues();

		$stmt = $this->_db->prepare($sql);

		$stmt->execute($itemValue);


	}
}





?>