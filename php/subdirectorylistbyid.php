<?php

 $subid= $_REQUEST['subid']; 
header('Access-Control-Allow-Origin: *');


  
 $url= "http://ras.refine-dev.com/webservice/subdirectorylistbyid.php?subid=".$subid;
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    header('Content-Type: application/json');
    echo $data; 
     
?>

