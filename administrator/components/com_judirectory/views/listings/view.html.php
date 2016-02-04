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


class JUDirectoryViewListings extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		$app = JFactory::getApplication();
		
		if ($app->input->get('layout') == null)
		{
			$app->redirect('index.php?option=com_judirectory&view=listcats');
		}

		
		$this->items      = $this->get('Items');
		$this->state      = $this->get('State');
		$this->authors    = $this->get('Authors');
		$this->pagination = $this->get('Pagination');
		
		if ($app->input->get('layout') == 'copy')
		{
			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_COPY_LISTINGS'), 'copy-listings');
			JToolBarHelper::apply('listings.copyListings', 'JTOOLBAR_APPLY');
			JToolBarHelper::cancel('listing.cancel', 'JTOOLBAR_CANCEL');
		}
		elseif ($app->input->get('layout') == 'move')
		{
			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MOVE_LISTINGS'), 'move-listings');
			JToolBarHelper::apply('listings.moveListings', 'JTOOLBAR_APPLY');
			JToolBarHelper::cancel('listing.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_LISTINGS'), 'listings');

			$this->totalListings = $this->get('Total');
			$this->setDocument();
		}

		if (JUDirectoryHelper::isJoomla3x() && $app->input->get('layout') == 'modal')
		{
			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');
		}
		
		parent::display($tpl);
	}

	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_LISTINGS'));
	}
}
