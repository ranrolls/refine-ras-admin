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


class JUDirectoryViewListing extends JUDIRViewAdmin
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
		$this->model  = $this->getModel();
		$this->app    = JFactory::getApplication();
		$cat_id       = $this->item->cat_id ? $this->item->cat_id : $this->app->input->get('cat_id');
		$this->params = JUDirectoryHelper::getParams(null, $this->item->id);
		if ($cat_id == JUDirectoryFrontHelperCategory::getRootCategory()->id && !$this->params->get('allow_add_listing_to_root', 0))
		{
			JError::raiseError(500, JText::_('COM_JUDIRECTORY_CAN_NOT_ADD_LISTING_TO_ROOT_CATEGORY'));

			return false;
		}

		if ($tempListing = JUDirectoryHelper::getTempListing($this->item->id))
		{
			$editPendingListingLink = '<a href="index.php?option=com_judirectory&task=document.edit&approve=1&id=' . $tempListing->id . '">' . $tempListing->title . '</a>';
			JError::raiseNotice('', JText::sprintf('COM_JUDIRECTORY_THIS_LISTING_HAS_PENDING_LISTING_X_PLEASE_APPROVE_PENDING_LISTING_FIRST', $editPendingListingLink));
		}

		if ($this->item->approved < 0)
		{
			$oriListingId   = abs($this->item->approved);
			$oriListingObj  = JUDirectoryHelper::getListingById($oriListingId);
			$editOriDocLink = '<a href="index.php?option=com_judirectory&task=document.edit&id=' . $oriListingId . '">' . $oriListingObj->title . '</a>';
			JError::raiseNotice('', JText::sprintf('COM_JUDIRECTORY_ORIGINAL_LISTING_X', $editOriDocLink));
		}

		$this->script                         = $this->get('Script');
		$this->plugins                        = $this->get('Plugins');
		$this->fieldLocations                 = $this->get('FieldLocations');
		$this->fieldsetDetails                = $this->model->getCoreFields('details');
		$this->fieldsetPublishing             = $this->model->getCoreFields('publishing');
		$this->fieldsetTemplateStyleAndLayout = $this->model->getCoreFields('template_style');
		$this->fieldsetMetadata               = $this->model->getCoreFields('metadata');
		$this->fieldCatid                     = JUDirectoryFrontHelperField::getField('cat_id', $this->item);
		$this->fieldGallery                   = $this->get('GalleryField');
		$this->extraFields                    = $this->get('ExtraFields');
		$this->fieldsData                     = $this->app->getUserState("com_judirectory.edit.listing.fieldsdata", array());
		$this->relatedListings                = $this->get('RelatedListings');
		$this->canDo                          = JUDirectoryHelper::getActions('com_judirectory', 'category', $this->item->cat_id);

		
		$this->addToolBar();

		
		$this->setDocument();

		
		parent::display($tpl);
	}

	
	protected function addToolBar()
	{
		$app = JFactory::getApplication();
		$app->input->set('hidemainmenu', true);

		$isNew      = ($this->item->id == 0);
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo      = JUDirectoryHelper::getActions('com_judirectory', 'listing', $this->item->id);
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_PAGE_' . ($checkedOut ? 'VIEW_LISTING' : ($isNew ? 'ADD_LISTING' : 'EDIT_LISTING'))), 'listing-add');

		if ($isNew && $user->authorise('judir.listing.create', 'com_judirectory'))
		{
			JToolBarHelper::apply('listing.apply');
			JToolBarHelper::save('listing.save');
			JToolBarHelper::save2new('listing.save2new');
			JToolBarHelper::cancel('listing.cancel');
		}
		else
		{
			if ($app->input->get('approve') == 1)
			{
				JToolBarHelper::save('pendinglisting.save');
				JToolBarHelper::cancel('pendinglisting.cancel', 'JTOOLBAR_CLOSE');
			}
			else
			{
				if (!$checkedOut)
				{
					
					if ($canDo->get('judir.listing.edit') || ($canDo->get('judir.listing.edit.own') && $this->item->created_by == $userId))
					{
						JToolBarHelper::apply('listing.apply');
						JToolBarHelper::save('listing.save');
						
						if ($canDo->get('judir.listing.create'))
						{
							JToolBarHelper::save2copy('listing.save2copy');
							JToolBarHelper::save2new('listing.save2new');
						}
					}
				}
				JToolBarHelper::cancel('listing.cancel', 'JTOOLBAR_CLOSE');
			}
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
		$document->setTitle(JText::_('COM_JUDIRECTORY_PAGE_' . ($checkedOut ? 'VIEW_LISTING' : ($isNew ? 'ADD_LISTING' : 'EDIT_LISTING'))));

		JUDirectoryFrontHelper::loadjQueryUI();

		$document->addStyleSheet(JUri::root() . "administrator/components/com_judirectory/assets/css/approval.css");
		$document->addScript(JUri::root() . "components/com_judirectory/assets/js/handlebars.min.js");

		JUDirectoryHelper::formValidation();
		$document->addScript(JUri::root() . $this->script);

		JText::script('COM_JUDIRECTORY_INVALID_IMAGE');
		JText::script('COM_JUDIRECTORY_REMOVE');
		JText::script('COM_JUDIRECTORY_CAN_NOT_ADD_IMAGE_BECAUSE_MAX_NUMBER_OF_IMAGE_IS_N');
		JText::script('COM_JUDIRECTORY_TOGGLE_TO_PUBLISH');
		JText::script('COM_JUDIRECTORY_TOGGLE_TO_UNPUBLISH');
		JText::script('COM_JUDIRECTORY_CLICK_TO_REMOVE');
		JText::script('COM_JUDIRECTORY_YOU_MUST_UPLOAD_AT_LEAST_ONE_IMAGE');
		JText::script('COM_JUDIRECTORY_DESCRIPTION');
		JText::script('COM_JUDIRECTORY_FIELD_TITLE');
		JText::script('COM_JUDIRECTORY_FIELD_DESCRIPTION');
		JText::script('COM_JUDIRECTORY_FIELD_PUBLISHED');
		JText::script('COM_JUDIRECTORY_UPDATE');
		JText::script('COM_JUDIRECTORY_CANCEL');
	}
}