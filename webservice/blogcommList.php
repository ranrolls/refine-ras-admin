<?php
include("include/config.php");

$blog = new BlogCommListClass();

$blog->action = $common->replaceEmpty('action',''); 
print_r($blog->blogCommDetails());
switch($blog->action){ 

case 'blog':$blog->blogCommDetails();  
break; 	
 
}
	
 
?>
