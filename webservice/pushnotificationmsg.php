<?php
  
include_once("config.php");

  
$action = $_REQUEST['action'];
  
if($action='save_device_token'){
	 
 $result=array(); 
  
############# FOr getting Device Logged /visited with date ################
  
$deviceplatform = strtolower($_REQUEST['deviceplatform']);
 
if($deviceplatform == "android"){

$rs_user_device = mysql_query("SELECT * from ".$prefix."mobile_device_tokens  where token= '".$_REQUEST['devicetoken']."' ");

if(mysql_num_rows($rs_user_device) > 0 ){
#################################################
while($rows_get_token = mysql_fetch_assoc($rs_user_device)){
$androidtoken                           = $rows_get_token['token']; 
$comment 				= $rows_get_token['message']; 
$read      				= $rows_get_token['readstatus'];

}

if($read=='0'){	
 $dat_unread = array('message'=>$comment,'status'=>$read);
 echo json_encode($dat_unread);
} 

else {
$dat_read = array('message'=>'','status'=>$read);
echo json_encode($dat_read);
}


}
 
##########################################################
	
/*											
$curlhandle = curl_init();	
curl_setopt($curlhandle, CURLOPT_URL, $upload_fullpath_short.$webservicefolder."test_gcm_server/send_message.php?regId=".$androidtoken."&mode=sendmessage&message=".urlencode($comment));		
curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 0);	
$response = curl_exec($curlhandle);	
curl_close($curlhandle);*/


#####################################################

else{
  
mysql_query("INSERT into  ".$prefix."mobile_device_tokens (token,deviceplatform,devicemodel,deviceversion,dateLogged,deviceid,readstatus,message) values ('".$_REQUEST['devicetoken']."','".$deviceplatform."','".$_REQUEST['devicemodel']."','".$_REQUEST['deviceversion']."',now(),'','0','') ");


$dat31 = array('message'=>'','status'=>'0');
echo json_encode($dat31);
}

 
}
 
#############################################

else if($deviceplatform == "ios"){
 
$rs_user_device1 = mysql_query("SELECT * from ".$prefix."mobile_device_tokens  where token= '".$_REQUEST['devicetoken']."' ");

if(mysql_num_rows($rs_user_device1) > 0 ){

while($rows_get_token1 = mysql_fetch_assoc($rs_user_device1)){
$androidtoken1                           = $rows_get_token1['token']; 
$comment1 				= $rows_get_token1['message']; 
$read1      				= $rows_get_token1['readstatus'];

}

if($read1=='0'){	
 $dat_unread1 = array('message'=>$comment1,'status'=>'0');
 echo json_encode($dat_unread1);
} 

else {
$dat_read1 = array('message'=>'','status'=>'1');
echo json_encode($dat_read1);
}


}



else{
  
mysql_query("INSERT into  ".$prefix."mobile_device_tokens (token,deviceplatform,devicemodel,deviceversion,dateLogged,deviceid,readstatus,message) values ('".$_REQUEST['devicetoken']."','".$deviceplatform."','".$_REQUEST['devicemodel']."','".$_REQUEST['deviceversion']."',now(),'','0','') ");

$dat34 = array('message'=>'','status'=>'0');
echo json_encode($dat34);
  
}

 
 	
}


}//close action
 
 
 
 
?>
