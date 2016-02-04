<?php

include_once("config.php");
 

$userid = $_REQUEST['userid'];

$result=array(); 

//header('Content-Type: application/json; Charset=UTF-8');

$sql_username_image = "SELECT * from ".$prefix."kunena_users where userid= '".$userid."' ";
								
$rs_username_image  = mysql_query($sql_username_image); 

if($rows_username_image = mysql_fetch_assoc($rs_username_image)){ 
   $result['userid'] = $rows_username_image['userid'];
  if($rows_username_image['avatar']!= "") {

      $result['avatar'] = $save_forum_user_image_path . $rows_username_image['avatar']; 
   }
   else {
   $userimage        = $upload_fullpath.'/images/s_nophoto.jpg';
   $result['avatar'] = $userimage;
    }
}

   echo json_encode($result);


 

?>