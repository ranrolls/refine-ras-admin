<?php	  
 mysql_connect("localhost","rasmento_rasras","i)%NhPvl5on*") or die(mysql_error());
 mysql_select_db("rasmento_ras") or die(mysql_error());
 $select = mysql_query("select email from ras_users") or die(mysql_error());

if($_REQUEST['send'])
						   {   
							######## Mailing Code Here
					    $sub = 'Ravindar Thakur';
						$to		 		= $_POST['email']; 
						$myemail		= 'ravindar.thakur7@gmail.com';
						$subject 		= 'Subscriber Mailer';
						$headers  		= 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
						$headers .= 'From: '.$sub.'('.$myemail.')' . "\r\n";
	                    $body  =   '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; maximum-scale=1.0;">
<title>RAS</title>

</head>

<body">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >

              <tr>
                <td height="20" align="left" valign="top"><img src="http://www.rasmentorshipforum.com/images/banner123.jpg" alt=" "  border="0" align="left" style="display:block;width:100%"></td>
              </tr>
             
                    <tr>
                      <td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Hi <span style="color:#343434;">user,</span><br />
                        <br />
                        Forgot your username? </span> <br />
                        <br />
                        <span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;"> <strong style="color:#f77635">Username:</strong> </span><br /></td>
                    </tr>
                    
                    <tr>
                      <td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Best regards,<br />
                        Team RAS</span></td>
                    </tr>
                    <tr>
                      <td height="20" align="center" valign="top"></td>
                    </tr>
                  
</table>
</body>
</html>';
foreach($_POST['email'] as $userEmail)
{
	$send =  mail($userEmail,$subject,$body,$headers);
	
}
				        
       if($send){
		$msg =  '<center><h3 style="color:#009933;">Mail sent successfully</h3></center>';
	        }
	     else
	     {
		$msg =  '<center><h3 style="color:#FF3300;">Mail error: </h3></center>';
	
         }
							}

    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mailer</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>
<body>

	<h1>Subscriber Meilier</h1>
    <i><?=$msg?></i>
    <form action="" method="post">
    <table >
    <? while($rows = mysql_fetch_assoc($select)) { ?>
    <tr>
    	<td><?=$rows['email']?><input type="checkbox" name="email[]" value="<?=$rows['email']?>"></td>
	</tr>
     <? } ?>
    <tr>
    	<td><input type="submit" name="send" value="Send" /></td>
	</tr>
    </table>
    </form>

</body>
</html>