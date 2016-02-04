<?php
/*------------------------------------------------------------------------
# default.php - fandb Component
# ------------------------------------------------------------------------
# author    Refine
# copyright Copyright (C) 2015. All Rights Reserved
# license   hello
# website   www.refine-interactive.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.output');
?>
<div id="fandb-fandb">
	<?php foreach($this->items as $item){ ?>
		<?php
		if(empty($item->alias)){
			$item->alias = $item->title;
		};
		$item->alias = JFilterOutput::stringURLSafe($item->alias);
		$item->linkURL = JRoute::_('index.php?option=com_fandb&view=fand&id='.$item->id.':'.$item->alias);
		?>
		<p><strong>Title</strong>: <a href="<?php echo $item->linkURL; ?>"><?php echo $item->title; ?></a></p>
		<p><strong>Link URL</strong>: <a href="<?php echo $item->linkURL; ?>">Go to page</a> - <?php echo $item->linkURL; ?></p>
		<br /><br />
	<?php }; ?>
</div>
