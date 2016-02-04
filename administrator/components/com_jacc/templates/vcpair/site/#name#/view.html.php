<?php 
/**
* @version		$Id: view.html.php 182 2014-02-08 18:34:48Z michel $
* @package		##Component##
* @subpackage 	Views
* @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
* @license ###license##
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class ##Component##View##Name##  extends JViewLegacy 
{
/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}
		
		//Get Params 	
		$this->params = JComponentHelper::getParams('com_##component##');
		
		parent::display($tpl);	
	}
}
?>