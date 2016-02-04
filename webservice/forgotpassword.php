 <?php
ob_start();
 error_reporting(0);
include_once('config.php');
$mode =  $_REQUEST['mode'];
$serverurl =  $_SERVER['HTTP_HOST'];
//echo '<a href="http://'.$serverurl.' target="_blank" style="color:#fd742f; text-decoration:none">http://'.$serverurl.'</a>';

//echo '<img src="http://'.$serverurl.'/images/icon_mail.jpg" width="13" height="10" />';

if($mode == "setpassword"){  

 if(!empty($_REQUEST['userid'])&& !empty($_REQUEST['newpassword']) )  
  {
    
	$userid    		= $_REQUEST['userid']; 
	$userpassword 	        = $_REQUEST['newpassword']; 
	   
 $result=array();
	// Generate the new password hash.
	$salt		= genRandomPassword(32);
	$crypted	= getCryptedPassword($userpassword, $salt);
	$password	= $crypted.':'.$salt; 
	$hashparts  = explode (':' , $password);	
	 
	if(mysql_query("UPDATE ".$prefix."users set password = '$password',activation='' where id = '$userid' ")){
		 
             //header('Content-Type: application/json');
		$result['status']='1';  // Invalid Token
		
	  echo json_encode($result); 

		       //echo"1";// Invalid Token
	}else{
		  
		 $result['status']='0';  // Invalid Token
			echo json_encode($result); 
	} 

}

else {
            $result1=array();
           $result['status']='0';  // Invalid Token
	    echo json_encode($result); 

}


}

elseif($mode == "verifytoken"){
	 
	$username = $_REQUEST['username'];
	$token    = $_REQUEST['token'];
	$result=array();
 
	############################ CHECKING USERNAME EXIST OR NOT ##################
	
	$sql_username = " SELECT id,activation from ".$prefix."users where username = '".$username."' ";
	$rs_username  = mysql_query($sql_username);
	$num_rows     = mysql_num_rows($rs_username);
	if($num_rows > 0){
		
		$tokenrs = mysql_fetch_assoc($rs_username);		
		$crypt  = $tokenrs['activation'];
		$testcrypt = md5($token);

		if(!($crypt == $testcrypt)){	
			header('Content-Type: application/json'); 
			$result['invalid']='2';  // Invalid Token
	 
			echo json_encode($result);
		}else{
			  header('Content-Type: application/json');
			  $result['valid']= '1'; // valid token
		          $result['userid']= $tokenrs['id'];
			  $result['token']=$crypt.':'.$salt;
			  
			 echo json_encode($result);
		}
	}else{
		   header('Content-Type: application/json'); 
		   $result['wrong user']='0';  // Invalid Token
	          echo json_encode($result); /*************** wrong user *************/
	}	
	 
}

################ FOrgot Username #############

else if($mode == "forgotusername"){
			
			 $email = $_REQUEST['email'];
			 
			 //header('Content-Type: application/json');
			############################ CHECKING USERNAME EXIST OR NOT ##################
			
			$sql_username = " SELECT id,username,name,block from ".$prefix."users where email = '".$email."' ";
			$rs_username  = mysql_query($sql_username);
			$num_rows     = mysql_num_rows($rs_username);
			if($num_rows > 0){	
		
				############### send email for token ##############		
				
				$records = mysql_fetch_assoc($rs_username);
				$username = $records['username'];
                                $name = $records['name'];
		 
if($records['block']=='0'){				   
           ######################################################
                                  $msg = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
       <tr><td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#343434;text-transform:capitalize;">'.$name.',</span><br /><br /> Please find your username below. You can use the RAS app to login to your account.</span>
<br /><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">
<strong style="color:#f77635">Username:</strong> '.$username.' </span>



</td></tr> 
  
 

  <tr><td height="20" align="center" valign="top"> </td></tr>
<tr><td align="left" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Best regards,<br /> 
Team RAS</span>
 
 </td></tr>

 <tr><td height="20" align="center" valign="top"> </td></tr>
</table>
      </td>    
       </tr>
  </table>


</td>
 
   
	 
 
       <tr> <td align="center" valign="middle" height="37 " bgcolor="#2a4c75" > <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px  ; color:#ffffff;-webkit-text-size-adjust: none;">Copyright © 2015. RAS All rights reserved </span></td>  
         </tr> 
     

  
  
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

                               #####################################################
				
			//echo $msg;  die;
				 
				$subject = "Forgot Username Request";
$myemail= 'info@ras.org.sg';
$name		        = 'RAS Mentorship Forum';

$headers= 'RAS Mentorship Forum <info@ras.org.sg>';
				sendmail($email,$subject,$msg,$headers );
				
echo json_encode(array("message"=>"Forgot Username request mail has been sent.","status"=>"1")); 
}

else {
       echo json_encode(array("message"=>"Your account is yet to be activated by the admin.","status"=>"0")); 

}



 }     
				  

 else{
                                            
					 echo json_encode(array("message"=>"0"));
					/*************** wrong email *************/
				  }
      }

