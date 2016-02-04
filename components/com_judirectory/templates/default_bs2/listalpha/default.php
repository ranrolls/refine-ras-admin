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

<div id="judir-container" class="jubootstrap component judir-container view-listalpha <?php echo $this->pageclass_sfx; ?>">

	<div id="judir-comparison-notification"></div>

	<?php
	if ($this->params->get('show_page_heading') && $this->params->get('page_heading'))
	{
		?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php
	} ?>

	<?php
	if (count($this->listAlpha))
	{
		$needle = array('listalpha'=> array($this->cat_id));
		?>
		<div class="judir-alpha-list pagination clearfix">
			<ul>
				<?php
				foreach ($this->listAlpha AS $alpha)
				{
					if ($this->model->checkAlpha($alpha))
					{
						if (strtoupper($alpha) == strtoupper($this->alphaKeyword))
						{
							echo '<li class="active"><a href="' . JRoute::_('index.php?option=com_judirectory&view=listalpha&id=' . $this->cat_id . '&alpha=' . $alpha . '&Itemid='.JUDirectoryHelperRoute::findItemId($needle)) . '">' . $alpha . '</a></li>';
						}
						else
						{
							echo '<li><a href="' . JRoute::_('index.php?option=com_judirectory&view=listalpha&id=' . $this->cat_id . '&alpha=' . $alpha . '&Itemid='.JUDirectoryHelperRoute::findItemId($needle) ) . '">' . $alpha . '</a></li>';
						}
					}
					else
					{
						echo '<li class="disabled"><a>' . $alpha . '</a></li>';
					}
				}
				?>
			</ul>
		</div>
	<?php
	}
	?>

	<?php
	if (count($this->categories) > 0)
	{
		?>
		<div class="judir-category-list">
			<h2 class="title"><?php echo JText::_('COM_JUDIRECTORY_CATEGORIES'); ?></h2>
			<ul>
				<?php
				foreach ($this->categories AS $key => $category)
				{
					?>
					<li>

						<a href="<?php

						echo JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($category->id)); ?>">
							<?php echo $category->title ?>
						</a>
					</li>
				<?php
				} ?>
			</ul>
		</div>
	<?php
	} ?>

	<?php
	if (count($this->items))
	{
		echo $this->loadTemplate('listings');
	}
	?>

	<?php
	if (count($this->items) == 0 && count($this->categories) == 0)
	{
		?>
		<div class="alert alert-no-items">
			<?php echo JText::sprintf("COM_JUDIRECTORY_THERE_IS_NO_CATEGORY_OR_LISTING_START_WITH_X", $this->alphaKeyword); ?>
		</div>
	<?php
	}
	?>

</div>