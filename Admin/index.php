<?php
session_start(); 
require_once "../Config.php";
require_once "Check.php";

define("ADMIN_SITE_URL", SITE_URL . "Admin/");


	if(empty($_GET["page-id"]))
	{
		$_GET["page-id"] = "Home";
	}
	$listedPages = scandir("Pages", 1);
	for($x = 0; $x < count($listedPages); $x++)
	{
		if($_GET["page-id"] == $listedPages[$x])
		{
			require "Pages/" . $listedPages[$x] ."/main.php";
			define("ADMIN_PAGE_GET_URL", ADMIN_SITE_URL. "?page-id=" . $_GET["page-id"]);
			define("ADMIN_PAGE_URL", ADMIN_SITE_URL ."Pages/". $_GET["page-id"] . "/");
		}
	}

	$pages = new Pages();
?>
		<html>
			<head>
				<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript"></script>
				<link rel="stylesheet" type="text/css" href=" <?php echo ADMIN_SITE_URL . "style.css" ?>"></link>
				<?php $pages->HeaderContent(); ?>
			</head>
			<body>
				<?php require "Menu.php"; ?>
	
				<div id="MainContainer">
					<?php $pages->BodyContent(); ?>

				</div>
			</body>
		</html>
