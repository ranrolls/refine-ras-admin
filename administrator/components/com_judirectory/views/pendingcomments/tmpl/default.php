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

JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

<div id="iframe-help"></div>

<form
	action="<?php echo JRoute::_('index.php?option=com_judirectory&view=pendingcomments'); ?>"
	method="post" name="adminForm" id="adminForm">
	<div id="j-main-container" class="span12">
		<?php
		
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php
		if (empty($this->items))
		{
			?>
			<div class="alert alert-no-items">
				<?php echo JText::_('COM_JUDIRECTORY_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php
		}
		else
		{
			?>
			<table class="table table-striped adminlist" id="data-list">
				<thead>
				<tr>
					<th style="width:2%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th style="width:25%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'cm.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:20%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_LISTING_TITLE', 'listing.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_USERNAME', 'ua.username', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_PARENT', 'cm.parent_id', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_CREATED', 'cm.created', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_REPORTS', 'total_reports', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_SUBSCRIPTIONS', 'total_subscriptions', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JText::_('COM_JUDIRECTORY_FIELD_APPROVE_COMMENT'); ?>
					</th>
					<th style="width:3%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_ID', 'cm.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
				</tfoot>

				<tbody>
				<?php
				foreach ($this->items AS $i => $item) :
					$canEdit    = $user->authorise('core.edit', 'com_judirectory') && $this->groupCanDoManage;
					$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own', 'com_judirectory') && $item->user_id == $user->id && $this->groupCanDoManage;
					$canChange  = $user->authorise('core.edit.state', 'com_judirectory') && $canCheckin && $this->groupCanDoManage;
					$author     = $item->author_name ? $item->author_name : JText::_('COM_JUDIRECTORY_GUEST') . ": " . $item->guest_name;
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td>
							<?php if ($item->checked_out) : ?>
								<?php
								echo JHtml::_('jgrid.checkedout', $i, $item->checked_out_name, $item->checked_out_time, 'pendingcomments.', $canCheckin || $user->authorise('core.manage', 'com_checkin'));
								?>
							<?php endif; ?>
							<?php if ($canEdit || $canEditOwn)
							{
								?>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=comment.edit&id=' . $item->id . '&approve=1'); ?>"><?php echo $item->title; ?></a>
							<?php
							}
							else
							{
								echo $item->title;
							}
							?>
						</td>
						<td>
							<?php if ($item->listing_id)
							{
								echo '<a href="index.php?option=com_judirectory&task=listing.edit&id=' . $item->listing_id . '" >' . $item->listing_title . '</a>';
							}
							?>
						</td>
						<td>
							<?php echo $author; ?>
						</td>
						<td>
							<?php echo $item->parent; ?>
						</td>
						<td>
							<?php echo $item->created; ?>
						</td>
						<td>
							<?php
							if ($item->total_reports)
							{
								echo '<a href="index.php?option=com_judirectory&view=reports&comment_id=' . $item->id . '" title="View reports">' . $item->total_reports . ' :Reports</a>';
							} ?>
						</td>
						<td>
							<?php
							if ($item->total_subscriptions)
							{
								echo '<a href="index.php?option=com_judirectory&view=subscriptions&comment_id=' . $item->id . '" title="View subscriptions">' . $item->total_subscriptions . ' :Subscriptions</a>';
							} ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('judirectoryadministrator.commentApproved', $item->approved, $i, $canChange, 'pendingcomments'); ?>
						</td>
						<td>
							<?php echo $item->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php
		} ?>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>