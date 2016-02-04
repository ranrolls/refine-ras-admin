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

class JUDirectoryModelListing extends JUDIRModelList
{
	protected $cache = array();

	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		
		$listingId = $app->input->getInt('id', 0);
		$this->setState('listing.id', $listingId);
		
		if ($this->context)
		{
			
			$app = JFactory::getApplication();

			
			$params = JUDirectoryHelper::getParams(null, $listingId);
			$this->setState('params', $params);

			
			$this->setState('list.start', $app->input->getUInt('limitstart', 0));

			
			$commentPagination = $params->get('comment_pagination', 10);

			if ($params->get('show_comment_pagination', 0))
			{
				
				$limitArray = JUDirectoryFrontHelper::customLimitBox();
				if (is_array($limitArray) && count($limitArray))
				{
					
					$limit = $app->input->getUint('limit', null);
					if (is_null($limit) || in_array($limit, $limitArray))
					{
						
						$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $commentPagination, 'uint');
					}
					else
					{
						
						$limit = $commentPagination;
					}
				}
				else
				{
					
					$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $commentPagination, 'uint');
				}

				
				$this->setState('list.limit', $limit);
			}
			else
			{
				$limit = $commentPagination;
				$this->setState('list.limit', $limit);
			}

			if ($params->get('show_comment_direction', 1))
			{
				
				$defaultOrdering = $params->get('comment_ordering', 'cm.created');
				$orderCol        = $app->getUserStateFromRequest($this->context . '.list.filter_order', 'filter_order', $defaultOrdering, 'string');

				
				$commentOrdering = $this->getCommentOrderingOptions();
				$commentOrdering = array_keys($commentOrdering);
				if (!in_array($orderCol, $commentOrdering))
				{
					$orderCol = 'cm.created';
				}

				
				$this->setState('list.ordering', $orderCol);

				
				$defaultDirection = $params->get('comment_direction', 'DESC');
				$orderDirection   = $app->getUserStateFromRequest($this->context . '.list.filter_order_Dir',
					'filter_order_Dir', $defaultDirection, 'cmd');

				
				if (!in_array(strtoupper($orderDirection), array('ASC', 'DESC', '')))
				{
					$orderDirection = 'DESC';
				}

				
				$this->setState('list.direction', $orderDirection);
			}

			
			if ($params->get('filter_comment_language', 0))
			{
				$filterLang = $app->getUserStateFromRequest($this->context . '.list.filter_lang', 'filter_lang', '*', 'string');
				$this->setState('list.lang', $filterLang);
			}

			
			if ($params->get('filter_comment_rating', 1))
			{
				$starFilter = $app->getUserStateFromRequest($this->context . '.list.star_filter', 'star_filter', '');

				
				$resetFilter = $app->input->getInt('resetfilter', 0);
				if ($resetFilter == 1)
				{
					$this->setState('list.star_filter', '');
				}
				else
				{
					$this->setState('list.star_filter', $starFilter);
				}
			}
		}
		else
		{
			$this->setState('list.start', 0);
			$this->state->set('list.limit', 0);
		}
	}

	
	public function getTotalCommentsOfListing($listingId)
	{
		$params = $this->getState('params');

		
		$mainCategoryId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);

		
		$rootComment = JUDirectoryFrontHelperComment::getRootComment();

		$user  = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__judirectory_comments');
		$query->where('approved = 1');
		$query->where('parent_id = ' . $rootComment->id);
		$query->where('listing_id = ' . $listingId);

		$getAll      = false;
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$moderator = JUDirectoryFrontHelperModerator::getModerator($mainCategoryId);
			
			if ($user->authorise('core.admin', 'com_judirectory') || ($moderator && ($moderator->comment_edit || $moderator->comment_edit_state || $moderator->comment_delete)))
			{
				$getAll = true;
			}
		}

		if (!$getAll)
		{
			$query->where('published = 1');
			$negative_vote_comment = $params->get('negative_vote_comment');
			if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
			{
				$query->where('(total_votes - helpful_votes) < ' . $negative_vote_comment);
			}
		}
		$db->setQuery($query);

		return $db->loadResult();
	}

	
	protected function getListQuery()
	{
		$user = JFactory::getUser();

		$params    = $this->getState('params');
		$listingId = (int) $this->getState('listing.id');
		$ordering  = $params->get('comment_ordering', 'cm.created');
		$direction = $params->get('comment_direction', 'DESC');
		if ($params->get('show_comment_direction', 1))
		{
			$ordering  = $this->getState('list.ordering', $ordering);
			$direction = $this->getState('list.direction', $direction);
		}

		
		$rootComment = JUDirectoryFrontHelperComment::getRootComment();

		
		$mainCategoryId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('cm.*');
		$query->from('#__judirectory_comments AS cm');
		$query->select('r.score');
		$query->select('r.id AS rating_id');
		$query->join('LEFT', '#__judirectory_rating AS r ON cm.rating_id = r.id');
		$query->where('cm.approved = 1');
		$query->where('cm.parent_id = ' . $rootComment->id);
		$query->where('cm.listing_id = ' . $listingId);

		$getAll      = false;
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$moderator = JUDirectoryFrontHelperModerator::getModerator($mainCategoryId);
			
			if ($user->authorise('core.admin', 'com_judirectory') || ($moderator && ($moderator->comment_edit || $moderator->comment_edit_state || $moderator->comment_delete)))
			{
				$getAll = true;
			}
		}

		if (!$getAll)
		{
			$query->where('cm.published = 1');
			$negative_vote_comment = $params->get('negative_vote_comment');
			if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
			{
				$query->where('(cm.total_votes - cm.helpful_votes) <' . $negative_vote_comment);
			}
		}

		
		if ($params->get('filter_comment_language', 0))
		{
			$languageTag = $this->getState('list.lang', '*');
			if ($languageTag != '*' && $languageTag != '')
			{
				$query->where('cm.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
			}
		}

		
		$allowFilterComment = $params->get('filter_comment_rating', 1);
		$commentFilter      = $this->getState('list.star_filter', '');
		if ($allowFilterComment && $commentFilter != '')
		{
			$commentFilter = array_map('trim', explode(',', $commentFilter));
			if (count($commentFilter) == 1)
			{
				$query->where('(r.score = 0 OR r.score IS NULL)');
			}
			else
			{
				$query->where('(r.score > ' . (int) $commentFilter[0] . ' AND r.score <= ' . (int) $commentFilter[1] . ')');
			}
		}

		$query->order($ordering . " " . $direction);

		return $query;
	}

	
	public function getListQueryNoFilter($listingId)
	{
		$mainCategoryId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
		$user           = JFactory::getUser();
		$params         = $this->getState('params');
		$ordering       = $params->get('comment_ordering', 'cm.created');
		$direction      = $params->get('comment_direction', 'DESC');
		if ($params->get('show_comment_direction', 1))
		{
			$ordering  = $this->getState('list.ordering', $ordering);
			$direction = $this->getState('list.direction', $direction);
		}

		
		$rootComment = JUDirectoryFrontHelperComment::getRootComment();

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('cm.id');
		$query->from('#__judirectory_comments AS cm');
		$query->select('r.score');
		$query->join('LEFT', '#__judirectory_rating AS r ON cm.rating_id = r.id');
		$query->where('cm.approved = 1');
		$query->where('cm.parent_id = ' . $rootComment->id);
		$query->where('cm.listing_id = ' . $listingId);

		$getAll      = false;
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$moderator = JUDirectoryFrontHelperModerator::getModerator($mainCategoryId);
			
			if ($user->authorise('core.admin', 'com_judirectory') || ($moderator && ($moderator->comment_edit || $moderator->comment_edit_state || $moderator->comment_delete)))
			{
				$getAll = true;
			}
		}

		if (!$getAll)
		{
			$query->where('cm.published = 1');
			$negative_vote_comment = $params->get('negative_vote_comment');
			if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
			{
				$query->where('(cm.total_votes - cm.helpful_votes) <' . $negative_vote_comment);
			}
		}
		$query->order($ordering . " " . $direction);

		return $query;
	}

	
	public function getTopLevelCommentId($commentId)
	{
		
		$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId);

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__judirectory_comments');
		$query->where('level = 1');
		$query->where($commentObject->lft . ' BETWEEN lft AND rgt');
		$db->setQuery($query);

		return $db->loadResult();
	}

	
	public function getLimitStartForComment($commentId)
	{
		$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId);
		if ($commentObject->id > 0 && $commentObject->approved == 1)
		{
			$params = $this->getState('params');

			if ($commentObject->level > 1)
			{
				$topLevelCommentId = $this->getTopLevelCommentId($commentObject->id);
			}
			else
			{
				$topLevelCommentId = $commentObject->id;
			}

			if ($topLevelCommentId)
			{
				$storeId = md5(__METHOD__ . "::" . $commentObject->listing_id);
				if (!isset($this->cache[$storeId]))
				{
					$db    = $this->getDbo();
					$query = $this->getListQueryNoFilter($commentObject->listing_id);
					$db->setQuery($query);
					$this->cache[$storeId] = $db->loadColumn();
				}
				$commentIdArray = $this->cache[$storeId];

				$commentIndex = array_search($topLevelCommentId, $commentIdArray);

				if ($commentIndex >= 0)
				{
					$commentPagination = $params->get('comment_pagination', 10);
					$limit             = (int) $this->getState('list.limit', $commentPagination);
					$limitStart        = $commentIndex - ($commentIndex % $limit);

					return $limitStart;
				}
			}
		}

		return false;
	}

	
	public function getItems()
	{
		$user      = JFactory::getUser();
		$token     = JSession::getFormToken();
		$items     = parent::getItems();
		$listingId = (int) $this->getState('listing.id');
		$params    = $this->getState('params');
		if (count($items) > 0)
		{
			$commentsRecursive = array();
			foreach ($items AS $item)
			{
				$commentsRecursive[] = $item;
				$commentsRecursive   = array_merge($commentsRecursive, $this->getCommentRecursive($item->id));
			}

			$items = $commentsRecursive;
		}

		foreach ($items AS $item)
		{
			$item->comment_edit = $item->comment;
			$item->comment      = JUDirectoryFrontHelper::BBCode2Html($item->comment);
			$item->comment      = JUDirectoryFrontHelperComment::parseCommentText($item->comment, $listingId);

			
			$item->can_reply     = JUDirectoryFrontHelperPermission::canReplyComment($listingId, $item->id);
			$item->can_vote      = JUDirectoryFrontHelperPermission::canVoteComment($listingId, $item->id);
			$item->can_report    = JUDirectoryFrontHelperPermission::canReportComment($listingId, $item->id);
			$item->can_subscribe = false;
			$item->can_edit      = false;
			$item->can_delete    = false;
			$isOwnerComment      = JUDirectoryFrontHelperPermission::isCommentOwner($item->id);
			if ($isOwnerComment)
			{
				$item->can_edit    = JUDirectoryFrontHelperPermission::canEditComment($item->id);
				$item->can_delete  = JUDirectoryFrontHelperPermission::canDeleteComment($item->id);
				$item->link_delete = JRoute::_('index.php?option=com_judirectory&task=listing.deleteComment&comment_id=' . $item->id . '&' . $token . '=1');

				if ($params->get('can_subscribe_own_comment', 1))
				{
					$item->can_subscribe = true;
					if ($this->isSubscriber($user->id, $item->id, 'comment'))
					{
						$item->is_subscriber = true;
						$secret              = JFactory::getConfig()->get('secret');
						$type                = 'comment';
						$code                = md5($user->id . $user->email . $type . $secret);

						$subscriptionObject = JUDirectoryFrontHelper::getSubscriptionObjectByType($user->id, $item->id, $type);

						$item->subscribe_link = JRoute::_('index.php?option=com_judirectory&task=subscribe.remove&sub_id=' . (int) $subscriptionObject->id .
							'&code=' . $code . '&' . $token . '=1');

					}
					else
					{
						$item->is_subscriber  = false;
						$item->subscribe_link = JRoute::_('index.php?option=com_judirectory&task=subscribe.save' .
							'&comment_id=' . $item->id . '&' . $token . '=1');
					}
				}
			}

			$item->voted_value = $this->getCommentVotedValue($item->id);
		}

		return $items;
	}

	
	public function getStart()
	{
		return $this->getState('list.start');
	}

	
	public function getCommentRecursive($parentId)
	{
		$params    = $this->getState('params');
		$ordering  = $params->get('comment_ordering', 'cm.created');
		$direction = $params->get('comment_direction', 'DESC');
		if ($params->get('show_comment_direction', 1))
		{
			$ordering  = $this->getState('list.ordering', $ordering);
			$direction = $this->getState('list.direction', $direction);
		}

		$listingId      = (int) $this->getState('listing.id');
		$mainCategoryId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
		$maxLevel       = $params->get('max_comment_level', 5);
		$user           = JFactory::getUser();

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('cm.*');
		$query->from('#__judirectory_comments AS cm');

		$query->join('', '#__judirectory_listings AS listing ON listing.id = cm.listing_id');
		$query->join('', '#__judirectory_listings_xref AS listingxref ON listingxref.listing_id = listing.id AND listingxref.main = 1');

		$query->where('cm.approved = 1');
		$query->where('cm.parent_id = ' . $parentId);

		$getAll      = false;
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$moderator = JUDirectoryFrontHelperModerator::getModerator($mainCategoryId);
			
			if ($user->authorise('core.admin', 'com_judirectory') || ($moderator && ($moderator->comment_edit || $moderator->comment_edit_state || $moderator->comment_delete)))
			{
				$getAll = true;
			}
		}

		if (!$getAll)
		{
			$query->where('cm.published = 1');
			$negative_vote_comment = $params->get('negative_vote_comment');
			if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
			{
				$query->where('(cm.total_votes - cm.helpful_votes) <' . $negative_vote_comment);
			}
		}

		if ($ordering != 'r.score')
		{
			$query->order($ordering . " " . $direction);
		}

		$db->setQuery($query);

		$commentObjectList = $db->loadObjectList();
		$recursiveComment  = array();
		if (count($commentObjectList) > 0)
		{
			foreach ($commentObjectList AS $commentObject)
			{
				if ($commentObject->level <= $maxLevel)
				{
					$recursiveComment[] = $commentObject;
					
					if ($commentObject->rgt > $commentObject->lft + 1)
					{
						$recursiveComment = array_merge($recursiveComment, $this->getCommentRecursive($commentObject->id));
					}
				}
			}
		}

		return $recursiveComment;
	}

	
	public function getTotalChildComments($parentId)
	{
		$listingId      = (int) $this->getState('listing.id');
		$mainCategoryId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
		$user           = JFactory::getUser();
		$params         = $this->getState('params');
		$db             = $this->getDbo();
		$query          = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__judirectory_comments AS cm');
		$query->where('cm.approved = 1');
		$query->where('cm.parent_id = ' . $parentId);
		$maxLevel = $params->get('max_comment_level', 5);
		$query->where('cm.level <= ' . $maxLevel);

		$getAll      = false;
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			$moderator = JUDirectoryFrontHelperModerator::getModerator($mainCategoryId);
			
			if ($user->authorise('core.admin', 'com_judirectory') || ($moderator && ($moderator->comment_edit || $moderator->comment_edit_state || $moderator->comment_delete)))
			{
				$getAll = true;
			}
		}

		if (!$getAll)
		{
			$query->where('cm.published = 1');
			$negative_vote_comment = $params->get('negative_vote_comment');
			if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
			{
				$query->where('(cm.total_votes - cm.helpful_votes) <' . $negative_vote_comment);
			}
		}
		$db->setQuery($query);

		return $db->loadResult();
	}

	
	public function getItem()
	{
		$params = $this->getState('params');

		
		$listingId = (int) $this->getState('listing.id');
		
		if (!$listingId)
		{
			JError::raiseError(404, JText::_('COM_JUDIRECTORY_LISTING_NOT_FOUND'));

			return false;
		}

		
		$user = JFactory::getUser();
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('listing.*, c.id AS cat_id');
		$query->from('#__judirectory_listings AS listing');

		$query->join('', '#__judirectory_listings_xref AS listingxref ON listing.id = listingxref.listing_id AND listingxref.main=1');
		$query->join('', '#__judirectory_categories AS c ON c.id = listingxref.cat_id');

		
		$query->select('(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE cm.listing_id = listing.id AND cm.approved = 1 AND cm.published = 1) AS total_comments');

		
		$query->select('(SELECT COUNT(*) FROM #__judirectory_subscriptions AS sub WHERE sub.item_id = listing.id AND sub.type = "listing" AND sub.published = 1) AS total_subscriptions');

		
		$query->select('(SELECT COUNT(*) FROM #__judirectory_reports AS r WHERE r.item_id = listing.id AND r.type = "listing") AS total_reports');

		
		$query->select('(SELECT GROUP_CONCAT(catids.id ORDER BY listingx_catids.main DESC, listingx_catids.ordering ASC SEPARATOR ",") FROM (#__judirectory_categories AS catids JOIN #__judirectory_listings_xref AS listingx_catids ON catids.id = listingx_catids.cat_id) WHERE listing.id = listingx_catids.listing_id GROUP BY listing.id) AS cat_ids');
		
		$query->select('(SELECT GROUP_CONCAT(cattitles.title ORDER BY listingx_cattitles.main DESC, listingx_cattitles.ordering ASC SEPARATOR "|||") FROM (#__judirectory_categories AS cattitles JOIN #__judirectory_listings_xref AS listingx_cattitles ON cattitles.id = listingx_cattitles.cat_id) WHERE listing.id = listingx_cattitles.listing_id GROUP BY listing.id) AS cat_titles');

		

		
		$accessLevel = implode(',', $user->getAuthorisedViewLevels());
		$db          = JFactory::getDbo();
		$date        = JFactory::getDate();
		$nullDate    = $db->quote($db->getNullDate());
		$nowDate     = $db->quote($date->toSql());

		
		$fieldQuery = $db->getQuery(true);
		$fieldQuery->select('field.id');
		$fieldQuery->from('#__judirectory_fields AS field');
		$fieldQuery->where('field.group_id != 1');
		$fieldQuery->where('field.details_view = 1');

		$fieldQuery->where('field.published = 1');
		$fieldQuery->where('field.publish_up <= ' . $nowDate);
		$fieldQuery->where('(field.publish_down = ' . $nullDate . ' OR field.publish_down > ' . $nowDate . ')');

		$fieldQuery->where('field.access IN (' . $accessLevel . ')');

		$category = JUDirectoryFrontHelperCategory::getMainCategory($listingId);
		if (is_object($category))
		{
			$fieldQuery->where('field.group_id = ' . $category->fieldgroup_id);
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

		$query->where('listing.id = ' . $listingId);
		$db->setQuery($query);
		$listingObject = $db->loadObject();
		
		if (!is_object($listingObject))
		{
			JError::raiseError(404, JText::_('COM_JUDIRECTORY_LISTING_NOT_FOUND'));

			return false;
		}

		
		$listingObject->params = JUDirectoryFrontHelperListing::getListingDisplayParams($listingObject->id);

		
		if (!$user->get('guest'))
		{
			$canEditListing      = JUDirectoryFrontHelperPermission::canEditListing($listingObject->id);
			$canDeleteListing    = JUDirectoryFrontHelperPermission::canDeleteListing($listingObject->id);
			$canEditStateListing = JUDirectoryFrontHelperPermission::canEditStateListing($listingObject);
			$listingObject->params->set('access-edit', $canEditListing);
			$listingObject->params->set('access-edit-state', $canEditStateListing);
			$listingObject->params->set('access-delete', $canDeleteListing);
		}

		
		$canReportListing  = JUDirectoryFrontHelperPermission::canReportListing($listingObject->id);
		$canContactListing = JUDirectoryFrontHelperPermission::canContactListing($listingObject->id);
		$canClaimListing   = JUDirectoryFrontHelperPermission::canClaimListing($listingObject->id);
		$canRateListing    = JUDirectoryFrontHelperPermission::canRateListing($listingObject->id);
		$canCommentListing = JUDirectoryFrontHelperPermission::canComment($listingObject->id);

		$listingObject->params->set('access-report', $canReportListing);
		$listingObject->params->set('access-contact', $canContactListing);
		$listingObject->params->set('access-claim', $canClaimListing);
		$listingObject->params->set('access-rate', $canRateListing);
		$listingObject->params->set('access-comment', $canCommentListing);

		$listingObject->template_params = new JRegistry($listingObject->template_params);

		
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

		
		$listingObject->next_item = $this->getNextPrevItem($listingObject, 'next');
		$listingObject->prev_item = $this->getNextPrevItem($listingObject, 'prev');

		return $listingObject;
	}

	
	protected function getNextPrevItem($listing, $type = 'next')
	{
		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		$user      = JFactory::getUser();
		$levels    = $user->getAuthorisedViewLevels();
		$levelsStr = implode(',', $levels);

		$app  = JFactory::getApplication();
		$lang = JFactory::getLanguage();

		
		$query = $db->getQuery(true);

		
		$query->select('listing.id, listing.title');
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

		
		$query->join('', '#__judirectory_listings_xref AS listingxref ON listing.id = listingxref.listing_id AND listingxref.main = 1');
		$query->join('', '#__judirectory_categories AS c ON c.id = listingxref.cat_id');
		$query->where('c.id = ' . $listing->cat_id);

		if ($type == 'prev')
		{
			$query->where('listing.id < ' . $listing->id);
			$query->order('listing.id DESC');
		}
		else
		{
			$query->where('listing.id > ' . $listing->id);
			$query->order('listing.id ASC');
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

		
		if ($app->getLanguageFilter())
		{
			$query->where('listing.language IN (' . $db->quote($lang->getTag()) . ',' . $db->quote('*') . ',' . $db->quote('') . ')');
		}

		$db->setQuery($query, 0, 1);

		$nextPrevItem = $db->loadObject();
		if ($nextPrevItem)
		{
			$nextPrevItem->link = JRoute::_(JUDirectoryHelperRoute::getListingRoute($nextPrevItem->id));
		}

		return $nextPrevItem;
	}

	
	public function isSubscriber($userId, $subscribeId, $type)
	{
		if ($userId == 0)
		{
			return false;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__judirectory_subscriptions');
		$query->where('user_id = ' . (int) $userId);
		$query->where('item_id = ' . (int) $subscribeId);
		$query->where('type = ' . $db->quote($type));
		$db->setQuery($query);
		$result = $db->loadResult();
		if ($result)
		{
			return true;
		}

		return false;
	}


	
	public function getRelatedListings($listingId)
	{
		
		$user      = JFactory::getUser();
		$levels    = $user->getAuthorisedViewLevels();
		$levelsStr = implode(',', $levels);

		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();

		$app  = JFactory::getApplication();
		$lang = JFactory::getLanguage();

		$params             = $this->getState('params');
		$maxRelatedListings = (int) $params->get('max_related_listings', 12);

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('listing.*, cmain.id AS cat_id');
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

		
		$query->join('', '#__judirectory_listings_relations AS listingrel ON listing.id = listingrel.listing_id_related');
		$query->where('listingrel.listing_id =' . $listingId);
		$query->where('listing.id !=' . $listingId);

		
		$query->where('listing.approved = 1');

		
		$query->where('listing.published = 1');

		$query->where('listing.publish_up <= ' . $db->quote($nowDate));
		$query->where('(listing.publish_down = ' . $db->quote($nullDate) . ' OR listing.publish_down > ' . $db->quote($nowDate) . ')');

		
		if ($user->get('guest'))
		{
			$query->where('listing.access IN (' . $levelsStr . ')');
		}
		else
		{
			$query->where('(listing.access IN (' . $levelsStr . ') OR (listing.created_by = ' . $user->id . '))');
		}

		if ($app->getLanguageFilter())
		{
			$query->where('listing.language IN (' . $db->quote($lang->getTag()) . ',' . $db->quote('*') . $db->quote('') . ')');
		}

		
		$order = $params->get('related_listings_ordering', 'listingrel.ordering');
		$dir   = $params->get('related_listings_direction', 'ASC');

		$query->order($order . ' ' . $dir);

		
		if ($maxRelatedListings > 0)
		{
			$db->setQuery($query, 0, $maxRelatedListings);
		}
		else
		{
			$db->setQuery($query);
		}

		return $db->loadObjectList();
	}

	
	public function updateHits($listingId)
	{
		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();
		$query    = $db->getQuery(true);
		$query->update('#__judirectory_listings');
		$query->set('hits = hits + 1');
		$query->where('id = ' . (int) $listingId);
		$query->where('approved = 1');
		$query->where('published = 1');
		$query->where('(publish_up = ' . $db->quote($nullDate) . ' OR publish_up <= ' . $db->quote($nowDate) . ')');
		$query->where('(publish_down = ' . $db->quote($nullDate) . ' OR publish_down >= ' . $db->quote($nowDate) . ')');
		$db->setQuery($query);
		$db->execute();
	}

	
	public function getCommentObject($commentId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_comments');
		$query->where('approved = 1');
		$query->where('published = 1');
		$query->where('id = ' . $commentId);
		$db->setQuery($query);

		return $db->loadObject();
	}

	
	public function saveComment($postData = array())
	{
		
		$user    = JFactory::getUser();
		$nowDate = JFactory::getDate()->toSql();

		$title = htmlspecialchars($postData['title']);
		
		$comment = htmlspecialchars($postData['comment'], ENT_NOQUOTES);

		if ($user->get('guest'))
		{
			$guestName  = strip_tags($postData['guest_name']);
			$guestEmail = strip_tags($postData['guest_email']);
		}
		else
		{
			$guestName  = '';
			$guestEmail = '';
		}

		$website = isset($postData['website']) ? strip_tags($postData['website']) : '';

		$listingId    = (int) $postData['listing_id'];
		$params       = JUDirectoryHelper::getParams(null, $listingId);
		$totalVotes   = 0;
		$helpfulVotes = 0;
		$ipAddress    = JUDirectoryFrontHelper::getIpAddress();
		$parentId     = (int) $postData['parent_id'];
		$rootComment  = JUDirectoryFrontHelperComment::getRootComment();

		
		if ($parentId == $rootComment->id)
		{
			
			$approved = JUDirectoryFrontHelperPermission::canAutoApprovalComment($listingId);
			$level    = 1;
			if ($params->get('filter_comment_language', 0))
			{
				$language = $postData['comment_language'];
			}
			else
			{
				$language = '*';
			}
		}
		else
		{
			
			$approved      = JUDirectoryFrontHelperPermission::canAutoApprovalReplyComment($listingId);
			$parentComment = $this->getCommentObject($parentId);
			$level         = $parentComment->level + 1;
			$language      = '*';
		}

		if ($approved)
		{
			$approved  = 1;
			$published = 1;
		}
		else
		{
			$approved  = 0;
			$published = 0;
		}

		$dataComment = array('title'         => $title, 'comment' => $comment, 'user_id' => $user->id,
		                     'guest_name'    => $guestName, 'guest_email' => $guestEmail, 'website' => $website,
		                     'listing_id'    => $listingId, 'created' => $nowDate, 'total_votes' => $totalVotes,
		                     'helpful_votes' => $helpfulVotes, 'ip_address' => $ipAddress, 'approved' => $approved,
		                     'published'     => $published, 'parent_id' => $parentId, 'level' => $level,
		                     'language'      => $language
		);

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$commentTable = JTable::getInstance('Comment', 'JUDirectoryTable');
		$commentTable->setLocation($postData['parent_id'], 'last-child');
		$commentTable->bind($dataComment);

		
		if (!$commentTable->check() || !$commentTable->store())
		{
			$this->setError($commentTable->getError());

			return false;
		}

		
		$canRateListing = JUDirectoryFrontHelperPermission::canRateListing($listingId);
		if ($canRateListing && $params->get('enable_listing_rate_in_comment_form', 1) && ($commentTable->parent_id == $rootComment->id))
		{
			$postData['approved'] = $approved;
			$criteriaArray        = array();

			
			if (isset($postData['criteria_array']))
			{
				if (JUDirectoryHelper::hasMultiRating())
				{
					$criteriaArray = $postData['criteria_array'];
					$saveRating    = $this->saveRating($postData, $listingId, $criteriaArray, $commentTable->id);
				}
			}
			
			else
			{
				if (isset($postData['ratingValue']))
				{
					$saveRating = $this->saveRating($postData, $listingId, $criteriaArray, $commentTable->id);
				}
			}

			if (!$saveRating)
			{
				$this->setError(JText::_('COM_JUDIRECTORY_SAVE_RATING_FAILED'));

				return false;
			}
		}

		
		if (JUDIRPROVERSION && isset($postData['subscribe']) && $postData['subscribe'])
		{
			$subscriptionData               = array();
			$subscriptionData['user_id']    = $user->id;
			$subscriptionData['type']       = 'comment';
			$subscriptionData['comment_id'] = $commentTable->id;
			$subscriptionData['name']       = ($user->id == 0) ? $guestName : $user->username;
			$subscriptionData['email']      = ($user->id == 0) ? $guestEmail : $user->email;
			$subscriptionData['item_id']    = $commentTable->id;
			$subscriptionData['ip_address'] = $ipAddress;
			$subscriptionData['created']    = $nowDate;
			$subscriptionData['published']  = ($user->id == 0 && $params->get('activate_subscription_by_email', 1)) ? 0 : 1;
			
			require_once JPATH_SITE . '/components/com_judirectory/models/subscribe.php';
			JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_judirectory/models');
			$subscribeModel = JModelLegacy::getInstance('Subscribe', 'JUDirectoryModel');
			if (!$subscribeModel->add($subscriptionData))
			{
				$this->setError(JText::_('COM_JUDIRECTORY_SUBSCRIBE_FAILED'));

				return false;
			}
		}

		
		if ($commentTable->parent_id == $rootComment->id)
		{
			
			JUDirectoryFrontHelperMail::sendEmailByEvent('comment.create', $commentTable->id);
			
			$logData = array(
				'item_id'    => $commentTable->id,
				'listing_id' => $listingId,
				'user_id'    => $user->id,
				'event'      => 'comment.create'
			);

			$commentSubmitType = 'create';
		}
		
		else
		{
			
			JUDirectoryFrontHelperMail::sendEmailByEvent('comment.reply', $commentTable->id);

			
			$logData           = array(
				'user_id'    => $user->id,
				'event'      => 'comment.reply',
				'item_id'    => $commentTable->id,
				'listing_id' => $listingId,
				'value'      => 0,
				'reference'  => $commentTable->parent_id,
			);
			$commentSubmitType = 'reply';
		}

		JUDirectoryFrontHelperLog::addLog($logData);

		
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('judirectory');

		$dispatcher->trigger('onCommentSubmit', array($commentTable, $commentSubmitType));

		return $commentTable->id;
	}


	
	public function checkNameOfGuest($listingId = null)
	{
		$guest_name = trim(JFactory::getApplication()->input->getString('guest_name', ''));
		
		$params         = JUDirectoryHelper::getParams(null, $listingId);
		$forbiddenNames = array_map('trim', explode(",", strtolower(str_replace("\n", ",", $params->get('forbidden_names', '')))));
		if ($forbiddenNames)
		{
			foreach ($forbiddenNames AS $value)
			{
				$pattern = '/' . $value . '/i';
				if (preg_match($pattern, $guest_name))
				{
					$this->setError('COM_JUDIRECTORY_NAME_X_HAS_BEEN_BANNED', $guest_name);

					return false;
				}
			}
		}

		return true;
	}

	
	public function checkEmailOfGuest()
	{
		$guest_email = trim(JFactory::getApplication()->input->getString('guest_email', ''));

		if (JUDirectoryFrontHelper::isEmailExisted($guest_email))
		{
			$this->setError('COM_JUDIRECTORY_EMAIL_HAS_BEEN_REGISTERED');

			return false;
		}

		return true;
	}

	
	public function setSessionCommentForm($listingId)
	{
		$session              = JFactory::getSession();
		$app                  = JFactory::getApplication();
		$criteriaRatingValues = array('judir-comment-rating-single' => $app->input->getFloat('judir_comment_rating_single', 0));
		$mainCatId            = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
		$criteriaArray        = JUDirectoryFrontHelperCriteria::getCriteriasByCatId($mainCatId);

		if (count($criteriaArray))
		{
			foreach ($criteriaArray AS $value)
			{
				$criteriaRatingValues[$value->id] = $app->input->getString('criteria-' . $value->id, '');
			}
		}

		$commentForm                     = array();
		$commentForm['rating']           = $criteriaRatingValues;
		$commentForm['title']            = $app->input->getString('title', '');
		$commentForm['guest_name']       = $app->input->getString('guest_name', '');
		$commentForm['guest_email']      = $app->input->getString('guest_email', '');
		$commentForm['comment']          = $app->input->getString('comment', '');
		$commentForm['parent_id']        = $app->input->getInt('parent_id', '');
		$commentForm['comment_language'] = $app->input->getString('comment_language', '*');

		if ($app->input->getString('website', ''))
		{
			$commentForm['website'] = $app->input->getString('website', '');
		}
		
		$session->set('judirectory_commentform_' . $listingId, $commentForm);
	}

	
	public function getImagesByListingId($listingId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_images');
		$query->where('published = 1');
		$query->where('listing_id=' . $listingId);
		$query->order('ordering ASC');
		$db->setQuery($query);
		$images = $db->loadObjectList();

		return $images;
	}

	public function saveRating($data, $listingId, $criteriaArray = array(), $commentId = 0)
	{
		$user = JFactory::getUser();

		$created = JFactory::getDate()->toSql();

		$ratingScore = JUDirectoryFrontHelperRating::calculateRatingScore($data, $listingId, $criteriaArray);

		$dataRating = array('listing_id' => $listingId, 'user_id' => $user->id, 'score' => $ratingScore, 'created' => $created);

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$ratingTable = JTable::getInstance('Rating', 'JUDirectoryTable');
		$ratingTable->bind($dataRating);

		if (!$ratingTable->check())
		{
			$this->setError($ratingTable->getError());

			return false;
		}

		$ratingStore = $ratingTable->store();
		if ($ratingStore)
		{
			if ($commentId)
			{
				$db    = JFactory::getDbo();
				$query = "UPDATE #__judirectory_comments SET rating_id = " . $ratingTable->id . " WHERE id = " . $commentId;
				$db->setQuery($query);
				$db->execute();
			}
		}
		else
		{
			$this->setError($ratingTable->getError());

			return false;
		}

		if ($ratingStore && count($criteriaArray) > 0)
		{
			foreach ($criteriaArray AS $criteria)
			{
				JUDirectoryMultiRating::insertCriteriaValue($ratingTable->id, $criteria->id, $criteria->value);
			}
		}

		if ($commentId == 0 || $data['approved'] == 1)
		{
			$listingItem             = JUDirectoryHelper::getListingById($listingId);
			$totalVoteTimes          = 0;
			$params                  = JUDirectoryHelper::getParams(null, $listingId);
			$onlyCalculateLastRating = $params->get('only_calculate_last_rating', 0);
			if (!$user->get('guest'))
			{
				$totalVoteTimes = JUDirectoryFrontHelperRating::getTotalListingVotesOfUser($user->id, $listingId);
			}
			
			if ($onlyCalculateLastRating && ($totalVoteTimes > 0))
			{
				$total_votes  = $listingItem->total_votes;
				$ratingLatest = $this->getLatestRating($listingId, $user->id);
				$rating       = (($listingItem->rating * $total_votes) + $ratingScore - $ratingLatest) / $total_votes;
			}
			else
			{
				$total_votes = $listingItem->total_votes + 1;
				$rating      = (($listingItem->rating * $listingItem->total_votes) + $ratingScore) / $total_votes;
			}

			
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->update('#__judirectory_listings');
			$query->set('rating = ' . $rating);
			$query->set('total_votes = ' . $total_votes);
			$query->where('id = ' . $listingId);
			$db->setQuery($query);
			$db->execute();
		}

		$session      = JFactory::getSession();
		$timeNow      = JFactory::getDate()->toSql();
		$timeNowStamp = strtotime($timeNow);
		
		$inputCookie   = JFactory::getApplication()->input->cookie;
		$config        = JFactory::getConfig();
		$cookie_domain = $config->get('cookie_domain', '');
		$cookie_path   = $config->get('cookie_path', '/');
		
		$inputCookie->set('judir-listing-rated-' . $listingId, $timeNowStamp, time() + 864000, $cookie_path, $cookie_domain);
		
		$session->set('judir-listing-rated-' . $listingId, $timeNowStamp);

		
		$logData = array(
			'user_id'    => $user->id,
			'event'      => 'listing.rate',
			'item_id'    => $listingId,
			'listing_id' => $listingId,
			'value'      => $ratingScore,
			'reference'  => $ratingTable->id
		);

		JUDirectoryFrontHelperLog::addLog($logData);

		return true;
	}

	
	public static function getLatestRating($listingId, $userId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('score');
		$query->from('#__judirectory_rating');
		$query->where('listing_id = ' . $listingId);
		$query->where('user_id = ' . $userId);
		$query->order('created DESC');
		$db->setQuery($query, 0, 1);

		return $db->loadResult();
	}

	
	public function getDisqus($listing_id, $short_name = '')
	{
		if ($short_name == '')
		{
			$params     = JUDirectoryHelper::getParams(null, $listing_id);
			$short_name = $params->get('disqus_username', '');
		}

		$listingObj = JUDirectoryHelper::getListingById($listing_id);
		$listingUrl = JUDirectoryHelperRoute::getListingRoute($listing_id);
		$script     = "
			var disqus_shortname = '{$short_name}';
			var disqus_identifier = 'id={$listing_id}';
			var disqus_title = '{$listingObj->title}';
			var disqus_url = '{$listingUrl}';
			(function() {
				var dsq		= document.createElement('script');
				dsq.type	= 'text/javascript';
				dsq.async	= true;
				dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();";
		$document   = JFactory::getDocument();
		$document->addScriptDeclaration($script);
	}

	
	public function updateComment($data)
	{
		$commentId     = $data['comment_id'];
		$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId, 'cm.*', true);
		$comment       = htmlspecialchars($data['comment']);
		$title         = htmlspecialchars($data['title']);
		$listingId     = $data['listing_id'];
		$newSubscribe  = $data['subscribe'];

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__judirectory_comments');
		$query->set('comment = ' . $db->quote($comment));
		$query->set('title = ' . $db->quote($title));
		$query->where('id = ' . $db->quote($commentId));

		$params = JUDirectoryHelper::getParams(null, $listingId);
		if ($params->get('website_field_in_comment_form', 0) && $data['website'])
		{
			$query->set('website = ' . $db->quote($data['website']));
		}

		$isSubscribe = $this->isSubscribedComment($commentId);
		if ($isSubscribe)
		{
			if (!$newSubscribe)
			{
				$this->deleteCommentSubscription($commentId);
			}
		}
		else
		{
			if (JUDIRPROVERSION && $newSubscribe == 1)
			{
				$this->addCommentSubscription($commentId);
			}
		}

		if ($commentObject->level == 1)
		{
			if ($params->get('filter_comment_language', 0))
			{
				$language = $data['language'];
				$query->set('language = ' . $db->quote($language));
			}
		}

		$db->setQuery($query);

		if ($db->execute())
		{
			if (isset($data['criteria_array']) || isset($data['ratingValue']))
			{
				$this->updateRating($data);
			}

			return true;
		};

		return false;
	}

	
	public function updateRating($data)
	{
		$user          = JFactory::getUser();
		$created       = JFactory::getDate()->toSql();
		$criteriaArray = isset($data['criteria_array']) ? $data['criteria_array'] : array();
		$listingId     = $data['listing_id'];
		$commentId     = $data['comment_id'];

		$ratingObj      = $this->getRating($listingId, $commentId);
		$ratingId       = $ratingObj->id;
		$ratingScoreOld = $ratingObj->score;
		$ratingScoreNew = JUDirectoryFrontHelperRating::calculateRatingScore($data, $listingId, $criteriaArray);

		
		$dataRating = array(
			'id'    => $ratingId, 'user_id' => $user->id, 'comment_id' => $commentId,
			'score' => $ratingScoreNew, 'created' => $created, 'listing_id' => $listingId
		);

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$ratingTable = JTable::getInstance('Rating', 'JUDirectoryTable');
		$ratingTable->bind($dataRating);

		if (!$ratingTable->check())
		{
			return false;
		}

		$ratingStore = $ratingTable->store();
		if (!$ratingStore)
		{
			return false;
		}

		if ($ratingStore && count($criteriaArray) > 0)
		{
			$oldCriteriaIdArr = $this->getCriteriasId($ratingId);
			$criteriaIdArr    = array();
			foreach ($criteriaArray AS $criteria)
			{
				$criteriaIdArr[] = $criteria->id;
			}
			
			$flagUpdate = false;
			if (count(array_diff($criteriaIdArr, $oldCriteriaIdArr)) == 0)
			{
				$flagUpdate = true;
			}
			if ($flagUpdate == false)
			{
				$this->deleteCriteriasByRatingId($ratingId);
			}
			foreach ($criteriaArray AS $criteria)
			{
				if ($flagUpdate == true)
				{
					$this->updateCriteriaValue($ratingId, $criteria->id, $criteria->value);
				}
				else
				{
					JUDirectoryMultiRating::insertCriteriaValue($ratingId, $criteria->id, $criteria->value);
				}
			}
		}
		$listingItem = JUDirectoryHelper::getListingById($listingId);
		$rating      = (($listingItem->rating * $listingItem->total_votes) + $ratingScoreNew - $ratingScoreOld) / $listingItem->total_votes;

		
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__judirectory_listings');
		$query->set('rating = ' . $rating);
		$query->where('id = ' . $listingId);
		$db->setQuery($query);
		$db->execute();

		$session      = JFactory::getSession();
		$timeNow      = JFactory::getDate()->toSql();
		$timeNowStamp = strtotime($timeNow);
		
		$inputCookie   = JFactory::getApplication()->input->cookie;
		$config        = JFactory::getConfig();
		$cookie_domain = $config->get('cookie_domain', '');
		$cookie_path   = $config->get('cookie_path', '/');
		
		$inputCookie->set('judir-listing-rated-' . $listingId, $timeNowStamp, time() + 864000, $cookie_path, $cookie_domain);
		
		$session->set('judir-listing-rated-' . $listingId, $timeNowStamp);

		
		$logData = array(
			'user_id'    => $user->id,
			'event'      => 'listing.rate',
			'item_id'    => $listingId,
			'listing_id' => $listingId,
			'value'      => $ratingScoreNew,
			'reference'  => $ratingTable->id
		);

		JUDirectoryFrontHelperLog::addLog($logData);

		return true;

	}

	public function deleteCriteriasByRatingId($ratingId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__judirectory_criterias_values')
			->where('rating_id = ' . $ratingId);
		$db->setQuery($query);
		$db->execute();
	}

	public function updateCriteriaValue($ratingId, $criteriaId, $value)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__judirectory_criterias_values'));
		$query->set('value = ' . $value);
		$query->where('rating_id = ' . $ratingId);
		$query->where('criteria_id = ' . $criteriaId);
		$db->setQuery($query);

		return $db->execute();
	}

	public function getCriteriasId($ratingId)
	{
		if ($ratingId > 0)
		{
			$db    = Jfactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('criteria_id')
				->from('#__judirectory_criterias_values')
				->where('rating_id = ' . $ratingId);
			$db->setQuery($query);

			return $db->loadColumn();
		}

		return null;
	}

	public function getRating($listingId, $commentId)
	{
		$userId = JFactory::getUser()->id;
		$db     = Jfactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('r.*')
			->from('#__judirectory_rating AS r')
			->join('LEFT', '#__judirectory_comments AS cm ON cm.rating_id = r.id')
			->where('r.user_id = ' . $userId)
			->where('r.listing_id =  ' . $listingId)
			->where('cm.id = ' . $commentId)
			->order('r.created DESC');
		$db->setQuery($query, 0, 1);

		return $db->loadObject();
	}

	
	public function voteComment($commentId)
	{
		$commentObj = JUDirectoryFrontHelperComment::getCommentObject($commentId);
		if (!$commentObj)
		{
			return;
		}

		$vote_up = JFactory::getApplication()->input->getInt('vote_up', 0);

		$helpful_votes = intval($commentObj->helpful_votes);
		$total_votes   = intval($commentObj->total_votes);

		$params                  = JUDirectoryHelper::getParams(null, $commentObj->listing_id);
		$allow_vote_down_comment = $params->get('allow_vote_down_comment', 1);

		
		if (!$allow_vote_down_comment)
		{
			$like_system = true;

			
			if ($vote_up != 1)
			{
				$voteType  = 0;
				$reference = 'unlike';
				$total_votes--;
				$helpful_votes--;
			}
			
			else
			{
				$voteType  = 1;
				$reference = 'like';
				$total_votes++;
				$helpful_votes++;
			}
		}
		else
		{
			$like_system = false;

			
			if ($vote_up != 1)
			{
				$voteType  = -1;
				$reference = 'vote_down';
				$total_votes++;
			}
			
			else
			{
				$voteType  = 1;
				$reference = 'vote_up';
				$total_votes++;
				$helpful_votes++;
			}
		}

		$votedValue = $this->getCommentVotedValue($commentObj->id);
		
		if (($allow_vote_down_comment && $votedValue) || ($votedValue == $voteType))
		{
			$return                = array();
			$return['message']     = JText::_('COM_JUDIRECTORY_VOTING_ERROR');
			$return['like_system'] = $like_system;
			$return['vote_type']   = null;

			JUDirectoryHelper::obCleanData();
			echo json_encode($return);
			exit();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__judirectory_comments')
			->set('helpful_votes = ' . $helpful_votes)
			->set('total_votes = ' . $total_votes)
			->where('id = ' . $commentObj->id);
		$db->setQuery($query);

		if ($db->execute())
		{
			
			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin('judirectory');
			$dispatcher->trigger('onVoteComment', $commentObj, $like_system, $voteType);

			$user   = JFactory::getUser();
			$userId = $user->id;

			
			$logData = array(
				'user_id'    => $user->id,
				'event'      => 'comment.vote',
				'item_id'    => $commentObj->id,
				'listing_id' => $commentObj->listing_id,
				'value'      => $voteType,
				'reference'  => $reference
			);

			JUDirectoryFrontHelperLog::addLog($logData);

			
			if ($userId > 0)
			{
				$cookieName = 'judir-comment-vote-' . $commentObj->id . '_' . $userId;
			}
			
			else
			{
				$ipAddress  = JUDirectoryFrontHelper::getIpAddress();
				$ipAddress  = str_replace('.', '_', $ipAddress);
				$cookieName = 'judir-comment-vote-' . $commentObj->id . '_' . $ipAddress;
			}

			
			$config        = JFactory::getConfig();
			$cookie_domain = $config->get('cookie_domain', '');
			$cookie_path   = $config->get('cookie_path', '/');
			setcookie($cookieName, $voteType, time() + 60 * 60 * 24 * 30, $cookie_path, $cookie_domain);

			$return                = array();
			$return['message']     = JText::sprintf('COM_JUDIRECTORY_N_HELPFUL_VOTES_N_TOTAL_VOTES', $helpful_votes, $total_votes);
			$return['like_system'] = $like_system;
			$return['vote_type']   = $voteType;
		}
		else
		{
			$return                = array();
			$return['message']     = JText::_('COM_JUDIRECTORY_VOTING_ERROR');
			$return['like_system'] = $like_system;
			$return['vote_type']   = null;
		}

		JUDirectoryHelper::obCleanData();
		echo json_encode($return);
		exit();
	}

	
	public function getCommentVotedValue($commentId)
	{
		$user   = JFactory::getUser();
		$userId = $user->id;

		
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('value')
			->from('#__judirectory_logs')
			->where('event = "comment.vote"')
			->where('item_id = ' . $commentId);
		if ($userId > 0)
		{
			$query->where('user_id = ' . $userId);
		}
		else
		{
			$ip = JUDirectoryFrontHelper::getIpAddress();
			$query->where('ip_address = ' . $db->quote($ip));
		}
		$query->order('id DESC');
		$db->setQuery($query);

		$votedValue = $db->loadResult();
		if ($votedValue)
		{
			return $votedValue;
		}

		
		if ($userId > 0)
		{
			$cookieName = 'judir-comment-vote-' . $commentId . '_' . $userId;
		}
		
		else
		{
			$ipAddress  = JUDirectoryFrontHelper::getIpAddress();
			$ipAddress  = str_replace('.', '_', $ipAddress);
			$cookieName = 'judir-comment-vote-' . $commentId . '_' . $ipAddress;
		}

		$app        = JFactory::getApplication();
		$votedValue = $app->input->cookie->get($cookieName);

		return $votedValue;
	}

	
	public function isSubscribedComment($commentId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__judirectory_subscriptions')
			->where('type = ' . $db->quote("comment"))
			->where('item_id = ' . $commentId);
		$db->setQuery($query);
		$result = $db->loadResult();
		$flag   = false;
		if ($result > 0)
		{
			$flag = true;
		}

		return $flag;
	}

	
	public function addCommentSubscription($commentId)
	{
		$user                           = JFactory::getUser();
		$subscriptionData               = array();
		$subscriptionData['user_id']    = $user->id;
		$subscriptionData['type']       = 'comment';
		$subscriptionData['name']       = $user->username;
		$subscriptionData['email']      = $user->email;
		$subscriptionData['created']    = JHtml::date('now', 'Y-m-d H:i:s', true);
		$subscriptionData['item_id']    = $commentId;
		$subscriptionData['ip_address'] = JUDirectoryFrontHelper::getIpAddress();
		$subscriptionData['published']  = 1;
		
		require_once JPATH_SITE . '/components/com_judirectory/models/subscribe.php';
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_judirectory/models');
		$subcribeModel = JModelLegacy::getInstance('Subscribe', 'JUDirectoryModel');
		if (!$subcribeModel->add($subscriptionData))
		{
			return false;
		}

		return true;

	}

	
	public function deleteCommentSubscription($commentId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__judirectory_subscriptions');
		$query->where('type = ' . $db->quote("comment"));
		$query->where('item_id = ' . $commentId);
		$db->setQuery($query);
		$db->execute();
	}

	
	public function getCommentOrderingOptions()
	{
		$commentOrderingOptions = array(
			'cm.title'         => JText::_('COM_JUDIRECTORY_COMMENT_TITLE'),
			'cm.guest_name'    => JText::_('COM_JUDIRECTORY_COMMENT_GUEST_NAME'),
			'cm.website'       => JText::_('COM_JUDIRECTORY_COMMENT_WEBSITE'),
			'cm.created'       => JText::_('COM_JUDIRECTORY_COMMENT_CREATED'),
			'r.score'          => JText::_('COM_JUDIRECTORY_COMMENT_RATING_SCORE'),
			'cm.helpful_votes' => JText::_('COM_JUDIRECTORY_COMMENT_HELPFUL_VOTES'),
			'cm.total_votes'   => JText::_('COM_JUDIRECTORY_COMMENT_TOTAL_VOTES')
		);

		return $commentOrderingOptions;
	}

	
	}