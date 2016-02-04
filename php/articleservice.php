<?php

header('Access-Control-Allow-Origin: *');

 $number= $_REQUEST['number']; 
 
 
   $url= "http://ras.refine-dev.com/webservice/articlewebservice.php?action=articlelist&number=".$number;
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    header('Content-Type: application/json');
    echo $data; 
 
?>