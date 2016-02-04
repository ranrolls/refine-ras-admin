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

jimport('joomla.application.component.helper');
jimport('joomla.filesystem.file');

abstract class JUDirectoryHelperRoute
{
	protected static $lookup;

	protected static $cache = array();

	public static function compareQuery($activeMenu, $query)
	{
		$queryTmp     = $query;
		$queryMenuTmp = $activeMenu->query;
		if (isset($queryTmp['format']))
		{
			unset($queryTmp['format']);
		}

		if (isset($queryTmp['limitstart']))
		{
			unset($queryTmp['limitstart']);
		}

		if (isset($queryTmp['start']))
		{
			unset($queryTmp['start']);
		}

		if (isset($queryTmp['limit']))
		{
			unset($queryTmp['limit']);
		}

		if (isset($queryTmp['Itemid']))
		{
			unset($queryTmp['Itemid']);
		}

		if (is_array($queryMenuTmp) && is_array($queryTmp))
		{
			if (count($queryMenuTmp) == count($queryTmp))
			{
				if (count(array_diff($queryMenuTmp, $queryTmp)) === 0 && count(array_diff($queryTmp, $queryMenuTmp)) === 0)
				{
					return true;
				}
			}
		}

		return false;
	}

	
	public static function getAdvsearchRoute($canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=advsearch';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('advsearch');
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getCompareRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=compare';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('compare');
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getUserProfileRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=profile';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('profile');
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getReportCommentRoute($commentId, $listingId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=report&comment_id=' . $commentId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getReportListingRoute($listingId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=report&listing_id=' . $listingId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getClaimListingRoute($listingId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=claim&listing_id=' . $listingId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getContactRoute($listingId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=contact&listing_id=' . $listingId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getListingSubscribeRoute($listingId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=subscribe&listing_id=' . $listingId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getSearchRoute($categoryId = 0, $subCat = 0, $searchWord = '', $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=search';

		if ($categoryId)
		{
			$link .= '&cat_id=' . $categoryId;
		}

		if ($subCat)
		{
			$link .= '&sub_cat=1';
		}

		if ($searchWord)
		{
			$link .= '&searchword=' . $searchWord;
		}

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = null;
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getModeratorCommentsRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=modcomments';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('modcomments');
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getModeratorPermissionsRoute()
	{
		$link = 'index.php?option=com_judirectory&view=modpermissions';

		$itemId = JUDirectoryHelperRoute::findItemIdByViewName('modpermissions');

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getModeratorPermissionRoute($id)
	{
		$link = 'index.php?option=com_judirectory&view=modpermission&id=' . $id;

		return $link;
	}

	
	public static function getModeratorPendingCommentsRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=modpendingcomments';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('modpendingcomments');
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getModeratorListingsRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=modlistings';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('modlistings');
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getModeratorPendingListingsRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=modpendinglistings';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('modpendinglistings');
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}


	
	public static function getUserCommentsRoute($userId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=usercomments&id=' . $userId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'usercomments' => array((int) $userId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getUserListingsRoute($userId, $filter = '', $canonical = false, $isRss = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=userlistings&id=' . $userId;

		if ($filter)
		{
			$link .= '&filter=' . $filter;
		}

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();

			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'userlistings' => array((int) $userId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}

	
	public static function getUserSubscriptionsRoute($userId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=usersubscriptions&id=' . $userId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'usersubscriptions' => array((int) $userId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}


	
	public static function getCategoriesRoute($categoryId = 1, $canonical = false, $layout = '')
	{
		
		$link = 'index.php?option=com_judirectory&view=categories&id=' . $categoryId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'categories' => array((int) $categoryId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
			}
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getCategoryRoute($categoryId, $categoryLevel1 = null, $isRss = false, $layout = '')
	{
		
		$link = 'index.php?option=com_judirectory&view=category&id=' . $categoryId;

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId, $categoryLevel1);

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}


		return $link;
	}

	
	public static function getTreeRoute($categoryId, $canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=tree&id=' . $categoryId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	
	public static function getListingRoute($listingId, $layout = '')
	{
		
		$link = 'index.php?option=com_judirectory&view=listing&id=' . $listingId;

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		$itemId = JUDirectoryHelperRoute::findItemIdOfListing($listingId);

		if ($itemId)
		{
			$link .= '&Itemid=' . $itemId;
		}

		return $link;
	}

	
	public static function getCollectionRoute($collectionId, $userId = false, $canonical = false, $isRss = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=collection&id=' . $collectionId;

		if ($userId)
		{
			$link .= '&user_id=' . $userId;
		}

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'collection' => array((int) $collectionId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}

	public static function getCollectionsRoute($userId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=collections&id=' . $userId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'collections' => array((int) $userId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getCommentTreeRoute($commentId, $canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=commenttree&id=' . $commentId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = null;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getDashboardRoute($userId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=dashboard&id=' . $userId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'dashboard' => array((int) $userId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getListingsRoute($canonical = false)
	{
		$link = 'index.php?option=com_judirectory&view=listings';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = null;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getFeaturedRoute($categoryId = 0, $fetchAllSubCat = 1, $canonical = false, $isRss = false, $layout = '')
	{
		if (!$categoryId)
		{
			$categoryId = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		}

		$link = 'index.php?option=com_judirectory&view=featured';

		$link .= '&id=' . (int) $categoryId;

		$link .= '&all=' . (int) $fetchAllSubCat;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'featured' => array((int) $categoryId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
			}
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}

	public static function getFormRoute($categoryId = 0, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=form&layout=edit';

		if ($categoryId)
		{
			$link .= '&cat_id=' . $categoryId;
		}

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getListAllRoute($categoryId = 0, $canonical = false, $isRss = false, $layout = '')
	{
		if (!$categoryId)
		{
			$categoryId = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		}

		$link = 'index.php?option=com_judirectory&view=listall';

		$link .= '&id=' . $categoryId;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			if ($categoryId)
			{
				$needles = array(
					'listall' => array((int) $categoryId)
				);

				$itemId = JUDirectoryHelperRoute::findItemId($needles);

				if (!$itemId)
				{
					$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
				}
			}
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}

	public static function getListAlphaRoute($categoryId = 0, $alphaWord, $canonical = false, $isRss = false, $layout = '')
	{
		if (!$categoryId)
		{
			$categoryId = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		}

		$link = 'index.php?option=com_judirectory&view=listalpha';

		$link .= '&id=' . $categoryId;

		$link .= '&alpha=' . $alphaWord;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			if ($categoryId)
			{
				$needles = array(
					'listalpha' => array((int) $categoryId)
				);

				$itemId = JUDirectoryHelperRoute::findItemId($needles);

				if (!$itemId)
				{
					$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
				}
			}
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}

	public static function getMaintenanceRoute($canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=maintenance';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = null;
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getSearchByRoute($fieldId, $fieldValue, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=searchby&field_id=' . $fieldId . '&value=' . $fieldValue;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = null;
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getTagRoute($id, $canonical = false, $isRss = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=tag&id=' . $id;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'tag' => array((int) $id)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}

	public static function getTagsRoute($canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=tags';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('tags');
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getTopCommentsRoute($canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=topcomments';

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdByViewName('topcomments');
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

	public static function getTopListingsRoute($categoryId = 0, $fetchAllSubCat = 1, $orderType, $canonical = false, $isRss = false, $layout = '')
	{
		if (!$categoryId)
		{
			$categoryId = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		}

		$link = 'index.php?option=com_judirectory&view=toplistings';

		$link .= '&id=' . $categoryId;

		$link .= '&all=' . $fetchAllSubCat;

		$link .= '&ordertype=' . $orderType;

		if ($canonical)
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);

			if (!$itemId)
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$itemId = JUDirectoryHelperRoute::findItemIdOfCategory($categoryId);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		if ($isRss)
		{
			$link .= "&format=feed";
		}

		return $link;
	}


	
	public static function getCategorySegment($categoryId, &$query, $fullPath = 0)
	{
		if (isset($query['Itemid']))
		{
			unset($query['Itemid']);
		}
		$segments = array();
		$params   = JUDirectoryHelper::getParams();

		$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();

		$categoryObject = JUDirectoryHelper::getCategoryById($categoryId);

		if (!is_object($categoryObject))
		{
			return false;
		}

		if ($categoryObject->level == 0)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$query['Itemid'] = $itemIdTreeRoot;

				return $segments;
			}

			$query['Itemid'] = JUDirectoryHelperRoute::getHomeMenuItemId();

			
			$sefRootCategory = $rootCategory->id . ':' . $params->get('sef_root_cat', 'root');
			$segments[]      = JApplication::stringURLSafe($sefRootCategory);

			return $segments;
		}
		elseif ($categoryObject->level == 1)
		{
			
			$menuItemIdsOfViewCategoryTreeLevel1 = JUDirectoryHelperRoute::getMenuItemIdArrayOfViewCategoryTreeLevel1();
			if (isset($menuItemIdsOfViewCategoryTreeLevel1[$categoryObject->id]))
			{
				$query['Itemid'] = $menuItemIdsOfViewCategoryTreeLevel1[$categoryObject->id];

				return $segments;
			}

			
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$query['Itemid'] = $itemIdTreeRoot;

				$segments[] = $categoryObject->id . ":" . $categoryObject->alias;

				return $segments;
			}

			$query['Itemid'] = JUDirectoryHelperRoute::getHomeMenuItemId();

			
			$sefRootCategory = $params->get('sef_root_cat', 'root');
			$segments[]      = JApplication::stringURLSafe($sefRootCategory);

			$segments[] = $categoryObject->id . ":" . $categoryObject->alias;

			return $segments;
		}
		else
		{
			
			$levelCats = JUDirectoryHelper::getCatsByLevel(1, $categoryObject->id);
			if (is_array($levelCats) && count($levelCats))
			{
				$categoryIdAncestorLevel1 = $levelCats[0]->id;
			}
			$menuItemIdsOfViewCategoryTreeLevel1 = JUDirectoryHelperRoute::getMenuItemIdArrayOfViewCategoryTreeLevel1();
			if (isset($menuItemIdsOfViewCategoryTreeLevel1[$categoryIdAncestorLevel1]))
			{
				$query['Itemid'] = $menuItemIdsOfViewCategoryTreeLevel1[$categoryIdAncestorLevel1];

				if ($fullPath)
				{
					$categoryPath = JUDirectoryHelper::getCategoryPath($categoryId);
					if (is_array($categoryPath) && count($categoryPath))
					{
						foreach ($categoryPath as $categoryPathValue)
						{
							if ($categoryPathValue->level > 1 && $categoryPathValue->id != $categoryObject->id)
							{
								$segments[] = $categoryPathValue->alias;
							}
						}
					}
				}

				$segments[] = $categoryObject->id . ":" . $categoryObject->alias;

				return $segments;
			}

			
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$query['Itemid'] = $itemIdTreeRoot;

				if ($fullPath)
				{
					$categoryPath = JUDirectoryHelper::getCategoryPath($categoryId);

					if (is_array($categoryPath) && count($categoryPath))
					{
						foreach ($categoryPath as $categoryPathValue)
						{
							if ($categoryPathValue->level > 0 && $categoryPathValue->id != $categoryObject->id)
							{
								$segments[] = $categoryPathValue->alias;
							}
						}
					}
				}

				$segments[] = $categoryObject->id . ":" . $categoryObject->alias;

				return $segments;
			}

			$query['Itemid'] = JUDirectoryHelperRoute::getHomeMenuItemId();

			if ($fullPath)
			{
				
				$sefRootCategory = $params->get('sef_root_cat', 'root');
				$segments[]      = JApplication::stringURLSafe($sefRootCategory);
				$categoryPath    = JUDirectoryHelper::getCategoryPath($categoryId);
				if (is_array($categoryPath) && count($categoryPath))
				{
					foreach ($categoryPath as $categoryPathValue)
					{
						if ($categoryPathValue->level > 0 && $categoryPathValue->id != $categoryObject->id)
						{
							$segments[] = $categoryPathValue->alias;
						}
					}
				}
			}

			$segments[] = $categoryObject->id . ":" . $categoryObject->alias;

			return $segments;
		}

		return $segments;
	}

	
	public static function getListingSegment($listingId, &$query, $params)
	{
		if (isset($query['Itemid']))
		{
			unset($query['Itemid']);
		}

		$fullPathListing = $params->get('sef_listing_full_path', 0);

		$listingObject  = JUDirectoryHelper::getListingById($listingId);
		$mainCategoryId = $listingObject->cat_id;
		$segment        = JUDirectoryHelperRoute::getCategorySegment($mainCategoryId, $query, $fullPathListing);
		$segment[]      = $listingId . ":" . (isset($listingObject->alias) ? $listingObject->alias : '');

		return $segment;
	}

	
	public static function findItemIdOfCategory($catId, $categoryLevel1 = null)
	{
		
		$menuIds = self::getMenuItemIdArrayOfViewCategoryTreeLevel0And1();

		if (!empty($menuIds))
		{
			
			if (isset($menuIds[$catId]))
			{
				return $menuIds[$catId];
			}
			else
			{
				
				if (!$categoryLevel1)
				{
					$levelCats = JUDirectoryHelper::getCatsByLevel(1, $catId);
					if (is_array($levelCats) && count($levelCats))
					{
						$categoryLevel1 = $levelCats[0]->id;
					}
				}

				
				if (isset($menuIds[$categoryLevel1]))
				{
					return $menuIds[$categoryLevel1];
				}

				
				$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
				if (isset($menuIds[$rootCat->id]))
				{
					return $menuIds[$rootCat->id];
				}
			}
		}

		
		return null;
	}

	
	public static function findItemIdOfListing($id)
	{
		$catId = JUDirectoryFrontHelperCategory::getMainCategoryId($id);

		return self::findItemIdOfCategory($catId);
	}

	
	public static function findItemIdByViewName($viewName)
	{
		$menuItems = self::getJUDirectoryMenuItems();
		foreach ($menuItems AS $menuItem)
		{
			if (isset($menuItem->query['view']) && $menuItem->query['view'] == $viewName)
			{
				return $menuItem->id;
			}
		}

		$activeItemIdOfJUDirectory = JUDirectoryHelperRoute::getActiveMenuItemIdOfJUDirectory();
		if ($activeItemIdOfJUDirectory)
		{
			return $activeItemIdOfJUDirectory;
		}

		return false;
	}

	
	public static function findItemId($needles = null, $acceptNotFound = false)
	{
		$app  = JFactory::getApplication();
		$menu = $app->getMenu('site');

		if (!empty($needles))
		{
			foreach ($needles AS $view => $ids)
			{
				if (!isset(self::$lookup[$view]))
				{
					$component  = JComponentHelper::getComponent('com_judirectory');
					$attributes = array('component_id');
					$values     = array($component->id);

					$items = $menu->getItems($attributes, $values);

					foreach ($items AS $item)
					{
						if (isset($item->query) && isset($item->query['view']) && $item->query['view'] == $view)
						{
							if (!isset(self::$lookup[$item->query['view']]))
							{
								self::$lookup[$item->query['view']] = array();
							}

							if (isset($item->query['id']))
							{
								if (!isset(self::$lookup[$item->query['view']][$item->query['id']]))
								{
									self::$lookup[$item->query['view']][$item->query['id']] = $item->id;
								}
							}
						}
					}
				}

				foreach ($ids AS $id)
				{
					if (isset(self::$lookup[$view][$id]))
					{
						return self::$lookup[$view][$id];
					}
				}
			}
		}

		if ($acceptNotFound)
		{
			return false;
		}

		
		return null;
	}

	
	public static function getMenuItemIdArrayOfViewCategoryTree()
	{
		$app  = JFactory::getApplication();
		$menu = $app->getMenu('site');

		if (!isset(self::$lookup['tree']))
		{
			$component  = JComponentHelper::getComponent('com_judirectory');
			$attributes = array('component_id');
			$values     = array($component->id);

			$items = $menu->getItems($attributes, $values);

			foreach ($items AS $item)
			{
				if (isset($item->query) && isset($item->query['view']) && $item->query['view'] == 'tree')
				{
					if (!isset(self::$lookup['tree']))
					{
						self::$lookup['tree'] = array();
					}

					if (isset($item->query['id']))
					{
						if (!isset(self::$lookup['tree'][$item->query['id']]))
						{
							self::$lookup['tree'][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if (isset(self::$lookup['tree']))
		{
			return self::$lookup['tree'];
		}
		else

		{
			return false;
		}
	}

	
	public static function getMenuItemIdOfViewCategoryTreeRoot()
	{
		$menuItemIdsOfViewCategoryTree = self::getMenuItemIdArrayOfViewCategoryTree();
		$rootCat                       = JUDirectoryFrontHelperCategory::getRootCategory();
		if (isset($menuItemIdsOfViewCategoryTree[$rootCat->id]))
		{
			return $menuItemIdsOfViewCategoryTree[$rootCat->id];
		}
		else
		{
			return null;
		}
	}

	
	public static function getMenuItemIdArrayOfViewCategoryTreeLevel1()
	{
		$menuItemIdsOfViewCategoryTree = self::getMenuItemIdArrayOfViewCategoryTree();

		$menuItemIdsOfViewCategoryTreeLevel1 = array();
		$categoryObjectListLevel1            = JUDirectoryHelper::getCatsByLevel(1);
		if (!empty($categoryObjectListLevel1))
		{
			foreach ($categoryObjectListLevel1 AS $categoryObjectLevel1)
			{
				if (isset($menuItemIdsOfViewCategoryTree[$categoryObjectLevel1->id]))
				{
					$menuItemIdsOfViewCategoryTreeLevel1[$categoryObjectLevel1->id] = $menuItemIdsOfViewCategoryTree[$categoryObjectLevel1->id];
				}
			}
		}

		return $menuItemIdsOfViewCategoryTreeLevel1;
	}

	
	public static function getMenuItemIdArrayOfViewCategoryTreeLevel0And1()
	{
		$menuItemIdsOfViewCategoryTree = self::getMenuItemIdArrayOfViewCategoryTree();
		$menuItemIds                   = array();

		
		$categoryObjectListLevel1 = JUDirectoryHelper::getCatsByLevel();

		if ($categoryObjectListLevel1)
		{
			foreach ($categoryObjectListLevel1 AS $categoryObjectLevel1)
			{
				if (isset($menuItemIdsOfViewCategoryTree[$categoryObjectLevel1->id]))
				{
					$menuItemIds[$categoryObjectLevel1->id] = $menuItemIdsOfViewCategoryTree[$categoryObjectLevel1->id];
				}
			}

			$categoryRoot = JUDirectoryFrontHelperCategory::getRootCategory();

			if (isset($menuItemIdsOfViewCategoryTree[$categoryRoot->id]))
			{
				$menuItemIds[$categoryRoot->id] = $menuItemIdsOfViewCategoryTree[$categoryRoot->id];
			}
		}

		return $menuItemIds;
	}

	
	public static function getJUDirectoryMenuItems()
	{
		$app       = JFactory::getApplication('site');
		$menus     = $app->getMenu();
		$component = JComponentHelper::getComponent('com_judirectory');
		$menuItems = $menus->getItems('component_id', $component->id);

		return $menuItems;
	}

	public static function findJUDirectoryTreeItemId()
	{
		$menuItemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();

		if ($menuItemIdTreeRoot > 0)
		{
			return $menuItemIdTreeRoot;
		}

		$homeMenuItemId = JUDirectoryHelperRoute::getHomeMenuItemId();

		if ($homeMenuItemId)
		{
			return $homeMenuItemId;
		}

		return false;
	}

	
	public static function getActiveMenuItemIdOfJUDirectory()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		
		$activeMenu = $menus->getActive();

		if ($activeMenu && $activeMenu->component == 'com_judirectory')
		{
			return $activeMenu->id;
		}

		$homeMenuItemId = JUDirectoryHelperRoute::getHomeMenuItemId();

		if ($homeMenuItemId)
		{
			return $homeMenuItemId;
		}

		return false;
	}

	
	public static function getHomeMenuItemId()
	{
		$storeId = md5(__METHOD__);
		if (!isset(self::$cache[$storeId]))
		{
			
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__menu');
			$query->where('home = 1');
			$db->setQuery($query);

			self::$cache[$storeId] = (int) $db->loadResult();
		}

		return self::$cache[$storeId];
	}

	
	public static function getPage($start, $viewName)
	{
		
		JLoader::register('JUDIRModelList', JPATH_SITE . '/components/com_judirectory/helpers/judirmodellist.php');

		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_judirectory/models', 'JUDirectory');

		$model = JModelLegacy::getInstance($viewName, "JUDirectoryModel");
		$limit = $model->getState("list.limit", 5);

		return ($start / $limit) + 1;
	}

	
	public static function getLimit($viewName)
	{
		JLoader::register('JUDIRModelList', JPATH_SITE . '/components/com_judirectory/helpers/judirmodellist.php');
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_judirectory/models', 'JUDirectory');

		$model = JModelLegacy::getInstance($viewName, "JUDirectoryModel");
		$limit = $model->getState("list.limit", 5);

		return $limit;
	}


	public static function parseLayout($layout, &$vars, $params)
	{
		$isLayout = preg_match('/^' . preg_quote(JApplication::stringURLSafe($params->get('sef_layout', 'layout')) . '-') . '/', $layout);
		if ($isLayout)
		{
			$vars['layout'] = substr($layout, strlen(JApplication::stringURLSafe($params->get('sef_layout', 'layout')) . '-'));

			return true;
		}

		return false;
	}

	
	public static function isLayout($viewName, $layoutName)
	{
		$viewPath         = JPath::clean(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_judirectory' . DIRECTORY_SEPARATOR . 'views');
		$specificViewPath = JPath::clean($viewPath . DIRECTORY_SEPARATOR . $viewName);
		$layoutPath       = JPath::clean($specificViewPath . DIRECTORY_SEPARATOR . 'tmpl');
		$layoutFilePath   = JPath::clean($layoutPath . $layoutName . '.xml');
		if (JFile::exists($layoutFilePath))
		{
			return true;
		}

		return false;
	}

	public static function seoLayout(&$query, &$segments, $params)
	{
		if (isset($query['layout']))
		{
			if (strtolower($query['layout']) != 'default')
			{
				$segments[] = JApplication::stringURLSafe($params->get('sef_layout', 'layout')) . ':' . $query['layout'];
			}
			unset($query['layout']);
		}

		return true;
	}

	public static function seoPagination(&$query, $params, &$segments)
	{
		if (isset($query['limitstart']) && $query['limitstart'] == 0)
		{
			unset($query['limitstart']);
		}

		if (isset($query['start']))
		{
			$segments[] = JApplication::stringURLSafe($params->get('sef_page', 'page')) . ':' . JUDirectoryHelperRoute::getPage($query['start'], $query['view']);
			unset($query['start']);
		}

		return true;
	}

	public static function seoFormat(&$query, $params, &$segments)
	{
		if (isset($query['format']))
		{
			$segments[] = JApplication::stringURLSafe($params->get('sef_rss', 'rss'));

			unset($query['format']);
		}

		return true;
	}

	public static function parsePagination(&$vars, $segments, $params)
	{
		$endSegment = end($segments);
		$isPaged    = preg_match('/' . preg_quote(JApplication::stringURLSafe($params->get('sef_page', 'page')) . '-') . '[0-9]*+/', $endSegment);
		if ($isPaged)
		{
			$limit              = JUDirectoryHelperRoute::getLimit($vars['view']);
			$page               = $endSegment;
			$pageNumber         = substr($page, strlen(JApplication::stringURLSafe($params->get('sef_page', 'page')) . '-'));
			$vars['limitstart'] = $limit * ($pageNumber - 1);

			return true;
		}

		return false;
	}


	public static function getCustomListRoute($customlistId, $canonical = false, $layout = '')
	{
		$link = 'index.php?option=com_judirectory&view=customlist&id=' . $customlistId;

		if ($canonical)
		{
			$itemIdTreeRoot = JUDirectoryHelperRoute::getMenuItemIdOfViewCategoryTreeRoot();
			if ($itemIdTreeRoot > 0)
			{
				$itemId = $itemIdTreeRoot;
			}
			else
			{
				$itemId = JUDirectoryHelperRoute::getHomeMenuItemId();
			}
		}
		else
		{
			$needles = array(
				'customlist' => array((int) $customlistId)
			);

			$itemId = JUDirectoryHelperRoute::findItemId($needles);
		}

		if ($layout && $layout != 'default')
		{
			$link .= '&layout=' . $layout;
		}

		if ($itemId)
		{
			$link .= "&Itemid=" . $itemId;
		}

		return $link;
	}

}