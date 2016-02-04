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
$group_id = $this->escape($this->state->get('filter.group_id'));
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

<div class="jubootstrap">

	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form
		action="<?php echo JRoute::_('index.php?option=com_judirectory&view=criterias'); ?>"
		method="post" name="adminForm" id="adminForm">
		<fieldset id="filter-bar">
			<div class="filter-search input-append pull-left">
				<label for="filter_search" class="filter-search-lbl element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" class="input-medium"
					placeholder="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"
					value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
					title="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>" />
				<button class="btn" rel="tooltip" type="submit"
					title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button class="btn" rel="tooltip" type="button"
					title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"
					onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>

			<div class="filter-select">
				<div class="pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_SELETEC_GROUP'); ?></label>
					<?php
					$criteriaGroupOptions = JUDirectoryHelper::getCriteriaGroupOptions();
					if ($criteriaGroupOptions)
					{
						array_unshift($criteriaGroupOptions, array('value' => '', 'text' => JText::_('JALL')));
						echo JHtml::_('select.genericlist', $criteriaGroupOptions, 'group_id', 'class="input-medium" onchange="this.form.submit()"', 'value', 'text', $group_id);
					}
					?>
				</div>

				<div class="pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
					<select name="directionTable" id="directionTable"
						class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>

						<option value="asc"
							<?php
							if ($listDirn == 'asc')
							{
								echo 'selected="selected"';
							}
							?>>
							<?php echo JText::_('COM_JUDIRECTORY_ASC'); ?>
						</option>

						<option value="desc"
							<?php
							if ($listDirn == 'desc')
							{
								echo 'selected="selected"';
							}
							?>><?php echo JText::_('COM_JUDIRECTORY_DESC'); ?>
						</option>
					</select>
				</div>

				<div class="pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('COM_JUDIRECTORY_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium"
						onchange="Joomla.orderTable()">
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
				<th style="width:2%" class="center hidden-phone"><input type="checkbox"
						onclick="Joomla.checkAll(this)" title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value=""
						name="checkall-toggle" /></th>
				<th style="width:25%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'title', $listDirn, $listOrder); ?>
				</th>
				<th style="width:20%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_GROUP_NAME', 'group_name', $listDirn, $listOrder); ?>
				</th>
				<th style="width:10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_WEIGHTS', 'weights', $listDirn, $listOrder); ?>
				</th>
				<th style="width:10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_REQUIRED', 'required', $listDirn, $listOrder); ?>
				</th>
				<th style="width:10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder && $this->groupCanDoManage) : ?>
						<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'criterias.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th style="width:10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_STATE', 'published', $listDirn, $listOrder); ?>
				</th>
				<th style="width:3%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ID', 'id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>

			<tfoot>
			<tr>
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
			</tfoot>

			<tbody>
			<?php
			$ordering = ($listOrder == 'ordering');
			$criteriagroupCandoManage = JUDirectoryHelper::checkGroupPermission("criteriagroup.edit");
			foreach ($this->items AS $i => $item) :
				$canEdit    = $user->authorise('core.edit',       'com_judirectory') && $this->groupCanDoManage;
				$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
				$canEditOwn = $user->authorise('core.edit.own',   'com_judirectory') && $item->created_by == $userId && $this->groupCanDoManage;
				$canChange  = $user->authorise('core.edit.state', 'com_judirectory') && $canCheckin && $this->groupCanDoManage;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td>
						<?php
						if ($item->checked_out && $this->groupCanDoManage)
						{
							$user = JFactory::getUser($item->checked_out);
							echo JHtml::_('jgrid.checkedout', $i, $user->username, $item->checked_out_time, 'criterias.', $canCheckin);
						}
						?>
						<?php if ($canEdit || $canEditOwn)
						{
							?>
							<a href="<?php echo $item->actionlink; ?>">
								<?php
								if ($item->tooltips)
								{
									echo JHtml::tooltip($item->tooltips, $item->title, 'tooltip.png', $item->title, '', false);
								}
								else
								{
									echo $item->title;
								}
								?>
							</a>
						<?php
						}
						else
						{
							if ($item->tooltips)
							{
								echo JHtml::tooltip($item->tooltips, $item->title, 'tooltip.png', $item->title, '', false);
							}
							else
							{
								echo $item->title;
							}
						} ?>
					</td>
					<td class="center">
						<?php if ($criteriagroupCandoManage)
						{
							?>
							<a href="<?php echo 'index.php?option=com_judirectory&amp;task=criteriagroup.edit&amp;id=' . $item->group_id; ?>"><?php echo $item->group_name; ?></a>
						<?php
						}
						else
						{
							echo $item->group_name;
						} ?>
					</td>
					<td class="center">
						<?php echo $item->weights; ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('judirectoryadministrator.criteriaRequired', $item->required, $i, $canChange); ?>
					</td>
					<td class="order">
						<?php if ($canChange) : ?>
							<?php if ($saveOrder) : ?>
								<?php if ($listDirn == 'asc') : ?>
									<span><?php echo $this->pagination->orderUpIcon($i, ($item->group_id == @$this->items[$i - 1]->group_id), 'criterias.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->group_id == @$this->items[$i + 1]->group_id), 'criterias.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span><?php echo $this->pagination->orderUpIcon($i, ($item->group_id == @$this->items[$i - 1]->group_id), 'criterias.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->group_id == @$this->items[$i + 1]->group_id), 'criterias.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
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
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'criterias.', true, 'cb'); ?>
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