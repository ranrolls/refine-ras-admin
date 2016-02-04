<?php
/**
 * Class Name 		:	EditProfile
	Description		:	This class  for EditProfile 
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class EditProfile{
	
 
		public function __construct(){
		
		}
		 
			 
			public function UpdateProfile()
			{
				 global $dbObj,$common;
				$action = $common->replaceEmpty('action');
				
			   $email    		= $common->replaceEmpty('email','');
			   $account_type    = $common->replaceEmpty('acctype','');
			   $password       	    			= $common->replaceEmpty('password','');
				
		if ($action='editprofile'){
		  
		     $sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password='".MD5($password)."' ");
			  if(mysql_num_rows($sql)>0){
				  
			$edit_query = " UPDATE user_reg SET account_type='".$account_type."',
						    updated_date=NOW(),
						    where $email='".$email."' ";

					
			$rs_details = $dbObj->runQuery($edit_query);
			
			$num_row = mysql_num_rows($rs_details);
		    if($num_row > 0)
			   {
			 
			   }
			   $results[] =array("message" =>"Your Profile has been Updated Successfully.");
			   echo json_encode(array('result'=>$results)); 
			    
			 }
	     } 
	}				
			  
			
					
					
} //Closing of Class


		
?>
		
		
