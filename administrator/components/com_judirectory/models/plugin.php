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


jimport('joomla.application.component.modeladmin');

class JUDirectoryModelPlugin extends JModelAdmin
{
	
	public function getTable($type = 'Plugin', $prefix = 'JUDirectoryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		
		$form = $this->loadForm('com_judirectory.plugin', 'plugin', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	
	public function getScript()
	{
		return 'administrator/components/com_judirectory/models/forms/plugin.js';
	}

	
	protected function loadFormData()
	{
		
		$data = JFactory::getApplication()->getUserState('com_judirectory.edit.plugin.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->preprocessData('com_judirectory.plugin', $data);
		}

		return $data;
	}

	public function checkJUDirectoryExtensionPlugin()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__extensions')
			->where('type = "plugin"')
			->where('element = "judirectory"')
			->where('folder = "extension"');
		$db->setQuery($query);
		$extensionObj = $db->loadObject();
		if (!$extensionObj)
		{
			JError::raiseWarning('', JText::_('COM_JUDIRECTORY_JUDIRECTORY_EXTENSION_PLUGIN_IS_NOT_INSTALLED'));

			return false;
		}

		if (!$extensionObj->enabled)
		{
			JError::raiseWarning('', JText::_('COM_JUDIRECTORY_JUDIRECTORY_EXTENSION_PLUGIN_IS_NOT_ACTIVE'));

			return false;
		}

		return true;
	}
}