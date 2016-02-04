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

class JUDirectoryViewMaintenance extends JUDIRView
{
	public function display($tpl = null)
	{
		$this->params = JUDirectoryHelper::getParams();

		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		if (!$this->params->get('activate_maintenance', 0))
		{
			$app = JFactory::getApplication();
			$app->redirect(JUri::root());
		}

		$this->_prepareDocument();

		$this->_setBreadcrumb();

		
		parent::display($tpl);
	}

	protected function _prepareDocument()
	{
		$uri = clone JUri::getInstance();
		
		$domain        = $uri->toString(array('scheme', 'host', 'port'));
		$canonicalLink = $domain . JRoute::_(JUDirectoryHelperRoute::getMaintenanceRoute(true, $this->_layout), false);
		JUDirectoryFrontHelper::setCanonical($canonicalLink);

		$seoData = array(
			"metatitle"       => JText::_('COM_JUDIRECTORY_SEO_TITLE_MAINTENANCE'),
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

		$linkMaintenance = JRoute::_(JUDirectoryHelperRoute::getMaintenanceRoute(true), false);
		$pathwayArray[]  = JUDirectoryFrontHelperBreadcrumb::createPathwayItem($this->getName(), $linkMaintenance);

		$pathway->setPathway($pathwayArray);
	}
}
