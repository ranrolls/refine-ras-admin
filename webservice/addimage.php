<?php

include("include/config.php");
 
$booktest = new ImageuploadClass();

$booktest->action = $common->replaceEmpty('action','');



switch($booktest->action){
  
	case 'addimage':$booktest->AddImage();
	  
	break;
	  
	 
default:
		echo json_encode('error');die;
break;


}
 

   
?>