//}



############ END HERE ############################


         ############ Forgot Password Mail Request ########################
		else{
			
			 $email = $_REQUEST['email'];
			 //header('Content-Type: application/json');
			############################ CHECKING USERNAME EXIST OR NOT ##################
			
			$sql_username = " SELECT id,username,name,block from ".$prefix."users where email = '".$email."' ";
			$rs_username  = mysql_query($sql_username);
			$num_rows     = mysql_num_rows($rs_username);
			if($num_rows > 0){	
		
				############### send email for token ##############		
				
				$records = mysql_fetch_assoc($rs_username);
				$id = $records['id'];
                                $name = $records['name'];
				$secret = 'JfXAcjoH0jbAMqF4';
				// Generate a new token
				$random = genRandomPassword();
				$randpasstemp = '';
				for($ik=0; $ik<4; $ik++){
					 $randpasstemp .= chr(mt_rand(48,57)); 
				}
				
				$token = md5($randpasstemp);
				$salt =  getSalt('crypt-md5');
				$hashedToken = md5($token.$salt).':'.$salt;
				
				$query	= "UPDATE ".$prefix."users SET activation = '".$token."' WHERE id = ". $id ." ";		
				mysql_query($query); 
				

if($records['block']=='0'){
				 
######################################################
 $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
       <tr><td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#343434;text-transform:capitalize;">'.$name.',</span><br /><br /> We have received a request regarding the change of your password.Please use the <br/>RAS mobile app to do the same. </span>
<br /><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">The verification code is <strong style="color:#f77635">'.$randpasstemp.'</strong></span> <br /> <br />
 
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Please ignore this mail if you do not want to reset your password.</span>

</td></tr> 
  
 

  <tr><td height="20" align="center" valign="top"> </td></tr>
<tr><td align="left" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Best regards,<br /> 
Team RAS</span>
 
 </td></tr>

 <tr><td height="20" align="center" valign="top"> </td></tr>
</table>
      </td>    
       </tr>
  </table>


</td>
 
     <tr> <td align="center" valign="middle" height="37 " bgcolor="#2a4c75" > <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px  ; color:#ffffff;-webkit-text-size-adjust: none;">Copyright © 2015. RAS All rights reserved </span></td>  
         </tr> 
     

  
  
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

#####################################################
	 			 
$subject = "Forgot Password Request";
$myemail= 'info@ras.org.sg';
$name		        = 'RAS Mentorship Forum';
$headers= 'RAS Mentorship Forum <info@ras.org.sg>';
sendmail($email,$subject,$msg,$headers);
#####################################################

 
echo json_encode(array("message"=>"Forgot Password request mail has been sent.","status"=>"1")); 

}

else {
       echo json_encode(array("message"=>"Your account is yet to be activated by the admin.","status"=>"0")); 

}



}     
 else{
                                            
echo json_encode(array("message"=>"0"));
/*************** wrong email *************/
}

}


#####################################################################################################
function genRandomPassword($length = 8){
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$base = strlen($salt);
		$makepass = '';

		/*
		 * Start with a cryptographic strength random string, then convert it to
		 * a string with the numeric base of the salt.
		 * Shift the base conversion on each character so the character
		 * distribution is even, and randomize the start shift so it's not
		 * predictable.
		 */
		$random = genRandomBytes($length + 1);
		$shift = ord($random[0]);
		for ($i = 1; $i <= $length; ++$i)
		{
			$makepass .= $salt[($shift + ord($random[$i])) % $base];
			$shift += ord($random[$i]);
		}

		return $makepass;
}

