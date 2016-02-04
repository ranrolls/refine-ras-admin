<?php
//Report all errors
error_reporting(E_ALL);
/**
 * Class Name 		:	Find Location
	Description		:	This class  for Find Location
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class AddvechileClass{
	
 
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
		   
		   
			public function AddVechile()
			{
				global $dbObj,$common;
				 
				$action = $common->replaceEmpty('action','');

				$vechile_name       	 = $common->replaceEmpty('vechile_name',''); 
				$year       	 		 = $common->replaceEmpty('year',''); 
				$make  					 = $common->replaceEmpty('make','');
				$model     				 = $common->replaceEmpty('model',''); 
				$trim  					 = $common->replaceEmpty('trim','');
				$licence_plate    		 = $common->replaceEmpty('licence_plate','');
				$milage  				 = $common->replaceEmpty('milage','');
				$vin      				 = $common->replaceEmpty('vin','');
				$image	  				 = $common->replaceEmpty('imageid','');
				$user_email	  			 = $common->replaceEmpty('email','');
				$user_password	  		  = $common->replaceEmpty('password','');
				$vechil_type	  		  = $common->replaceEmpty('vechil_type','');
				 
				 
	
			if ($action='addvechile'){
					  
			 
			 $sql	= $dbObj->runQuery("select * from user_reg where email='".$user_email."' and password='".md5($user_password)."' ");
			
			//if(mysql_num_rows($sql)>0){ 
			 	
			 $book_vech  =   "insert into vechile (email,vechile_name,year,make,model,trim,
							licence_plate,milage,vin,vechil_type,image,created_date) 
							values ('".$user_email."','".$vechile_name."','".$year."','".$make."','".$model."',
							'".$trim."','".$licence_plate."','".$milage."','".$vin."','".$vechil_type."','".$image."',NOW() )"; 
				  
			 $rs_details = $dbObj->runQuery($book_vech);
			 $vechileid = mysql_insert_id();
			  
			$results[] = array("vecid"=>$vechileid,"message" =>"Vechile has been Added.");
		
					echo json_encode(array('result'=>$results));   
			   
			   }
			  
		
	}// function close
	
		############################ Display Vechile by vechile ID #########################
		
		public function VechilebyID()
			{
				global $dbObj,$common;
				
				$action 			= $common->replaceEmpty('action','');
				
				$vechile_id       	 = $common->replaceEmpty('vecid',''); 
				$user_email	  			 = $common->replaceEmpty('email','');
				$user_password	  		  = $common->replaceEmpty('password','');
				
				$results = array();
				
				if ($action='vechilebyid'){
					
			$sql	=$dbObj->runQuery("select * from user_reg where email='".$user_email."' and password = '".md5($user_password)."' ");
				
				 if(mysql_num_rows($sql)>0){ 
				
				$Sb=$dbObj->runQuery("select vec.* from 
							vechile as vec 
							where vec.id='".$vechile_id."' and vec.email='".$user_email."' group by vec.id "); 
							
				    // ,img.Image_url,img.Image_id left join image as img on
					//vec.email=img.email		
					
				 
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) {
					$results[] = $r;
					
					//$results['Image_url'] = 'images/'.md5($user_email).'/'.$r['Image_url'];
					
					######################################
					/*$imgData = base64_encode(file_get_contents($fname)); 
						//$src = 'data: '.mime_content_type($fname).';base64,'.$imgData;
						//echo '<img src="'.$src.'">';*/
					########################################
					
					} 
				}
			}
			
		} 
				echo json_encode(array('result'=>$results));
	}
		
		############################ Display All Vechile #########################
		
		public function Allvechile()
			{
				global $dbObj,$common;
				$action 					= $common->replaceEmpty('action','');
				//$vechile_id       	 	= $common->replaceEmpty('vecid',''); 
				$user_email	  			 	= $common->replaceEmpty('email','');
				$user_password	  		  	= $common->replaceEmpty('password','');
				
				$results = array();
				
				if ($action='allvechile'){
				
			   $sql	=$dbObj->runQuery("select * from user_reg where email='".$user_email."' and password = '".md5($user_password)."' ");
				
			if(mysql_num_rows($sql)>0){ 
			
				$Sb=$dbObj->runQuery("select vec.* from vechile as vec 
							where vec.email='".$user_email."' order by created_date "); 
							
				if(mysql_num_rows($Sb)>0){
				while($r = mysql_fetch_assoc($Sb)) {
					$results[] = $r;
					
					} 
				}
			}
		
		} 
				echo json_encode(array('result'=>$results));   
			  
	}
		
		  ############ Delete Vechile #########################
				
			public function Deletevechile()
					{ 
						global $dbObj,$common;
						//header('Content-type: application/json');
						$action = $common->replaceEmpty('action','');
						
						$user_email	  				= $common->replaceEmpty('email','');
						$user_password	  		  	= $common->replaceEmpty('password','');
						$vechile_id       	 		= $common->replaceEmpty('vecid',''); 	
						
					if ($action='deletevechile'){
						$sql	= $dbObj->runQuery("select * from user_reg where email='".$user_email."' 
									and password='".md5($user_password)."' "); 
				if(mysql_num_rows($sql)>0){ 
					################################################################
					$Sb=$dbObj->runQuery("select vec.* from vechile as vec 
									where vec.email='".$user_email."' and vec.id='".$vechile_id ."' "); 

					if(mysql_num_rows($Sb)>0){
						while($r = mysql_fetch_assoc($Sb)) { 
						//print_r( $image1);die;
						foreach($r as $img){ 
							$img = explode(',',$r['image']); 
						foreach($img as $imageid){
							 
					$imagequery = $dbObj->runQuery("select * from image where Image_id ='".$imageid."' "); 
						 
						 while($r1 = mysql_fetch_assoc($imagequery)) {   
							 $Image_url       	 = $r1['Image_url']; 
						if (file_exists($Image_url))
							{
								unlink($Image_url);
							}
							else
							{
								echo "The file '$Image_url' does not exist.";
							}	
							 
					  $delete_vechileimg  = "DELETE from image where Image_id='".$imageid ."' and email='".$user_email."' ";
						  
					 $dbObj->runQuery($delete_vechileimg); 
					  }
				   }   
			     }
			  }		
			}   
				#####################################################################	
				 
					$delete_document  = "DELETE from document where vecid='".$vechile_id ."' and email='".$user_email."' ";

					$dbObj->runQuery($delete_document);	
					################################################# 
					$delete_notes  = "DELETE from notes where vecid='".$vechile_id ."' and email='".$user_email."' ";

					$dbObj->runQuery($delete_notes);	
					#################################################
					$delete_receipt  = "DELETE from receipt where vecid='".$vechile_id ."' and email='".$user_email."' ";

					$dbObj->runQuery($delete_receipt);

					$delete_vechile  = "DELETE from vechile where id='".$vechile_id ."' and email='".$user_email."' ";

					$dbObj->runQuery($delete_vechile);
		    
		    #####################################################################
		    	
		 }  		 
				echo json_encode(array("message" =>"Vechile has been Deleted."));
					     
			}  
				 	
	}		
			########### Edit Vehicle #########################
				public function EditVehicle()
				{
				 global $dbObj,$common;
				
				$action = $common->replaceEmpty('action','');
				
				$user_email	  			 = $common->replaceEmpty('email','');
				$user_password	  		 = $common->replaceEmpty('password','');
				
				$vechile_name       	 = $common->replaceEmpty('vechile_name',''); 
				$year       	 		 = $common->replaceEmpty('year',''); 
				$make  					 = $common->replaceEmpty('make','');
				$model     				 = $common->replaceEmpty('model',''); 
				$trim  					 = $common->replaceEmpty('trim','');
				$licence_plate    		 = $common->replaceEmpty('licence_plate','');
				$milage  				 = $common->replaceEmpty('milage','');
				$vin      				 = $common->replaceEmpty('vin','');
				$image	  				 = $common->replaceEmpty('imageid','');
				$vechile_id       		 = $common->replaceEmpty('vecid','');
				$vechil_type	  		  = $common->replaceEmpty('vechil_type','');
			
				if ($action='editvehicle'){
				
			$sql=$dbObj->runQuery("select * from user_reg where email='".$user_email."' and password='".md5($user_password)."'  ");
				
			if(mysql_num_rows($sql)>0){ 
				 
//http://ljcrm.com/clientfiles/autosist/webservice/addvechile.php?action=editvehicle&email=vishal.cool68@gmail.com&password=123456&vechile_name=28AugAUNewTest&year=2015&model=111111&trim=0196&make=28AugAUDINewTwst&licence_plate=28AugAKJ30124&milage=500&vin=12&imageid=131&vecid=161
 
					$edit_vechile = " UPDATE vechile SET vechile_name='".$vechile_name."',
									year='".$year."',make='".$make."',
									model='".$model."',trim='".$trim."',licence_plate='".$licence_plate."',
									milage='".$milage."',vin='".$vin."',vechil_type='".$vechil_type."',image='".$image."', 
									updated_date= NOW()
									where id= '".$vechile_id."' and email= '".$user_email."' "; 
									
									 
					$rs_details = $dbObj->runQuery($edit_vechile);
									 
					//$rs_details = $dbObj->runQuery($edit_vechile);
			
						//$num_row = mysql_fetch_array($rs_details);
						//  if($num_row > 0)
						//  {

						// }
						
						//$result[] = array("message" =>"Vechile has been Updated Successfully.");
						//echo json_encode(array('result'=>$results));
						echo json_encode(array("message" =>"Vechile has been Updated Successfully.")); 	
					 
				} 
				
			}	
		}	 
		
				
					
					
					##############################################
						
} //Closing of Class


	

		
?>
		
		
