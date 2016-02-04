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

class JUDirectoryFieldCore_addresses extends JUDirectoryFieldBase
{
	protected $field_name = 'addresses';
	protected $fieldvalue_column = "addresses";

	protected function getValue()
	{
		if ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__judirectory_locations');
			$query->where('listing_id = ' . $this->listing_id);
			$app = JFactory::getApplication();
			if ($app->isSite())
			{
				$query->where('published = 1');
			}
			$query->order($this->params->get('ordering', 'ordering') . ' ' . $this->params->get('ordering_direction', 'asc'));
			$db->setQuery($query);
			$locations = $db->loadObjectList();

			$addressTable = JTable::getInstance('Address', 'JUDirectoryTable');
			foreach ($locations AS $location)
			{
				if ($location->address_id && $addressTable->load($location->address_id))
				{
					$location->address_path = $addressTable->getPath();
				}
				else
				{
					$location->address_path = array();
				}
			}

			return !empty($locations) ? $locations : null;
		}

		return null;
	}

	
	public function storeValue($locations)
	{
		return true;
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	
	public function getBackendOutput()
	{
		return JText::plural('COM_JUDIRECTORY_N_ADDRESS', count($this->value));
	}

	public function getOutput($options = array())
	{
		return $this->fetch('output.php', __CLASS__);
	}

	public function onTagSearch(&$query, &$where, $tag = null)
	{
		
		if (!$this->params->get("tag_search", 0))
		{
			return false;
		}

		if ($tag !== "")
		{
			
			$tag        = JUDirectoryFrontHelper::UrlDecode($tag);
			$address_id = (int) $tag;
			if ($address_id)
			{
				$db     = JFactory::getDbo();
				$_query = $db->getQuery(true);
				$_query->select('n.id')
					->from('#__judirectory_addresses AS n, #__judirectory_addresses AS p')
					->where('n.lft BETWEEN p.lft AND p.rgt')
					->where('p.id = ' . $address_id)
					->order('n.lft');
				$db->setQuery($_query);
				$address_ids = $db->loadColumn();

				$query->join('LEFT', '#__judirectory_locations AS location ON location.listing_id = listing.id');
				$where[] = 'location.address_id IN (' . implode(', ', $address_ids) . ')';

				$query->group('listing.id');
			}
		}
	}

	public function getTextByValue($value)
	{
		$address_id = (int) $value;
		$db         = JFactory::getDbo();
		$query      = $db->getQuery(true);
		$query->select('title')
			->from('#__judirectory_addresses')
			->where('id = ' . $address_id);
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	public function getSearchInput($defaultValue = "")
	{
		return '';
	}

	public function getInput($fieldValue = null)
	{
		return '';
	}

	public function canEdit($userID = null)
	{
		return false;
	}

	public function canSearch($userID = null)
	{
		return false;
	}

	public function canSubmit($userID = null)
	{
		return false;
	}

	public function orderingPriority(&$query = null)
	{
		$app       = JFactory::getApplication();
		$where_str = $app->isSite() ? ' AND location.published = 1' : '';
		$this->appendQuery($query, 'select', '(SELECT COUNT(*) FROM #__judirectory_locations AS location WHERE (location.listing_id = listing.id' . $where_str . ')) AS locations');

		return array('ordering' => 'locations', 'direction' => $this->priority_direction);
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}
}

?>