<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
  $blogid= $_REQUEST['blogid']; 
  $url= "http://ras.refine-dev.com/webservice/blogcommList.php?action=bloglist&blogid=".$blogid;  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    
    

    echo $data; 
 
?>