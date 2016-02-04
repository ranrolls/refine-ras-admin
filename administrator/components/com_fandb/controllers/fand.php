<?php
/*------------------------------------------------------------------------
# fand.php - fandb Component
# ------------------------------------------------------------------------
# author    Refine
# copyright Copyright (C) 2015. All Rights Reserved
# license   hello
# website   www.refine-interactive.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Fandb Controller Fand
 */
class FandbControllerfand extends JControllerForm
{
	public function __construct($config = array())
	{
		$this->view_list = 'fandb'; // safeguard for setting the return view listing to the main view.
		parent::__construct($config);
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 * 
	 * @param   JModel  &$model     The data model object.
	 * @param   array   $validData  The validated data.
	 * 
	 * @return  void
	 * 
	 * @since   11.1
	 */
	protected function postSaveHook(JModel &$model, $validData = array())
	{
		// Get a handle to the Joomla! application object
		$application = JFactory::getApplication();

		$model->save($data);

	}

}
?>