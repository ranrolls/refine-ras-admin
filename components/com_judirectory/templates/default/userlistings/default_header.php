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

if ($this->allow_user_select_view_mode || $this->params->get('show_header_sort', 1) || $this->params->get('show_pagination', 1) == 1)
{
	if ($this->allow_user_select_view_mode && ($this->params->get('show_header_sort', 1) || $this->params->get('show_pagination', 1) == 1))
	{
		$sortPaginationSpan = array(3, 9);
	}
	elseif ($this->allow_user_select_view_mode)
	{
		$sortPaginationSpan = array(12, 0);
	}
	elseif ($this->params->get('show_header_sort', 1) || $this->params->get('show_pagination', 1) == 1)
	{
		$sortPaginationSpan = array(0, 12);
	}
	?>
	<div class="sort-pagination row clearfix">
		<?php
		if ($this->allow_user_select_view_mode)
		{
			?>
			<!-- View mode -->
			<div class="col-md-<?php echo $sortPaginationSpan[0]; ?>">
			<?php
				$list_selected = $grid_selected = "";
				if($this->view_mode == 2)
				{
					$grid_selected = "judir-selected btn-primary";
				}
				else
				{
					$list_selected = "judir-selected btn-primary";
				}
				?>
				<div class="judir-display btn-group hidden-xs hidden-sm">
					<span class="btn btn-default judir-list <?php echo $list_selected; ?>" data-view="judir-view-list"
					   title="<?php echo JText::_('COM_JUDIRECTORY_LIST_VIEW'); ?>"><i class="fa fa-th-list"></i></span>
					<span class="btn btn-default judir-grid <?php echo $grid_selected; ?>" data-view="judir-view-grid"
					   title="<?php echo JText::_('COM_JUDIRECTORY_GRID_VIEW'); ?>"><i class="fa fa-th"></i></span>
				</div>
			</div>
		<?php
		} ?>

		<?php
		if ($this->params->get('show_header_sort', 1) || $this->params->get('show_pagination', 1))
		{
			?>
			<!-- Sort + Pagination -->
			<div class="col-md-<?php echo $sortPaginationSpan[1]; ?>">
				<div class="pull-right">
					<?php
					// Listing filter
					if ($this->show_filter)
					{
						?>
						<div class="judir-filter-listing pull-left">
							<select name="filter" class="inputbox" onchange="this.form.submit()">
								<option value=""><?php echo JText::_('COM_JUDIRECTORY_FILTER_LISTING'); ?></option>
								<?php echo JHtml::_('select.options', $this->filter_options, 'value', 'text', $this->state->get('filter.type')); ?>
							</select>
						</div>
					<?php
					} ?>

					<?php
					if ($this->params->get('show_header_sort', 1))
					{
						?>
						<div id="sort" class="judir-sort">
							<select name="filter_order" class="input-medium sort-by" onchange="this.form.submit()">
								<?php echo JHtml::_('select.options', $this->order_name_array, 'value', 'text', $this->listing_order); ?>
							</select>
							<select name="filter_order_Dir" class="input-medium sort-direction"
							        onchange="this.form.submit()">
								<?php echo JHtml::_('select.options', $this->order_dir_array, 'value', 'text', $this->listing_dir); ?>
							</select>
						</div>
					<?php
					} ?>

					<?php
					if ($this->params->get('show_pagination', 1))
					{
						?>
						<div class="pagination-wrap">
							<div class="limitbox">
								<div
									class="display-number"><?php echo JText::_('COM_JUDIRECTORY_PAGINATION_DISPLAY'); ?></div>
								<?php echo $this->pagination->getLimitBox(); ?>
							</div>
						</div>
					<?php
					} ?>
				</div>
			</div>
		<?php
		} ?>
	</div>
<?php
} ?>