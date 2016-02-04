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

if (!$isDetailsView)
{
	$this->setAttribute("href", JRoute::_(JUDirectoryHelperRoute::getListingRoute($this->listing_id)), "output");
	echo '<a ' . $this->getAttribute(null, null, "output") . '>' . $value . '</a>';
}
else
{
	echo '<span ' . $this->getAttribute(null, null, "output") . '>' . $value . '</span>';
}

?>