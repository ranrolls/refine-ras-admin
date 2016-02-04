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

if ($this->approvalForm)
{
	// @todo recheck hosting
	require_once JPATH_SITE . '/components/com_judirectory/models/modpendinglistings.php';
	JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_judirectory/models');
	$modelModPendingListings = JModelLegacy::getInstance('ModPendingListings','JUDirectoryModel');
	$totalNext     = $modelModPendingListings->getTotalListingsModCanApprove('next', $this->item->id);
	$totalPrevious = $modelModPendingListings->getTotalListingsModCanApprove('prev', $this->item->id);
	?>
	<div class="judir-submit-buttons">
		<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('modpendinglisting.save')">
			<i class="fa fa-save"></i> <?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?>
		</button>
		<button type="button" class="btn" onclick="Joomla.submitbutton('modpendinglisting.cancel');">
			<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
		</button>
	</div>

	<div class="judir-approval-container clearfix">
		<div class="judir-approval-inner">
			<?php if ($totalPrevious)
			{
				?>
				<button type="button" class="judir-previous btn btn-info"
				        onclick="Joomla.submitbutton('modpendinglisting.saveAndPrev')">
					<i class="fa fa-arrow-circle-o-left"></i>
					<?php echo JText::sprintf('COM_JUDIRECTORY_SAVE_AND_PREV_N', $totalPrevious); ?>
				</button>
			<?php
			} ?>

			<div class="judir-approval-options">
				<div class="judir-option-approve-item input-prepend input-append pull-left">
					<span class="add-on">
						<input type="radio" name="approval_option" value="ignore" checked id="ignore-listing"/>
					</span>
					<label for="ignore-listing" class="btn">
						<i class="fa fa-question-circle"></i>
						<?php echo JText::_('COM_JUDIRECTORY_IGNORE'); ?>
					</label>
				</div>
				<div class="judir-option-approve-item input-prepend input-append pull-left">
					<span class="add-on">
						<input type="radio" name="approval_option" value="approve" id="approval-listing"/>
					</span>
					<label for="approval-listing" class="btn btn-success">
						<i class="fa fa-check-circle"></i>
						<?php echo JText::_('COM_JUDIRECTORY_APPROVE'); ?>
					</label>
				</div>
				<div class="judir-option-approve-item input-prepend input-append pull-left">
					<span class="add-on">
						<input type="radio" name="approval_option" value="delete" id="reject-listing"/>
					</span>
					<label for="reject-listing" class="btn btn-danger">
						<i class="fa fa-times"></i>
						<?php echo JText::_('COM_JUDIRECTORY_REJECT'); ?>
					</label>
				</div>
				<div class="clr"></div>
			</div>

			<?php if ($totalNext)
			{
				?>
				<button type="button" class="judir-next btn btn-info"
				        onclick="Joomla.submitbutton('modpendinglisting.saveAndNext')">
					<?php echo JText::sprintf('COM_JUDIRECTORY_SAVE_AND_NEXT_N', $totalNext); ?>
					<i class="fa fa-arrow-circle-o-right"></i>
				</button>
			<?php
			} ?>
		</div>
	</div>
<?php
}
else
{
	?>
	<div class="judir-submit-buttons">
		<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('form.save')">
			<i class="fa fa-save"></i> <?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?>
		</button>

		<button type="button" class="btn" onclick="Joomla.submitbutton('form.cancel')">
			<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
		</button>
	</div>
<?php
}
?>
