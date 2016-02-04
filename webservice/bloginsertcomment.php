<?php
//error_reporting(E_ALL);
include("include/config.php");

$blogInsert = new BlogUserComment();
	  
$blogInsert->action = $common->replaceEmpty('action','');
print_r($blogInsert->BlogCommentInsert());
switch($blogInsert->action){

case 'blogInsert':$blogInsert->BlogCommentInsert();
break;	 
}



   
?>
