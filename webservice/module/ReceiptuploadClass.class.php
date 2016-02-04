<?php
//Report all errors
error_reporting(E_ALL);
/**
 * Class Name 		:	add images
	Description		:	This class  for add images
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class ReceiptuploadClass{
	
 
		public function __construct(){
		
		}  
		   
		   
function getBytesFromHexString($hexdata)
						{
						  for($count = 0; $count < strlen($hexdata); $count+=2)
							$bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

						  return implode($bytes);
						}

	function getImageMimeType($imagedata)
	{
	  $imagemimetypes = array( 
		"jpeg" => "FFD8", 
		"png" => "89504E470D0A1A0A", 
		"gif" => "474946",
		"bmp" => "424D", 
		"tiff" => "4949",
		"tiff" => "4D4D"
	  );

	  foreach ($imagemimetypes as $mime => $hexbytes)
	  {
		$bytes = $this->getBytesFromHexString($hexbytes);
		if (substr($imagedata, 0, strlen($bytes)) == $bytes)
		  return $mime;
	  }

	  return NULL;
	} 
			public function UploadReceipt()			
				{
				global $dbObj,$common;
				$action = $common->replaceEmpty('uploadReceipt','');
				$vech_receipt		 	= $common->replaceEmpty('receipt','');
				$user_email	  			 	= $common->replaceEmpty('email','');
				$user_password	  		  	= $common->replaceEmpty('password','');
				 if ($action='uploadReceipt'){				
					$sql	= $dbObj->runQuery("select * from user_reg where email='".$user_email."' and password='".md5($user_password)."' "); 
					
					if(mysql_num_rows($sql)>0){  
							$newLocation 		= 'images/';
							$folderName 		= md5($user_email);					
						if (!@mkdir($newLocation . "/" . $folderName, 0777)) {
						$msg ="\/\"|<>?*: are not allowed to create the folder.";  
						}	     
						$upload_path 				= $newLocation.$folderName."/";
				 		#############################################################################
						// Decode image  
						 $receipt 		= base64_decode($vech_receipt);
						 $mimetype 	= $this->getImageMimeType($receipt);
						 $fname		= $upload_path."img-".time(). "." . $mimetype;
						 $fnm		= "img-".time(). "." . $mimetype;
						 file_put_contents($fname, $receipt);
						############## insert Image #######################
						
						$book_receipt_image  = "insert into upload_receipt (email,receipt_url,created_date) 
							      values ('".$user_email."','".$fname."',
							      CURRENT_TIMESTAMP )"; 
						
						$receipt_details = $dbObj->runQuery($book_receipt_image);
						$receiptId = mysql_insert_id();
						
						################### For Display Byte coded image on Web ####################
						//$imgData = base64_encode(file_get_contents($fname)); 
						//$src = 'data: '.mime_content_type($fname).';base64,'.$imgData;
						//echo '<img src="'.$src.'">';
						###################################################
				        ##################################################
							/*if (!$rs_details) {
							return false;
							} else {
							return $dbObj->insert_id; // function will now return the ID instead of true.
							}*/
							 ####################################################
							 //$num_row = mysql_num_rows($rs_details);
							//if($num_row > 0)
							//{
					}
					 
			echo json_encode(array("receiptId"=>$receiptId,"message" =>"Receipt has been Added."));
		}
	}   // function close
	
		############################ Display All Images #########################
		
		public function DisplayReceipt()
			{
				global $dbObj,$common;
				$action 					= $common->replaceEmpty('action','');
				$user_email	  			 	= $common->replaceEmpty('email','');	
				$user_password	  		  	= $common->replaceEmpty('password','');
				$receipt_id	  		  			= $common->replaceEmpty('receipt_id','');
				$results = array();
				if ($action='displayReceipt'){
					$sql	=	$dbObj->runQuery("select * from user_reg where email='".$user_email."' and password = '".md5($user_password)."' ");
						if(mysql_num_rows($sql)>0){ 
						$showReceipt=$dbObj->runQuery("select recpt.* from upload_receipt as recpt 
						where recpt.email='".$user_email."' and receipt_id = '".$receipt_id."' order by recpt.created_date "); 
						if(mysql_num_rows($showReceipt)>0){
						while($receipt = mysql_fetch_assoc($showReceipt)) {
						$results_receipt[] = $receipt;
						//$results['Image_url'] = 'images/'.md5($user_email).'/'.$r['Image_url'];
					} 
				}
			}
		} 	 
				echo json_encode( array( 'result' => $results_receipt ) );
			  
	}
		
		  ############ Delete Vechile #########################
				
					public function DeleteReceipt()
					{
						global $dbObj,$common;
						 
						$action = $common->replaceEmpty('action','');
						
						$user_email	  				= $common->replaceEmpty('email','');
						$user_password	  		  	= $common->replaceEmpty('password','');
						$receipt_id       	 		= $common->replaceEmpty('receipt_id',''); 	
						
					if ($action='deletereceipt'){
						$sql	= $dbObj->runQuery("select * from user_reg where email='".$user_email."' 
									and password='".md5($user_password)."' ");
									
						if(mysql_num_rows($sql)>0){ 
						
						/*$result = mysql_query("SELECT * FROM categories",$image_id);

						while ($a_row = mysql_fetch_assoc ($result) )
						{
							//unlink("/home/skgaston/public_html/CMS/full/pics/$a_row[image]");
							unlink('/webservice/images'.md5($user_email).'/'.$a_row['Image_url']);
							
						} */
						 
						$delete_receipt  = "DELETE from upload_receipt where receipt_id='".$receipt_id ."' and email='".$user_email."' ";
									 
						$dbObj->runQuery($delete_receipt);		

						echo json_encode(array("message" =>"Receipt has been Deleted."));
					    }  
					}
				}  
				
					########################################################
					 
						
} //Closing of Class


	

		
?>
		
		
