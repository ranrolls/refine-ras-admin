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

class JUDirectoryViewTopComments extends JUDIRView
{
	public function display($tpl = null)
	{
		$this->items        = $this->get('Items');
		$this->params       = JUDirectoryHelper::getParams();
		$this->state        = $this->get('State');
		$this->pagination   = $this->get('Pagination');
		$this->root_comment = JUDirectoryFrontHelperComment::getRootComment();

		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		
		$this->order_name_array = array(
			'cm.title'         => JText::_('COM_JUDIRECTORY_FIELD_TITLE'),
			'cm.created'       => JText::_('COM_JUDIRECTORY_FIELD_CREATED'),
			'r.score'          => JText::_('COM_JUDIRECTORY_FIELD_RATING_SCORE'),
			'cm.helpful_votes' => JText::_('COM_JUDIRECTORY_FIELD_HELPFUL_VOTES'),
			'cm.total_votes'   => JText::_('COM_JUDIRECTORY_FIELD_TOTAL_VOTES'));

		$this->order_dir_array = array(
			'ASC'  => JText::_('COM_JUDIRECTORY_ASC'),
			'DESC' => JText::_('COM_JUDIRECTORY_DESC'));

		$this->listOrder = $this->escape($this->state->get('list.ordering'));
		$this->listDirn  = $this->escape($this->state->get('list.direction'));

		$this->_prepareDocument();
		$this->_setBreadcrumb();

		parent::display($tpl);
	}

	protected function _prepareDocument()
	{
		$uri = clone JUri::getInstance();
		
		$domain        = $uri->toString(array('scheme', 'host', 'port'));
		$linkCanonical = $domain . JRoute::_(JUDirectoryHelperRoute::getTopCommentsRoute(true, $this->_layout), false);
		JUDirectoryFrontHelper::setCanonical($linkCanonical);

		$seoData = array(
			"metatitle"       => JText::_('COM_JUDIRECTORY_SEO_TITLE_TOP_COMMENTS'),
			"metadescription" => "",
			"metakeyword"     => ""
		);
		JUDirectoryFrontHelperSeo::seo($this, $seoData);
	}

	protected function _setBreadcrumb()
	{
		$app          = JFactory::getApplication();
		$pathway      = $app->getPathway();
		$pathwayArray = array();

		$pathwayArray[] = JUDirectoryFrontHelperBreadcrumb::getRootPathway();

		$linkTopComments = JRoute::_(JUDirectoryHelperRoute::getTopCommentsRoute());
		$pathwayArray[]  = JUDirectoryFrontHelperBreadcrumb::createPathwayItem($this->getName(), $linkTopComments);

		$pathway->setPathway($pathwayArray);
	}
}