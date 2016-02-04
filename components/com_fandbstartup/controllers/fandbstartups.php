<?php
/**
 * @version     1.0.0
 * @package     com_fandbstartup
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Refine <ravindar.k@refine-interactive.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Fandbstartups list controller class.
 */
class FandbstartupControllerFandbstartups extends FandbstartupController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Fandbstartups', $prefix = 'FandbstartupModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}