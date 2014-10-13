<!DOCTYPE html>
<?php


	session_start();
	require "Sats.php";
	require "Config.php";
	require "Library/Captcha.php";
	require "PageHandle.php";
	require "Libraries.php";


	if(!isset($_GET["page-id"]))
		$_GET["page-id"] ="";
	$pageHandle = new PageHandle($_GET["page-id"]);
	$libraies = new Libraries();
?>

<html lang="en">
 <head>
 	 <script type="text/javascript">
 		var SITE_URL = "<?php echo SITE_URL; ?>";
 		var PAGE_URL = "<?php echo PAGE_URL; ?>";
 	</script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<meta name="MobileOptimized" content="width"/>
    <meta name="HandheldFriendly" content="true"/>
    <link rel="shortcut icon" href="<?php echo SITE_URL?>/Public/favicon.ico" />
 	<?php
	$libraies->AddJavascript("http://code.jquery.com/jquery-1.9.1.min.js");
	$libraies->AddJavascript("http://ricostacruz.com/jquery.transit/jquery.transit.min.js");
	$libraies->AddJavascript(SITE_URL . "/Public/Main.js");
	$libraies->AddJavascript(SITE_URL . "/Public/Form.js");
	$libraies->AddJavascript(SITE_URL . "/Public/bootstrap/js/bootstrap.js");
	$libraies->AddJavascript("http://www.google.com/recaptcha/api/js/recaptcha_ajax.js");

	$libraies->AddCSS(SITE_URL . "/Public/style.css");
	$libraies->AddCSS(SITE_URL . "Public/bootstrap/css/bootstrap.css");

	 $pageHandle->GetPage()->HeaderContent($libraies);
	$libraies->Output();
	 ?>
 </head>
 <?php if(Get("single") == "single" || Post("single") == "single"):?>
 	<body>
	 	<div id="Wrapper" >
	 		<div id="Page">
	 			<?php  if($pageHandle->GetPage()->IsUserLegal()): ?>
	 				<?php $pageHandle->GetPage()->BodyContent(); ?>
	 			<?php else: ?>
	 				<h1>Please Login to the correct account to view this page</h1>
	 			<?php endif; ?>
	 		</div>
	 	</div >
	 </body>

 <?php else: ?>

	 <body>

	 	<div id="Wrapper" >
		 	<?php require "Header.php"; ?>
	 		<div id="Page" class="center_container">
	 			<?php  if($pageHandle->GetPage()->IsUserLegal()): ?>
	 				<?php $pageHandle->GetPage()->BodyContent(); ?>
	 			<?php else: ?>
	 				<h1>Please Login to the correct account to view this page</h1>
	 			<?php endif; ?>
	 		</div>
	 	</div >
	 		<footer id="Footer" class="center_container">
	 			<?php require "Footer.php" ?>
	 		</footer>
	 </body>
  <?php endif; ?>

 </html>
