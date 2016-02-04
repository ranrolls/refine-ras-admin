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
<div id="judir-container"
     class="jubootstrap component judir-container view-listall category-<?php echo $this->category->id; ?> <?php echo isset($this->tl_catid) ? 'tlcat-id-' . $this->tl_catid : ""; ?> <?php echo $this->category->class_sfx; ?> <?php echo $this->pageclass_sfx; ?>">

	<div id="judir-comparison-notification"></div>

	<?php
	if ($this->category->can_submit_listing && $this->params->get('show_submit_listing_btn_in_category', 1))
	{
		echo '<a class="hasTooltip btn" title="' . JText::_('COM_JUDIRECTORY_ADD_LISTING') . '" href="' . $this->category->submit_listing_link . '"><i class="fa fa-file-o"></i></a>';
	}
	?>

	<?php
	if (count($this->category))
	{
		echo $this->loadTemplate('category');
	}

	echo $this->loadTemplate('filterform');

	if (count($this->items))
	{
		echo $this->loadTemplate('listings');
	}
	else
	{
		?>
		<div class="alert alert-no-items"><?php echo JText::_('COM_JUDIRECTORY_NO_ITEM_FOUND'); ?></div>
	<?php
	}
	?>
</div>