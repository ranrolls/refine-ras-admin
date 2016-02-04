<?php

 include_once('config.php');
                
				$retvalArray = array();
				$finalArray=array();
                                $cid= $_REQUEST['id'];
				$sql= "SELECT * FROM ".$prefix."kunena_categories WHERE parent_id = '".$cid."' 
                                            ORDER BY id  ASC ";    

				$rs_username  = mysql_query($sql);

				if (mysql_num_rows($rs_username) > 0){ 

				while($data = mysql_fetch_assoc($rs_username)) {   
					 //print_r($data); 
					$tempArray = array();
                     					
					$tempArray['id']     			= $data['id'];
					$tempArray['parent_id']       		= $data['parent_id'];
					//$tempArray['title']     		= $data['title']; 
					$tempArray['name']     			= $data['name'];
					$tempArray['review']     		= $data['review']; 
					$tempArray['numTopics']     		= $data['numTopics'];
					$tempArray['numPosts']     		= $data['numPosts'];
$tempArray['reply_count']    = $tempArray['numPosts']-$tempArray['numTopics'];
					$tempArray['topic_ordering']     	= $data['topic_ordering'];
					$tempArray['last_topic_id']     	= $data['last_topic_id'];
					$tempArray['last_post_id']     		= $data['last_post_id'];
					//$tempArray['description']     		= $data['description'];
					//$tempArray['topic_ordering']     	= $data['topic_ordering'];
 //$tempArray['last_post_time']     	= ssss(date("Y-m-d H:i:s", $data['last_post_time']));
					//$tempArray['icon_id']     		= $data['icon_id'];  
					$paramsArray = array(); 
					$paramsArray 	                        = json_decode($data['params'], true); 
					
					$tempArray['access_reply']              = $paramsArray['access_reply'];
		#######################################################################
		 $sql=  mysql_query("SELECT a.id AS msgid, a.name as username, a.userid, a.subject,c.avatar as userimage
                              FROM ".$prefix."kunena_messages AS a
                              LEFT JOIN ".$prefix."users AS b ON a.userid = b.id
                              LEFT JOIN ras_kunena_users AS c ON a.userid = c.userid
                              WHERE a.catid = '".$tempArray['id']."'
                              ORDER BY msgid DESC LIMIT 1 ");

 			if(mysql_num_rows($sql) > 0)
					{   
					   $datares=mysql_fetch_assoc($sql); 
					   $tempArray['msgid']     				= $datares['msgid'];
					   $tempArray['username']     				= $datares['username']; 
					   $tempArray['userid']     				= $datares['userid'];
					   $tempArray['subject']     				= $datares['subject'];
       $tempArray['userimage']             = $upload_fullpath.'/media/kunena/avatars/resized/size36/'.$datares['userimage'];
					 
					} 
	###################################################################
					  $finalArray[] = $tempArray;  
				   
			     } 

######### get Current main category selected########## 

$sql_cate= "SELECT p.id,p.name as category_name
        FROM ras_kunena_categories p
        INNER JOIN ras_kunena_categories pp
        ON p.id = pp.parent_id 
        WHERE p.id = '".$cid."'  group by p.id ORDER BY p.id  ASC ";    

$rs_username_cat  = mysql_query($sql_cate);
 
$data_cat = mysql_fetch_assoc($rs_username_cat);  
					 
$category_name 	= $data_cat['category_name'];

########## End Here ####################
						

			$dat = array('status'=>'1','category_name'=>$category_name,'result'=>$finalArray); 

			echo json_encode($dat);exit;  
               } 

                else {
					
					$dat = array('status'=>'0','result'=>''); 
					echo json_encode($dat);exit;  
				}	
			
		    
?>
