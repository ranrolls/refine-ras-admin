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

	protected $context = 'listing';

	protected $view_list = 'listcats';

	
	protected function allowAdd($data = array())
	{
		
		if (empty($data))
		{
			$catId = JFactory::getApplication()->input->get('cat_id');
			if (!JUDirectoryFrontHelperPermission::canSubmitListing($catId))
			{
				return false;
			}
		}

		return true;
	}

	
	protected function allowEdit($data = array(), $key = 'id')
	{
		
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user     = JFactory::getUser();
		$userId   = $user->get('id');

		
		if ($user->authorise('judir.listing.edit', 'com_judirectory.listing.' . $recordId))
		{
			return true;
		}

		
		
		if ($user->authorise('judir.listing.edit.own', 'com_judirectory.listing.' . $recordId))
		{
			$ownerId = 0;

			if ($recordId)
			{
				
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			
			if ($ownerId == $userId)
			{
				return true;
			}
		}

		
		return parent::allowEdit($data, $key);
	}

	
	protected function getRedirectToListAppend()
	{
		$app             = JFactory::getApplication();
		$tmpl            = $app->input->get('tmpl');
		$append          = '';
		$rootCategory    = JUDirectoryFrontHelperCategory::getRootCategory();
		$categoriesField = new JUDirectoryFieldCore_categories();
		$postValue       = $app->input->getArray($_POST);
		$cat_id          = $postValue['fields'][$categoriesField->id]['main'];

		if (!$cat_id)
		{
			$cat_id = $rootCategory->id;
		}

		if ($cat_id)
		{
			$append .= '&cat_id=' . $cat_id;
		}

		
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		return $append;
	}

	
	public function save($key = null, $urlVar = null)
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		
		$app     = JFactory::getApplication();
		$lang    = JFactory::getLanguage();
		$model   = $this->getModel();
		$table   = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task    = $this->getTask();

		
		$data             = $app->input->post->get('jform', array(), 'array');
		$fieldsData       = $app->input->post->get('fields', array(), 'array');
		$related_listings = array_values($app->input->post->get("related_listings", array(), 'array'));

		
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = $app->input->getInt($urlVar, 0);

		if (!$this->checkEditId($context, $recordId))
		{
			
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		
		$data[$key] = $recordId;

		
		if ($task == 'save2copy')
		{
			
			if ($checkin && $model->checkin($data[$key]) === false)
			{
				
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');

				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);

				return false;
			}

			
			$listingArr           = array($data[$key]);
			$currentListingObject = JUDirectoryHelper::getListingById($data[$key]);
			$catArr               = array($currentListingObject->cat_id);
			$copyOptionsArr       = array('copy_rates', 'copy_hits', 'copy_permission', 'copy_extra_fields',
				'copy_related_listings', 'copy_comments', 'copy_reports', 'copy_subscriptions', 'copy_logs');
			
			$listingCopyMappedId = $model->copyAndMap($listingArr, $catArr, $copyOptionsArr, 'save2copy', $fieldsData);
			$data[$key]          = $listingCopyMappedId;
			$save2copy           = true;
			$task                = 'apply';
		}

		
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		
		
		$form = $model->getForm($data, false);
		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		
		$validData       = $model->validate($form, $data);
		$validFieldsData = $model->validateFields($fieldsData, $data[$key]);

		
		if ($validData === false || $validFieldsData === false)
		{
			
			$errors = $model->getErrors();

			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			
			$app->setUserState($context . '.data', $data);
			$app->setUserState($context . '.fieldsdata', $fieldsData);
			$app->setUserState($context . '.related_listings', $related_listings);

			if (isset($save2copy) && $save2copy)
			{
				$model->delete($data[$key]);
			}

			
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		
		$data['data'] = $validData;
		
		$data['data'][$key]       = $data[$key];
		$data['fieldsData']       = $validFieldsData;
		$data['related_listings'] = $related_listings;

		$categoriesField = new JUDirectoryFieldCore_categories();

		
		if (($model->getListingSubmitType($data['data'][$key]) == 'submit' && !$categoriesField->canSubmit())
			|| ($model->getListingSubmitType($data['data'][$key]) == 'edit' && !$categoriesField->canEdit())
		)
		{
			$listingObjectDb = JUDirectoryHelper::getListingById($data['data'][$key]);
			if ($listingObjectDb)
			{
				
				$data['fieldsData'][$categoriesField->id]['main'] = $listingObjectDb->cat_id;
			}
			
			else
			{
				$this->setError(JText::_('COM_JUDIRECTORY_INVALID_LISTING'));
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);

				return false;
			}
		}

		
		if (!$model->save($data))
		{
			
			$app->setUserState($context . '.data', $validData);
			$app->setUserState($context . '.fieldsdata', $validFieldsData);
			$app->setUserState($context . '.related_listings', $related_listings);


			
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		
		if ($table->load($recordId))
		{
			if ($table->id > 0)
			{
				
				if ($checkin && $model->checkin($recordId) === false)
				{
					
					$app->setUserState($context . '.data', $validData);
					$app->setUserState($context . '.fieldsdata', $validFieldsData);
					$app->setUserState($context . '.related_listings', $related_listings);


					
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
					$this->setMessage($this->getError(), 'error');

					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_item
							. $this->getRedirectToItemAppend($recordId, $urlVar), false
						)
					);

					return false;
				}
			}
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		
		switch ($task)
		{
			case 'apply':
				
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$app->setUserState($context . '.fieldsdata', null);
				$app->setUserState($context . '.related_listings', null);


				$model->checkout($recordId);

				
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);
				break;

			case 'save2new':
				
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$app->setUserState($context . '.fieldsdata', null);
				$app->setUserState($context . '.related_listings', null);


				
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend(null, $urlVar), false
					)
				);
				break;

			default:
				
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$app->setUserState($context . '.fieldsdata', null);
				$app->setUserState($context . '.related_listings', null);


				
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
					)
				);
				break;
		}

		
		$this->postSaveHook($model, $validData);

		return true;
	}

	
	public function edit($key = null, $urlVar = null)
	{
		
		$app     = JFactory::getApplication();
		$model   = $this->getModel();
		$table   = $model->getTable();
		$cid     = $app->input->get('cid', array(), 'array');
		$context = "$this->option.edit.$this->context";

		
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		
		$recordId = (int) (count($cid) ? $cid[0] : $app->input->getInt($urlVar, 0));
		$checkin  = property_exists($table, 'checked_out');

		
		if (!$this->allowEdit(array($key => $recordId), $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		
		if ($checkin && !$model->checkout($recordId))
		{
			
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}
		else
		{
			
			$this->holdEditId($context, $recordId);
			$app->setUserState($context . '.data', null);
			$app->setUserState($context . '.related_listings', null);

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return true;
		}
	}

	
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$app    = JFactory::getApplication();
		$tmpl   = $app->input->get('tmpl');
		$layout = $app->input->get('layout', 'edit');
		$append = '';

		if ($this->view_list == 'listcats')
		{
			$cat_id = $app->input->get->getInt('cat_id', 0);
			if ($cat_id)
			{
				$append .= '&cat_id=' . $cat_id;
			}
			else
			{
				$categoriesField = new JUDirectoryFieldCore_categories();
				$postValue       = $app->input->getArray($_POST);
				$cat_id          = $postValue['fields'][$categoriesField->id]['main'];

				if ($cat_id)
				{
					$append .= '&cat_id=' . $cat_id;
				}
			}
		}
		elseif ($this->view_list == 'pendinglistings')
		{
			$append .= '&approve=1';
		}

		
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}

		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		if ($app->input->getInt('approve', 0) == 1)
		{
			$append .= '&approve=1';
		}

		return $append;
	}

	
	public function cancel($key = null)
	{
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$app        = JFactory::getApplication();
		$listing_id = $app->input->getInt('id', 0);
		
		if ($listing_id)
		{
			$listingObject = JUDirectoryHelper::getListingById($listing_id);
			$cat_id        = $listingObject->cat_id;
		}
		
		else
		{
			$fieldCategory = JUDirectoryFrontHelperField::getField('cat_id');
			$fieldsData    = $app->input->post->get('fields', array(), 'array');
			$cat_id        = $fieldsData[$fieldCategory->id]['main'];
		}

		if (!$cat_id)
		{
			$rootCategory = JUDirectoryFrontHelperCategory::getRootCategory();
			$cat_id       = $rootCategory->id;
		}

		$context = $this->option . ".edit." . $this->context;

		$app->setUserState($context . '.data', null);
		$app->setUserState($context . '.fieldsdata', null);
		$app->setUserState($context . '.related_listings', null);

		if ($listing_id)
		{
			$db    = JFactory::getDbo();
			$query = "SELECT cat_id FROM #__judirectory_listings_xref WHERE listing_id = $listing_id AND main = 1";
			$db->setQuery($query);
			$cat_id = $db->loadResult();
		}

		parent::cancel($key = null);

		if ($this->view_list == "pendinglistings")
		{
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);
		}
		else
		{
			$this->setRedirect("index.php?option=com_judirectory&view=listcats&cat_id=$cat_id");
		}
	}

	
	public function add()
	{
		$context = "$this->option.edit.$this->context";
		$app     = JFactory::getApplication();
		$app->setUserState($context . '.data', null);
		$app->setUserState($context . '.fieldsdata', null);
		$app->setUserState($context . '.related_listings', null);

		return parent::add();
	}

	public function getAddressPath()
	{
		$app  = JFactory::getApplication();
		$id   = $app->input->get('id', 0);
		$html = '';
		if ($id)
		{
			$html = JUDirectoryHelper::getAddressPath($id);

		}

		
		echo $html;
		exit;
	}
}
