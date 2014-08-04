<?php

class Error
{
	private $_error;
	function __construct()
	{
		$this->_error = array();
		# code...
	}

	function AddErrorPair($errorId,$errorMessage)
	{
		
		array_push($this->_error,array('label_id' => $errorId, "message" => $errorMessage));
	}

	function HasError()
	{
		if(count($this->_error) == 0)
		{
			return false;
		}
		return true;
	}

	function Output()
	{
		return $this->_error;
	}
}

?>