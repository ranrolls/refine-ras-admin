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


class JUDirectoryViewFieldGroups extends JUDIRViewAdmin
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
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("fieldgroup.edit");
		$this->groupCanDoDelete = JUDirectoryHelper::checkGroupPermission("fieldgroups.delete");

		
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
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_FIELD_GROUPS'), 'fieldgroups');

		if ($this->groupCanDoManage)
		{
			if ($this->canDo->get('core.create'))
			{
				JToolBarHelper::addNew('fieldgroup.add');
			}

			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				JToolBarHelper::editList('fieldgroup.edit');
			}

			if ($this->canDo->get('core.edit.state'))
			{
				JToolbarHelper::publish('fieldgroups.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('fieldgroups.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
		}

		if ($this->groupCanDoDelete)
		{
			if ($this->canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('COM_JUDIRECTORY_DELETE_FIELD_GROUP_WARNING', 'fieldgroups.delete');
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
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_FIELD_GROUPS'));
	}

	
	protected function getSortFields()
	{
		return array(
			'fg.id'        => JText::_('COM_JUDIRECTORY_FIELD_ID'),
			'fg.name'      => JText::_('COM_JUDIRECTORY_FIELD_GROUP_NAME'),
			'fg.ordering'  => JText::_('COM_JUDIRECTORY_FIELD_ORDERING'),
			'fg.published' => JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'),
			'total_fields' => JText::_('COM_JUDIRECTORY_FIELD_TOTAL_FIELDS')
		);
	}
}
