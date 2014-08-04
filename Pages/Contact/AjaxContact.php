<?php 
require "../../Config.php";
require ROOT . "/Base/Capcha.php";
session_start();

/*
		Name:     		
		Email:    
		Subject:  			
		RecapchaChallange:  
		Recapcha: 			
		Message: 			
*/
 $warnings = array();
 $pass =  array();

$recapchePrivateKey = "6LdCLOYSAAAAABzDnBgAnkqe0C-kkUMUzHXC_qUN";
$email = "contact@baseplatedesigns.com";

//Name 1
if(empty($_POST["Name"]))
{
	array_push(	$warnings ,  array('ID' => 1,'Warning'=>"Required" ));
}
else
{
	array_push($pass, 1);
}

//Email 2
if(empty($_POST["Email"]))
{
	array_push(	$warnings ,  array('ID' => 2,'Warning'=>"Required" ));
}
else
{
	$validEmail = filter_var(filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
	if(!$validEmail)
	{
		array_push(	$warnings ,  array('ID' => 2,'Warning'=>"Invalid Email" ));
	}
	else
	{
		array_push($pass, 2);
	}
}

//subject 3
if(empty($_POST["Subject"]))
{
	$_POST["Subject"]="";
	array_push($pass, 3);
}

//recapche 4
if(!empty($_SESSION["Capchae"]))
{
	array_push($pass, 4);
}
else
{
	if(empty($_POST["Recapche"]))
	{
		array_push(	$warnings ,  array('ID' => 4,'Warning'=>"Required" ));
	}
	else
	{
		$validRecapche = recaptcha_check_answer ("6LcNk-ESAAAAAOLlSXDwNi-XkdCMB1DceKuCOvmI",
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["RecapcheChallange"],
	                                $_POST["Recapche"]);
		if($validRecapche)
		{
			$_SESSION["Capche"] = 1;
			array_push($pass, 4);
		}
		else
		{
			array_push(	$warnings ,  array('ID' => 4,'Warning'=>"try again" ));
		}
	}

}

//message 5
if(empty($_POST["Message"]))
{
	array_push(	$warnings ,  array('ID' => 5,'Warning'=>"Required" ));
}
else
{
	array_push($pass, 5);
}


if(count($warnings) != 0)
{
	echo json_encode(array('warnings' => $warnings,'pass'=>$pass));
}
else
{
	
	mail($email,$_POST["Subject"],$_POST["Message"],"From:".$_POST["Name"]."<".$_POST["Email"]. ">");
	unset(	$_SESSION["Capcha"] );

	echo json_encode(array('formSent'=>1,'warnings' => $warnings,'pass'=>$pass));
}

?>