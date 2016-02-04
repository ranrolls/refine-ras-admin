<?php
$ip = $_POST['ip']; 
$S_name = $_POST['S_name'];
$S_email = $_POST['S_email'];
$S_phone = $_POST['S_phone'];
$S_message = $_POST['S_message'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Thanks for  contacting us.</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #666666;
}
.style2 {font-size: 24px}
-->
</style>
<link href="image/stylesheet.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {font-size: 18px}
-->
</style>
</head>

<body>
<p>
<?php

$today = date("l dS \of F Y h:i:s A");
$Description = stripcslashes($Description); 
$message = " Dear Administrator, <br> Following information has been submited by :$name\n
Your Name : $S_name\n
E-mail : $S_email\n
Business Phone : $S_phone\n
Message : $S_message\n
From: $S_name ($S_email)\n
";
$from = "From: $S_email\r\n";
$subject = 'Enquiry from your website';
mail("puneetbajaj.iiit@gmail.com","puneet@makemyapplication.com",  $subject, $message, $from);
?></p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><table width="800" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr>
        <td align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="1560" height="14" colspan="2" align="center" valign="top" class="h3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="22%" rowspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" valign="top">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top">&nbsp;</td>
                      </tr>
                    </table></td>
                    <td width="76%" align="center" valign="top" class="h3">&nbsp;</td>
                    <td width="2%" rowspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center" valign="top"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="112" align="center" valign="top" class="regular">
                          <p class="h1 style2 style3">Thanks for contacting us. We will get back to you shortly.</p></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><a href="http://www.makemyapplication.com" class="heading">Back To Home </a></td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#6699CC"><div align="center">
                </div></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>