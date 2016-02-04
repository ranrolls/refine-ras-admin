<?php

Class forum_category_lict_byId{

		public function __construct(){
		
		}
		   
		   
			public function forum_category_lict_byId_Details() 
			{
				global $dbObj,$common; 
				$action = $common->replaceEmpty('action','');

				
			if ($action='forumcategorylistByid'){  
			
			//echo  "SELECT * FROM ras_kunena_topics  where category_id = '".$_REQUEST['forumId']."' ";

    $rs123 = $dbObj->runQuery("SELECT * FROM ras_kunena_topics  where category_id = '".$_REQUEST['forumId']."' ORDER BY first_post_time DESC ");

                 if (mysql_num_rows($rs123) > 0){ 

					while($data123=mysql_fetch_assoc($rs123))
					{	 
						$tempArray = array(); 
						$tempArray['category_id'] 			=  $data123['category_id'];
                                                $tempArray['topic_id'] 				=  $data123['id'];
						$tempArray['subject'] 				=  $data123['subject'];
						//$tempArray['reply_count'] 			=  $data123['posts'] -1;
                                                //$tempArray['posts'] 				= $tempArray['reply_count'];
                                                // $tempArray['reply_count']                    = $tempArray['posts'];

                                                 
						$tempArray['hits'] 					=  $data123['hits'];
						$tempArray['first_post_id'] 	                        =  $data123['first_post_id'];
						//$tempArray['first_post_time'] 		=  $data123['first_post_time'];
						$tempArray['first_post_time']      = ssss(date("Y-m-d H:i:s", $data123['first_post_time']));
						
						$tempArray['first_post_userid'] 		=  $data123['first_post_userid'];
						
						$tempArray['first_post_message'] 		=  $data123['first_post_message'];
						 
						$tempArray['first_post_guest_name'] 		=  $data123['first_post_guest_name'];
						$tempArray['last_post_id'] 		=  $data123['last_post_id'];
					 
						$tempArray['last_post_time']      = ssss(date("Y-m-d H:i:s", $data123['last_post_time']));
						
						$tempArray['last_post_userid'] 		=  $data123['last_post_userid']; 
						$tempArray['last_post_message'] 		=  $data123['last_post_message'];
						 
						$tempArray['last_post_guest_name'] 		=  $data123['last_post_guest_name'];
						 
############ For Message Count ##################
 
$rs_replyCount = $dbObj->runQuery("SELECT count( * ) as replycount
FROM `ras_kunena_messages` WHERE `thread` ='".$tempArray['topic_id']."' AND `catid` ='".$tempArray['category_id']."'  ");

$dataReply =mysql_fetch_assoc($rs_replyCount);
           
 $tempArray['posts']=  $dataReply['replycount']; 

###########End Here ###################
                                              
$FullfinalArray[] =  $tempArray;

//array_push($FullfinalArray11,$FullfinalArray);

}

 $data = array('status'=>'1','result'=>$FullfinalArray);
 echo json_encode($data);exit;
 }
                                               else {  
                                                 $data = array('status'=>'0','result'=>'');  
						 echo json_encode($data);exit; 
					      }
                
					
			}  
			
	      }	
			
} //Closing of Class
	
?>
		
		
