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
		if (task == 'subscribe.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div id="judir-container" class="jubootstrap component judir-container view-subscribe">
	<h4><?php echo JText::sprintf("COM_JUDIRECTORY_SUBSCRIBE_LISTING_X", $this->listing->title); ?></h4>
	<hr/>
	<form method="POST" action="#" name="adminForm" id="adminForm" class="form-validate form-horizontal">
		<div class="form-group">
			<label class="control-label col-sm-2" for="inputUsername">
				<?php echo JText::_('COM_JUDIRECTORY_NAME'); ?>
				<span style="color: red">*</span>
			</label>

			<div class="col-sm-10">
				<input type="text" class="required" name="jform[username]" value="" id="inputUsername" size="32"/>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2" for="inputEmail">
				<?php echo JText::_('COM_JUDIRECTORY_EMAIL'); ?>
				<span style="color: red">*</span>
			</label>

			<div class="col-sm-10">
				<input type="text" class="required email" name="jform[email]" value="" id="inputEmail" size="32"/>
			</div>
		</div>

		<div class="form-group">
			<label for="security_code" class="control-label col-sm-2">
				<?php echo JText::_('COM_JUDIRECTORY_CAPTCHA'); ?><span style="color: red">*</span>
			</label>
			<div class="col-sm-10">
				<?php echo JUDirectoryFrontHelperCaptcha::getCaptcha(false, null, false); ?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2"></label>

			<div class="col-sm-10">
				<button type="button" class="btn btn-default btn-primary" onclick="Joomla.submitbutton('subscribe.save')">
					<?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?>
				</button>
				<button type="button" class="btn btn-default"  onclick="Joomla.submitbutton('subscribe.cancel')">
					<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
				</button>
			</div>
		</div>

		<div>
			<?php echo JHtml::_('form.token'); ?>
			<input type="hidden" name="jform[listing_id]" value="<?php echo $this->listingId; ?>" />
			<input type="hidden" name="task" value="" />
		</div>
	</form>
</div>