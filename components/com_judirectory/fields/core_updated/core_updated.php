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

class JUDirectoryFieldCore_updated extends JUDirectoryFieldDateTime
{
	protected $field_name = 'updated';

	public function canView($options = array())
	{
		$storeId = md5(__METHOD__ . "::" . $this->listing_id . "::" . $this->id . "::" . serialize($options));

		if (!isset(self::$cache[$storeId]))
		{
			if (!$this->isPublished())
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			
			if (isset($this->listing) && $this->listing->cat_id)
			{
				$params = JUDirectoryHelper::getParams($this->listing->cat_id);
			}
			else
			{
				$params = JUDirectoryHelper::getParams(null, $this->listing_id);
			}

			$show_empty_field = $params->get('show_empty_field', 0);
			
			if ($this->listing_id && !$show_empty_field)
			{
				if (intval($this->value) == 0)
				{
					self::$cache[$storeId] = false;

					return self::$cache[$storeId];
				}
			}

			self::$cache[$storeId] = parent::canView($options);

			return self::$cache[$storeId];
		}

		return self::$cache[$storeId];
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			$value = !is_null($fieldValue) ? $fieldValue : $this->value;

			$this->addAttribute("type", "text", "input");
			$this->addAttribute("class", $this->getInputClass(), "input");
			$this->addAttribute("class", "readonly", "input");

			if ((int) $this->params->get("size", 32))
			{
				$this->setAttribute("size", (int) $this->params->get("size", 32), "input");
			}
			$this->setAttribute("readonly", "readonly", "input");

			$this->setVariable('value', $value);

			return $this->fetch('input.php', __CLASS__);
		}
		else
		{
			return parent::getInput($fieldValue);
		}
	}

	public function storeValue($value)
	{
		if (!$value)
		{
			$db    = JFactory::getDbo();
			$value = $db->getNullDate();
		}

		
		if ($value != $this->listing->updated)
		{
			return parent::storeValue($value);
		}
		
		else
		{
			return true;
		}
	}
}

?>