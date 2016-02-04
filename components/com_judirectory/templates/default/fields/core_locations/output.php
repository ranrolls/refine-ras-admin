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

$html = '<div id="julocation-' . $this->listing_id . '" class="julocation">';
$html .= '<div class="map-canvas" style="width: 100%; height: 300px; margin-top: 10px; border: 1px solid #CCCCCC"></div>';

if($this->params->get('show_get_direction', 1))
{
	//Get direction form
	$html .= '<div class="form-horizontal" style="margin-top: 10px">';
	$html .= '<div class="form-group">';
	$html .= '<label for="input-start" class="control-label col-xs-12 col-sm-2">' . JText::_('COM_JUDIRECTORY_STARTING_POINT') . '</label>';
	$html .= '<div class="col-xs-12 col-sm-6">';
	$html .= '<div class="input-group">';
	$html .= '<input type="text" size="64" class="input-start form-control" id="input-start" value="" placeholder="' . JText::_('COM_JUDIRECTORY_TYPE_TO_SEARCH_LOCATION') . '" />';
	$html .= '<span class="my_location hasTooltip btn btn-default input-group-addon" title="' . JText::_('COM_JUDIRECTORY_MY_LOCATION') . '"><i class="fa fa-crosshairs"></i></span>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<div class="form-group">';
	$html .= '<label for="input-destination" class="control-label col-xs-12 col-sm-2">' . JText::_('COM_JUDIRECTORY_DESTINATION') . '</label>';
	$html .= '<div class="col-xs-12 col-sm-10">';
	$html .= '<select class="input-destination" id="input-destination">';
	foreach ($this->value as $location)
	{
		$html .= '<option value="' . $location->lat . ',' . $location->lng . '">' . $location->address . '</option>';
	}
	$html .= '</select>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<div class="form-group">';
	$html .= '<label for="input-travelMode" class="control-label col-xs-12 col-sm-2">' . JText::_('COM_JUDIRECTORY_TRAVEL_MODE') . '</label>';
	$html .= '<div class="col-xs-12 col-sm-10">';
	$html .= '<select class="input-travelMode" id="input-travelMode">';
	$html .= '<option value="DRIVING" selected="selected">' . JText::_('COM_JUDIRECTORY_DRIVING') . '</option>';
	$html .= '<option value="BICYCLING">' . JText::_('COM_JUDIRECTORY_BICYCLING') . '</option>';
	$html .= '<option value="TRANSIT">' . JText::_('COM_JUDIRECTORY_TRANSIT') . '</option>';
	$html .= '<option value="WALKING">' . JText::_('COM_JUDIRECTORY_WALKING') . '</option>';
	$html .= '</select>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<div class="form-group">';
	$html .= '<label for="travelMode" class="control-label col-xs-2"></label>';
	$html .= '<div class="col-xs-10">';
	$html .= '<button class="get-directions btn btn-default btn-primary" id="get-directions"><i class="fa fa-location-arrow"></i> ' . JText::_('COM_JUDIRECTORY_GET_DIRECTION') . '</button>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
	//END - Get direction form
	$html .= '<div id="map-direction-panel"></div>';
}

$html .= '</div>';

echo $html;
?>