<?php
/**
 * ------------------------------------------------------------------------
 * JUDirectory for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 *
 * @copyright      Copyright (C) 2010-2015 JoomUltra Co., Ltd. All Rights Reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @author         JoomUltra Co., Ltd
 * @website        http://www.joomultra.com
 * @----------------------------------------------------------------------@
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'contact.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div id="judir-container" class="jubootstrap component judir-container view-contact">
	<h4><?php echo JText::_('COM_JUDIRECTORY_CONTACT_LISTING_OWNER') . ': ' . $this->listing->title; ?></h4>
	<hr/>
	<form method="POST" action="#" name="adminForm" id="adminForm" class="form-validate form-horizontal">
		<?php foreach ($this->form->getFieldset('contact') AS $key => $field)
		{
			?>
			<div class="form-group">
				<div class="col-sm-2">
					<?php echo $field->label; ?>
				</div>
				<div class="col-sm-10">
					<?php echo $field->input; ?>
				</div>
			</div>
		<?php
		} ?>

		<?php
		if ($this->requireCaptcha)
		{
			?>
			<div class="form-group">
				<label for="security_code" class="control-label col-sm-2">
					<?php echo JText::_('COM_JUDIRECTORY_CAPTCHA'); ?><span style="color: red">*</span>
				</label>
				<div class="col-sm-10">
					<?php echo JUDirectoryFrontHelperCaptcha::getCaptcha(false, null, false); ?>
				</div>
			</div>
			<?php
		} ?>

		<div class="form-group">
			<label class="control-label col-sm-2"></label>

			<div class="col-sm-10">
				<button type="button" class="btn btn-default btn-primary" onclick="Joomla.submitbutton('contact.send')">
					<?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?>
				</button>
				<button type="button" class="btn btn-default"  onclick="Joomla.submitbutton('contact.cancel')">
					<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
				</button>
			</div>
		</div>

		<div>
			<?php if ($this->listingId > 0) : ?>
				<input type="hidden" name="jform[listing_id]" value="<?php echo $this->listingId ?>"/>
			<?php endif ?>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>