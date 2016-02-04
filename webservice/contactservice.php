<?php
include("include/config.php");
$booktest = new Contact();


$booktest->action = $common->replaceEmpty('action','');
 

 
switch($booktest->action){ 

case 'contactUs':$booktest->ContactMail();  
	  
break;

  	 
}

 
   
?>
