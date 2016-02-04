<?php
/**
 * @version SVN: $Id: mod_#module#.php 147 2013-10-06 08:58:34Z michel $
 * @package    Directory_listing
 * @subpackage Base
 * @author     
 * @license    
 */

defined('_JEXEC') or die('Restricted access'); // no direct access

require_once __DIR__ . '/helper.php';
$item = modDirectory_listingHelper::getItem($params);
require(JModuleHelper::getLayoutPath('mod_directory_listing'));
require_once ('helper.php');

?>