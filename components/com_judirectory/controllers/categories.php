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

class JUDirectoryControllerCategories extends JControllerAdmin
{
	
	protected $text_prefix = 'COM_JUDIRECTORY_CATEGORIES';

	
	public function loadCategories()
	{
		
		require_once JPATH_ADMINISTRATOR . '/components/com_judirectory/models/category.php';
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/models');
		$backendCategoryModel = JModelLegacy::getInstance('Category', 'JUDirectoryModel');
		$data                 = $backendCategoryModel->loadCategories();
		JUDirectoryHelper::obCleanData();
		echo $data;
		exit();
	}

	
	public function listingChangeCategory()
	{
		
		require_once JPATH_ADMINISTRATOR . '/components/com_judirectory/models/category.php';
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/models');
		$backendCategoryModel = JModelLegacy::getInstance('Category', 'JUDirectoryModel');
		$data                 = $backendCategoryModel->listingChangeCategory();
		JUDirectoryHelper::obCleanData();
		echo $data;
		exit();
	}
}
