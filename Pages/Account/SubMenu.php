<?php 
require_once ROOT. "/Database/UserRow.php";

$luser = UserRow::RetrieveFromSession();
$lpage;

if($luser->GetType() == UserRow::ADMIN)
{
$lpage = array(
	array("page_name"=>"Account","page-id" => "Account"),
	array("page_name"=>"Team","page-id" => "Account-Team"),
	array("page_name"=>"Users","page-id" => "Account-User"));
}
else
{
$lpage = array(
	array("page_name"=>"Account","page-id" => "Account"),
	array("page_name"=>"Team","page-id" => "Account-Team"));
}

?>

<nav class="navbar navbar-inverse" id="account-nav-bar" role="navigation">
  <div class="container-fluid center_container">
	  <ul class="nav navbar-nav navbar-left">
		  <?php MenuRecursion($lpage); ?>
	  </ul>
  </div>
</nav>