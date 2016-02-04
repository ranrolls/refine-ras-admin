<?php 
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

//print_r(get_class_methods($this->form));

//die;

?>

<div class="row " >
<div class="col-xs-12 ">
<? 
 $url = $_SERVER['REQUEST_URI'];
if($url == '/f-b-startup-kit-login') { ?>
<div style="padding: 10px 0px 10px 0px; color:#FF0000 !important; font-weight:bold; border-top:3px solid #2a4c75; ">This area is for registered members only. Please register to login.</div>
 <? }?>
<div class="bg_white" style="border:1px solid #dddddd;  padding-bottom:20px;padding-top:20px; margin-bottom:50px;">
<div class="row">
	<div class="col-sm-7 col-sm-offset-4  pull-left" >
     
    <div class="k_guest" style="margin-left:15px; color:#333333; ">Welcome, <b class="blue_text">Guest</b>
</div>
  <div style="height:10px;"></div>
  
		<div class="login<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<h1>
					<?php echo $this->escape($this->params->get('page_heading')); ?>
				</h1>
			<?php endif; ?>

			<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
			<div class="login-description">
			<?php endif; ?>

				<?php if ($this->params->get('logindescription_show') == 1) : ?>
					<?php echo $this->params->get('login_description'); ?>
				<?php endif; ?>

				<?php if (($this->params->get('login_image') != '')) :?>
					<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USERS_LOGIN_IMAGE_ALT')?>"/>
				<?php endif; ?>

			<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
			</div>
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate">

				<?php /* Set placeholder for username, password and secretekey */
					$this->form->setFieldAttribute( 'username', 'hint', JText::_('COM_USERS_LOGIN_USERNAME_LABEL') );
					$this->form->setFieldAttribute( 'password', 'hint', JText::_('JGLOBAL_PASSWORD') );
					$this->form->setFieldAttribute( 'secretkey', 'hint', JText::_('JGLOBAL_SECRETKEY') );
				?>

				<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
					<?php if (!$field->hidden) : ?>	
						<div class="form-group col-md-4 pull-left" style="margin-right:5px;">
                      						
								<?php echo $field->input; ?>
							
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<!--<?php if ($this->tfa): ?>
					<div class="form-group">
						<div class="group-control">
							<?php echo $this->form->getField('secretkey')->input; ?>
						</div>
					</div>
				<?php endif; ?> -->

				<?php /*?><?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
					<div class="checkbox">
						<label>
							<input id="remember" type="checkbox" name="remember" class="inputbox" value="yes">
							<?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?>
						</label>
					</div>
				<?php endif; ?><?php */?>

				<div class="form-group  col-md-3 pull-left">
					<button type="submit" class="btn btn-primary btn-block" style="background-color:#2a4c75; border-color: #2a4c75;">
						Login
					</button>
				</div>

				<input type="hidden" name="return" value="<?php 

$this->params;

echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />





				<?php echo JHtml::_('form.token'); ?>

			</form>
		</div>
<div class="clearfix"></div>
		<div class=" col-md-12">
			 
				 <span class="pull-left">
					<a href="<?php echo JRoute::_('../forgot-password'); ?>" class="light_blue_text">
					<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
                    </span>
			<span class="pull-left blue_text">	&nbsp;|&nbsp; </span>
            <span class="pull-left">
					<a href="<?php echo JRoute::_('../forgot-username'); ?>" class="light_blue_text pull-left">
					<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
                    </span>
				 <span class="pull-left"> 
				<?php
				$usersConfig = JComponentHelper::getParams('com_users');
				if ($usersConfig->get('allowUserRegistration')) : ?>
                </span>
                <span class="blue_text pull-left">	&nbsp;|&nbsp; </span>
				 
					 <span class="pull-left">  
                     <span class="black_text" style="font-weight:bold; color:#333333">New user,</span>
                    
                     <a href="<?php echo JRoute::_('../register'); ?>" class="red_text" style="font-weight:bold">
					Create an account <?php /*?><?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?><?php */?></a>
                    </span>
				 
				<?php endif; ?>
			 
		</div>

	</div>
       
     <div class="login-right col-sm-3" > 
	 <img  src="images/page/login_right.png" style="max-width:130%" class="forum_login_ban"> </div>
    </div> 
   <div class="clearfix"></div>
   </div>    
   </div>
</div>