function genRandomBytes($length = 16)
	{
		$sslStr = '';
		/*
		 * if a secure randomness generator exists and we don't
		 * have a buggy PHP version use it.
		 */
		if (function_exists('openssl_random_pseudo_bytes'))
		{
			$sslStr = openssl_random_pseudo_bytes($length, $strong);
			if ($strong)
			{
				return $sslStr;
			}
		}

		/*
		 * Collect any entropy available in the system along with a number
		 * of time measurements of operating system randomness.
		 */
		$bitsPerRound = 2;
		$maxTimeMicro = 400;
		$shaHashLength = 20;
		$randomStr = '';
		$total = $length;

		// Check if we can use /dev/urandom.
		$urandom = false;
		$handle = null;

		// This is PHP 5.3.3 and up
		if (function_exists('stream_set_read_buffer') && @is_readable('/dev/urandom'))
		{
			$handle = @fopen('/dev/urandom', 'rb');
			if ($handle)
			{
				$urandom = true;
			}
		}

		while ($length > strlen($randomStr))
		{
			$bytes = ($total > $shaHashLength)? $shaHashLength : $total;
			$total -= $bytes;
			/*
			 * Collect any entropy available from the PHP system and filesystem.
			 * If we have ssl data that isn't strong, we use it once.
			 */
			$entropy = rand() . uniqid(mt_rand(), true) . $sslStr;
			$entropy .= implode('', @fstat(fopen(__FILE__, 'r')));
			$entropy .= memory_get_usage();
			$sslStr = '';
			if ($urandom)
			{
				stream_set_read_buffer($handle, 0);
				$entropy .= @fread($handle, $bytes);
			}
			else
			{
				/*
				 * There is no external source of entropy so we repeat calls
				 * to mt_rand until we are assured there's real randomness in
				 * the result.
				 *
				 * Measure the time that the operations will take on average.
				 */
				$samples = 3;
				$duration = 0;
				for ($pass = 0; $pass < $samples; ++$pass)
				{
					$microStart = microtime(true) * 1000000;
					$hash = sha1(mt_rand(), true);
					for ($count = 0; $count < 50; ++$count)
					{
						$hash = sha1($hash, true);
					}
					$microEnd = microtime(true) * 1000000;
					$entropy .= $microStart . $microEnd;
					if ($microStart > $microEnd)
					{
						$microEnd += 1000000;
					}
					$duration += $microEnd - $microStart;
				}
				$duration = $duration / $samples;

				/*
				 * Based on the average time, determine the total rounds so that
				 * the total running time is bounded to a reasonable number.
				 */
				$rounds = (int) (($maxTimeMicro / $duration) * 50);

				/*
				 * Take additional measurements. On average we can expect
				 * at least $bitsPerRound bits of entropy from each measurement.
				 */
				$iter = $bytes * (int) ceil(8 / $bitsPerRound);
				for ($pass = 0; $pass < $iter; ++$pass)
				{
					$microStart = microtime(true);
					$hash = sha1(mt_rand(), true);
					for ($count = 0; $count < $rounds; ++$count)
					{
						$hash = sha1($hash, true);
					}
					$entropy .= $microStart . microtime(true);
				}
			}

			$randomStr .= sha1($entropy, true);
		}

		if ($urandom)
		{
			@fclose($handle);
		}

		return substr($randomStr, 0, $length);
	}
