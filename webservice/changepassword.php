<?php

include("include/config.php");
 
$changepass = new ChangePass();

$changepass->action = $common->replaceEmpty('action','');



switch($changepass->action){

case 'changepass':$changepass->ChangePassword();
	  
break;

case 'forgotpassword':$changepass->ForgotPassword();
	  
break;
	 
	
default:
		echo json_encode('error');die;
break;


}



   
?>
