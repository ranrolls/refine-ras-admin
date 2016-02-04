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

class JUDirectoryModelCsvprocess extends JModelAdmin
{
	public $messages = array();

	public $ignoredFields = array('asset_id', 'introtext', 'fulltext');

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		
		$form = $this->loadForm('com_judirectory.csvconfig', 'csvconfig', array('control' => 'jform', 'load_data' => $loadData), true);
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	
	protected function loadFormData()
	{
		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');

		return isset($dataCSV['csv_config']) ? $dataCSV['csv_config'] : array();
	}

	
	public function getExportForm($data = array(), $loadData = true)
	{
		
		$form = $this->loadForm('com_judirectory.csvexport', 'csvexport', array('control' => 'jform', 'load_data' => $loadData), true);
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	public function getCoreFields()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('field.*, plg.folder');
		$query->from('#__judirectory_fields AS field');
		$query->join('', '#__judirectory_plugins AS plg ON plg.id = field.plugin_id');
		$query->where('field.group_id = 1');
		if ($this->ignoredFields)
		{
			$query->where('field.field_name NOT IN (' . implode(',', $db->quote($this->ignoredFields)) . ')');
		}
		$query->order('field.ordering');

		$db->setQuery($query);

		$fields            = $db->loadObjectList();
		$fieldNameHasClass = array();
		$coreFields        = array();
		if ($fields)
		{
			foreach ($fields AS $field)
			{
				$fieldClass = JUDirectoryFrontHelperField::getField($field);
				if ($fieldClass)
				{
					$coreFields[] = $fieldClass;
					if ($fieldClass->field_name)
					{
						$fieldNameHasClass[] = $fieldClass->field_name;
					}
				}
			}
		}

		$listingTable = JTable::getInstance('Listing', 'JUDirectoryTable');
		$fieldNames   = $listingTable->getProperties();
		foreach ($fieldNames AS $fieldName => $value)
		{
			if (in_array($fieldName, $this->ignoredFields))
			{
				continue;
			}

			if (!in_array($fieldName, $fieldNameHasClass))
			{
				$coreFields[] = $fieldName;
			}
		}

		return $coreFields;
	}

	public function getExtraFields()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('field.*, plg.folder');
		$query->from('#__judirectory_fields AS field');
		$query->join('', '#__judirectory_plugins AS plg ON plg.id = field.plugin_id');
		if ($this->ignoredFields)
		{
			$query->where('field.id NOT IN (' . implode(',', $db->quote($this->ignoredFields)) . ')');
		}

		$query->where('field.group_id > 1');

		$query->order('field.group_id, field.ordering');

		$db->setQuery($query);

		$fields = $db->loadObjectList();

		$extraFields = array();
		if ($fields)
		{
			foreach ($fields AS $field)
			{
				$fieldClass = JUDirectoryFrontHelperField::getField($field);
				if ($fieldClass)
				{
					$extraFields[] = $fieldClass;
				}
			}
		}

