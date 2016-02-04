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

class JUDirectoryFieldDropdownlist extends JUDirectoryFieldRadio
{
	public function getId()
	{
		return 'field_' . $this->id;
	}

	public function filterField($value)
	{
		
		if ($value == "<OPTGROUP>" || $value == "</OPTGROUP>")
		{
			return $value;
		}

		return parent::filterField($value);
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$options       = $this->getPredefinedValues();
		$selectOptions = array();
		if ($options)
		{
			$optGroupState = "close";
			foreach ($options AS $option)
			{
				if ($option->text == strtoupper($option->text))
				{
					$text = JText::_($option->text);
				}
				else
				{
					$text = $option->text;
				}

				$selectOptionItem['text']  = htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
				$selectOptionItem['value'] = htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8');

				if (strtoupper($option->value) == "<OPTGROUP>")
				{
					if ($optGroupState == "open")
					{
						$selectOptions[] = JHtml::_('select.option', '</OPTGROUP>');
						$optGroupState   = "close";
					}
					$selectOptions[] = JHtml::_('select.option', '<OPTGROUP>', $selectOptionItem['text']);
					$optGroupState   = "open";
				}
				elseif (strtoupper($option->value) == "</OPTGROUP>")
				{
					$selectOptions[] = JHtml::_('select.option', '</OPTGROUP>');
					$optGroupState   = "close";
				}
				else
				{
					if (isset($option->disabled) && $option->disabled)
					{
						$selectOptions[] = JHtml::_('select.option', $selectOptionItem['value'], $selectOptionItem['text'], "value", "text", true);
					}
					else
					{
						$selectOptions[] = JHtml::_('select.option', $selectOptionItem['value'], $selectOptionItem['text']);
					}
				}
			}

			if ($optGroupState == "open")
			{
				$selectOptions[] = JHtml::_('select.option', '</OPTGROUP>');
				$optGroupState   = "close";
			}
		}

		$value = !is_null($fieldValue) ? $fieldValue : $this->value;

		$this->addAttribute("class", $this->getInputClass(), "input");

		if ((int) $this->params->get("size", 1))
		{
			$this->setAttribute("size", (int) $this->params->get("size", 1), "input");
		}

		$this->setVariable('options', $selectOptions);
		$this->setVariable('value', $value);

		return $this->fetch('input.php', __CLASS__);
	}

	public function getSearchInput($defaultValue = "")
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$options       = $this->getPredefinedValues();
		$selectOptions = array();
		if ($options)
		{
			$optgroupState = "close";
			foreach ($options AS $option)
			{
				if ($option->text == strtoupper($option->text))
				{
					$text = JText::_($option->text);
				}
				else
				{
					$text = $option->text;
				}

				$selectOptionItem['text']  = htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
				$selectOptionItem['value'] = htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8');

				if (strtoupper($option->value) == "<OPTGROUP>")
				{
					if ($optgroupState == "open")
					{
						$selectOptions[] = JHtml::_('select.option', '</OPTGROUP>');
						$optgroupState   = "close";
					}
					$selectOptions[] = JHtml::_('select.option', '<OPTGROUP>', $selectOptionItem['text']);
					$optgroupState   = "open";
				}
				elseif (strtoupper($option->value) == "</OPTGROUP>")
				{
					$selectOptions[] = JHtml::_('select.option', '</OPTGROUP>');
					$optgroupState   = "close";
				}
				else
				{
					if (isset($option->disabled) && $option->disabled)
					{
						$selectOptions[] = JHtml::_('select.option', $selectOptionItem['value'], $selectOptionItem['text'], "value", "text", true);
					}
					else
					{
						$selectOptions[] = JHtml::_('select.option', $selectOptionItem['value'], $selectOptionItem['text']);
					}
				}
			}
		}

		if (!isset($options[0]->value) || $options[0]->value != "")
		{
			array_unshift($selectOptions, array("text" => "", "value" => ""));
		}

		if ($this->params->get("search_multiple", 1))
		{
			if ((int) $this->params->get("size", 5))
			{
				$this->setAttribute("size", (int) $this->params->get("size", 5), "search");
			}

			$this->setAttribute("multiple", "multiple", "search");
		}

		$this->setVariable('options', $selectOptions);
		$this->setVariable('value', $defaultValue);

		return $this->fetch('searchinput.php', __CLASS__);
	}
}

?>