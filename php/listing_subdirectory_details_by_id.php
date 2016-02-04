<?php


 $listing_id= $_REQUEST['listing_id']; 
header('Access-Control-Allow-Origin: *');

  
 $url= "http://ras.refine-dev.com/webservice/listing_subdirectory_details_by_id.php?listing_id=".$listing_id;
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    header('Content-Type: application/json');
    echo $data; 
     
?>

