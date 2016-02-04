<?php


header('Access-Control-Allow-Origin: *');

$userName= $_REQUEST["userName"]; 
$commentEmail=   $_REQUEST["commentEmail"]; 
$commentURL= $_REQUEST["commentURL"];
$commentText= $_REQUEST["commentText"];
$id = $_REQUEST['id'];
  
//$Jsoncallback=$_REQUEST['jsoncallback'];
 
$datatopost = array (
"userName" => $userName,
"commentEmail" => $commentEmail,
"commentURL" => $commentURL,
"commentText"=>$commentText
);
$url="http://ras.refine-dev.com/webservice/bloginsertcomment.php?action=insertcomment&id=".$id; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
		 
curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
	 
//print_r($data);
	header('Content-Type: application/json');
	
	echo $data;
	
//echo $Jsoncallback . '(' . $data . ');'; 
	
?>