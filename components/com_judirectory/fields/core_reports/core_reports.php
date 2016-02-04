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

class JUDirectoryFieldCore_reports extends JUDirectoryFieldText
{
	protected $field_name = 'reports';
	protected $fieldvalue_column = "reports";

	protected function getValue()
	{
		$app = JFactory::getApplication();
		
		if ($app->isSite() && isset($this->listing->total_reports) && !is_null($this->listing->total_reports))
		{
			$value = $this->listing->total_reports;
		}
		else
		{
			$db    = JFactory::getDbo();
			$query = "SELECT count(*) FROM #__judirectory_reports WHERE (item_id = " . $this->listing_id . " AND type = 'listing')";
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

		return '<span class="reports"><a href="index.php?option=com_judirectory&view=reports&listing_id=' . $this->listing_id . '" title="' . JText::_('COM_JUDIRECTORY_VIEW_REPORTS') . '">' . JText::plural('COM_JUDIRECTORY_N_REPORTS', $value) . '</a></span>';
	}

	public function onSimpleSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$query->where("(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type='listing') = " . (int) $search);
		}
	}

	public function onSearch(&$query, &$where, $search)
	{
		if ($this->params->get("is_numeric", 0) && is_array($search) && !empty($search))
		{
			if ($search['from'] !== "" && $search['to'] !== "")
			{
				$from = (int) $search['from'];
				$to   = (int) $search['to'];
				if ($from > $to)
				{
					$this->swap($from, $to);
				}

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type='listing') BETWEEN $from AND $to";
			}
			elseif ($search['from'] !== "")
			{
				$from = (int) $search['from'];

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type='listing') >= $from";
			}
			elseif ($search['to'] !== "")
			{
				$to = (int) $search['to'];

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type='listing') <= $to";
			}
		}
		else
		{
			$this->onSimpleSearch($query, $where, $search);
		}
	}

	public function orderingPriority(&$query = null)
	{
		$this->appendQuery($query, 'select', '(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type="listing") AS reports');

		return array('ordering' => 'reports', 'direction' => $this->priority_direction);
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