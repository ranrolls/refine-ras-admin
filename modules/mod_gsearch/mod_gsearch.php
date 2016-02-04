<?php
defined('_JEXEC') or die('Restircted access');

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

require_once (dirname(__FILE__).DS.'helper.php');

$logo      = $params->get('logo');
$button    = $params->get('button');
$client_id = $params->get('client_id');
$main_url  = $_SERVER['HTTP_HOST'];

require(JModuleHelper::getLayoutPath('mod_gsearch'));
