<?php
/*
# SP News Highlighter Module by JoomShaper.com
# --------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2013 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework', true);
$uniqid					= $module->id;
$width					= $params->get('width', '730');
$height					= $params->get('height', '30');
$button_width 			= $params->get('button_width', '100');
$bgcolor				= $params->get('bgcolor', "#F9F9F9");
$textcolor				= $params->get('textcolor', '#666666');
$title_text				= $params->get('text', 'Highlighter');
$showbutton				= $params->get("show_arrows", 1);
$interval 				= $params->get("interval", '5000');
$fxduration 			= $params->get('fxduration', '1000');
$effects				= $params->get('effects','cover-inplace-fade');
$transition 			= $params->get("transition", "Sine.easeOut");
$date_format			= $params->get('date_format','DATE_FORMAT_LC2');
$showtitle				= $params->get('showtitle');
$linkable				= $params->get( 'linkable',1 );
$slider_width			= $width-$button_width;
$linkcolor				= $params->get('linkcolor', "#047aac");
$linkhover				= $params->get('linkhover', "#039ee1");
$arrows					= $params->get('arrows', "style1.png");
$titleas				= $params->get('titleas', 1);
$titlelimit				= $params->get('titlelimit', 20);
$content_source			= $params->get('content_source', 'joomla');

$css 					= "#sp-nh{$uniqid} {width:{$width}px;color:{$textcolor};background:{$bgcolor}}.sp-nh-item{background:{$bgcolor}}";
$css				   .=".sp-nh-buttons {width:{$button_width}px}a.sp-nh-link {color:{$linkcolor}}a.sp-nh-link:hover {color:{$linkhover}}";
$css				   .=".sp-nh-buttons,.sp-nh-item,.sp-nh-prev,.sp-nh-next {height:{$height}px;line-height:{$height}px}";
$css				   .=".sp-nh-prev,.sp-nh-next{background-image: url(" . JURI::base(true) . "/modules/mod_sp_news_highlighter/assets/images/{$arrows})}";

$document = JFactory::getDocument();
$document->addStyledeclaration($css);
$document->addStyleSheet(JURI::base(true) . '/modules/mod_sp_news_highlighter/assets/css/style.css', 'text/css' );
$document->addScript(JURI::base(true) . '/modules/mod_sp_news_highlighter/assets/js/sp_highlighter.js');

// Include the syndicate functions only once
$modhelper = ($content_source =="joomla") ? 'helper.php' : 'k2helper.php';
require_once (dirname(__FILE__).'/'.$modhelper);
$list = modNewsHighlighterHelper::getList($params);
require(JModuleHelper::getLayoutPath('mod_sp_news_highlighter'));