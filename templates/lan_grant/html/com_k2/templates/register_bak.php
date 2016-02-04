<?php
/**
 * @version		2.6.x
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2014 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>
<!----- Start------>

<script language="javascript">

function check_password_safety(pwd){

var msg = "";
var points = pwd.length;
var password_info = document.getElementById('password_info');

var has_letter		= new RegExp("[a-z]");
var has_caps		= new RegExp("[A-Z]");
var has_numbers		= new RegExp("[0-9]");
var has_symbols		= new RegExp("\\W");

if(has_letter.test(pwd)) 	{ points += 4; }
if(has_caps.test(pwd)) 		{ points += 4; }
if(has_numbers.test(pwd)) 	{ points += 4; }
if(has_symbols.test(pwd)) 	{ points += 4; }


if( points >= 10 ) {
	msg = '<span style="color: #0f0;">Your password is strong!</span>';
} else if( points >= 8 ) {
	msg = '<span style="color: #00f;">Your password is medium!</span>';
} else if( points >= 4 ) {
	msg = '<span style="color: #fa0;">Your password is weak!</span>';
} else {
	msg = '<span style="color: #f00;">Your password is very weak!</span>';
}

password_info.innerHTML = msg ;
}


</script> 
<!----End Here ---->


<?php  
$aaaa =  $this->escape($this->params->get('page_title'));
if($aaaa = 'Registration') {  ?>
<style>
div.lan_page_title {
    display: none!important;
}
</style>
<h3 style="color:hsl(219, 64%, 40%)"><? echo 'Register'; ?></h3>
<? } ?>
<div class="bg_white br_gray">
<!-- K2 user register form -->

	<div class="col-sm-5 col-sm-offset-4  pull-left" >
<?php if(isset($this->message)) $this->display('message'); ?>

<form action="<?php echo JURI::root(true); ?>/index.php" enctype="multipart/form-data" method="post" id="josForm" name="josForm" class="form-validate">
	<?php if($this->params->def('show_page_title',1)): ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<?php /*?><h3>	<?php echo $this->escape($this->params->get('page_title')); ?></h3><?php */?>
	</div>
	<?php endif; ?>
	<div id="k2Container" class="k2AccountPage">
    
		<table class="admintable" cellpadding="0" cellspacing="0">
			<?php /*?><tr>
				<th colspan="2" class="k2ProfileHeading">
					<?php echo JText::_('K2_ACCOUNT_DETAILS'); ?>
				</th>
			</tr><?php */?>
            
			<tr>
				<?php /*?><td class="key">
					<label id="namemsg" for="name"><?php echo JText::_('K2_NAME'); ?></label>
				</td><?php */?>
				<td>
					<input type="text" name="<?php echo $this->nameFieldName; ?>" id="name" size="40" value="<?php echo $this->escape($this->user->get( 'name' )); ?>" class="inputbox required" maxlength="50" placeholder="Name*" />
					
				</td>
			</tr>
			<tr>
				<?php /*?><td class="key">
					<label id="usernamemsg" for="username"><?php echo JText::_('K2_USER_NAME'); ?></label>
				</td><?php */?>
				<td>
					<input type="text" id="username" name="<?php echo $this->usernameFieldName; ?>" size="40" value="<?php echo $this->escape($this->user->get( 'username' )); ?>" class="inputbox required validate-username" maxlength="25" placeholder="Username*" />
					
				</td>
			</tr>
			<tr>
				<?php /*?><td class="key">
					<label id="emailmsg" for="email"><?php echo JText::_('K2_EMAIL'); ?></label>
				</td><?php */?>
				<td>
					<input type="text" id="email" name="<?php echo $this->emailFieldName; ?>" size="40" value="<?php echo $this->escape($this->user->get( 'email' )); ?>" class="inputbox required validate-email" maxlength="100" placeholder="Email*"/>
					
				</td>
			</tr>
			<?php if(version_compare(JVERSION, '1.6', 'ge')): ?>
			<tr>
				<?php /*?><td class="key">
					<label id="email2msg" for="email2"><?php echo JText::_('K2_CONFIRM_EMAIL'); ?></label>
				</td><?php */?>
				<td>
<input type="text" id="email2" name="jform[email2]" size="40" value="" class="inputbox required validate-email" maxlength="100" placeholder="Verify Email*"/>
					
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<?php /*?><td class="key">
					<label id="pwmsg" for="password"><?php echo JText::_('K2_PASSWORD'); ?></label>
				</td><?php */?>
				<td>

<!--<input type="password" name="password" id="password" size="20" onkeyup="return check_password_safety(this.value);" /> -->


					<input class="inputbox required validate-password" type="password" id="password" name="<?php echo $this->passwordFieldName; ?>" size="40" value="" placeholder="Password*" onkeyup="return check_password_safety(this.value);"/>
