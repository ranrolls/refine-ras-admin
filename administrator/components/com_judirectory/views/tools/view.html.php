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


class JUDirectoryViewTools extends JUDIRViewAdmin
{
	
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		$levelOptions       = array();
		$levelOptions[]     = JHtml::_('select.option', 1, 1);
		$levelOptions[]     = JHtml::_('select.option', 5, 5);
		$levelOptions[]     = JHtml::_('select.option', 10, 10);
		$levelOptions[]     = JHtml::_('select.option', 15, 15);
		$levelOptions[]     = JHtml::_('select.option', 20, 20);
		$levelOptions[]     = JHtml::_('select.option', 25, 25);
		$levelOptions[]     = JHtml::_('select.option', 30, 30);
		$this->levelOptions = $levelOptions;

		$boolean       = array();
		$boolean[]     = JHtml::_('select.option', 0, JText::_('JNO'));
		$boolean[]     = JHtml::_('select.option', 1, JText::_('JYES'));
		$this->boolean = $boolean;
		$this->layout  = $this->getLayout();
		if ($this->layout == 'rebuildrating'
			||
			$this->layout == 'resizeimages'
		)
		{
			$categoryList = $this->get('CategoryList');

			foreach ($categoryList AS $key => $value)
			{
				$categoryList[$key]->id    = $value->id;
				$categoryList[$key]->title = '|— ' . $value->title;
			}

			
			$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
			array_unshift($categoryList, array('id' => $rootCat->id, 'title' => 'Root'));
			$this->categoryList = $categoryList;
		}

		if ($this->layout == 'rebuildrating')
		{
			$this->criteriaGroups = $this->get("CriteriaGroups");
		}

		
		$errors = $app->getUserState("import_file_errors");
		if (isset($errors))
		{
			$this->errors = $errors;
			$app->setUserState("import_file_errors", null);
		}

		
		$this->addToolBar();

		if ($this->getLayout() == "rebuildrating")
		{
			$app->setUserState("cats", null);
			$app->setUserState('criteria_groups', null);
			$app->setUserState('total_listings', null);
		}

		
		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function addToolBar()
	{
		$app = JFactory::getApplication();
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_TOOLS'), 'tools');
		if ($app->input->get('layout') == 'resizeimages')
		{
			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_RESIZE_IMAGES'), 'resize-images');
			JToolBarHelper::apply('tools.resizeImages', 'JTOOLBAR_APPLY');
		}

		if ($app->input->get('layout') == 'rebuildcommenttree')
		{

			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_REBUILD_COMMENT_TREE'), 'rebuild-comment-tree');
			JToolBarHelper::apply('tools.rebuildCommentTree', 'JTOOLBAR_APPLY');
		}

		if ($app->input->get('layout') == 'rebuildrating')
		{

			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_REBUILD_RATING'), 'rebuild-rating');
			JToolBarHelper::apply('tools.rebuildRating', 'JTOOLBAR_APPLY');
		}

		if ($app->input->get('layout') == 'information')
		{
			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_INFORMATION'), 'information');
		}

		if ($app->input->get("layout") == "batchimportimages")
		{
			JToolBarHelper::title(JText::_('COM_JUDIRECTORY_IMPORT_IMAGES'), 'import-images');

			if (isset($this->task))
			{
				JToolBarHelper::apply('tools.importImageProcess', 'COM_JUDIRECTORY_IMPORT');
			}
			else
			{
				JToolBarHelper::custom('tools.fieldsMapping', 'next', 'next', 'Next', false);
			}
		}

		if ($app->input->get('layout'))
		{
			JToolBarHelper::cancel('tools.cancel', 'JTOOLBAR_CANCEL');
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));

	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_TOOLS'));

		JText::script('COM_JUDIRECTORY_FINISHED');
		if ($this->getLayout() == 'rebuildcommenttree')
		{
			$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/rebuild-comment-tree.js");
		}
		elseif ($this->getLayout() == 'resizeimages')
		{
			$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/resize-images.js");
		}
		elseif ($this->getLayout() == 'import_images_process')
		{
			$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/import-image-ajax.js");
		}
		elseif ($this->getLayout() == 'rebuildrating')
		{
			$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/rebuild-rating-ajax.js");
		}
	}
}
