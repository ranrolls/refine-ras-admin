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

class JUDirectoryViewListCats extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		$app                           = JFactory::getApplication();
		$rootCat                       = JUDirectoryFrontHelperCategory::getRootCategory();
		$fastAddError                  = $app->getUserState('com_judirectory.categories.fastadderror');
		$fastAddSuccess                = $app->getUserState('com_judirectory.categories.fastaddsuccess');
		$this->cat_id                  = $app->input->getInt('cat_id', $rootCat->id);
		$this->params                  = JUDirectoryHelper::getParams($this->cat_id);
		$this->canDoCat                = JUDirectoryHelper::getActions('com_judirectory', 'category', $this->cat_id);
		$this->rootCat                 = JUDirectoryFrontHelperCategory::getRootCategory();
		$this->allowAddListing         = (($this->params->get('allow_add_listing_to_root', 0) && $this->cat_id == $this->rootCat->id) || $this->cat_id != $this->rootCat->id);
		$this->listingGroupCanDoManage = $this->groupCanDoCatManage = JUDirectoryHelper::checkGroupPermission("listing.edit");
		$this->listingGroupCanDoDelete = $this->groupCanDoCatDelete = JUDirectoryHelper::checkGroupPermission("listings.delete");
		$this->catGroupCanDoManage     = $this->groupCanDoCatManage = JUDirectoryHelper::checkGroupPermission("category.edit");
		$this->catGroupCanDoDelete     = $this->groupCanDoCatDelete = JUDirectoryHelper::checkGroupPermission("categories.delete");
		//
		if ($fastAddSuccess)
		{
			$app->enqueueMessage($fastAddSuccess);
			$app->setUserState('com_judirectory.categories.fastaddsuccess', '');
		}

		if ($fastAddError)
		{
			$app->enqueueMessage($fastAddError, 'error');
			$app->setUserState('com_judirectory.categories.fastadderror', '');
		}

		
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->model      = $this->getModel();

		
		$this->addToolBar();

		
		$this->setDocument();

		
		parent::display($tpl);
	}

	
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER'), 'manager');

		if ($this->listingGroupCanDoDelete)
		{
			if ($this->canDoCat->get('judir.listing.delete') || $this->canDoCat->get('judir.listing.delete.own'))
			{
				JToolBarHelper::custom($task = 'listings.delete', $icon = 'delete', $iconOver = 'delete', $alt = JText::_('COM_JUDIRECTORY_DELETE_LISTINGS_BTN'), $listSelect = false, $x = false);
			}
		}

		if ($this->listingGroupCanDoManage)
		{
			if ($this->canDoCat->get('judir.listing.create'))
			{
				JToolBarHelper::custom($task = 'listings.copyListings', $icon = 'copy', $iconOver = 'copy', $alt = JText::_('COM_JUDIRECTORY_COPY_LISTINGS_BTN'), $listSelect = false, $x = false);
			}

			if (($this->canDoCat->get('judir.listing.edit') || $this->canDoCat->get('judir.listing.edit.own')) && $this->canDoCat->get('judir.listing.create'))
			{
				JToolBarHelper::custom($task = 'listings.moveListings', $icon = 'move', $iconOver = 'move', $alt = JText::_('COM_JUDIRECTORY_MOVE_LISTINGS_BTN'), $listSelect = false, $x = false);
			}
		}

		JToolBarHelper::divider();

		if ($this->catGroupCanDoDelete)
		{
			if ($this->canDoCat->get('judir.category.delete') || $this->canDoCat->get('judir.category.delete.own'))
			{
				JToolBarHelper::custom($task = 'categories.delete', $icon = 'delete', $iconOver = 'delete', $alt = JText::_('COM_JUDIRECTORY_DELETE_CATS_BTN'), $listSelect = true, $x = false);
			}
		}

		if ($this->catGroupCanDoManage)
		{
			if ($this->canDoCat->get('judir.category.create'))
			{
				JToolBarHelper::custom($task = 'categories.copycats', $icon = 'copy', $iconOver = 'copy', $alt = JText::_('COM_JUDIRECTORY_COPY_CATS_BTN'), $listSelect = true, $x = false);
			}

			if (($this->canDoCat->get('judir.category.edit') || $this->canDoCat->get('judir.category.edit.own')) && $this->canDoCat->get('judir.category.create'))
			{
				JToolBarHelper::custom($task = 'categories.movecats', $icon = 'move', $iconOver = 'move', $alt = JText::_('COM_JUDIRECTORY_MOVE_CATS_BTN'), $listSelect = true, $x = false);
			}
		}

		if ($this->canDoCat->get('core.admin'))
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_judirectory');
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER'));
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/styles.css");
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/reset_css.css");
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/bootstrap-multiselect.css");
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/prettify.css");
		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/jquery-spliter.css");

		$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/bootstrap-multiselect.js");
		$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/jquery.splitter.js");
		$multiSelect =
			'jQuery(document).ready(function($) {
					$("#category-fields, #fields").multiselect({
						numberDisplayed: 3,
						nonSelectedText: "' . JText::_('COM_JUDIRECTORY_SELECT_DISPLAYED_FIELDS') . '",
						nSelectedText: "' . JText::_('COM_JUDIRECTORY_FIELDS_SELECTED') . '",
						allSelectedText: "' . JText::_('COM_JUDIRECTORY_ALL_FIELDS_SELECTED') . '",
						buttonClass: "btn btn-mini",
						buttonContainer: "<div class=\"select-fields btn-group pull-left\" />",
						maxHeight: 250,
						includeSelectAllOption: true,
						selectAllText: "' . JText::_('COM_JUDIRECTORY_SELECT_ALL') . '",
						selectAllValue: "0",
						enableFiltering: true,
						filterBehavior: "both",
						enableCaseInsensitiveFiltering: true,
						filterPlaceholder: "' . JText::_('COM_JUDIRECTORY_SEARCH') . '",
						templates: {
							filter: \'<li class="multiselect-item filter"><div class="input-group input-append"><input class="form-control multiselect-search input-mini" type="text"></div></li>\',
							filterClearBtn: \'<button class="btn btn-default multiselect-clear-filter" type="button"><i class="icon-remove"></i></button>\'
						}
					});

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
