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

jimport('joomla.application.component.controllerform');

class JUDirectoryControllerListing extends JControllerForm
{
	
	protected $text_prefix = 'COM_JUDIRECTORY_LISTING';

	public function compare()
	{
		$config        = JFactory::getConfig();
		$cookie_domain = $config->get('cookie_domain', '');
		$cookie_path   = $config->get('cookie_path', '/');

		$app          = JFactory::getApplication();
		$jInput       = $app->input;
		$JInputCookie = $jInput->cookie;
		$user         = JFactory::getUser();
		$listingId    = $jInput->post->getInt('listing_id', 0);

		if ($listingId > 0)
		{
			
			$listingObject = JUDirectoryFrontHelperListing::getListing($listingId);

			if (is_object($listingObject))
			{
				$listingIdString = $JInputCookie->get('judir-compare-listing-' . $user->id, null, null);

				if (!is_null($listingIdString))
				{
					$listingIdArray = explode(",", $listingIdString);
					if (!in_array($listingId, $listingIdArray))
					{
						array_push($listingIdArray, $listingId);
					}
					$listingIdArray = array_unique($listingIdArray);
				}
				else
				{
					$listingIdArray = array($listingId);
				}

				$listingIdString = implode(",", $listingIdArray);

				
				$JInputCookie->set('judir-compare-listing-' . $user->id, $listingIdString, time() + 3600 * 24, $cookie_path, $cookie_domain);

				$listingURL   = JRoute::_(JUDirectoryHelperRoute::getListingRoute($listingObject->id));
				$listingTitle = $listingObject->title;
				$compareURL   = JRoute::_('index.php?option=com_judirectory&view=compare', false);

				$json['success'] = JText::sprintf('COM_JUDIRECTORY_YOU_HAVE_ADDED_LISTING_X_TO_LISTING_COMPARISON', $listingTitle, $listingURL, $compareURL);

				$totalListingCompare = 0;
				require_once JPATH_SITE . '/components/com_judirectory/models/compare.php';
				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_judirectory/models');
				$modelCompare = JModelLegacy::getInstance('Compare', 'JUDirectoryModel');
				$items        = $modelCompare->getItems();
				if (is_array($items) && count($items))
				{
					$totalListingCompare = count($items);
				}

				$json['total'] = JText::plural('COM_JUDIRECTORY_LISTING_COMPARE_N_ITEMS', $totalListingCompare);

				JUDirectoryHelper::obCleanData();
				echo json_encode($json);
				exit;
			}
		}
		JUDirectoryHelper::obCleanData();
		exit;
	}

	public function removeCompare()
	{
		$config        = JFactory::getConfig();
		$cookie_domain = $config->get('cookie_domain', '');
		$cookie_path   = $config->get('cookie_path', '/');

		$app          = JFactory::getApplication();
		$jInput       = $app->input;
		$JInputCookie = $jInput->cookie;
		$user         = JFactory::getUser();

		$removeAll = $jInput->getInt('all', 0);
		if ($removeAll == 1)
		{
			$JInputCookie->set('judir-compare-listing-' . $user->id, '', time() - 3600, $cookie_path, $cookie_domain);
			$this->setRedirect(JRoute::_('index.php?option=com_judirectory&view=compare', false), JText::_('COM_JUDIRECTORY_YOU_HAVE_REMOVED_ALL_LISTING_COMPARISON'));

			return true;
		}

		$listingId = $jInput->getInt('listing_id', 0);

		if ($listingId > 0)
		{
			
			$listingObject = JUDirectoryFrontHelperListing::getListing($listingId);

			if (is_object($listingObject))
			{
				$listingIdString = $JInputCookie->get('judir-compare-listing-' . $user->id, '', 'string');

				$listingIdArray = explode(",", $listingIdString);

				if (in_array($listingId, $listingIdArray))
				{
					$keyListing = array_search($listingId, $listingIdArray);
					unset($listingIdArray[$keyListing]);
				}
				else
				{
					$this->setRedirect(JRoute::_('index.php?option=com_judirectory&view=compare', false), JText::_('COM_JUDIRECTORY_LISTING_IS_NOT_IN_COMPARISON_LIST'));

					return false;
				}

				if (count($listingIdArray))
				{
					$listingIdArray = array_unique($listingIdArray);
				}


				if (count($listingIdArray) > 0)
				{
					$listingIdString = implode(",", $listingIdArray);

					
					$JInputCookie->set('judir-compare-listing-' . $user->id, $listingIdString, time() + 3600 * 24, $cookie_path, $cookie_domain);
				}
				else
				{
					
					$JInputCookie->set('judir-compare-listing-' . $user->id, null, time() - 3600 * 24, $cookie_path, $cookie_domain);
				}

				$this->setRedirect(JRoute::_('index.php?option=com_judirectory&view=compare', false), JText::_('COM_JUDIRECTORY_YOU_HAVE_MODIFIED_YOUR_LISTING_COMPARISON'));

				return true;
			}
		}
	}

	
	public function sendemail()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$app  = JFactory::getApplication();
		$data = array();

		
		$data['from_name']  = $app->input->post->get('name', '', 'string');
		$data['from_email'] = $app->input->post->get('email', '', 'string');
		$data['to_email']   = $app->input->post->get('to_email', '', 'string');
		$data['listing_id'] = $app->input->getInt('id', 0);

