<?php
/**
* @version		$Id:default.php 1 2015-06-04 06:35:13Z  $
* @package		Fb
* @subpackage 	Controllers
* @copyright	Copyright (C) 2015, . All rights reserved.
* @license 		
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');
jimport('joomla.application.component.controllerform');

/**
 * FbFb Controller
 *
 * @package    Fb
 * @subpackage Controllers
 */
class FbControllerFb extends JControllerForm
{
	public function __construct($config = array())
	{
	
		$this->view_item = 'fb';
		$this->view_list = 'fbs';
		parent::__construct($config);
	}	
	
	/**
	 * Proxy for getModel.
	 *
	 * @param   string	$name	The name of the model.
	 * @param   string	$prefix	The prefix for the PHP class name.
	 *
	 * @return  JModel
	 * @since   1.6
	 */
	public function getModel($name = 'Fb', $prefix = 'FbModel', $config = array('ignore_request' => false))
	{
		$model = parent::getModel($name, $prefix, $config);
	
		return $model;
	}	
}// class
?>