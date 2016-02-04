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
			<div class="control-group">
				<?php echo $field->label; ?>
				<div class="controls">
					<?php echo $field->input; ?>
				</div>
			</div>
		<?php
		} ?>

		<?php
		if ($this->requireCaptcha)
		{
			echo JUDirectoryFrontHelperCaptcha::getCaptcha();
		} ?>

		<div class="control-group">
			<label class="control-label"></label>

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