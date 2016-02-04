<?php
 
 
 
Class AddreceiptClass{
	
 
		public function __construct(){
		
		}
		  
		   
			public function ArticleDetails()
			{
					global $dbObj,$common; 
					$action = $common->replaceEmpty('action',''); 
					$number = $common->replaceEmpty('number',''); 
				        $retvalArray = array();
				        $finalArray1=array();

			$serverrul= "http://".$_SERVER['HTTP_HOST'];
		
			if ($action='newsdetails'){

					if(!isset($number) || ($number == "" || $number == 0)){
					$page = 1;
					}	else{
					$page = $number;
					}
 
	  $query = "SELECT ac.id as cid,ac.title,ac.alias,ac.introtext,ab.params,
		ac.fulltext,ac.created,ac.images,ac.urls,ac.attribs FROM ras_categories as ab left join ras_content as
		ac on ab.id = ac.catid where ab.id = '11' and ac.state='1' order by ac.created desc "; 

					
					######### FOr Android Pagination #################	
                 					
					$sql = $dbObj->runQuery($query);	
					$total_records = mysql_num_rows($sql);

					$num_pages = ceil($total_records/10);

                                        //$start = 0; //10 * ($page-1);
                                          $start =  10* ($page-1);
                                         //$end = 20 * $page;
                                         $end = 10;
                                     
					$query .= " limit $start,$end ";

					  $rs1 	= $dbObj->runQuery($query);
					
			 
					########################################################	

if (mysql_num_rows($rs1) > 0){ 

while($data1=mysql_fetch_assoc($rs1)){
 
$tempArray1 = array();

  $tempArray1['cid']     		= $data1['cid']; 
 

 $tempArray1['title_short1']= htmlspecialchars (truncateWords(strip_tags(mb_convert_encoding($data1['title'],'utf-8')), 10, "..."));

$tempArray1['title_short'] = str_replace('&amp;','&', $tempArray1['title_short1']);
 

  $tempArray1['introtext_short'] =  htmlspecialchars (truncateWords(strip_tags(mb_convert_encoding($data1['introtext'],'utf-8')), 20, "..."));

  $created = date('d-m-Y', strtotime($data1['created']));
  $tempArray1['created'] =date('d F Y', strtotime($created));

$paramsArray1 = array();
$paramsArray1 				= json_decode($data1['urls'], true);
						
if($paramsArray1['urlb']!=''){
  $tempArray1['urlb']    	 = $serverrul.'/'.$paramsArray1['urlb'];
}else{
$tempArray1['urlb']    	 = $serverrul.'/images/5.jpg';

}

$finalArray1[] = $tempArray1;  

array_push($tempArray1,$finalArray1);	

			 
} 
					 
$dat1 = array('status'=>'1','num_pages'=>$num_pages,'result'=>$finalArray1);

//header( 'Content-Type: text/html; charset=utf-8' ); 
echo json_encode($dat1);
exit;

} 

else { 
$finalArray1['status'] = '0';  
echo json_encode($finalArray1);exit; 
}


} 

 	  
			  
}//function close

############################## News Details by invidual id ###############################################

public function ArticleDetails_by_id()

{
global $dbObj,$common; 
$action = $common->replaceEmpty('action','');
 
$cid = $common->replaceEmpty('cid',''); 
 
$finalArray2=array();

$serverrul= "http://".$_SERVER['HTTP_HOST'];
		
if ($action='newsdetails_by_id'){

  $query = "SELECT ac.id as cid,ac.title,ac.alias,ac.introtext,ab.params,
		ac.fulltext,ac.created,ac.images,ac.urls,ac.attribs FROM ras_categories as ab left join ras_content as
		ac on ab.id = ac.catid where ab.id = '11' AND ac.id = '".$cid."' ";  
  
$rs 	= $dbObj->runQuery($query);
	 
if (mysql_num_rows($rs) > 0){ 

while($data=mysql_fetch_assoc($rs)){

$tempArray = array();
				 
  $tempArray['cid']     		= $data['cid'];
							
$tempArray['title']           = $data['title'];
  
$tempArray['introtext']         =  strip_tags(mb_convert_encoding($data['introtext'],'utf-8'));

$tempArray['fulltext'] 		=  strip_tags(mb_convert_encoding($data['fulltext'], 'utf-8'));



$created = date('d-m-Y', strtotime($data['created']));
$tempArray['created'] =date('d F Y', strtotime($created));
 
$paramsArray = array();
$paramsArray 			= json_decode($data['images'], true);
 
$paramsArray = array();
$paramsArray 				= json_decode($data['urls'], true);

if($paramsArray['urlc']!=''){
$tempArray['urlc']    	 = $serverrul.'/'.$paramsArray['urlc'];
}else{
$tempArray['urlc']    	 = $serverrul.'/images/5.jpg';

} 
$finalArray2[] = $tempArray;  

array_push($tempArray,$finalArray2);				 
				 
} 
					 
$dat = array('status'=>'1','result'=>$finalArray2);

echo json_encode($dat);exit;    
} else { 
$finalArray['status'] = '0';  
 echo json_encode($finalArray2);exit; 
}
}  
	
		  
}

		     
 ################# End Here ###############################################					
} //Closing of Class

 
		
?>
		
		
