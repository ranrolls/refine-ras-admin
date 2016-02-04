<?php

header('Access-Control-Allow-Origin: *');


$userid=$_REQUEST["userid"];
$phoneno=$_REQUEST["phoneno"];

//$Jsoncallback=$_REQUEST["jsoncallback"];


$url= "http://ras.refine-dev.com/webservice/userregistration.php?action=editprofile";
 
$datatopost = array (
"userid" => $userid,
"phoneno" => $phoneno
);


 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
	
	header('Content-Type: application/json');
	echo $data;
	//echo $Jsoncallback . '(' . $data . ');';

?>