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


class JUDirectoryModelTools extends JModelList
{
	########################### RESIZE IMAGES FUNCTIONS ##################################
	
	public function resizeImages()
	{
		$app                  = JFactory::getApplication();
		$limitStart           = $app->input->getInt('limitstart', '0');
		$limit                = $app->input->getInt('limit', '10');
		$resizeCatImg         = $app->input->getInt('category', '0');
		$resizeListingGallery = $app->input->getInt('listing_gallery', '0');
		$resizeAvatar         = $app->input->getInt('avatar', '0');
		$resizeListingImage   = $app->input->getInt('listing_image', '0');
		$resizeColIcon        = $app->input->getInt('collection', '0');
		$catIdArr             = $app->input->get('catlist', array(), 'array');
		$rootCat              = JUDirectoryFrontHelperCategory::getRootCategory();
		if (empty($catIdArr[0]) || in_array($rootCat->id, $catIdArr))
		{
			$allChildCats = 'all';
			$listingIds   = 'all';
		}
		else
		{
			$allChildCats = $this->getAllChildCats($catIdArr);
			$listingIds   = $this->getListingIdList($allChildCats);
		}

		
		if ($resizeAvatar == 1)
		{
			$this->resizeUserAvatar($limitStart, $limit);
		}

		if ($resizeColIcon == 1)
		{
			$this->resizeCollectionIcons($limitStart, $limit);
		}

		if ($resizeCatImg == 1)
		{
			$this->resizeCategoryImages($limitStart, $limit, $allChildCats);
		}

		if ($resizeListingGallery == 1)
		{
			$this->resizeListingGallery($limitStart, $limit, $listingIds);
		}

		if ($resizeListingImage == 1)
		{
			$this->resizeListingImage($limitStart, $limit, $listingIds);
		}

		
		if ($limitStart == 0)
		{
			$totalListingImages         = $totalCatImages = $totalAvatars = $totalListingImages = $totalCollectionIcons = 0;
			$totalResizedImagesEachTime = 0;
			if ($resizeAvatar == 1)
			{
				$totalAvatars = $this->getTotalAvatars();
				$totalResizedImagesEachTime += 1;
			}

			if ($resizeColIcon == 1)
			{
				$totalCollectionIcons = $this->getTotalCollectionIcons();
				$totalResizedImagesEachTime += 1;
			}

			if ($resizeListingGallery == 1)
			{
				$totalListingImages = $this->getTotalListingImagesGallery($listingIds);
				$totalResizedImagesEachTime += 2;
			}

			if ($resizeListingImage == 1)
			{
				$totalListingImages = $this->getTotalListingImages($listingIds);
				$totalResizedImagesEachTime += 1;
			}

			if (in_array($rootCat->id, $catIdArr))
			{
				if ($resizeCatImg == 1)
				{
					$totalCatImages = $this->getTotalCategoryImages();
					$totalResizedImagesEachTime += 2;
				}
			}
			else
			{
				if ($resizeCatImg == 1)
				{
					$totalCatImages = count(explode(',', $allChildCats));
					$totalResizedImagesEachTime += 2;
				}
			}

			$totalImages = $totalAvatars + $totalListingImages + $totalCatImages + $totalListingImages + $totalCollectionIcons;
			$app->setUserState('total-images', $totalImages);
			$app->setUserState('total-resized-images-each-time', $totalResizedImagesEachTime);
		}

		$totalImages                = ($limitStart == 0) ? $totalImages : $app->getUserState('total-images');
		$totalResizedImagesEachTime = ($limitStart == 0) ? $totalResizedImagesEachTime : $app->getUserState('total-resized-images-each-time');

		
		$percent = floor(($limitStart + ($totalResizedImagesEachTime * $limit)) / $totalImages * 100);
		if (($limitStart >= ($totalImages - $limit)) || $percent >= 100 || $totalImages == 0)
		{
			$percent = 100;
		}

		echo $percent;
	}

	
	public function resizeCategoryImages($limitStart, $limit, $listCat = 'all')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, images')
			->from('#__judirectory_categories')
			
			->where("LOWER(images) LIKE " . $db->quote("%.jpeg%") . " OR LOWER(images) LIKE " . $db->quote("%.jpg%") . " OR LOWER(images) LIKE " . $db->quote("%.gif%") . " OR LOWER(images) LIKE " . $db->quote("%.png%"));

