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

class JUDirectoryFrontHelperPermission
{
	
	protected static $cache = array();

	
	public static function canDoCategory($categoryId, $checkAccess = false, &$error = array())
	{
		if (!$categoryId)
		{
			return false;
		}

		$storeId = md5(__METHOD__ . "::$categoryId::" . (int) $checkAccess);

		
		$storeId_AccessibleCategoryIds = md5(__CLASS__ . '::AccessibleCategoryIds');
		if (isset(self::$cache[$storeId_AccessibleCategoryIds]))
		{
			$categoryIdArrayCanAccess = self::$cache[$storeId_AccessibleCategoryIds];
			
			if (!empty($categoryIdArrayCanAccess) && in_array($categoryId, $categoryIdArrayCanAccess))
			{
				self::$cache[$storeId] = true;

				return self::$cache[$storeId];
			}
			else
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}
		}

		if (!isset(self::$cache[$storeId]))
		{
			
			if (!$categoryId)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			
			$path = JUDirectoryHelper::getCategoryPath($categoryId);

			
			if (!$path)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			$user    = JFactory::getUser();
			$levels  = $user->getAuthorisedViewLevels();
			$nowDate = JFactory::getDate()->toSql();

			
			foreach ($path AS $category)
			{
				
				if ($category->published != 1)
				{
					$error                 = array("code" => 404, "message" => JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
					self::$cache[$storeId] = false;

					return self::$cache[$storeId];
				}

				if ($category->publish_up > $nowDate || (intval($category->publish_down) > 0 && $category->publish_down < $nowDate))
				{
					$error                 = array("code" => 404, "message" => JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
					self::$cache[$storeId] = false;

					return self::$cache[$storeId];
				}

				
				if ($checkAccess && !in_array($category->access, $levels))
				{
					$error                 = array("code" => 403, "message" => JText::_('JERROR_ALERTNOAUTHOR'));
					self::$cache[$storeId] = false;

					return self::$cache[$storeId];
				}
			}

			self::$cache[$storeId] = true;
		}

		return self::$cache[$storeId];
	}

	
	public static function getAccessibleCategoryIds()
	{
		$storeId = md5(__CLASS__ . '::AccessibleCategoryIds');
		if (!isset(self::$cache[$storeId]))
		{
			
			$catIdArray = JUDirectoryFrontHelperCategory::getCategoryIdsRecursive(1);
			
			array_unshift($catIdArray, 1);
			self::$cache[$storeId] = $catIdArray;
		}

		return self::$cache[$storeId];
	}

	
	public static function isListingOwner($listingId)
	{
		if (!$listingId)
		{
			return false;
		}

		$storeId = md5(__METHOD__ . "::$listingId");
		if (!isset(self::$cache[$storeId]))
		{
			$listingObject = JUDirectoryHelper::getListingById($listingId);
			
			if (!is_object($listingObject))
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			$user = JFactory::getUser();
			if (!$user->get('guest') && $user->id == $listingObject->created_by)
			{
				self::$cache[$storeId] = true;
			}
			else
			{
				self::$cache[$storeId] = false;
			}
		}

		return self::$cache[$storeId];
	}

	
	public static function canSubmitListing($categoryId = null, &$messages = array())
	{
		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			return true;
		}

		return false;
	}

	public static function canSubmitListingInCat($categoryId)
	{
		
		$canDoCategory = JUDirectoryFrontHelperPermission::canDoCategory($categoryId, true);
		if (!$canDoCategory)
		{
			return false;
		}

		
		if ($categoryId == 1)
		{
			$params = JUDirectoryHelper::getParams($categoryId);
			if (!$params->get('allow_add_listing_to_root'))
			{
				return false;
			}
		}

		
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$modCanSubmitListing = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($categoryId, 'listing_create');
			if ($modCanSubmitListing)
			{
				return true;
			}
		}

		
		$user = JFactory::getUser();

		if ($user->authorise('judir.listing.create', 'com_judirectory.category.' . $categoryId))
		{
			return true;
		}

		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('judirectory');
		$pluginTriggerResults = $dispatcher->trigger('canSubmitListing', array($categoryId));

