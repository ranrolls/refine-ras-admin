<?php
 
include_once('config.php');
                                
                $catid 				= $_REQUEST['id'];
                $name 				= $_REQUEST['name'];
                $userid 			= $_REQUEST['userid'];
                $subject			= $_REQUEST['subject'];
                $message 			= $_REQUEST['message'];
                $first_post_time1	        = date("Y-m-d h:i:sa"); //date("Ymdis");
 
                $first_post_time   = strtotime($first_post_time1);

               $finalArray=array(); 


			$create_new_topic_insert = "INSERT INTO ".$prefix."kunena_topics(category_id, subject,hold,posts,hits, first_post_id, first_post_time, 
												first_post_userid,first_post_message, first_post_guest_name, last_post_id, last_post_time, 
last_post_userid, last_post_message, last_post_guest_name) 
VALUES ('".$catid."','".$subject."','0','1','1','','".$first_post_time."',
												'".$userid."','".$message."','".$name."','','".$first_post_time."','".$userid."','".$message."','".$name."')"; 

mysql_query($create_new_topic_insert);

				  $threadid =  mysql_insert_id();
                   
######## For Insert Subject by Category ID  ##################

$create_new_topic_subject = "insert into ".$prefix."kunena_messages(thread,catid,name,userid,subject,time) 
	values('".$threadid."','".$catid."', '".$name."','".$userid."','".$subject."','".$first_post_time."')"; 

				mysql_query($create_new_topic_subject);

				  $mesid =  mysql_insert_id();

######## For Insert Message  by Category ID  ##################
$create_new_topic_message = "insert into ".$prefix."kunena_messages_text(mesid,message) 
		             values('".$mesid."', '".$message."')";

				mysql_query($create_new_topic_message);
				
				 //$last_post_id =  mysql_insert_id();
				
####################################################### 

$create_new_topic_final = "insert into ".$prefix."kunena_user_topics(user_id,topic_id,category_id,posts,last_post_id) 
values('".$userid."','".$threadid."','".$catid."','1','".$mesid."')"; 

				mysql_query($create_new_topic_final);
				
######################################################
				
$update_new_topic_final = "UPDATE ".$prefix."kunena_topics SET first_post_id='".$mesid."' where id='".$threadid."' "; 

mysql_query($update_new_topic_final) or mysql_error(); 
				 
					 
#######################################################################  
				$finalArray[] = 'Your message has been successfully posted.';  
			   
				$dat = array('status'=>'1','result'=>$finalArray);

				echo json_encode($dat);exit; 
              

               
			
		    
?>
