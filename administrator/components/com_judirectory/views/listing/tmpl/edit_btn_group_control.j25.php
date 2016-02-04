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

if ($this->app->input->getInt('approve', 0) == 1)
{
	$totalNextPendingListings = JUDirectoryHelper::getTotalPendingListings('next', $this->item->id);
	$totalPrevPendingListings = JUDirectoryHelper::getTotalPendingListings('prev', $this->item->id);
	?>
	<div class="approval">
		<div class="approval-inner">
			<?php if ($totalPrevPendingListings > 0)
			{ ?>
				<button class="judir-previous btn btn-info" onclick="Joomla.submitbutton('pendinglisting.saveAndPrev')">
					<i class="icon-arrow-left-2"></i>
					<?php echo JText::sprintf('COM_JUDIRECTORY_SAVE_AND_PREV_N', $totalPrevPendingListings); ?>
				</button>
			<?php
			} ?>

			<div class="judir-approval-options">
				<div class="judir-option-approve-item input-prepend input-append pull-left">
					<span class="add-on">
						<input type="radio" name="approval_option" value="ignore" checked id="ignore-listing" />
					</span>
					<label for="ignore-listing" class="btn">
						<i class="icon-question"></i>
						<?php echo JText::_('COM_JUDIRECTORY_IGNORE'); ?>
					</label>
				</div>
				<div class="judir-option-approve-item input-prepend input-append pull-left">
					<span class="add-on">
						<input type="radio" name="approval_option" value="approve" id="approval-listing" />
					</span>
					<label for="approval-listing" class="btn btn-success">
						<i class="icon-checkmark-2"></i>
						<?php echo JText::_('COM_JUDIRECTORY_APPROVE'); ?>
					</label>
				</div>
				<div class="judir-option-approve-item input-prepend input-append pull-left">
					<span class="add-on">
						<input type="radio" name="approval_option" value="delete" id="reject-listing" />
					</span>
					<label for="reject-listing" class="btn btn-danger">
						<i class="icon-cancel"></i>
						<?php echo JText::_('COM_JUDIRECTORY_REJECT'); ?>
					</label>
				</div>
				<div class="clr"></div>
			</div>

			<?php if ($totalNextPendingListings > 0)
			{
				?>
				<button class="judir-next btn btn-info" onclick="Joomla.submitbutton('pendinglisting.saveAndNext')">
					<?php echo JText::sprintf('COM_JUDIRECTORY_SAVE_AND_NEXT_N', $totalNextPendingListings); ?>
					<i class="icon-arrow-right-2"></i>
				</button>
			<?php
			} ?>
		</div>
	</div>

	<div class="clr"></div>
<?php
}
?>