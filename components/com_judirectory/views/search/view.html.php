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

JHtml::addIncludePath(JPATH_SITE . '/components/com_judirectory/helpers');

class JUDirectoryViewSearch extends JUDIRView
{
	public function display($tpl = null)
	{
		
		$this->state  = $this->get('State');
		$this->token  = JSession::getFormToken();
		$this->params = $this->state->params;

		
		$app = JFactory::getApplication();

		$submit_simple_search = $app->input->getString('submit_simple_search', '');
		if (isset($submit_simple_search) && $submit_simple_search == "search")
		{
			$model = $this->getModel();
			$model->resetState();
		}
		$this->model = $this->getModel();
		$user        = JFactory::getUser();
		$uri         = JUri::getInstance();
		$this->items = $this->get('Items');
		foreach ($this->items as $item)
		{
			$item->report_link = JRoute::_(JUDirectoryHelperRoute::getReportListingRoute($item->id));

			$item->claim_link = JRoute::_(JUDirectoryHelperRoute::getClaimListingRoute($item->id));

			
			if ($item->checked_out > 0 && $item->checked_out != $user->get('id'))
			{
				if (JUDirectoryFrontHelperPermission::canCheckInListing($item->id))
				{
					$item->checkin_link = JRoute::_('index.php?option=com_judirectory&task=forms.checkin&id=' . $item->id . '&' . JSession::getFormToken() . '=1' . '&return=' . base64_encode(urlencode($uri)));
				}
			}
			else
			{
				$item->edit_link = JRoute::_('index.php?option=com_judirectory&task=form.edit&id=' . $item->id . '&Itemid=' . JUDirectoryHelperRoute::findItemIdOfListing($item->id));

				if ($item->published == 1)
				{
					$item->editstate_link = JRoute::_('index.php?option=com_judirectory&task=forms.unpublish&id=' . $item->id . '&return=' . base64_encode(urlencode($uri)) . '&' . JSession::getFormToken() . '=1&Itemid=' . JUDirectoryHelperRoute::findItemIdOfListing($item->id));
				}
				else
				{
					$item->editstate_link = JRoute::_('index.php?option=com_judirectory&task=forms.publish&id=' . $item->id . '&return=' . base64_encode(urlencode($uri)) . '&' . JSession::getFormToken() . '=1&Itemid=' . JUDirectoryHelperRoute::findItemIdOfListing($item->id));
				}
			}

			$item->delete_link = JRoute::_('index.php?option=com_judirectory&task=forms.delete&id=' . $item->id . '&return=' . base64_encode(urlencode($uri)) . '&' . JSession::getFormToken() . '=1&Itemid=' . JUDirectoryHelperRoute::findItemIdOfListing($item->id));

			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$item->event = new stdClass();
			$context     = 'com_judirectory.listing_list';

			$results                        = $dispatcher->trigger('onContentAfterTitle', array($context, &$item, &$item->params, 0));
			$item->event->afterDisplayTitle = trim(implode("\n", $results));

			$results                           = $dispatcher->trigger('onContentBeforeDisplay', array($context, &$item, &$item->params, 0));
			$item->event->beforeDisplayContent = trim(implode("\n", $results));

			$results                          = $dispatcher->trigger('onContentAfterDisplay', array($context, &$item, &$item->params, 0));
			$item->event->afterDisplayContent = trim(implode("\n", $results));
		}
		$this->pagination = $this->get('Pagination');
		$this->searchword = trim($app->input->getString('searchword', ''));

		
		$this->searchword = JUDirectoryFrontHelper::UrlDecode($this->searchword);

		$this->cat_id  = $app->input->getInt('cat_id', 0);
		$this->sub_cat = $app->input->getInt('sub_cat', 0);

		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$nestedCategories = JUDirectoryFrontHelperCategory::getCategoriesRecursive(1, false, false, true);
		$options          = array();

		$parent_cat_level = 0;
		foreach ($nestedCategories AS $key => $categoryObj)
		{
			if ($key == 0)
			{
				$parent_cat_level = $categoryObj->level;
			}
			$options[] = JHtml::_('select.option', $categoryObj->id, str_repeat('-.', ($categoryObj->level - $parent_cat_level)) . $categoryObj->title);
		}
		$this->cat_select_list = JHtml::_('select.genericList', $options, 'cat_id', '', 'value', 'text', $this->cat_id);

		
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		$this->_prepareData();
		$this->_prepareDocument();
		$this->_setBreadcrumb();

		parent::display($tpl);
	}

	protected function _prepareData()
	{
		
		$this->order_name_array = JUDirectoryFrontHelperField::getFrontEndOrdering();
		$this->order_dir_array  = JUDirectoryFrontHelperField::getFrontEndDirection();
		$this->listing_order    = $this->escape($this->state->get('list.ordering'));
		$this->listing_dir      = $this->escape($this->state->get('list.direction'));

		$this->listing_columns = (int) $this->params->get('view_search_columns', 2);
		if (!is_numeric($this->listing_columns) || ($this->listing_columns <= 0))
		{
			$this->listing_columns = 1;
		}
		$this->listing_bootstrap_columns = JUDirectoryFrontHelper::getBootstrapColumns($this->listing_columns);
		$this->listing_row_class         = htmlspecialchars($this->params->get('view_search_add_rows_class'));
		$this->listing_column_class      = htmlspecialchars($this->params->get('view_search_add_columns_class'));

		
		$this->allow_user_select_view_mode = $this->params->get('allow_user_select_view_mode', 1);

		if ($this->allow_user_select_view_mode && isset($_COOKIE['judir-view-mode']) && !empty($_COOKIE['judir-view-mode']))
		{
			$viewMode = $_COOKIE['judir-view-mode'] == 'judir-view-grid' ? 2 : 1;
		}
		else
		{
			$viewMode = $this->params->get('default_view_mode', 2);
		}

		$this->view_mode = $viewMode;
	}

	protected function _prepareDocument()
	{
		$app           = JFactory::getApplication();
		$parent_cat_id = $app->input->getInt('parent_cat_id', 0);
		$uri           = clone JUri::getInstance();
		
		$domain        = $uri->toString(array('scheme', 'host', 'port'));
		$linkCanonical = $domain . JRoute::_(JUDirectoryHelperRoute::getSearchRoute($this->cat_id, $parent_cat_id, $this->searchword, true, $this->_layout), false);
		JUDirectoryFrontHelper::setCanonical($linkCanonical);

		$seoData = array(
			"metatitle"       => JText::_('COM_JUDIRECTORY_SEO_TITLE_SEARCH'),
			"metadescription" => "",
			"metakeyword"     => ""
		);
		JUDirectoryFrontHelperSeo::seo($this, $seoData);
	}

	protected function _setBreadcrumb()
	{
		$app           = JFactory::getApplication();
		$jInput        = $app->input;
		$parent_cat_id = $jInput->getInt('parent_cat_id', 0);

		$pathway        = $app->getPathway();
		$pathwayArray   = array();
		$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::getRootPathway();

		$linkSearch     = JRoute::_(JUDirectoryHelperRoute::getSearchRoute());
		$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::createPathwayItem($this->getName(), $linkSearch);

		if ($this->searchword)
		{
			$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::createPathwayItem($this->searchword);
		}

		$pathway->setPathway($pathwayArray);
	}
}
