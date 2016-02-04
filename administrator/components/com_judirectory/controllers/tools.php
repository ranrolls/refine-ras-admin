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


class JUDirectoryControllerTools extends JControllerAdmin
{
	
	protected $text_prefix = 'COM_JUDIRECTORY_TOOLS';

	public function getModel($name = 'Tools', $prefix = 'JUDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	
	public function resizeImages()
	{
		$model = $this->getModel();
		JUDirectoryHelper::obCleanData();
		echo $model->resizeImages();
		exit;
	}

	public function rebuildRating()
	{
		$model = $this->getModel();
		JUDirectoryHelper::obCleanData();
		echo $model->rebuildRating();
		exit;
	}

	public function rebuildCategoryTree()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$table = JTable::getInstance('Category', 'JUDirectoryTable');
		if ($table->rebuild())
		{
			$this->setRedirect("index.php?option=com_judirectory&view=tools", JText::_("COM_JUDIRECTORY_REBUILD_CATEGORY_TREE_SUCCESSFULLY"));
		}
		else
		{
			$this->setRedirect("index.php?option=com_judirectory&view=tools", JText::_("COM_JUDIRECTORY_REBUILD_CATEGORY_TREE_FAILED"));
		}
	}

	public function rebuildCommentTree()
	{
		$model = $this->getModel();
		JUDirectoryHelper::obCleanData();
		echo $model->rebuildCommentTree();
		exit;
	}

	
	

	
	

	public function cancel()
	{
		$model = $this->getModel('tools');
		$model->deleteImportFilePath();

		$this->setRedirect("index.php?option=com_judirectory&view=tools");
	}

	}
