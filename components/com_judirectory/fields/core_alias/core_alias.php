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

class JUDirectoryFieldCore_alias extends JUDirectoryFieldText
{
	protected $field_name = 'alias';

	public function filterField($value)
	{
		if (trim($value) == '')
		{
			$fieldTitle = new JUDirectoryFieldCore_title();
			$titleValue = $this->fields_data[$fieldTitle->id];
			$value      = $titleValue;
		}

		$value = JApplication::stringURLSafe($value);

		if (trim(str_replace('-', '', $value)) == '')
		{
			$value = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		return $value;
	}

	public function PHPValidate($values)
	{
		
		if (($values === "" || $values === null) && !$this->isRequired())
		{
			return true;
		}

		$fieldCategories = new JUDirectoryFieldCore_categories();
		
		if (!isset($this->fields_data[$fieldCategories->id]))
		{
			return true;
		}
		else
		{
			$categoriesValue = $this->fields_data[$fieldCategories->id];
		}

		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->SELECT('COUNT(*)');
		$query->FROM('#__judirectory_listings AS listing');
		$query->JOIN('', '#__judirectory_listings_xref AS listingxref ON listingxref.listing_id = listing.id');
		$query->JOIN('', '#__judirectory_categories AS c ON listingxref.cat_id = c.id');
		
		$query->WHERE('listing.alias = ' . $db->quote($values));
		$query->WHERE('c.id = ' . (int) $categoriesValue['main']);

		if ($this->listing_id)
		{
			
			if ($this->listing->approved < 0)
			{
				$query->WHERE('listing.id != ' . abs($this->listing->approved));
			}
			
			elseif ($this->listing->approved == 1)
			{
				$query->WHERE('listing.approved != ' . (-$this->listing->id));
			}

			
			if ($this->listing->id)
			{
				$query->WHERE('listing.id !=' . $this->listing->id);
			}
		}

		$db->setQuery($query);

		if ($db->loadResult())
		{
			return JText::_('COM_JUDIRECTORY_LISTING_ALIAS_MUST_BE_UNIQUE');
		}

		return true;
	}
}

?>