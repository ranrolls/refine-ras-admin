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
$document->addStyleSheet(JURI::root(true).'/modules/lan_skill_progress/frontend/css/styles.css');
$document->addStyleSheet(JURI::root(true).'/modules/lan_skill_progress/frontend/css/responsive.css');

$document->addScript(JURI::root(true).'/modules/lan_skill_progress/frontend/js/jquery.circliful.js');

require JModuleHelper::getLayoutPath('lan_skill_progress', $params->get('layout', 'default'));
?>


<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#myStathalf1').circliful();
		$('#myStathalf2').circliful();
		$('#myStathalf3').circliful();
		$('#myStat1').circliful();
		$('#myStat2').circliful();
		$('#myStat3').circliful();
		$('#myStat4').circliful();
	});
</script>