		JUDirectoryHelper::obCleanData();
		if (!JUDirectoryFrontHelperMail::sendEmailByEvent('listing.sendtofriend', $data['listing_id'], $data))
		{
			echo '<label class="control-label"></label><div class="controls"><span class="alert alert-error">' . JText::_('COM_JUDIRECTORY_FAIL_TO_SEND_EMAIL') . '</span></div>';
			exit;
		}
		else
		{
			echo '<label class="control-label"></label><div class="controls"><span class="alert alert-success">' . JText::_('COM_JUDIRECTORY_SEND_EMAIL_SUCCESSFULLY') . '</span></div>';
			exit;
		}
	}

	
	public function singleRating()
	{
		
		JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

		
		$app       = JFactory::getApplication();
		$data      = $app->input->getArray($_POST);
		$listingId = $data['listing_id'];
		$params    = JUDirectoryHelper::getParams(null, $listingId);

		JUDirectoryHelper::obCleanData();

		
		$canVoteListing = JUDirectoryFrontHelperPermission::canRateListing($listingId);

		if (!$canVoteListing)
		{
			echo JText::_('COM_JUDIRECTORY_YOU_CAN_NOT_VOTE_ON_THIS_LISTING');
			exit;
		}

		
		if (($data['ratingValue'] <= 0) && ($data['ratingValue'] > 10))
		{
			echo JText::_('COM_JUDIRECTORY_INVALID_RATING_VALUE');
			exit;
		}

		$inputCookie = $app->input->cookie;

		$ratingInterval = $params->get('rating_interval', 86400);
		$user           = JFactory::getUser();
		$timeNow        = JFactory::getDate()->toSql();
		$timeNowStamp   = strtotime($timeNow);
		if ($user->get('guest'))
		{
			
			$lastTimeRated = $inputCookie->get('judir-listing-rated-' . $listingId, null);
			if ($lastTimeRated != null)
			{
				if ($timeNowStamp > $lastTimeRated)
				{
					if ($timeNowStamp - $lastTimeRated < $ratingInterval)
					{
						echo JText::_('COM_JUDIRECTORY_YOU_ARE_ALREADY_VOTED_ON_THIS_LISTING');
						exit;
					}
				}
			}
		}
		else
		{
			$lastTimeRated = JUDirectoryFrontHelperRating::getLastTimeVoteListingOfUser($user->id, $listingId);
			if (!$lastTimeRated)
			{
				$lastTimeRated = 0;
			}
			$lastTimeRated = strtotime($lastTimeRated);
			if ($lastTimeRated > 0)
			{
				if ($timeNowStamp > $lastTimeRated)
				{
					if ($timeNowStamp - $lastTimeRated < $ratingInterval)
					{
						echo JText::_('COM_JUDIRECTORY_YOU_ARE_ALREADY_VOTED_ON_THIS_LISTING');
						exit;
					}
				}
			}
		}

		$dataValid['ratingValue'] = $data['ratingValue'];

		$model = $this->getModel();

		JUDirectoryHelper::obCleanData();
		if ($model->saveRating($dataValid, $listingId))
		{
			echo JText::_('COM_JUDIRECTORY_THANK_YOU_FOR_VOTING');
		}
		else
		{
			echo JText::_('COM_JUDIRECTORY_VOTING_FAILED_PLEASE_CONTACT_ADMINISTRATOR');
		}
		exit;
	}

	
	public function multiRating()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		
		$app       = JFactory::getApplication();
		$data      = $app->input->getArray($_POST);
		$listingId = $data['listing_id'];
		$params    = JUDirectoryHelper::getParams(null, $listingId);

		
		$canRateListing = JUDirectoryFrontHelperPermission::canRateListing($listingId);

		JUDirectoryHelper::obCleanData();

		if (!$canRateListing)
		{
			echo JText::_('COM_JUDIRECTORY_YOU_CAN_NOT_VOTE_ON_THIS_LISTING');
			exit;
		}

		if (!JUDirectoryHelper::hasMultiRating())
		{
			echo JText::_('COM_JUDIRECTORY_MULTI_RATING_HAS_BEEN_DISABLED_PLEASE_CONTACT_ADMINISTRATOR');
			exit;
		}

		$inputCookie = $app->input->cookie;

		$ratingInterval = $params->get('rating_interval', 86400);
		$user           = JFactory::getUser();
		$timeNow        = JFactory::getDate()->toSql();
		$timeNowStamp   = strtotime($timeNow);
		if ($user->get('guest'))
		{
			
			$lastTimeRated = $inputCookie->get('judir-listing-rated-' . $listingId, null);
			if ($lastTimeRated != null)
			{
				if ($timeNowStamp > $lastTimeRated)
				{
					if ($timeNowStamp - $lastTimeRated < $ratingInterval)
					{
						echo JText::_('COM_JUDIRECTORY_YOU_ARE_ALREADY_VOTED_ON_THIS_LISTING');
						exit;
					}
				}
			}
		}
		else
		{
			$lastTimeRated = JUDirectoryFrontHelperRating::getLastTimeVoteListingOfUser($user->id, $listingId);
			if (!$lastTimeRated)
			{
				$lastTimeRated = 0;
			}
			$lastTimeRated = strtotime($lastTimeRated);
			if ($lastTimeRated > 0)
			{
				if ($timeNowStamp > $lastTimeRated)
				{
					if ($timeNowStamp - $lastTimeRated < $ratingInterval)
					{
						
						echo JText::_('COM_JUDIRECTORY_YOU_ARE_ALREADY_VOTED_ON_THIS_LISTING');
						exit;
					}
				}
			}
		}

		
		$dataValid     = array();
		$mainCatId     = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
		$criteriaArray = JUDirectoryFrontHelperCriteria::getCriteriasByCatId($mainCatId);
		$postCriteria  = $data['criteria'];

		if (count($criteriaArray) > 0)
		{
			foreach ($criteriaArray AS $key => $criteria)
			{
				if ($criteria->required)
				{
					if (isset($postCriteria[$criteria->id]) && $postCriteria[$criteria->id] > 0 && $postCriteria[$criteria->id] <= 10)
					{
						$criteria->value = $postCriteria[$criteria->id];
					}
					else
					{
						
						echo JText::_('Invalid Field ' . $criteria->title);
						exit;
					}
				}
				else
				{
					if (isset($postCriteria[$criteria->id]) && $postCriteria[$criteria->id] > 0 && $postCriteria[$criteria->id] <= 10)
					{
						$criteria->value = $postCriteria[$criteria->id];
					}
					else
					{
						unset($criteriaArray[$key]);
					}
				}
			}
		}
		else
		{
			echo JText::_('COM_JUDIRECTORY_VOTING_FAILED_PLEASE_CONTACT_ADMINISTRATOR');
			exit;
		}

		$model = $this->getModel();

		JUDirectoryHelper::obCleanData();
		if ($model->saveRating($dataValid, $listingId, $criteriaArray))
		{
			echo JText::_('COM_JUDIRECTORY_THANK_YOU_FOR_VOTING');
		}
		else
		{
			echo JText::_('COM_JUDIRECTORY_VOTING_FAILED_PLEASE_CONTACT_ADMINISTRATOR');
		}
		exit;
	}

	
	public function redirectUrl()
	{
		$app       = JFactory::getApplication();
		$fieldId   = $app->input->getInt('field_id');
		$listingId = $app->input->getInt('listing_id');

		$field = JUDirectoryFrontHelperField::getField($fieldId, $listingId);
		$field->redirectUrl();
	}

	
	public function addComment()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		
		$user  = JFactory::getUser();
		$model = $this->getModel();

		
		$rootComment = JUDirectoryFrontHelperComment::getRootComment();

		
		$data = $_POST;

		
		$listingId = $data['listing_id'];
		$params    = JUDirectoryHelper::getParams(null, $listingId);
		$parentId  = $data['parent_id'];

		
		$model->setSessionCommentForm($listingId);

		
		if (strlen($data['title']) < 6)
		{
			$this->setError(JText::_('COM_JUDIRECTORY_COMMENT_INVALID_TITLE'));
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

			return false;
		}

		
		if (strlen($data['guest_name']) < 1)
		{
			$this->setError(JText::_('COM_JUDIRECTORY_COMMENT_INVALID_NAME'));
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

			return false;
		}

		
		if (isset($data['guest_email']))
		{
			if (!preg_match('/^[\w\.-]+@[\w\.-]+\.[\w\.-]{2,6}$/', $data['guest_email']))
			{
				$this->setError(JText::_('COM_JUDIRECTORY_COMMENT_INVALID_EMAIL'));
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}

		
		if (isset($data['website']))
		{
			if (!preg_match('/^(https?:\/\/)?([\w\.-]+)\.([\w\.-]{2,6})([\/\w \.-]*)*\/?$/i', $data['website']))
			{
				$this->setError(JText::_('COM_JUDIRECTORY_COMMENT_INVALID_WEBSITE'));
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}

		
		if (isset($data['comment_language']))
		{
			$langArray = JHtml::_('contentlanguage.existing');
			$langKey   = array_keys($langArray);
			array_unshift($langKey, '*');
			if (!in_array($data['comment_language'], $langKey))
			{
				$this->setError(JText::_('COM_JUDIRECTORY_COMMENT_INVALID_LANGUAGE'));
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}

		
		$minCharacter     = $params->get('min_comment_characters', 20);
		$maxCharacter     = $params->get('max_comment_characters', 1000);
		$comment          = $data['comment'];
		$comment          = JUDirectoryFrontHelperComment::parseCommentText($comment, $listingId);
		$comment          = strip_tags($comment);
		$commentCharacter = strlen($comment);
		if ($commentCharacter < $minCharacter || $commentCharacter > $maxCharacter)
		{
			$this->setError(JText::_('COM_JUDIRECTORY_COMMENT_INVALID_COMMENT'));
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

			return false;
		}

		
		$showCaptcha = JUDirectoryFrontHelperPermission::showCaptchaWhenComment($listingId);

		if ($showCaptcha)
		{
			$validCaptcha = JUDirectoryFrontHelperCaptcha::checkCaptcha();
			
			if (!$validCaptcha)
			{
				if ($parentId == $rootComment->id)
				{
					$form = '#judir-comment-form';
				}
				else
				{
					$form = '#comment-reply-wrapper-' . $parentId;
				}

				$this->setError(JText::_('COM_JUDIRECTORY_INVALID_CAPTCHA'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId . $form, false));

				return false;
			}
		}

		
		if ($user->get('guest'))
		{
			if (!$model->checkNameOfGuest($listingId))
			{
				$this->setError(JText::_('COM_JUDIRECTORY_YOU_ARE_NOT_AUTHORIZED_TO_COMMENT_ON_THIS_LISTING'));
				$this->setMessage($model->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}

			if (!$model->checkEmailOfGuest())
			{
				$this->setMessage($model->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}

		if ($parentId == $rootComment->id)
		{
			
			$canComment = JUDirectoryFrontHelperPermission::canComment($listingId, $data['guest_email']);
			if (!$canComment)
			{
				$this->setError(JText::_('COM_JUDIRECTORY_YOU_ARE_NOT_AUTHORIZED_TO_COMMENT_ON_THIS_LISTING'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}
		elseif ($parentId > 0 && $parentId != $rootComment->id)
		{
			
			$canReplyComment = JUDirectoryFrontHelperPermission::canReplyComment($listingId, $parentId);
			if (!$canReplyComment)
			{
				$this->setError(JText::_('COM_JUDIRECTORY_YOU_ARE_NOT_AUTHORIZED_TO_REPLY_THIS_COMMENT'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}
		else
		{
			$this->setError(JText::_('COM_JUDIRECTORY_INVALID_DATA'));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

			return false;
		}

		
		$dataValid = array();
		if ($parentId == $rootComment->id)
		{
			$canRateListing = JUDirectoryFrontHelperPermission::canRateListing($listingId);
			if ($canRateListing)
			{
				$dataValid = $this->validateCriteria($data, $parentId);
				if (!$dataValid)
				{
					$this->setError(JText::_('COM_JUDIRECTORY_INVALID_RATING_VALUE'));
					$this->setMessage($this->getError(), 'error');
					$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

					return false;
				}
			}
		}

		$requiredPostNames = array('title', 'guest_name', 'guest_email', 'comment', 'parent_id', 'listing_id');

		if ($params->get('website_field_in_comment_form', 0) == 2)
		{
			array_push($requiredPostNames, 'website');
		}

		if ($parentId == $rootComment->id && $params->get('filter_comment_language', 0))
		{
			array_push($requiredPostNames, 'comment_language');
		}

		foreach ($requiredPostNames AS $requiredPostName)
		{
			if (trim($data[$requiredPostName]) == '')
			{
				$this->setError(JText::_('COM_JUDIRECTORY_INVALID_INPUT_DATA'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $listingId, false));

				return false;
			}
		}

		$acceptedPostNames = array('title', 'guest_name', 'guest_email', 'language', 'website', 'comment', 'parent_id', 'listing_id', 'subscribe');
		if ($params->get('website_field_in_comment_form', 0) == 2 || $params->get('website_field_in_comment_form', 0) == 1)
		{
			array_push($acceptedPostNames, 'website');
		}

		if ($params->get('filter_comment_language', 0))
		{
			array_push($acceptedPostNames, 'comment_language');
		}

		foreach ($acceptedPostNames AS $acceptedPostName)
		{
			if (isset($data[$acceptedPostName]))
			{
				$dataValid[$acceptedPostName] = $data[$acceptedPostName];
			}
		}

		$newCommentId = $model->saveComment($dataValid);
		if (!$newCommentId)
		{
			$this->setError($model->getError());
			$this->setMessage($this->getError(), 'error');
			$redirectUrl = JRoute::_(JUDirectoryHelperRoute::getListingRoute($listingId), false);
			$this->setRedirect($redirectUrl);

			return false;
		}

		
		$session                     = JFactory::getSession();
		$timeNow                     = JFactory::getDate()->toSql();
		$timeNowStamp                = strtotime($timeNow);
		$sessionCommentOnListingTime = 'judir-commented-' . $listingId;
		$sessionCommentTime          = 'judir-commented';
		$session->set($sessionCommentOnListingTime, $timeNowStamp);
		$session->set($sessionCommentTime, $timeNowStamp);
		
		$session->clear('judirectory_commentform_' . $listingId);

		
		$this->setMessage(JText::_('COM_JUDIRECTORY_ADD_COMMENT_SUCCESSFULLY'));
		$redirectUrl = JRoute::_(JUDirectoryHelperRoute::getListingRoute($listingId) . '#comment-item-' . $newCommentId, false);
		$this->setRedirect($redirectUrl);

		return true;
	}

	
	public function deleteComment()
	{
		
		JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		$app           = JFactory::getApplication();
		$commentId     = $app->input->getInt('comment_id', 0);
		$commentObject = JUDirectoryFrontHelperComment::getCommentObject($commentId);
		$listingId     = $commentObject->listing_id;

		
		$canDeleteComment = JUDirectoryFrontHelperPermission::canDeleteComment($commentId);
		if ($canDeleteComment)
		{
			$commentModel = $this->getModel('Modcomment', 'JUDirectoryModel');

			if ($commentModel->delete($commentId))
			{
				$this->setMessage(JText::_('COM_JUDIRECTORY_DELETE_COMMENT_SUCCESSFULLY'));
				$this->setRedirect(JRoute::_(JUDirectoryHelperRoute::getListingRoute($listingId), false));

				return true;
			}
		}

		$this->setMessage(JText::_('COM_JUDIRECTORY_DELETE_COMMENT_FAILED'), 'error');
		$this->setRedirect(JRoute::_(JUDirectoryHelperRoute::getListingRoute($listingId), false));

		return false;
	}

	
	public function voteComment()
	{
		
		if (!JSession::checkToken('get'))
		{
			$return                = array();
			$return['message']     = JText::_('JINVALID_TOKEN');
			$return['like_system'] = null;
			$return['vote_type']   = null;

			JUDirectoryHelper::obCleanData();
			echo json_encode($return);
			exit();
		}

		$model     = $this->getModel();
		$app       = JFactory::getApplication();
		$commentId = $app->input->getInt('id', 0);

		$model->voteComment($commentId);
	}

	
	public function quoteComment()
	{
		$app        = JFactory::getApplication();
		$commentId  = $app->input->getInt('comment_id', 0);
		$commentObj = JUDirectoryFrontHelperComment::getCommentObject($commentId);

		JUDirectoryHelper::obCleanData();
		$name = ($commentObj->user_id > 0) ? JFactory::getUser($commentObj->user_id)->name : $commentObj->guest_name;
		echo $quote = '[quote="' . $name . '"]' . $commentObj->comment . '[/quote]';
		exit();
	}

	
	public function updateComment()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		
		$user  = JFactory::getUser();
		$model = $this->getModel();

		
		$app            = JFactory::getApplication();
		$data           = $app->input->getArray($_POST);
		$listingId      = $data['listing_id'];
		$commentId      = $data['comment_id'];
		$canEditComment = JUDirectoryFrontHelperPermission::canEditComment($commentId);
		$redirectUrl    = JRoute::_(JUDirectoryHelperRoute::getListingRoute($listingId) . '#comment-item-' . $commentId);

		if (!$canEditComment)
		{
			$this->setMessage(JText::_('COM_JUDIRECTORY_UPDATE_COMMENT_ERROR'));
			$this->setRedirect($redirectUrl);

			return false;
		}

		$params = JUDirectoryHelper::getParams(null, $listingId);
		
		$ratingValue = $this->validateCriteria($data);
		if ($ratingValue)
		{
			$data = array_merge($data, $ratingValue);
		}
		else
		{
			$this->setMessage(JText::_('COM_JUDIRECTORY_UPDATE_COMMENT_ERROR'));
			$this->setRedirect($redirectUrl);

			return false;
		}

		JUDirectoryHelper::obCleanData();
		if ($model->updateComment($data, $params))
		{
			
			$logData = array(
				'user_id'    => $user->id,
				'event'      => 'comment.edit',
				'item_id'    => $commentId,
				'listing_id' => $listingId,
				'value'      => 0,
				'reference'  => '',
			);
			JUDirectoryFrontHelperLog::addLog($logData);
			$this->setMessage(JText::_('COM_JUDIRECTORY_UPDATE_COMMENT_SUCCESSFULLY'));
			$this->setRedirect($redirectUrl);

			return true;
		}
		else
		{
			$this->setMessage(JText::_('COM_JUDIRECTORY_UPDATE_COMMENT_ERROR'));
			$this->setRedirect($redirectUrl);

			return false;
		}
	}

	
	public function validateCriteria($data)
	{
		$listingId = $data['listing_id'];
		$params    = JUDirectoryHelper::getParams(null, $listingId);

		
		$dataValid      = array();
		$canRateListing = JUDirectoryFrontHelperPermission::canRateListing($listingId);
		if ($canRateListing && $params->get('enable_listing_rate_in_comment_form', 1))
		{
			$mainCatId     = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
			$criteriaArray = JUDirectoryFrontHelperCriteria::getCriteriasByCatId($mainCatId);
			$postCriteria  = $data['criteria'];
			if (count($criteriaArray) > 0)
			{
				foreach ($criteriaArray AS $key => $criteria)
				{
					if ($criteria->required)
					{
						if (isset($postCriteria[$criteria->id]) && $postCriteria[$criteria->id] > 0 && $postCriteria[$criteria->id] <= 10)
						{
							$criteria->value = $postCriteria[$criteria->id];
						}
						else
						{
							
							echo JText::_('Invalid Field ' . $criteria->title);
							exit;
						}
					}
					else
					{
						if (isset($postCriteria[$criteria->id]) && $postCriteria[$criteria->id] > 0 && $postCriteria[$criteria->id] <= 10)
						{
							$criteria->value = $postCriteria[$criteria->id];
						}
						else
						{
							unset($criteriaArray[$key]);
						}
					}
				}

				$dataValid['criteria_array'] = $criteriaArray;
			}
			else
			{
				
				if ($params->get('require_listing_rate_in_comment_form', 1))
				{
					if (($data['judir_comment_rating_single'] <= 0) && ($data['judir_comment_rating_single'] > 10))
					{
						return false;
					}

					$dataValid['ratingValue'] = $data['judir_comment_rating_single'];
				}
			}
		}

		return $dataValid;
	}
}
