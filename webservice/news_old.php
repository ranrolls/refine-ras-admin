<?php
include("include/config.php");

$booktest = new AddreceiptClass();

$booktest->action = $common->replaceEmpty('action','');



switch($booktest->action){
  
	case 'newsdetails':$booktest->ArticleDetails();
	  
	break;

case 'newsdetails_by_id':$booktest->ArticleDetails_by_id();
	  
	break;
	  
}

 
   
?>
