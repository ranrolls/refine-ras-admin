<?php

include_once('config.php'); 
 

$action = $_REQUEST['action'];
$number = $_REQUEST['number'];

$cid= $_REQUEST['cid'];


$retvalArray = array();
$finalArray=array();

  
    
  if ($action=='search_category'){  
    
 $query= mysql_query("SELECT id,parent_id,title FROM `ras_categories` where id in('11','27') ");

 $query1= mysql_query("SELECT id,title,parent_id FROM `ras_judirectory_categories` WHERE  id = '1' ");//parent_id = '1' 
 
 
$tempArray = array();
$tempArray1 = array();
 
 
 while ($data = mysql_fetch_array($query, MYSQL_ASSOC)){
    $tempArray[] = $data;
}
 

while ($data1 = mysql_fetch_assoc($query1, MYSQL_ASSOC)){
    $tempArray1[] = $data1;
    
}
 
//array_push($tempArray, $tempArray1);
 
############## End Here #################
 
$finalArray['0'] = $tempArray;  

$finalArray['1'] = $tempArray1;  
 
//$finalArray['directory_category'] = $tempArray1;  
 
$dat = array('status'=>'1','result'=>$finalArray);

echo json_encode($dat);exit;  
 
}   
  
 ################### For Details based on Catid and Text keywords ###################
 
 else if($action =="textsearch"){

$number = $_REQUEST['number'];

 if(!isset($number) || ($number == "" || $number == 0)){
  $page = 1;
 }	else{
 $page = $number;

}
			$retvalArray = array();
			$finalArray=array();
			
			$keywords=$_REQUEST['keywords'];
			
			$catid=$_REQUEST['id'];
			
			if(!empty($keywords)){
  
			$searchcond="(title LIKE'%$keywords%' OR introtext  Like'%$keywords%')";	 
			 $searchcond1="(title LIKE'%$keywords%' OR introtext  Like'%$keywords%')";	
			 
		     $checkin_details = "SELECT * FROM `ras_content`  WHERE  ".$searchcond." and catid = '".$catid."' ";
		     
		     //echo "SELECT * FROM `ras_content`  WHERE  ".$searchcond." and catid = '".$catid."' ";
		  //echo "SELECT * FROM `ras_judirectory_listings_xref` as a left join ras_judirectory_listings as b on a.listing_id=b.id  where  ".$searchcond." and a.cat_id= '".$catid."' ";
		   
######### For Pagination #################					
$sql = mysql_query($checkin_details);	
$total_records = mysql_num_rows($sql);
$num_pages = ceil($total_records/20); 
$start =  20* ($page-1);
$end = 20;
$checkin_details .= " limit $start,$end ";
$rs 	= mysql_query($checkin_details);

########################################################	
 while($rows_details = mysql_fetch_assoc($rs)){
				 $tempArray = array();
				$tempArray['id'] = $rows_details['id'];
				$tempArray['catid'] = $rows_details['catid'];
				$tempArray['title'] = $rows_details['title']; 
$tempArray['introtext_short'] =  truncateWords(str_replace('&amp;','&', strip_tags(mb_convert_encoding($rows_details['introtext'],'utf-8'))), 20, "..."); 
 $finalArray[]    = $tempArray;
 }
	
  
 // $query1= "SELECT * FROM `ras_judirectory_listings_xref` as a left join ras_judirectory_listings as b on a.listing_id=b.id  where  ".$searchcond1." and a.cat_id= '".$catid."' ";
 
  $query1= "SELECT * FROM `ras_judirectory_listings_xref` as a left join ras_judirectory_listings as b on a.listing_id=b.id  where  ".$searchcond1." and a.main= '".$catid."' ";



######### For Pagination #################					
$sql1 = mysql_query($query1);	
$total_records1 = mysql_num_rows($sql1);
$num_pages1 = ceil($total_records1/20); 
$start1 =  20* ($page-1);
$end1 = 20;
 $query1 .= " limit $start1,$end1 ";
$rs1 	= mysql_query($query1);

########################################################	   
                 		                 
################ for Directory Search ####################
			 while($rows_details1 = mysql_fetch_assoc($rs1))
				{ 	$tempArray1 = array();	
				$tempArray1['id'] = $rows_details1['id']; 
				$tempArray1['catid'] = $rows_details1['cat_id'];
				$tempArray1['title'] = $rows_details1['title']; 
$tempArray1['introtext_short'] =  truncateWords(str_replace('&amp;','&', strip_tags(mb_convert_encoding($rows_details1['introtext'],'utf-8'))), 20, "..."); 
				 $finalArray[]    = $tempArray1;
				  //array_push($tempArray, $tempArray);
		               } 
		               
			################ End Here ########
			
                           // $finalArray['content_search']    = $tempArray;
                           // $finalArray['directory_search']  = $tempArray1;
                        
		             $dat = array('status'=>'1','result'=>$finalArray);

		            echo json_encode($dat);exit;      			
			 }
		}
  

 ################### End Here #############################
