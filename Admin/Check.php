<?php

if(empty($_SESSION['ClientType']))
{
	 die();
}

if($_SESSION['ClientType'] != "Admin")
{
	 die();
}

?>