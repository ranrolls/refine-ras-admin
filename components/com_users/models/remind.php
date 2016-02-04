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
 * Remind model class for Users.
 *
 * @since  1.5
 */
class UsersModelRemind extends JModelForm
{
	/**
	 * Method to get the username remind request form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JFor     A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.remind', 'remind', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Override preprocessForm to load the user plugin group instead of content.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @throws	Exception if there is an error in the form event.
	 *
	 * @since   1.6
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, 'user');
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		// Get the application object.
		$app = JFactory::getApplication();
		$params = $app->getParams('com_users');

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Send the remind username email
	 *
	 * @param   array  $data  Array with the data received from the form
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function processRemindRequest($data)
	{
		// Get the form.
		$form = $this->getForm();
		$data['email'] = JStringPunycode::emailToPunycode($data['email']);

		// Check for an error.
		if (empty($form))
		{
			return false;
		}

		// Validate the data.
		$data = $this->validate($form, $data);

		// Check for an error.
		if ($data instanceof Exception)
		{
			return false;
		}

		// Check the validation results.
		if ($data === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $formError)
			{
				$this->setError($formError->getMessage());
			}

			return false;
		}

		// Find the user id for the given email address.
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__users'))
			->where($db->quoteName('email') . ' = ' . $db->quote($data['email']));

		// Get the user id.
		$db->setQuery($query);

		try
		{
			$user = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);

			return false;
		}

		// Check for a user.
		if (empty($user))
		{
			$this->setError(JText::_('COM_USERS_USER_NOT_FOUND'));

			return false;
		}

		// Make sure the user isn't blocked.
		if ($user->block)
		{
			$this->setError(JText::_('COM_USERS_USER_BLOCKED'));

			return false;
		}

		$config = JFactory::getConfig();

		// Assemble the login link.
		$itemid = UsersHelperRoute::getLoginRoute();
		$itemid = $itemid !== null ? '&Itemid=' . $itemid : '';
		$link = 'index.php?option=com_users&view=login' . $itemid;
		$mode = $config->get('force_ssl', 0) == 2 ? 1 : (-1);

		// Put together the email template data.
		$data = JArrayHelper::fromObject($user);
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		$data['sitename'] = $config->get('sitename');
		$data['link_text'] = JRoute::_($link, false, $mode);
		$data['link_html'] = JRoute::_($link, true, $mode);
 
		$subject = JText::sprintf(
			'COM_USERS_EMAIL_USERNAME_REMINDER_SUBJECT',
			$data['sitename']
		);

		/* $body = JText::sprintf(
			'COM_USERS_EMAIL_USERNAME_REMINDER_BODY',
			$data['sitename'],
			$data['username'],
			$data['link_text']
		); */

$serverurl =  $_SERVER['HTTP_HOST'];


  ######################## Costume User name Remind Email Templates By Vishal  ##################
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
       <tr><td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#343434; text-transform:capitalize;">'.$data['name'].',</span><br /><br /> Please find your username below.</span>
<br /><br />

<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">
<strong style="color:#f77635">Username:</strong> '.$data['name'].' </span><br />

  
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
</html> ';  
 
$mailer = JFactory::getMailer();
$config = JFactory::getConfig();
$subject = 'Forgot Username Request';

$from   = $config->get('mailfrom');
$fromname = $config->get('fromname'); 
$to     = $user->email;

$sender = array(
    $from,
    $fromname
);
$mailer->isHTML(true);
$mailer->setSender($sender); 

$mailer->addRecipient($to);

$mailer->Encoding = 'base64';
$mailer->setSubject($subject);

$mailer->setBody($body);

 $return = $mailer->Send();
    

     
   #################################################################
		// Send the password reset request email.
		//$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $user->email, $subject, $body);

		// Check for an error.
		if ($return !== true)
		{
			$this->setError(JText::_('COM_USERS_MAIL_FAILED'), 500);

			return false;
		}

		return true;
	}
}