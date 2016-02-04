<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$loggeduser = JFactory::getUser();
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=users');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="userList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th class="left">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_ENABLED', 'a.block', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_ACTIVATED', 'a.activation', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center">
							<?php echo JText::_('COM_USERS_HEADING_GROUPS'); ?>
						</th>
						<th width="15%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_EMAIL', 'a.email', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_LAST_VISIT_DATE', 'a.lastvisitDate', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_REGISTRATION_DATE', 'a.registerDate', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="15">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$canEdit   = $this->canDo->get('core.edit');
					$canChange = $loggeduser->authorise('core.edit.state',	'com_users');

					// If this group is super admin and this user is not super admin, $canEdit is false
					if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin'))
					{
						$canEdit   = false;
						$canChange = false;
					}
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php if ($canEdit) : ?>
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							<?php endif; ?>
						</td>
						<td>
							<div class="name">
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
									<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
							</div>
							<div class="btn-group">
								<?php echo JHtml::_('users.filterNotes', $item->note_count, $item->id); ?>
								<?php echo JHtml::_('users.notes', $item->note_count, $item->id); ?>
								<?php echo JHtml::_('users.addNote', $item->id); ?>
							</div>
							<?php echo JHtml::_('users.notesModal', $item->note_count, $item->id); ?>
							<?php if ($item->requireReset == '1') : ?>
								<span class="label label-warning"><?php echo JText::_('COM_USERS_PASSWORD_RESET_REQUIRED'); ?></span>
							<?php endif; ?>
							<?php if (JDEBUG) : ?>
								<div class="small"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id);?>">
								<?php echo JText::_('COM_USERS_DEBUG_USER');?></a></div>
							<?php endif; ?>
						</td>
						<td class="center">
							<?php echo $this->escape($item->username); ?>
						</td>
						<td class="center">
							<?php if ($canChange) : ?>
								<?php
								$self = $loggeduser->id == $item->id;
								echo JHtml::_('jgrid.state', JHtmlUsers::blockStates($self), $item->block, $i, 'users.', !$self);

$mailer = JFactory::getMailer();
$serverurl =  $_SERVER['HTTP_HOST'];
$name	= $item->name;
$username	= $item->username;
  $id		= $item->id;
  $email	= $item->email;
 $block		= $item->block;

$subject = "Account Activation";	

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
       <tr><td align="left" valign="top"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:20px; color:#343434; font-weight:normal;">Dear <span style="color:#343434;text-transform:capitalize;">'.$name.',</span><br /><br />Your account has been activated. You now have the privilege to participate more actively in the website.</span>
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
$fromName  = 'RAS Mentorship Forum';

$mailer->isHTML(true);
$mailer->Encoding = 'base64';

$send	=  $mailer->sendMail($fromEmail, $fromName,$email, $subject,$body,1,null,null);



################################################################

//id,email,username

//print_r($item);

								?>
							<?php else : ?>
								<?php echo JText::_($item->block ? 'JNO' : 'JYES'); ?>
							<?php endif; ?>
						</td>
						<td class="center hidden-phone">
							<?php
							$activated = empty( $item->activation) ? 0 : 1;
		echo JHtml::_('jgrid.state', JHtmlUsers::activateStates(), $activated, $i, 'users.', (boolean) $activated);
                   
// if($item->activation=='1'){

//echo "<script type='text/javascript'>alert('User Unblocked');</script>";

//} 
                    

							?>
						</td>
						<td class="center">
							<?php if (substr_count($item->group_names, "\n") > 1) : ?>
								<span class="hasTooltip" title="<?php echo JHtml::tooltipText(JText::_('COM_USERS_HEADING_GROUPS'), nl2br($item->group_names), 0); ?>"><?php echo JText::_('COM_USERS_USERS_MULTIPLE_GROUPS'); ?></span>
							<?php else : ?>
								<?php echo nl2br($item->group_names); ?>
							<?php endif; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo JStringPunycode::emailToUTF8($this->escape($item->email)); ?>
						</td>
						<td class="center hidden-phone">
							<?php if ($item->lastvisitDate != '0000-00-00 00:00:00'):?>
								<?php echo JHtml::_('date', $item->lastvisitDate, 'Y-m-d H:i:s'); ?>
							<?php else:?>
								<?php echo JText::_('JNEVER'); ?>
							<?php endif;?>
						</td>
						<td class="center hidden-phone">
							<?php echo JHtml::_('date', $item->registerDate, 'Y-m-d H:i:s'); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php //Load the batch processing form. ?>
		<?php echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>