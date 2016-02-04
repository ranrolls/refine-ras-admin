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

<div id="judir-container" class="jubootstrap component judir-container view-featured <?php echo $this->pageclass_sfx; ?>">
	<div class="pull-right">
		<?php
		if ($this->show_feed)
		{
			?>
			<a class="hasTooltip btn" href="<?php echo $this->rss_link; ?>"
			   title="<?php echo JText::_('COM_JUDIRECTORY_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
				<i class="fa fa-rss"></i>
			</a>
		<?php
		}
		?>
	</div>

	<?php
	if ($this->params->get('show_page_heading') && $this->params->get('page_heading'))
	{
		?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php
	} ?>

	<h2><?php echo JText::_('COM_JUDIRECTORY_FEATURED_LISTINGS'); ?></h2>

	<?php
	if (count($this->items))
	{
		echo $this->loadTemplate('listings');
	}
	?>
</div>