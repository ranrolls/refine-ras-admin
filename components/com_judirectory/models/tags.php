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

class JUDirectoryModelTags extends JUDIRModelList
{

	
	protected function populateState($ordering = null, $direction = null)
	{
		
		$app = JFactory::getApplication();

		$params = JUDirectoryHelper::getParams();
		$this->setState('params', $params);

		
		if ($this->context)
		{
			$limitArray = JUDirectoryFrontHelper::customLimitBox();

			if (is_array($limitArray) && count($limitArray))
			{
				$limit = $app->input->getUint('limit', null);
				if (is_null($limit) || in_array($limit, $limitArray))
				{
					$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
				}
				else
				{
					$limit = $app->getCfg('list_limit');
				}
			}
			else
			{
				$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
			}

			$this->setState('list.limit', $limit);

			$this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));

			$orderCol = $app->getUserStateFromRequest($this->context . '.list.ordering', 'filter_order', 'tag.title');
			$this->setState('list.ordering', $orderCol);

			$listOrder = $app->getUserStateFromRequest($this->context . '.list.direction', 'filter_order_Dir', 'ASC');
			$this->setState('list.direction', $listOrder);
		}
	}

	
	protected function getListQuery()
	{
		$ordering  = $this->getState('list.ordering', 'tag.title');
		$direction = $this->getState('list.direction', 'DESC');

		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		$user      = JFactory::getUser();
		$levels    = $user->getAuthorisedViewLevels();
		$levelsStr = implode(',', $levels);

		$query = $db->getQuery(true);
		$query->select('tag.*');
		$query->from('#__judirectory_tags AS tag');
		$query->where('tag.published = 1');
		$query->where('(tag.publish_up = ' . $db->quote($nullDate) . ' OR tag.publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(tag.publish_down = ' . $db->quote($nullDate) . ' OR tag.publish_down >= ' . $db->quote($nowDate) . ')');

		
		$query->where('tag.access IN (' . $levelsStr . ')');

		
		$app         = JFactory::getApplication();
		$languageTag = JFactory::getLanguage()->getTag();
		if ($app->getLanguageFilter())
		{
			$query->where('tag.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
		}

		$query->order($ordering . " " . $direction);

		return $query;
	}

	
	public function getItems()
	{
		$items = parent::getItems();
		if (is_array($items) && !empty($items))
		{
			foreach ($items AS $item)
			{
				$item->total_listings = (int) $this->getTotalListingsAssignTag($item->id);
			}
		}

		return $items;
	}

	
	public function getTotalListingsAssignTag($tagId)
	{
		$user      = JFactory::getUser();
		$levels    = $user->getAuthorisedViewLevels();
		$levelsStr = implode(',', $levels);

		$db       = $this->getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();
		$query    = $db->getQuery(true);

		
		$query->select('COUNT(*)');
		$query->from('#__judirectory_listings AS listing');

		
		$query->join('', '#__judirectory_listings_xref AS listingxmain ON listing.id = listingxmain.listing_id AND listingxmain.main = 1');
		$query->join('', '#__judirectory_categories AS cmain ON cmain.id = listingxmain.cat_id');

		
		$categoryIdArrayCanAccess = JUDirectoryFrontHelperPermission::getAccessibleCategoryIds();
		if (is_array($categoryIdArrayCanAccess) && count($categoryIdArrayCanAccess) > 0)
		{
			$query->where('cmain.id IN(' . implode(",", $categoryIdArrayCanAccess) . ')');
		}
		else
		{
			$query->where('cmain.id IN("")');
		}

		
		$query->where('listing.approved = 1');

		
		$query->where('listing.published = 1');
		$query->where('(listing.publish_up = ' . $db->quote($nullDate) . ' OR listing.publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(listing.publish_down = ' . $db->quote($nullDate) . ' OR listing.publish_down >= ' . $db->quote($nowDate) . ')');

		
		if ($user->get('guest'))
		{
			$query->where('listing.access IN (' . $levelsStr . ')');
		}
		else
		{
			$query->where('(listing.access IN (' . $levelsStr . ') OR (listing.created_by = ' . $user->id . '))');
		}

		
		$app         = JFactory::getApplication();
		$languageTag = JFactory::getLanguage()->getTag();
		if ($app->getLanguageFilter())
		{
			$query->where('listing.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
		}

		
		$query->join('', '#__judirectory_tags_xref AS tag_xref ON tag_xref.listing_id = listing.id');
		$query->join('', '#__judirectory_tags AS tag ON tag.id = tag_xref.tag_id');

		
		$query->where('tag.published = 1');
		$query->where('(tag.publish_up = ' . $db->quote($nullDate) . ' OR tag.publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(tag.publish_down = ' . $db->quote($nullDate) . ' OR tag.publish_down >= ' . $db->quote($nowDate) . ')');

		
		$query->where('tag.id =' . $tagId);

		
		$db->setQuery($query);

		return (int) $db->loadResult();
	}

} 