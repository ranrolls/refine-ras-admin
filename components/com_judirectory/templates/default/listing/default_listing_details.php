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
<div class="judir-listing-details clearfix">
<?php
if(JUDIRPROVERSION)
{
	echo $this->loadTemplate('private_actions');
}
?>

<?php

// Listing heading title
if (isset($this->item->fields['title']) && $this->item->fields['title']->canView())
{
	?>
	<h2 class="listing-title" itemscope="" itemtype="http://schema.org/Thing">

		<span itemprop="name" class="blue_text"><?php echo $this->item->fields['title']->getOutput(array("view" => "details", "template" => $this->template)); ?></span>
		<?php
		if ($this->item->label_new)
		{
			?>
			<span class="label label-new"><?php echo JText::_('COM_JUDIRECTORY_NEW'); ?></span>
		<?php
		}
		if ($this->item->label_updated)
		{
			?>
			<span class="label label-updated"><?php echo JText::_('COM_JUDIRECTORY_UPDATED'); ?></span>
		<?php
		}
		if ($this->item->label_hot)
		{
			?>
			<span class="label label-hot"><?php echo JText::_('COM_JUDIRECTORY_HOT'); ?></span>
		<?php
		}
		if ($this->item->label_featured)
		{
			?>
			<span class="label label-featured"><?php echo JText::_('COM_JUDIRECTORY_FEATURED'); ?></span>
		<?php
		} ?>
	</h2>
<?php
	echo $this->item->event->afterDisplayTitle;
} ?>

<?php
	echo $this->loadTemplate('meta_info');
?>

<div class="listing-box row" itemscope="" itemtype="http://schema.org/Thing">
<div class="">
<div class="column-left col-md-6">	<?php if (isset($this->item->fields['image']) && $this->item->fields['image']->canView())
		{
			?>
			<div class="image">
				<?php echo $this->item->fields['image']->getOutput(array("view" => "details", "template" => $this->template)); ?>
			</div>
		<?php
		} ?></div>
<div class="column-left col-md-6"><?php if (isset($this->item->fields['description']) && $this->item->fields['description']->canView())
		{
			?>
			<div class="description" itemprop="description">
				<?php
				echo $this->item->event->beforeDisplayContent;
				echo $this->item->fields['description']->getOutput(array("view" => "details", "template" => $this->template));
				echo $this->item->event->afterDisplayContent;
				?>
			</div>
		<?php
		} ?></div>
</div>
	
  
	<!-- /.column-left -->

	<!--<div class="column-right col-md-12">
	<?php
	if(JUDIRPROVERSION)
	{
		echo $this->loadTemplate('actions');
	}
	?>

	<?php /*?><?php
		echo $this->loadTemplate('quick_info');
	?>

	<?php
		echo $this->loadTemplate('multi_rating');
	?><?php */?>

	<?php
	if (count($this->item->fields))
	{
		echo $this->loadTemplate('extra_fields');
	} ?>

	<?php
		echo $this->loadTemplate('tags');
	?>
	</div>-->
	<!-- /.column-right -->
</div>
<!-- /.listing-box -->
</div><!-- /.listing-details -->

<div class="bottom_hide"></div> 