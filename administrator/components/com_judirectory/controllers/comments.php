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


jimport('joomla.application.component.controlleradmin');


class JUDirectoryControllerComments extends JControllerAdmin
{
	
	protected $text_prefix = 'COM_JUDIRECTORY_COMMENTS';

	
	public function getModel($name = 'Comment', $prefix = 'JUDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function back()
	{
		$app        = JFactory::getApplication();
		$listing_id = $app->input->getInt('listing_id', 0);
		$cat_id     = JUDirectoryFrontHelperCategory::getRootCategory()->id;
		if ($listing_id)
		{
			$listingObj = JUDirectoryHelper::getListingById($listing_id);
			if (isset($listingObj->cat_id) && $listingObj->cat_id)
			{
				$cat_id = $listingObj->cat_id;
			}
		}

		$this->setRedirect("index.php?option=com_judirectory&view=listcats&cat_id=$cat_id");
	}

	
	public function saveorder()
	{
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		
		$app           = JFactory::getApplication();
		$order         = $app->input->post->get('order', null, 'array');
		$originalOrder = explode(',', $app->input->post->get('original_order_values', null, 'string'));

		
		if (!($order === $originalOrder))
		{
			parent::saveorder();
		}
		else
		{
			
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));

			return true;
		}
	}
}