function getSalt($encryption = 'md5-hex', $seed = '', $plaintext = '')
	{
		// Encrypt the password.
		switch ($encryption)
		{
			case 'crypt':
			case 'crypt-des':
				if ($seed)
				{
					return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 2);
				}
				else
				{
					return substr(md5(mt_rand()), 0, 2);
				}
				break;

			case 'crypt-md5':
				if ($seed)
				{
					return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 12);
				}
				else
				{
					return '$1$' . substr(md5(mt_rand()), 0, 8) . '$';
				}
				break;

			case 'crypt-blowfish':
				if ($seed)
				{
					return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 16);
				}
				else
				{
					return '$2$' . substr(md5(mt_rand()), 0, 12) . '$';
				}
				break;

			case 'ssha':
				if ($seed)
				{
					return substr(preg_replace('|^{SSHA}|', '', $seed), -20);
				}
				else
				{
					return mhash_keygen_s2k(MHASH_SHA1, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
				}
				break;

			case 'smd5':
				if ($seed)
				{
					return substr(preg_replace('|^{SMD5}|', '', $seed), -16);
				}
				else
				{
					return mhash_keygen_s2k(MHASH_MD5, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
				}
				break;

			case 'aprmd5': /* 64 characters that are valid for APRMD5 passwords. */
				$APRMD5 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

				if ($seed)
				{
					return substr(preg_replace('/^\$apr1\$(.{8}).*/', '\\1', $seed), 0, 8);
				}
				else
				{
					$salt = '';
					for ($i = 0; $i < 8; $i++)
					{
						$salt .= $APRMD5{rand(0, 63)};
					}
					return $salt;
				}
				break;

			default:
				$salt = '';
				if ($seed)
				{
					$salt = $seed;
				}
				return $salt;
				break;
		}
	}
function getCryptedPassword($plaintext, $salt = '', $encryption = 'md5-hex', $show_encrypt = false)
	{
		// Get the salt to use.
		$salt = getSalt($encryption, $salt, $plaintext);

		// Encrypt the password.
		switch ($encryption)
		{
			case 'plain':
				return $plaintext;

			case 'sha':
				$encrypted = base64_encode(mhash(MHASH_SHA1, $plaintext));
				return ($show_encrypt) ? '{SHA}' . $encrypted : $encrypted;

			case 'crypt':
			case 'crypt-des':
			case 'crypt-md5':
			case 'crypt-blowfish':
				return ($show_encrypt ? '{crypt}' : '') . crypt($plaintext, $salt);

			case 'md5-base64':
				$encrypted = base64_encode(mhash(MHASH_MD5, $plaintext));
				return ($show_encrypt) ? '{MD5}' . $encrypted : $encrypted;

			case 'ssha':
				$encrypted = base64_encode(mhash(MHASH_SHA1, $plaintext . $salt) . $salt);
				return ($show_encrypt) ? '{SSHA}' . $encrypted : $encrypted;

			case 'smd5':
				$encrypted = base64_encode(mhash(MHASH_MD5, $plaintext . $salt) . $salt);
				return ($show_encrypt) ? '{SMD5}' . $encrypted : $encrypted;

			case 'aprmd5':
				$length = strlen($plaintext);
				$context = $plaintext . '$apr1$' . $salt;
				$binary = JUserHelper::_bin(md5($plaintext . $salt . $plaintext));

				for ($i = $length; $i > 0; $i -= 16)
				{
					$context .= substr($binary, 0, ($i > 16 ? 16 : $i));
				}
				for ($i = $length; $i > 0; $i >>= 1)
				{
					$context .= ($i & 1) ? chr(0) : $plaintext[0];
				}

				$binary = JUserHelper::_bin(md5($context));

				for ($i = 0; $i < 1000; $i++)
				{
					$new = ($i & 1) ? $plaintext : substr($binary, 0, 16);
					if ($i % 3)
					{
						$new .= $salt;
					}
					if ($i % 7)
					{
						$new .= $plaintext;
					}
					$new .= ($i & 1) ? substr($binary, 0, 16) : $plaintext;
					$binary = JUserHelper::_bin(md5($new));
				}

				$p = array();
				for ($i = 0; $i < 5; $i++)
				{
					$k = $i + 6;
					$j = $i + 12;
					if ($j == 16)
					{
						$j = 5;
					}
					$p[] = JUserHelper::_toAPRMD5((ord($binary[$i]) << 16) | (ord($binary[$k]) << 8) | (ord($binary[$j])), 5);
				}

				return '$apr1$' . $salt . '$' . implode('', $p) . JUserHelper::_toAPRMD5(ord($binary[11]), 3);

			case 'md5-hex':
			default:
				$encrypted = ($salt) ? md5($plaintext . $salt) : md5($plaintext);
				return ($show_encrypt) ? '{MD5}' . $encrypted : $encrypted;
		}
	}	
	
	function sendmail($to,$subject,$message,$from){
		// Always set content-type when sending HTML email 
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: ' . $from . "\r\n";
		$headers .= 'Reply-To: ' .$from . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		@mail($to,$subject,$message,$headers);
	}
?>