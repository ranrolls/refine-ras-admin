<?php
	error_reporting(E_ALL);
/**
 * Class Name 		:	Find Location
	Description		:	This class  for Find Location
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class AddinsuranceClass{
	
 
		public function __construct(){
		
		}
		   
			public function AddInsurance()
			{
				global $dbObj,$common;
				//header('Content-type: application/json'); 
				$action = $common->replaceEmpty('action','');
				
				$document_name       	 = $common->replaceEmpty('docname',''); 
				$document_date     	 	 = $common->replaceEmpty('docdate',''); 
				$document_quick_note  	 = $common->replaceEmpty('docquicknote','');
				$document_type       	 = $common->replaceEmpty('doctype',''); 
				$vechile_id       	 	 = $common->replaceEmpty('vecid',''); 
				$document       	    	 = $common->replaceEmpty('document','');
				$email                   = $common->replaceEmpty('email','');
				$password                   = $common->replaceEmpty('password','');
				
				
				
			if ($action='adddocuments'){
			 
				$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password ='".md5($password)."' ");
				
				if(mysql_num_rows($sql)>0){ 
					
			 $add_insurance  = "insert into document (document_name,document_date,document_quick_note,created_date,document_type,vecid,email,document) 
			values ('".$document_name."','".$document_date."','".$document_quick_note."',NOW(),'".$document_type."','".$vechile_id."','".$email."','".$document."')"; 
				
			 $dbObj->runQuery($add_insurance);		
			 $docunentid = mysql_insert_id();
				
			$result[]= array("docid"=>$docunentid,"message" =>"Document has been Added.");
			 
			
			//$result['login-status']= "1";
				//$finalarray[] =  $result;
				
				echo json_encode(array('result'=>$result));	 
				
		   }
				
		}
	}
	
		############################ Display Vechile by vechile ID #########################
		
		public function InsurancebyID()
			{
				global $dbObj,$common;
				//header('Content-type: application/json');
				$action 					= $common->replaceEmpty('action','');
				$doc_id       	 			= $common->replaceEmpty('docid','');	
				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				//$user_id       	     		= $common->replaceEmpty('userid',''); 
				$password                   = $common->replaceEmpty('password','');
				$email                   = $common->replaceEmpty('email','');
				
				
				$results = array();
				
				if ($action='documentbyid'){
				$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password ='".md5($password)."'  ");
				
				if(mysql_num_rows($sql)>0){ 
			
				$Sb=$dbObj->runQuery("select * from document where doc_id='".$doc_id."' 
									and vecid='".$vechile_id."' and email= '".$email."'  "); 
				
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) {
					$results[] = $r;
					} 
				}
			}
		}			//$result['login-status']= "1";
				//$finalarray[] =  $result;
				
				//$result[]=
				echo json_encode(array('result'=>$results));	 
				//echo json_encode($results);   
			  
	}
	
		############################ Display All Vechile #########################
		
		public function Allinsurance()
			{
				global $dbObj,$common;
				//header('Content-type: application/json');
				$action 					= $common->replaceEmpty('action','');
				$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
				$user_id       	     		= $common->replaceEmpty('userid','');
				$mode	      	     		= $common->replaceEmpty('mode','');
				$password                   = $common->replaceEmpty('password','');
				$email                   = $common->replaceEmpty('email','');
				
				$vechile_type 				= "";
				$Owner_type					= "";
				
				$results = array();
				
				if ($action='alldocuments'){
				
				$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password ='".md5($password)."' ");
				
				if(mysql_num_rows($sql)>0){
				
				if($mode=='Owners-Docs'){
				$Sb=$dbObj->runQuery("select * from document where email='".$email ."' 
				and document_type='Owners-Docs' order by created_date "); 
               if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) {
					$results[] = $r;
					} 
				}
			  } else {
					
					$Sb=$dbObj->runQuery("select * from document where vecid='".$vechile_id."' AND email='".$email ."' and document_type='Vehicle-Docs' order by created_date "); 
				if(mysql_num_rows($Sb)>0){
					while($r = mysql_fetch_assoc($Sb)) {
						$results[] = $r;
					   } 
					}
				}
			}	 
		}
				echo json_encode(array('result'=>$results));	
				//echo json_encode($results);   
}
		
		
			######################### Edit Insurance #################
			
				public function Editinsurance()
				{
					 global $dbObj,$common;
					$action = $common->replaceEmpty('action','');
				 

					$document_name       	 = $common->replaceEmpty('docname',''); 
					$document_date     	 	 = $common->replaceEmpty('docdate',''); 
					$document_quick_note  	 = $common->replaceEmpty('docquicknote','');
					$document_type       	 = $common->replaceEmpty('doctype',''); 
					$vechile_id       	 	 = $common->replaceEmpty('vecid',''); 
					$document       	    	 = $common->replaceEmpty('document','');
					$doc_id       			 = $common->replaceEmpty('docid','');
					$password                   = $common->replaceEmpty('password','');
				     $email                   = $common->replaceEmpty('email','');		
						
				if ($action='editdocument'){
				
					$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password ='".md5($password)."' ");
				
					if(mysql_num_rows($sql)>0){
							  
					$edit_query = " UPDATE document SET document_name='".$document_name."',
									document_date='".$document_date."',
									document_quick_note='".$document_quick_note."',
									document ='".$document."',updated_date=NOW(),
									document_type='".$document_type."'
									where doc_id='".$doc_id."' and vecid='".$vechile_id."' and email='".$email."' ";

						$rs_details = $dbObj->runQuery($edit_query);
					
					/*$num_row = mysql_num_rows($rs_details);
					if($num_row > 0)
					{
			 
					}*/
					
					$results[]=array("message" =>"Document has been Updated Successfully.");
					echo json_encode(array('result'=>$results));	
					
				}  
			}
		}		
			
				########################################################
				
					############ Delete Insurance #########################
				
					public function DeleteInsurance()
					{
						global $dbObj,$common;
						//header('Content-type: application/json');
						$action = $common->replaceEmpty('action','');
						
						$doc_id       				 = $common->replaceEmpty('docid','');		
						$vechile_id       	 		= $common->replaceEmpty('vecid',''); 
						//$user_id       	     		= $common->replaceEmpty('userid','');
						$password                   = $common->replaceEmpty('password','');
				        $email                   = $common->replaceEmpty('email','');	
						
					if ($action='deletedocument'){
					
					$sql	=$dbObj->runQuery("select * from user_reg where email='".$email."' AND password ='".md5($password)."' ");
				
					if(mysql_num_rows($sql)>0){
					$delete_insurance  = "DELETE from document where doc_id='".$doc_id ."' and vecid='".$vechile_id."' and email='".$email."' ";
									 
						$dbObj->runQuery($delete_insurance);		
						
						$results[]=array("message" =>"Document has been Deleted.");		
						echo json_encode(array('result'=>$results));		
						
					}	
				}
			}  
				
					########################################################
				
} //Closing of Class


		
?>
		
		
