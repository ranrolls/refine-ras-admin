<?php

include_once('config.php');
include("resize-class.php");

$user_id = $_REQUEST["userid"]; 
$karma_time1	                = date("Y-m-d h:i:sa"); //date("Ymdis"); 
$karma_time   			= strtotime($karma_time1);  
############################################# 
 
//$image_name1 = 'users/avatar361.jpg1437719486'; 
  
//echo $edit_query = "UPDATE ras_kunena_users SET avatar='".$image_name1."' where userid='".$user_id."' ";  

//$rs_details = mysql_query($edit_query);	



if (($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png" ) || ($_FILES["file"]["size"] < 10000000000))
{


$uploaddir = '/home/rasmentor/public_html/media/kunena/avatars/users/';
		
$image_name = basename($_FILES["file"]["name"]).time(); 

$uploadfile = $uploaddir . $image_name; 

$image_name1 = 'users/'.$image_name; 
	 

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) { 
	
$edit_query = "UPDATE ".$prefix."kunena_users SET avatar='".$image_name1."' where userid='".$user_id."' ";  

$rs_details = mysql_query($edit_query);	
 
}


}

 
 
?>
 
 