<!DOCTYPE html>
<?php


	session_start();

	require "Config.php";
	require "Library/Captcha.php";
	require "PageHandle.php";

	if(!isset($_GET["page-id"]))
		$_GET["page-id"] ="";
	$pageHandle = new PageHandle();

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
    <script type="text/javascript" src='http://www.google.com/recaptcha/api/js/recaptcha_ajax.js'></script>
 	<!--link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>style.css"-->

 	<?php //new css taht will replace the old one ?>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>/Public/style.css">

 	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
 	<script type="text/javascript" src="http://ricostacruz.com/jquery.transit/jquery.transit.min.js"></script>
 	<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/Main.js"></script>
 	<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/Form.js"></script>
 	
 	<?php //BOOTSTRAP ?>
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>Public/bootstrap/css/bootstrap.css">
	<script src="<?php echo SITE_URL; ?>Public/bootstrap/js/bootstrap.js"></script>

 	<link rel="stylesheet" type="text/css" href="">
 	
 	<?php $pageHandle->GetPage()->HeaderContent(); ?>
 </head>
 <?php if(Get("single") == "single" || Post("single") == "single"):?>
 	<body>
	 	<div id="Wrapper" >
	 		<div id="Page">
	 			<?php  if($pageHandle->GetPage()->IsUserLegal()): ?>
	 				<?php $pageHandle->GetPage()->BodyContent(); ?>
	 			<?php else: ?>
	 				<h1>Access Denied</h1>
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
	 				<h1>Access Denied</h1>
	 			<?php endif; ?>
	 		</div>
	 	</div >
	 		<footer id="Footer" class="center_container">
	 			<?php require "Footer.php" ?>
	 		</footer>
	 </body>
  <?php endif; ?>

 </html>
 