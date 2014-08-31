<?php
	session_start();

	require "Sats.php";
	require "Config.php";
	require "Utility/Error.php";
	require "Library/Captcha.php";
	require "PageHandle.php";

	//creates an error handler
	$lerror = new Error();
	$loutput = array();
	$lredirect = "";

	if(isset($_GET["page-id"]))
	{

		$page = new PageHandle($_GET["page-id"]);
		if($page->GetPage()->IsUserLegal())
		$lredirect = $page->GetPage()->Ajax($lerror,$loutput);

	}
	else if(isset($_GET["json-id"]))//used for custom ajax
	{

		//prevents people from injecting info into url
		$listedPages = scandir("JsonHandlers");
		$_GET["json-id"] = $_GET["json-id"] . ".php";
		for($x = 0; $x < count($listedPages); $x++)
		{
			if($_GET["json-id"] == $listedPages[$x])
			{
				require "JsonHandlers/" . $listedPages[$x];
				$json = new Json();
				$lredirect = $json->Exectute($lerror,$loutput);
			}
		}
	}
	if($lredirect != "")
	$loutput["redirect"] = $lredirect;
	$loutput["error"] = $lerror->Output();
	echo json_encode($loutput);

?>