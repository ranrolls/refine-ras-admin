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
<div class="map">
	<h3><?php echo JText::_('COM_JUDIRECTORY_MAP'); ?></h3>
	<?php
	$fieldLocation = $this->item->fieldLocations;
	echo $fieldLocation->getDisplayPrefixText() . ' ' . $fieldLocation->getOutput(array("view" => "details", "template" => $this->template)) . ' ' . $fieldLocation->getDisplaySuffixText();
	?>
</div>