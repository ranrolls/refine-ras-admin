<?php 
/**
* @version		$Id: view.html.php 183 2014-02-17 16:17:50Z michel $
* @package		##Component##
* @subpackage 	Views
* @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
* @license ###license##
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jjimport('joomla.application.component.view');


class ##Component##View##Name##  extends JViewLegacy {


	/**
     *  Displays the list view
     * @param string $tpl
     */
	public function display($tpl = null)
{


    // Check for errors.
    if (count($errors = $this->get('Errors')))
    {
        JError::raiseError(500, implode("\n", $errors));
        return false;
    }

    parent::display($tpl);
}
}
?>