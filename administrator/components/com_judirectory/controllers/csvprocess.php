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

class JUDirectoryControllerCSVProcess extends JControllerForm
{
	public function getModel($name = 'CSVProcess', $prefix = 'JUDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function cancel($key = null)
	{
		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');
		if (trim($dataCSV['csv_import_dir']) && JFolder::exists($dataCSV['csv_import_dir']))
		{
			JFolder::delete($dataCSV['csv_import_dir']);
		}

		$app->setUserState('csv.dataCSV', array());
		$this->setRedirect(JRoute::_('index.php?option=com_judirectory&view=csvprocess', false));

		return true;
	}

	public function getGroupIdsByCats()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$cats = $app->input->get('cats', array(), 'array');

		$groupIds = array();
		foreach ($cats AS $cat)
		{
			$db->setQuery("SELECT fieldgroup_id FROM #__judirectory_categories WHERE id = " . $cat);
			if ($db->loadResult())
			{
				$groupIds[] = $db->loadResult();
			}
		}
		$groupIds = array_unique($groupIds);

		JUDirectoryHelper::obCleanData();
		echo json_encode($groupIds);
		exit;
	}

	public function import()
	{
		$app = JFactory::getApplication();
		
		$dataCSV = array();

		
		$config     = JFactory::getConfig();
		$tmp_folder = JPath::clean($config->get('tmp_path') . "/" . uniqid("judir_csvimport_") . "/");

		if (!JFolder::exists($tmp_folder))
		{
			if (JFolder::create($tmp_folder))
			{
				$dataCSV['csv_import_dir'] = $tmp_folder;
			}
			else
			{
				$this->setError(JText::sprintf("COM_JUDIRECTORY_CSV_PROCESS_CAN_NOT_CREATE_S_TEMPORARY_FOLDER", $tmp_folder));

				return false;
			}
		}

		
		$dataCSV['log_file_name'] = 'com_judirectory.importlog.' . JFactory::getUser()->id . '.' . time() . '.csv';
		$dataCSV['total_insert']  = 0;
		$dataCSV['total_update']  = 0;
		$dataCSV['total_skip']    = 0;
		$dataCSV['total_error']   = 0;

		$app->setUserState('csv.dataCSV', $dataCSV);

		$this->setRedirect('index.php?option=com_judirectory&view=csvprocess&layout=import');

		return true;
	}

	
	public function loadCSVFile()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel();

		if ($model->loadCSVFile())
		{
			$mappedColumns = $model->getCSVColumns();
		}
		else
		{
			$this->redirect(JRoute::_('index.php?option=com_judirectory&view=csvprocess&layout=import', false), $model->getError(), 'error');

			return false;
		}

		if ($mappedColumns === false)
		{
			$this->redirect(JRoute::_('index.php?option=com_judirectory&view=csvprocess&layout=import', false), $model->getError(), 'error');

			return false;
		}


		
		
		$view = $this->getView('csvprocess', 'html');
		$view->assignRef('mapped_columns', $mappedColumns);
		$view->setLayout('fields_mapping');
		$view->setModel($model, true);
		$view->display();

		return $this;
	}

	
	public function mapFields()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel();
		if (!$model->mapFields())
		{
			$this->redirect(JRoute::_('index.php?option=com_judirectory&view=csvprocess&layout=import', false), $model->getError(), 'error');

			return false;
		}

		$view = $this->getView('csvprocess', 'html');
		$view->setLayout('config');
		$view->setModel($model, true);
		$view->display();

		return true;
	}

	
	public function config()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel();
		if ($model->config() === false)
		{
			$this->redirect(JRoute::_('index.php?option=com_judirectory&view=csvprocess&layout=import', false), $model->getError(), 'error');

			return false;
		}

		
		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');
		$review  = array(
			'csv_columns'                       => $dataCSV['csv_columns'],
			'csv_assigned_fields'               => $dataCSV['csv_assigned_fields'],
			'csv_array_map_column_fieldcaption' => $dataCSV['csv_array_map_column_fieldcaption'],
			'config'                            => $dataCSV['csv_config']
		);

		$view = $this->getView('csvprocess', 'html');
		$view->assignRef('review', $review);
		$view->setModel($model, true);
		$view->setLayout('review');
		$view->display();

		return true;
	}

	public function review()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$this->setRedirect('index.php?option=com_judirectory&view=csvprocess&layout=processing');

		return true;
	}

	public function importProcessing()
	{
		$model = $this->getModel();

		$result = $model->import();
		JUDirectoryHelper::obCleanData();
		echo json_encode($result);
		exit;
	}

	public function downloadLog()
	{
		$app      = JFactory::getApplication();
		$fileName = $app->input->get('fileName', '', 'string');
		$logPath  = $app->get('log_path', JPATH_SITE . '/logs') . '/' . $fileName;
		if (JFile::exists($logPath))
		{
			
			$resume         = 1;
			$speed          = 500;
			$downloadResult = JUDirectoryHelper::downloadFile($logPath, 'com_judirectory.importlog.csv', 'php', $speed, $resume);
			if ($downloadResult !== true)
			{
				echo $downloadResult;
				exit;
			}
		}
		else
		{
			echo JText::_('COM_JUDIRECTORY_FILE_NOT_FOUND');
		}

		exit;
	}

	public function export()
	{
		$this->setRedirect('index.php?option=com_judirectory&view=csvprocess&layout=export');

		return true;
	}

	public function exportProcessing()
	{
		
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel();
		$model->export();

		return true;
	}
}