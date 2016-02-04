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


class JUDirectoryViewPlugins extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		
		$this->items            = $this->get('Items');
		$this->pagination       = $this->get('Pagination');
		$this->state            = $this->get('State');
		$this->canDo            = JUDirectoryHelper::getActions('com_judirectory');
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("plugin.edit");
		$this->groupCanDoDelete = JUDirectoryHelper::checkGroupPermission("plugins.delete");

		
		$this->addToolBar();

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');
		}

		
		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_PLUGINS'), 'plugins');

		if ($this->groupCanDoManage)
		{
			if ($this->canDo->get('core.create'))
			{
				JToolBarHelper::addNew('plugin.add', 'COM_JUDIRECTORY_INSTALL_PLUGIN');
			}

			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				JToolBarHelper::editList('plugin.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($this->groupCanDoDelete)
		{
			if ($this->canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_UNINSTALL_THESE_PLUGINS', 'plugins.remove', 'JTOOLBAR_UNINSTALL');
			}
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_PLUGINS'));
	}

	
	protected function getSortFields()
	{
		return array(
			'plg.id'      => JText::_('COM_JUDIRECTORY_FIELD_ID'),
			'plg.title'   => JText::_('COM_JUDIRECTORY_FIELD_TITLE'),
			'plg.type'    => JText::_('COM_JUDIRECTORY_FIELD_TYPE'),
			'plg.author'  => JText::_('COM_JUDIRECTORY_FIELD_AUTHOR'),
			'plg.email'   => JText::_('COM_JUDIRECTORY_FIELD_EMAIL'),
			'plg.website' => JText::_('COM_JUDIRECTORY_FIELD_WEBSITE'),
			'plg.date'    => JText::_('COM_JUDIRECTORY_FIELD_DATE'),
			'plg.version' => JText::_('COM_JUDIRECTORY_FIELD_VERSION'),
			'plg.folder'  => JText::_('COM_JUDIRECTORY_FIELD_FOLDER'),
			'plg.core'    => JText::_('COM_JUDIRECTORY_FIELD_CORE'),
			'plg.default' => JText::_('COM_JUDIRECTORY_FIELD_DEFAULT')
		);
	}
}
