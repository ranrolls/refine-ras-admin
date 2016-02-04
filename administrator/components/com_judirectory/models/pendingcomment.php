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


jimport('joomla.application.component.modeladmin');

if (!class_exists('JUDirectoryModelComment'))
{
	require_once JPATH_ADMINISTRATOR . "/components/com_judirectory/models/comment.php";
}

class JUDirectoryModelPendingComment extends JUDirectoryModelComment
{
	
	function approve($comment_ids)
	{
		if (!is_array($comment_ids) || empty($comment_ids))
		{
			$this->setError('COM_JUDIRECTORY_NO_ITEM_SELECTED');

			return false;
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$comment_table = JTable::getInstance("Comment", "JUDirectoryTable");
		$count         = 0;
		$comment_ids   = (array) $comment_ids;
		$rootComment   = JUDirectoryFrontHelperComment::getRootComment();
		$listingIds    = array();
		foreach ($comment_ids AS $comment_id)
		{
			$comment_table->reset();
			
			if ($comment_table->load($comment_id) && $comment_table->parent_id == $rootComment->id && $comment_table->approved == 0)
			{
				$listingIds[$comment_table->listing_id] = $comment_table->listing_id;
			}

			$user                         = JFactory::getUser();
			$date                         = JFactory::getDate();
			$comment_table->approved      = 1;
			$comment_table->published     = 1;
			$comment_table->approved_by   = $user->id;
			$comment_table->approved_time = $date->toSql();
			$comment_table->store();
			$count++;

			
			JUDirectoryFrontHelperMail::sendEmailByEvent('comment.approve', $comment_id);

			
			$logData = array(
				'user_id'    => $comment_table->user_id,
				'event'      => 'comment.approve',
				'item_id'    => $comment_id,
				'listing_id' => $comment_table->listing_id,
				'value'      => 0,
				'reference'  => '',
			);

			JUDirectoryFrontHelperLog::addLog($logData);
		}

		
		foreach ($listingIds AS $listingId)
		{
			JUDirectoryHelper::rebuildRating($listingId);
		}

		return $count;
	}

	
	public function save($data)
	{
		$app            = JFactory::getApplication();
		$comment_option = $app->input->get('approval_option');

		if (parent::save($data))
		{
			if ($comment_option == 'approve')
			{
				$this->approve(array($data['id']));
			}

			return true;
		}

		return false;
	}

	public function getPrevOrNextCommentId($type = 'next')
	{
		$app        = JFactory::getApplication();
		$comment_id = $app->input->getInt('id', 0);
		$db         = JFactory::getDbo();
		$query      = $db->getQuery(true);
		$query->select('id');
		$query->from('#__judirectory_comments');
		$query->where('approved != 1');

		switch ($type)
		{
			case 'prev':
				$query->where('id < ' . $comment_id);
				$query->order('id DESC');
				break;

			case 'next':
			default:
				$query->where('id > ' . $comment_id);
				$query->order('id ASC');
				break;
		}

		$db->setQuery($query, 0, 1);
		$next_prev_comment = $db->loadResult();

		return $next_prev_comment;
	}

}
