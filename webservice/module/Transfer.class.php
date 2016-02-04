<?php
/**
 * Class Name 		:	Transfer
	Description		:	This class  for VehicleData
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class Transfer{  

   public function __construct(){
		
		} 
		
		
		public function transfer_Vehicle(){ 
			global $dbObj,$common;
			 $action      					= $common->replaceEmpty('action','');
			 $log_user_email    			= $common->replaceEmpty('email','');
			 $transfer_email 				= $common->replaceEmpty('transferemail','');
			 $vec_id      					= $common->replaceEmpty('vecid','');
			 $transferdoc      				= $common->replaceEmpty('transferdoc','');
			 $transfernote      			= $common->replaceEmpty('transfernote','');
			 $transferreceipt       		= $common->replaceEmpty('transferreceipt','');
			 $result = array();
			if($action='transfer'){ 
				
				/*$sql=mysql_query("UPDATE document,image,vechile,receipt SET document.email=image.email=vechile.email=receipt.email='".$fetch_email."' WHERE document.vecid=receipt.vecid=image.vecid=image.id='".$vec_id."'"); */
				
				
				$sql	= $dbObj->runQuery("select * from user_reg where email='".$log_user_email."' and password='".md5($user_password)."' ");
				 
				//if(mysql_num_rows($sql)>0){
				 
					
				$Sb=$dbObj->runQuery("select vec.* from vechile as vec 
								where vec.id='".$vec_id."' and vec.email='".$log_user_email."' ");  
				 
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) { 
				
					$vechile_name       	 = $r['vechile_name']; 
					$year       	 		 = $r['year'];
				 	$make  				 	 = $r['make'];
					$model     				 = $r['model'];
					$trim  					 = $r['trim'];
					$licence_plate    		 = $r['licence_plate'];
					$milage  				 = $r['milage'];
					$vin      				 = $r['vin'];
					$vechil_type	  		 = $r['vechil_type'];
					$image	  				 = $r['image'];
					  
				} 
				 #############################################################################
				  $newImage = "";
				  
				  $imageArray = explode(',',$image);
				  foreach($imageArray as $img){
				   
					 $newid = $this->copyImage($img);
					
					 $newImage =  $newImage.",".$newid;
				   
				    }
					
			    $finalimg =  ltrim ($newImage,',');	
				 
				 #########################################################################
				$book_vech  =   "insert into vechile (email,vechile_name,year,make,model,trim,
							  licence_plate,milage,vin,vechil_type,image,created_date) 
							 values ('".$transfer_email."','".$vechile_name."','".$year."','".$make."','".$model."',
							 '".$trim."','".$licence_plate."','".$milage."','".$vin."','".$vechil_type."','".$finalimg."',NOW() )"; 
				  
				$rs_details = $dbObj->runQuery($book_vech);
				$transfervechileid = mysql_insert_id();
			 
			}
					############ Mail Sending to Users for Vechile Transfer ####################
				 
						$base_url		= 'http://ljcrm.com/clientfiles/autosist/webservice/';
					 //<Year Make Model> 
					//$message 		= '".$log_user_email."' 'have Transfer a Vehicle '".$vechile_name."'  to You.';
					$to		 		= $transfer_email;
					$myemail		= 'info@autosist.com';
					//$subject 		= 'Transfer Vehicle Request for';
			$subject 		= " Transfer Vehicle Request for '".$year."' '".$make."' '".$model."' ";
					$name			= 'AUTOsist';
					$headers  		= 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.$myemail.'('.$name.')' . "\r\n";
					
					 $body="Greetings, <br/> <br/><br/>An AUTOsist member with the email address '".$log_user_email."' has requested to transfer records to you for the following vehicle: <br>
					 Year: '".$year."' <br>
					 Make: '".$make."'  <br>
					 Model of vehicle: '".$model."' <br><br>
					If you have an AUTOsist account, simply select the 'Accept' button when notified in the app.<br/><br>
					If you do not have an account, you can download AUTOsist for free in the App store or Google Play.  The vehicle that was transferred will be waiting for you to accept once you login.<br><br>
					<br/><br/>
					
					Regards,<br/><br/>
					AUTOsist Team<br/>
					Managing your vehicle records just got easier<br/>  
					www.AUTOsist.com
					<br><br><br><br>
					<center>This email was generated on behalf of an AUTOsist member.  To stop receiving communications, please email us at support@autosist.com</center>";
					
							
					mail($to, $subject, $body,$headers);
				 
				//log_user_email
				############################### End #######################################
				

			
		  }	
				
				$results[] = array("message" =>"Tansfer Vechile Data.");
		
				echo json_encode(array('result'=>$results));   
				
				 
				 
			if($transferdoc!=0){
				$Sb=$dbObj->runQuery("select doc.* from document as doc 
								where doc.vecid='".$vec_id."' and doc.email='".$log_user_email."' and doc.document_type not in ('Owners-Docs') ");  
				 
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) { 
				
					$document_name       	 = $r['document_name']; 
					$document_date       	 = $r['document_date'];
				 	$document_quick_note  	 = $r['document_quick_note'];
					$document_type     		 = $r['document_type'];
					$document  				= $r['document'];
					
				#############################################################################
				  $newImage = "";
				  
				  $imageArray = explode(',',$document);
				  foreach($imageArray as $img){
				   
					 $newid = $this->copyImage($img);
					
					 $newImage =  $newImage.",".$newid;
				   
				    }
					
			    $finalimg =  ltrim ($newImage,',');	
				 
				 #########################################################################
				}
					 
					$add_insurance  = "insert into document (document_name,document_date,document_quick_note,created_date,document_type,vecid,email,document) 
					values ('".$document_name."','".$document_date."','".$document_quick_note."',NOW(),'".$document_type."','".$transfervechileid."','".$transfer_email."','".$finalimg."')"; 
					
					$dbObj->runQuery($add_insurance);		
				    $docunentid = mysql_insert_id();
					
					
				}
				$results[] = array("message" =>"Tansfer Documents Data.");
		
					echo json_encode(array('result'=>$results));
			}	
		 
				if($transfernote!=0){
				$Sb=$dbObj->runQuery("select note.* from notes as note 
								where note.vecid='".$vec_id."' and note.email='".$log_user_email."'  ");  
				 
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) { 
				
					$notes_title       	 = $r['title']; 
					$comments       	 = $r['comments'];
				 	$notes  	 		 = $r['notes'];
					  
					  #############################################################################
				  $newImage = "";
				  
				  $imageArray = explode(',',$notes);
				  foreach($imageArray as $img){
				   
					 $newid = $this->copyImage($img);
					
					 $newImage =  $newImage.",".$newid;
				   
				    }
					
			    $finalimg =  ltrim ($newImage,',');	
				 
				 #########################################################################
					$str	=	"insert into notes(title,comments,created_date,vecid,email,notes)
							values ('".$notes_title."','".$comments."',NOW(),'".$transfervechileid."','".$transfer_email."','".$finalimg."'  )";
				
				 $dbObj->runQuery($str);		
				  $notesid = mysql_insert_id();	  
				}
				
				}
					$results[] = array("message" =>"Tansfer Notes Data.");
		
					echo json_encode(array('result'=>$results));
		     }
			 
			 
			 ############ For Receipt ##############
				if($transferreceipt!=0){
				$Sb=$dbObj->runQuery("select recp.* from receipt as recp 
								where recp.vecid='".$vec_id."' and recp.email='".$log_user_email."'  ");  
				 
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) { 
					
			/*$service_add  = "insert into receipt (service_date,shop,milage,cost,details,
							created_date,vecid,email,nickname,receipt) 
							values ('".$service_date."','".$shop."','".$milage."',
							'".$cost."','".$details."',NOW(),'".$vechile_id."','".$email."','".$nick_name."','".$receipt."' )";*/ 
						
					$service_date       	 = $r['service_date']; 
					$shop       			 = $r['shop'];
					$milage  	 			 = $r['milage'];
					$cost  	 				 = $r['cost'];
					$details  	 			 = $r['details'];
					$nick_name  	 		 = $r['nickname'];
					$receipt  	 		 	 = $r['receipt']; 
					   
				 #############################################################################
				  $newImage = "";
				  
				  $imageArray = explode(',',$receipt);
				   
				  foreach($imageArray as $img){
				   
					 $newid = $this->copyImage($img);
					
					 $newImage =  $newImage.",".$newid; 
				   
				    }
					
			      $finalimg =  ltrim ($newImage,',');	
				 
				 #########################################################################
					  $service_add  = "insert into receipt (service_date,shop,milage,cost,details,
									created_date,vecid,email,nickname,receipt) 
									 values ('".$service_date."','".$shop."','".$milage."',
									'".$cost."','".$details."',NOW(),'".$transfervechileid."','".$transfer_email."','".$nick_name."','".$finalimg."' )"; 
				
				$dbObj->runQuery($service_add);		
				 $receiptid = mysql_insert_id();
					
				}
					
			}
		
					$results[] = array("message" =>"Tansfer Receipt Data.");
		
					echo json_encode(array('result'=>$results));
		  }
		 
			#########################################	
				
			   
			
			//}
		}
	
	
	
	 function copyImage($img){ 
				 
			global $dbObj,$common;
			 $log_user_email    			= $common->replaceEmpty('email','');
			 $transfer_email 				= $common->replaceEmpty('transferemail',''); 
				 
				
		 $imagequery = $dbObj->runQuery("select * from image where Image_id ='".$img."' ");
			 
				
			if(mysql_num_rows($imagequery)>0){
				while($r = mysql_fetch_assoc($imagequery)) { 
				
                 				
				 $Image_url       	 = $r['Image_url'];
				
				}
				
					 $uploaddir 					= 'images/';
					 $sourcefolder 				= md5($log_user_email); 
					 $sourceimage_name   		= explode('/',$Image_url);
					 
				     $transfer_imagename 		= $sourceimage_name['2'];
					
					 
					$DestinationfolderName 		= md5($transfer_email);	
					
					if (!@mkdir($uploaddir . $DestinationfolderName, 0777)) {
						$msg ="\/\"|<>?*: are not allowed to create the folder.";  
					} 
				 
				 $source_path 	= $uploaddir.$sourcefolder; 
				 $dstination_path 	= $uploaddir.$DestinationfolderName;
				 $destinationPath = $dstination_path.'/'.$transfer_imagename;	 
			  
			if (file_exists($Image_url)) 
			{
				if(copy($Image_url , $destinationPath))
				{ 
					echo "Copy file";
				
				 
					$book_vech_image  = "insert into image (email,Image_url,created_date) 
										values ('".$transfer_email."','".$destinationPath."',CURRENT_TIMESTAMP )"; 

					$rs_details = mysql_query($book_vech_image);

					if($rs_details){

					$imageid = mysql_insert_id();				
					}
				}
				else
				{
					echo "Canot Copy file";
				}
		  } 		
	}
		
		return $imageid;
	}
	    		

}//class close
		
?>
	
