<?php {
require_once  "Database/UserTable.php";
require_once "HtmlFragments/HtmlFormFragment.php";

$luser = UserRow::RetrieveFromSession();

//pages to list out on the head of the document

$pages = array(
	array("page_name"=>"Home","page-id" => "Home"),
	array("page_name"=>"Reports","page-id" => "Reports","sub_menu" => array(array("page_name"=>"Satellite","page-id" => "Satellite"),array("page_name"=>"Vendor","page-id" => "Vendor"),array("page_name"=>"Component","page-id" => "Component"),array("page_name"=>"Mission","page-id" => "Mission"),array("page_name"=>"Spaceport","page-id" => "Spaceport"))),
	array("page_name"=>"Contact","page-id" => "Contact"),
	array("page_name"=>"Help","page-id" => "Help"));


$loginForm = new HtmlFormFragment(SITE_URL . "JsonHandle.php?json-id=AjaxLogin","POST","login_form");
$loginForm->AddTextInput("username","Username:");
$loginForm->AddPasswordInput("password","Password:");
$loginForm->AddSubmitButton("Login");


$registerForm = new HtmlFormFragment(SITE_URL . "JsonHandle.php?json-id=AjaxRegister","POST","signup_form");
$registerForm->AddTextInput("username","Username:");
$registerForm->AddPasswordInput("password","Password:");
$registerForm->AddPasswordInput("re_enter_password","Confirm Password:*");
$registerForm->AddTextInput("email","Email:*");
$registerForm->AddInput("captcha","Capcha:*",recaptcha_get_html(CAPTCHA_PUBLIC_KEY));
$registerForm->AddSubmitButton("Sign Up");


?>

<div id="overlay" style="display:none;"></div>
<div id="login_form_container" style="display:none;">
<h1>Login</h1>
	<?php $loginForm->Output();?>
	do you have an account? <a href="<?php echo SITE_URL. "?page-id=Register" ?>">Register</a>
</div>


<nav  class="navbar navbar-default" id="top-header" role="navigation">
  <div class="container-fluid center_container">
  	 	<div class="nav navbar-nav navbar-left ">
			 <a class="navbar-brand" href="#">Digital Astronautics</a>
	 	</div>
	   <ul class="nav navbar-nav navbar-right">
		 	<?php MenuRecursion($pages); ?>
			<li>
				<?php if(!isset($luser)) : ?>
						<a id="sign_in" href="#">Sign In</a>
				<?php else: ?>

					<a id=""  data-toggle="dropdown" class="dropdown-toggle" href="#">
						<?php echo $luser->GetUsername(); ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" >
						<li><a href="<?php echo  SITE_URL. "?page-id=Account"; ?>">Account</a></li>
						<li><a href="#" id="user_logout">Logout</a></li>
					</ul>
				<?php endif; ?>

			</li>
	 	</ul>

  </div>
 </nav>


<?php }?>
