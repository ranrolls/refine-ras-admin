<?php

include_once('config.php');
  
$number = $_REQUEST['number']; 
 
$finalArray=array();

$resultarray = array();

$serverrul= "http://".$_SERVER['HTTP_HOST'];

############# For Content Update in F&B Startup KIT ##################

$query_details = "select * from ".$prefix."content where id= '19' ";  
$rs_details = mysql_query($query_details);
while($data = mysql_fetch_assoc($rs_details)){
  
$title 	        =  $data['title'];

$introtext      =  str_replace('{loadmodule mod_fandb}','',$data['introtext']);

$introtext2      =  str_replace('The standard chunk of Lorem Ipsum used since the 1500s','',$introtext);

$introtext3 = str_replace('\r','',$introtext2); 

$introtext1 = htmlspecialchars($introtext3);

//$introtext1 = htmlspecialchars($data['introtext']);

###############################################################
//$introtext4 = str_replace('{source}&lt;? if($user-&gt;username == ','',strip_tags($introtext3)); 

//$introtext5 = str_replace('{?&gt;&lt;?','',strip_tags($introtext4)); 

//$introtext6 = str_replace('\r','',strip_tags($introtext5));
//$introtext7 = str_replace('header(','',strip_tags($introtext6));
//$introtext8 = str_replace('f-b-startup-kit-login','',strip_tags($introtext7));
//$introtext9 = str_replace('header)?&gt&lt? } else {?&gt',strip_tags($introtext8));
//$introtext10 = str_replace('&lt? } ?&gt{\/source}','',strip_tags($introtext9));
 //{source}&lt;? if($user-&gt;username == '') {?&gt;&lt;? header('location:\/f-b-startup-kit-login');?&gt;&lt;? } 
   
//$introtext1 = htmlspecialchars($data['introtext']);

//$introtext1 = preg_replace('/\?\//', '?', $introtext10);
 
//$introtext1 = strip_tags(mb_convert_encoding($data['introtext1'],'utf-8'));

//$fulltext 	=  strip_tags(mb_convert_encoding($data['fulltext'],'utf-8'));

###################################################################

$paramsArray1 = array();
$paramsArray1 				= json_decode($data['images'], true);
$image_fulltext_caption                 = $paramsArray1['image_fulltext_caption'];

$paramsArray = array();
$paramsArray 				= json_decode($data['urls'], true);
$images                                 = $serverrul.'/'.$paramsArray['urlc'];

}

###################### End Here #################################



$query= "select * from ".$prefix."fandbstartup_fb where state = '1' ORDER BY id ASC  ";   

	if(!isset($number) || ($number == "" || $number == 0)){
		$page = 1;
	}	else{
		$page = $number;
	}

$sql = mysql_query($query);	
$total_records = mysql_num_rows($sql);
$num_pages = ceil($total_records/10);
$start = 10 * ($page-1);
$end = 10; 
$query .= "limit $start,$end";  
$rs = mysql_query($query);
 
if (mysql_num_rows($rs) > 0){ 
				
while($data=mysql_fetch_assoc($sql)){
$tempArray = array();

$tempArray['id']     	= $data['id'];
$tempArray['title']     = $data['title'];
$tempArray['filetype']  = $upload_fullpath.'images/fnb/'.$data['filetype'];

$finalArray[] = $tempArray;
 

array_push($tempArray,$finalArray);

}
  
$dat = array('status'=>'1','title'=>$title,'introtext'=>$introtext1,'full_title'=>$image_fulltext_caption,'images'=>$images,'result'=>$finalArray);

echo json_encode($dat);exit;       

 }
 
	else {
		header('Content-Type: application/json; Charset=UTF-8');
		$finalArray['status'] = '0';  
		echo json_encode($finalArray);exit; 
	}

   
 
?>
