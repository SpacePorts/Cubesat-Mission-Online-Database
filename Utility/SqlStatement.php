<?php 

class SqlStatment
{
	private $_db;
	private $_table;

	private $_where = array();
	private $_whereValue = array();

	private $_limit = -1;
	private $_offset = -1;

	function __construct($table,$db)
	{
		$this->_table = $table;
		$this->_db = $db;
	}

	//WHERE Clauses
	function EqualTo($column,$value)
	{
		array_push($this->_where,array("value"=>( $column . " = ?"),"type" => "comparison"));
		array_push($this->_whereValue, $value);
		return $this;
	}

	function Like($column,$value)
	{
		array_push($this->_where,array("value"=>( $column . " LIKE ?"), "type"=>"comparison"));
		array_push($this->_whereValue, $value);
		return $this;
	}


	function S_Or()
	{
		array_push($this->_where,array("value"=>"OR","type"=>"operator"));
		 return $this;
	}

	function S_And()
	{
		array_push($this->_where,array("value"=>"AND","type"=>"operator"));
		return $this;
	}

	//LIMIT

	function Limit($val)
	{
		$this->_limit = (int)$val;

	}


	//OFFSET


	function Offset($val)
	{
		$this->_offset = (int)$val;

	}


	function Execute()
	{
		$lvalues = array();

		$sql = "SELECT * FROM " . $this->_table;
		if(!empty($this->_where))
		{
			$sql .= " WHERE ";
			for($x = 0;$x < count($this->_where);$x++)
			{
				if($x < count($this->_where)-1 && $this->_where[$x]["type"] == "comparison")
				{
					if($this->_where[$x+1]["type"] != "operator")
					{
						$sql.= " AND ";
					}
				}
				if(!($this->_where[0]["type"] == "operator" || $this->_where[count($this->_where)-1]["type"] == "operator" ))
				$sql.= " " . $this->_where[$x]["value"]   . " ";
			}

			$lvalues = array_merge($lvalues, $this->_whereValue);
		}

		if($this->_limit != -1)
		{
			$sql .=  " LIMIT " . $this->_limit;
		}

		if($this->_offset != -1)
		{
			$sql .=  " OFFSET " . $this->_offset;
		}
		$stmt = $this->_db->prepare($sql);
		$stmt->execute($lvalues);

		
		return $stmt;
	}

	function ExecuteCount()
	{
		$lvalues = array();

		$sql = "SELECT COUNT(*) FROM " . $this->_table;
		if(!empty($this->_where))
		{
			$sql .= " WHERE ";
			for($x = 0;$x < count($this->_where);$x++)
			{
				if($x < count($this->_where)-1 && $this->_where[$x]["type"] == "comparison")
				{
					if($this->_where[$x+1]["type"] != "operator")
					{
						$sql.= " AND ";
					}
				}
				if(!($this->_where[0]["type"] == "operator" || $this->_where[count($this->_where)-1]["type"] == "operator" ))
				$sql.= " " . $this->_where[$x]["value"]   . " ";
			}

			$lvalues = array_merge($lvalues, $this->_whereValue);
		}

		if($this->_limit != -1)
		{
			$sql .=  " LIMIT " . $this->_limit;
		}

		if($this->_offset != -1)
		{
			$sql .=  " OFFSET " . $this->_offset;
		}

		$stmt = $this->_db->prepare($sql);
		$stmt->execute($lvalues);
		
		return $stmt->fetchColumn();
	}

}

class SqlInsert
{
	private $_db;
	private $_table;

	private $_column = array();
	private $_value = array();
	function __construct($table,$db)
	{
		$this->_table = $table;
		$this->_db = $db;
	}

	function AddPair($column,$value)
	{
		array_push($this->_column, $column);
		array_push($this->_value, $value);
	}

	function Execute()
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
		$stmt->execute($this->_value);
		
	}
}


class SqlDelete
{
	private $_db;
	private $_table;

	private $_column = array();
	private $_value = array();
	function __construct($table,$db)
	{
		$this->_table = $table;
		$this->_db = $db;
	}

	function AddPair($column,$value)
	{
		array_push($this->_column, $column);
		array_push($this->_value, $value);
	}

	function Execute()
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
		$stmt->execute($this->_value);
		
	}
}








?>