<?php
include("include/config.php");


$blog = new BlogClass();

$blog->action = $common->replaceEmpty('action',''); 

switch($blog->action){ 

case 'blog':$blog->blogDetails();  
break; 	
 
}
	
 
?>
