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

class JUDirectoryFieldCheckboxes extends JUDirectoryFieldBase
{
	public function getDefaultPredefinedValues()
	{
		$options = $this->getPredefinedValues();
		$return  = array();
		if ($options)
		{
			foreach ($options AS $option)
			{
				if (isset($option->default) && $option->default == 1)
				{
					$return[] = $option->value;
				}
			}
		}

		return $return;
	}

	public function parseValue($value)
	{
		if (!$this->isPublished())
		{
			return null;
		}

		if ($value)
		{
			return explode("|", $value);
		}

		return $value;
	}

	public function getName()
	{
		$name = parent::getName();

		return $name . "[]";
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->setAttribute("type", "checkbox", "input");

		$value   = !is_null($fieldValue) ? (array) $fieldValue : (array) $this->value;
		$options = $this->getPredefinedValues();

		$this->setVariable('value', $value);
		$this->setVariable('options', $options);

		return $this->fetch('input.php', __CLASS__);
	}

	public function getSearchInput($defaultValue = "")
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->setAttribute("type", "checkbox", "search");

		$defaultValue = (array) $defaultValue;
		$options      = $this->getPredefinedValues();

		$this->setVariable('value', $defaultValue);
		$this->setVariable('options', $options);

		return $this->fetch('searchinput.php', __CLASS__);
	}

	public function onSave($data)
	{
		$preDefinedValues = $data['predefined_values'];
		$preDefinedValues = array_values($preDefinedValues);
		$i                = 0;
		foreach ($preDefinedValues AS $key => $preDefinedValue)
		{
			
			if (($preDefinedValue["value"] == "" && $i > 0))
			{
				unset($preDefinedValues[$key]);
			}
			
			else
			{
				$preDefinedValues[$key]["value"] = str_replace(array("|", ","), "", trim($this->filterField($preDefinedValue["value"])));
			}

			$i++;
		}

		$data['predefined_values'] = !empty($preDefinedValues) ? json_encode(array_values($preDefinedValues)) : "";

		return $data;
	}

	public function getPredefinedValuesHtml()
	{
		$this->loadDefaultAssets();

		JText::script('COM_JUDIRECTORY_OPTION_VALUE');
		JText::script('COM_JUDIRECTORY_REMOVE');
		JText::script('COM_JUDIRECTORY_CSV_JSON_DATA');
		JText::script('COM_JUDIRECTORY_CSV_JSON_DATA_DESC');
		JText::script('COM_JUDIRECTORY_CSV_DELIMITER');
		JText::script('COM_JUDIRECTORY_CSV_ENCLOSURE');
		JText::script('COM_JUDIRECTORY_PROCESSING');
		JText::script('COM_JUDIRECTORY_PROCESS');
		JText::script('COM_JUDIRECTORY_OPTION_VALUE_MUST_BE_UNIQUE');

		$document = JFactory::getDocument();
		$script   = "jQuery(document).ready(function($){
						$(\"#jform_predefined_values .table tbody\").dragsort({dragSelector: \"td\", dragEnd: function () {}, placeHolderTemplate: \"<td></td>\", dragSelectorExclude: \"input, .remove-option\"});
					});";
		$document->addScriptDeclaration($script);
		$html = "<div id=\"jform_predefined_values\">";
		$html .= "<div class=\"clearfix\">";
		$html .= "<button class=\"btn btn-mini add-option\"><i class=\"icon-new\"></i> " . JText::_('COM_JUDIRECTORY_ADD_AN_OPTION') . "</button>";
		$html .= "<button class=\"btn btn-mini fast-add-options\"><i class=\"icon-flash\"></i> " . JText::_('COM_JUDIRECTORY_FAST_ADD_OPTIONS') . "</button>";
		$html .= "</div>";
		$html .= "<table class='table table-striped table-bordered'>";
		$html .= "<thead>";
		$html .= "<tr>";
		$html .= "<th>" . JText::_("COM_JUDIRECTORY_SORT") . "</th>";
		$html .= "<th>" . JText::_("COM_JUDIRECTORY_VALUE") . "</th>";
		$html .= "<th>" . JText::_("COM_JUDIRECTORY_TEXT") . "</th>";
		$html .= "<th>" . JText::_("COM_JUDIRECTORY_DEFAULT") . "</th>";
		$html .= "<th>" . JText::_("COM_JUDIRECTORY_DISABLED") . "</th>";
		$html .= "<th>" . JText::_("COM_JUDIRECTORY_REMOVE") . "</th>";
		$html .= "</tr>";
		$html .= "</thead>";
		$html .= "<tbody>";
		$html .= "<tr></tr>";
		$options = $this->getPredefinedValues(1);
		if ($options)
		{
			foreach ($options AS $key => $option)
			{
				$isdefault  = (isset($option->default) && $option->default) ? "checked" : "";
				$isdisabled = (isset($option->disabled) && $option->disabled) ? "checked" : "";
				$text       = $option->text;
				$value      = htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8');
				$html .= "<tr>";
				$html .= '<td><a class="drag-icon"></a></td>';
				$html .= "<td>
							<label style=\"display: none\" for=\"input-value-" . $key . "\">" . JText::_("COM_JUDIRECTORY_OPTION_VALUE") . "</label>
							<input id=\"input-value-" . $key . "\" type=\"text\" class=\"validate-value value input-mini\" value=\"$value\" size=\"15\" name=\"jform[predefined_values][$key][value]\"/></td>";
				$html .= "<td><input type=\"text\" class=\"input-medium\" value=\"$text\" size=\"35\" name=\"jform[predefined_values][$key][text]\"/></td>";
				$html .= "<td><input type=\"checkbox\" value=\"1\" name=\"jform[predefined_values][$key][default]\" $isdefault/></td>";
				$html .= "<td><input type=\"checkbox\" value=\"1\" name=\"jform[predefined_values][$key][disabled]\" $isdisabled/></td>";
				$html .= "<td><a href=\"#\" class=\"btn btn-mini btn-danger remove-option\" ><i class=\"icon-minus\"></i> " . JText::_('COM_JUDIRECTORY_REMOVE') . "</a>";
				$html .= "</tr>";
			}
		}
		$html .= "</tbody>";
		$html .= "</table>";
		$html .= "</div>";
		$html .= "<div id=\"form-post-data\"></div>";

		return $html;
	}

	public function onSimpleSearch(&$query, &$where, $search)
	{
		
		$matched_options = array();
		$options         = $this->getPredefinedValues();
		foreach ($options AS $option)
		{
			if (strpos(mb_strtolower($search, 'UTF-8'), mb_strtolower($option->text, 'UTF-8')) !== false)
			{
				$matched_options[] = $option->value;
			}
		}

		
		parent::onSimpleSearch($query, $where, $matched_options);
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$values = (array) $this->value;

		if (!$values)
		{
			return "";
		}

		$options = $this->getPredefinedValues();

		$this->setVariable('values', $values);
		$this->setVariable('options', $options);

		return $this->fetch('output.php', __CLASS__);
	}

	public function getBackendOutput()
	{
		$html    = '';
		$options = $this->getPredefinedValues();
		$values  = (array) $this->value;
		if ($values)
		{
			$html .= '<ul class="nav">';
			
			foreach ($options AS $option)
			{
				if (in_array($option->value, $values))
				{
					$html .= '<li>' . $option->text . '</li>';
				}
			}
			$html .= '</ul>';
		}

		return $html;
	}

	public function onImport($value, &$message = '')
	{
		if ($value)
		{
			$value   = explode("|", $value);
			$options = $this->getPredefinedValues();
			foreach ($value AS $key => $_value)
			{
				$found = false;
				foreach ($options AS $option)
				{
					if ($option->value == $_value)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					unset($value[$key]);
				}
			}

			$value = implode("|", $value);
		}

		return $value;
	}
}

?>