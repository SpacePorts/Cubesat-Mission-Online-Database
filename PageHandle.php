<?php 
require "PageBase.php";

class PageHandle
{
	private $_page;
	private $_explodedPage = array();
	private $_PageId;

	function __construct() {
		if(empty($_GET["page-id"]))
			$_GET["page-id"] = "Home";

		//sanatizes the page-id
		$lsanatize = preg_replace('/[^A-Za-z0-9\-]/', '', $_GET["page-id"]);
		$this->_explodedPage = explode("-", $lsanatize);
		$this->_PageId =  $lsanatize;
	
		$ldefault = true;

		$ldirectory = "";
		for($x = 0; $x < count($this->_explodedPage); $x++)
		{
			if(strlen ( "Pages/" .$this->_explodedPage[$x]) > 0)
			{
				$ldirectory .= $this->_explodedPage[$x];

				if($x < count($this->_explodedPage)-1)
					$ldirectory .= "/";

	
				if(is_dir(ROOT."/Pages/".$ldirectory) == false)
				{
					$ldefault = false;
					break;
				}
			}
		}


		//page url and page GEt url
		define("PAGE_URL", SITE_URL . "Pages/" . $ldirectory . "/");
		define("PAGE_GET_URL", SITE_URL ."?page-id=" . $this->_PageId );
		define("PAGE_GET_AJAX_URL", SITE_URL ."JsonHandle.php?page-id=" . $this->_PageId );

		

		if($ldefault == true)
		{
			require "Pages/" . $ldirectory ."/" . $this->_explodedPage[count($this->_explodedPage)-1].".php";
		}
		else
		{
				require "Pages/" . $ldirectory .".php";
		}

		$this->_page = new  $this->_explodedPage[count($this->_explodedPage)-1]();
	}

	
	public function GetPage()
	{

		return $this->_page;
	}
}

?>