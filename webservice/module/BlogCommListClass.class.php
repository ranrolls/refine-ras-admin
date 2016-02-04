<?php

Class BlogCommListClass{

		public function __construct(){
		
		}
		   
		   
			public function blogCommDetails() 
			{
				global $dbObj,$common; 
				$action = $common->replaceEmpty('action','');

				$retvalArray = array();
				$finalArray=array();

			if ($action='bloglist'){  

 "SELECT * FROM ras_k2_comments where itemID = '".$_REQUEST['blogid']."' ";
					$rs123 = $dbObj->runQuery("SELECT * FROM ras_k2_comments where itemID = '".$_REQUEST['blogid']."' ");
					while($data123=mysql_fetch_assoc($rs123))
					{	 
						$tempArray = array(); 
						$tempArray['id'] 			=  $data123['id'];
						$tempArray['userName'] 		=  $data123['userName'];
						$tempArray['commentDate'] 	=  $data123['commentDate'];
						$tempArray['commentText'] 	=  $data123['commentText'];
						$tempArray['commentEmail'] 	=  $data123['commentEmail'];
						$tempArray['commentURL'] 		=  $data123['commentURL'];
					
					        $FullfinalArray[] =  $tempArray; 
					 }
					
					 
						header('Content-Type: application/json; Charset=UTF-8');
						$data = array('status'=>'1','result'=>$FullfinalArray);
						echo json_encode($data);exit;
				  
                
					
			}  
			
	      }	
			
} //Closing of Class
	
?>
		
		
