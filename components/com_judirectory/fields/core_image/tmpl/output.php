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

$this->setAttribute("src", $image_src, "output");
$this->setAttribute("alt", $this->listing->title, "output");
$this->setAttribute("title", $this->listing->title, "output");

if ($isDetailsView)
{
	if ($this->params->get("details_view_set_icon_dimension", 1))
	{
		$this->setAttribute("style", 'display: block; max-width:' . $this->params->get("details_view_image_width", 100) . 'px; max-height:' . $this->params->get("details_view_icon_height", 100) . 'px;', "output");
	}

	$html = '<img ' . $this->getAttribute(null, null, "output") . ' />';
	echo $html;
}
else
{
	$html = '<a href="' . JRoute::_(JUDirectoryHelperRoute::getListingRoute($this->listing->id)) . '">';
	if ($this->params->get("list_view_set_icon_dimension", 1))
	{
		$this->setAttribute("style", 'display: block; max-width:' . $this->params->get("details_view_image_width", 100) . 'px; max-height:' . $this->params->get("details_view_icon_height", 100) . 'px;', "output");
	}

	$html .= '<img ' . $this->getAttribute(null, null, "output") . ' />';
	$html .= '</a>';

	echo $html;
}

?>