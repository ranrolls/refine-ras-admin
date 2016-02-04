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
<div id="judir-container" class="jubootstrap component judir-container view-modpermission">

<h2 class="judir-view-title"><?php echo JText::_('COM_JUDIRECTORY_MODERATOR_PERMISSION'); ?></h2>

<table class="table table-striped table-bordered">
<thead>
<tr>
	<th style="width: 200px" class="center">
		<?php echo JText::_('COM_JUDIRECTORY_FIELD'); ?>
	</th>

	<th class="center">
		<?php echo JText::_('COM_JUDIRECTORY_VALUE'); ?>
	</th>
</tr>
</thead>

<tbody>
<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_NAME'); ?>
	</td>
	<td>
		<?php echo $this->item->name; ?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_DESCRIPTION'); ?>
	</td>
	<td>
		<?php echo $this->item->description; ?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_CATEGORIES'); ?>
	</td>
	<td>
		<?php
		echo $this->item->assignedCategories;
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_VIEW'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_view ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_VIEW_UNPUBLISHED'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_view_unpublished ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_CREATE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_create ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_EDIT'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_edit ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_EDIT_STATE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_edit_state ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_EDIT_OWN'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_edit_own ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_DELETE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_delete ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_DELETE_OWN'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_delete_own ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_APPROVE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->listing_approve ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_COMMENT_EDIT'); ?>
	</td>
	<td>
		<?php
		echo $this->item->comment_edit ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_COMMENT_EDIT_STATE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->comment_edit_state ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_COMMENT_DELETE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->comment_delete ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_COMMENT_APPROVE'); ?>
	</td>
	<td>
		<?php
		echo $this->item->comment_approve ? JText::_('JYES') : JText::_('JNO');
		?>
	</td>
</tr>
</tbody>
</table>
</div>
