<?php

header('Access-Control-Allow-Origin: *');

  $forumId= $_REQUEST['forumId']; 
  $url= "http://ras.refine-dev.com/webservice/forum_category_lict_byId.php?action=forumcategorylistByid&forumId=".$forumId; 
 // $url= "http://localhost/newras/webservice/forum_category_lict_byId.php?action=forumcategorylistByid&forumId=".$forumId;  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    
    header('Content-Type: application/json');

    echo $data; 
 
?>