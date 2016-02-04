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

class JUDirectoryFieldCore_subscriptions extends JUDirectoryFieldText
{
	protected $field_name = 'subscriptions';
	protected $fieldvalue_column = "subscriptions";

	protected function getValue()
	{
		$app = JFactory::getApplication();
		
		if ($app->isSite() && isset($this->listing->total_subscriptions) && !is_null($this->listing->total_subscriptions))
		{
			$value = $this->listing->total_subscriptions;
		}
		else
		{
			$where_str = $app->isSite() ? ' AND published = 1' : '';

			$db    = JFactory::getDbo();
			$query = "SELECT COUNT(*) FROM #__judirectory_subscriptions WHERE (item_id = " . $this->listing_id . " AND type = 'listing'$where_str)";
			$db->setQuery($query);
			$result = $db->loadResult();
			$value  = $result;
		}

		return $value;
	}

	
	public function storeValue($value)
	{
		return true;
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function getBackendOutput()
	{
		$value = $this->value;

		return '<span class="subscriptions"><a href="index.php?option=com_judirectory&view=subscriptions&listing_id=' . $this->listing_id . '" title="' . JText::_('COM_JUDIRECTORY_VIEW_SUBSCRIPTIONS') . '">' . JText::plural('COM_JUDIRECTORY_N_SUBSCRIPTIONS', $value) . ' </a></span>';
	}

	public function onSearch(&$query, &$where, $search)
	{
		if (is_array($search) && !empty($search))
		{
			$app       = JFactory::getApplication();
			$where_str = $app->isSite() ? ' AND s.published = 1' : '';

			if ($search['from'] !== "" && $search['to'] !== "")
			{
				$from = (int) $search['from'];
				$to   = (int) $search['to'];
				if ($from > $to)
				{
					$this->swap($from, $to);
				}

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_subscriptions AS s WHERE s.item_id = listing.id AND s.type='listing'$where_str) BETWEEN $from AND $to";
			}
			elseif ($search['from'] !== "")
			{
				$from = (int) $search['from'];

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_subscriptions AS s WHERE s.item_id = listing.id AND s.type='listing'$where_str) >= $from";
			}
			elseif ($search['to'] !== "")
			{
				$to = (int) $search['to'];

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_subscriptions AS s WHERE s.item_id = listing.id AND s.type='listing'$where_str) <= $to";
			}
		}
		else
		{
			$this->onSimpleSearch($query, $where, $search);
		}
	}

	public function onSimpleSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$app       = JFactory::getApplication();
			$where_str = $app->isSite() ? ' AND s.published = 1' : '';
			$where[]   = "(SELECT count(*) FROM #__judirectory_subscriptions AS s WHERE s.item_id = listing.id AND s.type='listing'$where_str) = " . (int) $search;
		}
	}

	public function orderingPriority(&$query = null)
	{
		$app       = JFactory::getApplication();
		$where_str = $app->isSite() ? ' AND s.published = 1' : '';
		$this->appendQuery($query, 'select', '(SELECT COUNT(*) FROM #__judirectory_subscriptions AS s WHERE s.item_id = listing.id AND s.type="listing"' . $where_str . ') AS subscriptions');

		return array('ordering' => 'subscriptions', 'direction' => $this->priority_direction);
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