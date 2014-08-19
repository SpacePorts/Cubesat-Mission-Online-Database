#!/usr/bin/php

<?php

$_SERVER["HTTP_HOST"] ="";

if(php_sapi_name() !== 'cli')
{
	echo "you can't trigger the Initialize script in the browser.";
	exit;
}

require "Sats.php";
require "Config.php";

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Ddl;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Metadata;
use Zend\Db\Sql\Ddl\Constraint;

	$_adapter = new Zend\Db\Adapter\Adapter(array(
	    'driver' => 'Pdo_Mysql',
	    'database' => DB_NAME,
	    'username' => USER_NAME,
	    'password' => USER_PASSWORD
	 ));

	/**
	* One of "PRIMARY KEY", "UNIQUE", "FOREIGN KEY", or "CHECK"
	*
	* @var string
	*/
	function GetConstraint($value,$column)
	{

		if($value == "PRIMARY KEY")
			return new Constraint\PrimaryKey($column);
	}

	echo "Starting_Update";


	$lmetadata = new Zend\Db\Metadata\Metadata($_adapter);	

	$ltableNames = $lmetadata->getTableNames();

	$ltables = scandir("Database");
	for($x =0; $x < count($ltables);$x++)
	{

		if (strpos($ltables[$x],'Table') !== false) {
			echo "Verfifying table class: " .  $ltables[$x] . "\n" ;

		    require_once("Database/".$ltables[$x]);
		    $class = substr($ltables[$x],0,strlen($ltables[$x])-4);
		    $ltableObject = new $class();

		    $ldatbaseTable;
		    if($ltableObject->GetTable() != "")
		    {
			    $columns = array(); 
			    if(in_array($ltableObject->GetTable(),$ltableNames))
			    {
			    	 $ldatbaseTable = new Ddl\AlterTable($ltableObject->GetTable());
			    	  $columns = $lmetadata->GetTable($ltableObject->GetTable())->getColumns();
			 	}
			 	else
			 	{
			 		echo "Creating new Table: " .  $ltableObject->GetTable(). "\n";
			 		$ldatbaseTable = new Ddl\CreateTable($ltableObject->GetTable());
			 	}
		
			    $lcolumnStructure = $ltableObject->GetColumnStructure();

			    for($y =0; $y < count($lcolumnStructure);$y++)
			    {
			    	$lcolumnExist = false;

			    	if(isset( $columns))
			    	{
				    	for($z =0; $z < count( $columns);$z++)
				    	{
				    		if($lcolumnStructure[$y]["column"]->getName() == $columns[$z]->getName())
				    		{
				    			$lcolumnExist = true;
				    		}
				    	}
			    	}
			    	
			    	if(!$lcolumnExist)
			    	{
			    		$ldatbaseTable->addColumn($lcolumnStructure[$y]["column"]);
			    		echo "Added new Column: " .  $lcolumnStructure[$y]["column"]->getName() . "\n";
			    		if(isset( $lcolumnStructure[$y]["constraints"]))
			    		{
	   						for($w = 0; $w < count($lcolumnStructure[$y]["constraints"]);$w++)
					   		{
					   			 $ldatbaseTable->addConstraint(GetConstraint($lcolumnStructure[$y]["constraints"][$w],$lcolumnStructure[$y]["column"]->getName()));

					   		}
			    		}
			    	}
			    	else
			    	{
			    		$lcolumnChange = false;
						if(isset( $lcolumnStructure[$y]["constraints"]))
			    		{
			 
			    			 foreach ($lmetadata->getConstraints($lcolumnStructure[$y]["column"]->getName()) as $constraint)
			    			 {
		   						for($y = 0; $y < count($lcolumnStructure[$y]["constraints"]);$y++)
						   		{
						   			if(!in_array($lcolumnStructure[$y]["constraints"][$y],$constraint->getType()) && in_array($lcolumnStructure[$y]["column"]->getName(),$constraint->getColumns()))
						   			{
						   				$lcolumnChange = true;
						   			 	$ldatbaseTable->addConstraint(GetConstraint($lcolumnStructure[$y]["constraints"][$y],$lcolumnStructure[$y]["column"]));
						   			}
						   		}
			    			 }
			    		}
			    		if($lcolumnChange)
			    		{
			    			$ldatbaseTable->changeColumn($lcolumnStructure[$y]["column"]->getName(),$lcolumnStructure[$y]["column"]);

			    		}
			    	}

		   		}

				echo "SQL:".$ldatbaseTable->getSqlString() . "\n";
				if($ldatbaseTable->getSqlString() != "")
				{
					$lsql = new Sql($_adapter);
			   		$_adapter->query(
					    $lsql->getSqlStringForSqlObject($ldatbaseTable),
					   Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
					);
				}

			}
	

		}

	}


	$lpropertyTable = new PropertyTable();
	echo $lpropertyTable->GetProperty("version") . "=>" . VERSION . "\n";
	$lpropertyTable->SetProperty("version",VERSION);

	echo "Done!";

		
	



