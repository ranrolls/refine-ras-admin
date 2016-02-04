<?php
/**
 * @version     1.0.0
 * @package     com_fandbstartup
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Refine <ravindar.k@refine-interactive.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Fb controller class.
 */
class FandbstartupControllerFb extends JControllerForm
{

    function __construct() {
        $this->view_list = 'fbs';
        parent::__construct();
    }

}