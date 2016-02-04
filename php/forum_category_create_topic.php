<?php


header('Access-Control-Allow-Origin: *');

$catid 				= $_REQUEST['id'];
$name 				= $_REQUEST['name'];
$userid 			= $_REQUEST['userid'];
$subject			= $_REQUEST['subject'];
$message 			= $_REQUEST['message'];
  
   
$datatopost = array (

"id" => $catid,
"name" => $name,
"userid" => $userid,
"subject" => $subject,
"message"=>$message

);
  
 $url="http://ras.refine-dev.com/webservice/forum_category_create_topic.php"; 
 
  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
		 
curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch); 
header('Content-Type: application/json');
echo $data;
	 
?>