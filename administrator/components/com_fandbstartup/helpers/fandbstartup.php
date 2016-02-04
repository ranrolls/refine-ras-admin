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

/**
 * Fandbstartup helper.
 */
class FandbstartupHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_FANDBSTARTUP_TITLE_FBS'),
			'index.php?option=com_fandbstartup&view=fbs',
			$vName == 'fbs'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_FANDBSTARTUP_TITLE_FANDBSTARTUPS'),
			'index.php?option=com_fandbstartup&view=fandbstartups',
			$vName == 'fandbstartups'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_fandbstartup';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
