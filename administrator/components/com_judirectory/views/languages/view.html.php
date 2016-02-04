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

class JUDirectoryViewLanguages extends JUDIRViewAdmin
{
	public function display($tpl = null)
	{
		JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');
		$this->canDo            = JUDirectoryHelper::getActions('com_judirectory');
		$this->groupCanDoManage = JUDirectoryHelper::checkGroupPermission("languages.edit");

		$model       = $this->getModel();
		$app         = JFactory::getApplication();
		$this->state = $this->get('State');
		$lang        = $this->state->get('language.lang', 'en-GB');
		$site        = $this->state->get('language.site', 'frontend');
		$item        = $this->state->get('language.item', '.com_judirectory.ini');

		if ($site == 'frontend')
		{
			$path = JPATH_ROOT . "/" . 'language' . "/" . 'en-GB' . "/" . 'en-GB' . $item;
		}

		if ($site == 'backend')
		{
			$path = JPATH_ADMINISTRATOR . "/" . 'language' . "/" . 'en-GB' . "/" . 'en-GB' . $item;
		}

		if (!JFile::exists($path))
		{
			$item = '.com_judirectory.ini';
		}
		
		$fileLanguages = $model->getFileLanguages($site);
		$fileArr       = array();
		foreach ($fileLanguages AS $value)
		{
			$value           = substr($value, 5);
			$fileArr[$value] = $value;
		}

		
		$languages = $model->getSiteLanguages();

		$options = array();
		foreach ($languages AS $language)
		{
			if ($language != 'overrides')
			{
				$options[$language] = $language;
			}
		}
		$this->translate = $model->getTranslate($lang, $item, $site);
		$this->language  = $options;
		$this->siteArr   = array(
			'frontend' => JText::_('COM_JUDIRECTORY_FRONTEND'),
			'backend'  => JText::_('COM_JUDIRECTORY_BACKEND')
		);
		$this->filterArr = array(
			'none'    => JText::_('COM_JUDIRECTORY_NONE_FILTER'),
			'empty'   => JText::_('COM_JUDIRECTORY_EMPTY_FILTER'),
			'warning' => JText::_('COM_JUDIRECTORY_WARNING_FILTER')
		);
		$this->fileArr   = $fileArr;

		$this->site   = $site;
		$this->lang   = $lang;
		$this->item   = $item;
		$this->search = $this->escape($this->state->get('filter.search'));
		$this->filter = $this->escape($this->state->get('language.filter'));
		
		$this->original    = $this->translate['en-GB'][$this->item];
		$this->translation = $this->translate[$this->lang][$this->item];
		
		$this->pagination = $this->get('Pagination');
		$this->limitArr   = array(
			'50'  => '50',
			'100' => '100',
			'200' => '200',
			'300' => '300',
			'500' => '500'
		);
		$model            = $this->getModel();
		$this->start      = $model->getStart();

		
		$comment = '';
		if (isset($this->translation['COMMENT']))
		{
			$comment = $this->translation['COMMENT'];
			if (!empty($comment))
			{
				$commentArr = explode("\n", $comment);
				function rep($str)
				{
					return preg_replace('#^\s*;#', '', $str);
				}

				$newArr  = array_map("rep", $commentArr);
				$comment = implode("\n", $newArr);
			}
		}
		$this->comment = $comment;
		unset($this->original['COMMENT']);

		
		$fileExisted = true;
		if ($this->site == 'frontend')
		{
			$pathFile = JPATH_ROOT . "/" . 'language' . "/" . $this->lang . "/" . $this->lang . $this->item;
		}
		elseif ($this->site == 'backend')
		{
			$pathFile = JPATH_ADMINISTRATOR . "/" . 'language' . "/" . $this->lang . "/" . $this->lang . $this->item;
		}

		if (!JFile::exists($pathFile))
		{
			$fileExisted = false;
		}
		$this->fileExisted = $fileExisted;

		
		$this->share_link = JRoute::_('index.php?option=com_judirectory&view=languages&layout=modal&tmpl=component&share=' . $this->site);
		$layout           = $app->input->get('layout', '');
		if ($layout == 'modal')
		{
			$this->share_site       = $app->input->get('site', 'frontend');
			$this->arrLanguageFiles = $model->getFileLanguageList($this->share_site);
		}

		$this->setDocument();
		
		$this->addToolBar();
		parent::display($tpl);
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JUDIRECTORY_MANAGER_LANGUAGES'));
		$document->addStyleSheet(JUri::root() . 'administrator/components/com_judirectory/assets/css/languages.css');
		$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/languages.js");
		$script = "jQuery(document).ready(function ($) {
	                     $('.img-tooltip').tooltip();
	                 }); ";

		$document->addScriptDeclaration($script);







	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_MANAGER_LANGUAGES'), 'languages');

		$app    = JFactory::getApplication();
		$layout = $app->input->get('layout', '');
		if ($layout == 'modal')
		{
			JToolBarHelper::save('languages.share', 'JTOOLBAR_SHARE');
		}
		else
		{
			JToolBarHelper::apply('languages.save', 'JTOOLBAR_APPLY');
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}
}