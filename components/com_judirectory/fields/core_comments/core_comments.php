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

class JUDirectoryFieldCore_comments extends JUDirectoryFieldText
{
	protected $field_name = 'comments';

	protected function getValue()
	{

		$app = JFactory::getApplication();
		
		if ($app->isSite() && isset($this->listing->total_comments) && !is_null($this->listing->total_comments))
		{
			return $this->listing->total_comments;
		}

		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		if ($app->isSite())
		{
			$query = $db->getQuery(true);
			$query->select('COUNT(*)');
			$query->from('#__judirectory_comments AS cm');
			$query->where('listing_id =' . $this->listing_id);
			$query->where('level = 1');
			$query->where('approved = 1');

			$moderator = JUDirectoryFrontHelperModerator::getModerator($this->listing->cat_id);
			$getAll    = false;
			if ($user->authorise('core.admin', 'com_judirectory'))
			{
				$getAll = true;
			}

			if (is_object($moderator))
			{
				if ($moderator->comment_edit || $moderator->comment_edit_state || $moderator->comment_delete)
				{
					$getAll = true;
				}
			}

			if (!$getAll)
			{
				$query->where('published = 1');
				$params                = JUDirectoryHelper::getParams(null, $this->listing_id);
				$negative_vote_comment = $params->get('negative_vote_comment');
				if (is_numeric($negative_vote_comment) && $negative_vote_comment > 0)
				{
					$query->where('(total_votes - helpful_votes) <' . $negative_vote_comment);
				}
			}
		}
		else
		{
			$query = $db->getQuery(true);
			$query->select('COUNT(*)');
			$query->from('#__judirectory_comments AS cm');
			$query->where('listing_id =' . $this->listing_id);
			$query->where('level = 1');
			$query->where('approved = 1');
		}
		$db->setQuery($query);
		$totalComments = $db->loadResult();

		return $totalComments;
	}

	
	public function storeValue($value)
	{
		return true;
	}

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function getBackendOutput()
	{
		$value = $this->value;

		return '<span class="comments"><a href="index.php?option=com_judirectory&view=comments&listing_id=' . $this->listing_id . '" title="' . JText::_('COM_JUDIRECTORY_VIEW_COMMENTS') . '">' . JText::plural('COM_JUDIRECTORY_N_COMMENT', $value) . '</a></span>';
	}

	public function onSimpleSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$app       = JFactory::getApplication();
			$where_str = $app->isSite() ? ' AND cm.published = 1' : '';
			$where[]   = "(SELECT COUNT(*) FROM #__judirectory_comments AS cm (cm.listing_id = listing.id AND cm.approved = 1$where_str)) = " . (int) $search;
		}
	}

	public function onSearch(&$query, &$where, $search)
	{
		if (is_array($search) && !empty($search))
		{
			$app       = JFactory::getApplication();
			$where_str = $app->isSite() ? ' AND cm.published = 1' : '';
			if ($search['from'] !== "" && $search['to'] !== "")
			{
				$from = (int) $search['from'];
				$to   = (int) $search['to'];
				if ($from > $to)
				{
					$this->swap($from, $to);
				}

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE (cm.listing_id = listing.id AND cm.approved = 1$where_str)) BETWEEN $from AND $to";
			}
			elseif ($search['from'] !== "")
			{
				$from = (int) $search['from'];

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE (cm.listing_id = listing.id AND cm.approved = 1$where_str)) >= $from";
			}
			elseif ($search['to'] !== "")
			{
				$to = (int) $search['to'];

				$where[] = "(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE (cm.listing_id = listing.id AND cm.approved = 1$where_str)) <= $to";
			}
		}
		else
		{
			$this->onSimpleSearch($query, $where, $search);
		}
	}

	public function orderingPriority(&$query = null)
	{
		$app       = JFactory::getApplication();
		$where_str = $app->isSite() ? ' AND cm.published = 1' : '';
		$this->appendQuery($query, 'select', '(SELECT COUNT(*) FROM #__judirectory_comments AS cm WHERE (cm.listing_id = listing.id AND cm.approved = 1' . $where_str . ')) AS comments');

		return array('ordering' => 'comments', 'direction' => $this->priority_direction);
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}

}

?>