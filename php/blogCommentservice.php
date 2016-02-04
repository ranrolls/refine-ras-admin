<?php

   header('Access-Control-Allow-Origin: *');

 $number= $_REQUEST['number'];
  $id= $_REQUEST['id']; 
 


  $url= "http://ras.refine-dev.com/webservice/blogCommentwebservice.php?action=blogcomment&id=".$_REQUEST['id'];  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    
    header('Content-Type: application/json');

    echo $data; 
 
?>