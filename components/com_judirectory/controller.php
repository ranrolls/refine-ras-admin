<?php
/**
 * ------------------------------------------------------------------------
 * JUDirectory for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 *
 * @copyright      Copyright (C) 2010-2015 JoomUltra Co., Ltd. All Rights Reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @author         JoomUltra Co., Ltd
 * @website        http://www.joomultra.com
 * @----------------------------------------------------------------------@
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');


class JUDirectoryController extends JControllerLegacy
{
	
	public function display($cachable = false, $urlparams = false)
	{
		$app      = JFactory::getApplication();
		$cachable = true;
		$id       = $app->input->getInt('id', 0);
		
		$vName = $app->input->getCmd('view', 'categories');
		$app->input->set('view', $vName);

		$user = JFactory::getUser();

		
		if ($user->get('id') ||
			($_SERVER['REQUEST_METHOD'] == 'POST' && (($vName == 'category' && $app->input->get('layout') != 'blog') || $vName == 'archive'))
		)
		{
			$cachable = false;
		}

		
		$safeurlparams = array('catid'   => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'UINT', 'limitstart' => 'UINT',
		                       'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD', 'Itemid' => 'INT');

		$params = JUDirectoryHelper::getParams();
		
		if (!$user->authorise('core.admin', 'com_judirectory') && $params->get('activate_maintenance', 0) && $app->input->getString('view', '') != 'maintenance')
		{
			$this->setRedirect(JUDirectoryHelperRoute::getMaintenanceRoute());
		}

		
		if ($vName == 'form' && $id > 0 && !$this->checkEditId('com_judirectory.edit.listing', $id))
		{
			
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		
		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
