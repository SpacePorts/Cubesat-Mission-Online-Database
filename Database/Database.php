<?php 

//require_once ROOT . "/Utility/SqlStatement.php";
require_once ROOT . "/Utility/SqlProcessor.php";

class Table
{
	protected $_db;
	protected $_adapter;

	function __construct() {
		$this->_db= new PDO("mysql:host=" . HOST .";dbname=" . DB_NAME,USER_NAME,USER_PASSWORD);
		$this->_adapter = new Zend\Db\Adapter\Adapter(array(
		    'driver' => 'Pdo_Mysql',
		    'database' => DB_NAME,
		    'username' => USER_NAME,
		    'password' => USER_PASSWORD
		 ));
	}


	function Connect()
	{
		$this->_db= new PDO("mysql:host=" . HOST .";dbname=" . DB_NAME,USER_NAME,USER_PASSWORD);
	}

	function DisConnect()
	{
		$this->_db= NULL;
	}

   	function __destruct(){
		$this->_db = NULL;
	}

}

class Row
{
	protected $_db;
	protected $_adapter;

	function __construct() {
		$this->_db= new PDO("mysql:host=" . HOST .";dbname=" . DB_NAME,USER_NAME,USER_PASSWORD);
		$this->_adapter = new Zend\Db\Adapter\Adapter(array(
		    'driver' => 'Pdo_Mysql',
		    'database' => DB_NAME,
		    'username' => USER_NAME,
		    'password' => USER_PASSWORD
		 ));
	}

	function Connect()
	{
		$this->_db= new PDO("mysql:host=" . HOST .";dbname=" . DB_NAME,USER_NAME,USER_PASSWORD);
	}

	function DisConnect()
	{
		$this->_db= NULL;
	}
	
   	function __destruct(){
		$this->_db = NULL;
	}

}

?>