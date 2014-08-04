<?php 


$pages = array(
	array("page_name"=>"Account","page-id" => "Account"),
	array("page_name"=>"Team","page-id" => "Account-Team"));
?>

<nav class="navbar navbar-inverse" id="account-nav-bar" role="navigation">
  <div class="container-fluid center_container">
	  <ul class="nav navbar-nav navbar-left">
		  <?php MenuRecursion($pages); ?>
	  </ul>
  </div>
</nav>