<?php


header('Access-Control-Allow-Origin: *');
  

$userid  = $_REQUEST['userid'];

//$_FILES['image'];

$url= "http://ras.refine-dev.com/webservice/upload.php?action=uploadphoto&userid=".$userid;


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

     $data = curl_exec($ch);
     header('Content-Type: application/json');
     echo $data;
  
	
?>