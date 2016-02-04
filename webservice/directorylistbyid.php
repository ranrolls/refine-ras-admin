<?php

include_once('config.php');
  
 


//$sql= "SELECT * FROM ".$prefix."judirectory_categories WHERE parent_id = '1' AND level = '1' ORDER BY  id ASC";

//$rs_username  = mysql_query($sql);

//while($data = mysql_fetch_assoc($rs_username)) {
	 
 
			  
   $id  = $_REQUEST['id']; 
   $finalArray12=array();
   $sql12= "SELECT id as subid,title,introtext,images FROM ".$prefix."judirectory_categories WHERE parent_id = '".$id."' ";

			$rs_username12  = mysql_query($sql12);
                    if (mysql_num_rows($rs_username12) > 0){ 
			while($data12 = mysql_fetch_assoc($rs_username12)) {
				 
					$tempArray11 = array();	 
					$tempArray11 ['subid'] = $data12['subid']; 
					$tempArray11 ['title'] = $data12['title'];  
                          ###################################################################################
                                       
                                // $tempArray11['introtext'] = htmlspecialchars($data12['introtext']);
                                 //$tempArray11['listtext'] = strip_tags($data12['introtext']);  
  
				//$tempArray11['params']    = $data12['params'];
				//$tempArray11['fulltext']  = htmlspecialchars($data12['fulltext']); //strip_tags 
				//$tempArray11['created']   = $data12['created'];
				$paramsArray = array();
		// $paramsArray 			= json_decode($data12['images'], true);

 //$tempArray11['intro_image']    = $upload_fullpath.'/media/com_judirectory/images/category/detail/'.$paramsArray['intro_image'];
//$tempArray11['detail_image']    = $upload_fullpath.'/media/com_judirectory/images/category/detail/'.$paramsArray['detail_image'];
                              

				//$paramsArray = array();
				//$paramsArray 			= json_decode($data12['urls'], true);
				//$tempArray11['urls']   	= $paramsArray['image_intro'];
				//$tempArray11['urla']    	= $paramsArray['urla'];
				//$tempArray11['urlatext']   = $paramsArray['urlatext'];
				//$tempArray11['urlb']    	 = $paramsArray['urlb'];
				//$tempArray11['urlbtext']   = $paramsArray['urlbtext'];
				//$tempArray11['urlc']    	 = $paramsArray['urlc'];
				//$tempArray11['urlctext']   = $paramsArray['urlctext'];

				//$tempArray11['urls']      = $data12['urls'];
				//$tempArray11['attribs']   = $data12['attribs']; 

                                 $finalArray12[] = $tempArray11;
                                  array_push($tempArray11,$finalArray12);
                           #######################################################################################
				   
			  // } 
				   
			 } 
					//header('Content-Type: application/json; Charset=UTF-8');
					
					$dat = array('status'=>'1','result'=>$finalArray12);

					echo json_encode($dat);exit; 
			}
                                 else {
				   //header('Content-Type: application/json; Charset=UTF-8');
				   $dat = array('status'=>'0','result'=>'');

				  echo json_encode($dat);exit;  
				}		 
		    
?>