<span id="password_info"></span>	 			
				</td>
			</tr>
			<tr>
				<?php /*?><td class="key">
					<label id="pw2msg" for="password2"><?php echo JText::_('K2_VERIFY_PASSWORD'); ?></label>
				</td><?php */?>
				<td>
					<input class="inputbox required validate-passverify" type="password" id="password2" name="<?php echo $this->passwordVerifyFieldName; ?>" size="40" value="" placeholder="Verify Password*" />
					
				</td>
			</tr>
			<?php /*?><tr>
				<th colspan="2" class="k2ProfileHeading">
					<?php echo JText::_('K2_PERSONAL_DETAILS'); ?>
				</th>
			</tr><?php */?>
			<!-- K2 attached fields -->
		<?php /*?>	<tr>

				<?php /*?><td class="key">
					<label id="gendermsg" for="gender"><?php echo JText::_('K2_GENDER'); ?></label>
				</td><?php */?>
			<?php /*?>	<td class="gender_chk">
					<?php echo $this->lists['gender']; ?>
				</td>
			</tr>
            
			<?php /*?><tr>
				<td class="key">
					<label id="descriptionmsg" for="description"><?php echo JText::_('K2_DESCRIPTION'); ?></label>
				</td>
				<td>
					<?php echo $this->editor; ?>
				</td>
			</tr><?php */?>
            
			<?php /*?><tr>
				<?php /*?><td class="key">
					<label id="imagemsg" for="image"><?php echo JText::_( 'K2_USER_IMAGE_AVATAR' ); ?></label>
				</td><?php */?>
			<?php /*?>	<td>
					<input type="file" id="image" name="image"/>
					<?php if ($this->K2User->image): ?>
					<img class="k2AdminImage" src="<?php echo JURI::root().'media/k2/users/'.$this->K2User->image; ?>" alt="<?php echo $this->user->name; ?>" />
					<input type="checkbox" name="del_image" id="del_image" />
					<label for="del_image"><?php echo JText::_('K2_CHECK_THIS_BOX_TO_DELETE_CURRENT_IMAGE_OR_JUST_UPLOAD_A_NEW_IMAGE_TO_REPLACE_THE_EXISTING_ONE'); ?></label>
					<?php endif; ?>
				</td>
			</tr><?php */?>
            
			<?php /*?><tr>
				<td class="key">
					<label id="urlmsg" for="url"><?php echo JText::_('K2_URL'); ?></label>
				</td>
				<td>
					<input type="text" size="50" value="<?php echo $this->K2User->url; ?>" name="url" id="url"/>
				</td>
			</tr><?php */?>
            
			<?php if(count(array_filter($this->K2Plugins))): ?>
			<!-- K2 Plugin attached fields -->
			<tr>
				<th colspan="2" class="k2ProfileHeading">
					<?php echo JText::_('K2_ADDITIONAL_DETAILS'); ?>
				</th>
			</tr>
			<?php foreach ($this->K2Plugins as $K2Plugin): ?>
			<?php if(!is_null($K2Plugin)): ?>
			<tr>
				<td colspan="2">
					<?php echo $K2Plugin->fields; ?>
				</td>
			</tr>
			<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
				
			<!-- Joomla! 1.6+ JForm implementation -->
			<?php if(isset($this->form)): ?>
			<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
				<?php if($fieldset->name != 'default'): ?>
				<?php $fields = $this->form->getFieldset($fieldset->name);?>
				<?php if (count($fields)):?>
					<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
					<tr>
						<th colspan="2" class="k2ProfileHeading">
							<?php echo JText::_($fieldset->label);?>
						</th>
					</tr>
					<?php endif;?>
					<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
						<?php if ($field->hidden):// If the field is hidden, just display the input.?>
							<tr><td colspan="2"><?php echo $field->input;?></td></tr>
						<?php else:?>
							<tr>
								<td class="key">
									<?php echo $field->label; ?>
									<?php if (!$field->required && $field->type != 'Spacer'): ?>
										<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
									<?php endif; ?>
								</td>
								<td><?php echo $field->input;?></td>
							</tr>
                            
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
				<?php endif; ?>
			<?php endforeach;?>
			<?php endif; ?>
			<tr>
				<?php /*?><td class="key">
					<label id="pw2msg" for="password2"><?php echo JText::_('K2_VERIFY_PASSWORD'); ?></label>
				</td><?php */?>
				<td>
					<span style="color:red;" >Note : </span>For those not currently in the F&B industry, please state current industry 
					
				</td>
			</tr>
		</table>
		<table></table>
        <div class="captcha_box">
		<?php if($this->K2Params->get('recaptchaOnRegistration') && $this->K2Params->get('recaptcha_public_key')): ?>
        <div class="clearfix"><br></div>
		<?php /*?><label class="formRecaptcha"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label><?php */?>
		<div id="recaptcha"></div>
		<?php endif; ?>
		</div>
		<div class="k2AccountPageNotice"><?php echo JText::_('K2_REGISTER_REQUIRED'); ?></div>
		<div class="k2AccountPageUpdate">
			<button class=" validate blue_btn btn btn-primary" type="submit" >
				<?php echo "Register"; ?>
			</button>
		</div>
	</div>
    
	<input type="hidden" name="option" value="<?php echo $this->optionValue; ?>" />
	<input type="hidden" name="task" value="<?php echo $this->taskValue; ?>" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
	<input type="hidden" name="K2UserForm" value="1" />
	<?php echo JHTML::_( 'form.token' ); ?>
    
</form>

</div>

<div class="login-right col-sm-5 pull-left"> <img  src="images/page/login_right.png" class="sp-default-logo"> </div>
<div class="clearfix"></div>


</div>