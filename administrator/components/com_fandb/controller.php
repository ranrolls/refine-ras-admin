<?php
/*------------------------------------------------------------------------
# controller.php - fandb Component
# ------------------------------------------------------------------------
# author    Refine
# copyright Copyright (C) 2015. All Rights Reserved
# license   hello
# website   www.refine-interactive.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of Fandb component
 */
class FandbController extends JController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'Fandb'));

		// call parent behavior
		parent::display($cachable);

		// set view
		$view = strtolower(JRequest::getVar('view'));

		// Set the submenu
		FandbHelper::addSubmenu($view);
	}
}
?>