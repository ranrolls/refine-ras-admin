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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();
$saveOrder = ($listOrder == 'tag.ordering');
?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&view=tags'); ?>" method="post" name="adminForm" id="adminForm">
		<fieldset id="filter-bar">
			<div class="filter-search input-append pull-left">
				<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" class="input-medium"
					placeholder="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"
					value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
					title="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH_DESC'); ?>" />
				<button class="btn" rel="tooltip" type="submit"
					title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>

				<button class="btn" rel="tooltip" type="button"
					title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"
					onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>

			<div class="filter-select">
				<div class="pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
					<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
						<option value="asc" <?php if ($listDirn == 'asc')
						{
							echo 'selected="selected"';
						} ?>><?php echo JText::_('COM_JUDIRECTORY_ASC'); ?></option>
						<option value="desc" <?php if ($listDirn == 'desc')
						{
							echo 'selected="selected"';
						} ?>><?php echo JText::_('COM_JUDIRECTORY_DESC'); ?></option>
					</select>
				</div>
				<div class="pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('COM_JUDIRECTORY_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('COM_JUDIRECTORY_SORT_BY'); ?></option>
						<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
					</select>
				</div>
			</div>
		</fieldset>

		<div class="clearfix"></div>

		<table class="table table-striped adminlist">
			<thead>
			<tr>
				<th style="width:2%" class="center hidden-phone">
					<input type="checkbox" onclick="Joomla.checkAll(this)" title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value="" name="checkall-toggle" />
				</th>
				<th style="width:45%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'title', $listDirn, $listOrder); ?>
				</th>
				<th style="width:15%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TOTAL_LISTINGS', 'total_listings', $listDirn, $listOrder); ?>
				</th>
				<th style="width:15%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder && $this->groupCanDoManage) : ?>
						<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'tags.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th style="width:15%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_PUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
				<th style="width:8%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ID', 'id', $listDirn, $listOrder); ?>
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

			foreach ($this->items AS $i => $item):
				$canEdit    = $user->authorise('core.edit',       'com_judirectory') && $this->groupCanDoManage;
				$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
				$canEditOwn = $user->authorise('core.edit.own',   'com_judirectory') && $item->created_by == $user->id && $this->groupCanDoManage;
				$canChange  = $user->authorise('core.edit.state', 'com_judirectory') && $canCheckin && $this->groupCanDoManage;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="">
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
						<p class="smallsub"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
					</td>
					<td class="center">
						<?php echo (int) $item->total_listings; ?>
					</td>
					<td class="order center">
						<?php if ($canChange) : ?>
							<?php if ($saveOrder) : ?>
								<?php if ($listDirn == 'asc') : ?>
									<span><?php echo $this->pagination->orderUpIcon($i, true, 'tags.orderup', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
									<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'tags.orderdown', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span><?php echo $this->pagination->orderUpIcon($i, true, 'tags.orderdown', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
									<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'tags.orderup', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5"
								value="<?php echo $item->ordering; ?>" <?php echo $disabled ?>
								class="text-area-order" />
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', $canChange, 'cb'); ?>
					</td>
					<td class="center">
						<?php echo $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>