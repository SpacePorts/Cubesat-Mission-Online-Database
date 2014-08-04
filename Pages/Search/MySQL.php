<?php

class MySQL
{
	private $_db;

	//INTIALZATION----------------------------------------------------------------------------------------------------------------------------
	function __construct() {
		$this->_db= new mysqli(HOST,USER_NAME,USER_PASSWORD,DB_NAME);
	}

	

}
?>