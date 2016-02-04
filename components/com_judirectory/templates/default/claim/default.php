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
		if (task == 'claim.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div id="judir-container" class="jubootstrap component judir-container view-claim">
	<h2><?php echo JText::sprintf("COM_JUDIRECTORY_CLAIM_LISTING_X", $this->listing->title); ?></h2>
	<hr/>
	<form method="POST" name="adminForm" id="adminForm" action="#" class="form-validate form-horizontal">
		<div class="form-group">
			<label class="control-label col-sm-2" for="claim-name">
				<?php echo JText::_('COM_JUDIRECTORY_NAME'); ?>
				<span class="star">*</span>
			</label>

			<div class="col-sm-10">
				<input type="text" class="required" name="jform[name]"
				       value="<?php echo $this->user->name; ?>" id="claim-name"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="claim-email">
				<?php echo JText::_('COM_JUDIRECTORY_EMAIL'); ?>
				<span class="star">*</span>
			</label>

			<div class="col-sm-10">
				<input type="text" class="required" name="jform[email]"
				       value="<?php echo $this->user->email; ?>" id="claim-email"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="claim-phone">
				<?php echo JText::_('COM_JUDIRECTORY_PHONE'); ?>
			</label>

			<div class="col-sm-10">
				<input type="text" name="jform[phone]"
				       value="" id="claim-phone"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="claim-message">
				<?php echo JText::_('COM_JUDIRECTORY_MESSAGE'); ?>
			</label>

			<div class="col-sm-10">
				<textarea name="jform[message]" rows="5" class="form-control" id="claim-message"></textarea>
			</div>
		</div>

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
		}
		?>
		<div class="form-group">
			<label class="control-label col-sm-2">
			</label>

			<div class="col-sm-10">
				<button type="button" class="btn btn-default btn-primary submit-report"
				        onclick="Joomla.submitbutton('claim.save')"><?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?></button>
				<button type="button" class="btn btn-default cancel submit-report"
				        onclick="Joomla.submitbutton('claim.cancel')"><?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?></button>
			</div>
		</div>

		<div>
			<input type="hidden" name="jform[id]" value="0"/>
			<input type="hidden" name="jform[listing_id]" value="<?php echo $this->listingId; ?>"/>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>