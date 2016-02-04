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

class JUDirectoryViewTag extends JUDIRView
{
	public function display($tpl = null)
	{
		$this->model  = $this->getModel();
		$this->state  = $this->get('State');
		$this->params = $this->state->params;
		$this->tag    = $this->get('Tag');

		if (!is_object($this->tag))
		{
			JError::raiseError(404, JText::_('COM_JUDIRECTORY_TAG_NOT_FOUND'));

			return false;
		}

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

			$results                        = $dispatcher->trigger('onContentAfterTitle', array($context, &$this->item, &$this->params, 0));
			$item->event->afterDisplayTitle = trim(implode("\n", $results));

			$results                           = $dispatcher->trigger('onContentBeforeDisplay', array($context, &$this->item, &$this->params, 0));
			$item->event->beforeDisplayContent = trim(implode("\n", $results));

			$results                          = $dispatcher->trigger('onContentAfterDisplay', array($context, &$this->item, &$this->params, 0));
			$item->event->afterDisplayContent = trim(implode("\n", $results));
		}
		$this->pagination = $this->get('Pagination');
		$this->token      = JSession::getFormToken();

		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		
		$this->show_feed = JUDIRPROVERSION ? $this->params->get('rss_display_icon', 1) : 0;

		$rssLink        = JRoute::_(JUDirectoryHelperRoute::getTagRoute($this->tag->id, false, true));
		$this->rss_link = JRoute::_($rssLink, false);

		
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
		$this->listing_dir      = $this->escape($this->state->get('list.direction', 'ASC'));
		
		$this->listing_columns = (int) $this->params->get('listing_columns', 2);
		if (!is_numeric($this->listing_columns) || ($this->listing_columns <= 0))
		{
			$this->listing_columns = 1;
		}
		$this->listing_bootstrap_columns = JUDirectoryFrontHelper::getBootstrapColumns($this->listing_columns);
		$this->listing_row_class         = htmlspecialchars($this->params->get('listing_row_class', ''));
		$this->listing_column_class      = htmlspecialchars($this->params->get('listing_column_class', ''));

		
		$this->load_bootstrap = $this->params->get('load_bootstrap', 2);
		if ($this->load_bootstrap)
		{
			$this->listing_bootstrap_columns = JUDirectoryFrontHelper::getBootstrapColumns($this->listing_columns);
		}

		
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
		$uri = clone JUri::getInstance();
		
		$domain        = $uri->toString(array('scheme', 'host', 'port'));
		$linkCanonical = $domain . JRoute::_(JUDirectoryHelperRoute::getTagRoute($this->tag->id, true, false, $this->_layout), false);
		JUDirectoryFrontHelper::setCanonical($linkCanonical);

		
		$this->item = $this->tag;
		JUDirectoryFrontHelperSeo::seo($this);
	}

	protected function _setBreadcrumb()
	{
		$app          = JFactory::getApplication();
		$pathway      = $app->getPathway();
		$pathwayArray = array();

		$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::getRootPathway();

		$linkTags       = JRoute::_(JUDirectoryHelperRoute::getTagsRoute());
		$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::createPathwayItem('tags', $linkTags);

		$linkTag        = JRoute::_(JUDirectoryHelperRoute::getTagRoute($this->tag->id));
		$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::createPathwayItem($this->tag->title, $linkTag);

		$pathway->setPathway($pathwayArray);
	}
}
