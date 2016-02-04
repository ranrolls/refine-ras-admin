<?php

header('Access-Control-Allow-Origin: *');


$name		=	$_REQUEST["name"];
$email		=   $_REQUEST["email"]; 
$subject	= 	$_REQUEST["subject"];
$message	= 	$_REQUEST["message"];


//$name='vishal123';
//$email=  'vishal.k@refine-interactive.com';
//$subject= 'Hello123Test';
//$message= 'Hello123testmessage';

$datapost = array (
"name" => $name,
"email" => $email,
"subject" => $subject,
"message"=>$message
);



  $url= "http://ras.refine-dev.com/webservice/contact.php?action=contactUs";
	 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
		 
curl_setopt ($ch, CURLOPT_POSTFIELDS, $datapost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
		 

header('Content-Type: application/json');

echo $data;
	
 

?>








