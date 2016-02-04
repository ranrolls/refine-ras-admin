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

jimport('joomla.utilities.utility');

JLoader::register('JUDirectoryHelper', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judirectory.php');


JLoader::register('JUDirectoryFrontHelperBreadcrumb', JPATH_SITE . '/components/com_judirectory/helpers/breadcrumb.php');
JLoader::register('JUDirectoryFrontHelperCaptcha', JPATH_SITE . '/components/com_judirectory/helpers/captcha.php');
JLoader::register('JUDirectoryFrontHelperCategory', JPATH_SITE . '/components/com_judirectory/helpers/category.php');
JLoader::register('JUDirectoryFrontHelperComment', JPATH_SITE . '/components/com_judirectory/helpers/comment.php');
JLoader::register('JUDirectoryFrontHelperCriteria', JPATH_SITE . '/components/com_judirectory/helpers/criteria.php');
JLoader::register('JUDirectoryFrontHelperListing', JPATH_SITE . '/components/com_judirectory/helpers/listing.php');
JLoader::register('JUDirectoryFrontHelperEditor', JPATH_SITE . '/components/com_judirectory/helpers/editor.php');
JLoader::register('JUDirectoryFrontHelperField', JPATH_SITE . '/components/com_judirectory/helpers/field.php');
JLoader::register('JUDirectoryFrontHelperLanguage', JPATH_SITE . '/components/com_judirectory/helpers/language.php');
JLoader::register('JUDirectoryFrontHelperLog', JPATH_SITE . '/components/com_judirectory/helpers/log.php');
JLoader::register('JUDirectoryFrontHelperMail', JPATH_SITE . '/components/com_judirectory/helpers/mail.php');
JLoader::register('JUDirectoryFrontHelperModerator', JPATH_SITE . '/components/com_judirectory/helpers/moderator.php');
JLoader::register('JUDirectoryFrontHelperPermission', JPATH_SITE . '/components/com_judirectory/helpers/permission.php');
JLoader::register('JUDirectoryFrontHelperPluginParams', JPATH_SITE . '/components/com_judirectory/helpers/pluginparams.php');
JLoader::register('JUDirectoryFrontHelperRating', JPATH_SITE . '/components/com_judirectory/helpers/rating.php');
JLoader::register('JUDirectoryFrontHelperSeo', JPATH_SITE . '/components/com_judirectory/helpers/seo.php');
JLoader::register('JUDirectoryFrontHelperString', JPATH_SITE . '/components/com_judirectory/helpers/string.php');
JLoader::register('JUDirectoryFrontHelperTemplate', JPATH_SITE . '/components/com_judirectory/helpers/template.php');

class JUDirectoryFrontHelper
{
	
	protected static $cache = array();

	
	public static $sef_replace = array(
		'%26' => '-26', 
		'%3F' => '-3F', 
		'%2F' => '-2F', 
		'%3C' => '-3C', 
		'%3E' => '-3E', 
		'%23' => '-23', 
		'%24' => '-24', 
		'%3A' => '-3A', 
		'%2E' => '-2E', 
		'%2B' => '-2B', 
		'%25' => '-25', 
	);

	
	public static function getSubscriptionObject($subscriptionId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_subscriptions');
		$query->where('id = ' . $subscriptionId);
		$db->setQuery($query);
		$subscriptionObject = $db->loadObject();

		return $subscriptionObject;
	}

	
	public static function getSubscriptionObjectByType($userId, $itemId, $type)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_subscriptions');
		$query->where('user_id = ' . (int) $userId);
		$query->where('item_id = ' . (int) $itemId);
		$query->where('type = ' . $db->quote($type));
		$db->setQuery($query);
		$subscriptionObject = $db->loadObject();

		if (is_object($subscriptionObject))
		{
			
			return $subscriptionObject;
		}

		return false;
	}

	
	public static function setCanonical($canonicalLink)
	{
		$homPage = false;

		$language        = JFactory::getLanguage();
		$languageTagArr  = explode('-', $language->getTag());
		$currentUrl      = trim(str_replace(array('index.php/', 'index.php'), '', JUri::getInstance()->toString()), '/');
		$currentUrl      = str_replace('?lang=' . $languageTagArr[0], '', $currentUrl);
		$homeUrl         = JUri::base();
		$homeUrlWithLang = $homeUrl . $languageTagArr[0];

		if ($currentUrl == $homeUrl || $currentUrl == $homeUrlWithLang)
		{
			$homPage = true;
		}

		if (!$homPage)
		{
			$document = JFactory::getDocument();
			
			$canonicalTagExisted = false;
			$listingHeadData     = $document->getHeadData();
			$listingHeadDataLink = $listingHeadData['links'];

			
			if (!empty($listingHeadDataLink))
			{
				foreach ($listingHeadDataLink AS $listingHeadDataLinkKey => $listingHeadDataLinkItem)
				{
					if (is_array($listingHeadDataLinkItem) && isset($listingHeadDataLinkItem['relation']) && $listingHeadDataLinkItem['relation'] == 'canonical')
					{
						$canonicalTagExisted = true;
						unset($listingHeadDataLink[$listingHeadDataLinkKey]);
						$listingHeadDataLink[$canonicalLink] = $listingHeadDataLinkItem;
					}
				}
				$listingHeadData['links'] = $listingHeadDataLink;
				$document->setHeadData($listingHeadData);
			}

			
			if (!$canonicalTagExisted)
			{
				$document->addHeadLink(htmlspecialchars($canonicalLink), 'canonical');
			}
		}
	}

	
	public static function isWithinXDays($time_from, $days)
	{
		$timeNow   = strtotime(JFactory::getDate()->toSql());
		$time_from = strtotime($time_from);

		
		if ($timeNow < $time_from + $days * 86400)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	
	public static function getTagById($tag_id)
	{
		$storeId = md5(__METHOD__ . "::$tag_id");
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select("*")
				->from("#__judirectory_tags")
				->where("id = " . $tag_id);
			$db->setQuery($query);
			self::$cache[$storeId] = $db->loadObject();
		}

		return self::$cache[$storeId];
	}

	
	public static function getTagsByListingId($listing_id, $select = 't.*', $checkPublished = true, $checkAccess = true, $checkLanguage = true, $order = 'txref.ordering', $dir = 'ASC')
	{
		if (!$listing_id)
		{
			return array();
		}

		
		if (strpos(",", $select) !== false)
		{
			$selectColumnArr = explode(",", $select);
			sort($selectColumnArr);
			$select = implode(",", $selectColumnArr);
		}

		$storeId = md5(__METHOD__ . "::$listing_id::$select::" . (int) $checkPublished . "::" . (int) $checkAccess . "::" . (int) $checkLanguage . "::$order::$dir");
		if (!isset(self::$cache[$storeId]))
		{
			$db       = JFactory::getDBo();
			$nullDate = $db->getNullDate();
			$nowDate  = JFactory::getDate()->toSql();

			$query = $db->getQuery(true);
			$query->select($select);
			$query->from("#__judirectory_tags AS t");
			$query->join("", "#__judirectory_tags_xref txref ON t.id = txref.tag_id");
			$query->where("txref.listing_id = " . $listing_id);
			if ($checkPublished)
			{
				$query->where('t.published = 1');
				$query->where('(t.publish_up = ' . $db->quote($nullDate) . ' OR t.publish_up <= ' . $db->quote($nowDate) . ')');
				$query->where('(t.publish_down = ' . $db->quote($nullDate) . ' OR t.publish_down >= ' . $db->quote($nowDate) . ')');
			}

			if ($checkAccess)
			{
				
				$user      = JFactory::getUser();
				$levels    = $user->getAuthorisedViewLevels();
				$levelsStr = implode(',', $levels);
				$query->where('t.access IN (' . $levelsStr . ')');
			}

			$app = JFactory::getApplication();
			if ($app->isSite() && $checkLanguage)
			{
				
				$languageTag = JFactory::getLanguage()->getTag();
				if ($app->getLanguageFilter())
				{
					$query->where('t.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
				}
			}

			$query->order($order . ' ' . $dir);

			$db->setQuery($query);

			self::$cache[$storeId] = $db->loadObjectList();
		}

		return self::$cache[$storeId];
	}

	
	public static function loadjQuery($forceLoad = false)
	{
		$document = JFactory::getDocument();
		if ($document->getType() != 'html')
		{
			return true;
		}
		
		$isJoomla3x = version_compare(JVERSION, '3.0', 'ge');
		if ($isJoomla3x)
		{
			JHtml::_('jquery.framework');
		}
		else
		{
			
			$params = JUDirectoryHelper::getParams();
			
			if ($params->get('load_jquery', 2) == 0)
			{
				return false;
			}

			$loadjQuery = true;
			
			if ($params->get('load_jquery', 2) == 2)
			{
				$loadjQuery = true;
				$header     = $document->getHeadData();
				$scripts    = $header['scripts'];
				if (count($scripts))
				{
					$pattern = '/([\/\\a-zA-Z0-9_:\.-]*)jquery([0-9\.-]|core|min|pack)*?.js/i';
					foreach ($scripts AS $script => $opts)
					{
						if (preg_match($pattern, $script))
						{
							$loadjQuery = false;
							break;
						}
					}
				}
			}

			
			if ($loadjQuery || $forceLoad)
			{
				$document->addScript(JUri::root(true) . '/components/com_judirectory/assets/js/jquery.min.js');
				$document->addScript(JUri::root(true) . '/components/com_judirectory/assets/js/jquery-junoconflict.js');
			}
		}
	}

	
	public static function loadjQueryUI($forceLoad = false)
	{
		
		$params = JUDirectoryHelper::getParams();
		
		if ($params->get('load_jquery_ui', 2) == 0)
		{
			return false;
		}

		$loadjQueryUI = true;
		
		if ($params->get('load_jquery_ui', 2) == 2)
		{
			$loadjQueryUI = true;
			$document     = JFactory::getDocument();
			$header       = $document->getHeadData();
			$scripts      = $header['scripts'];
			if (count($scripts))
			{
				$pattern = '/([\/\\a-zA-Z0-9_:\.-]*)jquery[.-]ui([0-9\.-]|core|custom|min|pack)*?.js(.*?)/i';
				foreach ($scripts AS $script => $opts)
				{
					if (preg_match($pattern, $script))
					{
						$loadjQueryUI = false;
						break;
					}
				}
			}
		}

		
		if ($loadjQueryUI || $forceLoad)
		{
			JUDirectoryFrontHelper::loadjQuery();
			$document = JFactory::getDocument();
			$document->addScript(JUri::root(true) . '/components/com_judirectory/assets/js/jquery-ui.min.js');
			$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/css/jquery-ui.min.css');
		}
	}

	
	public static function loadBootstrap($version = 2, $type = 2)
	{
		$document = JFactory::getDocument();

		
		if ($document->getType() != 'html')
		{
			return true;
		}

		$isJoomla3x = JUDirectoryHelper::isJoomla3x();
		$app        = JFactory::getApplication();

		
		if ($type == 0 && ($isJoomla3x || $app->isSite()))
		{
			return false;
		}

		
		$loadBootstrap = true;
		if ($type == 2 || $app->isAdmin())
		{
			$header  = $document->getHeadData();
			$scripts = $header['scripts'];
			if (count($scripts))
			{
				$pattern = '/([\/\\a-zA-Z0-9_:\.-]*)bootstrap.([0-9\.-]|core|custom|min|pack)*?.js(.*?)/i';
				foreach ($scripts AS $script => $opts)
				{
					if (preg_match($pattern, $script))
					{
						$loadBootstrap = false;
						break;
					}
				}
			}
		}

		
		if ($loadBootstrap)
		{
			JUDirectoryFrontHelper::loadjQuery();

			if ($version == 2)
			{
				if (!$isJoomla3x)
				{
					$document->addScript(JUri::root(true) . '/components/com_judirectory/assets/bootstrap2/js/bootstrap.min.js');
					$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/bootstrap2/css/bootstrap.min.css');
					$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/bootstrap2/css/bootstrap-responsive.min.css');

					$document->addScriptDeclaration('
					jQuery(document).ready(function($){
						$(\'.hasTooltip\').tooltip({\'html\': true, trigger: \'hover\'}).bind(\'hidden\', function () {
					        $(this).show();
					    });
					});
				');
				}
				else
				{
					JHtml::_('bootstrap.framework');
					if ($app->isSite())
					{
						
					}
				}
			}
			elseif ($version == 3)
			{
				$document->addScript(JUri::root(true) . '/components/com_judirectory/assets/bootstrap3/js/bootstrap.min.js');
				$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/bootstrap3/css/bootstrap.min.css');
				$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/bootstrap3/css/bootstrap-theme.min.css');

				$document->addScriptDeclaration('
					jQuery(document).ready(function($){
						$(\'.hasTooltip\').tooltip({\'html\': true, trigger: \'hover\'}).bind(\'hidden\', function () {
					        $(this).show();
					    });
					});
				');
			}
		}

		
		if ($app->isAdmin())
		{
			$document->addScript(JUri::root(true) . '/administrator/components/com_judirectory/assets/js/bootstrap-hover-dropdown.js');
		}
	}

	
	public static function getBootstrapColumns($numOfColumns)
	{
		switch ($numOfColumns)
		{
			case 1:
				return array(12);
				break;
			case 2:
				return array(6, 6);
				break;
			case 3:
				return array(4, 4, 4);
				break;
			case 4:
				return array(3, 3, 3, 3);
				break;
			case 5:
				return array(3, 3, 2, 2, 2);
				break;
			case 6:
				return array(2, 2, 2, 2, 2, 2);
				break;
			case 7:
				return array(2, 2, 2, 2, 2, 1, 1);
				break;
			case 8:
				return array(2, 2, 2, 2, 1, 1, 1, 1);
				break;
			case 9:
				return array(2, 2, 2, 1, 1, 1, 1, 1, 1);
				break;
			case 10:
				return array(2, 2, 1, 1, 1, 1, 1, 1, 1, 1);
				break;
			case 11:
				return array(2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
				break;
			case 12:
				return array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
				break;
			default:
				return array(12);
				break;
		}
	}


	#######################< LISTING LIST QUERY OPTIMIZATION SECTION >##########################

	
	public static function optimizeListListingQuery(&$query, $massSelect = false)
	{
		$user = JFactory::getUser();
		$query->select('cmain.id AS cat_id');
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

		if ($massSelect)
		{
			
			$commentsField = new JUDirectoryFieldCore_comments();
			if ($commentsField->canView(array("view" => "list")))
			{
				$isModerator = JUDirectoryFrontHelperModerator::isModerator();
				if (!$isModerator && !$user->authorise('core.admin', 'com_judirectory'))
				{
					$params                = JUDirectoryHelper::getParams();
					$negative_vote_comment = $params->get('negative_vote_comment');
					if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
					{
						$query->select('(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE cm.listing_id = listing.id AND cm.approved = 1 AND cm.published = 1 AND cm.level = 1
										AND (cm.total_votes - cm.helpful_votes) < ' . $negative_vote_comment . ' ) AS total_comments');
					}
					else
					{
						$query->select('(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE cm.listing_id = listing.id AND cm.approved = 1 AND cm.published = 1 AND cm.level = 1) AS total_comments');
					}
				}
			}

			
			$subscriptionsField = new JUDirectoryFieldCore_subscriptions();
			if ($subscriptionsField->canView(array("view" => "list")))
			{
				$query->select('(SELECT COUNT(*) FROM #__judirectory_subscriptions AS sub WHERE sub.item_id = listing.id AND sub.type = "listing" AND sub.published = 1) AS total_subscriptions');
			}

			
			$reportsField = new JUDirectoryFieldCore_reports();
			if ($reportsField->canView(array("view" => "list")))
			{
				$query->select('(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type = "listing") AS total_reports');
			}

			$categoriesField = new JUDirectoryFieldCore_categories();
			if ($categoriesField->canView(array("view" => "list")))
			{
				
				$query->select('(SELECT GROUP_CONCAT(catids.id ORDER BY listingx_catids.main DESC, listingx_catids.ordering ASC SEPARATOR ",") FROM (#__judirectory_categories AS catids JOIN #__judirectory_listings_xref AS listingx_catids ON catids.id = listingx_catids.cat_id) WHERE listing.id = listingx_catids.listing_id GROUP BY listing.id) AS cat_ids');
				
				$query->select('(SELECT GROUP_CONCAT(cattitles.title ORDER BY listingx_cattitles.main DESC, listingx_cattitles.ordering ASC SEPARATOR "|||") FROM (#__judirectory_categories AS cattitles JOIN #__judirectory_listings_xref AS listingx_cattitles ON cattitles.id = listingx_cattitles.cat_id) WHERE listing.id = listingx_cattitles.listing_id GROUP BY listing.id) AS cat_titles');
			}

			$tagsField = new JUDirectoryFieldCore_tags();
			if ($tagsField->canView(array("view" => "list")))
			{
				
				
				$query->select('IFNULL ((SELECT GROUP_CONCAT(tagids.id ORDER BY tx_tagids.ordering ASC SEPARATOR ",") FROM (#__judirectory_tags AS tagids JOIN #__judirectory_tags_xref AS tx_tagids ON tagids.id = tx_tagids.tag_id) WHERE listing.id = tx_tagids.listing_id GROUP BY listing.id), "") AS tag_ids');
				
				$query->select('IFNULL ((SELECT GROUP_CONCAT(tagtitles.title ORDER BY tx_tagtitles.ordering ASC SEPARATOR "|||") FROM (#__judirectory_tags AS tagtitles JOIN #__judirectory_tags_xref AS tx_tagtitles ON tagtitles.id = tx_tagtitles.tag_id) WHERE listing.id = tx_tagtitles.listing_id GROUP BY listing.id), "") AS tag_titles');
			}

			
			$app         = JFactory::getApplication();
			$accessLevel = implode(',', $user->getAuthorisedViewLevels());
			$db          = JFactory::getDbo();
			$date        = JFactory::getDate();
			$nullDate    = $db->quote($db->getNullDate());
			$nowDate     = $db->quote($date->toSql());

			
			$fieldQuery = $db->getQuery(true);
			$fieldQuery->select('field.id');
			$fieldQuery->from('#__judirectory_fields AS field');
			$fieldQuery->where('field.field_name = ""');
			$fieldQuery->where('field.list_view = 1');

			$fieldQuery->where('field.published = 1');
			$fieldQuery->where('field.publish_up <= ' . $nowDate);
			$fieldQuery->where('(field.publish_down = ' . $nullDate . ' OR field.publish_down > ' . $nowDate . ')');

			$fieldQuery->where('field.access IN (' . $accessLevel . ')');

			$view = $app->input->get('view', '');
			if ($view == 'category' || $view == 'tree')
			{
				$cat_id   = $app->input->getInt('id', 0);
				$category = JUDirectoryHelper::getCategoryById($cat_id);
				if (is_object($category))
				{
					$fieldQuery->where('field.group_id = ' . $category->fieldgroup_id);
				}
			}
			else
			{
				$fieldQuery->join('', '#__judirectory_categories AS c ON (field.group_id = c.fieldgroup_id OR field.group_id = 1)');

				if (is_array($categoryIdArrayCanAccess) && count($categoryIdArrayCanAccess) > 0)
				{
					$fieldQuery->where('c.id IN(' . implode(",", $categoryIdArrayCanAccess) . ')');
				}
				else
				{
					$fieldQuery->where('c.id IN("")');
				}
			}

			$fieldQuery->join('', '#__judirectory_fields_groups AS field_group ON field.group_id = field_group.id');
			$fieldQuery->where('field_group.published = 1');
			$fieldQuery->where('field_group.access IN (' . $accessLevel . ')');

			$fieldQuery->group('field.id');

			$db->setQuery($fieldQuery);

			
			$fields = $db->loadObjectList();
			foreach ($fields AS $field)
			{
				$query->select('IFNULL (fields_values_' . $field->id . '.value, "") AS field_values_' . $field->id);
				$query->join('LEFT', '#__judirectory_fields_values AS fields_values_' . $field->id . ' ON fields_values_' . $field->id . '.listing_id = listing.id AND fields_values_' . $field->id . '.field_id = ' . $field->id);
			}
		}
	}

	
	public static function appendDataToListingObjList(&$listingObjectList, $params, $usingForMod = false)
	{
		if (is_array($listingObjectList) && count($listingObjectList))
		{
			$user = JFactory::getUser();

			foreach ($listingObjectList AS $listingObject)
			{
				
				JUDirectoryHelper::getListingById($listingObject->id, false, $listingObject);

				$listingObject->params = JUDirectoryFrontHelperListing::getListingDisplayParams($listingObject->id);

				
				if (!$user->get('guest'))
				{
					$canEditListing      = JUDirectoryFrontHelperPermission::canEditListing($listingObject->id);
					$canEditStateListing = JUDirectoryFrontHelperPermission::canEditStateListing($listingObject);
					$canDeleteListing    = JUDirectoryFrontHelperPermission::canDeleteListing($listingObject->id);
					$listingObject->params->set('access-edit', $canEditListing);
					$listingObject->params->set('access-edit-state', $canEditStateListing);
					$listingObject->params->set('access-delete', $canDeleteListing);
				}

				
				if ($listingObject->published != 1)
				{
					$listingObject->label_unpublished = true;
				}
				else
				{
					$listingObject->label_unpublished = false;
				}

				
				$listingObject->label_pending = false;
				$nowDate                      = JFactory::getDate()->toSql();
				if (intval($listingObject->publish_up) > 0)
				{
					if (strtotime($listingObject->publish_up) > strtotime($nowDate))
					{
						$listingObject->label_pending = true;
					}
				}

				
				$listingObject->label_expired = false;
				if (intval($listingObject->publish_down) > 0)
				{
					if (intval($listingObject->publish_up) > 0)
					{
						if (strtotime($listingObject->publish_up) <= strtotime($nowDate))
						{
							if (strtotime($listingObject->publish_down) < strtotime($nowDate))
							{
								$listingObject->label_expired = true;
							}
						}
					}
					else
					{
						if (strtotime($listingObject->publish_down) < strtotime($nowDate))
						{
							$listingObject->label_expired = true;
						}
					}
				}

				
				if ($params->get('show_new_label', 1) && JUDirectoryFrontHelper::isWithinXDays($listingObject->publish_up, $params->get('num_day_to_show_as_new', 10)))
				{
					$listingObject->label_new = true;
				}
				else
				{
					$listingObject->label_new = false;
				}

				
				if ($params->get('show_updated_label', 1) && JUDirectoryFrontHelper::isWithinXDays($listingObject->updated, $params->get('num_day_to_show_as_updated', 10)))
				{
					$listingObject->label_updated = true;
				}
				else
				{
					$listingObject->label_updated = false;
				}

				
				if ($params->get('show_hot_label', 1) && JUDirectoryFrontHelperListing::checkHotListing($listingObject->publish_up, $params->get('num_hit_per_day_to_be_hot', 100), $listingObject->hits))
				{
					$listingObject->label_hot = true;
				}
				else
				{
					$listingObject->label_hot = false;
				}

				
				if ($params->get('show_featured_label', 1) && $listingObject->featured)
				{
					$listingObject->label_featured = true;
				}
				else
				{
					$listingObject->label_featured = false;
				}
			}
		}
	}

	
	public static function getDirectory($name, $default = '', $urlPath = false)
	{
		$storeId = md5(__METHOD__ . "::$name::$default::" . (int) $urlPath);
		if (!isset(self::$cache[$storeId]))
		{
			$params    = JUDirectoryHelper::getParams();
			$directory = $params->get($name, $default);
			
			if (substr($directory, -1) != "/")
			{
				$directory = $directory . "/";
			}

			$directory = JPath::clean($directory);

			if ($urlPath)
			{
				$directory = str_replace(DIRECTORY_SEPARATOR, "/", $directory);
			}

			self::$cache[$storeId] = $directory;
		}

		return self::$cache[$storeId];
	}

	
	public static function getDashboardUserId()
	{
		$app  = JFactory::getApplication();
		$view = $app->input->getString('view', '');
		if ($view == 'collection')
		{
			$userId = $app->input->getInt('user_id', 0);
			if (!$userId)
			{
				$collectionId  = $app->input->getInt('id', 0);
				$collectionObj = JUDirectoryFrontHelper::getCollectionById($collectionId);
				if (!empty($collectionObj))
				{
					$userId = $collectionObj->created_by;
				}
			}
		}
		else
		{
			$userId = ($view == 'modpermission') ? 0 : $app->input->getInt('id', 0);
			if (!$userId)
			{
				$user   = JFactory::getUser();
				$userId = $user->id;
			}
		}

		return $userId;
	}

	
	public static function getUser($user, $joinJUUserTbl = false)
	{
		$storeId = md5(__METHOD__ . "::$user::" . (int) $joinJUUserTbl);
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__users AS ua');
			if ($joinJUUserTbl)
			{
				$query->join('LEFT', '#__judirectory_users AS juua ON ua.id = juua.id');
			}

			if (is_numeric($user))
			{
				$query->where('ua.id = ' . $user);
			}
			else
			{
				$query->where('ua.username = ' . $db->quote($user));
			}
			$db->setQuery($query);
			$userObj = $db->loadObject();
			if (is_null($userObj))
			{
				$userObj = false;
			}
			self::$cache[$storeId] = $userObj;
		}

		return self::$cache[$storeId];
	}

	
	public static function getCollectionById($collectionId)
	{
		$storeId = md5(__METHOD__ . "::$collectionId");
		if (!isset(self::$cache[$storeId]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select("*");
			$query->from("#__judirectory_collections");
			$query->where("id = " . $collectionId);
			$db->setQuery($query);
			self::$cache[$storeId] = $db->loadObject();
		}

		return self::$cache[$storeId];
	}

	
	public static function validateImageFile($file)
	{
		$app = JFactory::getApplication();
		if (empty($file['name']))
		{
			return false;
		}
		if (!JFile::exists($file['tmp_name']))
		{
			$app->enqueueMessage(JText::_('COM_JUDIRECTORY_FILE_NOT_FOUND'), 'error');

			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		
		$executable = array(
			'php', 'js', 'exe', 'phtml', 'java', 'perl', 'py', 'asp', 'dll', 'go', 'ade', 'adp', 'bat', 'chm', 'cmd', 'com', 'cpl', 'hta', 'ins', 'isp',
			'jse', 'lib', 'mde', 'msc', 'msp', 'mst', 'pif', 'scr', 'sct', 'shb', 'sys', 'vb', 'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh'
		);

		$explodedFileName = explode('.', $file['name']);
		if (count($explodedFileName) > 2)
		{
			foreach ($executable AS $extensionName)
			{
				if (in_array($extensionName, $explodedFileName))
				{
					$app->enqueueMessage(JText::_('COM_JUDIRECTORY_INVALID_FILE_TYPE'), 'error');

					return false;
				}
			}
		}

		$params = JUDirectoryHelper::getParams();

		$allowable = $params->get('upload_extensions', 'bmp,gif,jpg,png');
		$allowable = explode(',', strtolower(str_replace("\n", ",", trim($allowable))));
		if ($format == '' || $format == false || (!in_array($format, $allowable)))
		{
			$app->enqueueMessage(JText::sprintf('COM_JUDIRECTORY_INVALID_FILE_TYPE', $format), 'error');

			return false;
		}

		
		$maxSize          = (int) ($params->get('image_max_size', 400) * 1024);
		$maxSizeFormatted = JUDirectoryHelper::formatBytes($maxSize);
		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$app->enqueueMessage(JText::sprintf('COM_JUDIRECTORY_REACH_MAX_FILE_SIZE', $maxSizeFormatted), 'error');

			return false;
		}

		$imgInfo = null;

		
		if (!empty($file['tmp_name']))
		{
			if (($imgInfo = getimagesize($file['tmp_name'])) === false)
			{
				$app->enqueueMessage(JText::_('COM_JUDIRECTORY_INVALID_IMAGE_FILE'), 'error');

				return false;
			}
		}
		else
		{
			$app->enqueueMessage(JText::sprintf('COM_JUDIRECTORY_REACH_MAX_FILE_SIZE', $maxSizeFormatted), 'error');

			return false;
		}

		$xss_check = JFile::read($file['tmp_name'], false, 256);
		$html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');
		foreach ($html_tags AS $tag)
		{
			
			if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>'))
			{
				$app->enqueueMessage(JText::_('COM_JUDIRECTORY_IEXSS_WARNING'), 'error');

				return false;
			}
		}

		return true;
	}

	
	public static function getFeed($feedListings, &$document)
	{
		$db     = JFactory::getDbo();
		$app    = JFactory::getApplication();
		$params = JUDirectoryHelper::getParams();

		$image     = $params->get('rss_thumbnail_source', 'image');
		$feedEmail = $params->get('rss_email', 'none');
		$siteEmail = $app->get('mailfrom');

		foreach ($feedListings AS $listing)
		{
			$title = $listing->title;
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			$categoryId = JUDirectoryFrontHelperCategory::getMainCategoryId($listing->id);
			$category   = JUDirectoryFrontHelperCategory::getCategory($categoryId);

			$feedItem        = new JFeedItem();
			$feedItem->title = $title;
			$feedItem->link  = JRoute::_(JUDirectoryHelperRoute::getListingRoute($listing->id));
			if ($feedEmail != 'none')
			{
				$user             = JFactory::getUser($listing->created_by);
				$feedItem->author = $listing->created_by_alias ? $listing->created_by_alias : $user->name;
				
				if ($feedEmail == 'site')
				{
					$feedItem->authorEmail = $siteEmail;
				}
				elseif ($feedEmail === 'author')
				{
					$feedItem->authorEmail = $listing->email ? $listing->email : $user->email;
				}
			}

			$feedItem->category = $category->title;
			@$date = $listing->publish_up ? date('r', strtotime($listing->publish_up)) : '';
			$feedItem->date        = $date;
			$feedItem->description = "";

			if ($params->get('rss_show_thumbnail', 1))
			{
				
				if ($image == 'image' && $listing->image)
				{
					$imageUrl = JUDirectoryHelper::getListingImage($listing->image);
				}
				
				else
				{
					$query = "SELECT file_name FROM #__judirectory_images WHERE listing_id = " . $listing->id . " ORDER BY ordering ASC LIMIT 1";
					$db->setQuery($query);
					$firstImage = $db->loadResult();
					$imageUrl   = JUri::root() . JUDirectoryFrontHelper::getDirectory("listing_small_image_directory", "media/com_judirectory/images/gallery/small/", true) . $firstImage;
				}
				$feedItem->description = "<img src='" . $imageUrl . "' align=\"" . $params->get('rss_thumbnail_alignment', 'left') . "\" />";
			}
			$feedItem->description .= $listing->introtext;
			$document->addItem($feedItem);
		}
	}

	
	public static function isEmailExisted($email)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("COUNT(*)");
		$query->from('#__users');
		$query->where('email = ' . $db->quote($email));
		$db->setQuery($query);
		$total = $db->loadResult();
		if ($total)
		{
			return true;
		}

		return false;
	}

	
	public static function getIpAddress()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
		{
			$ipaddress = getenv('HTTP_CLIENT_IP');
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			}
			else
			{
				if (getenv('HTTP_X_FORWARDED'))
				{
					$ipaddress = getenv('HTTP_X_FORWARDED');
				}
				else
				{
					if (getenv('HTTP_FORWARDED_FOR'))
					{
						$ipaddress = getenv('HTTP_FORWARDED_FOR');
					}
					else
					{
						if (getenv('HTTP_FORWARDED'))
						{
							$ipaddress = getenv('HTTP_FORWARDED');
						}
						else
						{
							if (getenv('REMOTE_ADDR'))
							{
								$ipaddress = getenv('REMOTE_ADDR');
							}
							else
							{
								$ipaddress = '';
							}
						}
					}
				}
			}
		}

		return $ipaddress;
	}

	
	public static function UrlEncode($string)
	{
		
		$string = urlencode($string);

		$params = JUDirectoryHelper::getParams();
		$string = preg_replace('/' . $params->get('sef_space', '-') . '/', "%252D", $string);
		$string = preg_replace('/\+/', $params->get('sef_space', '-'), $string);
		

		foreach (self::$sef_replace AS $key => $value)
		{
			$string = preg_replace('/' . $key . '/', $value, $string);
		}

		return $string;
	}

	
	public static function UrlDecode($string)
	{
		foreach (self::$sef_replace AS $key => $value)
		{
			$string = str_replace($value, urldecode($key), $string);
		}

		$params = JUDirectoryHelper::getParams();
		$string = preg_replace('/' . $params->get('sef_space', '-') . '/', "%20", $string);
		
		$string = preg_replace('/&quot;/', "%22", $string);
		$string = preg_replace("/%252D/", $params->get('sef_space', '-'), $string);

		
		$string = urldecode($string);

		return $string;
	}

	
	public static function customLimitBox()
	{
		$params      = JUDirectoryHelper::getParams();
		$limitString = $params->get('limit_string', '5,10,15,20,25,30,50');
		$limitArray  = array();
		if ($limitString != '')
		{
			if (strpos($limitString, ',') != false)
			{
				$limitArray = explode(",", $limitString);
			}

			if (is_array($limitArray) && count($limitArray) > 0)
			{
				array_unique($limitArray);
				foreach ($limitArray AS $limitKey => $limitValue)
				{
					if (!is_numeric($limitValue) || $limitValue < 0)
					{
						unset($limitArray[$limitKey]);
					}
				}
			}
		}

		return $limitArray;
	}

	public static function BBCode2Html($text)
	{
		$text = trim($text);

		
		if (!function_exists('escape'))
		{
			function escape($s)
			{
				global $text;
				$text = strip_tags($text);
				$code = $s[1];
				$code = htmlspecialchars($code);
				$code = str_replace("[", "&#91;", $code);
				$code = str_replace("]", "&#93;", $code);

				return '<pre><code>' . $code . '</code></pre>';
			}
		}
		$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);

		

		$in          = array(
			'3:)', ':)', ':(', ':P', ':D',
			'&gt;:o', ':o', ';)', ':-/', ':v', ':\'(',
			'^_^', '8-)', '&lt;3', '-_-', 'o.O', ':3', '(y)'

		);
		$smileFolder = JUri::root(true) . '/components/com_judirectory/assets/wysibb/theme/default/img/smiles/';
		
		$out  = array(
			'<img  src="' . $smileFolder . 'devil.png" />',
			'<img  src="' . $smileFolder . 'smile.png" />',
			'<img  src="' . $smileFolder . 'frown.png" />',
			'<img  src="' . $smileFolder . 'tongue.png" />',
			'<img  src="' . $smileFolder . 'grin.png" />',
			'<img  src="' . $smileFolder . 'angry.png" />',
			'<img  src="' . $smileFolder . 'gasp.png" />',
			'<img  src="' . $smileFolder . 'wink.png" />',
			'<img  src="' . $smileFolder . 'unsure.png" />',
			'<img  src="' . $smileFolder . 'pacman.png" />',
			'<img  src="' . $smileFolder . 'cry.png" />',
			'<img  src="' . $smileFolder . 'kiki.png" />',
			'<img  src="' . $smileFolder . 'glasses.png" />',
			'<img  src="' . $smileFolder . 'heart.png" />',
			'<img  src="' . $smileFolder . 'squinting.png" />',
			'<img  src="' . $smileFolder . 'confused.png" />',
			'<img  src="' . $smileFolder . 'colonthree.png" />',
			'<img  src="' . $smileFolder . 'like.png" />',
		);
		$text = str_replace($in, $out, $text);

		
		$in = array(
			'/\[b\](.*?)\[\/b\]/ms',
			'/\[i\](.*?)\[\/i\]/ms',
			'/\[u\](.*?)\[\/u\]/ms',
			'/\[align=(.*?)\](.*?)\[\/align\]/ms',
			'/\[img\](.*?)\[\/img\]/ms',
			'/\[email\](.*?)\[\/email\]/ms',
			'/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
			'/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
			'/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
			'/\[background\="?(.*?)"?\](.*?)\[\/background\]/ms',
			'/\[list\=(.*?)\](.*?)\[\/list\]/ms',
			'/\[list\](.*?)\[\/list\]/ms',
			'/\[\*\]([^\[\]\*\<]*)/ms',
			'/\[left\](.*?)\[\/left\]/ms',
			'/\[right\](.*?)\[\/right\]/ms',
			'/\[center\](.*?)\[\/center\]/ms'
		);
		
		$out  = array('<strong>\1</strong>',
			'<em>\1</em>',
			'<u>\1</u>',
			'<p style="\1">\2</p>',
			'<img src="\1" alt="\1" />',
			'<a href="mailto:\1">\1</a>',
			'<a href="\1" rel="nofollow">\2</a>',
			'<span style="font-size:\1%">\2</span>',
			'<span style="color:\1">\2</span>',
			'<span style="background-color:\1">\2</span>',
			'<ol start="\1">\2</ol>',
			'<ul>\1</ul>',
			'<li>\1</li>',
			'<p style="text-align:left">\1</p>',
			'<p style="text-align:right">\1</p>',
			'<p style="text-align:center">\1</p>'
		);
		$text = preg_replace($in, $out, $text);

		$quoteFind    = array(
			
			'/\[quote\]/is',
			'/\[\/quote\]/is',
			'/\[quote\s*=\s*"?([^"]*?)"?\s*\]/is'
		);
		$quoteReplace = array(
			'<blockquote>',
			'</blockquote>',
			'<span class="author">$1 said:</span><blockquote>'
		);
		
		$count = 0;
		do
		{
			$text = preg_replace($quoteFind, $quoteReplace, $text, -1, $count);
		} while ($count > 0);

		
		$videoPatt = '/\[video\](.*?)\[\/video\]/ms';
		preg_match($videoPatt, $text, $matches);

		if ($matches && $matches[1])
		{
			$vimeo_html = JUDirectoryFrontHelperComment::parseVideo($matches[1]);
			$text       = str_replace($matches[0], $vimeo_html, $text);
		}
		

		
		$text = str_replace("\r", "", $text);
		$text = "<p>" . preg_replace("/(\n){2,}/", "</p><p>", $text) . "</p>";
		$text = nl2br($text);

		
		
		if (!function_exists('removeBr'))
		{
			function removeBr($s)
			{
				return str_replace("<br />", "", $s[0]);
			}
		}
		$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
		$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);

		$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
		$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);

		return $text;
	}

	
	public static function getTotalListingCompare()
	{
		require_once JPATH_SITE . '/components/com_judirectory/models/compare.php';
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_judirectory/models');
		$modelCompare = JModelLegacy::getInstance('Compare', 'JUDirectoryModel');
		$items        = $modelCompare->getItems();
		if (is_array($items) && count($items))
		{
			return count($items);
		}

		return 0;
	}

	public static function getLocations($listings)
	{
		$locations  = array();
		$listingIds = array();
		if ($listings)
		{
			foreach ($listings as $listing)
			{
				$listingIds[] = $listing->id;
			}

			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('location.*, listing.image AS listing_image, listing.title')
				->from('#__judirectory_locations AS location')
				->join('', '#__judirectory_listings AS listing ON listing.id = location.listing_id')
				->where('listing.id IN (' . implode(',', $listingIds) . ')');

			$db->setQuery($query);

			$locations       = $db->loadObjectList();
			$locationIconUrl = JUri::root(true) . '/media/com_judirectory/images/location/';

			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
			$addressTable = JTable::getInstance('Address', 'JUDirectoryTable');

			foreach ($locations as $location)
			{
				if ($location->image)
				{
					$location->image = $locationIconUrl . $location->listing_id . '/' . $location->image;
				}
				else
				{
					$location->image = JUDirectoryHelper::getListingImage($location->listing_image);
				}

				$addresses             = self::getAddresses($addressTable, $location->address_id);
				$addresses[]           = $location->address;
				$location->address     = implode(', ', array_reverse($addresses));
				$location->link        = JRoute::_(JUDirectoryHelperRoute::getListingRoute($location->listing_id));
				$location->description = nl2br($location->description);
			}
		}

		return $locations;
	}

	protected static function getAddresses($addressTable, $addressId)
	{
		$addressArr = array();
		if ($addressId)
		{
			if ($addressTable->load($addressId))
			{
				$addresses = $addressTable->getPath();
				foreach ($addresses as $address)
				{
					if ($address->id == 1) continue;
					$addressArr[] = $address->title;
				}
			}
		}

		return $addressArr;
	}

} 