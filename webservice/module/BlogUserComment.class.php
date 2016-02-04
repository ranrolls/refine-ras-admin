<?php

Class BlogUserComment{
 
		public function __construct(){
		
		}
		   
			public function BlogCommentInsert()
			{
				 
				 global $dbObj,$common; 
				 $action 	= $common->replaceEmpty('action','');
				 $userName	=$common->replaceEmpty('userName',''); 
				 $commentEmail = $common->replaceEmpty('commentEmail','');
				 $commentURL = $common->replaceEmpty('commentURL',''); 
				 $commentText = $common->replaceEmpty('commentText',''); 
				 $finalArray=array();	
  
		if ($action='insertcomment'){
		
			######################### IF NOT Exits Then Insert ###########################
			
			 
			  $user_BlogComment = "insert into ras_k2_comments(itemID,userID,userName,commentEmail,commentURL,commentText,commentDate,published) values
									('".$_REQUEST['id']."', '0','".$userName."','".$commentEmail."','".$commentURL."','".$commentText."',now(), '1')";
	  
				$dbObj->runQuery($user_BlogComment);	 
                               $finalArray[] = 'Comment added! Refreshing Page...';  
                               //array_push($finalArray);
				  
			       header('Content-Type: application/json; Charset=UTF-8');
		              $dat = array('status'=>'1','result'=>$finalArray);

			      echo json_encode($dat);exit;    
 
			     }   
		    }
 

		  		
} //Closing of Class


		
?>
		
		
