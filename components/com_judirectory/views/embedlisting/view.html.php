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


class JUDirectoryViewEmbedListing extends JUDIRView
{
	
	public function display($tpl = null)
	{
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->_prepareDocument();

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');
		}

		parent::display($tpl);
	}

	public function getFieldDisplay()
	{
		
		$fields = array(
			'title'      => JText::_('Title'),
			'introtext'  => JText::_('Introtext'),
			'categories' => JText::_('Categories'),
			'created'    => JText::_('Created'),
			'created_by' => JText::_('Created by'),
			'image'      => JText::_('Image'),
			'hits'       => JText::_('Hits'),
			'rating'     => JText::_('Rating'),
			'tag'        => JText::_('Tag'),
			'task'       => JText::_('Task')
		);

		return $fields;
	}

	protected function _prepareDocument()
	{
		$seoData = array(
			"metatitle"       => JText::_('COM_JUDIRECTORY_SEO_TITLE_EMBED_LISTING'),
			"metadescription" => "",
			"metakeyword"     => ""
		);
		JUDirectoryFrontHelperSeo::seo($this, $seoData);
	}
}
