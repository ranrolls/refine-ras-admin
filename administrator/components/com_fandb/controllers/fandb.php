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

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Fandb Controller
 */
class FandbControllerfandb extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'fand', $prefix = 'FandbModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}
}
?>