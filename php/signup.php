<?php


header('Access-Control-Allow-Origin: *');

$name=$_REQUEST["name"];
$username= $_REQUEST["username"]; 
$email=   $_REQUEST["email"]; 
$password= $_REQUEST["password"];
$phoneno= $_REQUEST["phoneno"];
$country= $_REQUEST["country"];
$company= $_REQUEST["company"];
  
//$Jsoncallback=$_REQUEST['jsoncallback'];
 
$datatopost = array (

"name" => $name,
"username" => $username,
"email" => $email,
"password" => $password,
"phoneno"=>$phoneno
"company"=>$company
"country"=>$country

);
 
  // name=vishalkumar&username=vishal123&email=vishal.k@refine-interactive.com&password=123456
 
$url="http://egghuntsg.com/webservice/userregistration.php?action=userregistration"; 
	
/*
foreach($emp as $val)
{
$emp_st.="&employment_status[]=".$val;
//echo $category;
}*/

 

//echo 'URL = '.$url;

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