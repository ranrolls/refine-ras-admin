<?php


header('Access-Control-Allow-Origin: *');

$userid=$_REQUEST["userid"];
$newpassword=$_REQUEST["newpassword"];

 
   $url="http://ras.refine-dev.com/webservice/forgotpassword.php?mode=setpassword&userid=".$userid."&newpassword=".$newpassword; 
  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
header('Content-Type: application/json');
	echo $data;
	
?>