		return $extraFields;
	}

	

	
	public function loadCSVFile()
	{
		$app  = JFactory::getApplication();
		$file = $app->input->files->get('file');
		if ($file)
		{
			$dataCSV = $app->getUserState('csv.dataCSV');
			
			if (!JFile::copy($file['tmp_name'], $dataCSV['csv_import_dir'] . $file['name']))
			{
				$this->setError(JText::printf("COM_JUDIRECTORY_CSV_PROCESS_FAIL_TO_COPY_S_TO_S_FILE", $file['tmp_name'], $dataCSV['csv_import_dir'] . $file['name']));

				return false;
			}

			$dataCSV['csv_file_path'] = $dataCSV['csv_import_dir'] . $file['name'];
			$delimiter                = $app->input->post->getString('delimiter', ',');
			$dataCSV['csv_delimiter'] = $delimiter;
			$enclosure                = $app->input->post->getString('enclosure', '"');
			$dataCSV['csv_enclosure'] = $enclosure;

			$app->setUserState('csv.dataCSV', $dataCSV);
		}

		$dataCSV = $app->getUserState('csv.dataCSV');

		if (!isset($dataCSV['csv_file_path']) || !JFile::exists($dataCSV['csv_file_path']))
		{
			$this->setError(JText::_('COM_JUDIRECTORY_CSV_FILE_NOT_FOUND'));

			return false;
		}

		
		if (strtolower(JFile::getExt($dataCSV['csv_file_path'])) != 'csv')
		{
			$this->setError(JText::_('COM_JUDIRECTORY_CSV_FILE_IS_INVALID'));

			return false;
		}

		$csvRows = JUDirectoryHelper::getCSVData($dataCSV['csv_file_path'], $dataCSV['csv_delimiter'], $dataCSV['csv_enclosure'], 'r+', 0, null, true);
		if ($csvRows === false)
		{
			$this->setError(JText::_('COM_JUDIRECTORY_CSV_CANNOT_READ_FILE'));

			return false;
		}

		return true;
	}

	
	public function getCSVColumns()
	{
		$app        = JFactory::getApplication();
		$dataCSV    = $app->getUserState('csv.dataCSV');
		$csvRows    = JUDirectoryHelper::getCSVData($dataCSV['csv_file_path'], $dataCSV['csv_delimiter'], $dataCSV['csv_enclosure'], 'r+', 0, null, true);
		$csvColumns = array_shift($csvRows);

		$mappedColumns = array();
		$db            = JFactory::getDbo();

		foreach ($csvColumns AS $column)
		{
			$query = $db->getQuery(true);
			$query->select('field.*, plg.folder')
				->from('#__judirectory_fields AS field')
				->join('', '#__judirectory_plugins AS plg ON plg.id = field.plugin_id');

			
			if (preg_match('/^.*\[(\d+)\]$/', $column, $matches))
			{
				$id = $matches[1];
				$query->where('field.id = ' . $id);
			}
			elseif (is_string($column))
			{
				$query->where('( field.field_name = ' . $db->quote($column) . ' OR field.caption = ' . $db->quote($column) . ' )');
			}

			$db->setQuery($query);

			$field = $db->loadObject();

			
			if ($field)
			{
				$fieldObj = JUDirectoryFrontHelperField::getField($field);
				if ($fieldObj && $fieldObj->canImport())
				{
					$mappedColumns[$column] = $field->id;
				}
			}
			else
			{
				$mappedColumns[$column] = $column;
			}
		}

		
		if (!count($mappedColumns))
		{
			$this->setError(JText::_('COM_JUDIRECTORY_CSV_FILE_IS_EMPTY'));

			return false;
		}

		$dataCSV['csv_columns']    = $csvColumns;
		$dataCSV['csv_total_rows'] = count($csvRows);
		$app->setUserState('csv.dataCSV', $dataCSV);

		return $mappedColumns;
	}

	
	public function mapFields()
	{
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();

		
		$assignedFields = $app->input->get('assign', array(), 'array');

		if (!empty($assignedFields))
		{
			$dataCSV               = $app->getUserState('csv.dataCSV');
			$db                    = JFactory::getDbo();
			$csvColumnFieldCaption = array();

			foreach ($assignedFields AS $key => $field)
			{
				
				if (is_numeric($field))
				{
					$query = $db->getQuery(true);
					$query->select('caption')
						->from('#__judirectory_fields')
						->where('id = ' . $field);

					$db->setQuery($query);
					$fieldCaption = $db->loadResult();

					$csvColumnFieldCaption[$key] = $fieldCaption;
				}
				else
				{
					$csvColumnFieldCaption[$key] = $field;
				}
			}

			$dataCSV['csv_assigned_fields'] = $assignedFields;
			
			$dataCSV['csv_array_map_column_fieldcaption'] = $csvColumnFieldCaption;

			$app->setUserState('csv.dataCSV', $dataCSV);
		}

		$dataCSV = $app->getUserState('csv.dataCSV');

		if (!isset($dataCSV['csv_assigned_fields']) || !$dataCSV['csv_assigned_fields'])
		{
			$this->setError('COM_JUDIRECTORY_CSV_CANNOT_MAP_FIELDS');

			return false;
		}
		else
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__judirectory_fields')
				->where('field_name = "title" OR field_name = "id"');
			$db->setQuery($query);
			$requiredFields = $db->loadColumn();

			
			if (!in_array($requiredFields[0], $dataCSV['csv_assigned_fields']) && !in_array($requiredFields[1], $dataCSV['csv_assigned_fields']))
			{
				$this->setError(JText::_("COM_JUDIRECTORY_ID_OR_TITLE_FIELD_IS_REQUIRED"), 'error');

				return false;
			}

			return true;
		}
	}

	
	public function config()
	{
		$app    = JFactory::getApplication();
		$config = $app->input->post->get('jform', array(), 'array');
		if ($config)
		{
			$dataCSV               = $app->getUserState('csv.dataCSV');
			$dataCSV['csv_config'] = $config;
			$app->setUserState("csv.dataCSV", $dataCSV);
		}

		$dataCSV = $app->getUserState('csv.dataCSV');

		if (!isset($dataCSV['csv_config']) || !$dataCSV['csv_config'])
		{
			$this->setError('COM_JUDIRECTORY_CSV_CONFIG_MISSING');

			return false;
		}
		else
		{
			return true;
		}
	}

	
	public function combineCSVColumnNameWithColumnValue()
	{
		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');

		
		
		$config = $dataCSV['csv_config'];
		
		$csvPath = $dataCSV['csv_file_path'];
		
		$delimiter = $dataCSV['csv_delimiter'];
		
		$enclosure = $dataCSV['csv_enclosure'];

		
		$start = $app->input->getInt('start', 0);

		
		$limit = $config['limit'];

		
		$csvData = JUDirectoryHelper::getCSVData($csvPath, $delimiter, $enclosure, 'r+', $start, $limit, false);

		
		$columns = $dataCSV['csv_assigned_fields'];

		
		foreach ($csvData AS $index => $data)
		{
			if (count($columns) == count($data))
			{
				$csvData[$index] = array_combine($columns, $data);
			}
			else
			{
				unset($csvData[$index]);
			}
		}

		return $csvData;
	}

	
	public function getImportType(&$data, $config)
	{
		
		$idFieldId = JUDirectoryFrontHelperField::getField('id')->id;
		if (!isset($data[$idFieldId]) || !$data[$idFieldId])
		{
			$listingId = 0;

			$type = 1;
		}
		else
		{
			$listingId = $data[$idFieldId];

			$listing = JUDirectoryHelper::getListingById($listingId);

			if (!$listing)
			{
				$listingId = 0;

				$type = 1;
			}
			else
			{
				
				$saveOption = isset($config['save_options']) ? $config['save_options'] : 'update';

				switch ($saveOption)
				{
					
					case 'update':
					default:
						$type = 2;
						break;

					
					case 'create':
						$articleId = 0;
						$type      = 1;
						break;

					
					case 'ignore':
						$type = 0;
						break;
				}
			}
		}

		$data['id'] = $data[$idFieldId] = $listingId;

		return $type;
	}

	
	public function prepareDataToImport($data, $importType, $config = array())
	{
		
		
		$defaultData = array('cat_id' => $data['main_cat']);

		$isInsert = $importType == 1 ? true : false;

		
		if ($isInsert)
		{
			
			$defaultData['publish_up'] = JFactory::getDate()->toSql();

			
			if ($config['force_publish'] != "")
			{
				
				$pulishFieldId        = JUDirectoryFrontHelperField::getField('published')->id;
				$data[$pulishFieldId] = $config['force_publish'];
			}

			if (!empty($config['created_by']) && $config['created_by'] > 0)
			{
				$defaultData['created_by'] = $config['created_by'];
			}

			$defaultData['language'] = '*';

			$defaultData['access'] = 1;

			$defaultData['style_id'] = -1;

			$defaultData['layout'] = -1;

			$defaultData['approved'] = 1;

			$params = array(
				'display_params' => array(
					'show_comment' => -2,
					'fields'       => array(
						'title'   => array(
							'detail_view' => -2,
						),
						'created' => array(
							'detail_view' => -2,
						),
						'author'  => array(
							'detail_view' => -2,
						),
						'cat_id'  => array(
							'detail_view' => -2,
						),
						'rating'  => array(
							'detzail_view' => -2,
						),
					),
				),
			);

			$defaultData['params'] = json_encode($params);
		}

		
		
		
		$aliasFieldId = JUDirectoryFrontHelperField::getField('alias')->id;
		$titleFieldId = JUDirectoryFrontHelperField::getField('title')->id;
		$newTitle     = (isset($data[$titleFieldId]) && $data[$titleFieldId]) ? $data[$titleFieldId] : "";
		$newAlias     = (isset($data[$aliasFieldId]) && $data[$aliasFieldId]) ? $data[$aliasFieldId] : "";

		
		if (!$newAlias)
		{
			
			if ($data['id'])
			{
				
				if ($newTitle && $config['rebuild_alias'])
				{
					$data['alias'] = JApplication::stringURLSafe($newTitle);
					
				}
				else
				{
					$listing       = JUDirectoryHelper::getListingById($data['id']);
					$data['alias'] = $listing->alias;
				}
				
			}
			else
			{
				$data['alias'] = JApplication::stringURLSafe($newTitle);
			}
		}
		else
		{
			$data['alias'] = $newAlias;
		}

		
		if (!$newTitle)
		{
			$listing       = JUDirectoryHelper::getListingById($data['id']);
			$data['title'] = $listing->title;
		}
		else
		{
			$data['title'] = $newTitle;
		}

		$this->titleIncrement($data['main_cat'], $data['id'], $defaultData['alias']);
		$data[$titleFieldId] = $data['title'];
		$data[$aliasFieldId] = $data['alias'];

		
		$related_listings = !empty($data['related_listings']) ? explode(',', $data['related_listings']) : array();

		
		$fieldsData = array();

		foreach ($data AS $key => $value)
		{
			
			if (is_numeric($key))
			{
				$fieldsData[$key] = $value;
			}
			
			elseif (is_string($key) && $key != 'related_listings')
			{
				$defaultData[$key] = $value;
			}
		}

		
		$postData = array(
			'main_cat'         => $data['main_cat'],
			'data'             => $defaultData,
			'fieldsData'       => $fieldsData,
			'related_listings' => $related_listings
		);

		return $postData;
	}

	
	public function titleIncrement($catId, $listingId, &$alias)
	{
		$db = $this->getDbo();

		do
		{
			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__judirectory_listings AS listing')
				->join('', '#__judirectory_listings_xref AS listingxref on listing.id = listingxref.listing_id')
				->where('listingxref.cat_id = ' . $catId)
				->where('listingxref.main = 1')
				->where('listing.alias = ' . $db->quote($alias));
			if ($listingId > 0)
			{
				$query->where('listing.id != ' . $listingId);
			}

			$db->setQuery($query);
			$result = $db->loadResult();

			if ($result > 0)
			{
				$alias = JString::increment($alias, 'dash');
			}

		} while ($result);

		return true;
	}

	
	public function importData($importData, $config = array(), $start = 0)
	{
		if (empty($importData))
		{
			return false;
		}

		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');

		$indexRow = $start + 1;
		foreach ($importData AS $index => $data)
		{
			$indexRow++;

			if (is_object($data))
			{
				$data = get_object_vars($data);
			}

			
			$importType = $this->getImportType($data, $config);
			if ($importType == 0)
			{
				
				$this->addLog($indexRow, $importType, JText::_('COM_JUDIRECTORY_CSV_PROCESS_SKIP_LISTING_MESSAGE'));
				$dataCSV['total_skip']++;
				continue;
			}

			
			$titleFieldId = JUDirectoryFrontHelperField::getField('title')->id;
			if (!$data['id'] && (!isset($data[$titleFieldId]) || !$data[$titleFieldId]))
			{
				$this->addLog($index, $importType, JText::_('COM_JUDIRECTORY_CSV_PROCESS_EMPTY_ID_AND_TITLE'), 'Error');
				$dataCSV['total_error']++;
				continue;
			}

			
			$categoryFieldId = JUDirectoryFrontHelperField::getField('cat_id')->id;
			
			if (isset($data[$categoryFieldId]) && $data[$categoryFieldId])
			{
				$data['main_cat'] = explode(",", $data[$categoryFieldId])[0];
			}
			
			else
			{
				
				if ($data['id'])
				{
					$data['main_cat'] = JUDirectoryFrontHelperCategory::getMainCategoryId($data['id']);
				}
				
				else
				{
					$data['main_cat'] = $config['default_main_cat_id'];
				}
			}

			if (!$data['main_cat'])
			{
				$this->addLog($index, $importType, JText::_('COM_JUDIRECTORY_CSV_PROCESS_EMPTY_CAT'), 'Error');
				continue;
			}

			
			$data = $this->prepareDataToImport($data, $importType, $config);

			if (JUDirectoryHelper::hasCSVPlugin())
			{
				$JUDirectoryCsv = new JUDirectoryCSV($this);
				
				if ($JUDirectoryCsv->insertUpdateListing($data, $indexRow, $importType))
				{
					if ($importType == 1)
					{
						$dataCSV['total_insert']++;
					}
					else
					{
						$dataCSV['total_update']++;
					}
				}
				else
				{
					$dataCSV['total_error']++;
				}
			}
		}

		$app->setUserState('csv.dataCSV', $dataCSV);

		
		$this->writeToLogFile();
	}

	
	public function import()
	{
		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');
		$csvData = $this->combineCSVColumnNameWithColumnValue();

		$start  = $app->input->getInt('start', 0);
		$config = $dataCSV['csv_config'];

		$this->importData($csvData, $config, $start);

		$dataCSV      = $app->getUserState('csv.dataCSV');
		$totalCSVRows = $dataCSV['csv_total_rows'];

		$message = '';
		
		if ($start + $config['limit'] >= $totalCSVRows)
		{
			if (JFolder::exists($dataCSV['csv_import_dir']))
			{
				JFolder::delete($dataCSV['csv_import_dir']);
			}

			$message .= '<ul>';
			if ($dataCSV['total_skip'])
			{
				$message .= '<li>';
				$message .= JText::plural('COM_JUDIRECTORY_CSV_N_LISTING_SKIPPED', $dataCSV['total_skip']);
				$message .= '</li>';
			}

			if ($dataCSV['total_update'])
			{
				$message .= '<li>';
				$message .= JText::plural('COM_JUDIRECTORY_CSV_N_LISTING_UPDATED', $dataCSV['total_update']);
				$message .= '</li>';
			}

			if ($dataCSV['total_insert'])
			{
				$message .= '<li>';
				$message .= JText::plural('COM_JUDIRECTORY_CSV_N_LISTING_INSERTED', $dataCSV['total_insert']);
				$message .= '</li>';
			}

			if ($dataCSV['total_error'])
			{
				$message .= '<li>';
				$message .= JText::plural('COM_JUDIRECTORY_CSV_N_LISTING_CAN_NOT_BE_SAVED', $dataCSV['total_error']);
				$message .= '</li>';
			}

			$message .= '<li>';
			$message .= JText::sprintf('COM_JUDIRECTORY_CSV_YOU_CAN_DOWNLOAD_LOG_FILE_HERE', 'index.php?option=com_judirectory&task=csvprocess.downloadLog&fileName=' . $dataCSV['log_file_name']);
			$message .= '</li>';

			$message .= '</ul>';

			$app->setUserState('csv.dataCSV', array());
		}

		

		return array(
			'processed' => $config['limit'],
			'message'   => $message,
			'total'     => $totalCSVRows
		);
	}

	
	public function writeToLogFile()
	{
		$app     = JFactory::getApplication();
		$dataCSV = $app->getUserState('csv.dataCSV');
		$logPath = $app->get('log_path', JPATH_SITE . '/logs') . '/' . $dataCSV['log_file_name'];
		if (!empty($this->messages))
		{
			if (!JFolder::exists(dirname($logPath)))
			{
				JFolder::create(dirname($logPath));

				$file_index = dirname($logPath) . '/index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			if (!JFile::exists($logPath))
			{
				array_unshift($this->messages, array_keys($this->messages[0]));
			}

			
			$this->array2csv($this->messages, $logPath, 'a');
		}
	}

	
	public function array2csv($data, $path = 'php://output', $mode = 'w')
	{
		if (count($data) == 0)
		{
			return null;
		}

		ob_start();

		$file = fopen($path, $mode);

		

		foreach ($data AS $row)
		{
			fputcsv($file, $row);
		}

		fclose($file);

		return ob_get_clean();
	}

	
	public function addLog($rowN, $importType, $notice, $result = 'Success')
	{
		
		$processArray = array(
			'0' => JText::_('Skip'),
			'1' => JText::_('Insert'),
			'2' => JText::_('Update')
		);

		$error = array(
			'row'     => $rowN,
			'process' => $processArray[$importType],
			'result'  => $result,
			'notice'  => is_array($notice) ? implode(' | ', $notice) : $notice
		);

		$this->messages[] = $error;
	}
	

	
	
	public function export()
	{
		
		$app           = JFactory::getApplication();
		$filter        = $app->input->post->get('jform', array(), 'array');
		$exportColumns = $app->input->post->get('col', array(), 'array');
		$file_name     = $filter['csv_export_file_name'] ? $filter['csv_export_file_name'] : "Export_";
		
		$data = $this->getExportData($exportColumns, $filter);

		
		JUDirectoryHelper::obCleanData();
		$file_name = str_replace(' ', '_', $file_name) . "-" . date("Y-m-d") . ".csv";
		$this->downloadSendHeaders($file_name);

		echo chr(239) . chr(187) . chr(191); 
		$csv_data = $this->array2csv($data);
		echo $csv_data;
		exit;
	}

	
	public function getExportData($exportColumns, $filter)
	{
		$exportData = array();
		$start      = 0;
		$limit      = 0;
		if (isset($filter['csv_limit_export']) && $filter['csv_limit_export'])
		{
			if (strpos($filter['csv_limit_export'], ',') !== false)
			{
				list($start, $limit) = explode(',', $filter['csv_limit_export']);
			}
			else
			{
				$limit = (int) $filter['csv_limit_export'];
			}
		}

		
		if (JUDirectoryHelper::hasCSVPlugin())
		{
			$JUDirectoryCsv = new JUDirectoryCSV($this);
			$listings       = $JUDirectoryCsv->getListings($exportColumns, $filter, $start, $limit);
		}

		if (!empty($listings))
		{
			foreach ($listings AS $listing)
			{
				$data = array();
				foreach ($exportColumns AS $exportColumn)
				{
					if (is_numeric($exportColumn))
					{
						$field = JUDirectoryFrontHelperField::getField($exportColumn, $listing);
						if ($field && $field->canExport())
						{
							$data[$field->getCaption(true) . ' [' . $field->id . ']'] = $field->onExport();
						}
					}
					elseif (isset($listing->$exportColumn))
					{
						$data[$exportColumn] = $listing->$exportColumn;
					}
				}

				
				if (in_array('related_listings', $exportColumns))
				{
					$data['related_listings'] = $listing->related_listings;
				}

				$exportData[] = $data;
			}
		}

		$columns = array_keys($exportData[0]);
		array_unshift($exportData, $columns);

		return $exportData;
	}

	public function downloadSendHeaders($filename)
	{
		
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");

		
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
}