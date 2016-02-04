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
?>
<div id="judir-container" class="jubootstrap component judir-container view-tags">
	<h2><?php echo JText::_('COM_JUDIRECTORY_TAGS'); ?></h2>

	<form name="judir-form-tags" id="judir-form-tags" class="judir-form-tags" method="post" action="#">
		<div class="sort-pagination clearfix">
			<div class="pull-right">
				<div id="sort" class="judir-sort">
					<select name="filter_order" class="judir-order-sort input-medium" onchange="this.form.submit()">
						<?php echo JHtml::_('select.options', $this->order_name_array, 'value', 'text', $this->listOrder); ?>
					</select>
					<select name="filter_order_Dir" class="judir-order-dir input-small" onchange="this.form.submit()">
						<?php echo JHtml::_('select.options', $this->order_dir_array, 'value', 'text', $this->listDirn); ?>
					</select>
				</div>
				<div class="pagination-wrap">
					<div class="limitbox">
						<div
							class="display-number"><?php echo JText::_('COM_JUDIRECTORY_PAGINATION_DISPLAY'); ?></div>
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<?php foreach ($this->items AS $key => $item)
				{
				?>
				<div class="col-md-6">
					<i class="fa fa-tag"></i>
					<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getTagRoute($item->id)); ?>">
						<?php echo $item->title; ?><span> (<?php echo $item->total_listings; ?>)</span></a>
				</div>
				<?php
				$key++;
				if (($key % 2) == 0 && $key < count($this->items))
				{
				?>
			</div>
			<div class="row">
				<?php
				} ?>

				<?php
				} ?>
			</div>
		</div>

		<div class="pagination-wrap clearfix">
			<?php
			if ($this->pagination->get('pages.total') > 1)
			{
				?>
				<div class="pages-links">
					<?php echo $this->pagination->getPagesLinks(); ?>
				</div>

				<div class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</div>
			<?php
			} ?>
		</div>
	</form>
</div>