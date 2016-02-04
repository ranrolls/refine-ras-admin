<?php
include("include/config.php");
$blogcomment = new BlogCommentClass();

$blogcomment->action = $common->replaceEmpty('action',''); 

switch($blogcomment->action){ 

case 'blogcomment':$blogcomment->blogCommentDetails();  
break; 	
}
	
 
?>
