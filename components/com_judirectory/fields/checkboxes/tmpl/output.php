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

if ($options)
{
	$html = "<ul class='value-list'>";

	
	foreach ($options AS $option)
	{
		if (in_array($option->value, $values))
		{
			if ($option->text == strtoupper($option->text))
			{
				$text = JText::_($option->text);
			}
			else
			{
				$text = $option->text;
			}
			$text = htmlspecialchars($text, ENT_COMPAT, 'UTF-8');

			
			if ($this->params->get("tag_search", 0))
			{
				$item = "<a href =\"" . JRoute::_("index.php?option=com_judirectory&view=searchby&field_id=" . $this->id . "&value=" . JUDirectoryFrontHelper::UrlEncode($option->value)) . "\">" . $text . "</a>";
			}
			else
			{
				$item = $text;
			}

			$html .= "<li " . $this->getAttribute(null, null, "output") . ">" . $item . "</li>";
		}
	}

	$html .= "</ul>";

	echo $html;
}
?>