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


class JUDirectoryViewComments extends JUDIRViewAdmin
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
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("comment.edit");
		$this->groupCanDoDelete = JUDirectoryHelper::checkGroupPermission("comments.delete");
		$this->rootComment      = JUDirectoryFrontHelperComment::getRootComment();
		
		foreach ($this->items AS &$item)
		{
			$this->ordering[$item->parent_id][] = $item->id;
		}

		
		$this->addToolBar();

		if (JUDirectoryHelper::isJoomla3x())
		{
			$layout = JFactory::getApplication()->input->get('layout', '');
			if ($layout != 'modal')
			{
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
			}
		}
		
		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_COMMENTS'), 'comments');

		if ($this->groupCanDoManage)
		{
			if ($this->canDo->get('core.create'))
			{
				JToolBarHelper::addNew('comment.add', 'JTOOLBAR_NEW');
			}

			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				JToolBarHelper::editList('comment.edit', 'JTOOLBAR_EDIT');
			}

			if ($this->canDo->get('core.edit.state'))
			{
				JToolbarHelper::publish('comments.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('comments.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
		}

		if ($this->groupCanDoDelete)
		{
			if ($this->canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_ITEMS', 'comments.delete', 'JTOOLBAR_DELETE');
			}
		}

		if (JFactory::getApplication()->input->getInt('listing_id', 0))
		{
			JToolBarHelper::custom($task = 'comments.back', $icon = 'back', $iconOver = 'back', $alt = JText::_('JTOOLBAR_BACK'), $listSelect = false, $x = false);
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_COMMENTS'));
	}

	
	protected function getSortFields()
	{
		return array(
			'cm.id'               => JText::_('COM_JUDIRECTORY_FIELD_ID'),
			'cm.title'            => JText::_('COM_JUDIRECTORY_FIELD_TITLE'),
			'listing.title'       => JText::_('COM_JUDIRECTORY_FIELD_LISTING_TITLE'),
			'ua.name'             => JText::_('COM_JUDIRECTORY_FIELD_USERNAME'),
			'ua.email'            => JText::_('COM_JUDIRECTORY_FIELD_EMAIL'),
			'cm.total_votes'      => JText::_('COM_JUDIRECTORY_FIELD_TOTAL_VOTES'),
			'cm.helpful_votes'    => JText::_('COM_JUDIRECTORY_FIELD_HELPFUL_VOTES'),
			'cm.created'          => JText::_('COM_JUDIRECTORY_FIELD_CREATED'),
			'cm.ip_address'       => JText::_('COM_JUDIRECTORY_FIELD_IP_ADDRESS'),
			'cm.published'        => JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'),
			'total_reports'       => JText::_('COM_JUDIRECTORY_FIELD_REPORTS'),
			'total_subscriptions' => JText::_('COM_JUDIRECTORY_FIELD_SUBSCRIPTIONS'),
			'cm.lft'              => JText::_('COM_JUDIRECTORY_FIELD_ORDERING')
		);
	}
}
