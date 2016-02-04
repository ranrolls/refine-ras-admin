<?php
/**
 * Class Name 		:	FeedbackSend
	Description		:	This class  for FeedbackSend
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class Feedback{
	
 
		public function __construct(){
		
		} 
		    
			public function SubmitFeedback()
			{
				global $dbObj,$common;
				$action = $common->replaceEmpty('action','');
				
				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				$notes       	     		= $common->replaceEmpty('notes','');
				$notes_title   	 			= $common->replaceEmpty('title','');
				$comments   	 			= $common->replaceEmpty('comments',''); 
				//$imgattachemts 	 			= $common->replaceEmpty('imgattachemts',''); 
                //$fileattachemts 			 = $common->replaceEmpty('fileattachemts','');
                $password       	    			= $common->replaceEmpty('password','');
                $email       	    			= $common->replaceEmpty('email','');
				
				if($action='addnotes'){
				
				$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password='".MD5($password)."' ");
			  if(mysql_num_rows($sql)>0){
				  
				$str	=	"insert into notes(title,comments,created_date,vecid,email,notes)
							values ('".$notes_title."','".$comments."',NOW(),'".$vechile_id."','".$email."','".$notes."'  )";
				
				 $dbObj->runQuery($str);		
				 $notesid = mysql_insert_id();
				 
				$results[]= array("noteid"=>$notesid,"message" => "Your Notes has been Saved.");
				echo json_encode(array('result'=>$results));
			}	
		}
	}			
	
	
			##################### Display All Notes BY Vechile id and User id #########################
			public function DisplayNotes()
				{
				 global $dbObj,$common;
				//header('Content-type: application/json');
				$action 					= $common->replaceEmpty('action','');
				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				//$user_id       	     		= $common->replaceEmpty('userid','');	
				$email       	    			= $common->replaceEmpty('email','');
				$password       	    			= $common->replaceEmpty('password','');
					 
				$results = array();

				if($action='displayallnotes'){
					$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password='".MD5($password)."' ");
					if(mysql_num_rows($sql)>0){
					
				$Sb=$dbObj->runQuery("select * from notes where vecid='".$vechile_id."' and email='".$email."'
							order by created_date "); 
				if(mysql_num_rows($Sb)>0){
					while($r = mysql_fetch_assoc($Sb)) {
					$results[] = $r;
					} 
				}
			} 
		  }		echo json_encode(array('result'=>$results));
				//echo json_encode($results); 
		}
		
			##################### Display Feedback  by Feedback ID #########################
			public function Displaybyid()
				{
				 global $dbObj,$common;
				//header('Content-type: application/json');
				$action 					= $common->replaceEmpty('action','');
				$note_id					=	$common->replaceEmpty('noteid','');
				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				//$user_id       	     		= $common->replaceEmpty('userid','');	
				$email       	    			= $common->replaceEmpty('email','');
				$password       	    			= $common->replaceEmpty('password','');
				
				$results = array();

				if($action='displaybyid'){
				$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password='".MD5($password)."' ");
				if(mysql_num_rows($sql)>0){
					
				$Sb=$dbObj->runQuery("select * from notes where note_id='".$note_id."' 
									 and vecid='".$vechile_id."' and email='".$email."' "); 
				
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) {
					$results[] = $r;
				} 
			}
		  }	
		} 	echo json_encode(array('result'=>$results));
			//echo json_encode($results); 
			
		}
		
		################# Delete Notes ######################## 
			public function DleteFeedback()
				{
				 global $dbObj,$common;
				//header('Content-type: application/json');
				$action 					= $common->replaceEmpty('action','');
				$note_id					=	$common->replaceEmpty('noteid','');
				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				//$user_id       	     		= $common->replaceEmpty('userid','');
				$email       	    			= $common->replaceEmpty('email','');
				$password       	    			= $common->replaceEmpty('password','');	
				
				  
				if($action='deletenotes'){
				$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password='".MD5($password)."' ");
				if(mysql_num_rows($sql)>0){
				
				$Sb=$dbObj->runQuery("DELETE FROM  notes where note_id = '".$note_id."' and vecid='".$vechile_id."' and email='".$email."' ");
				} 
			 } 	
			 $results[]= array("message" => "Notes Deleted.");
			 echo json_encode(array('result'=>$results));
			 
			
		}
		
		
		
		
		
		
		
		
			 ########### Edit Notes ################
			
				public function Editnotes()
				{
					 global $dbObj,$common;
					//header('Content-type: application/json');
					$action = $common->replaceEmpty('action','');
				 

				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				$notes_title   	 			= $common->replaceEmpty('title','');
				$comments   	 			= $common->replaceEmpty('comments',''); 
				$notes       	     		= $common->replaceEmpty('notes','');
				$notes_id       			= $common->replaceEmpty('noteid','');
				$email       	    		= $common->replaceEmpty('email','');
				$password       	    	= $common->replaceEmpty('password','');		
						
				if ($action='editnotes'){
				
					$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND 		password='".MD5($password)."' ");
				
					if(mysql_num_rows($sql)>0){
							  
					 $edit_query = " UPDATE notes SET title='".$notes_title."',
									comments='".$comments."',
									updated_date=NOW(),notes='".$notes."'
									where note_id='".$notes_id."' and vecid='".$vechile_id."' and email='".$email."' ";

						$rs_details = $dbObj->runQuery($edit_query);
					
					/*$num_row = mysql_num_rows($rs_details);
					if($num_row > 0)
					{
			 
					}*/
					$results[]=array("message" =>"Notes has been Updated Successfully.");
					echo json_encode(array('result'=>$results));
					 
				} 
			}
		}	
			 
			 
		
				 				
} //Closing of Class


		
?>
		
		
