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

class JUDirectoryFrontHelperListing
{
	
	protected static $cache = array();

	
	public static function getListing($listingId, $checkLanguage = false, $checkAccess = true, $listingObject = null)
	{
		if (!$listingId)
		{
			return null;
		}

		

		$storeId = md5(__METHOD__ . "::$listingId::" . (int) $checkLanguage . "::" . (int) $checkAccess);
		if (!isset(self::$cache[$storeId]))
		{
			if (empty($listingObject))
			{
				$listingObject = JUDirectoryHelper::getListingById($listingId);
			}

			$nowDate = JFactory::getDate()->toSql();

			if (!is_object($listingObject))
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			if ($listingObject->approved != 1)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			if ($listingObject->published != 1)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			if ($listingObject->publish_up > $nowDate)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			if ($listingObject->publish_down != '0000-00-00 00:00:00' && $listingObject->publish_down < $nowDate)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			if ($checkAccess)
			{
				$user   = JFactory::getUser();
				$levels = $user->getAuthorisedViewLevels();

				if ($user->get('guest'))
				{
					if (!in_array($listingObject->access, $levels))
					{
						self::$cache[$storeId] = false;

						return self::$cache[$storeId];
					}
				}
				else
				{
					if (!in_array($listingObject->access, $levels) && $listingObject->created_by != $user->id)
					{
						self::$cache[$storeId] = false;

						return self::$cache[$storeId];
					}
				}
			}

			if ($checkLanguage)
			{
				
				$app         = JFactory::getApplication();
				$tagLanguage = JFactory::getLanguage()->getTag();
				if ($app->getLanguageFilter())
				{
					$languageArray = array($tagLanguage, '*', '');
					if (!in_array($listingObject->language, $languageArray))
					{
						self::$cache[$storeId] = false;

						return self::$cache[$storeId];
					}
				}
			}

			self::$cache[$storeId] = $listingObject;
		}

		return self::$cache[$storeId];
	}

	
	public static function isListingPublished($listingId)
	{
		$listingObject = JUDirectoryHelper::getListingById($listingId);
		if (!is_object($listingObject))
		{
			return false;
		}
		$catPublished = JUDirectoryFrontHelperPermission::canDoCategory($listingObject->cat_id);
		if (!$catPublished)
		{
			return false;
		}

		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__judirectory_listings');
		
		$query->where('approved = 1');
		
		$query->where('published = 1');
		$query->where('(publish_up = ' . $db->quote($nullDate) . ' OR publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(publish_down = ' . $db->quote($nullDate) . ' OR publish_down >= ' . $db->quote($nowDate) . ')');
		$query->where('id =' . $listingId);
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result)
		{
			return true;
		}

