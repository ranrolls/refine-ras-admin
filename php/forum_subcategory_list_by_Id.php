<?php

   header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
  $threadId= $_REQUEST['threadId']; 
   // $url= "http://localhost/newras/webservice/forum_subcategory_list_byId.php?action=forumsubcategorylistByid&threadId=".$threadId; 
    $url= "http://ras.refine-dev.com/webservice/forum_subcategory_list_byId.php?action=forumsubcategorylistByid&threadId=".$threadId;  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
 
    echo $data; 
 
?>