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

$user = JFactory::getUser();
?>
<div id="judir-container" class="jubootstrap component judir-container view-userlistings <?php echo $this->pageclass_sfx; ?>">

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
	if ($this->show_feed)
	{
		?>
		<div class="pull-right">
			<a class="hasTooltip btn btn-default" href="<?php echo $this->rss_link; ?>"
			   title="<?php echo JText::_('COM_JUDIRECTORY_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
				<i class="fa fa-rss"></i>
			</a>
		</div>
	<?php
	}
	?>

	<h2><?php echo JText::sprintf('COM_JUDIRECTORY_USER_LISTINGS_HEADING', $this->userListingsUserName); ?></h2>

	<?php
	if (count($this->items))
	{
		echo $this->loadTemplate('listings');
	}
	?>
</div>





