<?php

//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
 
 
define( '_JEXEC', 1 );
define( 'JPATH_BASE', str_replace('/webservice','',dirname(__FILE__)) ); 	# This is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
//$mainframe = & JFactory::getApplication('site');
//$mainframe->initialise(); 

jimport('joomla.user.helper');


include_once("config.php");

 
$action = $_REQUEST['action']; 
$username = $_REQUEST['username'];
$userpassword = $_REQUEST['password'];
//header('Content-Type: application/json');

if($action='login'){
	
  
 $result=array(); 

 ############################ CHECKING USERNAME EXIST OR NOT ##################

		  $sql_username = "SELECT * from ".$prefix."users where username = '".$username."'  ";
								
			$rs_username  = mysql_query($sql_username);

			if($rows_username = mysql_fetch_assoc($rs_username)){ 
			$dbpassword = $rows_username['password']; 
				  
		if(JUserHelper::verifyPassword($userpassword, $rows_username['password'], $rows_username['id'])){
			
		$datelogged = date('Y-m-d H:i:s');
//$sqlLog = "INSERT INTO ".$prefix."user_visit_log SET userID='".$rows_username['id']."', useFrom = 'Mobile', dateLogged='".$datelogged."'";
//mysql_query($sqlLog);

############# FOr getting Device Logged /visited with date ################

 if($_REQUEST['devicetoken'] <> ""){

$deviceplatform = strtolower($_REQUEST['deviceplatform']);

if($deviceplatform == "android"){

$rs_user_device = mysql_query(" SELECT androidtoken,deviceplatform,devicemodel,deviceversion from ".$prefix."user_tokens where userid = ".$rows_username['id']." ");
if(mysql_num_rows($rs_user_device) > 0 ){

mysql_query("UPDATE ".$prefix."user_tokens set androidtoken = '".$_REQUEST['devicetoken']."',devicemodel='".$_REQUEST['devicemodel']."',
deviceversion='".$_REQUEST['deviceversion']."' where userid = ".$rows_username['id']." ");
}
else{
mysql_query("INSERT into  ".$prefix."user_tokens (userid,androidtoken,deviceplatform,devicemodel,deviceversion,dateLogged) values (".$rows_username['id'].",'".$_REQUEST['devicetoken']."','".$_REQUEST['deviceplatform']."','".$_REQUEST['devicemodel']."','".$_REQUEST['deviceversion']."',now() ) ");

} 

}

}


################### End Here ###########################################
		
// $result=$rows_username; 

$result['id']=$rows_username['id'];
$result['name']=$rows_username['name'];
$result['email']=$rows_username['email'];
$result['phoneno']=$rows_username['phoneno'];
$result['registerDate']=$rows_username['registerDate'];
$result['block']=$rows_username['block'];
   

$sql_username_image = "SELECT * from ".$prefix."kunena_users where userid= '".$result['id']."' ";
								
 $rs_username_image  = mysql_query($sql_username_image);

 
if($rows_username_image = mysql_fetch_assoc($rs_username_image)){ 
$result['personalText']= $rows_username_image['personalText'];
$result['location'] = $rows_username_image['location'];

if($rows_username_image['avatar']!= "") {

$result['avatar'] = $save_forum_user_image_path . $rows_username_image['avatar']; 
}
else {
			$userimage   = $upload_fullpath.'/images/s_nophoto.jpg';
			$result['avatar'] =$userimage;
}
 

 }
    $dat = array('status'=>'1','result'=>$result);

        echo json_encode($dat);
				 
			}else{
				
				$dat = array('status'=>'0','result'=>'');
				  echo json_encode($dat);
				 
			}

			}
			
			else{
				
				$dat = array('status'=>'0','result'=>'');
				  echo json_encode($dat);
			}
		
		
		
}//close action

 
 
?>
