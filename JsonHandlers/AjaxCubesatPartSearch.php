<?php

require ROOT . "/Database/PartTable.php";
require ROOT . "/HtmlFragments/HtmlItemSelectionFragment.php";


use Zend\Db\Sql\Where;

class Json
{
	private $_partTable;
	private $_htmlItemSelectionAjax;
	function __construct()
	{
		$this->_partTable = new PartTable();
		$this->_htmlItemSelectionAjax = new HtmlItemSelectionAjax();
	}

	function Exectute($error,&$output)
	{

		$lwhere = new Where();
		$lwhere->Like("formal_specification","%".Post("search"). "%");

		$lparts = $this->_partTable->Find(0,10,$lwhere);
		for($x =0; $x < count($lparts);$x++)
		{
			$this->_htmlItemSelectionAjax->AddPair($lparts[$x]->GetId(),$lparts[$x]->GetFormalSpecification());
		}
		$this->_htmlItemSelectionAjax->Execute($error,$output);
	}

}


?>