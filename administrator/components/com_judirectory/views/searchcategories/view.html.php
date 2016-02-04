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


class JUDirectoryViewSearchCategories extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		$this->searchword = trim($this->state->get('filter.searchword'));

		
		$this->addToolBar();

		
		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function addToolBar()
	{
		$canDo = JUDirectoryHelper::getActions('com_judirectory');

		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_SEARCH_CATEGORIES'), 'search-categories');

		if ($canDo->get('judir.category.create'))
		{
			JToolBarHelper::custom($task = 'categories.copycats', $icon = 'copy', $iconOver = 'copy', $alt = JText::_('COM_JUDIRECTORY_COPY_CATS_BTN'), $listSelect = true, $x = false);
		}

		if (($canDo->get('judir.category.edit') || $canDo->get('judir.category.edit.own')) && $canDo->get('judir.category.create'))
		{
			JToolBarHelper::custom($task = 'categories.movecats', $icon = 'move', $iconOver = 'move', $alt = JText::_('COM_JUDIRECTORY_MOVE_CATS_BTN'), $listSelect = true, $x = false);
		}

		if ($canDo->get('judir.category.delete') || $canDo->get('judir.category.delete.own'))
		{
			JToolBarHelper::custom($task = 'categories.delete', $icon = 'delete', $iconOver = 'delete', $alt = JText::_('COM_JUDIRECTORY_DELETE_CATS_BTN'), $listSelect = true, $x = false);
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_SEARCH_CATEGORIES'));
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
