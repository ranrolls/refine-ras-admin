<?php
/*------------------------------------------------------------------------
# view.html.php - fandb Component
# ------------------------------------------------------------------------
# author    Refine
# copyright Copyright (C) 2015. All Rights Reserved
# license   hello
# website   www.refine-interactive.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
/**
 * HTML Fandb View class for the fandb Component
 */
class FandbViewfandb extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// Assign data to the view
		$this->items = $this->get('Items');
		// Check for errors.
		if (count($errors = $this->get('Errors'))){
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		};
		// Display the view
		parent::display($tpl);
	}
}
?>