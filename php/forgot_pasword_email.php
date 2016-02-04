<?php


header('Access-Control-Allow-Origin: *');

$email=$_REQUEST["email"];

//$Jsoncallback=$_REQUEST['jsoncallback'];
 
$url="http://ras.refine-dev.com/webservice/forgotpassword.php?email=".$email; 
 
 
  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
	
	header('Content-Type: application/json');
	
	echo $data;
	//echo $Jsoncallback . '(' . $data . ');'; 
	
?>