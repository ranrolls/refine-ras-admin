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
$document->addStyleSheet(JURI::root(true).'/modules/mod_lan_our_team/frontend/css/styles.css');
$document->addStyleSheet(JURI::root(true).'/modules/mod_lan_our_team/frontend/css/responsive.css');
require JModuleHelper::getLayoutPath('mod_lan_our_team', $params->get('layout', 'default'));
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.boxgrid.caption').hover(function(){
			$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:420});
		}, function() {
			$(".cover", this).stop().animate({top:'295px'},{queue:false,duration:420});
		});
	});
</script>