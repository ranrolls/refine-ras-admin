<?php
//error_reporting(E_ALL);
include("include/config.php");
 
$regis = new UserRegistration();
	 
$regis->action = $common->replaceEmpty('action','');
 


switch($regis->action){

case 'userregistration':$regis->Registration();
	  
break;

case 'editprofile':$regis->UpdateProfile();
	  
break;


case 'forgotpassword':$regis->ForgotPassword();
	  
break;
	 
	 
	 
}



   
?>
