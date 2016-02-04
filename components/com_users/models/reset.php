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
 * Rest model class for Users.
 *
 * @since  1.5
 */
class UsersModelReset extends JModelForm
{
	/**
	 * Method to get the password reset request form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.reset_request', 'reset_request', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the password reset complete form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getResetCompleteForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.reset_complete', 'reset_complete', $options = array('control' => 'jform'));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the password reset confirm form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getResetConfirmForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.reset_confirm', 'reset_confirm', $options = array('control' => 'jform'));

		if (empty($form))
		{
			return false;
		}
		else
		{
			$form->setValue('token', '', JFactory::getApplication()->input->get('token'));
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
		parent::preprocessForm($form, $data, $group);
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
		$params = JFactory::getApplication()->getParams('com_users');

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Save the new password after reset is done
	 *
	 * @param   array  $data  The data expected for the form.
	 *
	 * @return  mixed  Exception | JException | boolean
	 *
	 * @since   1.6
	 */
	public function processResetComplete($data)
	{
		// Get the form.
		$form = $this->getResetCompleteForm();
		$data['email'] = JStringPunycode::emailToPunycode($data['email']);

		// Check for an error.
		if ($form instanceof Exception)
		{
			return $form;
		}

		// Filter and validate the form data.
		$data = $form->filter($data);
		$return = $form->validate($data);

		// Check for an error.
		if ($return instanceof Exception)
		{
			return $return;
		}

		// Check the validation results.
		if ($return === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $formError)
			{
				$this->setError($formError->getMessage());
			}

			return false;
		}

		// Get the token and user id from the confirmation process.
		$app = JFactory::getApplication();
		$token = $app->getUserState('com_users.reset.token', null);
		$userId = $app->getUserState('com_users.reset.user', null);

		// Check the token and user id.
		if (empty($token) || empty($userId))
		{
			return new JException(JText::_('COM_USERS_RESET_COMPLETE_TOKENS_MISSING'), 403);
		}

		// Get the user object.
		$user = JUser::getInstance($userId);

		// Check for a user and that the tokens match.
		if (empty($user) || $user->activation !== $token)
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

		// Check if the user is reusing the current password if required to reset their password
		if ($user->requireReset == 1 && JUserHelper::verifyPassword($data['password1'], $user->password))
		{
			$this->setError(JText::_('JLIB_USER_ERROR_CANNOT_REUSE_PASSWORD'));

			return false;
		}

		// Update the user object.
		$user->password = JUserHelper::hashPassword($data['password1']);
		$user->activation = '';
		$user->password_clear = $data['password1'];

		// Save the user to the database.
		if (!$user->save(true))
		{
			return new JException(JText::sprintf('COM_USERS_USER_SAVE_FAILED', $user->getError()), 500);
		}

		// Flush the user data from the session.
		$app->setUserState('com_users.reset.token', null);
		$app->setUserState('com_users.reset.user', null);

