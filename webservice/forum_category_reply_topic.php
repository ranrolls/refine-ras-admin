 <?php

include_once('config.php');

$catid 				= $_REQUEST['id'];
$threadid 			= $_REQUEST['threadid'];
$name 				= $_REQUEST['name'];
$userid 			= $_REQUEST['userid'];
$subject			= $_REQUEST['subject'];
$message 			= $_REQUEST['message'];

$first_post_time1	        = date("Y-m-d h:i:sa"); //date("Ymdis");

$first_post_time   = strtotime($first_post_time1);


$finalArray=array();

#####################################################################
$create_new_topic_subject = "insert into ".$prefix."kunena_messages(thread,catid,name,userid,subject,time) 
values('".$threadid."','".$catid."', '".$name."','".$userid."','".$subject."','".$first_post_time."')"; 

mysql_query($create_new_topic_subject);

$mesid =  mysql_insert_id(); 

######## For Insert Message  by Category ID  ##################
$create_new_topic_message = "insert into ".$prefix."kunena_messages_text(mesid,message) 
values('".$mesid."', '".$message."')";

mysql_query($create_new_topic_message);
				
############################## 

 $selectquery = mysql_query("select * from ".$prefix."kunena_user_topics where user_id='".$userid."' and topic_id='".$threadid."' and category_id='".$catid."' ") ;

$fetchdata=mysql_fetch_assoc($selectquery);
$posts= $fetchdata['posts']+1;   


$update_new_topic_final = "UPDATE ".$prefix."kunena_user_topics  SET last_post_id='".$mesid."',posts='".$posts."' 
where user_id='".$userid."' and topic_id='".$threadid."' and category_id='".$catid."' "; 

mysql_query($update_new_topic_final) or mysql_error(); 

#############################################################################	
$last_post_time1        = date("Y-m-d h:i:sa");  

$last_post_time   = strtotime($last_post_time1);

$update_new_topic_final = "UPDATE ".$prefix."kunena_topics  SET last_post_id='".$threadid."',last_post_time='".$last_post_time."',last_post_userid='".$userid."',
last_post_guest_name='".$name."',last_post_message='".$mesid."' 
where id='".$threadid."' "; 

mysql_query($update_new_topic_final); 


#######################################################################  
$finalArray[] = 'Your reply message has been successfully posted.';   
 
$dat = array('status'=>'1','result'=>$finalArray);

 echo json_encode($dat);exit;
              
//else { 
 //$finalArray['status'] = '0';  
 //echo json_encode($finalArray);exit; 
 //}

               
			
		    
?>
