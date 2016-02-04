<?php
/**
 * Class Name 		:	True Value
	Description		:	This class  for Service Booking 
 * Author			:	vishal
 * Created on		:	07-09-2013
 * 
 * */
 
 
Class ChangePass{
	
 
	public function __construct(){
		
		}
		  
			/*public function ChangePassword()
			{
			global $dbObj,$common;
			//header('Content-type: application/json');
			$action = $common->replaceEmpty('action');

			$adminemail		=	$_REQUEST['email'];
			
		 	$res=$dbObj->runQuery("select password,email from user_reg where email='".$adminemail."'");
			$r=mysql_fetch_array($res);
			$useremail=$r['email'];

		 	/*$res=$dbObj->runQuery("select fullname from user_reg where email='".$email."'");
			$r=mysql_fetch_array($res);
			  $fname=$r['fullname'];



			if ($action='changepass'){

			$u	=	$_REQUEST['email'];

		 	$qq	=	mysql_query("select password from user_reg where email='".$u."'");
			$opwd1	=	mysql_fetch_array($qq);
		 	$opwd	=	$opwd1['password'];
			 
			$cpwd1	=	$_REQUEST['pass'];
		 	$cpwd   =   md5($cpwd1);
			 

			$napss	=	$_REQUEST['npass'];
		 	$pw    =    md5($napss);
			//echo '<pre>',print_r($pw),'</pre>';
 
			if($opwd==$cpwd)
			{	
		$query = "update user_reg set password='".$pw."' where email='".$u."' " ;
			//echo "update admin set password='".$pw."' where adminid='".$_SESSION['adminid']."'";
			$rs_details = $dbObj->runQuery($query);
			
			$num_row = mysql_num_rows($rs_details);
			
			if($num_row > 0)
			{ 
			}
			$results[] = array("message" =>"Your password has been changed.");
			echo json_encode(array('result'=>$results)); 
			 
			}
			
			else
			{
			
			$results[] = array("message" =>"Wrong password,Try again.");
			echo json_encode(array('result'=>$results)); 
		 
			}
		}  
		 
	}*/		
	#######################################################################
	
	
	public function ForgotPassword()
			{
			global $dbObj,$common;
			  $action = $common->replaceEmpty('action',''); 
			   $email = $common->replaceEmpty('email','');
			if ($action='forgotpassword'){
		 	$res	=	$dbObj->runQuery("select verified from user_reg where email='".$email."' ");
			//select verified from user_reg where email= 'vishal68@rediffmail.com'
			$num_row = mysql_num_rows($res);
			if($num_row > 0){ 
			if($rows_username = mysql_fetch_array($res)){ 
			 $verified	=	$rows_username['verified'];  
			//}
			
			if($verified){
			
					$base_url		= 'http://ljcrm.com/clientfiles/autosist/webservice/';
					 
					$message 		= 'You have Registered Sucuessfully.';
					$to		 		= $email;
					$myemail		= 'info@autosist.com';
					$subject 		= 'Password Reset';
					$name			= 'AUTOsist';
					$headers  		= 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.$myemail.'('.$name.')' . "\r\n";
					
					$body= "To reset your password, please <a href='".$base_url."forgotpassword.php?email=".$email." '>click here</a><br/> <br/>
					  
					<br/><br/>

					Regards,<br/><br/>
					AUTOsist Team<br/>
					Managing your vehicle records just got easier<br/>
					www.AUTOsist.com";
					
					//print_r($body);
					mail($to, $subject,$body,$headers);
				 
				 }
				  echo json_encode(array('result'=>'A Mail has send to You.')); 
			}	
				else {
					echo json_encode(array('result'=>'No account found associated with this email')); 
					}			
			
			 
			}
		}  
} 
			 
									
} //Closing of Class


		
?>
		
		
