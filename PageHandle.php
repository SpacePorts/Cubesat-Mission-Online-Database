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
		
		$lurl = "";
		for($x = 0; $x < count($this->_explodedPage); $x++)
		{
			if(strlen ( $this->_explodedPage[$x]) > 0)
			{
				$lurl .= $this->_explodedPage[$x];
				if($x < count($this->_explodedPage)-1)
					$lurl .= "/";
			}
		}


		//page url and page GEt url
		define("PAGE_URL", SITE_URL . "Pages/" . $lurl . "/");
		define("PAGE_GET_URL", SITE_URL ."?page-id=" . $this->_PageId );
		define("PAGE_GET_AJAX_URL", SITE_URL ."JsonHandle.php?page-id=" . $this->_PageId );
		require "Pages/" . $lurl ."/Main.php";

		$this->_page = new Pages();
	}

	
	public function GetPage()
	{

		return $this->_page;
	}
}

?>