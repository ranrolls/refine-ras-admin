<?php

Class forum_subcategory_list_byId{

		public function __construct(){
		
		}
			public function forum_subcategory_list_byId_Details() 
			{
				global $dbObj,$common; 
				$action = $common->replaceEmpty('action','');
                                $threadId = $common->replaceEmpty('threadId','');
				
			if ($action='forumsubcategorylistByid'){
    
$rs123 = $dbObj->runQuery("SELECT * FROM ras_kunena_messages  where thread = '".$threadId."' order by id desc");

						if (mysql_num_rows($rs123) > 0){ 

						while($data123=mysql_fetch_assoc($rs123))
						{	 
				$tempArray = array();  
				$tempArray['msgId'] 	=  $data123['id'];

				$rs1234 = $dbObj->runQuery("SELECT * FROM ras_kunena_messages_text  where mesid = '".$data123['id']."'  ");

							$data1234=mysql_fetch_assoc($rs1234);

							$tempArray['message'] 			= $data1234['message']; 
							$tempArray['thread'] 			=  $data123['thread'];
							$tempArray['parent'] 			=  $data123['parent'];
							$tempArray['catid'] 			=  $data123['catid'];
							$tempArray['name'] 				=  $data123['name'];
							$tempArray['userid'] 			=  $data123['userid'];  
							$tempArray['email']      		= $data123['email']; 
							$tempArray['subject'] 			=  $data123['subject']; 
							$tempArray['time']      		= ssss(date("Y-m-d H:i:s", $data123['time']));
							$FullfinalArray[] 			=  $tempArray;
					               }


######### get Current Topic Title######### 

$sql_cate1= "  SELECT subject as topic_name FROM `ras_kunena_topics` WHERE `id` = '".$threadId."'  ";    
              
$rs_username_cat1  = mysql_query($sql_cate1);
 
$data_cat1 = mysql_fetch_assoc($rs_username_cat1);  
					 
$category_name 	= $data_cat1['topic_name'];

########## End Here ####################
 
							$data = array('status'=>'1','category_name'=>$category_name,'result'=>$FullfinalArray);
							echo json_encode($data);exit;
				                        }
					                      

                                                              else {  
                                                              $data = array('status'=>'0','category_name'=>$category_name,'result'=>'');  
								   echo json_encode($data);exit; 
					                       }
                
					
			}//action close  
			
	      }//function close	
			
} //Closing of Class
	
?>
		
		
