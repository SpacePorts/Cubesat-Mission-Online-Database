<?php

class Pages extends PageBase {


	function __construct() {

	}

	function HeaderContent()
	{

	}

	function BodyContent()
	{
		 include ROOT . "\Pages\Account\SubMenu.php"; 
		 ?>
		 <div class="center_container">
		 
		 </div>
		 <?php
	}
}



?>
