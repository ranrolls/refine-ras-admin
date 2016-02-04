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
<div class="judir-frontend-toolbar clearfix">
	<div class="pull-right">
		<button id="judir-edit-comment" class="btn"><i
				class="fa fa-edit"></i> <?php echo JText::_('COM_JUDIRECTORY_EDIT'); ?></button>
		<button id="judir-delete-comments" class="btn"><i
				class="fa fa-trash-o"></i> <?php echo JText::_('COM_JUDIRECTORY_DELETE'); ?></button>
		<button id="judir-publish-comments" class="btn"><i
				class="fa fa-check"></i> <?php echo JText::_('COM_JUDIRECTORY_PUBLISH'); ?></button>
		<button id="judir-unpublish-comments" class="btn"><i
				class="fa fa-close"></i> <?php echo JText::_('COM_JUDIRECTORY_UNPUBLISH'); ?></button>
	</div>
</div>

<div class="sort-pagination clearfix">
	<div class="judir-filter-search input-append pull-left">
		<input type="text" name="filter_search" id="filter_search" class="input-medium"
		       placeholder="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"
		       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
		       title="<?php echo JText::_('COM_JUDIRECTORY_FILTER_SEARCH'); ?>"/>
		<button type="submit" class="btn"><?php echo JText::_('COM_JUDIRECTORY_FILTER_SUBMIT'); ?></button>
		<button type="button" class="btn"
		        onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_JUDIRECTORY_FILTER_CLEAR'); ?></button>
	</div>

	<div class="judir-sort pull-right">
		<select name="filter_order" class="judir-order-sort input-medium" onchange="this.form.submit()">
			<?php echo JHtml::_('select.options', $this->order_name_array, 'value', 'text', $this->listOrder); ?>
		</select>
		<select name="filter_order_Dir" class="judir-order-dir input-small" onchange="this.form.submit()">
			<?php echo JHtml::_('select.options', $this->order_dir_array, 'value', 'text', $this->listDirn); ?>
		</select>
	</div>
</div>