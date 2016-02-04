<?php 
/*------------------------------------------------------------------------
# Module By http://www.themelan.com
# ------------------------------------------------------------------------
# Author    ThemeLan by http://www.themelan.com
# Copyright (C) 2013 - 2014 http://www.themelan.com All Rights Reserved.
# @license - GNU/GPL V2 for PHP files. CSS / JS are Copyrighted Commercial
# Websites: http://www.themelan.com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/modules/mod_lan_vertical_tab/frontend/css/styles.css');
$document->addStyleSheet(JURI::root(true).'/modules/mod_lan_vertical_tab/frontend/css/responsive.css');
require JModuleHelper::getLayoutPath('mod_lan_vertical_tab', $params->get('layout', 'default'));
?>