		return false;
	}

	
	public static function getListingLayoutFromCategory($categoryId)
	{
		$path = JUDirectoryHelper::getCategoryPath($categoryId);

		$pathCatToRoot = array_reverse($path);

		foreach ($pathCatToRoot AS $category)
		{
			if ($category->layout_listing == -2)
			{
				$params = JUDirectoryHelper::getParams($categoryId);
				$layout = $params->get('layout_listing', '_:default');

				return $layout;
			}
			elseif ($category->layout_listing == -1)
			{
				
				if ($category->parent_id == 0)
				{
					$params = JUDirectoryHelper::getParams($categoryId);
					$layout = $params->get('layout_listing', '_:default');

					return $layout;
				}
				else
				{
					continue;
				}
			}
			else
			{
				$layout = trim($category->layout_listing);

				return $layout;
			}
		}

		
		return '_:default';
	}

	
	public static function getListingLayout($listingId)
	{
		$storeId = md5(__METHOD__ . "::" . $listingId);
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('layout');
			$query->from('#__judirectory_listings');
			$query->where('id =' . $listingId);
			$db->setQuery($query);
			$layout = $db->loadResult();

			
			if ($layout == -2)
			{
				$params = JUDirectoryHelper::getParams(null, $listingId);
				$layout = $params->get('layout_listing', '_:default');
			}
			
			elseif ($layout == -1)
			{
				$parentId = (int) JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
				
				if ($parentId == 0)
				{
					$params = JUDirectoryHelper::getParams(null, $listingId);
					$layout = $params->get('layout_listing', '_:default');
				}
				
				else
				{
					$layout = JUDirectoryFrontHelperListing::getListingLayoutFromCategory($parentId);
				}
			}
			else
			{
				$layout = trim($layout);
			}

			self::$cache[$storeId] = $layout;
		}

		return self::$cache[$storeId];
	}

	
	public static function getListingViewLayout($layoutUrl, $listingId)
	{
		
		if ($layoutUrl)
		{
			$layout = $layoutUrl;
		}
		else
		{
			$app = JFactory::getApplication();
			
			$activeMenuItem = $app->getMenu()->getActive();
			if (($activeMenuItem) && ($activeMenuItem->component == 'com_judirectory') && ((strpos($activeMenuItem->link, 'view=listing') > 0) && (strpos($activeMenuItem->link, '&id=' . (string) $listingId) > 0)))
			{
				$activeMenuItemId = $activeMenuItem->id;
			}
			else
			{
				$activeMenuItemId = false;
			}

			
			if ($activeMenuItemId)
			{
				$menus = $app->getMenu();
				$menu  = $menus->getItem($activeMenuItemId);
				if (isset($menu->query['layout']))
				{
					$layout = $menu->query['layout'];
				}
				else
				{
					$layout = 'default';
				}
			}
			
			else
			{
				$layout = 'default';

			}
		}

		
		if (empty($layout))
		{
			$layout = 'default';
		}

		return $layout;
	}

	
	public static function checkHotListing($publish_up, $hit_per_day_to_be_hot, $hits)
	{
		$timeNow    = strtotime(JFactory::getDate()->toSql());
		$publish_up = strtotime($publish_up);
		$total_days = ceil(($timeNow - $publish_up) / 86400);

		$hit_per_day = $hits / $total_days;

		if ($hit_per_day >= $hit_per_day_to_be_hot)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	
	public static function getTotalListingsOfUserApprovedByMod($userId)
	{
		if (!$userId)
		{
			return 0;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("COUNT(*)");
		$query->from('#__judirectory_listings');
		$query->where("created_by =" . $userId);
		$query->where("approved = 1");
		$query->where("approved_by != 0");
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	
	public static function getListingDisplayParams($listing_id)
	{
		if (!$listing_id)
		{
			return null;
		}

		$storeId = md5(__METHOD__ . "::$listing_id");
		if (!isset(self::$cache[$storeId]))
		{
			$params                        = JUDirectoryHelper::getParams(null, $listing_id);
			$global_display_params         = $params->get('display_params');
			$global_listing_display_object = isset($global_display_params->listing) ? $global_display_params->listing : array();
			$global_listing_display_params = new JRegistry($global_listing_display_object);

			$listingObj     = JUDirectoryHelper::getListingById($listing_id);
			$listing_params = $listingObj->params;
			if ($listing_params)
			{
				$listing_params         = json_decode($listing_params);
				$listing_display_params = $listing_params->display_params;

				if ($listing_display_params)
				{
					$global_listing_display_params = JUDirectoryFrontHelperField::mergeFieldOptions($global_listing_display_params->toObject(), $listing_display_params);
					unset($listing_display_params->fields);

					foreach ($listing_display_params AS $option => $value)
					{
						if ($value == '-2')
						{
							unset($listing_display_params->$option);
						}
					}

					$global_listing_display_params->loadObject($listing_display_params);
				}
			}

			self::$cache[$storeId] = $global_listing_display_params;
		}

		return self::$cache[$storeId];
	}

	public static function getAddListingLink($categoryId = null, $xhtml = true)
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		
		$params = JUDirectoryHelper::getParams($categoryId);
		
		$itemId       = 0;
		$assignItemId = $params->get('assign_itemid_to_submit_link', 'currentItemid');
		switch (strtolower($assignItemId))
		{
			default:
			case "currentitemid":
				$itemId = $app->input->getInt('Itemid', 0);
				break;

			case "listingsubmitmenuitemid":
				$component = JComponentHelper::getComponent('com_judirectory');
				$menuItems = $menus->getItems('component_id', $component->id);
				
				foreach ($menuItems AS $menuItem)
				{
					if (isset($menuItem->query) && $menuItem->query['view'] == 'form')
					{
						$itemId = $menuItem->id;
						break;
					}
				}
				break;

			case "predefineditemid":
				$predefinedItemId = (int) $params->get('predefined_itemid_for_submit_link', 0);
				if (is_object($menus->getItem($predefinedItemId)))
				{
					$itemId = $predefinedItemId;
				}
				else
				{
					$itemId = $app->input->getInt('Itemid', 0);
				}
				break;
		}

		$submitListingLink = 'index.php?option=com_judirectory&task=form.add';

		if ($categoryId)
		{
			$submitListingLink .= '&cat_id=' . $categoryId;
		}

		if ($itemId)
		{
			$submitListingLink .= '&Itemid=' . $itemId;
		}

		return JRoute::_($submitListingLink, $xhtml);
	}

} 