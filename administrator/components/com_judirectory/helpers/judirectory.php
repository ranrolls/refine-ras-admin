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


class JUDirectoryHelper
{
	
	protected static $cache = array();

	################################< CATEGORY SECTION >################################

	
	public static function getCategoryById($cat_id)
	{
		if (!$cat_id)
		{
			return null;
		}

		$storeId = md5(__METHOD__ . "::$cat_id");
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->SELECT('*');
			$query->FROM('#__judirectory_categories');
			$query->WHERE('id = ' . $cat_id);
			$db->setQuery($query);
			self::$cache[$storeId] = $db->loadObject();
		}

		return self::$cache[$storeId];
	}

	public static function getCategoryPath($catId, $diagnostic = false)
	{
		$storeId = md5(__METHOD__ . "::$catId::" . (int) $diagnostic);

		if (!isset(self::$cache[$storeId]))
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
			$categoryTable         = JTable::getInstance('Category', 'JUDirectoryTable');
			self::$cache[$storeId] = $categoryTable->getPath($catId, $diagnostic);
		}

		return self::$cache[$storeId];
	}

	
	public static function generateCategoryPath($cat_id, $separator = " > ", $link = false, $lastItemLink = false)
	{
		if (!$cat_id)
		{
			return '';
		}

		$categories = self::getCategoryPath($cat_id);
		$totalCats  = count($categories);

		if ($separator == "li")
		{
			$html = '<ul class="breadcrumb">';
			$html .= '<li><i class="icon-location"></i></li>';
			$divider = self::isJoomla3x() ? '' : '<span class="divider">/</span>';
			foreach ($categories AS $i => $category)
			{
				$html .= ($link && ($lastItemLink || (!$lastItemLink && $i != $totalCats - 1))) ? '<li><a href="index.php?option=com_judirectory&view=listcats&cat_id=' . $category->id . '" >' . $category->title . '</a>' . $divider . '</li>' : (($i != $totalCats - 1) ? '<li>' . $category->title . $divider . '</li>' : '<li class="active">' . $category->title . '</li>');
			}
			$html .= '</ul>';

			return $html;
		}
		else
		{
			$path = array();
			foreach ($categories AS $i => $category)
			{
				$path[] = ($link && ($lastItemLink || (!$lastItemLink && $i != $totalCats - 1))) ? "<a href='index.php?option=com_judirectory&view=listcats&cat_id=" . $category->id . "' >" . $category->title . "</a>" : $category->title;
			}

			return implode($separator, $path);
		}
	}

	public static function getCategoryTree($categoryId = 1, $fetchSelf = true, $checkPublish = false)
	{
		$storeId = md5(__METHOD__ . "::$categoryId::" . (int) $fetchSelf . "::" . (int) $checkPublish);
		if (!isset(self::$cache[$storeId]))
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
			$categoryTable = JTable::getInstance('Category', 'JUDirectoryTable');

			$nowDate = JFactory::getDate()->toSql();

			
			$validCategories  = array();
			$validCategoryIds = array();

			if ($categoryTable->load($categoryId))
			{
				$categories = $categoryTable->getTree();
				foreach ($categories AS $key => $category)
				{
					if ($key == 0)
					{
						
						if ($checkPublish && ($category->published != 1 || $nowDate < $category->publish_up || (intval($category->publish_down) != 0 && $nowDate > $category->publish_down)))
						{
							self::$cache[$storeId] = array();

							return self::$cache[$storeId];
						}

						if ($fetchSelf)
						{
							$validCategories[] = $category;
						}
					}
					else
					{
						if (!in_array($category->parent_id, $validCategoryIds))
						{
							unset($categories[$key]);
							continue;
						}

						if ($checkPublish && ($category->published != 1 || $nowDate < $category->publish_up || (intval($category->publish_down) != 0 && $nowDate > $category->publish_down)))
						{
							unset($categories[$key]);
							continue;
						}

						$validCategories[] = $category;
					}

					$validCategoryIds[] = $category->id;
				}
			}

			self::$cache[$storeId] = $validCategories;
		}

		return self::$cache[$storeId];
	}

	
	public static function getCategoryDTree($cat_id = null)
	{
		JLoader::register('JUDirectoryHelperRoute', JPATH_SITE . '/components/com_judirectory/helpers/route.php');

		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::root() . "components/com_judirectory/assets/dtree/css/dtree.css");
		$document->addScript(JUri::root() . "components/com_judirectory/assets/dtree/js/dtree.js");

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title, parent_id, level, config_params');
		$query->from('#__judirectory_categories');
		$query->order('title ASC, id ASC');
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$script     = "<script type=\"text/javascript\">\r\n";
		$iconFolder = JUri::root() . 'components/com_judirectory/assets/dtree/img';
		$script .= "tree_cat = new dTree('tree_cat');\r\n";
		$script .= "tree_cat.icon.root = '$iconFolder/base.gif';\r\n
					tree_cat.icon.folder = '$iconFolder/folder.gif';\r\n
					tree_cat.icon.folderOpen = '$iconFolder/folderopen.gif';\r\n
					tree_cat.icon.node = '$iconFolder/folder.gif';\r\n
					tree_cat.icon.empty = '$iconFolder/empty.gif';\r\n
					tree_cat.icon.line = '$iconFolder/line.gif';\r\n
					tree_cat.icon.join = '$iconFolder/join.gif';\r\n
					tree_cat.icon.joinBottom = '$iconFolder/joinbottom.gif';\r\n
					tree_cat.icon.plus = '$iconFolder/plus.gif';\r\n
					tree_cat.icon.plusBottom = '$iconFolder/plusbottom.gif';\r\n
					tree_cat.icon.minus = '$iconFolder/minus.gif';\r\n
					tree_cat.icon.minusBottom = '$iconFolder/minusbottom.gif';\r\n
					tree_cat.icon.nlPlus = '$iconFolder/nolines_plus.gif';\r\n
					tree_cat.icon.nlMinus = '$iconFolder/nolines_minus.gif';\r\n";

		foreach ($categories AS $category)
		{
			$cat_title = addslashes(htmlspecialchars($category->title, ENT_QUOTES));
			if ($category->level == 1 && $category->config_params)
			{
				$cat_title .= " <i class=\"icon-cog disabled hasTooltip\" title=\"" . JText::_('COM_JUDIRECTORY_OVERRIDE_CONFIG') . "\"></i>";
			}

			if ($category->level == 1 && JUDirectoryHelperRoute::findItemId(array('tree' => array($category->id))))
			{
				$script .= "tree_cat.add($category->id, $category->parent_id, '$cat_title', '" . JUri::Base() . "index.php?option=com_judirectory&view=listcats&cat_id=$category->id', '', '', tree_cat.icon.root);\r\n";
			}
			else
			{
				$script .= "tree_cat.add($category->id, $category->parent_id, '$cat_title', '" . JUri::Base() . "index.php?option=com_judirectory&view=listcats&cat_id=$category->id');\r\n";
			}
		}

		$script .= "tree_cat.config.useCookies=false;\r\n";
		$script .= "tree_cat.config.closeSameLevel=true;\r\n";
		$script .= "document.write(tree_cat);\r\n";
		if ($cat_id)
		{
			$script .= "tree_cat.openTo($cat_id, true);";
		}
		$script .= "</script>";

		return $script;
	}

	
	public static function getCategoriesByListingId($listing_id, $select = 'c.*', $secondaryCat = false)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->SELECT($select);
		$query->FROM('#__judirectory_categories AS c');
		$query->JOIN('', '#__judirectory_listings_xref AS listingxref ON listingxref.cat_id = c.id');
		$query->WHERE('listingxref.listing_id = ' . $listing_id);
		if ($secondaryCat)
		{
			$query->WHERE('listingxref.main = 0');
		}
		$query->ORDER('listingxref.main DESC, listingxref.ordering ASC');
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	
	public static function getCatsByLevel($level = 1, $childCatId = null)
	{
		$storeId = md5(__METHOD__ . "::$level::" . (int) $childCatId);
		if (!isset(self::$cache[$storeId]))
		{
			$db = JFactory::getDbo();
			if ($childCatId > 0)
			{
				$fromCatObj = self::getCategoryById($childCatId);
				if (!empty($fromCatObj))
				{
					$query = $db->getQuery(true);
					$query->select('*');
					$query->from('#__judirectory_categories');
					$query->where('lft <= ' . $fromCatObj->lft);
					$query->where('rgt >= ' . $fromCatObj->rgt);
					$query->where('level = ' . $level);
					$db->setQuery($query);
				}
			}
			else
			{
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from('#__judirectory_categories');
				$query->where('level = ' . $level);
				$db->setQuery($query);
			}

			self::$cache[$storeId] = $db->loadObjectList();
		}

		return self::$cache[$storeId];
	}

	
	public static function getCategoryOptions($catId = 1, $fetchSelf = true, $checkCreatedPermission = false, $checkPublished = false, $ignoredCatId = array(), $startLevel = 0, $separation = '|—')
	{
		$categoryTree = self::getCategoryTree($catId, $fetchSelf, $checkPublished);
		if ($categoryTree)
		{
			$app     = JFactory::getApplication();
			$user    = JFactory::getUser();
			$options = array();

			$ignoredCatIdArr = array();
			if ($ignoredCatId)
			{
				foreach ($ignoredCatId as $cat_id)
				{
					if (!in_array($cat_id, $ignoredCatIdArr))
					{
						$_categoryTree = self::getCategoryTree($cat_id, true);
						foreach ($_categoryTree as $category)
						{
							if (!in_array($category->id, $ignoredCatIdArr))
							{
								$ignoredCatIdArr[] = $category->id;
							}
						}
					}
				}
			}

			foreach ($categoryTree AS $key => $item)
			{
				if ($app->isSite())
				{
					$accessibleCategoryIds = JUDirectoryFrontHelperPermission::getAccessibleCategoryIds();
					if (!is_array($accessibleCategoryIds))
					{
						$accessibleCategoryIds = array();
					}
					if (!in_array($item->id, $accessibleCategoryIds))
					{
						continue;
					}
				}

				if ($ignoredCatIdArr && in_array($item->id, $ignoredCatIdArr))
				{
					continue;
				}

				$disable = false;
				if ($checkCreatedPermission)
				{
					if ($checkCreatedPermission == "category")
					{
						$assetName   = 'com_judirectory.category.' . (int) $item->id;
						$candoCreate = $user->authorise('judir.category.create', $assetName);
						if (!$candoCreate)
						{
							$disable = true;
						}
					}
					elseif ($checkCreatedPermission == "listing")
					{
						$assetName   = 'com_judirectory.category.' . (int) $item->id;
						$candoCreate = $user->authorise('judir.listing.create', $assetName);
						if (!$candoCreate)
						{
							$disable = true;
						}

						if ($item->id == 1 && !$disable)
						{
							$params = JUDirectoryHelper::getParams();
							if (!$params->get('allow_add_listing_to_root', 0))
							{
								$disable = true;
							}
						}
					}
				}

				
				if ($item->published != 1)
				{
					$item->title = "[" . $item->title . "]";
				}

				if ($key == 0)
				{
					$firstLevel = $item->level - $startLevel;
				}

				$level = $item->level - $firstLevel;

				$options[] = JHtml::_('select.option', $item->id, str_repeat($separation, $level) . $item->title, 'value', 'text', $disable);
			}
		}

		return $options;
	}

	################################< LISTING SECTION >################################

	
	public static function getListingById($listing_id, $resetCache = false, $listingObject = null)
	{
		if (!$listing_id)
		{
			return null;
		}

		

		$storeId = md5(__METHOD__ . "::" . $listing_id);
		if (!isset(self::$cache[$storeId]) || $resetCache)
		{
			
			if (!is_object($listingObject))
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->SELECT('listing.*');
				$query->FROM('#__judirectory_listings AS listing');
				$query->JOIN('LEFT', '#__judirectory_listings_xref AS listingxref ON (listing.id = listingxref.listing_id AND listingxref.main = 1)');
				$query->SELECT('c.id AS cat_id');
				$query->JOIN('LEFT', '#__judirectory_categories AS c ON (c.id = listingxref.cat_id)');
				$query->WHERE('listing.id = ' . $listing_id);
				$db->setQuery($query);
				$listingObject = $db->loadObject();
			}

			if ($listingObject && $listingObject->cat_id > 0)
			{
				self::$cache[$storeId] = $listingObject;
			}
			else
			{
				return $listingObject;
			}
		}

		return self::$cache[$storeId];
	}

	
	public static function getListingIdsByCatId($cat_id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->SELECT('listing_id');
		$query->FROM('#__judirectory_listings_xref');
		$query->WHERE('cat_id=' . $cat_id . ' AND main=1');
		$db->setQuery($query);
		$rows = $db->loadColumn();

		return $rows;
	}

	
	public static function getTotalPendingListings($type = '', $listing_id = null)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__judirectory_listings');
		$query->where('approved < 1');

		if (strtolower($type) == 'next')
		{
			$query->where('id > ' . $listing_id);
			$query->order('id ASC');
		}
		elseif (strtolower($type) == 'prev')
		{
			$query->where('id < ' . $listing_id);
			$query->order('id DESC');
		}

		$db->setQuery($query);
		$total = $db->loadResult();

		return $total;
	}

	
	public static function getTempListing($listing_id)
	{
		
		if ($listing_id <= 0)
		{
			return false;
		}

		$storeId = md5(__METHOD__ . "::" . $listing_id);
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__judirectory_listings');
			$query->where('approved = ' . (-$listing_id));
			$db->setQuery($query);
			$listing = $db->loadObject();
			if (is_null($listing))
			{
				$listing = false;
			}

			self::$cache[$storeId] = $listing;
		}

		return self::$cache[$storeId];
	}

	
	public static function getDefaultListingImage($catId = null, $listingId = null)
	{
		$params       = self::getParams($catId, $listingId);
		$default_icon = $params->get('listing_default_image', 'default-listing.png');

		if ($default_icon != -1)
		{
			return JUri::root(true) . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/", true) . "default/" . $default_icon;
		}
		else
		{
			return '';
		}
	}

	public static function getListingImage($image, $getDefault = true)
	{
		if ($image)
		{
			$listingImageUrl = JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/", true);

			return JUri::root(true) . "/" . $listingImageUrl . $image;
		}
		elseif ($getDefault)
		{
			return JUDirectoryHelper::getDefaultListingImage();
		}

		return '';
	}

	
	public static function getDefaultCollectionIcon()
	{
		$params       = self::getParams();
		$default_icon = $params->get('collection_default_icon', 'default-collection.png');

		if ($default_icon != -1)
		{
			return JUri::root(true) . "/" . JUDirectoryFrontHelper::getDirectory("collection_icon_directory", "media/com_judirectory/images/collection/", true) . "default/" . $default_icon;
		}
		else
		{
			return '';
		}
	}

	################################< COMMENT SECTION >################################

	
	public static function getTotalPendingComments($type = '', $id = null)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__judirectory_comments');
		$query->where('approved < 1');
		$query->where('parent_id != 0');
		$query->where('level != 0');

		if (strtolower($type) == 'next')
		{
			$query->where('id > ' . $id);
			$query->order('id ASC');
		}
		elseif (strtolower($type) == 'prev')
		{
			$query->where('id < ' . $id);
			$query->order('id DESC');
		}

		$db->setQuery($query);
		$total = $db->loadResult();

		return $total;
	}

	################################< FIELD GROUP & FIELD SECTION >################################

	
	public static function deleteFieldValuesOfListing($listingId)
	{
		
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("field.*, plg.folder");
		$query->from("#__judirectory_fields AS field");
		$query->join("", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id");
		$query->join("", "#__judirectory_fields_groups AS fg ON fg.id = field.group_id");
		$query->join("", "#__judirectory_categories AS c ON (c.fieldgroup_id = fg.id OR field.group_id = 1)");
		$query->join("", "#__judirectory_listings_xref AS listingxref ON listingxref.cat_id = c.id");
		$query->join("", "#__judirectory_listings AS listing ON (listingxref.listing_id = listing.id AND listingxref.main=1)");
		$query->where("listing.id = $listingId");
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		foreach ($fields AS $field)
		{
			
			$fieldClass = JUDirectoryFrontHelperField::getField($field, $listingId);
			$fieldClass->onDelete();
		}
	}

	
	public static function autoLoadFieldClass($class)
	{
		
		if (class_exists($class))
		{
			return null;
		}

		$pattern = '/^judirectoryfield(.*)$/i';
		preg_match($pattern, strtolower($class), $matches);
		if ($matches)
		{
			$fieldFolderPath = JPATH_SITE . '/components/com_judirectory/fields/';
			
			if ($matches[1])
			{
				
				$path = $fieldFolderPath . $matches[1] . '/' . $matches[1] . '.php';
				if (JFile::exists($path))
				{
					require_once $path;
				}
			}
		}
	}

	
	public static function changeInheritedFieldGroupId($cat_id, $new_fieldgroup_id = null)
	{
		
		if ($new_fieldgroup_id === null)
		{
			$new_fieldgroup_id = self::getCategoryById($cat_id)->fieldgroup_id;
		}

		
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('id, fieldgroup_id');
		$query->from('#__judirectory_categories');
		$query->where('parent_id = ' . $cat_id);
		$query->where('selected_fieldgroup = -1');
		$db->setQuery($query);
		$subcategories = $db->loadObjectList();

		foreach ($subcategories AS $subcategory)
		{
			if ($subcategory->fieldgroup_id != $new_fieldgroup_id)
			{
				$query = $db->getQuery(true);
				$query->update('#__judirectory_categories');
				$query->set('fieldgroup_id = ' . $new_fieldgroup_id);
				$query->where('id = ' . $subcategory->id);
				$db->setQuery($query);
				$db->execute();

				
				$listingIds = self::getListingIdsByCatId($subcategory->id);
				foreach ($listingIds AS $listingId)
				{
					self::deleteFieldValuesOfListing($listingId);
				}

				
				$query = $db->getQuery(true);
				$query->delete('#__judirectory_fields_ordering');
				$query->where('item_id = ' . $subcategory->id);
				$query->where('type = "category"');
				$db->setQuery($query);
				$db->execute();

				
				self::changeInheritedFieldGroupId($subcategory->id, $new_fieldgroup_id);
			}
		}
	}

	
	public static function getFieldGroupIdByListingId($listing_id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('fieldgroup_id');
		$query->from('#__judirectory_categories AS c');
		$query->join('', '#__judirectory_listings_xref AS listingxref ON c.id=listingxref.cat_id');
		$query->where('listingxref.listing_id=' . $listing_id);
		$query->where('listingxref.main = 1');
		$db->setQuery($query);

		return $db->loadResult();
	}

	
	public static function getCatFields()
	{
		$catFields                     = array();
		$catFields['id']               = JText::_('COM_JUDIRECTORY_FIELD_ID');
		$catFields['title']            = JText::_('COM_JUDIRECTORY_FIELD_TITLE');
		$catFields['alias']            = JText::_('COM_JUDIRECTORY_FIELD_ALIAS');
		$catFields['parent_id']        = JText::_('COM_JUDIRECTORY_FIELD_PARENT_CAT');
		$catFields['rel_cats']         = JText::_('COM_JUDIRECTORY_FIELD_REL_CATS');
		$catFields['access']           = JText::_('COM_JUDIRECTORY_FIELD_ACCESS');
		$catFields['lft']              = JText::_('COM_JUDIRECTORY_FIELD_ORDERING');
		$catFields['fieldgroup_id']    = JText::_('COM_JUDIRECTORY_FIELD_FIELD_GROUP_ID');
		$catFields['criteriagroup_id'] = JText::_('COM_JUDIRECTORY_FIELD_CRITERIA_GROUP_ID');
		$catFields['featured']         = JText::_('COM_JUDIRECTORY_FIELD_FEATURED');
		$catFields['published']        = JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED');
		$catFields['show_item']        = JText::_('COM_JUDIRECTORY_FIELD_SHOW_ITEM');
		$catFields['description']      = JText::_('COM_JUDIRECTORY_FIELD_DESCRIPTION');
		$catFields['intro_image']      = JText::_('COM_JUDIRECTORY_FIELD_INTRO_IMAGE');
		$catFields['detail_image']     = JText::_('COM_JUDIRECTORY_FIELD_DETAIL_IMAGE');
		$catFields['publish_up']       = JText::_('COM_JUDIRECTORY_FIELD_PUBLISH_UP');
		$catFields['publish_down']     = JText::_('COM_JUDIRECTORY_FIELD_PUBLISH_DOWN');
		$catFields['created_by']       = JText::_('COM_JUDIRECTORY_FIELD_CREATED_BY');
		$catFields['created']          = JText::_('COM_JUDIRECTORY_FIELD_CREATED');
		$catFields['modified_by']      = JText::_('COM_JUDIRECTORY_FIELD_MODIFIED_BY');
		$catFields['modified']         = JText::_('COM_JUDIRECTORY_FIELD_MODIFIED');
		$catFields['style_id']         = JText::_('COM_JUDIRECTORY_FIELD_TEMPLATE_STYLE');
		$catFields['layout']           = JText::_('COM_JUDIRECTORY_FIELD_LAYOUT');
		$catFields['metatitle']        = JText::_('COM_JUDIRECTORY_FIELD_METATITLE');
		$catFields['metakeyword']      = JText::_('COM_JUDIRECTORY_FIELD_METAKEYWORD');
		$catFields['metadescription']  = JText::_('COM_JUDIRECTORY_FIELD_METADESCRIPTION');
		$catFields['metadata']         = JText::_('COM_JUDIRECTORY_FIELD_METADATA');
		$catFields['total_categories'] = JText::_('COM_JUDIRECTORY_FIELD_TOTAL_CATEGORIES');
		$catFields['total_listings']   = JText::_('COM_JUDIRECTORY_FIELD_TOTAL_LISTINGS');

		return $catFields;
	}

	
	public static function getFieldGroupOptions($createPermission = false, $ignoreCoreFieldGroup = true)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('`id` AS value, `name` AS text, published');
		$query->from('#__judirectory_fields_groups');
		if ($ignoreCoreFieldGroup)
		{
			$query->where('id != 1');
		}
		$query->order('ordering ASC');
		$db->setQuery($query);
		$options     = array();
		$fieldgroups = $db->loadObjectList();
		$user        = JFactory::getUser();

		foreach ($fieldgroups AS $fieldgroup)
		{
			if ($createPermission && !$user->authorise('core.create', 'com_judirectory.fieldgroup.' . $fieldgroup->value))
			{
				continue;
			}

			if ($fieldgroup->published != 1)
			{
				$fieldgroup->text = "[" . $fieldgroup->text . "]";
			}
			$options[] = JHtml::_('select.option', $fieldgroup->value, $fieldgroup->text);
		}

		return $options;
	}

	
	public static function getAdvSearchFields()
	{
		$app      = JFactory::getApplication();
		$db       = JFactory::getDbo();
		$nullDate = $db->quote($db->getNullDate());
		$nowDate  = $db->quote(JFactory::getDate()->toSql());
		$query    = $db->getQuery(true);
		$query->select('plg.folder, field.*, field_group.name AS filed_group_name');
		$query->from('#__judirectory_fields AS field');
		$query->join('', '#__judirectory_plugins AS plg ON field.plugin_id = plg.id');
		$query->join('', '#__judirectory_fields_groups AS field_group ON field.group_id = field_group.id');
		if ($app->isSite())
		{
			$query->where('field.advanced_search = 1');
		}
		$query->where('field_group.published = 1');
		$query->where('field.published = 1');
		$query->where('field.field_name != "cat_id"');
		$query->where('field.publish_up <= ' . $nowDate);
		$query->where('(field.publish_down = ' . $nullDate . ' OR field.publish_down > ' . $nowDate . ')');
		$query->order('field.group_id, field.ordering');
		$db->setQuery($query);

		$fields = $db->loadObjectList();

		if ($fields)
		{
			$fieldGroups = array();
			foreach ($fields AS $field)
			{
				if (!isset($fieldGroups[$field->group_id]))
				{
					$fieldGroups[$field->group_id]         = new stdClass();
					$fieldGroups[$field->group_id]->name   = $field->filed_group_name;
					$fieldGroups[$field->group_id]->id     = $field->group_id;
					$fieldGroups[$field->group_id]->fields = array();
				}

				$fieldGroups[$field->group_id]->fields[] = JUDirectoryFrontHelperField::getField($field);
			}

			return $fieldGroups;
		}

		return null;
	}

	
	public static function getFieldGroupsByCatIds($catIds, $search_sub_categories = false)
	{
		if (!$catIds)
		{
			return null;
		}

		$field_groups = array();
		foreach ($catIds AS $catId)
		{
			if ($search_sub_categories)
			{
				$categoryTree = JUDirectoryHelper::getCategoryTree($catId, true, true);
				foreach ($categoryTree AS $sub_category)
				{
					if ($sub_category->fieldgroup_id > 0)
					{
						$field_groups[] = $sub_category->fieldgroup_id;
					}
				}
			}
			else
			{
				$catObj         = JUDirectoryHelper::getCategoryById($catId);
				$field_groups[] = $catObj->fieldgroup_id ? $catObj->fieldgroup_id : 1;
			}
		}

		$field_groups = array_unique($field_groups);
		if ($field_groups)
		{
			return implode(",", $field_groups);
		}
		else
		{
			return null;
		}
	}

	################################< CRITERIA GROUP & CRITERIA SECTION >################################

	
	public static function changeInheritedCriteriaGroupId($cat_id, $new_criteriagroup_id = null)
	{
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('id, criteriagroup_id');
		$query->from('#__judirectory_categories');
		$query->where('parent_id = ' . $cat_id);
		$query->where('selected_criteriagroup = -1');
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		
		if (is_null($new_criteriagroup_id))
		{
			$new_criteriagroup_id = self::getCategoryById($cat_id)->criteriagroup_id;
		}

		if ($categories)
		{
			foreach ($categories AS $category)
			{
				
				if ($category->criteriagroup_id != $new_criteriagroup_id)
				{
					$query = $db->getQuery(true);
					$query->update('#__judirectory_categories');
					$query->set('criteriagroup_id = ' . $new_criteriagroup_id);
					$query->where('id = ' . $category->id);
					$db->setQuery($query);
					$db->execute();

					
					
					

					
					self::changeInheritedCriteriaGroupId($category->id, $new_criteriagroup_id);
				}
			}
		}
	}

	
	public static function getCriteriaGroupIdByListingId($listing_id)
	{
		if (!$listing_id)
		{
			return null;
		}

		$storeId = md5(__METHOD__ . "::" . $listing_id);
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('c.criteriagroup_id');
			$query->from('#__judirectory_categories AS c');
			$query->join('', '#__judirectory_listings_xref AS listingxref ON listingxref.cat_id = c.id AND listingxref.main = 1');
			$query->where('listingxref.listing_id = ' . $listing_id);
			$db->setQuery($query);
			self::$cache[$storeId] = $db->loadResult();
		}

		return self::$cache[$storeId];
	}

	
	public static function getCriteriaGroupOptions()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('`id` AS value, `name` AS text, published');
		$query->from('#__judirectory_criterias_groups');
		$query->order('id ASC');
		$db->setQuery($query);
		$criteriaGroups = $db->loadObjectList();
		$option         = array();
		foreach ($criteriaGroups AS $criteriaGroup)
		{
			if ($criteriaGroup->published != 1)
			{
				$criteriaGroup->text = '[ ' . $criteriaGroup->text . ' ]';
			}
			$option[] = JHtml::_('select.option', $criteriaGroup->value, $criteriaGroup->text);
		}

		return $option;
	}

	################################< RATING SECTION >################################

	
	public static function rebuildRating($listing_id)
	{
		$db                      = JFactory::getDbo();
		$params                  = self::getParams(null, $listing_id);
		$onlyCalculateLastRating = $params->get('only_calculate_last_rating', 0);

		if ($onlyCalculateLastRating == 1)
		{
			$query = $db->getQuery(true);
			$query->select('r.id, r.listing_id, r.score');
			$query->select('cm.approved AS comment_approved');
			$query->from('#__judirectory_rating AS r');
			$query->join('LEFT', '#__judirectory_comments AS cm ON cm.rating_id = r.id');
			$query->where('r.`created` = (SELECT  MAX(created) FROM `#__judirectory_rating` AS r2 WHERE r2.listing_id = r.listing_id AND r2.user_id = r.user_id AND r.user_id > 0)');
			$query->where('r.listing_id = ' . $listing_id);
			$query->group('r.created, r.listing_id, r.user_id');
		}
		else
		{
			$query = $db->getQuery(true);
			$query->select('r.id, r.listing_id, r.score');
			$query->select('cm.approved AS comment_approved');
			$query->from('#__judirectory_rating AS r');
			$query->join('LEFT', '#__judirectory_comments AS cm ON cm.rating_id = r.id');
			$query->where('r.listing_id = ' . $listing_id);
		}

		$db->setQuery($query);
		$ratings = $db->loadObjectList();

		
		if ($ratings)
		{
			$totalScore = 0;
			$totalVotes = 0;

			if (JFile::exists(JPATH_SITE . '/components/com_judirectory/fields/multirating/multirating.class.php'))
			{
				require_once JPATH_SITE . '/components/com_judirectory/fields/multirating/multirating.class.php';
			}

			foreach ($ratings AS $rating)
			{
				
				if (self::hasMultiRating())
				{
					$ratingScore = JUDirectoryMultiRating::rebuildRating($rating);
				}
				
				else
				{
					$ratingScore = $rating->score;
				}

				
				if ($rating->comment_approved !== 0)
				{
					$totalScore += $ratingScore;
					$totalVotes++;
				}
			}


			
			if ($onlyCalculateLastRating == 1)
			{
				$avgScore = $totalScore / $totalVotes;
				$query    = $db->getQuery(true);
				$query->update('#__judirectory_listings');
				$query->set('rating = ' . $avgScore);
				$query->set('total_votes = ' . $totalVotes);
				$query->where('id = ' . $listing_id);
			}
			else
			{
				$query = $db->getQuery(true);
				$query->update('#__judirectory_listings');
				$query->set('rating = (SELECT AVG(score) FROM #__judirectory_rating WHERE listing_id=' . $listing_id . ')');
				$query->set('total_votes = ' . $totalVotes);
				$query->where('id = ' . $listing_id);
			}
			$db->setQuery($query);
			$db->execute();

			return false;
		}
	}

	
	public static function hasMultiRating()
	{
		JLoader::register('JUDirectoryMultiRating', JPATH_SITE . '/components/com_judirectory/plugins/multirating/multirating.php');
		if (!class_exists('JUDirectoryMultiRating'))
		{
			return false;
		}

		return true;
	}

	
	public static function hasCSVPlugin()
	{
		JLoader::register('JUDirectoryCSV', JPATH_SITE . '/components/com_judirectory/plugins/csv/csv.php');
		if (!class_exists('JUDirectoryCSV'))
		{
			return false;
		}

		return true;
	}
	################################< IMAGE & FILE SECTION >################################

	
	public static function renderImages($image, $output, $type = 'listing_small', $output_url = true, $catId = null, $listingId = null)
	{
		$params = self::getParams($catId, $listingId);

		
		if (preg_match('/^https?:\/\/[^\/]+/i', $image))
		{
			$image = str_replace(JUri::root(), '', $image);
		}

		$timthumb_params        = array();
		$timthumb_params['src'] = $image;
		switch ($type)
		{
			case "listing_small" :
			default :
				$timthumb_params['w']  = $params->get('listing_small_image_width', 100);
				$timthumb_params['h']  = $params->get('listing_small_image_height', 100);
				$timthumb_params['a']  = $params->get('listing_small_image_alignment', 'c');
				$timthumb_params['zc'] = $params->get('listing_small_image_zoomcrop', 1);
				break;
			case "listing_big" :
				$timthumb_params['w']  = $params->get('listing_big_image_width', 600);
				$timthumb_params['h']  = $params->get('listing_big_image_height', 600);
				$timthumb_params['a']  = $params->get('listing_big_image_alignment', 'c');
				$timthumb_params['zc'] = $params->get('listing_big_image_zoomcrop', 3);
				break;
			case "category_intro" :
				$timthumb_params['w']  = $params->get('category_intro_image_width', 200);
				$timthumb_params['h']  = $params->get('category_intro_image_height', 200);
				$timthumb_params['a']  = $params->get('category_intro_image_alignment', 'c');
				$timthumb_params['zc'] = $params->get('category_intro_image_zoomcrop', 1);
				break;
			case "category_detail" :
				$timthumb_params['w']  = $params->get('category_detail_image_width', 200);
				$timthumb_params['h']  = $params->get('category_detail_image_height', 200);
				$timthumb_params['a']  = $params->get('category_detail_image_alignment', 'c');
				$timthumb_params['zc'] = $params->get('category_detail_image_zoomcrop', 1);
				break;
			case "avatar" :
				$timthumb_params['w']  = $params->get('avatar_width', 120);
				$timthumb_params['h']  = $params->get('avatar_height', 120);
				$timthumb_params['a']  = $params->get('avatar_alignment', 'c');
				$timthumb_params['zc'] = $params->get('avatar_zoomcrop', 1);
				break;
			case "listing_image" :
				$timthumb_params['w']  = $params->get('listing_image_width', 100);
				$timthumb_params['h']  = $params->get('listing_image_height', 100);
				$timthumb_params['a']  = $params->get('listing_image_alignment', 'c');
				$timthumb_params['zc'] = $params->get('listing_image_zoomcrop', 1);
				break;
			case "location_image" :
				$timthumb_params['w']  = $params->get('location_image_width', 100);
				$timthumb_params['h']  = $params->get('location_image_height', 120);
				$timthumb_params['a']  = $params->get('location_image_alignment', 'c');
				$timthumb_params['zc'] = $params->get('location_image_zoomcrop', 1);
				break;
			case "collection":
				$timthumb_params['w']  = $params->get('collection_icon_width', 100);
				$timthumb_params['h']  = $params->get('collection_icon_height', 100);
				$timthumb_params['a']  = $params->get('collection_icon_alignment', 'c');
				$timthumb_params['zc'] = $params->get('collection_icon_zoomcrop', 1);
				break;
		}

		$timthumb_params['q'] = $params->get('imagequality', 90);
		if ($params->get('customfilters', '') != '')
		{
			$timthumb_params['f'] = $params->get('customfilters', '');
		}
		else
		{
			$filters = $params->get('filters');
			if (!empty($filters))
			{
				$filters              = implode("|", $filters);
				$timthumb_params['f'] = $filters;
			}
		}
		$timthumb_params['s']      = $params->get('sharpen', 0);
		$timthumb_params['cc']     = trim($params->get('canvascolour', 'FFFFFF'), '#');
		$timthumb_params['ct']     = $params->get('canvastransparency', 1);
		$timthumb_params['output'] = $output;

		$tim    = new jutimthumb($timthumb_params);
		$output = $tim->start();

		

		if ($output_url)
		{
			$output = str_replace(JPATH_SITE, substr(JUri::root(), 0, -1), $output);
		}

		return $output;
	}

	
	public static function parseImageNameByTags($replace, $type = 'listing', $catId = null, $listingId = null)
	{
		$params = self::getParams($catId, $listingId);
		if ($type == 'category')
		{
			$image_filename = "{id}_" . $params->get('category_image_filename_rule', '{category}');
		}
		else
		{
			$image_filename = $params->get('listing_image_filename_rule', '{image_name}');
		}
		$search         = array('{id}', '{category}', '{listing}', '{image_name}');
		$image_filename = str_replace($search, array($replace['id'], $replace['category'], $replace['listing'], $replace['image_name']), $image_filename);

		return self::fileNameFilter($image_filename);
	}

	
	public static function fileNameFilter($fileName)
	{
		
		$fileNameFilterPath = JPATH_ADMINISTRATOR . "/components/com_judirectory/helper/filenamefilter.php";
		if (JFile::exists($fileNameFilterPath))
		{
			require_once $fileNameFilterPath;
			if (class_exists("JUFileNameFilter"))
			{
				
				if (function_exists("fileNameFilter"))
				{
					$fileName = call_user_func("fileNameFilter", $fileName);
				}
			}
		}

		$fileInfo = pathinfo($fileName);
		$fileName = str_replace("-", "_", JFilterOutput::stringURLSafe($fileInfo['filename']));

		$fileName = JFile::makeSafe($fileName);

		
		if (!$fileName)
		{
			$fileName = JFactory::getDate()->format('Y_m_d_H_i_s');
		}

		return isset($fileInfo['extension']) ? $fileName . "." . $fileInfo['extension'] : $fileName;
	}

	
	public static function getMimeType($filePath)
	{
		$mime_type = '';

		if (function_exists('finfo_open'))
		{
			$fhandle   = finfo_open(FILEINFO_MIME);
			$mime_type = finfo_file($fhandle, $filePath);
		}

		if (function_exists('mime_content_type'))
		{
			$mime_type = mime_content_type($filePath);
		}

		if (!$mime_type)
		{
			$imageExtensions = array("jpeg", "pjpeg", "png", "gif", "bmp", "jpg");
			$extension       = JFile::getExt($filePath);

			if (in_array(strtolower($extension), $imageExtensions))
			{
				$imageInfo = getimagesize($filePath);

				$mime_type = $imageInfo['mime'];
			}
		}

		return $mime_type;
	}

	
	public static function formatBytes($n_bytes)
	{
		if ($n_bytes < 1024)
		{
			return $n_bytes . ' B';
		}
		elseif ($n_bytes < 1048576)
		{
			return round($n_bytes / 1024) . ' KB';
		}
		elseif ($n_bytes < 1073741824)
		{
			return round($n_bytes / 1048576, 2) . ' MB';
		}
		elseif ($n_bytes < 1099511627776)
		{
			return round($n_bytes / 1073741824, 2) . ' GB';
		}
		elseif ($n_bytes < 1125899906842624)
		{
			return round($n_bytes / 1099511627776, 2) . ' TB';
		}
		elseif ($n_bytes < 1152921504606846976)
		{
			return round($n_bytes / 1125899906842624, 2) . ' PB';
		}
		elseif ($n_bytes < 1180591620717411303424)
		{
			return round($n_bytes / 1152921504606846976, 2) . ' EB';
		}
		elseif ($n_bytes < 1208925819614629174706176)
		{
			return round($n_bytes / 1180591620717411303424, 2) . ' ZB';
		}
		else
		{
			return round($n_bytes / 1208925819614629174706176, 2) . ' YB';
		}
	}

	
	public static function getPostMaxSize()
	{
		$val  = ini_get('post_max_size');
		$last = strtolower($val[strlen($val) - 1]);
		switch ($last)
		{
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}

	
	public static function getPhysicalPath($path)
	{
		if (empty($path))
		{
			return '';
		}

		
		if (stripos($path, JUri::root()) === 0)
		{
			$path = JPath::clean(str_replace(JUri::root(), JPATH_ROOT . "/", $path));
		}
		else
		{
			if (stripos($path, JPATH_ROOT) === false)
			{
				$path = JPath::clean(JPATH_ROOT . "/" . $path);
			}
		}

		if (JFile::exists($path))
		{
			return $path;
		}

		return '';
	}

	
	public static function downloadFile($file, $fileName, $transport = 'php', $speed = 50, $resume = true, $downloadMultiParts = true, $mimeType = false)
	{
		
		if (ini_get('zlib.output_compression'))
		{
			@ini_set('zlib.output_compression', 'Off');
		}

		
		if (function_exists('apache_setenv'))
		{
			apache_setenv('no-gzip', '1');
		}

		
		

		
		
		
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : null;
		if ($agent && preg_match('#(?:MSIE |Internet Explorer/)(?:[0-9.]+)#', $agent)
			&& (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		)
		{
			header('Pragma: ');
			header('Cache-Control: ');
		}
		else
		{
			header('Pragma: no-store,no-cache');
			header('Cache-Control: no-cache, no-store, must-revalidate, max-age=-1');
			header('Cache-Control: post-check=0, pre-check=0', false);
		}
		header('Expires: Mon, 14 Jul 1789 12:30:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		
		if (is_resource($file) && get_resource_type($file) == "stream")
		{
			$transport = 'php';
		}
		
		elseif (!JFile::exists($file))
		{
			return JText::sprintf("COM_JUDIRECTORY_FILE_NOT_FOUND_X", $fileName);
		}

		
		if ($transport != 'php')
		{
			
			header('Content-Description: File Transfer');
			header('Date: ' . @gmdate("D, j M m Y H:i:s ") . 'GMT');
			
			if ($resume)
			{
				header('Accept-Ranges: bytes');
			}
			
			elseif (isset($_SERVER['HTTP_RANGE']))
			{
				exit;
			}

			if (!$downloadMultiParts)
			{
				
				header('Accept-Ranges: none');
			}

			header('Content-Type: application/force-download');
			
			
			
			
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
		}

		switch ($transport)
		{
			
			case 'apache':
				
				$modules = apache_get_modules();
				if (in_array('mod_xsendfile', $modules))
				{
					header('X-Sendfile: ' . $file);
				}
				break;

			
			case 'ngix':
				$path = preg_replace('/' . preg_quote(JPATH_ROOT, '/') . '/', '', $file, 1);
				header('X-Accel-Redirect: ' . $path);
				break;

			
			case 'lighttpd':
				header('X-LIGHTTPD-send-file: ' . $file); 
				header('X-Sendfile: ' . $file); 
				break;

			
			case 'php':
			default:
				JLoader::register('JUDownload', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judownload.class.php');

				JUDirectoryHelper::obCleanData();

				$download = new JUDownload($file);

				$download->rename($fileName);
				if ($mimeType)
				{
					$download->mime($mimeType);
				}
				if ($resume)
				{
					$download->resume();
				}
				$download->speed($speed);
				$download->start();

				if ($download->error)
				{
					return $download->error;
				}

				unset($download);
				break;
		}

		return true;
	}

	################################< LOG SECTION >################################

	
	public static function deleteLogs($event, $id)
	{
		if (!$id || !$event)
		{
			return false;
		}

		$db = JFactory::getDbo();
		if (is_array($id))
		{
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__judirectory_logs');
			
			$query->where("(event LIKE " . $db->quote("%." . $event) . " OR event LIKE " . $db->quote($event . ".%") . ") AND item_id IN (" . implode(",", $id) . ")");
		}
		else
		{
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__judirectory_logs');
			
			$query->where("(event LIKE " . $db->quote("%." . $event) . " OR event LIKE " . $db->quote($event . ".%") . ") AND item_id = " . $id);
		}

		$db->setQuery($query);
		$logIds = $db->loadColumn();

		if ($logIds)
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
			$logTable = JTable::getInstance("Log", "JUDirectoryTable");
			
			foreach ($logIds AS $logId)
			{
				$logTable->delete($logId);
			}
		}

		return true;
	}


	################################< THEME & LAYOUT SECTION >################################

	
	public static function calculateStyle($styleId, $parentCatId = 1)
	{
		if (!$parentCatId)
		{
			$parentCatId = 1;
		}

		if ($styleId == -2)
		{
			return self::getDefaultStyleId();
		}
		elseif ($styleId == -1)
		{
			do
			{
				$category    = self::getCategoryById($parentCatId);
				$styleId     = $category->style_id;
				$parentCatId = $category->parent_id;
			} while ($styleId == -1 && $parentCatId != 0);

			if ($styleId == -2)
			{
				return self::getDefaultStyleId();
			}
			else
			{
				return $styleId;
			}
		}
		else
		{
			return $styleId;
		}
	}

	
	public static function getDefaultStyleId()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('style.id');
		$query->from('#__judirectory_template_styles AS style');
		$query->join('', '#__judirectory_templates AS t ON t.id = style.template_id');
		$query->join('', '#__judirectory_plugins AS plg ON plg.id = t.plugin_id');
		$query->where('style.home = 1');
		$db->setQuery($query);
		$result = $db->loadObject();
		if ($result)
		{
			return $result->id;
		}
		else
		{
			return 0;
		}
	}

	################################< PERMISSION SECTION >################################

	
	public static function getActions($component = 'com_judirectory', $section = '', $id = 0)
	{
		if (!$component)
		{
			$component = 'com_judirectory';
		}

		$user   = JFactory::getUser();
		$result = new JObject;

		$path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';

		switch ($section)
		{
			case 'component':
				$actionsComponent    = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
				$actionsCategory     = JAccess::getActionsFromFile($path, "/access/section[@name='component_category']/");
				$actionsListing      = JAccess::getActionsFromFile($path, "/access/section[@name='component_listing']/");
				$actionsComment      = JAccess::getActionsFromFile($path, "/access/section[@name='component_comment']/");
				$actionsSingleRating = JAccess::getActionsFromFile($path, "/access/section[@name='component_single_rating']/");
				$actionsFieldValue   = JAccess::getActionsFromFile($path, "/access/section[@name='component_field_value']/");
				$actionsModerator    = JAccess::getActionsFromFile($path, "/access/section[@name='component_moderator']/");
				$actionsCriteria     = JAccess::getActionsFromFile($path, "/access/section[@name='component_criteria']/");
				$actions             = array_merge($actionsComponent, $actionsCategory, $actionsListing, $actionsComment,
					$actionsSingleRating, $actionsFieldValue, $actionsModerator, $actionsCriteria);
				break;
			case 'category':
			case 'listing':
				$actionsComponent    = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
				$actionsCategory     = JAccess::getActionsFromFile($path, "/access/section[@name='component_category']/");
				$actionsListing      = JAccess::getActionsFromFile($path, "/access/section[@name='component_listing']/");
				$actionsComment      = JAccess::getActionsFromFile($path, "/access/section[@name='component_comment']/");
				$actionsSingleRating = JAccess::getActionsFromFile($path, "/access/section[@name='component_single_rating']/");
				$actions             = array_merge($actionsComponent, $actionsCategory, $actionsListing, $actionsComment, $actionsSingleRating);
				break;
			case 'fieldgroup':
			case 'field':
				$actionsComponent  = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
				$actionsFieldValue = JAccess::getActionsFromFile($path, "/access/section[@name='component_field_value']/");
				$actions           = array_merge($actionsComponent, $actionsFieldValue);
				break;
			case 'moderator':
				$actionsComponent = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
				$actionsModerator = JAccess::getActionsFromFile($path, "/access/section[@name='component_moderator']/");
				$actions          = array_merge($actionsComponent, $actionsModerator);
				break;
			case 'criteriagroup':
				$actionsComponent = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
				$actionsCriteria  = JAccess::getActionsFromFile($path, "/access/section[@name='component_criteria']/");
				$actions          = array_merge($actionsComponent, $actionsCriteria);
				break;
			default:
				$actionsComponent    = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
				$actionsCategory     = JAccess::getActionsFromFile($path, "/access/section[@name='component_category']/");
				$actionsListing      = JAccess::getActionsFromFile($path, "/access/section[@name='component_listing']/");
				$actionsComment      = JAccess::getActionsFromFile($path, "/access/section[@name='component_comment']/");
				$actionsSingleRating = JAccess::getActionsFromFile($path, "/access/section[@name='component_single_rating']/");
				$actionsFieldValue   = JAccess::getActionsFromFile($path, "/access/section[@name='component_field_value']/");
				$actionsModerator    = JAccess::getActionsFromFile($path, "/access/section[@name='component_moderator']/");
				$actionsCriteria     = JAccess::getActionsFromFile($path, "/access/section[@name='component_criteria']/");
				$actions             = array_merge($actionsComponent, $actionsCategory, $actionsListing, $actionsComment,
					$actionsSingleRating, $actionsFieldValue, $actionsModerator, $actionsCriteria);
		}

		if ($section && $id)
		{
			$assetName = $component . '.' . $section . '.' . (int) $id;
		}
		else
		{
			$assetName = $component;
		}

		foreach ($actions AS $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}

	
	public static function checkGroupPermission($task_str = '', $view_str = '')
	{
		return true;
	}

	################################< COLLECTION SECTION >################################

	
	public static function deleteCollectionIcon($collectionId)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$collectionTable = JTable::getInstance("Collection", "JUDirectoryTable");
		$collectionTable->load($collectionId);

		$errors[] = array();
		if ($collectionTable->icon)
		{
			$collection_icon_path = JPATH_SITE . "/" . JUDirectoryFrontHelper::getDirectory('collection_icon_directory', "media/com_judirectory/images/collection/");
			$originalIcon         = $collection_icon_path . "original/" . $collectionTable->icon;
			if (JFile::exists($originalIcon))
			{
				if (!JFile::delete($originalIcon))
				{

					return false;
				}
			}

			$resizeIcon = $collection_icon_path . $collectionTable->icon;
			if (JFile::exists($resizeIcon))
			{
				if (!JFile::delete($resizeIcon))
				{
					return false;
				}
			}
		}

		return true;
	}

	################################< MENU SECTION >################################

	
	protected static function addSubmenu($submenu)
	{
		
		if (!self::isJoomla3x())
		{
			JSubMenuHelper::addEntry(JText::_('COM_JUDIRECTORY_SUBMENU_DASHBOARD'), 'index.php?option=com_judirectory&view=dashboard', $submenu == 'dashboard');
		}
	}

	
	protected static function addPathMenu($item, $path = '')
	{
		$item->addAttribute('path', $path ? $path . '.' . $item['name'] : $item['name']);
		if (strlen(trim((string) $item)) == 0)
		{
			foreach ($item->children() AS $child)
			{
				self::addPathMenu($child, $item['path']);
			}
		}
	}

	
	protected static function showMenuItem($item)
	{
		if (strpos($item['name'], 'criteria') !== false && !self::hasMultiRating())
		{
			return 0;
		}

		if (strpos($item['name'], 'csv') !== false && !self::hasCSVPlugin())
		{
			return 0;
		}

		if ($item['proversion'] == "true" && !JUDIRPROVERSION)
		{
			return 0;
		}

		$task = $view = $item['name'];
		if (strpos($item['name'], ".") !== false)
		{
			$view = "";
		}
		else
		{
			$task = "";
		}

		if (!self::checkGroupPermission($task, $view))
		{
			$showItemStatus = 0;
			$children       = $item->children();
			if (count($children))
			{
				foreach ($children AS $child)
				{
					if (self::showMenuItem($child) != 0)
					{
						$showItemStatus = 2;
						break;
					}
				}
			}
		}
		else
		{
			$showItemStatus = 1;
		}

		return $showItemStatus;
	}

	
	protected static function getMenuItems($item, $activePath)
	{
		$html     = '';
		$children = $item->children();

		if (self::showMenuItem($item) == 2)
		{
			$item['link'] = '#';
		}
		elseif (self::showMenuItem($item) == 0)
		{
			return $html;
		}

		$icon        = $item['icon'] ? $item['icon'] . ' ' : '';
		$activeClass = in_array($item['name'], $activePath) ? 'active' : '';
		if ($item->getName() == 'divider')
		{
			$html .= '<li class="divider"></li>';
		}
		elseif ($item->getName() == 'header')
		{
			$html .= '<li class="nav-header">' . $icon . ($item['label'] ? JText::_($item['label']) : $item['name']) . '</li>';
		}
		else
		{
			if (count($children) > 0)
			{
				$child_html = '';
				foreach ($children AS $child)
				{
					$child_html .= self::getMenuItems($child, $activePath);
				}

				$html .= '<li class="dropdown ' . $activeClass . '">';
				$html .= '<a href="' . $item['link'] . '" class="dropdown-toggle" data-toggle="dropdown">' . $icon . ($item['label'] ? JText::_($item['label']) : $item['name']) . ($child_html ? '<b class="caret"></b>' : '') . '</a>';
				if ($child_html)
				{
					$html .= '<ul class="dropdown-menu">';
					$html .= $child_html;
					$html .= '</ul>';
				}
				$html .= '</li>';
			}
			else
			{
				$html .= '<li class="' . $activeClass . '"><a href="' . $item['link'] . '">' . $icon . ($item['label'] ? JText::_($item['label']) : $item['name']) . '</a></li>';
			}
		}

		return $html;
	}

	
	public static function getMenu($menuName)
	{
		
		$app = JFactory::getApplication();
		if ($app->input->get('tmpl', '') == 'component')
		{
			return '';
		}

		$menu_path = JPATH_ADMINISTRATOR . "/" . 'components/com_judirectory/helpers/menu.xml';
		$menu_xml  = JFactory::getXML($menu_path, true);
		$html      = '';
		if (!$menu_xml)
		{
			return $html;
		}

		foreach ($menu_xml->children() AS $child)
		{
			self::addPathMenu($child);
		}

		$activePath = array();
		$activeMenu = $menu_xml->xpath('//item[@name="' . $menuName . '"]');
		if (isset($activeMenu[0]) && $activeMenu[0])
		{
			$activePath = $activeMenu[0]['path'];
			if ($activePath)
			{
				$activePath = explode(".", $activePath);
			}
		}
		$html .= '<div class="navbar" id="jumenu">';
		$html .= '<div class="navbar-inner">';
		$html .= '<div class="container">';
		$html .= '<ul class="nav">';
		foreach ($menu_xml->children() AS $child)
		{
			$html .= self::getMenuItems($child, $activePath);
		}
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		self::addSubmenu('');
		$document = JFactory::getDocument();

		if (!self::isJoomla3x())
		{

			$script = "jQuery(document).ready(function($){
				var menu = $('#jumenu').clone(),
					jubootstrap = $('<div class=\"jubootstrap\" />');
					jubootstrap.html(menu);
					$('#element-box #jumenu').remove();
					$('#submenu-box').html(jubootstrap);
			});";
			$document->addScriptDeclaration($script);
		}

		$script = "jQuery(document).ready(function($){
						$('#jumenu .dropdown-toggle').dropdownHover();
					});";
		$document->addScriptDeclaration($script);

		return $html;
	}

	################################< OTHER SECTION >################################

	
	public static function getParams($catId = null, $listingId = null)
	{
		// If set listingId but don't set catId -> get catId by listingId
		if (!$catId && $listingId)
		{
			$listingObj = self::getListingById($listingId);
			if ($listingObj)
			{
				$catId = $listingObj->cat_id;
			}
		}

		// Only override if cat existed, override by params of top level cat
		// Find the top level category, assign to $catId if top level cat is found
		if ($catId)
		{
			$path = self::getCategoryPath($catId);

			$rootCat = $path[0];
		}
		else
		{
			$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
		}

		$catIdToGetParams = $rootCat->id;

		// Cache by catId
		$storeId = md5(__METHOD__ . "::$catIdToGetParams");
		// Set params by top level catId(or root) if it has not already set
		if (!isset(self::$cache[$storeId]))
		{
			// Get global config params(of root cat) by default
			$registry = new JRegistry;
			$registry->loadString($rootCat->config_params);

			// Override params from active menu if is a menu of component(Use merge to ignore empty string and null param value)
			$app        = JFactory::getApplication();
			$activeMenu = $app->getMenu()->getActive();
			if ($activeMenu && $activeMenu->component == 'com_judirectory')
			{
				$registry->merge($activeMenu->params);
			}

			self::$cache[$storeId] = $registry;
		}

		return self::$cache[$storeId];
	}

	
	public static function obCleanData($error_reporting = false)
	{
		
		if (!$error_reporting)
		{
			error_reporting(0);
		}

		$obLevel = ob_get_level();
		if ($obLevel)
		{
			while ($obLevel > 0)
			{
				ob_end_clean();
				$obLevel--;
			}
		}
		else
		{
			ob_clean();
		}

		return true;
	}

	
	public static function isJoomla3x()
	{
		return version_compare(JVERSION, '3.0', 'ge');
	}

	
	public static function generateRandomString($length = 10)
	{
		$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++)
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}

		return $randomString;
	}

	
	public static function canEditJUDirectoryPluginParams($rule, $index)
	{
		JPluginHelper::importPlugin('judirectory', $rule);
		$dispatcher = JDispatcher::getInstance();
		$states     = $dispatcher->trigger('canEdit', array());
		if (in_array(true, $states))
		{
			return true;
		}

		return false;
	}

	public static function convertCsvCellToUtf8($str)
	{
		return mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str, 'UTF-8, ISO-8859-1', true));
	}

	
	public static function getCSVData($csvPath, $delimiter = ',', $enclosure = '"', $mode = 'r+', $offset = 0, $length = null, $includeFirstRow = true)
	{
		$app = JFactory::getApplication();
		
		if (!JFile::exists($csvPath))
		{
			$app->enqueueMessage("COM_JUDIRECTORY_CSV_FILE_NOT_FOUND", 'error');

			return false;
		}

		$data = array();

		$handle = fopen($csvPath, $mode);

		try
		{
			if (!$handle)
			{
				$app->enqueueMessage(JText::sprintf('COM_JUDIRECTORY_UNABLE_TO_OPEN_FILE', $csvPath), 'error');

				return false;
			}

			
			if (!$includeFirstRow)
			{
				$offset += 1;
			}

			$count = 0;

			while (!feof($handle))
			{
				$row = fgetcsv($handle, 0, $delimiter, $enclosure);

				
				
				if ($count == 0 && is_array($row))
				{
					$row[0] = str_replace(chr(239) . chr(187) . chr(191), '', $row[0]);
					$row[0] = trim($row[0], '"\'');
				}

				if (is_array($row) && $count >= $offset)
				{
					
					$row = array_map("JUDirectoryHelper::convertCsvCellToUtf8", $row);

					$data[] = $row;
				}

				$count++;

				
				if (!is_null($length) && ($count - $offset) == $length)
				{
					break;
				}

			}

			fclose($handle);
		}
		catch (Exception $e)
		{
			$app->enqueueMessage($e->getMessage(), 'error');

			fclose($handle);

			return false;
		}

		return $data;
	}


	
	public static function formValidation()
	{
		
		$storeId = md5(__METHOD__);
		if (isset(self::$cache[$storeId]) && self::$cache[$storeId])
		{
			return;
		}

		if (self::isJoomla3x())
		{
			JHtml::_('behavior.formvalidation');
		}
		
		else
		{
			JText::script('COM_JUDIRECTORY_FIELD_INVALID');
			JHtml::_('behavior.framework');
			$document = JFactory::getDocument();
			$document->addScript(JUri::root() . "administrator/components/com_judirectory/assets/js/validate.js");
		}

		self::$cache[$storeId] = true;
	}

	
	public static function appendXML(SimpleXMLElement $source, SimpleXMLElement $append, $globalConfig = false, $displayParams = false)
	{
		if ($append)
		{
			$attributes = $append->attributes();
			if ($globalConfig)
			{
				if ((isset($attributes['override']) && $attributes['override'] != 'true' && $attributes['override'] != 1) &&
					(in_array($append->getName(), array('field', 'fields', 'fieldset')))
				)
				{
					return false;
				}
			}

			if ($displayParams && $attributes['type'] == 'list')
			{
				$globalOption = $append->addChild('option', 'COM_JUDIRECTORY_USE_GLOBAL');
				$globalOption->addAttribute('value', '-2');
			}

			if (strlen(trim((string) $append)) == 0)
			{
				$xml = $source->addChild($append->getName());
				foreach ($append->children() AS $child)
				{
					self::appendXML($xml, $child, $globalConfig, $displayParams);
				}
			}
			else
			{
				$xml = $source->addChild($append->getName(), (string) $append);
			}

			foreach ($append->attributes() AS $n => $v)
			{
				if ($displayParams && $n == 'fieldset')
				{
					$xml->addAttribute('fieldset', 'params');
				}
				elseif ($displayParams && $n == 'default')
				{
					$xml->addAttribute($n, '-2');
				}
				else
				{
					$xml->addAttribute($n, $v);
				}

			}
		}
	}

	
	public static function emailLinkRouter($url, $xhtml = true, $ssl = null)
	{
		
		$app    = JFactory::getApplication('site');
		$router = $app->getRouter();

		
		if (!$router)
		{
			return null;
		}

		if ((strpos($url, '&') !== 0) && (strpos($url, 'index.php') !== 0))
		{
			return $url;
		}

		
		$uri = $router->build($url);

		$url = $uri->toString(array('path', 'query', 'fragment'));

		
		$url = preg_replace('/\s/u', '%20', $url);

		
		if ((int) $ssl)
		{
			$uri = JUri::getInstance();

			
			static $prefix;
			if (!$prefix)
			{
				$prefix = $uri->toString(array('host', 'port'));
			}

			
			$scheme = ((int) $ssl === 1) ? 'https' : 'http';

			
			if (!preg_match('#^/#', $url))
			{
				$url = '/' . $url;
			}

			
			$url = $scheme . '://' . $prefix . $url;
		}

		if ($xhtml)
		{
			$url = htmlspecialchars($url);
		}

		
		$url = str_replace('/administrator', '', $url);

		return $url;
	}

	
	public static function addCategory($listing_id, $cat_id, $main, $ordering = 1)
	{
		if (!$listing_id || !$cat_id)
		{
			return false;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert('#__judirectory_listings_xref');
		$query->set('listing_id = ' . $listing_id . ', cat_id =' . $cat_id . ', main = ' . $main . ', ordering = ' . $ordering);
		$db->setQuery($query);

		return $db->execute();
	}

	public static function generateImageNameByListing($listing_id, $file_name)
	{
		if (!$listing_id || !$file_name)
		{
			return "";
		}
		$dir_listing_ori = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/") . $listing_id . "/";
		$info            = pathinfo($file_name);
		$listing         = JUDirectoryHelper::getListingById($listing_id);
		$replace         = array('id' => $listing->id, 'category' => '', 'listing' => $listing->title, 'image_name' => $info['filename']);
		$base_file_name  = JUDirectoryHelper::parseImageNameByTags($replace, 'listing', null, $listing->id) . "." . $info['extension'];
		$img_file_name   = $base_file_name;
		$img_path_ori    = $dir_listing_ori . $img_file_name;
		while (JFile::exists($img_path_ori))
		{
			$img_file_name = JUDirectoryHelper::generateRandomString(3) . "-" . $base_file_name;
			$img_path_ori  = $dir_listing_ori . $img_file_name;
		}

		return $img_file_name;
	}

	
	public static function getPluginOptions($type = null, $core = null, $default = null)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS value, title AS text');
		$query->from('#__judirectory_plugins');
		if ($type)
		{
			$query->where('`type` = "' . $type . '"');
		}
		if (!is_null($core))
		{
			$query->where('`core` = "' . (int) $core . '"');
		}
		if (!is_null($default))
		{
			$query->where('`default` = "' . (int) $default . '"');
		}

		if (!JUDIRPROVERSION)
		{
			$query->where('folder != "datetime"');
			$query->where('folder != "files"');
			$query->where('folder != "images"');
		}

		$query->order('id ASC');

		$db->setQuery($query);
		$plugins = $db->loadObjectlist();

		$options = array();
		foreach ($plugins AS $plugin)
		{

			$options[] = JHtml::_('select.option', $plugin->value, $plugin->text);
		}

		return $options;
	}


	
	public static function getListingSubmitType($listingId)
	{
		
		if ($listingId == 0)
		{
			return 'submit';
		}

		$listingObject = JUDirectoryHelper::getListingById($listingId);
		
		if ($listingObject->approved == 0)
		{
			return 'submit';
		}
		
		else
		{
			return 'edit';
		}
	}

	
	public static function detectFieldsForCSVColumns($csvColumns, $importFor = 'listing')
	{
		$db = JFactory::getDbo();

		$mappedColumns = array();

		switch ($importFor)
		{
			case 'image':
				
				$query = "SHOW COLUMNS ";
				$query .= ' FROM ' . $db->quoteName('#__judirectory_images');

				$db->setQuery($query);
				$columns = $db->loadColumn();

				foreach ($csvColumns AS $csvColumn)
				{
					
					$mappedColumns[$csvColumn] = 'ignore';

					foreach ($columns AS $column)
					{
						if (strcmp(strtolower($csvColumn), $column) == 0)
						{
							$mappedColumns[$csvColumn] = $column;
							break;
						}
					}
				}

				break;

			case 'listing':
				
		}

		return $mappedColumns;
	}

	
	public static function getTemplateOptions()
	{
		
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->select('tpl.id AS value,plg.title AS text');
		$query->from('#__judirectory_plugins AS plg');
		$query->join('', '#__judirectory_templates AS tpl ON tpl.plugin_id = plg.id');
		$query->where('plg.type =' . $db->quote('template'));
		$query->order('tpl.lft ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		return $options;
	}

	
	public static function canUploadTemplateFile($file, $err = '')
	{
		$params = JUDirectoryHelper::getParams();

		if (empty($file['name']))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_JUDIRECTORY_ERROR_UPLOAD_INPUT'), 'error');

			return false;
		}

		
		$executable       = array(
			'exe', 'phtml', 'java', 'perl', 'py', 'asp', 'dll', 'go', 'jar',
			'ade', 'adp', 'bat', 'chm', 'cmd', 'com', 'cpl', 'hta', 'ins', 'isp',
			'jse', 'lib', 'mde', 'msc', 'msp', 'mst', 'pif', 'scr', 'sct', 'shb',
			'sys', 'vb', 'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh'
		);
		$explodedFileName = explode('.', $file['name']);

		if (count($explodedFileName > 2))
		{
			foreach ($executable AS $extensionName)
			{
				if (in_array($extensionName, $explodedFileName))
				{
					$app = JFactory::getApplication();
					$app->enqueueMessage(JText::_('COM_JUDIRECTORY_ERROR_EXECUTABLE'), 'error');

					return false;
				}
			}
		}

		jimport('joomla.filesystem.file');

		if ($file['name'] !== JFile::makeSafe($file['name']) || preg_match('/\s/', JFile::makeSafe($file['name'])))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_JUDIRECTORY_ERROR_WARNFILENAME'), 'error');

			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		$imageTypes   = explode(',', $params->get('template_image_formats', 'gif,bmp,jpg,jpeg,png'));
		$sourceTypes  = explode(',', $params->get('template_source_formats', 'txt,less,ini,xml,js,php,css'));
		$fontTypes    = explode(',', $params->get('template_font_formats', 'woff,ttf,otf'));
		$archiveTypes = explode(',', $params->get('template_compressed_formats', 'zip'));

		$allowable = array_merge($imageTypes, $sourceTypes, $fontTypes, $archiveTypes);

		if ($format == '' || $format == false || (!in_array($format, $allowable)))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_JUDIRECTORY_ERROR_WARNFILETYPE'), 'error');

			return false;
		}

		if (in_array($format, $archiveTypes))
		{
			
			$zip = new ZipArchive;

			if ($zip->open($file['tmp_name']) === true)
			{
				for ($i = 0; $i < $zip->numFiles; $i++)
				{
					$entry     = $zip->getNameIndex($i);
					$endString = substr($entry, -1);

					if ($endString != DIRECTORY_SEPARATOR)
					{
						$explodeArray = explode('.', $entry);
						$ext          = end($explodeArray);

						if (!in_array($ext, $allowable))
						{
							$app = JFactory::getApplication();
							$app->enqueueMessage(JText::_('COM_JUDIRECTORY_FILE_UNSUPPORTED_ARCHIVE'), 'error');

							return false;
						}
					}
				}
			}
			else
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('COM_JUDIRECTORY_FILE_ARCHIVE_OPEN_FAIL'), 'error');

				return false;
			}
		}

		
		$maxSize = (int) ($params->get('template_upload_limit', 2) * 1024 * 1024);

		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_JUDIRECTORY_ERROR_WARNFILETOOLARGE'), 'error');

			return false;
		}

		$xss_check = file_get_contents($file['tmp_name'], false, null, -1, 256);
		$html_tags = array(
			'abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote',
			'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div',
			'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html',
			'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing',
			'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option',
			'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike',
			'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml',
			'xmp', '!DOCTYPE', '!--'
		);

		foreach ($html_tags AS $tag)
		{
			
			if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>'))
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('COM_JUDIRECTORY_ERROR_WARNIEXSS'), 'error');

				return false;
			}
		}

		return true;
	}

	public static function getComVersion($comName = true, $comVersion = true)
	{
		$app    = JFactory::getApplication();
		$option = $app->input->get('option', '');
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('manifest_cache')
			->from('#__extensions')
			->where('element = ' . $db->quote($option));
		$db->setQuery($query);
		$result   = $db->loadResult();
		$manifest = new JRegistry($result);
		$version  = array();
		if ($comName)
		{
			$name = $manifest->get('name');
			if (!JUDIRPROVERSION)
			{
				$name .= ' Lite';
			}
			$version[] = $name;
		}

		if ($comVersion)
		{
			$version[] = 'Version ' . $manifest->get('version');
		}

		return implode(" - ", $version);
	}

	
	public static function getAddressOptions($addressId = 1, $getSelf = true, $checkPublished = false, $ignoredAddressId = array(), $startLevel = 0, $separation = '|—')
	{
		$options = array();

		$addressTree = self::getAddressTree($addressId, $getSelf, $checkPublished);
		if ($addressTree)
		{
			$ignoredAddressIdArr = array();
			if ($ignoredAddressId)
			{
				foreach ($ignoredAddressId as $address_id)
				{
					if (!in_array($address_id, $ignoredAddressIdArr))
					{
						$_addressTree = self::getAddressTree($address_id, true);
						foreach ($_addressTree as $address)
						{
							if (!in_array($address->id, $ignoredAddressIdArr))
							{
								$ignoredAddressIdArr[] = $address->id;
							}
						}
					}
				}
			}

			foreach ($addressTree as $key => $item)
			{
				if ($ignoredAddressIdArr && in_array($item->id, $ignoredAddressIdArr))
				{
					continue;
				}

				
				if ($item->published != 1)
				{
					$item->title = "[" . $item->title . "]";
				}

				if (!isset($firstLevel))
				{
					$firstLevel = $item->level - $startLevel;
				}

				$level = $item->level - $firstLevel;

				$options[] = JHtml::_('select.option', $item->id, str_repeat($separation, $level) . $item->title);
			}
		}

		return $options;
	}

	public static function getAddressTree($addressId, $fetchSelf = false, $checkPublish = false)
	{
		$storeId = md5(__METHOD__ . "::$addressId::" . (int) $fetchSelf . "::" . (int) $checkPublish);
		if (!isset(self::$cache[$storeId]))
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
			$addressTable = JTable::getInstance('Address', 'JUDirectoryTable');

			
			$validAddresses  = array();
			$validAddressIds = array();

			if ($addressTable->load($addressId))
			{
				$addresses = $addressTable->getTree();
				foreach ($addresses AS $key => $address)
				{
					if ($key == 0)
					{
						
						if ($checkPublish && $address->published != 1)
						{
							self::$cache[$storeId] = array();

							return self::$cache[$storeId];
						}

						if ($fetchSelf)
						{
							$validAddresses[] = $address;
						}
					}
					else
					{
						if (!in_array($address->parent_id, $validAddressIds))
						{
							unset($addresses[$key]);
							continue;
						}

						if ($checkPublish && $address->published != 1)
						{
							unset($addresses[$key]);
							continue;
						}

						$validAddresses[] = $address;
					}

					$validAddressIds[] = $address->id;
				}
			}

			self::$cache[$storeId] = $validAddresses;
		}

		return self::$cache[$storeId];
	}

	public static function getAddressOptionsByParentId($parentId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title, level, published')
			->from('#__judirectory_addresses')
			->where('parent_id = ' . (int) $parentId);
		$db->setQuery($query);
		$addresses = $db->loadObjectList();
		$options   = array();
		foreach ($addresses as $address)
		{
			
			if ($address->published != 1)
			{
				$address->title = "[" . $address->title . "]";
			}

			$options[] = JHtml::_('select.option', $address->id, $address->title);
		}

		if ($options)
		{
			array_unshift($options, JHtml::_('select.option', "1", "Select"));
		}

		return $options;
	}

	public static function getAddressPath($id)
	{
		include_once JPATH_ADMINISTRATOR . '/components/com_judirectory/tables/address.php';
		$db           = JFactory::getDbo();
		$addressTable = new JUDirectoryTableAddress($db);
		$html         = '';
		if ($addressTable->load($id))
		{
			$addressPath  = $addressTable->getPath();
			$optionsArray = $selectedIds = array();
			foreach ($addressPath as $address)
			{
				$selectedIds[] = $address->id;
				$options       = JUDirectoryHelper::getAddressOptionsByParentId($address->id);
				if ($options)
				{
					$optionsArray[] = JUDirectoryHelper::getAddressOptionsByParentId($address->id);
				}
			}

			foreach ($optionsArray as $key => $options)
			{
				$html .= JHtml::_('select.genericlist', $options, 'address_path', 'class="nochosen address-path"', 'value', 'text', $selectedIds, '');
			}
		}

		return $html;
	}


	
	public static function getCustomListById($customListId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_custom_lists');
		$query->where('id = ' . (int) $customListId);
		$db->setQuery($query);

		return $db->loadObject();
	}

	
	public static function uploader($targetDir = null, $cb_check_file = false)
	{
		
		error_reporting(0);

		JLoader::register('PluploadHandler', JPATH_SITE . '/components/com_judirectory/helpers/pluploadhandler.php');

		
		if (!$targetDir)
		{
			$targetDir = JPATH_ROOT . "/media/com_judirectory/tmp";
		}

		$cleanupTargetDir = true; 
		$maxFileAge       = 5 * 3600; 

		
		self::cleanup($targetDir, $maxFileAge);

		
		if (!JFolder::exists($targetDir))
		{
			JFolder::create($targetDir);
			$indexHtml = $targetDir . 'index.html';
			$buffer    = "<!DOCTYPE html><title></title>";
			JFile::write($indexHtml, $buffer);
		}

		
		if (!is_writable($targetDir))
		{
			$targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "plupload";
			

			
			if (!file_exists($targetDir))
			{
				@mkdir($targetDir);
			}
		}

		PluploadHandler::no_cache_headers();
		PluploadHandler::cors_headers();

		if (!PluploadHandler::handle(array(
			'target_dir'    => $targetDir,
			'cleanup'       => $cleanupTargetDir,
			'max_file_age'  => $maxFileAge,
			'cb_check_file' => $cb_check_file,
		))
		)
		{
			die(json_encode(array(
				'OK'    => 0,
				'error' => array(
					'code'    => PluploadHandler::get_error_code(),
					'message' => PluploadHandler::get_error_message()
				)
			)));
		}
		else
		{
			die(json_encode(array('OK' => 1)));
		}
	}

	
	private static function cleanup($tmpDir, $maxFileAge = 18000)
	{
		
		if (JFolder::exists($tmpDir))
		{
			foreach (glob($tmpDir . '/*.*') AS $tmpFile)
			{
				if (basename($tmpFile) == 'index.html' || (time() - filemtime($tmpFile) < $maxFileAge))
				{
					continue;
				}

				if (is_dir($tmpFile))
				{
					JFolder::delete($tmpFile);
				}
				else
				{
					JFile::delete($tmpFile);
				}
			}
		}
	}

	
	public static function isValidUploadURL()
	{
		$app  = JFactory::getApplication();
		$time = $app->input->getInt('time', 0);
		$code = $app->input->get('code', '');

		if (!$time || !$code)
		{
			return false;
		}

		$secret = JFactory::getConfig()->get('secret');
		if ($code != md5($time . $secret))
		{
			return false;
		}

		
		$liveTimeUrl = 60 * 60 * 5;
		if ((time() - $time) > $liveTimeUrl)
		{
			return false;
		}

		return true;
	}


}