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

class JUDirectoryModelCategory extends JUDIRModelList
{
	
	protected function populateState($ordering = null, $direction = null)
	{
		
		$app = JFactory::getApplication();

		
		$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
		$catId        = $app->input->getInt('id', $rootCategory->id);
		$this->setState('category.id', $catId);

		
		$params = JUDirectoryHelper::getParams($catId);
		$this->setState('params', $params);

		
		if ($this->context)
		{
			
			$listingPagination = $params->get('listing_pagination', 10);

                         // print_r($listingPagination);

			
			$limitArray = JUDirectoryFrontHelper::customLimitBox();

			if (is_array($limitArray) && count($limitArray))
			{
				

				
				$limit = $app->input->getUint('limit', null);
				if (is_null($limit) || in_array($limit, $limitArray))
				{
					
					$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $listingPagination, 'uint');
				}
				else
				{
					
					$limit = $listingPagination;
				}
			}
			else
			{
				
				$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $listingPagination, 'uint');
			}

			$this->setState('list.limit', $limit);

			$this->setState('list.start', $app->input->getUint('limitstart', 0));

			 $orderCol = $app->getUserStateFromRequest($this->context . '.list.ordering', 'filter_order', '', 'string');
			$this->setState('list.ordering', $orderCol);

			 $listOrder = $app->getUserStateFromRequest($this->context . '.list.direction', 'filter_order_Dir', '', 'cmd');
			$this->setState('list.direction', $listOrder);
		}
		else
		{
			$this->setState('list.start', 0);
			$this->state->set('list.limit', 0);
		}
	}

	
	public function getStart()
	{
		return $this->getState('list.start');
	}

	
	public function getRelatedCategories($categoryId, $ordering = 'crel.ordering', $direction = 'ASC')
	{
		$storeId = md5(__METHOD__ . "::" . $categoryId . "::" . $ordering . "::" . $direction);

		if (!isset($this->cache[$storeId]))
		{
			$params            = $this->getState('params');
			$showEmptyCategory = $params->get('show_empty_related_category', 1);

			$user      = JFactory::getUser();
			$levels    = $user->getAuthorisedViewLevels();
			$levelsStr = implode(',', $levels);

			$db       = JFactory::getDbo();
			$nullDate = $db->getNullDate();
			$nowDate  = JFactory::getDate()->toSql();

			
			$query = $db->getQuery(true);
			$query->select('c.*');
			$query->from('#__judirectory_categories AS c');

			
			$query->join('INNER', '#__judirectory_categories_relations AS crel ON c.id=crel.cat_id_related');
			$query->where('crel.cat_id =' . $categoryId);

			
			$query->where('c.published = 1');
			$query->where('(c.publish_up = ' . $db->quote($nullDate) . ' OR c.publish_up <= ' . $db->quote($nowDate) . ')');
			$query->where('(c.publish_down = ' . $db->quote($nullDate) . ' OR c.publish_down >= ' . $db->quote($nowDate) . ')');

			
			$query->where('c.access IN (' . $levelsStr . ')');

			
			$categoryIdArrayCanAccess = JUDirectoryFrontHelperPermission::getAccessibleCategoryIds();
			if (is_array($categoryIdArrayCanAccess) && count($categoryIdArrayCanAccess) > 0)
			{
				$query->where('c.id IN(' . implode(",", $categoryIdArrayCanAccess) . ')');
			}
			else
			{
				$query->where('c.id IN("")');
			}

			
			$app = JFactory::getApplication();
			$tag = JFactory::getLanguage()->getTag();
			if ($app->getLanguageFilter())
			{
				$query->where('c.language IN (' . $db->quote($tag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
			}

			
			$query->order($ordering . ' ' . $direction);

			
			$query->group('c.id');

			$db->setQuery($query);
			$categoriesBefore = $db->loadObjectList();

			$categoriesAfter = array();
			foreach ($categoriesBefore AS $category)
			{
				
				$showTotalSubCats       = $params->get('show_total_subcats_of_relcat', 0);
				$showTotalChildListings = $params->get('show_total_listings_of_relcat', 0);

				$nestedCategories = null;

				if ($showTotalChildListings || $showTotalSubCats)
				{
					$nestedCategories = JUDirectoryFrontHelperCategory::getCategoriesRecursive($category->id, true, true, true, false, false, true);

					if ($showTotalChildListings)
					{
						
						$category->total_listings = JUDirectoryFrontHelperCategory::getTotalListingsInCategory($category->id, $nestedCategories);
					}

					if ($showTotalSubCats)
					{
						
						$category->total_nested_categories = JUDirectoryFrontHelperCategory::getTotalSubCategoriesInCategory($category->id, $nestedCategories);
					}
				}

				
				$registry = new JRegistry;
				$registry->loadString($category->images);
				$category->images = $registry->toObject();

				
				$category->link = JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($category->id));

				
				if (!$showEmptyCategory)
				{
					
					if (is_null($nestedCategories))
					{
						$nestedCategories = JUDirectoryFrontHelperCategory::getCategoriesRecursive($category->id, true, true, true, false, false, true);
					}

					if (!isset($category->total_nested_categories))
					{
						$category->total_nested_categories = JUDirectoryFrontHelperCategory::getTotalSubCategoriesInCategory($category->id, $nestedCategories);
					}
					if (!isset($category->total_listings))
					{
						$category->total_listings = JUDirectoryFrontHelperCategory::getTotalListingsInCategory($category->id, $nestedCategories);
					}
					if (($category->total_nested_categories > 0) || ($category->total_listings > 0))
					{
						$categoriesAfter[] = $category;
					}
				}
				else
				{
					$categoriesAfter[] = $category;
				}
			}

			
			$this->cache[$storeId] = $categoriesAfter;
		}

		return $this->cache[$storeId];
	}

	
	public function getSubCategories($parentId, $ordering = 'title', $direction = 'ASC')
	{
		$params            = $this->getState('params');
		$showEmptyCategory = $params->get('show_empty_subcategory', 1);

		$user      = JFactory::getUser();
		$levels    = $user->getAuthorisedViewLevels();
		$levelsStr = implode(',', $levels);

		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_categories');
		$query->where('parent_id=' . $parentId);

		
		$query->where('published = 1');
		$query->where('(publish_up = ' . $db->quote($nullDate) . ' OR publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(publish_down = ' . $db->quote($nullDate) . ' OR publish_down >= ' . $db->quote($nowDate) . ')');

		
		$query->where('access IN (' . $levelsStr . ')');

		
		$app = JFactory::getApplication();
		$tag = JFactory::getLanguage()->getTag();
		if ($app->getLanguageFilter())
		{
			$query->where('language IN (' . $db->quote($tag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
		}

		
		$query->order($ordering . ' ' . $direction);

		$db->setQuery($query);
		$subCategoriesBefore = $db->loadObjectList();

		$subCategoriesAfter = array();
		foreach ($subCategoriesBefore AS $category)
		{
			
			$showTotalSubCats       = $params->get('show_total_subcats_of_subcat', 0);
			$showTotalChildListings = $params->get('show_total_listings_of_subcat', 0);

			$nestedCategories = null;

			if ($showTotalChildListings || $showTotalSubCats)
			{
				$nestedCategories = JUDirectoryFrontHelperCategory::getCategoriesRecursive($category->id, true, true, true, false, false, true);

				if ($showTotalChildListings)
				{
					
					$category->total_listings = JUDirectoryFrontHelperCategory::getTotalListingsInCategory($category->id, $nestedCategories);
				}

				if ($showTotalSubCats)
				{
					
					$category->total_nested_categories = JUDirectoryFrontHelperCategory::getTotalSubCategoriesInCategory($category->id, $nestedCategories);
				}
			}

			
			$registry = new JRegistry;
			$registry->loadString($category->images);
			$category->images = $registry->toObject();

			
			$category->link = JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($category->id));

			if (!$showEmptyCategory)
			{
				
				if (is_null($nestedCategories))
				{
					$nestedCategories = JUDirectoryFrontHelperCategory::getCategoriesRecursive($category->id, true, true, true, false, false, true);
				}

				if (!isset($category->total_nested_categories))
				{
					$category->total_nested_categories = JUDirectoryFrontHelperCategory::getTotalSubCategoriesInCategory($category->id, $nestedCategories);
				}
				if (!isset($category->total_listings))
				{
					$category->total_listings = JUDirectoryFrontHelperCategory::getTotalListingsInCategory($category->id, $nestedCategories);
				}

				if (($category->total_nested_categories > 0) || ($category->total_listings > 0))
				{
					$subCategoriesAfter[] = $category;
				}
			}
			else
			{
				$subCategoriesAfter[] = $category;
			}
		}

		return $subCategoriesAfter;
	}

	
	protected function getListQuery()
	{
		$catId     = $this->getState('category.id');
		$ordering  = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');

		$user      = JFactory::getUser();
		$levels    = $user->getAuthorisedViewLevels();
		$levelsStr = implode(',', $levels);

		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		$query = $db->getQuery(true);
		$query->select('listing.*');
		$query->from('#__judirectory_listings AS listing');

		JUDirectoryFrontHelper::optimizeListListingQuery($query);

		
		$query->join("", "#__judirectory_listings_xref AS listingxref ON listing.id = listingxref.listing_id");
		$query->join("", "#__judirectory_categories AS c ON c.id = listingxref.cat_id");

		
		$query->where('c.id = ' . $catId);

		
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

		
		JUDirectoryFrontHelperField::appendFieldOrderingPriority($query, $catId, $ordering, $direction);

		return $query;
	}

	
	public function getItems()
	{
		$params            = $this->getState('params');
		$listingObjectList = parent::getItems();

		JUDirectoryFrontHelper::appendDataToListingObjList($listingObjectList, $params);

		return $listingObjectList;
	}
}