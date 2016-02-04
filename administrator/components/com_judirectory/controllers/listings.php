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
jimport('joomla.application.component.view');


class JUDirectoryControllerListings extends JControllerAdmin
{
	protected $view_list = 'listcats';

	
	protected $text_prefix = 'COM_JUDIRECTORY_LISTINGS';

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unfeature', 'feature');
		$this->registerTask('inherit_access_unpublish', 'inherit_access_publish');
	}

	
	public function feature()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$app     = JFactory::getApplication();
		$cid     = $app->input->post->get('listingid', array(), 'array');
		$data    = array('feature' => 1, 'unfeature' => 0);
		$task    = $this->getTask();
		$value   = JArrayHelper::getValue($data, $task, 0, 'int');
		$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();

		if (empty($cid))
		{
			JError::raiseWarning(500, JText::_('COM_JUDIRECTORY_NO_ITEM_SELECTED'));
		}
		else
		{
			
			$model = $this->getModel();
			
			JArrayHelper::toInteger($cid);

			
			if (!$model->feature($cid, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$ntext = $this->text_prefix . '_N_ITEMS_FEATURED';
				}
				elseif ($value == 0)
				{
					$ntext = $this->text_prefix . '_N_ITEMS_UNFEATURED';
				}
				$this->setMessage(JText::plural($ntext, count($cid)));
			}
		}

		$extension    = $app->input->get('extension');
		$extensionURL = ($extension) ? '&extension=' . $extension : '';
		$catURL       = '&cat_id=' . $app->input->getInt('cat_id', $rootCat->id);
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $catURL . $extensionURL, false));
	}

	
	public function getModel($name = 'Listing', $prefix = 'JUDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	
	public function moveListings()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$app              = JFactory::getApplication();
		$cat_id           = $app->input->getInt('categories', 0);
		$moved_listing_id = $app->input->post->get('listingid', array(), 'array');
		$session          = JFactory::getSession();
		if (!$cat_id)
		{
			if (!empty($moved_listing_id))
			{
				$session->set('moved_listing_id', $moved_listing_id);
			}
			$this->setRedirect("index.php?option=com_judirectory&view=listings&layout=move");
		}
		else
		{
			$model                = $this->getModel();
			$move_option_arr      = $app->input->post->get('move_options', array(), 'array');
			$total_moved_listings = $model->moveListings($session->get('moved_listing_id'), $cat_id, $move_option_arr);
			$this->setRedirect("index.php?option=com_judirectory&view=listcats&cat_id=$cat_id", JText::plural($this->text_prefix . '_N_ITEMS_MOVED', $total_moved_listings));
		}
	}

	
	public function copyListings()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$app               = JFactory::getApplication();
		$cat_id            = $app->input->get('categories', array(), 'array');
		$copied_listing_id = $app->input->post->get('listingid', array(), 'array');
		$session           = JFactory::getSession();
		if (empty($cat_id))
		{
			if (!empty($copied_listing_id))
			{
				$session->set('copied_listing_id', $copied_listing_id);
			}
			$this->setRedirect("index.php?option=com_judirectory&view=listings&layout=copy");
		}
		else
		{
			$model                 = $this->getModel();
			$copy_option_arr       = $app->input->post->get('copy_options', array(), 'array');
			$total_copied_listings = $model->copyListings($session->get('copied_listing_id'), $cat_id, $copy_option_arr);
			$this->setRedirect("index.php?option=com_judirectory&view=listcats&cat_id=$cat_id[0]", JText::plural($this->text_prefix . '_N_ITEMS_COPIED', $total_copied_listings));
		}
	}

	
	public function checkin()
	{
		$app       = JFactory::getApplication();
		$listingid = $app->input->post->get('listingid', array(), 'array');
		$app->input->post->set('cid', $listingid);
		$_POST['cid'] = $listingid;

		parent::checkin();

		$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
		$cat_id  = $app->input->getInt('cat_id', $rootCat->id);
		$this->setRedirect("index.php?option=com_judirectory&view=$this->view_list&cat_id=$cat_id");
	}

	
	public function publish()
	{
		$app       = JFactory::getApplication();
		$listingid = $app->input->post->get('listingid', array(), 'array');
		$app->input->set('cid', $listingid);
		$_POST['cid'] = $listingid;

		parent::publish();

		$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
		$cat_id  = $app->input->getInt('cat_id', $rootCat->id);
		$this->setRedirect("index.php?option=com_judirectory&view=$this->view_list&cat_id=$cat_id");
	}

	
	public function saveorder()
	{
		$app       = JFactory::getApplication();
		$listingid = $app->input->post->get('listingid', array(), 'array');
		$app->input->post->set('cid', $listingid);
		$_POST['cid'] = $listingid;
		$rootCat      = JUDirectoryFrontHelperCategory::getRootCategory();
		$cat_id       = $app->input->getInt('cat_id', $rootCat->id);

		parent::saveorder();

		$this->setRedirect("index.php?option=com_judirectory&view=$this->view_list&cat_id=$cat_id");
	}

	
	public function reorder()
	{
		$app       = JFactory::getApplication();
		$listingid = $app->input->post->get('listingid', array(), 'array');
		$app->input->post->set('cid', $listingid);
		$_POST['cid'] = $listingid;
		$rootCat      = JUDirectoryFrontHelperCategory::getRootCategory();
		$cat_id       = $app->input->getInt('cat_id', $rootCat->id);

		parent::reorder();

		$this->setRedirect("index.php?option=com_judirectory&view=listcats&cat_id=$cat_id");
	}

	
	public function delete()
	{
		$app       = JFactory::getApplication();
		$listingid = $app->input->post->get('listingid', array(), 'array');
		$app->input->set('cid', $listingid);
		$_POST['cid'] = $listingid;
		$rootCat      = JUDirectoryFrontHelperCategory::getRootCategory();
		$cat_id       = $app->input->getInt('cat_id', $rootCat->id);

		parent::delete();

		$this->setRedirect("index.php?option=com_judirectory&view=$this->view_list&cat_id=$cat_id");
	}
}
