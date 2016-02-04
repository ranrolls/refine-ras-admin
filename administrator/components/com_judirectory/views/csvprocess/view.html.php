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
JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');

class JUDirectoryViewCSVProcess extends JUDIRViewAdmin
{
	public $fieldsOption = array();

	public function display($tpl = null)
	{
		JHtml::_('behavior.calendar');
		$this->addToolBar();
		$this->model = $this->getModel();

		
		if ($this->getLayout() == "fields_mapping")
		{
			
			$this->fieldsOption[] = JHTML::_('select.option', '<OPTGROUP>', JText::_('COM_JUDIRECTORY_DEFAULT'));
			$this->fieldsOption[] = JHTML::_('select.option', 'ignore', JText::_("COM_JUDIRECTORY_IGNORE"));

			
			$this->fieldsOption[] = JHTML::_('select.option', '<OPTGROUP>', JText::_('COM_JUDIRECTORY_CORE_FIELDS'));

			$ignoredFields = array('asset_id', 'introtext', 'fulltext', 'comments');
			$coreFields    = $this->model->getCoreFields($ignoredFields);

			foreach ($coreFields AS $field)
			{
				if (is_object($field))
				{
					$value = $field->id;
					$label = ucfirst(JText::_($field->caption));
				}
				else
				{
					$value = $field;
					$label = ucfirst(str_replace('_', ' ', $field));
				}

				$this->fieldsOption[] = JHTML::_('select.option', $value, $label);
			}

			
			$extraFields = $this->model->getExtraFields();
			foreach ($extraFields AS $field)
			{
				if (isset($fieldGroups[$field->group_id]))
				{
					$fieldGroups[$field->group_id][] = $field;
				}
				else
				{
					$fieldGroups[$field->group_id] = array($field);
				}
			}

			foreach ($fieldGroups AS $groupId => $fields)
			{
				$group                = JUDirectoryFrontHelperField::getFieldGroupById($groupId);
				$this->fieldsOption[] = JHTML::_('select.option', '<OPTGROUP>', $group->name);
				foreach ($fields AS $field)
				{
					$label                = ucfirst(JText::_($field->caption));
					$this->fieldsOption[] = JHTML::_('select.option', $field->id, $label);
				}
			}

			$this->fieldsOption[] = JHTML::_('select.option', '<OPTGROUP>', JText::_('COM_JUDIRECTORY_OTHER_FIELDS'));
			$this->fieldsOption[] = JHTML::_('select.option', 'related_listings', JText::_('COM_JUDIRECTORY_FIELD_RELATED_LISTINGS'));
		}

		
		if ($this->getLayout() == 'config')
		{
			$this->form = $this->get('Form');
		}

		
		if ($this->getLayout() == 'review')
		{
			if (isset($this->review['config']['default_icon']))
			{
				$this->review['config']['default_icon'] = str_replace(array(JPATH_ROOT . '\\', "\\"), array(JUri::root(), '/'), $this->review['config']['default_icon']);
			}
		}
		$this->isJoomla3x = JUDirectoryHelper::isJoomla3x();
		
		if ($this->getLayout() == "export")
		{
			$this->exportForm = $this->get("ExportForm");
		}

		parent::display($tpl);

		
		$this->setDocument();
	}

	
	protected function setDocument()
	{
		if ($this->getLayout() == 'processing')
		{
			JText::script('COM_JUDIRECTORY_IMPORT_CSV_FINISHED');

			$document = JFactory::getDocument();
			$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/import-csv-ajax.js");
		}

	}

	public function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JUDIRECTORY_CSV_PROCESS'), 'csv-process');
		switch ($this->getLayout())
		{
			case 'import':
				JToolBarHelper::custom('csvprocess.loadCSVFile', 'next', 'next', 'Next', false);
				JToolBarHelper::cancel('csvprocess.cancel', 'JTOOLBAR_CANCEL');
				break;
			case 'fields_mapping':
				JToolBarHelper::custom('csvprocess.import', 'back', 'back', 'Back', false);
				JToolBarHelper::custom('csvprocess.mapFields', 'next', 'next', 'Next', false);
				JToolBarHelper::cancel('csvprocess.cancel', 'JTOOLBAR_CANCEL');
				break;
			case 'config':
				JToolBarHelper::custom('csvprocess.loadCSVFile', 'back', 'back', 'Back', false);
				JToolBarHelper::custom('csvprocess.config', 'next', 'next', 'Next', false);
				JToolBarHelper::cancel('csvprocess.cancel', 'JTOOLBAR_CANCEL');
				break;
			case 'review':
				JToolBarHelper::custom('csvprocess.mapFields', 'back', 'back', 'Back', false);
				JToolBarHelper::custom('csvprocess.review', 'next', 'next', 'Process', false);
				break;
			case 'processing':
				JToolBarHelper::custom('csvprocess.cancel', 'back', 'back', 'Back', false);
				break;
			case 'export':
				JToolBarHelper::custom('csvprocess.exportProcessing', 'export', 'export', 'Export', false);
				JToolBarHelper::cancel('csvprocess.cancel', 'JTOOLBAR_CANCEL');
				break;
		}

		JToolBarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_ADMINISTRATOR . "/components/com_judirectory/helpers/button");
		$bar->appendButton('JUHelp', 'help', JText::_('JTOOLBAR_HELP'));
	}
}