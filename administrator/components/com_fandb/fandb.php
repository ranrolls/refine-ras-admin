<?php
/*------------------------------------------------------------------------
# fandb.php - fandb Component
# ------------------------------------------------------------------------
# author    Refine
# copyright Copyright (C) 2015. All Rights Reserved
# license   hello
# website   www.refine-interactive.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_fandb')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
};

// require helper files
JLoader::register('FandbHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'fandb.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Add CSS file for all pages
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_fandb/assets/css/fandb.css');
$document->addScript('components/com_fandb/assets/js/fandb.js');

// Get an instance of the controller prefixed by Fandb
$controller = JController::getInstance('Fandb');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>