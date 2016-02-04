<?php

header('Access-Control-Allow-Origin: *');


$username	= $_REQUEST["username"];
$token		= $_REQUEST["token"];
 
 
  $url="http://ras.refine-dev.com/webservice/forgotpassword.php?mode=verifytoken&username=".$username."&token=".$token; 
  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
   header('Content-Type: application/json');
	echo $data;
	  
?>