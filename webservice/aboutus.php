<?php

include_once('config.php'); 
 

  
$result=array();

 //$sql= "select * from ".$prefix."content as b left join ".$prefix."assets AS a ON a.id = b.asset_id WHERE b.asset_id =  '54' "; 

$sql= "SELECT b.title, b.introtext, b.fulltext, b.images, b.urls, b.catid
FROM ras_content AS b
LEFT JOIN ras_assets AS a ON a.id = b.asset_id
WHERE b.asset_id = '54'";  

$rs_username  = mysql_query($sql);

while($logindetails = mysql_fetch_assoc($rs_username)) {
 
$result['title'] = $logindetails['title']; 
//$result['introtext'] = htmlentities(mb_convert_encoding($logindetails['introtext'], 'HTML-ENTITIES', 'utf-8','auto'));
 
$result['introtext'] =  str_replace('&amp;','&', strip_tags(mb_convert_encoding($logindetails['introtext'],'utf-8')));
$result['fulltext'] =  str_replace('&amp;','&', strip_tags(mb_convert_encoding($logindetails['fulltext'],'utf-8')));  


//$result['introtext'] = $logindetails['introtext'];


//$result['introtext'] = htmlspecialchars($logindetails['introtext']);

//$result['fulltext'] = htmlspecialchars($logindetails['fulltext']);




$result['catid'] = $logindetails['catid'];

$paramsArray = array();



##########################################################################
 $sql_team = "select * from ".$prefix."modules  WHERE asset_id =  '64' and id='90' ";  

$rs_username_team  = mysql_query($sql_team);

$rows_data=mysql_fetch_assoc($rs_username_team);
//print_r($rows_data); die;

$paramsArray22 = array();
$paramsArray22 = json_decode($rows_data['params'], true);

//$result['tm_name1']    = $paramsArray22['tm_name1'];
//$result['tm_name2']    = $paramsArray22['tm_name2'];
//$result['tm_name3']    = $paramsArray22['tm_name3'];
//$result['tm_name4']    = $paramsArray22['tm_name4']; 
//$result['tm_disc1']    = $paramsArray22['tm_disc1'];
//$result['tm_disc2']    = $paramsArray22['tm_disc2'];
//$result['tm_disc3']    = $paramsArray22['tm_disc3']; 
//$result['tm_disc4']    = $paramsArray22['tm_disc4'];
//$result['tm_img1']    = $upload_fullpath.'/modules/mod_lan_our_team/frontend/images/about_1.jpg';
//$result['tm_img2']    = $upload_fullpath.'/modules/mod_lan_our_team/frontend/images/about_2.jpg';
//$result['tm_img3']    = $upload_fullpath.'/modules/mod_lan_our_team/frontend/images/about_3.jpg';
//$result['tm_img4']    = $upload_fullpath.'/modules/mod_lan_our_team/frontend/images/about_4.jpg';

 ############################################################################

					$paramsArray = json_decode($logindetails['images'], true);
					//print_r($paramsArray);
					$result['images']    = $upload_fullpath.$paramsArray['image_intro'];
					$paramsArray = array();
					$paramsArray = json_decode($logindetails['urls'], true);
					$result['urls']    = $paramsArray['image_intro'];
					$result['urla']    = $paramsArray['urla'];
					$result['urlatext']    = $paramsArray['urlatext'];
					$result['urlb']    = $paramsArray['urlb'];
					$result['urlbtext']    = $paramsArray['urlbtext'];
					$result['urlc']    = $paramsArray['urlc'];
					$result['urlctext']    = $paramsArray['urlctext'];
					
					$result['urls']      = $logindetails['urls'];


 
 
   
  
 }

 echo json_encode($result);	 
   
 
?>
