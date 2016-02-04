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

JLoader::register('JUDirectoryHelper', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judirectory.php');
JLoader::register('JUDirectoryFrontHelperField', JPATH_SITE . '/components/com_judirectory/helpers/field.php');
JLoader::register('JUDirectoryFrontHelper', JPATH_SITE . '/components/com_judirectory/helpers/helper.php');
spl_autoload_register(array('JUDirectoryHelper', 'autoLoadFieldClass'));

JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');


jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

class JFormFieldCustomSearch extends JFormField
{
	protected $type = 'customsearch';

	protected function getInput()
	{
		$language = JFactory::getLanguage();
		$language->load('com_judirectory', JPATH_ADMINISTRATOR);
		$isJoomla3x  = JUDirectoryHelper::isJoomla3x();
		$fieldsGroup = JUDirectoryHelper::getAdvSearchFields();
		$html        = '<div id="judirectory-field" style="clear: both;">';
		if ($isJoomla3x)
		{
			$html .= JHtml::_('bootstrap.startTabSet', 'search-form', array('active' => 'fieldgroup-1'));
		}
		else
		{
			$html .= JHtml::_('tabs.start', 'search-form');
		}

		foreach ($fieldsGroup AS $groupField)
		{
			if ($isJoomla3x)
			{
				$html .= JHtml::_('bootstrap.addTab', 'search-form', 'fieldgroup-' . $groupField->id, $groupField->name, true);
			}
			else
			{
				$html .= JHtml::_('tabs.panel', $groupField->name, 'fieldgroup-' . $groupField->id);
			}

			if (!$isJoomla3x)
			{
				$html .= '<fieldset class="adminform">';
				$html .= '<ul class="adminformlist">';
			}

			foreach ($groupField->fields AS $field)
			{
				$value      = isset($this->value[$field->id]) ? $this->value[$field->id] : "";
				$fieldClass = JUDirectoryFrontHelperField::getField($field);
				
				if (JFactory::getApplication()->input->getCmd('view', '') == 'module')
				{
					$fieldClass->name = 'jform[params][fields][' . $fieldClass->id . ']';
				}

				if ($isJoomla3x)
				{
					$html .= '<div class="control-group">';
					$html .= '<div class="control-label">';
					$html .= $fieldClass->getLabel(false);
					$html .= '</div>';
					$html .= '<div class="controls">';
					$html .= $fieldClass->getDisplayPrefixText() . $fieldClass->getSearchInput($value) . $fieldClass->getDisplaySuffixText();
					$html .= "</div>";
					$html .= "</div>";
				}
				else
				{
					$html .= "<li>";
					$html .= $fieldClass->getLabel(false);
					$html .= $fieldClass->getDisplayPrefixText() . $fieldClass->getSearchInput($value) . $fieldClass->getDisplaySuffixText();
					$html .= "</li>";
				}
			}

			if (!$isJoomla3x)
			{
				$html .= '</ul>';
				$html .= '</fieldset>';
			}

			if ($isJoomla3x)
			{
				$html .= JHtml::_('bootstrap.endTab');
			}
		}

		if ($isJoomla3x)
		{
			$html .= JHtml::_('bootstrap.endTabSet');
		}
		else
		{
			$html .= JHtml::_('tabs.end');
		}

		$html .= '</div>';

		return $html;
	}
}

?>