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


class JUDirectoryViewFieldGroup extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}
		
		$this->form   = $this->get('Form');
		$this->item   = $this->get('Item');
		$this->script = $this->get('Script');
		$this->canDo  = JUDirectoryHelper::getActions('com_judirectory');

		
		$this->addToolBar();

		
		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function addToolBar()
	{
		$app = JFactory::getApplication();
		$app->input->set('hidemainmenu', true);
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_PAGE_' . ($checkedOut ? 'VIEW_FIELD_GROUP' : ($isNew ? 'ADD_FIELD_GROUP' : 'EDIT_FIELD_GROUP'))), 'fieldgroup-add');
		if ($isNew && $user->authorise('core.create', 'com_judirectory'))
		{
			JToolBarHelper::apply('fieldgroup.apply');
			JToolBarHelper::save('fieldgroup.save');
			JToolBarHelper::save2new('fieldgroup.save2new');
			JToolBarHelper::cancel('fieldgroup.cancel');
		}
		else
		{
			if (!$checkedOut)
			{
				
				if ($this->canDo->get('core.edit') || ($this->canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolBarHelper::apply('fieldgroup.apply');
					JToolBarHelper::save('fieldgroup.save');
					
					if ($this->canDo->get('core.create'))
					{
						JToolBarHelper::save2new('fieldgroup.save2new');
					}
				}
			}
			JToolBarHelper::cancel('fieldgroup.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$isNew      = $this->item->id == 0;
		$document   = JFactory::getDocument();
		$userId     = JFactory::getUser()->id;
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$document->setTitle(JText::_('COM_JUDIRECTORY_PAGE_' . ($checkedOut ? 'VIEW_FIELD_GROUP' : ($isNew ? 'ADD_FIELD_GROUP' : 'EDIT_FIELD_GROUP'))));
		$document->addScript(JUri::root() . $this->script);
	}
}