		if ($listCat != 'all')
		{
			$query->where('id IN (' . $listCat . ')');
		}

		$db->setQuery($query, $limitStart, $limit);
		$categories = $db->loadObjectList();

		if (!empty($categories))
		{
			foreach ($categories AS $category)
			{

				$registry = new JRegistry;
				$registry->loadString($category->images);
				$catImg = $registry->toObject();

				if ($catImg->intro_image)
				{
					$intro_image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("category_intro_image_directory", "media/com_judirectory/images/category/intro/") . 'original/' . $catImg->intro_image;
					if (JFile::exists($intro_image_path))
					{
						$image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("category_intro_image_directory", "media/com_judirectory/images/category/intro/") . $catImg->intro_image;

						
						if (JFile::exists($image_path))
						{
							JFile::delete($image_path);
						}
						
						JUDirectoryHelper::renderImages($intro_image_path, $image_path, 'category_intro', true, $category->id);
					}
				}

				if ($catImg->detail_image)
				{
					$detail_image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("category_detail_image_directory", "media/com_judirectory/images/category/detail/") . "original/" . $catImg->intro_image;
					if (JFile::exists($detail_image_path))
					{
						$image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("category_detail_image_directory", "media/com_judirectory/images/category/detail/") . $catImg->intro_image;
						
						if (JFile::exists($image_path))
						{
							JFile::delete($image_path);
						}
						
						JUDirectoryHelper::renderImages($detail_image_path, $image_path, 'category_detail', true, $category->id);
					}
				}

			}
		}
	}

	
	public function resizeListingGallery($limitStart, $limit, $listingIds = 'all')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('img.listing_id, img.file_name, listingxref.cat_id')
			->from('#__judirectory_images AS img')
			->join('', ' #__judirectory_listings_xref AS listingxref ON img.listing_id = listingxref.listing_id')
			->where('listingxref.main = 1')
			->where("img.file_name != ''");

		if ($listingIds != 'all' && $listingIds)
		{
			$query->where('listingxref.listing_id IN (' . $listingIds . ')');
		}
		$db->setQuery($query, $limitStart, $limit);
		$listings = $db->loadObjectList();

		if (!empty($listings))
		{
			foreach ($listings AS $listing)
			{
				$image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/") . $listing->listing_id . '/' . $listing->file_name;
				if (JFile::exists($image_path))
				{
					if ($listing->cat_id)
					{
						$image_path       = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/") . $listing->listing_id . '/' . $listing->file_name;
						$small_image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_small_image_directory", "media/com_judirectory/images/gallery/small/") . $listing->listing_id . "/" . $listing->file_name;
						$full_image_path  = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_big_image_directory", "media/com_judirectory/images/gallery/big/") . $listing->listing_id . "/" . $listing->file_name;
						
						if (JFile::exists($small_image_path))
						{
							JFile::delete($small_image_path);
						}

						if (JFile::exists($full_image_path))
						{
							JFile::delete($full_image_path);
						}

						
						JUDirectoryHelper::renderImages($image_path, $small_image_path, 'listing_small', true, $listing->cat_id);
						JUDirectoryHelper::renderImages($image_path, $full_image_path, 'listing_big', true, $listing->cat_id);
					}
				}
			}
		}
	}

	
	public function resizeListingImage($limitStart, $limit, $listingIds = 'all')
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		
		$query->select('listing.image, listingxref.cat_id')
			->from('#__judirectory_listings AS listing')
			->join('', '#__judirectory_listings_xref AS listingxref ON listing.id = listingxref.listing_id')
			->where('listingxref.main = 1')
			->where("listing.image != ''")
			->where("listing.image NOT LIKE 'default/%'");
		if ($listingIds != 'all')
		{
			$query->where('listingxref.listing_id IN (' . $listingIds . ')');
		}

		$db->setQuery($query, $limitStart, $limit);
		$listings = $db->loadObjectList();
		if ($listings)
		{
			foreach ($listings AS $listing)
			{
				$ori_image_dir = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/") . 'original/' . $listing->image;
				if (JFile::exists($ori_image_dir))
				{
					$image_dir = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/") . $listing->image;
					if (JFile::exists($image_dir))
					{
						JFile::delete($image_dir);
					}

					JUDirectoryHelper::renderImages($ori_image_dir, $image_dir, 'listing_image', true, $listing->cat_id);
				}
			}
		}
	}

	
	public function resizeUserAvatar($limitStart, $limit)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT avatar FROM #__judirectory_users WHERE avatar != '' LIMIT $limitStart, $limit";
		$db->setQuery($query);
		$images = $db->loadObjectList();

		if (count($images))
		{
			foreach ($images AS $image)
			{
				$ori_image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("avatar_directory", "media/com_judirectory/images/avatar/") . 'original/' . $image->avatar;
				if (JFile::exists($ori_image_path))
				{
					$image_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("avatar_directory", "media/com_judirectory/images/avatar/") . $image->avatar;
					if (JFile::exists($image_path))
					{
						JFile::Delete($image_path);
					}

					JUDirectoryHelper::renderImages($ori_image_path, $image_path, 'avatar');
				}
			}
		}
	}

	
	public function resizeCollectionIcons($limitStart, $limit)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT icon FROM #__judirectory_collections WHERE icon != ''";
		$db->setQuery($query, $limitStart, $limit);
		$images = $db->loadObjectList();

		if (count($images))
		{
			$collection_icon_path = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("collection_icon_directory", "media/com_judirectory/images/collection/");
			foreach ($images AS $image)
			{
				$ori_image_path = $collection_icon_path . "original/" . $image->icon;

				if (JFile::exists($ori_image_path))
				{
					$image_path = $collection_icon_path . $image->icon;
					if (JFile::exists($image_path))
					{
						JFile::delete($image_path);
					}

					JUDirectoryHelper::renderImages($ori_image_path, $image_path, 'collection');
				}
			}
		}
	}

	
	public function getTotalListingImagesGallery($listingIds = 'all')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)')
			->from('#__judirectory_images AS img')
			->join('', ' #__judirectory_listings_xref AS listingxref ON img.listing_id = listingxref.listing_id')
			->where('listingxref.main = 1')
			->where("img.file_name != ''");

		if ($listingIds != 'all')
		{
			$query->where('listingxref.listing_id IN (' . $listingIds . ')');
		}
		$db->setQuery($query);

		return $db->loadResult();
	}

	
	public function getTotalCategoryImages()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)')
			->from('#__judirectory_categories')
			
			->where("LOWER(images) LIKE " . $db->quote("%.jpeg%") . " OR LOWER(images) LIKE " . $db->quote("%.jpg%") . " OR LOWER(images) LIKE " . $db->quote("%.gif%") . " OR LOWER(images) LIKE " . $db->quote("%.png%"));
		$db->setQuery($query);
		$totalCats = $db->loadResult();

		return $totalCats;
	}

	
	public function getTotalAvatars()
	{
		$db    = JFactory::getDbo();
		$query = "SELECT COUNT(*) FROM #__judirectory_users WHERE avatar != ''";
		$db->setQuery($query);
		$totalAvatars = $db->loadResult();

		return $totalAvatars;
	}

	
	public function getTotalListingImages($listingIds = 'all')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)')
			->from('#__judirectory_listings')
			->where("images != ''");
		if ($listingIds != 'all')
		{
			$query->where('id IN (' . $listingIds . ')');
		}
		$db->setQuery($query);

		$totalListingImages = $db->loadResult();

		return $totalListingImages;
	}

	
	public function getTotalCollectionIcons()
	{
		$db    = JFactory::getDbo();
		$query = "SELECT COUNT(*) FROM #__judirectory_collections WHERE icon != ''";
		$db->setQuery($query);
		$totalCollectionIcons = $db->loadResult();

		return $totalCollectionIcons;
	}

	
	public function getCategoryList()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id', 'title'))
			->from('#__judirectory_categories')
			->where('level = 1')
			->order('id ASC');

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

	
	public function getAllChildCats($catArr)
	{
		$childCatIds = array();
		$db          = JFactory::getDbo();
		foreach ($catArr AS $catId)
		{
			
			$catObject = JUDirectoryHelper::getCategoryById($catId);

			
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__judirectory_categories')
				->where('lft >= ' . $catObject->lft)
				->where('rgt <= ' . $catObject->rgt);
			$db->setQuery($query);
			$childCatIdArr = $db->loadColumn();

			$childCatIds = array_merge($childCatIds, $childCatIdArr);
		}

		$childCatIdStr = implode(',', $childCatIds);

		return $childCatIdStr;
	}

	
	public function getListingIdList($catListFull)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('listing_id');
		$query->from('#__judirectory_listings_xref');
		$query->where('cat_id IN (' . $catListFull . ')');
		$db->setQuery($query);
		$listingIdArr = $db->loadColumn();
		$listingIds   = implode(',', $listingIdArr);

		return $listingIds;
	}

	########################### !RESIZE IMAGES FUNCTIONS  ##################################

	########################### REBUILD RATING FUNCTIONS ##################################

	
	public function getListingsForRating(array $cats, array $criteriaGroups, $limit, $start)
	{
		$listingsCats = array();
		$db           = $this->getDbo();
		$rootCat      = JUDirectoryFrontHelperCategory::getRootCategory();

		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__judirectory_criterias_groups');
		$query->where('published != 1');
		$db->setQuery($query);
		$criteriaGroupsUnpublished = $db->loadColumn();

		if (empty($cats[0]) || in_array($rootCat->id, $cats))
		{
			$query = $db->getQuery(true);
			$query->select('id AS cat_id, parent_id, lft, rgt, level, selected_criteriagroup');
			$query->from('#__judirectory_categories');

			if (!empty($criteriaGroups) && !empty($criteriaGroups[0]))
			{
				$query->where('criteriagroup_id IN (' . implode(',', $criteriaGroups) . ')');
			}

			if (!empty($criteriaGroupsUnpublished))
			{
				$query->where('criteriagroup_id NOT IN (' . implode(',', $criteriaGroupsUnpublished) . ')');
			}

			$db->setQuery($query);
			$groupCats = $db->loadObjectList();

			if (!empty($groupCats))
			{
				foreach ($groupCats AS $group)
				{
					$listingsCats[] = $group->cat_id;
				}
			}
		}
		else
		{
			foreach ($cats AS $cat)
			{
				$query = $db->getQuery(true);
				$query->select('lft, rgt');
				$query->from('#__judirectory_categories');
				$query->where('id = ' . $cat);
				$db->setQuery($query);
				$left_rigth = $db->loadObject();

				if ($left_rigth)
				{
					$query = $db->getQuery(true);
					$query->select('id');
					$query->from('#__judirectory_categories');
					$query->where('lft >= ' . $left_rigth->lft);
					$query->where('rgt <= ' . $left_rigth->rgt);

					if (!empty($criteriaGroups) && !empty($criteriaGroups[0]))
					{
						$query->where('criteriagroup_id IN (' . implode(',', $criteriaGroups) . ')');
					}

					if (!empty($criteriaGroupsUnpublished))
					{
						$query->where('criteriagroup_id NOT IN (' . implode(',', $criteriaGroupsUnpublished) . ')');
					}

					$db->setQuery($query);
					$subCats = $db->loadColumn();

					if (!empty($subCats))
					{
						$listingsCats = array_merge($listingsCats, $subCats);
					}
				}
			}
		}

		if (!empty($listingsCats))
		{
			$query = "SELECT listing_id FROM #__judirectory_listings_xref WHERE cat_id IN (" . implode(',', $listingsCats) . ") ORDER BY listing_id";

			
			if ($start == 0)
			{
				$db->setQuery($query);
				$allListings = $db->loadColumn();

				$app = JFactory::getApplication();
				$app->setUserState('total_listings', count($allListings));
			}

			$query .= " LIMIT $start,$limit";

			$db->setQuery($query);
			$listingIds = $db->loadColumn();

			return $listingIds;
		}
		else
		{
			return array();
		}

	}

	public function reBuildRating()
	{
		$app = JFactory::getApplication();

		$start = $app->input->getInt("start", 0);
		$limit = $app->input->getInt("limit", 5);

		
		if ($start == 0)
		{
			
			$cats           = $app->input->get('cats', array(), 'array');
			$criteriaGroups = $app->input->get('criteriagroups', array(), 'array');

			
			$app->setUserState('cats', $cats);
			$app->setUserState('criteria_groups', $criteriaGroups);
		}
		else
		{
			
			$cats           = $app->getUserState('cats', array());
			$criteriaGroups = $app->getUserState('criteria_groups', array());
		}

		
		$listingIds = $this->getListingsForRating($cats, $criteriaGroups, $limit, $start);

		if (!empty($listingIds))
		{
			foreach ($listingIds AS $listingId)
			{
				JUDirectoryHelper::rebuildRating($listingId);
			}

			$result = array(
				'processed' => count($listingIds) + $start,
				'total'     => $app->getUserState('total_listings', 0)
			);
		}
		else
		{
			$result = array(
				'processed' => 100,
				'total'     => $app->getUserState('total_listings', 0)
			);
		}

		return json_encode($result);
	}

	########################### !REBUILD RATING FUNCTIONS  ##################################

	########################## IMPORT CSV FILE ###########################################
	
	

	
	public function loadCSVColumns()
	{
		$app = JFactory::getApplication();

		
		$path = $app->getUserState('file_path', '');
		
		$delimiter = $app->getUserState('delimiter', ',');
		
		$enclosure = $app->getUserState('enclosure', '"');

		if (!JFile::exists($path))
		{
			$this->setError(JText::sprintf("COM_JUDIRECTORY_IMPORT_IMAGE_FILE_S_NOT_FOUND", $path));

			return false;
		}

		
		$rows = JUDirectoryHelper::getCSVData($path, $delimiter, $enclosure, 'r+', 0, null, true);

		$csvColumnRows = array_shift($rows);

		$totalCsvRow = count($rows);
		$app->setUserState('csv_total_row', $totalCsvRow);

		return $csvColumnRows;
	}

	
	

	

	

	
	

	
	

	

	

	##################################### END OF IMPORT TOOL ######################################

	public function getCriteriaGroups()
	{
		$db = $this->getDbo();
		$db->setQuery("SELECT id, name FROM #__judirectory_criterias_groups WHERE published = 1");
		$criteriaGroups = $db->loadObjectList();

		if (!empty($criteriaGroups))
		{
			foreach ($criteriaGroups AS $key => $criteriaGroup)
			{
				$criteriaGroups[$key] = "|—" . $criteriaGroup->name;
			}
			array_unshift($criteriaGroups, array('id' => '', 'name' => JText::_('JALL')));

			return $criteriaGroups;
		}

		return array();
	}

	public function deleteImportFilePath()
	{
		$app            = JFactory::getApplication();
		$importFilePath = $app->getUserState('file_path');
		if ($importFilePath && JFile::exists($importFilePath))
		{
			$folderPath = dirname($importFilePath);
			if (JFolder::exists($folderPath))
			{
				JFolder::delete($folderPath);
			}
		}
	}

	public function rebuildCommentTree()
	{
		$app        = JFactory::getApplication();
		$limit      = $app->input->get('limit', 10);
		$limitStart = $app->input->get('limitstart', 0);
		$left       = $app->input->get('lft', 2);

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__judirectory_comments');
		$query->order('lft');
		$query->where('level = 1');
		$db->setQuery($query, $limitStart, $limit);
		$commentIds = $db->loadColumn();

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$table = JTable::getInstance('Comment', 'JUDirectoryTable');
		foreach ($commentIds AS $commentId)
		{
			$left = $table->rebuild($commentId, $left, 1);
		}

		$return             = array();
		$totalCommentLevel1 = self::getTotalCommentLevel1();
		if (!$commentIds || ($limitStart + $limit) > $totalCommentLevel1)
		{
			$return['percent'] = 100;
			$return['lft']     = $left;
			if ($commentIds)
			{
				$query->clear();
				$query->update('#__judirectory_comments');
				$query->set('rgt = ' . $left);
				$query->where('level = 0 AND id = 1');
				$db->setQuery($query);
				$db->execute();
			}
		}
		else
		{
			$return['percent'] = round((($limitStart + $limit) / $totalCommentLevel1) * 100);
			$return['lft']     = $left;
		}

		return json_encode($return);
	}

	public function getTotalCommentLevel1()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(1)');
		$query->from('#__judirectory_comments');
		$query->order('lft');
		$query->where('level = 1');
		$db->setQuery($query);

		return $db->loadResult();
	}
}
