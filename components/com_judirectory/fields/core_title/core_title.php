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

class JUDirectoryFieldCore_title extends JUDirectoryFieldText
{
	protected $field_name = 'title';

	public function __construct($field = null, $listing = null)
	{
		parent::__construct($field, $listing);
		$this->required = true;
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		if (!$this->value)
		{
			return "";
		}

		$title         = $this->value;
		$isDetailsView = $this->isDetailsView($options);
		if (!$isDetailsView)
		{
			if ($this->params->get('max_length_list_view', 0) > 0)
			{
				$title = substr($title, 0, $this->params->get('max_length_list_view', 0));
				if (strlen($title) < strlen($this->value))
				{
					$title .= "...";
				}
			}
		}
		else
		{
			if ($this->params->get('max_length_details_view', 0) > 0)
			{
				$title = substr($title, 0, $this->params->get('max_length_details_view', 0));
				if (strlen($title) < strlen($this->value))
				{
					$title .= "...";
				}
			}
		}

		$this->setVariable('value', $title);
		$this->setVariable('isDetailsView', $isDetailsView);

		return $this->fetch('output.php', __CLASS__);
	}

	
	public function getBackendOutput()
	{
		return '';
	}

	public function PHPValidate($values)
	{
		
		if ($values === "")
		{
			return JText::_('COM_JUDIRECTORY_TITLE_MUST_NOT_BE_EMPTY');
		}

		$aliasField              = new JUDirectoryFieldCore_alias(null, $this->listing);
		$aliasField->fields_data = $this->fields_data;

		if (($this->is_new && (!$aliasField->canSubmit() || ($aliasField->canSubmit() && $this->fields_data[$aliasField->id] == "")))
			|| (!$this->is_new && (!$aliasField->canEdit() || ($aliasField->canEdit() && $this->fields_data[$aliasField->id] == "")))
		)
		{
			$aliasValue = $aliasField->filterField($values);

			return $aliasField->PHPValidate($aliasValue);
		}

		return true;
	}

	public function storeValue($value)
	{
		
		$aliasField              = new JUDirectoryFieldCore_alias(null, $this->listing);
		$aliasField->fields_data = $this->fields_data;
		
		if (($this->is_new && (!$aliasField->canSubmit() || ($aliasField->canSubmit() && $this->fields_data[$aliasField->id] == "")))
			|| (!$this->is_new && (!$aliasField->canEdit() || ($aliasField->canEdit() && $this->fields_data[$aliasField->id] == "")))
		)
		{
			$aliasValue = $aliasField->filterField($value);
			$aliasField->storeValue($aliasValue);
		}

		if (parent::storeValue($value))
		{
			
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
			$tableListing = JTable::getInstance('Listing', 'JUDirectoryTable');
			$tableListing->load($this->listing_id);
			if (isset($tableListing->asset_id) && $tableListing->asset_id > 0)
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->update('#__assets');
				$query->set('title =' . $db->quote($value));
				$query->where('id =' . $tableListing->asset_id);
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	
	public function canSubmit($userID = null)
	{
		return true;
	}
}

?>