		if (in_array(true, $pluginTriggerResults, true))
		{
			return true;
		}
	}

	
	public static function canCheckInListing($listingId)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
		$listingTable = JTable::getInstance('Listing', 'JUDirectoryTable');
		$listingTable->load($listingId);

		if (property_exists($listingTable, 'checked_out') && property_exists($listingTable, 'checked_out_time') && $listingTable->checked_out > 0)
		{
			$user           = JFactory::getUser();
			$isModerator    = JUDirectoryFrontHelperModerator::isModerator();
			$isListingOwner = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
			
			if ($isModerator || $isListingOwner || $listingTable->checked_out == $user->id)
			{
				$canEditListing      = JUDirectoryFrontHelperPermission::canEditListing($listingId);
				$canEditStateListing = JUDirectoryFrontHelperPermission::canEditStateListing($listingTable);
				if ($canEditListing || $canEditStateListing)
				{
					return true;
				}
			}
		}

		return false;
	}

	
	public static function canEditListing($listingId)
	{
		$listingObject = JUDirectoryHelper::getListingById($listingId);
		if (!is_object($listingObject))
		{
			return false;
		}

		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			return true;
		}

		return false;
	}

	
	public static function canEditStateListing($listingObject)
	{
		if (!is_object($listingObject))
		{
			return false;
		}
		$listingId = $listingObject->id;

		if (!isset($listingObject->cat_id))
		{
			$listingObject = JUDirectoryHelper::getListingById($listingId);
		}

		$mainCatId = $listingObject->cat_id;

		
		$userCanDoCategory = JUDirectoryFrontHelperPermission::canDoCategory($mainCatId);
		if (!$userCanDoCategory)
		{
			return false;
		}

		if ($listingObject->id)
		{
			
			$isListingOwner = JUDirectoryFrontHelperPermission::isListingOwner($listingObject->id);
			if ($isListingOwner)
			{
				$params                   = JUDirectoryHelper::getParams($mainCatId);
				$ownerCanEditStateListing = $params->get('listing_owner_can_edit_state_listing', 0);
				if ($ownerCanEditStateListing)
				{
					return true;
				}
			}
		}

		
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$modCanEditState = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($mainCatId, 'listing_edit_state');
			if ($modCanEditState)
			{
				return true;
			}

			if ($listingObject->id && $listingObject->approved <= 0)
			{
				$modCanApprove = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($mainCatId, 'listing_approve');
				if ($modCanApprove)
				{
					return true;
				}
			}
		}

		
		$user = JFactory::getUser();
		if (!$user->get('guest'))
		{
			$corePublished = JUDirectoryFrontHelperField::getField('published', $listingObject);
			if ($corePublished)
			{
				if ($listingObject->approved <= 0)
				{
					if ($corePublished->canSubmit())
					{
						return true;
					}
				}
				elseif ($listingObject->approved == 1)
				{
					if ($corePublished->canEdit())
					{
						return true;
					}
				}
			}
		}

		return false;
	}

	
	public static function canDeleteListing($listingId)
	{
		$listingObject = JUDirectoryHelper::getListingById($listingId);
		if (!is_object($listingObject))
		{
			return false;
		}

		
		$userCanDoCategory = JUDirectoryFrontHelperPermission::canDoCategory($listingObject->cat_id);
		if (!$userCanDoCategory)
		{
			return false;
		}

		
		$isListingOwner = JUDirectoryFrontHelperPermission::isListingOwner($listingId);

		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$modCanDelete = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($listingObject->cat_id, 'listing_delete');
			if ($modCanDelete)
			{
				return true;
			}

			if ($isListingOwner)
			{
				$modCanDeleteOwn = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($listingObject->cat_id, 'listing_delete_own');
				if ($modCanDeleteOwn)
				{
					return true;
				}
			}
		}

		
		$user = JFactory::getUser();
		if (!$user->get('guest'))
		{
			$asset = 'com_judirectory.listing.' . $listingObject->id;
			
			if ($user->authorise('judir.listing.delete', $asset))
			{
				return true;
			}

			
			if ($isListingOwner && $user->authorise('judir.listing.delete.own', $asset))
			{
				return true;
			}
		}

		return false;
	}

	
	public static function userCanDoListing($listingId, $checkAccess = false)
	{
		$nowDate = JFactory::getDate()->toSql();
		if (!$listingId)
		{
			return false;
		}

		$storeId = md5(__METHOD__ . "::$listingId::" . (int) $checkAccess);
		if (!isset(self::$cache[$storeId]))
		{
			$listingObject = JUDirectoryHelper::getListingById($listingId);

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

			$canDoCat = JUDirectoryFrontHelperPermission::canDoCategory($listingObject->cat_id);
			if (!$canDoCat)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			self::$cache[$storeId] = true;
		}

		return self::$cache[$storeId];
	}

	
	public static function canViewListing($listingId)
	{
		$listingObject = JUDirectoryHelper::getListingById($listingId);
		if (!is_object($listingObject))
		{
			return false;
		}

		$canDoCategory = JUDirectoryFrontHelperPermission::canDoCategory($listingObject->cat_id, true);

		if (!$canDoCategory)
		{
			return false;
		}
		
		if ($listingObject->approved == 1)
		{
			$canEditListing      = JUDirectoryFrontHelperPermission::canEditListing($listingId);
			$canEditStateListing = JUDirectoryFrontHelperPermission::canEditStateListing($listingObject);
			$userCanDoListing    = JUDirectoryFrontHelperPermission::userCanDoListing($listingId, true);
			if ($canEditListing || $canEditStateListing || $userCanDoListing)
			{
				return true;
			}
		}

		$isListingPublished = JUDirectoryFrontHelperListing::isListingPublished($listingId);

		
		$isListingOwner = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
		if ($isListingOwner)
		{
			$params = JUDirectoryHelper::getParams(null, $listingId);
			
			if ($listingObject->approved <= 0 || $isListingPublished || (!$isListingPublished && $params->get('listing_owner_can_view_unpublished_listing', 0)))
			{
				return true;
			}
		}

		
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			if ($listingObject->approved == 1)
			{
				if ($isListingPublished)
				{
					
					$modCanViewListing = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($listingObject->cat_id, 'listing_view');
					if ($modCanViewListing)
					{
						return true;
					}
				}
				else
				{
					
					$modCanViewListing = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($listingObject->cat_id, 'listing_view_unpublished');
					if ($modCanViewListing)
					{
						return true;
					}
				}
			}
			else
			{
				
				$modCanViewListing = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithListing($listingObject->cat_id, 'listing_approve');
				if ($modCanViewListing)
				{
					return true;
				}
			}
		}

		return false;
	}

	
	

	
	public static function canAutoApprovalListingWhenSubmit($mainCategoryId)
	{
		$user         = JFactory::getUser();
		$mainCategory = JUDirectoryFrontHelperCategory::getCategory($mainCategoryId);
		if (!is_object($mainCategory))
		{
			return false;
		}

		
		if ($user->authorise('judir.listing.create.auto_approval', 'com_judirectory.category.' . $mainCategory->id))
		{
			return true;
		}

		
		
		if (!$user->get('guest'))
		{
			$params                       = JUDirectoryHelper::getParams($mainCategoryId);
			$autoApprovalListingThreshold = (int) $params->get('auto_approval_listing_threshold', 0);
			if ($autoApprovalListingThreshold > 0)
			{
				$totalApprovedListingsOfUser = JUDirectoryFrontHelperListing::getTotalListingsOfUserApprovedByMod($user->id);
				if ($totalApprovedListingsOfUser >= $autoApprovalListingThreshold)
				{
					return true;
				}
			}
		}

		return false;
	}

	
	public static function canAutoApprovalListingWhenEdit($listingId, $newMainCategoryId)
	{
		$listingObject = JUDirectoryHelper::getListingById($listingId);

		if ($listingObject->approved == 1)
		{
			$originalListingId     = $listingObject->id;
			$originalListingObject = $listingObject;
			$mainCategoryId        = JUDirectoryFrontHelperCategory::getMainCategoryId($originalListingObject->id);
			$params                = JUDirectoryHelper::getParams($newMainCategoryId);
		}
		elseif ($listingObject->approved < 0)
		{
			$tempListingObject     = $listingObject;
			$originalListingId     = abs($listingObject->approved);
			$originalListingObject = JUDirectoryHelper::getListingById($originalListingId);
			$mainCategoryId        = JUDirectoryFrontHelperCategory::getMainCategoryId($originalListingObject->id);
			$params                = JUDirectoryHelper::getParams($newMainCategoryId);
		}
		else
		{
			return false;
		}

		
		$isListingOwner              = JUDirectoryFrontHelperPermission::isListingOwner($originalListingObject->id);
		$autoApprovalForListingOwner = $params->get('listing_owner_can_edit_listing_auto_approval', 1);
		if ($isListingOwner && $autoApprovalForListingOwner)
		{
			return true;
		}

		$user = JFactory::getUser();

		
		if ($mainCategoryId == $newMainCategoryId)
		{
			if ($user->authorise('judir.listing.edit.auto_approval', 'com_judirectory.category.' . $mainCategoryId))
			{
				return true;
			}
		}
		else
		{
			if ($user->authorise('judir.listing.create.auto_approval', 'com_judirectory.category.' . $newMainCategoryId))
			{
				return true;
			}
		}

		
		
		if (!$user->get('guest'))
		{
			$autoApprovalListingThreshold = (int) $params->get('auto_approval_listing_threshold', 0);
			if ($autoApprovalListingThreshold > 0)
			{
				$totalApprovedListingsOfUser = JUDirectoryFrontHelperListing::getTotalListingsOfUserApprovedByMod($user->id);
				if ($totalApprovedListingsOfUser >= $autoApprovalListingThreshold)
				{
					return true;
				}
			}
		}

		return false;
	}


	
	public static function canRateListing($listingId)
	{
		$listingObject = JUDirectoryHelper::getListingById($listingId);
		if (!is_object($listingObject))
		{
			return false;
		}

		$params = JUDirectoryHelper::getParams(null, $listingId);
		if (!$params->get('enable_listing_rate', 1))
		{
			return false;
		}

		$userCanViewListing = JUDirectoryFrontHelperPermission::userCanDoListing($listingId, true);
		if (!$userCanViewListing)
		{
			return false;
		}

		$ratingField = new JUDirectoryFieldCore_rating();
		if (!$ratingField->canView())
		{
			return false;
		}

		$user            = JFactory::getUser();
		$criteriaGroupId = JUDirectoryFrontHelperCriteria::getCriteriaGroupIdByCategoryId($listingObject->cat_id);
		if ($criteriaGroupId == 0 || !JUDirectoryHelper::hasMultiRating())
		{
			$assetName = 'com_judirectory.category.' . $listingObject->cat_id;
			
			if ($user->authorise('judir.single.rate', $assetName))
			{
				if ($user->authorise('judir.single.rate.many_times', $assetName))
				{
					return true;
				}
				else
				{
					
					if ($user->get('guest'))
					{
						$session = JFactory::getSession();
						if (!$session->has('judir-listing-rated-' . $listingId))
						{
							return true;
						}
					}
					
					else
					{
						$totalVoteTimes = JUDirectoryFrontHelperRating::getTotalListingVotesOfUser($user->id, $listingId);
						if ($totalVoteTimes == 0)
						{
							return true;
						}
					}
				}
			}
		}
		else
		{
			$assetName = 'com_judirectory.criteriagroup.' . $criteriaGroupId;
			
			if ($user->authorise('judir.criteria.rate', $assetName))
			{
				if ($user->authorise('judir.criteria.rate.many_times', $assetName))
				{
					return true;
				}
				else
				{
					
					if ($user->get('guest'))
					{
						$session = JFactory::getSession();
						if (!$session->has('judir-listing-rated-' . $listingId))
						{
							return true;
						}
					}
					
					else
					{
						$totalVoteTimes = JUDirectoryFrontHelperRating::getTotalListingVotesOfUser($user->id, $listingId);
						if ($totalVoteTimes == 0)
						{
							return true;
						}
					}
				}
			}
		}

		return false;
	}

	
	public static function canReportListing($listingId)
	{
		return false;
	}

	
	public static function canClaimListing($listingId)
	{
		return false;
	}

	
	public static function canContactListing($listingId)
	{
		return false;
	}

	
	public static function canUpload(&$file, &$error = array(), $legal_extensions, $max_size = 0, $check_mime = false, $allowed_mime = '', $ignored_extensions = '', $image_extensions = 'bmp,gif,jpg,jpeg,png')
	{
		
		if (empty($file['name']) || !JFile::exists($file['tmp_name']))
		{
			isset($error['WARN_SOURCE']) ? $error['WARN_SOURCE']++ : $error['WARN_SOURCE'] = 1;

			return false;
		}

		jimport('joomla.filesystem.file');

		
		

		
		$executable = array(
			'php', 'js', 'exe', 'phtml', 'java', 'perl', 'py', 'asp', 'dll', 'go', 'ade', 'adp', 'bat', 'chm', 'cmd', 'com', 'cpl', 'hta', 'ins', 'isp',
			'jse', 'lib', 'mde', 'msc', 'msp', 'mst', 'pif', 'scr', 'sct', 'shb', 'sys', 'vb', 'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh'
		);

		$legal_extensions   = array_map('trim', explode(",", strtolower(str_replace("\n", ",", $legal_extensions))));
		$ignored_extensions = array_map('trim', explode(",", strtolower(str_replace("\n", ",", $ignored_extensions))));

		$format = strtolower(JFile::getExt($file['name']));
		
		if ($format == '' || $format == false || (!in_array($format, $legal_extensions)) || in_array($format, $executable))
		{
			isset($error['WARN_FILETYPE']) ? $error['WARN_FILETYPE']++ : $error['WARN_FILETYPE'] = 1;

			return false;
		}

		
		if ($max_size > 0 && (int) $file['size'] > $max_size)
		{
			isset($error['WARN_FILETOOLARGE']) ? $error['WARN_FILETOOLARGE']++ : $error['WARN_FILETOOLARGE'] = 1;

			return false;
		}

		
		if ($check_mime)
		{
			$image_extensions = array_map('trim', explode(",", strtolower(str_replace("\n", ",", $image_extensions))));

			
			if (in_array($format, $image_extensions))
			{
				
				
				if (!empty($file['tmp_name']))
				{
					if (($imginfo = getimagesize($file['tmp_name'])) === false)
					{
						isset($error['WARN_INVALID_IMG']) ? $error['WARN_INVALID_IMG']++ : $error['WARN_INVALID_IMG'] = 1;

						return false;
					}
				}
				else
				{
					isset($error['WARN_FILETOOLARGE']) ? $error['WARN_FILETOOLARGE']++ : $error['WARN_FILETOOLARGE'] = 1;

					return false;
				}

				$file['mime_type'] = $imginfo['mime'];
			}
			
			elseif (!in_array($format, $ignored_extensions))
			{
				
				$allowed_mime = array_map('trim', explode(",", strtolower(str_replace("\n", ",", $allowed_mime))));

				if (function_exists('finfo_open'))
				{
					
					$finfo = finfo_open(FILEINFO_MIME);
					$type  = finfo_file($finfo, $file['tmp_name']);

					if (strlen($type) && !in_array($type, $allowed_mime))
					{
						isset($error['WARN_INVALID_MIME']) ? $error['WARN_INVALID_MIME']++ : $error['WARN_INVALID_MIME'] = 1;

						return false;
					}
					$file['mime_type'] = $type;
					finfo_close($finfo);
				}
				elseif (function_exists('mime_content_type'))
				{
					
					$type = mime_content_type($file['tmp_name']);

					if (strlen($type) && !in_array($type, $allowed_mime))
					{
						isset($error['WARN_INVALID_MIME']) ? $error['WARN_INVALID_MIME']++ : $error['WARN_INVALID_MIME'] = 1;

						return false;
					}
					$file['mime_type'] = $type;
				}
				
			}
		}

		$xss_check = file_get_contents($file['tmp_name'], false, null, -1, 256);

		$html_tags = array(
			'abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink',
			'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del',
			'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
			'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext',
			'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object',
			'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar',
			'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title',
			'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--'
		);

		
		foreach ($html_tags AS $tag)
		{
			
			if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>'))
			{
				isset($error['WARN_IEXSS']) ? $error['WARN_IEXSS']++ : $error['WARN_IEXSS'] = 1;

				return false;
			}
		}

		

		return true;
	}

	
	public static function isCommentOwner($commentId)
	{
		$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId);
		if (!is_object($commentObject))
		{
			return false;
		}

		$user = JFactory::getUser();
		
		if (!$user->get('guest') && $user->id == $commentObject->user_id)
		{
			return true;
		}

		return false;
	}

	
	public static function canComment($listingId, $email = '')
	{
		
		$canViewListing = JUDirectoryFrontHelperPermission::userCanDoListing($listingId, true);
		if ($canViewListing == false)
		{
			return false;
		}

		
		$userIdPassed = self::checkBlackListUserId();
		if (!$userIdPassed)
		{
			return false;
		}

		
		$userIpPassed = self::checkBlackListUserIP();
		if (!$userIpPassed)
		{
			return false;
		}

		
		$params = JUDirectoryHelper::getParams(null, $listingId);
		$user   = JFactory::getUser();

		$isListingOwner           = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
		$ownerCanCommentOnListing = $params->get('listing_owner_can_comment', 0);
		
		if ($isListingOwner && $ownerCanCommentOnListing)
		{
			
			$ownerCanCommentManyTimes = $params->get('listing_owner_can_comment_many_times', 0);
			if ($ownerCanCommentManyTimes)
			{
				return true;
			}
			else
			{
				$totalCommentsOnListing = JUDirectoryFrontHelperComment::getTotalCommentsOnListingOfUser($listingId, $user->id);
				if ($totalCommentsOnListing == 0)
				{
					return true;
				}
			}
		}

		$asset = 'com_judirectory.listing.' . $listingId;

		if ($user->authorise('judir.comment.create', $asset))
		{
			
			if ($user->authorise('judir.comment.create.many_times', $asset))
			{
				return true;
			}
			else
			{
				if (!$user->get('guest'))
				{
					$totalCommentsOnListing = JUDirectoryFrontHelperComment::getTotalCommentsOnListingOfUser($listingId, $user->id);
					if ($totalCommentsOnListing == 0)
					{
						return true;
					}
				}
				else
				{
					if ($email != '')
					{
						$totalCommentsPerOneListingForGuest = JUDirectoryFrontHelperComment::getTotalCommentsOnListingForGuest($listingId, $email);
						if ($totalCommentsPerOneListingForGuest == 0)
						{
							return true;
						}
					}
					else
					{
						return true;
					}
				}
			}
		}

		return false;
	}

	
	public static function checkBlackListUserId()
	{
		$params          = JUDirectoryHelper::getParams();
		$userIdBlackList = $params->get('userid_blacklist', '');
		if ($userIdBlackList !== '')
		{
			$user               = JFactory::getUser();
			$userIdBlackListArr = array_map('trim', explode(",", strtolower(str_replace("\n", ",", $userIdBlackList))));
			if (in_array($user, $userIdBlackListArr))
			{
				return false;
			}
		}

		return true;
	}

	
	public static function checkBlackListUserIP()
	{
		require_once JPATH_SITE . '/components/com_judirectory/libs/ipblocklist.class.php';
		$params    = JUDirectoryHelper::getParams();
		$app       = JFactory::getApplication();
		$is_passed = true;

		if ($app->isSite() && $params->get('block_ip', 0))
		{
			$ip_address  = JUDirectoryFrontHelper::getIpAddress();
			$ipWhiteList = $params->get('ip_whitelist', '');
			$ipBlackList = $params->get('ip_blacklist', '');

			$checkIp   = new IpBlockList($ipWhiteList, $ipBlackList);
			$is_passed = $checkIp->ipPass($ip_address);
		}

		return $is_passed;
	}

	
	public static function canReplyComment($listingId, $commentId)
	{
		if (!$listingId || !$commentId)
		{
			return false;
		}

		$storeId = md5(__METHOD__ . "::" . $listingId . "::" . $commentId);
		if (!isset(self::$cache[$storeId]))
		{
			$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId);
			if ($commentObject->published != 1 || $commentObject->approved != 1)
			{
				self::$cache[$storeId] = false;

				return self::$cache[$storeId];
			}

			$params = JUDirectoryHelper::getParams(null, $listingId);

			
			$isCommentOwner              = JUDirectoryFrontHelperPermission::isCommentOwner($commentId);
			$commentOwnerCanReplyComment = $params->get('can_reply_own_comment', 0);
			if ($isCommentOwner && $commentOwnerCanReplyComment)
			{
				self::$cache[$storeId] = true;

				return self::$cache[$storeId];
			}

			
			$isListingOwner              = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
			$listingOwnerCanReplyComment = $params->get('listing_owner_can_reply_comment', 1);
			if ($isListingOwner && $listingOwnerCanReplyComment)
			{
				self::$cache[$storeId] = true;

				return self::$cache[$storeId];
			}

			
			$user  = JFactory::getUser();
			$asset = 'com_judirectory.listing.' . $listingId;
			if ($user->authorise('judir.comment.reply', $asset))
			{
				self::$cache[$storeId] = true;

				return self::$cache[$storeId];
			}

			self::$cache[$storeId] = false;

			return self::$cache[$storeId];
		}

		return self::$cache[$storeId];
	}


	
	public static function canAutoApprovalComment($listingId)
	{
		$params = JUDirectoryHelper::getParams(null, $listingId);

		
		$isListingOwner                      = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
		$autoApprovalWhenListingOwnerComment = $params->get('listing_owner_auto_approval_when_comment', 0);

		if ($isListingOwner && $autoApprovalWhenListingOwnerComment)
		{
			return true;
		}

		
		$user  = JFactory::getUser();
		$asset = 'com_judirectory.listing.' . $listingId;
		if ($user->authorise('judir.comment.auto_approval', $asset))
		{
			return true;
		}

		
		
		if (!$user->get('guest'))
		{
			$autoApprovalCommentThreshold = (int) $params->get('auto_approval_comment_threshold', 0);
			if ($autoApprovalCommentThreshold > 0)
			{
				$totalApprovedCommentsOfUser = JUDirectoryFrontHelperComment::getTotalApprovedCommentsOfUser($user->id);
				if ($totalApprovedCommentsOfUser >= $autoApprovalCommentThreshold)
				{
					return true;
				}
			}
		}

		return false;
	}


	
	public static function canAutoApprovalReplyComment($listingId)
	{
		$params = JUDirectoryHelper::getParams(null, $listingId);

		
		$isListingOwner                    = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
		$autoApprovalWhenListingOwnerReply = $params->get('listing_owner_auto_approval_when_reply_comment', 0);
		if ($isListingOwner && $autoApprovalWhenListingOwnerReply)
		{
			return true;
		}

		
		$user  = JFactory::getUser();
		$asset = 'com_judirectory.listing.' . $listingId;
		if ($user->authorise('judir.comment.reply.auto_approval', $asset))
		{
			return true;
		}

		
		
		if (!$user->get('guest'))
		{
			$autoApprovalReplyThreshold = (int) $params->get('auto_approval_comment_reply_threshold', 0);
			if ($autoApprovalReplyThreshold > 0)
			{
				$totalApprovedRepliesOfUser = JUDirectoryFrontHelperComment::getTotalApprovedRepliesOfUser($user->id);
				if ($totalApprovedRepliesOfUser >= $autoApprovalReplyThreshold)
				{
					return true;
				}
			}
		}

		return false;
	}

	
	public static function canEditComment($commentId)
	{
		$commentObj = JUDirectoryFrontHelperComment::getCommentObject($commentId);

		
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			if ($commentObj->approved > 0)
			{
				$modCanEditComment = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithComment($commentId, 'comment_edit');

				if ($modCanEditComment)
				{
					return true;
				}
			}
			else
			{
				$modCanApproveComment = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithComment($commentId, 'comment_approve');

				if ($modCanApproveComment)
				{
					return true;
				}
			}

		}

		$isCommentOwner = JUDirectoryFrontHelperPermission::isCommentOwner($commentId);
		if ($isCommentOwner)
		{
			$params = JUDirectoryHelper::getParams(null, $commentObj->listing_id);

			$allowEditCommentWithin        = $params->get('allow_edit_comment_within', 600);
			$allowEditCommentWithinSeconds = $allowEditCommentWithin * 60;

			
			if ($allowEditCommentWithin == 0)
			{
				return true;
			}

			$inEditableTime = false;
			$timeNow        = strtotime(JHtml::date('now', 'Y-m-d H:i:s'));
			$commentCreated = strtotime($commentObj->created);
			
			if ($timeNow <= ($commentCreated + $allowEditCommentWithinSeconds))
			{
				$inEditableTime = true;
			}

			
			if ($inEditableTime)
			{
				return true;
			}
		}

		return false;
	}

	
	public static function canDeleteComment($commentId)
	{
		
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$modCanDeleteComment = JUDirectoryFrontHelperModerator::checkModeratorCanDoWithComment($commentId, 'comment_delete');
			if ($modCanDeleteComment)
			{
				return true;
			}
		}

		$commentObj       = JUDirectoryFrontHelperComment::getCommentObject($commentId, 'cm.listing_id');
		$params           = JUDirectoryHelper::getParams(null, $commentObj->listing_id);
		$isCommentOwner   = JUDirectoryFrontHelperPermission::isCommentOwner($commentId);
		$deleteOwnComment = $params->get('delete_own_comment', 0);
		if ($isCommentOwner && $deleteOwnComment)
		{
			return true;
		}

		return false;
	}

	
	public static function canVoteComment($listingId, $commentId)
	{
		$params = JUDirectoryHelper::getParams(null, $listingId);

		if (!$params->get('allow_vote_comment', 1))
		{
			return false;
		}

		$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId);
		if ($commentObject->published != 1 || $commentObject->approved != 1)
		{
			return false;
		}

		$session = JFactory::getSession();
		
		if ($session->has('judir-comment-voted-' . $commentId))
		{
			return false;
		}

		
		$user              = JFactory::getUser();
		$enableVoteComment = $params->get('allow_vote_comment', 1);
		if (!$user->get('guest') && $enableVoteComment)
		{
			
			$isCommentOwner = JUDirectoryFrontHelperPermission::isCommentOwner($commentId);
			if ($isCommentOwner)
			{
				$commentOwnerCanVoteOwnComment = $params->get('can_vote_own_comment', 0);
				if ($commentOwnerCanVoteOwnComment)
				{
					return true;
				}
				else
				{
					return false;
				}
			}

			
			$isListingOwner             = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
			$listingOwnerCanVoteComment = $params->get('listing_owner_can_vote_comment', 1);
			if ($isListingOwner && $listingOwnerCanVoteComment)
			{
				return true;
			}

			
			$asset = 'com_judirectory.listing.' . $listingId;
			if ($user->authorise('judir.comment.vote', $asset))
			{
				return true;
			}
		}

		return false;
	}

	
	public static function canReportComment($listingId, $commentId)
	{
		return false;
	}

	
	public static function canCheckInComment($commentId)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
		$commentTable = JTable::getInstance('Comment', 'JUDirectoryTable');
		$commentTable->load($commentId);

		if (property_exists($commentTable, 'checked_out') && property_exists($commentTable, 'checked_out_time') && $commentTable->checked_out > 0)
		{
			$user           = JFactory::getUser();
			$isModerator    = JUDirectoryFrontHelperModerator::isModerator();
			$isCommentOwner = JUDirectoryFrontHelperPermission::isCommentOwner($commentId);
			
			if ($isModerator || $isCommentOwner || $commentTable->checked_out == $user->id)
			{
				$canEditComment = JUDirectoryFrontHelperPermission::canEditComment($commentId);
				if ($canEditComment)
				{
					return true;
				}
			}
		}

		return false;
	}

	
	public static function showCaptchaWhenReport($listingId, $reportComment = false)
	{
		if (!$listingId)
		{
			return false;
		}
		$params       = JUDirectoryHelper::getParams(null, $listingId);
		$ownerListing = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
		if ($ownerListing)
		{
			if (!$params->get('listing_owner_use_captcha_when_report', 0))
			{
				return false;
			}
		}
		$user = JFactory::getUser();
		if ($reportComment)
		{
			$assetName = 'com_judirectory.listing.' . $listingId;
			if ($user->authorise('judir.comment.report.no_captcha', $assetName))
			{
				return false;
			}
		}
		else
		{
			$assetName = 'com_judirectory.listing.' . $listingId;
			if ($user->authorise('judir.listing.report.no_captcha', $assetName))
			{
				return false;
			}
		}

		return true;
	}

	
	public static function showCaptchaWhenClaimListing($listingId)
	{
		
		return true;
	}

	
	public static function showCaptchaWhenContactListing($listingId)
	{
		if (!$listingId)
		{
			return false;
		}
		$user      = JFactory::getUser();
		$assetName = 'com_judirectory.listing.' . $listingId;
		if ($user->authorise('judir.listing.contact.no_captcha', $assetName))
		{
			return false;
		}

		return true;
	}

	
	public static function showCaptchaWhenComment($listingId)
	{
		if (!$listingId)
		{
			return false;
		}
		$user         = JFactory::getUser();
		$params       = JUDirectoryHelper::getParams(null, $listingId);
		$ownerListing = JUDirectoryFrontHelperPermission::isListingOwner($listingId);
		if ($ownerListing && $params->get('listing_owner_use_captcha_when_comment', 1))
		{
			return false;
		}
		$assetName = 'com_judirectory.listing.' . $listingId;
		if ($user->authorise('judir.comment.no_captcha', $assetName))
		{
			return false;
		}

		return true;
	}

	
	public static function canVoteCollection($collectionId)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$params = JUDirectoryHelper::getParams();
		if (!$params->get('collection_allow_vote', 1))
		{
			return false;
		}

		$user = JFactory::getUser();
		
		if ($user->id == 0 && !$params->get('collection_allow_guest_vote', 1))
		{
			return false;
		}

		$session = JFactory::getSession();
		
		if ($session->has('judir-collection-voted-' . $collectionId))
		{
			return false;
		}

		
		if (!$user->get('guest'))
		{
			$collection = JTable::getInstance('Collection', 'JUDirectoryTable');
			if ($collection->load($collectionId))
			{
				if ($user->id > 0 && $user->id == $collection->created_by && !$params->get('collection_allow_owner_vote', 0))
				{
					return false;
				}
			}
		}

		return true;
	}

	
	public static function isOwnDashboard()
	{
		$app  = JFactory::getApplication();
		$view = $app->input->getString('view', '');
		if ($view == 'modpermission')
		{
			return true;
		}
		$userId = $app->input->getInt('id', 0);
		$user   = JFactory::getUser();
		
		$isOwnDashboard = true;
		
		if ($userId > 0 && $userId != $user->id)
		{
			$isOwnDashboard = false;
		}

		

		return $isOwnDashboard;
	}

	public static function canViewDashboard()
	{
		$params                = JUDirectoryHelper::getParams();
		$public_user_dashboard = $params->get("public_user_dashboard", 0);
		$user                  = JFactory::getUser();

		if ($public_user_dashboard)
		{
			$app    = JFactory::getApplication();
			$userId = $app->input->getInt('id', 0);
			
			if ($user->id == 0 && $userId == 0)
			{
				return false;
			}

			return true;
		}
		else
		{
			
			if ($user->id == 0)
			{
				return false;
			}
			
			else
			{
				$isOwnDashboard = JUDirectoryFrontHelperPermission::isOwnDashboard();

				return $isOwnDashboard;
			}
		}
	}

}