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

?>
<div id="judir-container" class="jubootstrap component judir-container view-modcomments">
	<h2 class="judir-view-title"><?php echo JText::_('COM_JUDIRECTORY_MANAGE_COMMENTS'); ?></h2>

	<?php if (!is_array($this->items) || !count($this->items))
	{
		?>
		<div class="alert alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo JText::_('COM_JUDIRECTORY_NO_COMMENT'); ?>
		</div>
	<?php
	} ?>

	<form name="judir-form-comments" id="judir-form-comments" class="judir-form" method="post" action="#">

		<?php
		echo $this->loadTemplate('header');
		?>

		<table class="table table-striped table-bordered">
			<thead>
			<tr>
				<th style="width:5%" class="center">
					<input type="checkbox" name="judir-cbAll" id="judir-cbAll" value=""/>
				</th>
				<th class="center">
					<?php echo JText::_('COM_JUDIRECTORY_FIELD_TITLE'); ?>
				</th>
				<th style="width:20%" class="center">
					<?php echo JText::_('COM_JUDIRECTORY_FIELD_PARENT'); ?>
				</th>
				<th style="width:15%" class="center">
					<?php echo JText::_('COM_JUDIRECTORY_FIELD_GUEST_NAME'); ?>
				</th>
				<th style="width:15%" class="center">
					<?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED'); ?>
				</th>
				<th style="width:10%" class="center">
					<?php echo JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'); ?>
				</th>
				<th style="width:5%" class="center">
					<?php echo JText::_('COM_JUDIRECTORY_FIELD_ID'); ?>
				</th>
			</tr>
			</thead>

			<tbody>
			<?php
			if (is_array($this->items) && count($this->items))
			{
				// @todo recheck hosting
				require_once JPATH_SITE . '/components/com_judirectory/models/listing.php';
				$listingModel = JModelLegacy::getInstance('Listing', 'JUDirectoryModel');

				foreach ($this->items AS $i => $item)
				{
					$canEdit = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithComment($item->id, 'comment_edit');
					?>
					<tr>
						<td class="center">
							<input type="checkbox" checked="checked" class="judir-cb" name="cid[]"
							       value="<?php echo $item->id; ?>" id="judir-cb-<?php echo $i; ?>"/>
						</td>

						<td>
							<?php
							if ($item->checked_out)
							{
								if ($item->checkout_link)
								{
									$checkedOutUser = JFactory::getUser($item->checked_out);
									$checkedOutTime = JHtml::_('date', $item->checked_out_time);
									$tooltip  = JText::_('COM_JUDIRECTORY_EDIT_COMMENT');
									$tooltip .= '<br/>';
									$tooltip .= JText::sprintf('COM_JUDIRECTORY_CHECKED_OUT_BY', $checkedOutUser->name) . ' <br /> ' . $checkedOutTime;

									echo '<a class="hasTooltip btn btn-mini" title="' . $tooltip . '" href="' . $item->checkout_link . '"><i class="fa fa-lock"></i></a>';
								}
								else
								{
									echo '<a class="btn btn-xs"><i class="fa fa-lock"></i></a>';
								}
							}
							?>
							<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1); ?>

							<?php
							if ($canEdit)
							{
								?>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=modcomment.edit&id=' . $item->id); ?>">
									<?php echo $item->title; ?>
								</a>
							<?php
							}
							else
							{
								echo $item->title;
							}
							?>

							<a href="<?php echo JRoute::_('index.php?option=com_judirectory&view=commenttree&id=' . $item->id . '&tmpl=component'); ?>"
							   rel="{handler: 'iframe', size: {x: 570, y: 500}}" class="modal judir-comment-tree">
								<i class="fa fa-sitemap"></i>
							</a>
						</td>

						<td>
							<?php if ($item->level == 1)
							{
								$limitStart = $listingModel->getLimitStartForComment($item->id);
								?>
								<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getListingRoute($item->listing_id) . '&limitstart=' . $limitStart . '&resetfilter=1' . '#comment-item-' . $item->id); ?>">
									<?php echo $item->listing_title; ?></a>
							<?php
							}
							elseif ($item->level > 1)
							{
								$parentCommentObject = JUDirectoryFrontHelperComment::getCommentObject($item->parent_id, 'cm.id, cm.title');
								$limitStart          = $listingModel->getLimitStartForComment($parentCommentObject->id);
								?>
								<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getListingRoute($item->listing_id)); ?>">
									<?php echo $item->listing_title; ?>
								</a>
								<span> / </span>
								<a target="_blank"
								   href="<?php echo JRoute::_(JUDirectoryHelperRoute::getListingRoute($item->listing_id) . '&limitstart=' . $limitStart . '&resetfilter=1' . '#comment-item-' . $parentCommentObject->id); ?>">
									<?php echo $parentCommentObject->title; ?>
								</a>
							<?php
							} ?>
						</td>

						<td class="center">
							<?php
							if ($item->user_id > 0)
							{
								$userComment = JFactory::getUser($item->user_id);
								echo $userComment->get('name');
							}
							else
							{
								echo $item->guest_name;
							}
							?>
						</td>

						<td class="center">
							<?php echo $item->created; ?>
						</td>

						<td class="center">
							<?php if ($item->published == 1)
							{
								?>
								<a href="#" id="judir-comment-publish-<?php echo $i; ?>"
								   class="judir-comment-publish">
									<i class="fa fa-check"></i>
								</a>
							<?php
							}
							else
							{
								?>
								<a href="#" id="judir-comment-unpublish-<?php echo $i; ?>"
								   class="judir-comment-unpublish">
									<i class="fa fa-close"></i>
								</a>
							<?php
							} ?>
						</td>

						<td class="center">
							<?php echo $item->id; ?>
						</td>
					</tr>
				<?php
				}
			}
			?>
			</tbody>
		</table>

		<?php
			echo $this->loadTemplate('footer');
		?>

		<div>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>