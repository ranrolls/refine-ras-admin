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


class JUDirectoryViewFields extends JUDIRViewAdmin
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
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("field.edit");
		$this->groupCanDoDelete = JUDirectoryHelper::checkGroupPermission("fields.delete");

		
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
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_FIELDS'), 'fields');

		if ($this->groupCanDoManage)
		{
			if ($this->canDo->get('core.create'))
			{
				JToolBarHelper::addNew('field.add');
			}

			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				JToolBarHelper::editList('field.edit');
			}

			if ($this->canDo->get('core.edit.state'))
			{
				JToolbarHelper::publish('fields.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('fields.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

		}

		if ($this->groupCanDoDelete)
		{
			if ($this->canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_ITEMS', 'fields.delete');
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
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_FIELDS'));
		$document->addStyleSheet(JUri::root() . 'administrator/components/com_judirectory/assets/css/tablesticky.css');
		$document->addScript(JUri::root() . 'administrator/components/com_judirectory/assets/js/jquery.ba-throttle-debounce.min.js');
		$document->addScript(JUri::root() . 'administrator/components/com_judirectory/assets/js/jquery.stickyheader.js');
	}

	
	protected function getSortFields()
	{
		return array(
			'field.id'        => JText::_('COM_JUDIRECTORY_FIELD_ID'),
			'field.caption'   => JText::_('COM_JUDIRECTORY_FIELD_CAPTION'),
			'field.group_id'  => JText::_('COM_JUDIRECTORY_FIELD_GROUP'),
			'field.plugin_id' => JText::_('COM_JUDIRECTORY_FIELD_TYPE'),
			'field.published' => JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'),
			'field.ordering'  => JText::_('COM_JUDIRECTORY_FIELD_ORDERING')
		);
	}
}