################# Search Details by article id and Category id ####

 else if($action =="search_result"){

			$retvalArray = array();
			$finalArray=array();
			
			//$catid=$_REQUEST['catid'];
			$id=$_REQUEST['id'];
			$serverrul= "http://".$_SERVER['HTTP_HOST'];
 
		     $checkin_details = mysql_query("SELECT * FROM `ras_content` WHERE  id='".$id."' ");
		      
                     $query1= mysql_query("SELECT * FROM  ras_judirectory_listings where id='".$id."'  ");
                 
             
			 while($rows_details = mysql_fetch_assoc($checkin_details)){
				 $tempArray = array();

				$tempArray['id']    = $rows_details['id'];
				//$tempArray['catid'] = $rows_details['catid'];
				$tempArray['title'] = $rows_details['title']; 
$tempArray['introtext'] =  str_replace('&amp;','&', strip_tags(mb_convert_encoding($rows_details['introtext'],'utf-8'))); 
 
$tempArray['fulltext'] = htmlspecialchars($data['fulltext']);

$paramsArray = array();
			$paramsArray 			= json_decode($rows_details['images'], true);

			$tempArray['images']    = $serverrul.'/'.$paramsArray['image_intro'];

			$paramsArray = array();
			$paramsArray 			= json_decode($rows_details['urls'], true);
				
			if($paramsArray['urlb'] != ''){
				$tempArray['urlb']    	    = $serverrul.'/'.$paramsArray['urlb'];
			}
			else
			{
				$tempArray['urlb']    	    = $serverrul.'/images/5.jpg';
			}

			if($paramsArray['urlc'] != ''){
			$tempArray['urlc']    	    = $serverrul.'/'.$paramsArray['urlc'];
			}
			else
			{
			$tempArray['urlc']    	    =$serverrul.'/images/5.jpg';
			} 


 $finalArray[]    = $tempArray;
}
		                 
################ for Directory Listing ####################
while($rows_details1 = mysql_fetch_assoc($query1))
{ 	$tempArray1 = array();	
$tempArray1['id'] = $rows_details1['id']; 
//$tempArray1['catid'] = '';
$tempArray1['title'] = $rows_details1['title']; 

$tempArray1['introtext'] =  str_replace('&amp;','&', strip_tags(mb_convert_encoding($rows_details1['introtext'],'utf-8'))); 
 
$tempArray1['fulltext'] = htmlspecialchars($rows_details1['fulltext']);

$tempArray['images']='';
$tempArray['urlb']='';
$tempArray['urlc']='';

$finalArray[]    = $tempArray1;
//array_push($tempArray, $tempArray);
} 
		               
			################ End Here ########
			 
		             $dat = array('status'=>'1','result'=>$finalArray);

		            echo json_encode($dat);exit;      			
			 }
		//}


 
################ End HERE #######################################



?>
