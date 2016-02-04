<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base controller class for Users.
 *
 * @since  1.5
 */
class UsersController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   1.5
	 */

########### For User Unblock by Admin Send Mail Notification to User ############### 

public function mailsendmemberstatus(){

$serverurl =  $_SERVER['HTTP_HOST'];
$db        =  JFactory::getDBO();

$userQuery = "SELECT * FROM ras_users where block='0' and lastvisitDate  IS NULL  ";  
$db->setQuery($userQuery);
$userData = $db->loadObjectList();

foreach($userData as $userData1){

$mailer = JFactory::getMailer();



  $username	= $userData1->username;
  $id		= $userData1->id;
  $email	= $userData1->email;
 $block		= $userData1->block;
$lastvisitDate = $userData1->lastvisitDate;  


$subject = "User Account Activation Reminder";	

//$body="";    
 $body ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
       <tr><td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#343434;">'.$username.',</span><br /><br />Your account has been activated. You now have the privilege to participate more actively in the website.</span>
<br /><br />
  
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
       
       
  <tr><td align="center" valign="top" bgcolor="#e10000" > 
  <br /> 
 <span style="font-family:Arial, Helvetica, sans-serif; font-size:26px; line-height:20px; color:#ffffff; font-weight:bold;"> Contact Us</span><br /><br />
  
 <span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#ffffff; font-weight:normal;">  <strong style="text-transform:uppercase"> Restaurant Association of Singapore.</strong> <br />
29 Tai Seng Avenue, #06-04A Natural Cool Lifestyle Hub, Singapore 534119.</span>
   
  
  </td></tr>
    <tr><td height="20" align="center" valign="top" bgcolor="#e10000" > </td></tr>
  <tr>
    <td align="center" valign="top" bgcolor="#e10000">
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" class="full_480" >
      <tr>
        <td align="center" valign="top"><table width="48%" border="0" align="left" cellpadding="0" cellspacing="0" class="full_480" >
      <tr>	<td width="13"><img src="http://'.$serverurl.'/images/icon_mob.jpg" width="9" height="13" /></td><td width="5"></td>     
	<td width="180" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#ffffff;"> Call us on: (65) 6479 7723 </td> </tr>
      </table>
      
      <table width="48%" border="0" align="right" cellpadding="0" cellspacing="0"  class="full_480">
      <tr> 
     <td width="13"><img src="http://'.$serverurl.'/images/icon_mail.jpg" width="13" height="10" /></td><td width="5"></td>     
     <td align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#ffffff;"> Email us: <a href="mailto:info@ras.org.sg" style="font-size:12px; color:#ffffff;">info@ras.org.sg</a></td> </tr>
      </table>
       </td></tr> 
       
       
     <tr> 

	
     </tr>

       
       </table></td></tr>
       <tr><td height="20" align="center" valign="top" bgcolor="#e10000" > </td></tr>
  </table>


</td>
  </tr>
 
   
	 
 
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
</html>  ';	
 
 //echo $body;
				        //$mailer->setBody($body);
  


$fromEmail = 'info@ras.org.sg'; 
$fromName  = 'From: RAS Mentorship Forum';

$mailer->isHTML(true);
$mailer->Encoding = 'base64';

$send	=  $mailer->sendMail($fromEmail, $fromName,$email, $subject,$body,1,null,null);

  
}
 

} //function close


           ################## End here #########################################################







	public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->getCmd('view', 'login');
		$vFormat = $document->getType();
		$lName   = $this->input->getCmd('layout', 'default');

		if ($view = $this->getView($vName, $vFormat))
		{
			// Do any specific processing by view.
			switch ($vName)
			{
				case 'registration':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();

					if ($user->get('guest') != 1)
					{
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));

						return;
					}

					// Check if user registration is enabled
					if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0)
					{
						// Registration is disabled - Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));

						return;
					}

					// The user is a guest, load the registration model and show the registration page.
					$model = $this->getModel('Registration');
					break;

				// Handle view specific models.
				case 'profile':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();

					if ($user->get('guest') == 1)
					{
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));

						return;
					}

					$model = $this->getModel($vName);
					break;

				// Handle the default views.
				case 'login':
					$model = $this->getModel($vName);
					break;

				case 'reset':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();

					if ($user->get('guest') != 1)
					{
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));

						return;
					}

					$model = $this->getModel($vName);
					break;

				case 'remind':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();

					if ($user->get('guest') != 1)
					{
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));

						return;
					}

					$model = $this->getModel($vName);
					break;

				default:
					$model = $this->getModel('Login');
					break;
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->document = $document;

			$view->display();
		}
	}
}