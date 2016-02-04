<?php

include_once('config.php');
  
$retvalArray = array();
$finalArray=array();

$sql= "SELECT * FROM ".$prefix."judirectory_categories WHERE parent_id = '1' AND level = '1' ORDER BY  lft ASC ";

$rs_username  = mysql_query($sql);

if (mysql_num_rows($rs_username) > 0){ 

while($data = mysql_fetch_assoc($rs_username)) {
	
				$tempArray = array();	
 
				$tempArray['id']     		= $data['id'];
				$tempArray['title']     	= $data['title'];
				$tempArray['parent_id']         = $data['parent_id'];
				$tempArray['level']     	= $data['level'];
$paramsArray = array();
$paramsArray 	             = json_decode($data['images'], true); 
$tempArray['intro_image']    = $upload_fullpath.'media/com_judirectory/images/category/detail/'.$paramsArray['intro_image'];
$tempArray['detail_image']   = $upload_fullpath.'media/com_judirectory/images/category/detail/'.$paramsArray['detail_image'];
$finalArray[] = $tempArray;  
				   
			  } 
				    // header('Content-Type: application/json; Charset=UTF-8');
					
					$dat = array('status'=>'1','result'=>$finalArray);

					echo json_encode($dat);exit;  
                           } 

                                else {
				  // header('Content-Type: application/json; Charset=UTF-8');
				   $dat = array('status'=>'0','result'=>'');

				  echo json_encode($dat);exit;  
				}	
			
		    
?>
