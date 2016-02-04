<?php
 
/**
 * Class Name 		        :	Find Location
	Description		:	This class  for Find Location
 * Author			:	vishal
 * Created on		        :	07-09-2013
 * 
 * */
 
 
Class ArticleClass{ 
	
 
		public function __construct(){
		
		}
		   
		   
			public function ArticleDetails12() 
			{
					global $dbObj,$common; 
					$action = $common->replaceEmpty('action','');
					$number = $common->replaceEmpty('number','');

                     					
				    $retvalArray = array();
					$finalArray=array();
				
			if ($action='articlelist'){  

			 if(!isset($number) || ($number == "" || $number == 0)){
					  $page = 1;
				 }	else{
						$page = $number;
				 }

				 
	$query= "SELECT ac.id AS cid, ac.title, ac.alias, ac.introtext, ab.params, ac.fulltext, ac.created, ac.images, ac.urls, ac.attribs
				FROM ras_categories AS ab
				LEFT JOIN ras_content AS ac ON ab.id = ac.catid 
				WHERE ab.id =  '27' and ac.state='1'
				ORDER BY ac.created DESC ";
							 
                                  // $sql = $dbObj->runQuery($query);	
				   //$total_records = mysql_num_rows($sql);
			           //$num_pages = ceil($total_records/4);
    				  // $start =  4* ($page-1);
			           //$end = 4; 
				  // $query .= "limit $start,$end";
                                      
				  //$rs = $dbObj->runQuery($query);
	
				     ######### FOr Android #################					
					 $sql = $dbObj->runQuery($query);	
                                         $total_records = mysql_num_rows($sql);

					$num_pages = ceil($total_records/4);
					//$start = 0; 
                                         //10 * ($page-1);
                                         $start =  4* ($page-1);
					 //$end = 4 * $page;
                                         $end = 4;
                                      $query .= " limit $start,$end ";

				         $rs 	= $dbObj->runQuery($query);
                                         //echo "num_pages".".$num_pages." ;
					 ########################################################	

		if (mysql_num_rows($rs) > 0){ 
				
			while($data=mysql_fetch_assoc($rs)){
				
                              // print_r($data); //strip_tags

                                $tempArray = array();
				 
				$tempArray['cid']     	= $data['cid'];
				//$tempArray['title']     = $data['title'];

$tempArray['title']= $data['title'];
  

$tempArray['title_short'] =  truncateWords(str_replace('&amp;','&', strip_tags(mb_convert_encoding($data['title'],'utf-8'))),5, "..."); 
 

$tempArray['alias']     = $data['alias'];

$serverrul= "http://".$_SERVER['HTTP_HOST'];

$tempArray['introtext'] =  str_replace('&amp;','&', strip_tags(mb_convert_encoding($data['introtext'],'utf-8'))); 

$tempArray['introtext_short'] =  truncateWords(str_replace('&amp;','&', strip_tags(mb_convert_encoding($data['introtext'],'utf-8'))), 20, "..."); 

//$tempArray['introtext_short'] =  truncateWords(strip_tags(mb_convert_encoding($data['introtext'],'utf-8')), 20, "...");
 
$created = date('d-m-Y', strtotime($data['created']));
$tempArray['created'] =date('d F Y', strtotime($created));

				$paramsArray = array();
				$paramsArray 			= json_decode($data['images'], true);
 
				$tempArray['images']    = $serverrul.'/'.$paramsArray['image_intro'];

				$paramsArray = array();
				$paramsArray 			= json_decode($data['urls'], true);
				
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
				 $finalArray[] = $tempArray;  
                                 
                                array_push($tempArray,$finalArray);
				 
				 
			  }  $dat = array('status'=>'1','num_pages'=>$num_pages,'result'=>$finalArray);

					echo json_encode($dat);exit;    

		      } else {
			 
				   $retvalArray['status'] = '0';  
				   echo json_encode($retvalArray);exit; 
				}
			}  
			  
			  
	       }

 #####################################################################

public function ArticleDetails_by_id() 
			{
					global $dbObj,$common; 
					$action = $common->replaceEmpty('action','');
					$cid = $common->replaceEmpty('cid',''); 

                     					
				        $retvalArray = array();
					$finalArray=array();
				 
			if ($action='articlelist_by_id'){  
  
	  $query= "SELECT ac.id AS cid, ac.title, ac.alias, ac.introtext, ab.params, ac.fulltext, ac.created, ac.images, ac.urls, ac.attribs
				FROM ras_categories AS ab
				LEFT JOIN ras_content AS ac ON ab.id = ac.catid 
				WHERE ab.id =  '27' and ac.id = '".$cid."'
				ORDER BY ac.created DESC ";
					 
			 $rs 	= $dbObj->runQuery($query); 
		if (mysql_num_rows($rs) > 0){ 
				
			while($data=mysql_fetch_assoc($rs)){ 

                                $tempArray = array();
				 
				$tempArray['cid']     	= $data['cid']; 

$tempArray['title']= $data['title'];  
$serverrul= "http://".$_SERVER['HTTP_HOST'];

//$tempArray['introtext'] =  strip_tags(mb_convert_encoding($data['introtext'],'utf-8'));

$tempArray['introtext'] = htmlspecialchars($data['introtext']);

//$tempArray['fulltext'] =  strip_tags(mb_convert_encoding($data['fulltext'],'utf-8'));

$tempArray['fulltext'] = htmlspecialchars($data['fulltext']);
 
$created = date('d-m-Y', strtotime($data['created']));
$tempArray['created'] =date('d F Y', strtotime($created));

				$paramsArray = array();
				$paramsArray 			= json_decode($data['images'], true);
 
				$tempArray['images']    = $serverrul.'/'.$paramsArray['image_intro'];

				$paramsArray = array();
				$paramsArray 			= json_decode($data['urls'], true);
				
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
				       $finalArray[] = $tempArray;  
                                 
                                      array_push($tempArray,$finalArray);
				 
				 
			              }  $dat = array('status'=>'1','num_pages'=>$num_pages,'result'=>$finalArray);

					echo json_encode($dat);exit;    

		                       } else {
			 
				         $retvalArray['status'] = '0';  
				         echo json_encode($retvalArray);exit; 
				        }
			        }  
			  
			  
	                        }

			   
						
} //Closing of Class

  
		
?>
		
		
