<?php
/**
 * Class Name 		:	User Registration
	Description		:	This class User  Registration 
 * Author			:	vishal
 * Created on		:	25-11-2014
 * 
 * */
 
 
Class UserRegistration{
 
		public function __construct(){
		
		}
		   
			public function Registration()
			{
				 
				 global $dbObj,$common;
				 
				$action 	= $common->replaceEmpty('action','');
				$name 		= $common->replaceEmpty('name','');
				$username	= $common->replaceEmpty('username',''); 
				$email         = $common->replaceEmpty('email','');
				$password      = $common->replaceEmpty('password',''); 
				$phoneno       = $common->replaceEmpty('phoneno',''); 
				$country        = $common->replaceEmpty('country',''); 
				$company        = $common->replaceEmpty('company',''); 
				$region         = $common->replaceEmpty('region',''); 
				$serverurl      =  $_SERVER['HTTP_HOST'];	

		if ($action='userregistration'){
				  
		  $user_reg = "select * from ras_users where username='".$username."' OR email='".$email ."' " ;
			 
			$urs_details = $dbObj->runQuery($user_reg); 
			
			$num_row = mysql_num_rows($urs_details);
		 
                        while($datar= mysql_fetch_assoc($urs_details)){
                             $emailid =$datar['email'];
                             $loggedusername=$datar['username'];

                              }                        
                            
                            if(($loggedusername==$username) && ($emailid==$email)){
						          echo json_encode(array("status"=>"1","message"=>"User already exists."));  
				           }  
					else if($loggedusername==$username){
						echo json_encode(array("status"=>"1","message"=>"Username already exists."));
					}
					else if($emailid==$email){
						echo json_encode(array("status"=>"1","message"=>"User email ID already exists."));
					} 
#################################################################################


else {
 
define( '_JEXEC', 1 );
define( 'JPATH_BASE', str_replace('/webservice/module','',dirname(__FILE__)) ); 	# This is when we are in the root
define( 'DS', DIRECTORY_SEPARATOR );
require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
jimport('joomla.user.helper');
  

$activation = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

 //$activation = md5($password);
 
 $user_registration = "insert into ras_users(name,username,email,password,phoneno,block,registerDate,activation) 
                       values('".$name."','".$username."','".$email."','".md5($password)."',
                      '".$phoneno."','0',now(),'".$activation."') ";

	 $dbObj->runQuery($user_registration);	

	 $user_id = mysql_insert_id();	


################################ Insert into ras_user_profiles  group table ##################################### 

$country = "INSERT INTO ras_user_profiles(user_id, profile_key, profile_value) VALUES ('".$user_id."','profile.country','".$country."')";
$dbObj->runQuery($country);	

$company = "INSERT INTO ras_user_profiles(user_id, profile_key, profile_value) VALUES ('".$user_id."','profile.favoritebook','".$company."')";
$dbObj->runQuery($company);	

$region  = "INSERT INTO ras_user_profiles(user_id, profile_key, profile_value) VALUES ('".$user_id."','profile.region','".$region."')";
$dbObj->runQuery($region);	

### Insert user id into user  group table #### 

$user_registration_usergroup = "insert into ras_user_usergroup_map(user_id,group_id) 
                               values('".$user_id."','2') ";

$dbObj->runQuery($user_registration_usergroup);	


### Insert user id into forum user table ####

$karma_time1	                = date("Y-m-d h:i:sa"); //date("Ymdis"); 
$karma_time   					= strtotime($karma_time1); 

 $user_registration_forum = "insert into ras_kunena_users(userid,karma_time) 
		             values('".$user_id."','".$karma_time."') ";

				$dbObj->runQuery($user_registration_forum);	

				########### End Here ######################### 
				$message 		= 'You have Registered Sucuessfully.';
				$to		 	= $email;
				$myemail		= 'info@ras.org.sg';
				$subject 		= 'Welcome to RAS Mentorship Forum';
				$name1			= 'RAS Mentorship Forum';
				$headers  		= 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$myemail.'('.$name1.')' . "\r\n";

$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; maximum-scale=1.0;">
<title>RAS</title>

<style type="text/css">
 body{ margin:0px; padding:0px;}
@media only screen and (max-width:598px){
table[class="mainWd"]{ width:100% !important; }
.img{ width:100% !important; }
}
@media only screen and (max-width:599px){
table{ float:none !important; }
table[class="mainWd"]{ width:100% !important; }
table[class="table-width"]{ float:left !important}
.img{ width:100% !important; }
}


@media only screen and (max-width:480px){
td[class="wd660"]{ width:100% !important; float:left !important; text-align:center !important; }
.img1{ display:none !important}
td[class="wd360"]{ width:100% !important; float:left !important; text-align:center; margin-bottom:20px; }	
table[class="full_480"]{ width:220px !important;  text-align:center !important;  float:none !important;  }	
td[class="mob_hide"]{ display:none !important; }
}
 
.img {width:100% !important; }
.img {width:100% !important; }
</style>
</head>

<body style="background:#cccccc;-moz-text-size-adjust:none; -webkit-text-size-adjust:none; -ms-text-size-adjust:none;  ">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
<tr><td align="center">
	<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="mainWd" >
    
<tr><td height="25" align="center" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#ffffff; background:#2a4c75">Can’t see this email? View it in your browser. </td></tr> 
    

  
  
  <tr>
    <td align="left" valign="top" class="bg" bgcolor="#ffffff">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
     
<tr>    <td height="20" align="left" valign="top"> <img src="http://'.$serverurl.'/images/banner123.jpg" alt=" " class="img" border="0" align="left" style="display:block;width:100%"></td>    </tr>
 
    
     
     <tr><td height="20" align="center" valign="top"> </td></tr>
     
     <tr><td   align="center" valign="top">
       <table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
         <td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#f77635;">'.$name.',</span><br />
             <br /> Welcome to RAS. Your registration process is successfully completed.<br/><br/>
Your privacy matters to us. Thank you for using the RAS app.</span><br />
<br/>
<span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#343434; font-weight:normal;">Here are your login details:</span>

</td></tr> 
 <tr><td height="5" align="center" valign="top"> </td></tr>
 
<tr>
  <td align="left" valign="top" style="border:1px dashed #1e7fc0; padding:10px;">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:25px; color:#e10000; font-weight:normal; ">
<span style="color:#000000;">Go to the Mobile App and login:</span><br />
<br />
 			
</span>
<span style="color:#000000;">Username:</span> '.$username.'<br />
<span style="color:#000000;">Password:</span> '.$password.'
</span>
 </td></tr>
  <tr><td height="20" align="center" valign="top"> </td></tr> 
<tr><td align="left" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:25px; color:#e10000; font-weight:normal; ">
 
<span style="font-family:Arial, Helvetica, sans-serif; font-size:26px; line-height:20px; color:#454545; font-weight:bold;">Get started now!<br /><br />  </span>

<span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#454545; font-weight:normal;">Best regards,<br /> 
Team RAS</span>
 
 </td></tr>

 <tr><td height="20" align="center" valign="top"> </td></tr>
</table>
      </td>    
    
  </table>


</td>
 
 
   
	 
 
       <tr> <td align="center" valign="middle" height="37 " bgcolor="#2a4c75" > <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px  ; color:#ffffff;-webkit-text-size-adjust: none;">Copyright © 2015. RAS All rights reserved </span></td>  
        
     

  
  
  </table>
  </td>
  </tr>
<tr>
  <td align="center">&nbsp;</td>
</tr>
</table>

</body>
</html>
';
 
 //echo $body;  
mail($to, $subject, $body,$headers);

######################## Admin Reminder mail when User registered #################

$message 		= 'New User have  Registered.';
$to		 	= 'yogendra.paul@refine-interactive.com';
$myemail		= 'info@ras.org.sg';
$subject 		= 'Welcome to RAS Mentorship Forum';
$name		        = 'RAS Mentorship Forum';
$headers  		= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$myemail.'('.$name.')' . "\r\n";
					 
$body = 'Hello, Admin<br/><br/>
This New User Registered . Activate the User
  
<span style="color:#000000;">New Username:</span> '.$username.'<br /><br /><br />
 
Best regards,<br /> 
Team RAS';
 
 //echo $body;
mail($to, $subject, $body,$headers);

                        
 ###############################End Here #########################################	

 
       echo json_encode(array("status"=>"1","message"=>"Your account has been created and a confirmation email has been sent to the email address you entered."));	 

	   
}  // else condition close

}  // action close

} // function close

 ################## Edit Profile with Account Type based on Email id ###########
	
	public function UpdateProfile()
			{
				global $dbObj,$common;
				$action = $common->replaceEmpty('action',''); 
				$userid       		= $common->replaceEmpty('userid','');
				$phoneno       		= $common->replaceEmpty('phoneno','');
				$personalText           = $common->replaceEmpty('personalText','');
				$location               = $common->replaceEmpty('location',''); 
                                
				 
		if ($action='editprofile'){
		   
		     $sql	=	$dbObj->runQuery("select * from ras_users where  id='".$userid."' "); 
			  
			  if(mysql_num_rows($sql)>0){
				  
			  $edit_query = " UPDATE ras_users SET phoneno='".$phoneno."',updated_date=NOW() 
						     where id='".$userid."' ";  

			 $rs_details = $dbObj->runQuery($edit_query);
                         ####################################################### 

           $edit_query_forum = "UPDATE ras_kunena_users SET personalText='".$personalText."',location='".$location."'
                                 where userid='".$userid."' ";  

			 $rs_details_forum = $dbObj->runQuery($edit_query_forum);

			 
			   echo json_encode(array("message" =>"Your Profile has been Updated Successfully."));
			 } 
	     } 
	}	
	
	  		 
		  		
} //Closing of Class


		
?>
		
		