		return true;
	}

	/**
	 * Receive the reset password request
	 *
	 * @param   array  $data  The data expected for the form.
	 *
	 * @return  mixed  Exception | JException | boolean
	 *
	 * @since   1.6
	 */
	public function processResetConfirm($data)
	{
		// Get the form.
		$form = $this->getResetConfirmForm();
		$data['email'] = JStringPunycode::emailToPunycode($data['email']);

		// Check for an error.
		if ($form instanceof Exception)
		{
			return $form;
		}

		// Filter and validate the form data.
		$data = $form->filter($data);
		$return = $form->validate($data);

		// Check for an error.
		if ($return instanceof Exception)
		{
			return $return;
		}

		// Check the validation results.
		if ($return === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $formError)
			{
				$this->setError($formError->getMessage());
			}

			return false;
		}

		// Find the user id for the given token.
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('activation')
			->select('id')
			->select('block')
			->from($db->quoteName('#__users'))
			->where($db->quoteName('username') . ' = ' . $db->quote($data['username']));

		// Get the user id.
		$db->setQuery($query);

		try
		{
			$user = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			return new JException(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
		}

		// Check for a user.
		if (empty($user))
		{
			$this->setError(JText::_('COM_USERS_USER_NOT_FOUND'));

			return false;
		}

		$parts = explode(':', $user->activation);
		$crypt = $parts[0];

		if (!isset($parts[1]))
		{
			$this->setError(JText::_('COM_USERS_USER_NOT_FOUND'));

			return false;
		}

		$salt = $parts[1];
		$testcrypt = JUserHelper::getCryptedPassword($data['token'], $salt, 'md5-hex');

		// Verify the token
		if (!($crypt == $testcrypt))
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

		// Push the user data into the session.
		$app = JFactory::getApplication();
		$app->setUserState('com_users.reset.token', $crypt . ':' . $salt);
		$app->setUserState('com_users.reset.user', $user->id);

		return true;
	}

	/**
	 * Method to start the password reset process.
	 *
	 * @param   array  $data  The data expected for the form.
	 *
	 * @return  mixed  Exception | JException | boolean
	 *
	 * @since   1.6
	 */
	public function processResetRequest($data)
	{
		$config = JFactory::getConfig();

		// Get the form.
		$form = $this->getForm();

		$data['email'] = JStringPunycode::emailToPunycode($data['email']);

		// Check for an error.
		if ($form instanceof Exception)
		{
			return $form;
		}

		// Filter and validate the form data.
		$data = $form->filter($data);
		$return = $form->validate($data);

		// Check for an error.
		if ($return instanceof Exception)
		{
			return $return;
		}

		// Check the validation results.
		if ($return === false)
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
			->select('id')
			->from($db->quoteName('#__users'))
			->where($db->quoteName('email') . ' = ' . $db->quote($data['email']));

		// Get the user object.
		$db->setQuery($query);

		try
		{
			$userId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);

			return false;
		}

		// Check for a user.
		if (empty($userId))
		{
			$this->setError(JText::_('COM_USERS_INVALID_EMAIL'));

			return false;
		}

		// Get the user object.
		$user = JUser::getInstance($userId);

		// Make sure the user isn't blocked.
		if ($user->block)
		{
			$this->setError(JText::_('COM_USERS_USER_BLOCKED'));

			return false;
		}

		// Make sure the user isn't a Super Admin.
		if ($user->authorise('core.admin'))
		{
			$this->setError(JText::_('COM_USERS_REMIND_SUPERADMIN_ERROR'));

			return false;
		}

		// Make sure the user has not exceeded the reset limit
		if (!$this->checkResetLimit($user))
		{
			$resetLimit = (int) JFactory::getApplication()->getParams()->get('reset_time');
			$this->setError(JText::plural('COM_USERS_REMIND_LIMIT_ERROR_N_HOURS', $resetLimit));

			return false;
		}

		// Set the confirmation token.
		$token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
		$salt = JUserHelper::getSalt('crypt-md5');
		$hashedToken = md5($token . $salt) . ':' . $salt;
		$user->activation = $hashedToken;

		// Save the user to the database.
		if (!$user->save(true))
		{
			return new JException(JText::sprintf('COM_USERS_USER_SAVE_FAILED', $user->getError()), 500);
		}

		// Assemble the password reset confirmation link.
		$mode = $config->get('force_ssl', 0) == 2 ? 1 : (-1);
		$itemid = UsersHelperRoute::getLoginRoute();
		$itemid = $itemid !== null ? '&Itemid=' . $itemid : '';
		$link = 'index.php?option=com_users&view=reset&layout=confirm&token=' . $token . $itemid;

		// Put together the email template data.
		$data = $user->getProperties();
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		$data['sitename'] = $config->get('sitename');
		$data['link_text'] = JRoute::_($link, false, $mode);
		$data['link_html'] = JRoute::_($link, true, $mode);
		$data['token'] = $token;

		$subject = JText::sprintf(
			'COM_USERS_EMAIL_PASSWORD_RESET_SUBJECT',
			$data['sitename']
		);

		/*$body = JText::sprintf(
			'COM_USERS_EMAIL_PASSWORD_RESET_BODY',
			$data['sitename'],
			$data['token'],
			$data['link_text']
		);*/

$serverurl =  $_SERVER['HTTP_HOST'];

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
       <tr><td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#343434;text-transform:capitalize;">'.$data['name'].',</span><br /><br />We have received a request regarding the change of your password.</span>
<br /><br />

<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">The verification code is - </span> '.$data['token'].' <br /> <br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Please ignore this mail if you don’t want to reset your password.</span></td></tr> 
<tr><td height="20" align="center" valign="top"> </td></tr>
<tr><td align="left" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Best regards,<br /> 
Team RAS</span>
 
 </td></tr>

 <tr><td height="20" align="center" valign="top"> </td></tr>
</table>
      </td>   
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
</html>';  







$mailer = JFactory::getMailer();
$config = JFactory::getConfig();
$subject = 'Forgot Password Request';
$from   = $config->get('mailfrom');
$fromname = $config->get( 'fromname' ); 

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



		// Check for an error.
		if ($return !== true)
		{
			return new JException(JText::_('COM_USERS_MAIL_FAILED'), 500);
		}

		return true;
	}

	/**
	 * Method to check if user reset limit has been exceeded within the allowed time period.
	 *
	 * @param   JUser  $user  User doing the password reset
	 *
	 * @return  boolean true if user can do the reset, false if limit exceeded
	 *
	 * @since    2.5
	 */
	public function checkResetLimit($user)
	{
		$params = JFactory::getApplication()->getParams();
		$maxCount = (int) $params->get('reset_count');
		$resetHours = (int) $params->get('reset_time');
		$result = true;

		$lastResetTime = strtotime($user->lastResetTime) ? strtotime($user->lastResetTime) : 0;
		$hoursSinceLastReset = (strtotime(JFactory::getDate()->toSql()) - $lastResetTime) / 3600;

		if ($hoursSinceLastReset > $resetHours)
		{
			// If it's been long enough, start a new reset count
			$user->lastResetTime = JFactory::getDate()->toSql();
			$user->resetCount = 1;
		}
		elseif ($user->resetCount < $maxCount)
		{
			// If we are under the max count, just increment the counter
			++$user->resetCount;
		}
		else
		{
			// At this point, we know we have exceeded the maximum resets for the time period
			$result = false;
		}

		return $result;
	}
}