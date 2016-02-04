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


class JUDirectoryViewStyles extends JUDIRViewAdmin
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
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("style.edit");
		$this->groupCanDoDelete = JUDirectoryHelper::checkGroupPermission("styles.delete");

		
		$this->addToolBar();

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');
		}
		
		$this->setDocument();

		
		parent::display($tpl);
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_TEMPLATE_STYLES'));
	}

	
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_TEMPLATE_STYLES'), 'style');
		if ($this->groupCanDoManage)
		{
			if ($this->canDo->get('core.create'))
			{
				JToolBarHelper::addNew('style.add');
			}
			if ($this->canDo->get('core.edit'))
			{
				JToolBarHelper::editList('style.edit');
			}
			if ($this->canDo->get('core.create'))
			{
				JToolbarHelper::custom('styles.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				JToolbarHelper::divider();
			}
		}

		if ($this->groupCanDoDelete)
		{
			if ($this->canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_STYLES', 'styles.delete');
			}
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	protected function getSortFields()
	{
		return array(
			'style.id'      => JText::_('COM_JUDIRECTORY_FIELD_ID'),
			'style.title'   => JText::_('COM_JUDIRECTORY_FIELD_TITLE'),
			'plg.id'        => JText::_('COM_JUDIRECTORY_FIELD_PLUGIN_ID'),
			'plg.title'     => JText::_('COM_JUDIRECTORY_FIELD_PLUGIN_TITLE'),
			'plg.author'    => JText::_('COM_JUDIRECTORY_FIELD_AUTHOR'),
			'plg.version'   => JText::_('COM_JUDIRECTORY_FIELD_VERSION'),
			'style.created' => JText::_('COM_JUDIRECTORY_FIELD_CREATED'),
			'style.home'    => JText::_('COM_JUDIRECTORY_FIELD_DEFAULT')
		);
	}
}
