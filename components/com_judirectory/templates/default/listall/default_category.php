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
<div class="judir-cat clearfix">
	<h2 class="cat-title"><?php echo JText::sprintf('COM_JUDIRECTORY_LIST_ALL_LISTINGS', $this->category->title); ?></h2>
</div>

<div class="clearfix">
	<a class="btn btn-default btn-primary btn-xs pull-left" href="#"
	   onclick="javascript:jQuery('.filter-form').slideToggle('300'); return false;">
		<i class="fa fa-filter"></i> <?php echo JText::_('COM_JUDIRECTORY_FILTER'); ?>
	</a>
	<a class="btn btn-default btn-xs pull-right"
	   href="<?php echo JUDirectoryHelperRoute::getCategoryRoute($this->category->id); ?>">
		<i class="fa fa-folder-open"></i> <?php echo JText::_('COM_JUDIRECTORY_THIS_CATEGORY'); ?>
	</a>
</div>