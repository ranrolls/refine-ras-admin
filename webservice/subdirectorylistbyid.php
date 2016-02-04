<?php

include_once('config.php');
  
				
  
				$subid  = $_REQUEST['subid']; 
                                $finalArray12=array();
				
				
$sql12= "SELECT * FROM ".$prefix."judirectory_listings AS a LEFT JOIN ".$prefix."judirectory_listings_xref AS b ON a.id = b.listing_id
            WHERE b.cat_id = '".$subid."' ";


				$rs_username12  = mysql_query($sql12);
				
				if (mysql_num_rows($rs_username12) > 0){ 
					while($data12 = mysql_fetch_assoc($rs_username12)) { 
                  						
					$tempArray11 = array();	 
					$tempArray11['listing_id'] 	= $data12['listing_id']; 
					$tempArray11['cat_id'] 		= $data12['cat_id'];  
					$tempArray11['id'] 			= $data12['id'];   
					$tempArray11['title'] 		= $data12['title'];  
                                        //$tempArray11['listtext'] = strip_tags($data12['introtext']);   

   //$tempArray11['image'] = $upload_fullpath.'/media/com_judirectory/images/listing/'.$data12['image'];  
  
					//$tempArray11['image'] 		= $upload_fullpath.$data12['image'];   

					//$tempArray11['email'] 		= $data12['email'];  
 
					//$tempArray11['url'] 		=  $data12['url'];   
					//$tempArray11['telephone'] 	= $data12['telephone'];   
					//$tempArray11['rating'] 		= $data12['rating'];   
					//$tempArray11['created']   	= $data12['created']; 
		//$tempArray11['introtext'] 	= htmlspecialchars($data12['introtext']);

		  // $tempArray11['fulltext']  	= htmlspecialchars($data12['fulltext']); 	
					
					###################################################################################  
					/*$paramsArray = array(); 
					$paramsArray 			= json_decode($data12['images'], true);
					$tempArray11['images']    = $upload_fullpath.$paramsArray['image_intro'];
					$paramsArray = array();
					$paramsArray 			= json_decode($data12['urls'], true);
					$tempArray11['urls']   	        = $paramsArray['image_intro'];
					$tempArray11['urla']    	= $paramsArray['urla'];
					//$tempArray11['urlatext']        = $paramsArray['urlatext'];
					//$tempArray11['urlb']    	= $paramsArray['urlb'];
					//$tempArray11['urlbtext']        = $paramsArray['urlbtext']; 
					//$tempArray11['urlc']    	= $paramsArray['urlc'];
					//$tempArray11['urlctext']        = $paramsArray['urlctext'];

					$tempArray11['urls']      = $data12['urls'];
					$tempArray11['attribs']   = $data12['attribs']; */
					
					$finalArray12[] = $tempArray11;
					
					array_push($tempArray11,$finalArray12);
				   #######################################################################################
				     
			 } 
					header('Content-Type: application/json; Charset=UTF-8'); 
					$dat = array('status'=>'1','result'=>$finalArray12);

					echo json_encode($dat);exit; 
			}
                 else {
				   header('Content-Type: application/json; Charset=UTF-8');
				   $dat = array('status'=>'0','result'=>''); 
				   echo json_encode($dat);exit;  
				}		 
		    
?>
