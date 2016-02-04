<?php

header('Access-Control-Allow-Origin: *');

$user=$_REQUEST["username"];
//$user=$_REQUEST["email"];

$password=$_REQUEST["password"];

//$Jsoncallback=$_REQUEST['jsoncallback'];
  
// $url="http://ras.refine-dev.com/newras/webservice/login.php?action=login&email=".$user."&password=".$password;
 
 $url="http://ras.refine-dev.com/webservice/login.php?action=login&username=".$user."&password=".$password;  
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $data = curl_exec($ch);

//var_dump($data);

//echo $Jsoncallback . '(' . $data . ');';
header('Content-Type: application/json');

echo $data;

//echo json_encode($data);	
	
	
?>