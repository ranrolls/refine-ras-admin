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


class JUDirectoryViewEmbedListing extends JUDIRViewAdmin
{
	protected $items;
	protected $pagination;
	protected $state;

	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->setDocument();

		parent::display($tpl);
	}

	protected function setDocument()
	{
		JText::script('COM_JUDIRECTORY_PLEASE_SELECT_LISTING');
		$document = JFactory::getDocument();
		$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/js/view.embedlisting.js");
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
}
