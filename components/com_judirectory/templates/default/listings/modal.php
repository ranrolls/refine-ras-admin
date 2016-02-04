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

JHtml::addIncludePath(JPATH_SITE . '/components/com_judirectory/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('script', 'system/multiselect.js', false, true);
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$app = JFactory::getApplication();
$function = $app->input->get('function', 'jSelectListing');

?>

<div id="judir-container" class="jubootstrap component judir-container view-listings">
	<form
		action="<?php echo JRoute::_('index.php?option=com_judirectory&&view=listings&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>"
		method="post" name="adminForm" id="adminForm">
		<fieldset id="filter-bar">
			<div class="filter-search row">
				<div class="col-xs-6">
					<div class="input-group">
						<input type="text" name="filter_search" id="filter_search" class="form-control"
							value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
							title="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"
							placeholder="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"/>
						<span class="input-group-btn">
							<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
							<button type="button" class="btn btn-default"
								onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
						</span>
					</div>
				</div>
			</div>
			<br/>
			<div class="filter-select row">
				<div class="col-xs-12">			
					<div class="form-inline">
						<select name="filter_featured" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('COM_JUDIRECTORY_SELECT_FEATURED'); ?></option>
							<option
								value="1" <?php echo $this->state->get('filter.featured') === '1' ? "selected" : ""; ?>><?php echo JText::_('COM_JUDIRECTORY_FEATURED'); ?></option>
							<option
								value="0" <?php echo $this->state->get('filter.featured') === '0' ? "selected" : ""; ?>><?php echo JText::_('COM_JUDIRECTORY_UNFEATURED'); ?></option>
						</select>
						<select name="filter_category" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('COM_JUDIRECTORY_SELECT_CATEGORY'); ?></option>
							<?php
							$options = JUDirectoryHelper::getCategoryOptions();
							echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.category'));
							?>
						</select>
						<select name="filter_access" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS'); ?></option>
							<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access')); ?>
						</select>
					</div>
				</div>
			</div>
		</fieldset>

		<div class="clr"></div>

		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_TITLE', 'listing.title', $listDirn, $listOrder); ?>
				</th>
				<th style="width:15%">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_CATEGORY', 'c.title', $listDirn, $listOrder); ?>
				</th>
				<th style="width:15%">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_CREATED_BY', 'listing.created_by', $listDirn, $listOrder); ?>
				</th>
				<th style="width:15%">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ACCESS', 'listing.access', $listDirn, $listOrder); ?>
				</th>
				<th style="width:15%">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_CREATED', 'listing.created', $listDirn, $listOrder); ?>
				</th>
				<th style="width: 1%;" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_JUDIRECTORY_FIELD_ID', 'listing.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>

			<tbody>
			<?php foreach ($this->items AS $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<a class="pointer"
						   onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes(str_replace(array("\r", "\n"), "", $item->title))); ?>', '<?php echo $item->image; ?>');">
							<?php echo $this->escape($item->title); ?>
						</a>
					</td>
					<td class="center">
						<?php echo $this->escape($item->category_title); ?>
					</td>

					<td class="center">
						<?php echo $this->escape($item->created_by); ?>
					</td>

					<td class="center">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<td class="center nowrap">
						<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
					</td>

					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<div class="pagination-wrap clearfix">
			<?php
			if ($this->params->get('show_pagination', 1) == 2)
			{
				?>
				<div class="limitbox">
					<div class="display-number">
						<?php echo JText::_('COM_JUDIRECTORY_PAGINATION_DISPLAY'); ?>
					</div>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php
			}
			?>
			<div class="pages-links">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>

			<div class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</div>
		</div>

		<div>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>