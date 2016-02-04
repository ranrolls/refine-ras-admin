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


class JUDirectoryViewCriteriaGroup extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		if (!JUDirectoryHelper::hasMultiRating())
		{
			JError::raiseError(500, JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));

			return false;
		}

		
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

		$isNew      = ($this->item->id == 0);
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_PAGE_' . ($checkedOut ? 'VIEW_CRITERIAGROUP' : ($isNew ? 'ADD_CRITERIAGROUP' : 'EDIT_CRITERIAGROUP'))), 'criteriagroup-add');

		if ($isNew && $user->authorise('core.create', 'com_judirectory'))
		{
			JToolBarHelper::apply('criteriagroup.apply');
			JToolBarHelper::save('criteriagroup.save');
			JToolBarHelper::save2new('criteriagroup.save2new');
			JToolBarHelper::cancel('criteriagroup.cancel');
		}
		else
		{
			if (!$checkedOut)
			{
				
				if ($this->canDo->get('core.edit') || ($this->canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolBarHelper::apply('criteriagroup.apply');
					JToolBarHelper::save('criteriagroup.save');
					
					if ($this->canDo->get('core.create'))
					{
						JToolBarHelper::save2new('criteriagroup.save2new');
					}
				}
			}
			JToolBarHelper::cancel('criteriagroup.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$isNew      = ($this->item->id == 0);
		$userId     = JFactory::getUser()->id;
		$document   = JFactory::getDocument();
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$document->setTitle(JText::_('COM_JUDIRECTORY_PAGE_' . ($checkedOut ? 'VIEW_CRITERIAGROUP' : ($isNew ? 'ADD_CRITERIAGROUP' : 'EDIT_CRITERIAGROUP'))));
		$document->addScript(JUri::root() . $this->script);
	}
}