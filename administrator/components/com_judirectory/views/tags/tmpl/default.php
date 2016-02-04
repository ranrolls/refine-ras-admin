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
$saveOrder = ($listOrder == 'tag.ordering');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_judirectory&task=tags.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'data-list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

<div id="iframe-help"></div>

<form
	action="<?php echo JRoute::_('index.php?option=com_judirectory&view=tags'); ?>"
	method="post" name="adminForm" id="adminForm">
	<div id="j-main-container" class="span12">
		<?php
		
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array("filterButton" => false)));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped adminlist" id="data-list">
				<thead>
				<tr>
					<th style="width:2%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'tag.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th style="width:2%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th style="width:56%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'tag.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:15%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_TOTAL_LISTINGS', 'total_listings', $listDirn, $listOrder); ?>
					</th>
					<th style="width:15%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_PUBLISHED', 'tag.published', $listDirn, $listOrder); ?>
					</th>
					<th style="width:10%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_JUDIRECTORY_FIELD_ID', 'tag.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
				</tfoot>

				<tbody>
				<?php
				foreach ($this->items AS $i => $item) :
					$canEdit    = $user->authorise('core.edit',       'com_judirectory') && $this->groupCanDoManage;
					$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own',   'com_judirectory') && $item->created_by == $user->id && $this->groupCanDoManage;
					$canChange  = $user->authorise('core.edit.state', 'com_judirectory') && $canCheckin && $this->groupCanDoManage;
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';
							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
									<i class="icon-menu"></i>
								</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
							<?php endif; ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td>
							<?php if ($item->checked_out) : ?>
								<?php
								echo JHtml::_('jgrid.checkedout', $i, $item->checked_out_name, $item->checked_out_time, 'tags.', $canCheckin || $user->authorise('core.manage', 'com_checkin'));
								?>
							<?php endif; ?>
							<?php if ($canEdit || $canEditOwn)
							{
								?>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;task=tag.edit&amp;id=' . $item->id); ?>">
									<?php echo $item->title; ?>
								</a>
							<?php
							}
							else
							{
								?>
								<?php echo $item->title; ?>
							<?php
							} ?>
							<p class="small"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
						</td>
						<td>
							<?php echo (int) $item->total_listings; ?>
						</td>
						<td>
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', $canChange, 'cb'); ?>
						</td>
						<td>
							<?php echo $item->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>