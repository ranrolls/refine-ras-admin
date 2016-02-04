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


jimport('joomla.application.component.modellist');


class JUDirectoryModelFieldGroups extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'fg.id',
				'fg.name',
				'fg.ordering',
				'fg.published',
				'total_fields'
			);
		}

		parent::__construct($config);
	}

	
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		parent::populateState('fg.ordering', 'asc');
	}

	
	protected function getStoreId($id = '')
	{
		
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	
	protected function getListQuery()
	{
		
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->SELECT('fg.*');
		$query->FROM('#__judirectory_fields_groups AS fg');
		$query->SELECT('(SELECT COUNT(*) FROM #__judirectory_fields AS field WHERE field.group_id = fg.id) AS total_fields');
		$query->SELECT('ua.name AS checked_out_name');
		$query->JOIN('LEFT', '#__users AS ua ON ua.id =  fg.checked_out');

		$search = $this->getState('filter.search');
		
		if (!empty($search))
		{
			$search = '%' . $db->escape($search, true) . '%';
			$where  = "(fg.name LIKE '{$search}')";
			$query->WHERE($where);
		}

		
		$orderCol  = $this->getState('list.ordering');
		$orderDirn = $this->getState('list.direction');

		if ($orderCol)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		if ($items)
		{
			foreach ($items AS $item)
			{
				$item->actionlink         = 'index.php?option=com_judirectory&amp;task=fieldgroup.edit&amp;id=' . $item->id;
				$item->assignedCategories = $item->id == 1 ? JText::_("JALL") : $this->getAssignedCategories($item->id);
			}
		}

		return $items;
	}

	
	protected function getAssignedCategories($fieldgroup_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT id, title FROM #__judirectory_categories WHERE fieldgroup_id = $fieldgroup_id ORDER BY level, lft";
		$db->setQuery($query);
		$categories     = $db->loadObjectList();
		$categories_arr = array();
		if ($categories)
		{
			foreach ($categories AS $category)
			{
				$url              = "index.php?option=com_judirectory&task=category.edit&id=" . $category->id;
				$categories_arr[] = "<a href=\"" . $url . "\" tile=\"Edit category\" />" . $category->title . "</a>";
			}
		}

		return implode(', ', $categories_arr);
	}
}
