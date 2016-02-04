<?php
/**
 * @version 1.0.0.0
 * @package    joomla
 * @subpackage Fb
 * @author	   	
 *  @copyright  	Copyright (C) 2015, . All rights reserved.
 *  @license 
 */

//--No direct access
defined('_JEXEC') or die('Resrtricted Access');

require_once(JPATH_COMPONENT.'/helpers/fb.php');
$controller = JControllerLegacy::getInstance('fb');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();