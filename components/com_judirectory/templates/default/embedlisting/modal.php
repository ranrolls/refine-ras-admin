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
JHtml::_('script', 'system/multiselect.js', false, true);

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$function = $app->input->get('function', 'jSelectFile');
$ename = $app->input->get('e_name', 'jform_articletext', 'string');
$field_display = $this->state->get('field_display', array());
if(!$field_display){
	$field_display = array('title', 'image', 'introtext', 'tag');
}
?>
<div id="judir-container" class="jubootstrap component judir-container view-embedlisting">
	<div class="judirectory-manager">
		<form
			action="<?php echo JRoute::_('index.php?option=com_judirectory&view=embedlisting&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>"
			method="post" name="adminForm" id="adminForm">
			<div class="clearfix">
				<div class="filter-search input-append pull-left">
					<input type="text" name="filter_search" id="filter_search" class="input-medium"
					       placeholder="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"
					       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
					       title="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"/>
					<button class="btn btn-default" rel="tooltip" type="submit"
					        title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button class="btn btn-default" rel="tooltip" type="button"
					        title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"
					        onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
				<div class="filter-select pull-left">
					<select style="margin-left: 0" name="filter_featured" class="input-medium"
					        onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_JUDIRECTORY_SELECT_FEATURED'); ?></option>
						<option
							value="1" <?php echo $this->state->get('filter.featured') === '1' ? "selected" : ""; ?>><?php echo JText::_('COM_JUDIRECTORY_FEATURED'); ?></option>
						<option
							value="0" <?php echo $this->state->get('filter.featured') === '0' ? "selected" : ""; ?>><?php echo JText::_('COM_JUDIRECTORY_UNFEATURED'); ?></option>
					</select>

					<select name="filter_catid" class="input-medium" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_JUDIRECTORY_SELECT_CATEGORY'); ?></option>
						<?php
						$options = JUDirectoryHelper::getCategoryOptions();
						echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.catid'));
						?>
					</select>
				</div>
			</div>

			<div class="filter-select" id="options">
				<div class="col-sm-4">
					<label class="checkbox"><input type="checkbox" name="link_icon" checked="checked" id="link-icon" value="" /><?php echo JText::_('COM_JUDIRECTORY_LINK_ICON'); ?></label>
					<label class="checkbox"><input type="checkbox" name="link_title" checked="checked" id="link-title" value="" /><?php echo JText::_('COM_JUDIRECTORY_LINK_TITLE'); ?></label>
					<a class="btn btn-default" name="insert" id="insert" onclick="return InsertDocument('<?php echo $ename; ?>');"><?php echo JText::_('COM_JUDIRECTORY_INSERT_ALL_SELECTED'); ?></a>
				</div>
				<div class="col-sm-4">
					<div class="control-group" >
						<div class="controls" >
							<?php
							echo JHtml::_('select.genericlist', $this->getFieldDisplay(), 'field_display[]',
								'class="inputbox hasTooltip"  multiple="multiple" style="width: 100%" title="'.JText::_('COM_JUDIRECTORY_DISPLAYED_FIELDS').'"',
								'value', 'text', $field_display, 'field_display');
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="clr"></div>

			<table class="adminlist table table-striped table-bordered" id="listing-list">
				<thead>
				<tr>
					<th style="width:2%" class="hidden-xs hidden-sm">
						<input type="checkbox" onclick="Joomla.checkAll(this)"
						       title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value=""
						       name="checkall-toggle"/>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'listing.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:20%">
						<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ACCESS', 'listing.access', $listDirn, $listOrder); ?>
					</th>
					<th style="width:15%">
						<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_CATEGORY', 'c.title', $listDirn, $listOrder); ?>
					</th>
					<th style="width:15%">
						<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_CREATED', 'listing.created', $listDirn, $listOrder); ?>
					</th>
					<th style="width: 1%;" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ID', 'listing.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>

				<tbody>
				<?php foreach ($this->items AS $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
						<td>
							<label for="cb<?php echo $i; ?>"><?php echo $this->escape($item->title); ?></label>
						</td>
						<td class="center">
							<?php echo $this->escape($item->access_title); ?>
						</td>
						<td class="center">
							<?php echo $this->escape($item->category_title); ?>
						</td>
						<td class="center nowrap">
							<?php echo JHtml::_('date', $item->created, 'Y-m-d H:i:s'); ?>
						</td>

						<td class="center">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<div>
				<input type="hidden" name="task" value=""/>
				<input type="hidden" name="boxchecked" value="0"/>
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
				<?php echo JHtml::_('form.token'); ?>
			</div>
			<br/>
			<a class="btn btn-default" name="insert" id="insert"
			   onclick="return InsertListing('<?php echo $ename; ?>');"><?php echo JText::_('COM_JUDIRECTORY_INSERT_ALL_SELECTED'); ?></a>
		</form>
	</div>
</div>