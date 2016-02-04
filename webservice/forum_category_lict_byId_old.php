<?php
//include("include/config.php");

//$forum = new forum_category_lict_byId();
//$forum->action = $common->replaceEmpty('action',''); 
 
//switch($forum->action){ 
//case 'forumcategorylistByid':$forum->forum_category_lict_byId_Details();  
//break; 	
 
//}

################################################################


include_once('config.php');
				
				
                $action = $_REQUEST['action'];
				
				$retvalArray = array();
				$finalArray=array();
                 
	if ($action='forumcategorylistByid'){  
	
				$sql= "SELECT * FROM ras_kunena_topics  where category_id = '".$_REQUEST['forumId']."'  and hold='0' ORDER BY first_post_time DESC ";    

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
						//header('Content-Type: application/json; Charset=UTF-8');

						$dat = array('status'=>'1','result'=>$finalArray); 

						echo json_encode($dat);exit;  
               } 
			   

                else {
					//header('Content-Type: application/json; Charset=UTF-8');
					$dat = array('status'=>'0','result'=>''); 
					echo json_encode($dat);exit;  
				}
}				

	
 
?>
