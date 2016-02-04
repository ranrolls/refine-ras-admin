<?php
//Report all errors
error_reporting(E_ALL);
/**
 * Class Name 		:	add images
	Description		:	This class  for add images
 * Author			:	vishal
 * Created on		:	07-05-2015
 * 
 * */
 
 
Class ImageuploadClass{
	
 
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
			public function AddImage()			
				{
				global $dbObj,$common;
				$action 					= $common->replaceEmpty('action','');
				$user_email	  			 = $common->replaceEmpty('email','');
				$user_password	  		  	= $common->replaceEmpty('password','');
				 
				if ($action='addimage'){
										
		$sql	= $dbObj->runQuery("select * from user_reg where email='".$user_email."' and password='".md5($user_password)."' "); 
					
		 if(mysql_num_rows($sql)>0){
						
			$uploaddir 			= 'images/';
			$folderName 		= md5($user_email);	
										
			if (!@mkdir($uploaddir . $folderName, 0777)) {
			$msg ="\/\"|<>?*: are not allowed to create the folder.";  
			}
							     
			$upload_path 	= $uploaddir.$folderName."/";
						 
						$file = mktime().basename($_FILES['userfile']['name']);

						 $uploadfile = $upload_path.$file; //die('vvv');
						 
						 ###########################################################
													 
							/*$uploaddir = './images/';

							$file = mktime().basename($_FILES['userfile']['name']);

							$uploadfile = $uploaddir . $file;
									
							$image_name = basename($_FILES["image"]["name"]);

							$new_name 	= $uploadfile.$image_name;	*/
						 
						 ###############################################

					if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

						echo "http://ljcrm.com/clientfiles/autosist/webservice/images/{$file}";
						
						$book_vech_image  = "insert into image (email,Image_url,created_date) 
								 values ('".$user_email."','".$uploadfile."',CURRENT_TIMESTAMP )"; 

						$rs_details = mysql_query($book_vech_image);

						if($rs_details){

						  $imageid = mysql_insert_id();				
						}
					}
				}
						
						echo json_encode(array("imageid"=>$imageid,"message" =>"Image has been Added."));	
				}
			}   // function close						
						
						
						
						
						
				 		#############################################################################
						/* Decode image  
						 $image 		= base64_decode($vech_image);
						 $mimetype 	= $this->getImageMimeType($image);
						 $fname		= $upload_path."img-".time(). "." . $mimetype;
						 $fnm		= "img-".time(). "." . $mimetype;
						 file_put_contents($fname, $image);
						############## insert Image #######################
						
						$book_vech_image  = "insert into image (email,Image_url,created_date) 
							      values ('".$user_email."','".$fname."',
							      CURRENT_TIMESTAMP )"; 
						
						$rs_details = $dbObj->runQuery($book_vech_image);
						$imageid = mysql_insert_id();
						
						################### For Display Byte coded image on Web ####################
						//$imgData = base64_encode(file_get_contents($fname)); 
						//$src = 'data: '.mime_content_type($fname).';base64,'.$imgData;
						//echo '<img src="'.$src.'">';
						###################################################
				       
					}*/
					 
	
	
		############################ Display All Images #########################
		
		public function Allimages()
			{
				global $dbObj,$common;
				$action 					= $common->replaceEmpty('action','');
				$user_email	  			 	= $common->replaceEmpty('email','');	
				$user_password	  		  	= $common->replaceEmpty('password','');
				$image	  		  			= $common->replaceEmpty('imageid','');
				$results = array();
				if ($action='allimages'){
					$sql	=	$dbObj->runQuery("select * from user_reg where email='".$user_email."' and password = '".md5($user_password)."' ");
						if(mysql_num_rows($sql)>0){ 
						$Sb=$dbObj->runQuery("select img.* from image as img 
						where img.email='".$user_email."' and Image_id = '".$image."' order by img.created_date "); 
						if(mysql_num_rows($Sb)>0){
						while($r = mysql_fetch_assoc($Sb)) {
						$results[] = $r;
						//$results['Image_url'] = 'images/'.md5($user_email).'/'.$r['Image_url'];
					} 
				}
			}
		} 	 
				echo json_encode( array( 'result' => $results ) );
			  
	}
		
		  ############ Delete Vechile #########################
				
					public function Deleteimage()
					{
						global $dbObj,$common;
						 
						$action = $common->replaceEmpty('action','');
						
						$user_email	  				= $common->replaceEmpty('email','');
						$user_password	  		  	= $common->replaceEmpty('password','');
						$image_id       	 		= $common->replaceEmpty('imageid',''); 	
						
					if ($action='deleteimage'){
						$sql	= $dbObj->runQuery("select * from user_reg where email='".$user_email."' 
									and password='".md5($user_password)."' ");
									
						if(mysql_num_rows($sql)>0){ 
						
						/*$result = mysql_query("SELECT * FROM image",$image_id);

						while ($a_row = mysql_fetch_assoc ($result) )
						{
							//unlink("/home/skgaston/public_html/CMS/full/pics/$a_row[image]");
							unlink('/webservice/images'.md5($user_email).'/'.$a_row['Image_url']);
							
						} */
						 
						$delete_vechile  = "DELETE from image where Image_id='".$image_id ."' and email='".$user_email."' ";
									 
						$dbObj->runQuery($delete_vechile);		

						echo json_encode(array("message" =>"Image has been Deleted."));
					    }  
					}
				}  
				
					########################################################
					 
						
} //Closing of Class


	

		
?>
		
		
