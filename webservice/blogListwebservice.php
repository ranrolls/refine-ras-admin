<?php
include("include/config.php");


$blogList = new BlogListClass();

$blogList->action = $common->replaceEmpty('action',''); 


switch($blogList->action){ 

case 'blogList':$blogList->blogCategoryDetails();  
break; 	
 
}
	
 
?>
