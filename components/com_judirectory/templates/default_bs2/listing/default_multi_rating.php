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

if (JUDirectoryHelper::hasMultiRating() && isset($this->item->fields['rating']) && $this->item->fields['rating']->canView())
{
	echo $this->item->fields['rating']->getDisplayPrefixText() . ' ' . $this->item->fields['rating']->getOutput(array("view" => "details", "template" => $this->template, "type" => "multi_rating")) . ' ' . $this->item->fields['rating']->getDisplaySuffixText();
}
?>