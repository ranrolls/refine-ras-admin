<?php defined('_JEXEC') or die('Restricted access'); ?>
##codestart##
/**
* @version		$Id:default.php 1 ##date##Z ##sauthor## $
* @package		##Component##
* @subpackage 	Controllers
* @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
* @license 		##license##
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');
jimport('joomla.application.component.controllerform');

/**
 * ##Component####Name## Controller
 *
 * @package    ##Component##
 * @subpackage Controllers
 */
class ##Component##Controller##Name## extends JControllerForm
{
	public function __construct($config = array())
	{
	
		$this->view_item = '##name##';
		$this->view_list = '##plural##';
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
	public function getModel($name = '##Name##', $prefix = '##Component##Model', $config = array('ignore_request' => false))
	{
		$model = parent::getModel($name, $prefix, $config);
	
		return $model;
	}	
}// class
##codeend##