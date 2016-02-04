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

$app = JFactory::getApplication();
$function = $app->input->get('function', 'jSelectTag');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();
$saveOrder = $listOrder == 'ordering';
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

<form action="<?php echo JRoute::_('index.php?option=com_judirectory&view=tags&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1'); ?>" method="post" name="adminForm" id="adminForm">
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
			<th class="nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'tag.title', $listDirn, $listOrder); ?>
			</th>
			<th width="30%" class="center nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TOTAL_LISTINGS', 'total_listings', $listDirn, $listOrder); ?>
			</th>
			<th width="15%" class="center nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_PUBLISHED', 'tag.published', $listDirn, $listOrder); ?>
			</th>
			<th style="width:5%" class="center nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ID', 'tag.id', $listDirn, $listOrder); ?>
			</th>
		</tr>
		</thead>

		<tfoot>
		<tr>
			<td colspan="4"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
		</tfoot>

		<tbody>
		<?php
		foreach ($this->items AS $i => $item): ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>');">
						<?php echo $item->title; ?>
					</a>
				</td>
				<td class="center">
					<?php echo (int) $item->total_listings; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', false, 'cb'); ?>
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