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


class JUDirectoryViewSearchListings extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		$simple_search = JFactory::getApplication()->input->get('submit_simple_search');
		if (isset($simple_search))
		{
			$model = $this->getModel();
			$model->resetState();
		}

		
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->searchword = trim($this->state->get('filter.searchword'));

		
		$this->addToolBar();

		
		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function addToolBar()
	{
		$canDo = JUDirectoryHelper::getActions('com_judirectory');

		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_SEARCH_LISTINGS'), 'search-listings');

		if ($canDo->get('judir.listing.create'))
		{
			JToolBarHelper::custom($task = 'listings.copyListings', $icon = 'copy', $iconOver = 'copy', $alt = JText::_('COM_JUDIRECTORY_COPY_LISTINGS_BTN'), $listSelect = false, $x = false);
		}

		if (($canDo->get('judir.listing.edit') || $canDo->get('judir.listing.edit.own')) && $canDo->get('judir.listing.create'))
		{
			JToolBarHelper::custom($task = 'listings.moveListings', $icon = 'move', $iconOver = 'move', $alt = JText::_('COM_JUDIRECTORY_MOVE_LISTINGS_BTN'), $listSelect = false, $x = false);
		}

		if ($canDo->get('judir.listing.delete') || $canDo->get('judir.listing.delete.own'))
		{
			JToolBarHelper::custom($task = 'listings.delete', $icon = 'delete', $iconOver = 'delete', $alt = JText::_('COM_JUDIRECTORY_DELETE_LISTINGS_BTN'), $listSelect = false, $x = false);
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_SEARCH_LISTINGS'));
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/reset_css.css");
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/jquery-spliter.css");

		$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/bootstrap-multiselect.js");
		$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/jquery.splitter.js");
		$multiSelect =
			'jQuery(document).ready(function($) {
					$("#search-in").multiselect({
						buttonClass: "btn btn-mini",
						buttonContainer: "<div class=\"select-fields btn-group pull-left\" />",
						maxHeight: 250,
						enableFiltering: false
					});
				});
			';
		$splitter    = '
			jQuery(document).ready(function($) {
				$("#splitterContainer").splitter({name: "judirectory", minAsize:150, maxAsize:500, splitVertical:true, A:$("#leftPane"), B:$("#rightPane"), slave:$("#rightSplitterContainer"), closeableto:0});
			});
		';
		$document->addScriptDeclaration($multiSelect);
		$document->addScriptDeclaration($splitter);
	}
}
