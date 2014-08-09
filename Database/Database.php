<?php 

class Table
{

	protected $_adapter;

	function __construct() {
		$this->_adapter = new Zend\Db\Adapter\Adapter(array(
		    'driver' => 'Pdo_Mysql',
		    'database' => DB_NAME,
		    'username' => USER_NAME,
		    'password' => USER_PASSWORD
		 ));
	}


}

class Row
{
	protected $_adapter;

	function __construct() {
		$this->_adapter = new Zend\Db\Adapter\Adapter(array(
		    'driver' => 'Pdo_Mysql',
		    'database' => DB_NAME,
		    'username' => USER_NAME,
		    'password' => USER_PASSWORD
		 ));
	}


}

?>