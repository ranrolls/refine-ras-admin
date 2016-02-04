<?php

include_once('config.php');
  
$result=array();

  $sql= "SELECT * FROM ras_app_version WHERE 1";  

$rs_username  = mysql_query($sql);

while($logindetails = mysql_fetch_assoc($rs_username)) {
 
  $result['package_name'] = $logindetails['package_name'];  
 
  $result['version']       = $logindetails['version']; 

//$result['introtext'] = htmlentities(mb_convert_encoding($logindetails['introtext'], 'HTML-ENTITIES', 'utf-8','auto'));
 
  $result['details'] = htmlspecialchars($logindetails['details']);

}


echo json_encode($result);	 
   
 

?>
