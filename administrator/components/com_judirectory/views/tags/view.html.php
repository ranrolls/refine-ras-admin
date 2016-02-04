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


class JUDirectoryViewTags extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		
		$this->items = $this->get('Items');

		$this->pagination       = $this->get('Pagination');
		$this->state            = $this->get('State');
		$this->canDo            = JUDirectoryHelper::getActions('com_judirectory');
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("tag.edit");
		$this->groupCanDoDelete = JUDirectoryHelper::checkGroupPermission("tags.delete");

		
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
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_TAGS'), 'tags');

		if ($this->groupCanDoManage)
		{
			if ($this->canDo->get('core.create'))
			{
				JToolBarHelper::addNew('tag.add');
			}
			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				JToolBarHelper::editList('tag.edit');
			}
			if ($this->canDo->get('core.edit.state'))
			{
				JToolbarHelper::publish('tags.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('tags.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
		}

		if ($this->groupCanDoDelete)
		{
			if ($this->canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_ITEMS', 'tags.delete');
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
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_TAGS'));
	}

	
	protected function getSortFields()
	{
		return array(
			'tag.id'         => JText::_('COM_JUDIRECTORY_FIELD_ID'),
			'tag.title'      => JText::_('COM_JUDIRECTORY_FIELD_TITLE'),
			'total_listings' => JText::_('COM_JUDIRECTORY_FIELD_TOTAL_LISTINGS'),
			'tag.ordering'   => JText::_('COM_JUDIRECTORY_FIELD_ORDERING'),
			'tag.published'  => JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED')
		);
	}
}
