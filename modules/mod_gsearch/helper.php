<?php
defined('_JEXEC') or die('Restricted access');

class modGSearchHelper {
        function getParams(&$params) {
		$params->def('logo', 'images/joomla_logo_black.jpg');
		$params->def('button', 'modules/mod_gsearch/tmpl/gsearch.gif');
		$params->def('client_id', 'pub-2914600261958472');
        	return $params;
	}
}