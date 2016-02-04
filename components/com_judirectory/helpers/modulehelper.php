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

JLoader::register('JUDirectoryFrontHelperCategory', JPATH_SITE . '/components/com_judirectory/helpers/category.php');

class JUDirectoryModuleHelper
{
	public $params = null;
	public $view = null;
	public $input;

	public function __construct($params = '')
	{
		$this->params = $params;
		$this->input  = JFactory::getApplication()->input;
	}

	
	public function isModuleShown()
	{
		
		if ($this->input->get('option', '') != 'com_judirectory')
		{
			return true;
		}

		$assign_cats       = $this->params->get('assign_cats', 'all');
		$pages_assignment  = $this->params->get('pages_assignment', array());
		$allAssignedCatIds = $this->getAllAssignedCatIds();

		if (is_null($pages_assignment))
		{
			$pages_assignment = array();
		}

		
		if ($this->getCategoryIdInPage() !== false)
		{
			$cat_id = $this->getCategoryIdInPage();
			if (in_array('categories', $pages_assignment) && ($assign_cats == 'all' || in_array($cat_id, $allAssignedCatIds)))
			{
				return true;
			}

			return false;
		}
		
		elseif ($this->getListingIdInPage() !== false)
		{
			$listing_id = $this->getListingIdInPage();
			$cat_id     = JUDirectoryFrontHelperCategory::getMainCategoryId($listing_id);
			if (in_array('listings', $pages_assignment) && ($assign_cats == 'all' || in_array($cat_id, $allAssignedCatIds)))
			{
				return true;
			}

			return false;
		}
		
		else
		{
			if ($this->params->get('other_pages_assignment', 1))
			{
				return true;
			}

			return false;
		}
	}

	
	public function getView()
	{
		if (!is_null($this->view))
		{
			return $this->view;
		}
		else
		{
			$this->view = $this->input->getCmd('view', '');

			return $this->view;
		}
	}

	
	public function getCategoryIdInPage()
	{
		$view = $this->getView();

		if (in_array($view, array('category', 'categories')))
		{
			return $this->input->getInt('id', 0);
		}
		else
		{
			return false;
		}
	}

	
	public function getListingIdInPage()
	{
		$view = $this->getView();

		if (in_array($view, array('listing')))
		{
			return $this->input->getInt('id', 0);
		}
		
		elseif (in_array($view, array('report', 'contact')))
		{
			return $this->input->getInt('listing_id', 0);
		}
		else
		{
			return false;
		}
	}

	
	protected function getAllAssignedCatIds()
	{
		$cats_assignment = $this->params->get('categories_assignment', array());
		$rootCatId       = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		$allAssignedCats = array();
		if (count($cats_assignment))
		{
			foreach ($cats_assignment AS $cat_id)
			{
				$recursiveCatIds = JUDirectoryFrontHelperCategory::getCategoryIdsRecursive($cat_id);
				array_unshift($recursiveCatIds, $cat_id);
				$allAssignedCats = array_merge($allAssignedCats, $recursiveCatIds);
			}
			
			array_unshift($allAssignedCats, $rootCatId);
		}

		return $allAssignedCats;
	}

	
	public static function getCurrentCatId()
	{
		$app    = JFactory::getApplication();
		$option = $app->input->getString('option', '');
		$view   = $app->input->getString('view', '');
		$catId  = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		if ($option == 'com_judirectory' && $view)
		{
			if ($view == 'listing')
			{
				$listingId = $app->input->getInt('id', 0);
				if ($listingId > 0)
				{
					$catId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
				}
			}
			elseif ($view == 'category' || $view == 'categories')
			{
				$catId = $app->input->getInt('id', 0);
			}
		}

		return $catId;
	}

	
	public static function getItemId($needles = null)
	{
		require_once 'route.php';
		$itemId = JUDirectoryHelperRoute::findItemId($needles);

		return $itemId = '&Itemid=' . $itemId;
	}

	
	public static function isEmptyCat($catId)
	{
		$categoryIdArrayCanAccess = JUDirectoryFrontHelperPermission::getAccessibleCategoryIds();
		if (empty($categoryIdArrayCanAccess))
		{
			return true;
		}

		$user        = JFactory::getUser();
		$levelsArray = $user->getAuthorisedViewLevels();
		$levelString = implode(',', $levelsArray);

		$db       = JFactory::getDBO();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		
		$query = $db->getQuery(true);

		$query->select('COUNT(*)');
		$query->from('#__judirectory_categories AS c');
		$query->where('c.parent_id = ' . $catId);

		
		$query->where('c.published = 1');
		$query->where('(c.publish_up = ' . $db->quote($nullDate) . ' OR c.publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(c.publish_down = ' . $db->quote($nullDate) . ' OR c.publish_down >= ' . $db->quote($nowDate) . ')');

		
		$query->where('c.access IN (' . $levelString . ')');

		
		$query->where('c.id IN (' . implode(",", $categoryIdArrayCanAccess) . ')');


		
		$app         = JFactory::getApplication();
		$tagLanguage = JFactory::getLanguage()->getTag();
		if ($app->getLanguageFilter())
		{
			$query->where('c.language IN (' . $db->quote($tagLanguage) . ',' . $db->quote('*') . ')');
		}
		$db->setQuery($query);

		$totalSubCats = $db->loadResult();

		
		$query = $db->getQuery(true);

		$query->select('COUNT(*)');
		$query->from('#__judirectory_listings AS listing');

		
		$query->where('listingxref.cat_id = ' . $catId);

		
		$query->where('listing.approved = 1');

		
		$query->where('listing.published = 1');
		$query->where('(listing.publish_up = ' . $db->quote($nullDate) . ' OR listing.publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(listing.publish_down = ' . $db->quote($nullDate) . ' OR listing.publish_down >= ' . $db->quote($nowDate) . ')');

		
		if ($user->get('guest'))
		{
			$query->where('listing.access IN (' . $levelString . ')');
		}
		else
		{
			$query->where('(listing.access IN (' . $levelString . ') OR (listing.created_by = ' . $user->id . '))');
		}

		
		$query->join('INNER', '#__judirectory_listings_xref AS listingxref ON listing.id = listingxref.listing_id');
		$query->where('listingxref.cat_id IN (' . implode(",", $categoryIdArrayCanAccess) . ')');

		
		$app         = JFactory::getApplication();
		$tagLanguage = JFactory::getLanguage()->getTag();
		if ($app->getLanguageFilter())
		{
			$query->where('listing.language IN (' . $db->quote($tagLanguage) . ',' . $db->quote('*') . ')');
		}
		$query->group('listing.id');
		$db->setQuery($query);

		$totalListings = $db->loadResult();

		if (!$totalSubCats && !$totalListings)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>