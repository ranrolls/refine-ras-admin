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

class JUDirectoryFieldCore_publish_up extends JUDirectoryFieldDateTime
{
	protected $field_name = 'publish_up';

	public function parseValue($value)
	{
		if (!$value || $value == '0000-00-00 00:00:00')
		{
			if (is_object($this->listing))
			{
				return $this->listing->created;
			}
		}

		return parent::parseValue($value);
	}

	public function onSaveListing($value = '')
	{
		
		$publishedField = new JUDirectoryFieldCore_published();
		if ($this->fields_data[$publishedField->id] == 1 && intval($value) == 0)
		{
			$date  = JFactory::getDate();
			$value = $date->toSql();
		}

		return $value;
	}
}

?>