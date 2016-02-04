<?php

include_once('config.php');
				
				
                $action = $_REQUEST['action'];
				
				$retvalArray = array();
				$finalArray=array();
                 
	if ($action='forumcategorylistByid'){  
	
				$sql= "SELECT * FROM ras_kunena_topics  where category_id = '".$_REQUEST['forumId']."'  and hold='0' ORDER BY last_post_time DESC";    

					$rs_username  = mysql_query($sql); 
				if (mysql_num_rows($rs_username) > 0){ 

				while($data = mysql_fetch_assoc($rs_username)) {   
					 
					$tempArray = array();
                     					 
						$tempArray['category_id'] 			=  $data['category_id'];
						$tempArray['topic_id'] 				=  $data['id'];
						$tempArray['subject'] 				=  $data['subject']; 
						$tempArray['hits'] 					=  $data['hits'];
						$tempArray['first_post_id'] 	    =  $data['first_post_id']; 
						$tempArray['first_post_time']       = ssss(date("Y-m-d H:i:s", $data['first_post_time']));

						$tempArray['first_post_userid'] 			=  $data['first_post_userid'];

						$tempArray['first_post_message'] 			=  $data['first_post_message'];

						$tempArray['first_post_guest_name'] 		=  $data['first_post_guest_name'];
						$tempArray['last_post_id'] 					=  $data['last_post_id'];
						$tempArray['last_post_time']      			= ssss(date("Y-m-d H:i:s", $data['last_post_time']));
						$tempArray['last_post_userid'] 				=  $data['last_post_userid']; 
						$tempArray['last_post_message'] 			=  $data['last_post_message'];
						$tempArray['last_post_guest_name'] 			=  $data['last_post_guest_name'];
					
					#######################################################################  
					$rs_replyCount = mysql_query("SELECT count( * ) as replycount
					                 FROM ras_kunena_messages WHERE thread ='".$tempArray['topic_id']."' AND
									 catid ='".$tempArray['category_id']."'  ");

					$dataReply =mysql_fetch_assoc($rs_replyCount);

					$tempArray['posts']=  $dataReply['replycount']; 

					###########End Here ################### 
					
					  $finalArray[] = $tempArray;  
				   
			     } 


######### get Current Topic Title######### 

$sql_cate= "SELECT b.name as category_name,b.id,a.id as topic_id FROM ras_kunena_topics as a left join ras_kunena_categories as  b on a.category_id=b.id where a.category_id = '".$_REQUEST['forumId']."'  group by b.id ORDER BY b.id ASC  ";    
             
          

$rs_username_cat  = mysql_query($sql_cate);
 
$data_cat = mysql_fetch_assoc($rs_username_cat);  
					 
$category_name 	= $data_cat['category_name'];

########## End Here ####################
			   $dat = array('status'=>'1','category_name'=>$category_name,'result'=>$finalArray); 			

						//$dat = array('status'=>'1','result'=>$finalArray); 

						echo json_encode($dat);exit;  
               } 
			   

                else {
					
					$dat = array('status'=>'0','result'=>''); 
					echo json_encode($dat);exit;  
				}
}				

	
 
?>
