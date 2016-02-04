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


class JUDirectoryModelListing extends JModelAdmin
{
	protected $cache = array();
	
	protected $pluginsCanEdit = array();

	
	public function getTable($type = 'Listing', $prefix = 'JUDirectoryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getFormDefault($data = array(), $loadData = true)
	{
		
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_judirectory/models/forms');
		JForm::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_judirectory/models/fields');
		$listing_xml_path = JPath::find(JForm::addFormPath(), 'listing.xml');
		$listing_xml      = JFactory::getXML($listing_xml_path, true);

		$form = new SimpleXMLElement($listing_xml->asXML());

		return $form;
	}


	
	public function getForm($data = array(), $loadData = true)
	{
		$storeId = md5(__METHOD__ . "::" . serialize($data) . "::" . (int) $loadData);
		if (!isset($this->cache[$storeId]))
		{
			
			if ($data)
			{
				$data = (object) $data;
			}
			else
			{
				$data = $this->getItem();
			}

			
			JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_judirectory/models/forms');
			JForm::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_judirectory/models/fields');
			$listing_xml_path = JPath::find(JForm::addFormPath(), 'listing.xml');
			$listing_xml      = JFactory::getXML($listing_xml_path, true);

			if ($data->id)
			{
				$templateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfListing($data->id);
				$templateFolder      = trim($templateStyleObject->folder);
				if ($templateFolder)
				{
					$template_path = JPATH_SITE . "/components/com_judirectory/templates/" . $templateFolder . "/" . $templateFolder . '.xml';
					if (JFile::exists($template_path))
					{
						$template_xml = JFactory::getXML($template_path, true);
						if ($template_xml->listing_config)
						{
							foreach ($template_xml->listing_config->children() AS $child)
							{
								$template_params_xpath = $listing_xml->xpath('//fieldset[@name="template_params"]');
								JUDirectoryHelper::appendXML($template_params_xpath[0], $child);
							}
						}

						
						if ($template_xml->languages->count())
						{
							foreach ($template_xml->languages->children() AS $language)
							{
								$languageFile = (string) $language;
								
								$first_pos       = strpos($languageFile, '.');
								$last_pos        = strrpos($languageFile, '.');
								$languageExtName = substr($languageFile, $first_pos + 1, $last_pos - $first_pos - 1);

								
								$client = JApplicationHelper::getClientInfo((string) $language->attributes()->client, true);
								$path   = isset($client->path) ? $client->path : JPATH_BASE;

								JUDirectoryFrontHelperLanguage::loadLanguageFile($languageExtName, $path);
							}
						}
					}

				}
			}

			
			$globalconfig_path = JPath::find(JForm::addFormPath(), 'globalconfig.xml');
			$globalconfig_xml  = JFactory::getXML($globalconfig_path, true);

			$display_params_fields_xpath = $globalconfig_xml->xpath('//fields[@name="display_params"]/fields[@name="listing"]');
			$display_params_xml          = $display_params_fields_xpath[0];
			if ($display_params_xml)
			{
				foreach ($display_params_xml->children() AS $child)
				{
					$display_params_xpath = $listing_xml->xpath('//fields[@name="display_params"]');
					JUDirectoryHelper::appendXML($display_params_xpath[0], $child, false, true);
				}
			}

			
			$plugin_dir = JPATH_SITE . "/plugins/judirectory/";
			$db         = JFactory::getDbo();
			$query      = "SELECT * FROM #__extensions WHERE type = 'plugin' AND folder = 'judirectory' AND enabled = 1 ORDER BY ordering ASC";
			$db->setQuery($query);
			$elements = $db->loadObjectList();

			if ($elements)
			{
				foreach ($elements AS $index => $element)
				{
					$folder    = $element->element;
					$file_path = $plugin_dir . $folder . "/$folder.xml";

					
					if (JFile::exists($file_path) && JUDirectoryHelper::canEditJUDirectoryPluginParams($folder, $index) === true)
					{
						$xml = JFactory::getXML($file_path, true);

						
						if ($xml->listing_config)
						{
							$ruleXml             = new SimpleXMLElement('<fields name="' . $folder . '"></fields>');
							$plugin_params_xpath = $listing_xml->xpath('//fields[@name="plugin_params"]');
							JUDirectoryHelper::appendXML($plugin_params_xpath[0], $ruleXml);
							$total_fieldsets = 0;
							foreach ($xml->listing_config->children() AS $child)
							{
								$total_fieldsets++;
								$child->addAttribute('plugin_name', $folder);
								$jplugin_xpath = $listing_xml->xpath('//fields[@name="' . $folder . '"]');
								JUDirectoryHelper::appendXML($jplugin_xpath[0], $child);
							}

							if ($total_fieldsets)
							{
								$pluginLabel                   = $xml->listing_config->attributes()->label ? $xml->listing_config->attributes()->label : $element->name;
								$this->pluginsCanEdit[$folder] = array('label' => $pluginLabel, 'total_fieldsets' => $total_fieldsets);
							}

							
							if (isset($xml->languages))
							{
								JUDirectoryFrontHelperLanguage::loadLanguageFile($xml->languages, JPATH_ADMINISTRATOR);
							}
						}
					}
				}
			}

			$form = $this->loadForm('com_judirectory.listing', $listing_xml->asXML(), array('control' => 'jform', 'load_data' => $loadData));

			
			if (!$loadData)
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select("field.field_name");
				$query->from("#__judirectory_fields AS field");
				$query->join("LEFT", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id");
				$query->where("field.group_id = 1 AND field.field_name != ''");
				$db->setQuery($query);
				$fieldNames = $db->loadColumn();
				foreach ($fieldNames AS $fieldName)
				{
					$form->removeField($fieldName);
				}
			}

			if (empty($form))
			{
				$this->cache[$storeId] = false;
			}

			$this->cache[$storeId] = $form;
		}

		return $this->cache[$storeId];
	}

	
	public function getScript()
	{
		return 'administrator/components/com_judirectory/models/forms/listing.js';
	}

	
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		

		if (empty($table->id))
		{
			

			
			if (!$table->created)
			{
				$table->created = $date->toSql();
			}

			
			if (!$table->created_by)
			{
				$table->created_by = $user->id;
			}
		}
		else
		{
			

			
			$table->modified = $date->toSql();

			
			$table->modified_by = $user->id;
		}

		
		$table->access = 1;

		
		$table->language = '*';

		
	}

	
	protected function canDelete($record)
	{
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		
		if ($app->isSite())
		{
			return JUDirectoryFrontHelperPermission::canDeleteListing($record->id);
		}

		$canDelete = $user->authorise('judir.listing.delete', $this->option . '.listing.' . (int) $record->id);
		if (!$canDelete)
		{
			if ($user->id)
			{
				if ($user->id == $record->created_by)
				{
					$canDeleteOwn = $user->authorise('judir.listing.delete.own', $this->option . '.listing.' . (int) $record->id);
					if ($canDeleteOwn)
					{
						return $canDeleteOwn;
					}
				}
			}

		}

		return $canDelete;
	}

	
	protected function canEditState($record)
	{
		$app = JFactory::getApplication();
		
		if ($app->isSite())
		{
			return JUDirectoryFrontHelperPermission::canEditStateListing($record);
		}

		
		return true;
	}

	
	public function getItem($pk = null)
	{
		$storeId = md5(__METHOD__ . "::" . $pk);
		if (!isset($this->cache[$storeId]))
		{
			$item = parent::getItem($pk);

			$item->cat_id = 0;

			if ($item->id)
			{
				$template_params = new JRegistry;
				$template_params->loadString($item->template_params);
				$item->template_params = $template_params->toArray();

				$plugin_params = new JRegistry;
				$plugin_params->loadString($item->plugin_params);
				$item->plugin_params = $plugin_params->toArray();

				$item->description = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

				$listingObj = JUDirectoryHelper::getListingById($item->id);
				if ($listingObj)
				{
					$item->cat_id = $listingObj->cat_id;
				}

				
				$registry = new JRegistry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}

			$this->cache[$storeId] = $item;
		}

		return $this->cache[$storeId];
	}

	
	protected function loadFormData()
	{
		
		$data = JFactory::getApplication()->getUserState('com_judirectory.edit.listing.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->preprocessData('com_judirectory.listing', $data);
		}

		return $data;
	}

	
	public function save($dataInput)
	{
		$app = JFactory::getApplication();

		
		$fieldsData = $dataInput['fieldsData'];
		$data       = $dataInput['data'];
		

		
		$dispatcher = JDispatcher::getInstance();
		$table      = $this->getTable();
		$key        = $table->getKeyName();
		$pk         = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew      = true;

		
		$categoriesField = new JUDirectoryFieldCore_categories();
		$newMainCatId    = $fieldsData[$categoriesField->id]['main'];
		
		JPluginHelper::importPlugin('content');

		$tableBeforeSave = null;

		
		try
		{
			
			if ($pk > 0)
			{
				$table->load($pk);

				
				$tableBeforeSave = clone $table;

				$isNew = false;
			}

			
			$saveListingStoreCategoryField = $this->saveListingStoreCategoryField($isNew, $pk, $fieldsData, $newMainCatId);
			if (!$saveListingStoreCategoryField)
			{
				return false;
			}

			
			$this->saveListingPrepareSave($data, $table, $isNew);

			
			$this->saveListingPrepareTemplateParams($data, $pk, $isNew, $newMainCatId);

			
			$this->saveListingPreparePluginParams($data, $pk, $isNew);

			
			$data['cat_id'] = $fieldsData[$categoriesField->id]['main'];
			
			if (!$table->bind($data))
			{
				$this->setError($table->getError());

				return false;
			}

			
			$this->prepareTable($table);

			
			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			
			$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, $table, $isNew));
			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());

				return false;
			}

			
			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}

			
			$this->saveListingRelated($dataInput, $table);

			
			$this->saveListingFields($fieldsData, $table, $isNew);

			if (!$isNew)
			{
				$publishedField = new JUDirectoryFieldCore_published();
				if (isset($fieldsData[$publishedField->id]) && $fieldsData[$publishedField->id] != $tableBeforeSave->published)
				{
					$context = $this->option . '.' . $this->name;
					$pks     = array($table->id);
					$value   = $fieldsData[$publishedField->id];
					
					$dispatcher->trigger($this->event_change_state, array($context, $pks, $value));
				}

				$approvedField = new JUDirectoryFieldCore_approved();
				if (isset($fieldsData[$approvedField->id]) && $fieldsData[$approvedField->id] != $tableBeforeSave->approved)
				{
					$context = $this->option . '.' . $this->name;
					$pks     = array($table->id);
					$value   = $fieldsData[$approvedField->id];
					
					$dispatcher->trigger('onContentApprove', array($context, $pks, $value));
				}
			}

			
			$this->cleanCache();

			
			$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, $table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$pkName = $table->getKeyName();

		if ($app->isAdmin())
		{
			if (isset($table->$pkName))
			{
				$this->setState($this->getName() . '.id', $table->$pkName);
			}
			$this->setState($this->getName() . '.new', $isNew);
		}
		else
		{
			if (isset($table->$pkName))
			{
				$this->setState('listing.id', $table->$pkName);
			}
			$this->setState('listing.new', $isNew);
		}

		
		$this->saveListingAddLog($table, $isNew);

		
		$this->saveListingSendEmail($table, $isNew, $fieldsData, $tableBeforeSave);

		return true;
	}

	
	public function saveListingStoreCategoryField($isNew, $pk, $fieldsData, $newMainCatId)
	{
		
		if (!$isNew)
		{
			$categoriesField = new JUDirectoryFieldCore_categories(null, $pk);

			if (($this->getListingSubmitType($pk) == 'submit' && $categoriesField->canSubmit())
				|| ($this->getListingSubmitType($pk) == 'edit' && $categoriesField->canEdit())
			)
			{
				$categoriesField->is_new = $isNew;
				$categoriesFieldValue    = $fieldsData[$categoriesField->id];
				$categoriesFieldValue    = $categoriesField->onSaveListing($categoriesFieldValue);
				$saveFieldCategory       = $categoriesField->storeValue($categoriesFieldValue);
				if ($saveFieldCategory)
				{
					$listingObject = JUDirectoryHelper::getListingById($pk);
					$mainCatIdDB   = $listingObject->cat_id;

					
					if ($mainCatIdDB != $newMainCatId)
					{
						$fieldGroupIdDB = JUDirectoryHelper::getCategoryById($mainCatIdDB)->fieldgroup_id;
						$fieldGroupId   = JUDirectoryHelper::getCategoryById($newMainCatId)->fieldgroup_id;

						if ($fieldGroupId != $fieldGroupIdDB)
						{
							JUDirectoryHelper::deleteFieldValuesOfListing($pk);
						}
					}
				}
				else
				{
					$this->setError('COM_JUDIRECTORY_FAIL_TO_SAVE_CATEGORY_FIELD');

					return false;
				}
			}
		}

		return true;
	}

	
	public function saveListingPrepareTemplateParams(&$data, $pk, $isNew, $newMainCatId)
	{
		$removeTemplateParams = false;

		if (!$isNew)
		{
			

			
			$oldTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfListing($pk);
			$styleId                = $data['style_id'];
			if ($styleId == -2)
			{
				$newTemplateStyleObject = JUDirectoryFrontHelperTemplate::getDefaultTemplateStyle();
			}
			elseif ($styleId == -1)
			{
				$newTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfCategory($newMainCatId);

			}
			else
			{
				$newTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleObject($styleId);
			}

			if ($oldTemplateStyleObject->template_id != $newTemplateStyleObject->template_id)
			{
				$data['template_params'] = "";
				$removeTemplateParams    = true;
			}
		}
		


		
		if (!$removeTemplateParams && isset($data['template_params']) && is_array($data['template_params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['template_params']);
			$data['template_params'] = $registry->toString();
		}
	}

	
	public function saveListingPreparePluginParams(&$data, $pk, $isNew)
	{
		$db = JFactory::getDbo();

		
		$db_plugin_params = array();
		if (!isset($data['plugin_params']) || !is_array($data['plugin_params']))
		{
			$data['plugin_params'] = array();
		}

		if (!$isNew)
		{
			$db->setQuery("SELECT plugin_params FROM #__judirectory_listings WHERE id = " . $pk);
			$rule_str      = $db->loadResult();
			$rule_registry = new JRegistry;
			$rule_registry->loadString($rule_str);
			$db_plugin_params = $rule_registry->toArray();
		}

		if (!empty($db_plugin_params))
		{
			$db->setQuery("SELECT element FROM #__extensions WHERE type='plugin' AND folder='judirectory'");
			$rule_plugin = $db->loadColumn();

			foreach ($db_plugin_params AS $key => $value)
			{
				
				if (!in_array($key, $rule_plugin))
				{
					unset($db_plugin_params[$key]);
				}

				
				if (array_key_exists($key, $data['plugin_params']))
				{
					unset($db_plugin_params[$key]);
				}
			}
		}

		$plugin_params = array_merge($db_plugin_params, $data['plugin_params']);
		$registry      = new JRegistry;
		$registry->loadArray($plugin_params);
		$data['plugin_params'] = $registry->toString();
	}

	
	public static function saveListingPrepareSave(&$data, $table, $isNew)
	{
		$app = JFactory::getApplication();

		

		if ($app->isAdmin())
		{
			

			
			if ($isNew)
			{
				$data['approved'] = 1;
				
				$data['published'] = 1;
			}
			else
			{
				$data['approved']  = $table->approved;
				$data['published'] = $table->published;
			}
		}
		else
		{
			
			if (isset($data['approved']))
			{
				$data['approved'] = $data['approved'];
			}

			if (isset($data['published']))
			{
				$data['published'] = $data['published'];
			}
		}

		$data['style_id'] = isset($data['style_id']) ? $data['style_id'] : -1;
	}

	
	public function saveListingRelated($dataInput, $table)
	{
		$relatedListingIds = $dataInput['related_listings'];

		if ($relatedListingIds)
		{
			$listingId = $table->id;

			
			$listingsRelationTable = JTable::getInstance("ListingsRelation", "JUDirectoryTable");
			foreach ($relatedListingIds AS $relatedListingOrdering => $relatedListingId)
			{
				
				if ($listingsRelationTable->load(array("listing_id" => $listingId, "listing_id_related" => $relatedListingId), true))
				{
					$listingsRelationTable->ordering = $relatedListingOrdering + 1;
					$listingsRelationTable->store();
				}
				
				else
				{
					$listingsRelationTable->bind(array("id" => 0, "listing_id" => $listingId, "listing_id_related" => $relatedListingId, "ordering" => $relatedListingOrdering + 1), true);
					$listingsRelationTable->store();
				}
			}

			$db = JFactory::getDbo();

			
			$query = "SELECT listing_id_related FROM #__judirectory_listings_relations WHERE listing_id = " . $listingId;
			$db->setQuery($query);
			$relatedListingIdsDb = $db->loadColumn();

			$removeRelatedListingIds = array_diff($relatedListingIdsDb, $relatedListingIds);
			if ($removeRelatedListingIds)
			{
				$query = "DELETE FROM #__judirectory_listings_relations WHERE listing_id = " . $listingId . " AND listing_id_related IN (" . implode(",", $removeRelatedListingIds) . ")";
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	
	public function saveListingFields($fieldsData, $table, $isNew)
	{
		$params = JUDirectoryHelper::getParams();
		$app    = JFactory::getApplication();

		$listingId = $table->id;
		$db        = JFactory::getDbo();
		$nullDate  = $db->getNullDate();
		$nowDate   = JFactory::getDate()->toSql();
		$key       = $table->getKeyName();

		$canAutoUpdate = false;
		$autoUpdate    = false;
		$originalData  = array();
		if ($this->getListingSubmitType($listingId, $table, $isNew) == 'edit')
		{
			$params       = JUDirectoryHelper::getParams();
			$fieldIds     = $params->get('required_fields_to_mark_listing_as_updated', '');
			$updatedField = JUDirectoryFrontHelperField::getField('updated', $table);

			if ($fieldIds && $updatedField && $updatedField->canEdit())
			{
				$canAutoUpdate = true;
			}

			if ($canAutoUpdate)
			{
				$fieldIds = array_unique(explode(",", $fieldIds));
				foreach ($fieldIds as $fieldId)
				{
					$field = JUDirectoryFrontHelperField::getField($fieldId, $table);
					if ($field)
					{
						$originalData[$fieldId] = serialize($field->value);
					}
				}

				$originalUpdatedFieldValue = serialize($updatedField->value);
			}
		}

		
		if ($isNew)
		{
			$categoriesField = JUDirectoryFrontHelperField::getField('cat_id', $listingId);

			if (($this->getListingSubmitType($listingId, $table, $isNew) == 'submit' && $categoriesField->canSubmit())
				|| ($this->getListingSubmitType($listingId, $table, $isNew) == 'edit' && $categoriesField->canEdit())
			)
			{
				$categoriesField->is_new = $isNew;
				$categoriesFieldValue    = $fieldsData[$categoriesField->id];
				$categoriesFieldValue    = $categoriesField->onSaveListing($categoriesFieldValue);
				$saveFieldCategory       = $categoriesField->storeValue($categoriesFieldValue);
				if (!$saveFieldCategory)
				{
					$this->setError('COM_JUDIRECTORY_FAIL_TO_SAVE_CATEGORY_FIELD');

					

					$this->delete($listingId);

					return false;
				}

				if ($canAutoUpdate && isset($originalData[$categoriesField->id]))
				{
					$categoriesField = JUDirectoryFrontHelperField::getField('cat_id', $listingId, true);
					if (serialize($categoriesField->value) !== $originalData[$categoriesField->id])
					{
						$autoUpdate = true;
					}
				}
			}
		}

		
		$form                      = $this->getFormDefault();
		$xml_field_name_publishing = array();

		$elementsInPublishing = $form->xpath('//fieldset[@name="publishing"]/field | //field[@fieldset="publishing"]');

		foreach ($elementsInPublishing AS $elementsInPublishingKey => $elementsInPublishingVal)
		{
			$elementInPublishing         = $elementsInPublishingVal->attributes();
			$xml_field_name_publishing[] = (string) $elementInPublishing['name'];
		}

		
		$query = $db->getQuery(true);
		$query->select("field.*");
		$query->select("plg.folder");
		$query->from("#__judirectory_fields AS field");
		$query->join("", "#__judirectory_fields_groups AS field_group ON field.group_id = field_group.id");
		$query->join("", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id");
		$query->join("", "#__judirectory_categories AS c ON (c.fieldgroup_id = field.group_id OR field.group_id = 1)");
		$query->join("", "#__judirectory_listings_xref AS listingxref ON (listingxref.cat_id = c.id AND listingxref.main = 1)");
		$query->join("", "#__judirectory_listings AS listing ON listingxref.listing_id = listing.id");
		$query->where("field.published = 1");
		$query->where('field.publish_up <= ' . $db->quote($nowDate));
		$query->where('(field.publish_down = ' . $db->quote($nullDate) . ' OR field.publish_down > ' . $db->quote($nowDate) . ')');
		$query->where("field_group.published = 1");
		$query->where("listing.id = $listingId");
		
		$query->where("field.field_name != '" . $key . "'");
		
		$query->where("field.field_name != 'cat_id'");

		if ($app->isSite() && !$params->get('submit_form_show_tab_publishing', 0))
		{
			if (!empty($xml_field_name_publishing))
			{
				$query->where('field.field_name NOT IN (' . implode(',', $db->quote($xml_field_name_publishing)) . ')');
			}
		}

		if (!JUDIRPROVERSION)
		{
			$query->where("field.field_name != 'approved'");
			$query->where("field.field_name != 'approved_by'");
			$query->where("field.field_name != 'approved_time'");

			$query->where("field.field_name != 'locations'");
			$query->where("field.field_name != 'addresses'");
		}
		$query->group('field.id');
		$query->order('field.ordering ASC');
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		
		foreach ($fields AS $field)
		{
			$fieldObj = JUDirectoryFrontHelperField::getField($field, $table);
			
			if (($this->getListingSubmitType($listingId, $table, $isNew) == 'submit' && $fieldObj->canSubmit())
				|| ($this->getListingSubmitType($listingId, $table, $isNew) == 'edit' && $fieldObj->canEdit())
			)
			{
				$fieldObj->fields_data = $fieldsData;
				$fieldValue            = isset($fieldsData[$field->id]) ? $fieldsData[$field->id] : "";
				
				$fieldObj->is_new = $isNew;
				$fieldValue       = $fieldObj->onSaveListing($fieldValue);
				$fieldObj->storeValue($fieldValue);
				if ($canAutoUpdate && $autoUpdate == false && isset($originalData[$fieldObj->id]))
				{
					$fieldObj = JUDirectoryFrontHelperField::getField($field, $listingId, true);
					
					if (serialize($fieldObj->value) !== $originalData[$fieldObj->id])
					{
						$autoUpdate = true;
					}
				}
			}
		}

		if ($canAutoUpdate)
		{
			if ($originalData && $autoUpdate == true)
			{
				$updatedField = JUDirectoryFrontHelperField::getField('updated', $listingId, true);
				if (serialize($updatedField->value) !== $originalUpdatedFieldValue)
				{
					$autoUpdate = false;
				}
			}

			if ($autoUpdate)
			{
				$updatedField->storeValue(JFactory::getDate()->toSql());
			}
		}
	}

	
	public function saveListingAddLog($table, $isNew)
	{
		$app = JFactory::getApplication();

		
		if ($app->isSite())
		{
			$user = JFactory::getUser();

			if ($isNew)
			{
				
				$logData = array(
					'user_id'    => $user->id,
					'event'      => 'listing.create',
					'item_id'    => $table->id,
					'listing_id' => $table->id,
					'value'      => 0,
					'reference'  => '',
				);
			}
			else
			{
				
				$logData = array(
					'user_id'    => $user->id,
					'event'      => 'listing.edit',
					'item_id'    => $table->id,
					'listing_id' => $table->id,
					'value'      => 0,
					'reference'  => '',
				);
			}

			JUDirectoryFrontHelperLog::addLog($logData);
		}
	}

	
	public function saveListingSendEmail($table, $isNew, $fieldsData, $tableBeforeSave = null)
	{
		$app = JFactory::getApplication();

		
		if ($app->isSite())
		{
			if ($isNew)
			{
				JUDirectoryFrontHelperMail::sendEmailByEvent('listing.create', $table->id);
			}
			else
			{
				JUDirectoryFrontHelperMail::sendEmailByEvent('listing.edit', $table->id);
			}
		}
	}

	
	public function copyListings($listing_id_arr, $tocat_id_arr, $copy_option_arr, $tmp_listing = false, &$fieldsData = array())
	{
		$dispatcher = JDispatcher::getInstance();
		JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
		$db       = JFactory::getDbo();
		$user     = JFactory::getUser();
		$catTable = JTable::getInstance("Category", "JUDirectoryTable");
		$table    = $this->getTable();
		if (empty($listing_id_arr))
		{
			return false;
		}

		if (empty($tocat_id_arr))
		{
			return false;
		}

		set_time_limit(0);

		$assetTable        = JTable::getInstance('Asset', 'JTable');
		$commentTable      = JTable::getInstance("Comment", "JUDirectoryTable");
		$reportTable       = JTable::getInstance("Report", "JUDirectoryTable");
		$subscriptionTable = JTable::getInstance("Subscription", "JUDirectoryTable");
		$logTable          = JTable::getInstance("Log", "JUDirectoryTable");

		$total_copied_listings = 0;
		foreach ($tocat_id_arr AS $tocat_id)
		{
			$catTable->reset();
			if (!$catTable->load($tocat_id))
			{
				continue;
			}

			
			$assetName   = 'com_judirectory.category.' . (int) $tocat_id;
			$canDoCreate = $user->authorise('judir.listing.create', $assetName);
			if (!$canDoCreate)
			{
				JError::raiseWarning(401, JText::sprintf('COM_JUDIRECTORY_CAN_NOT_CREATE_LISTING_IN_THIS_CATEGORY', $catTable->title));
				continue;
			}

			
			foreach ($listing_id_arr AS $listing_id)
			{
				$table->reset();
				if (!$table->load($listing_id))
				{
					continue;
				}

				$oldTable = $table;

				$table->id = 0;
				
				$table->cat_id = $tocat_id;
				
				do
				{
					$query = $db->getQuery(true);
					$query->SELECT('COUNT(*)');
					$query->FROM('#__judirectory_listings AS listing');
					$query->JOIN('', '#__judirectory_listings_xref AS listingxref ON listingxref.listing_id = listing.id');
					$query->JOIN('', '#__judirectory_categories AS c ON listingxref.cat_id = c.id');
					$query->WHERE('c.id = ' . $tocat_id);
					$query->WHERE('listing.alias = "' . $table->alias . '"');
					$db->setQuery($query);
					$sameAliasListing = $db->loadResult();

					if ($sameAliasListing)
					{
						$table->title = JString::increment($table->title);
						$table->alias = JApplication::stringURLSafe(JString::increment($table->alias, 'dash'));
					}
				} while ($sameAliasListing);

				
				if ($table->style_id == -1)
				{
					$old_cat_id = JUDirectoryFrontHelperCategory::getMainCategoryId($listing_id);
					
					if ($old_cat_id != $tocat_id)
					{
						$oldTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfCategory($old_cat_id);
						$newTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfCategory($tocat_id);
						if ($oldTemplateStyleObject->template_id != $newTemplateStyleObject->template_id)
						{
							if (in_array('keep_template_params', $copy_option_arr) && $tmp_listing == false)
							{
								$table->style_id = $oldTemplateStyleObject->style_id;
							}
							else
							{
								if ($tmp_listing == false)
								{
									$table->template_params = '';
								}
							}
						}
					}
				}

				
				if (!in_array('copy_rates', $copy_option_arr) && $tmp_listing == false)
				{
					$table->rating      = 0;
					$table->total_votes = 0;
				}

				
				if (!in_array('copy_hits', $copy_option_arr) && $tmp_listing == false)
				{
					$table->hits = 0;
				}

				
				if (in_array('copy_permission', $copy_option_arr))
				{
					$assetTable->reset();
					if ($assetTable->loadByName('com_judirectory.listing.' . $listing_id))
					{
						$table->setRules($assetTable->rules);
					}
					else
					{
						$table->setRules('{}');
					}
				}
				else
				{
					$table->setRules('{}');
				}

				if (!$table->check())
				{
					continue;
				}

				
				$result = $dispatcher->trigger('onContentBeforeCopy', array($this->option . '.' . $this->name, $table, $oldTable, $copy_option_arr));

				if (in_array(false, $result, true))
				{
					$this->setError($table->getError());

					return false;
				}

				if ($table->store())
				{
					$table->checkIn();
					$total_copied_listings++;
				}
				else
				{
					continue;
				}

				$newListingId = $table->id;

				
				$query = "INSERT INTO #__judirectory_listings_xref (listing_id, cat_id, main) VALUES($newListingId, $tocat_id, 1)";
				$db->setQuery($query);
				$db->execute();

				
				$ori_fieldgroup_id = JUDirectoryHelper::getFieldGroupIdByListingId($listing_id);

				$copy_extra_fields = in_array("copy_extra_fields", $copy_option_arr);
				if ($copy_extra_fields)
				{
					$copy_extra_fields = $ori_fieldgroup_id == $catTable->fieldgroup_id ? true : false;
				}

				
				$query = $db->getQuery(true);
				$query->select("field.*");
				$query->from("#__judirectory_fields AS field");
				$query->select("plg.folder");
				$query->join("", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id");
				if ($copy_extra_fields && $ori_fieldgroup_id)
				{
					$query->where("field.group_id IN (1, $ori_fieldgroup_id)");
				}
				else
				{
					$query->where("field.group_id = 1");
				}
				$query->order('field.group_id, field.ordering');
				$db->setQuery($query);
				$fields = $db->loadObjectList();

				foreach ($fields AS $field)
				{
					$fieldObj = JUDirectoryFrontHelperField::getField($field, $listing_id);
					$fieldObj->onCopy($newListingId, $fieldsData);
				}

				
				if (in_array('copy_related_listings', $copy_option_arr))
				{
					$query = "INSERT INTO `#__judirectory_listings_relations` (listing_id, listing_id_related, ordering) SELECT $newListingId, listing_id_related, ordering FROM `#__judirectory_listings_relations` WHERE listing_id = $listing_id";
					$db->setQuery($query);
					$db->execute();
				}

				
				if (in_array('copy_rates', $copy_option_arr))
				{
					$ratingMapping = array();

					$query = "SELECT * FROM #__judirectory_rating WHERE listing_id = $listing_id";
					$db->setQuery($query);
					$ratings = $db->loadObjectList();
					if (count($ratings))
					{
						$criteriagroup_id = JUDirectoryHelper::getCriteriaGroupIdByListingId($listing_id);
						foreach ($ratings AS $rating)
						{
							$oldRatingId        = $rating->id;
							$rating->id         = 0;
							$rating->listing_id = $newListingId;

							if ($db->insertObject('#__judirectory_rating', $rating, 'id'))
							{
								if (JUDirectoryHelper::hasMultiRating() && $criteriagroup_id && $criteriagroup_id == $catTable->criteriagroup_id)
								{
									
									JUDirectoryMultiRating::copyCriteriaValue($rating->id, $oldRatingId);
								}

								
								$ratingMapping[$oldRatingId] = $rating->id;
							}
						}
					}
				}

				
				if (in_array('copy_comments', $copy_option_arr))
				{
					$query = "SELECT id FROM #__judirectory_comments WHERE listing_id=" . $listing_id . " AND parent_id = 1";
					$db->setQuery($query);
					$commentIds = $db->loadColumn();
					
					$commentMapping = array();
					WHILE (!empty($commentIds))
					{
						
						$commentId = array_shift($commentIds);
						
						$query = "SELECT id FROM #__judirectory_comments WHERE listing_id=" . $listing_id . " AND parent_id = $commentId";
						$db->setQuery($query);
						$_commentIds = $db->loadColumn();
						foreach ($_commentIds AS $_commentId)
						{
							
							if (!in_array($_commentId, $commentIds))
							{
								array_push($commentIds, $_commentId);
							}
						}

						
						$commentTable->load($commentId, true);
						$commentTable->id         = 0;
						$commentTable->listing_id = $newListingId;
						$commentTable->parent_id  = isset($commentMapping[$commentTable->parent_id]) ? $commentMapping[$commentTable->parent_id] : 0;
						
						if (in_array('copy_rates', $copy_option_arr))
						{
							$commentTable->rating_id = isset($ratingMapping[$commentTable->rating_id]) ? $ratingMapping[$commentTable->rating_id] : 0;
						}
						$commentTable->store();

						$new_comment_id = $commentTable->id;
						
						$commentMapping[$commentId] = $new_comment_id;

						
						$query = "SELECT * FROM #__judirectory_reports WHERE `item_id` = $commentId AND `type` = 'comment'";
						$db->setQuery($query);
						$reports = $db->loadObjectList();
						if ($reports)
						{
							foreach ($reports AS $report)
							{
								$reportTable->reset();
								if ($reportTable->bind($report) && $reportTable->check())
								{
									$reportTable->id      = 0;
									$reportTable->item_id = $new_comment_id;
									$reportTable->store();
								}
								else
								{
									continue;
								}
							}
						}

						
						$query = "SELECT * FROM #__judirectory_subscriptions WHERE `item_id` = $commentId AND `type` = 'comment'";
						$db->setQuery($query);
						$subscriptions = $db->loadObjectList();
						if ($subscriptions)
						{
							foreach ($subscriptions AS $subscription)
							{
								$subscriptionTable->reset();
								if ($subscriptionTable->bind($subscription) && $subscriptionTable->check())
								{
									$subscriptionTable->id      = 0;
									$subscriptionTable->item_id = $new_comment_id;
									$subscriptionTable->store();
								}
								else
								{
									continue;
								}
							}
						}
					}
				}

				
				if (in_array('copy_reports', $copy_option_arr))
				{
					
					$query = "SELECT * FROM #__judirectory_reports WHERE `item_id` = $listing_id AND `type` = 'listing'";
					$db->setQuery($query);
					$reports = $db->loadObjectList();
					if ($reports)
					{
						foreach ($reports AS $report)
						{
							$reportTable->reset();
							if ($reportTable->bind($report) && $reportTable->check())
							{
								$reportTable->id      = 0;
								$reportTable->item_id = $newListingId;
								$reportTable->store();
							}
							else
							{
								continue;
							}
						}
					}
				}

				
				if (in_array('copy_subscriptions', $copy_option_arr))
				{
					$query = "SELECT * FROM #__judirectory_subscriptions WHERE `item_id` = $listing_id AND `type` = 'listing'";
					$db->setQuery($query);
					$subscriptions = $db->loadObjectList();
					if ($subscriptions)
					{
						foreach ($subscriptions AS $subscription)
						{
							$subscriptionTable->reset();
							if ($subscriptionTable->bind($subscription) && $subscriptionTable->check())
							{
								$subscriptionTable->id      = 0;
								$subscriptionTable->item_id = $newListingId;
								$subscriptionTable->store();
							}
							else
							{
								continue;
							}
						}
					}
				}

				
				if (in_array('copy_logs', $copy_option_arr))
				{
					$query = "SELECT * FROM #__judirectory_logs WHERE (`listing_id` = $listing_id)";
					$db->setQuery($query);
					$logs = $db->loadObjectList();
					if ($logs)
					{
						foreach ($logs AS $log)
						{
							$logTable->reset();
							if ($logTable->bind($log) && $logTable->check())
							{
								$logTable->id         = 0;
								$logTable->item_id    = $newListingId;
								$logTable->listing_id = $newListingId;
								$logTable->store();
							}
							else
							{
								continue;
							}
						}
					}
				}

				
				if ($tmp_listing)
				{
					return $newListingId;
				}

				
				$this->cleanCache();

				
				$dispatcher->trigger('onContentAfterCopy', array($this->option . '.' . $this->name, $table, $oldTable, $copy_option_arr));
			}
		}

		return $total_copied_listings;
	}

	
	public function copyAndMap($listingArr, $catArr, $copyOptionsArr, $case = null, &$fieldsData = array())
	{
		
		$listingIdToCopy     = (int) $listingArr[0];
		$listingObjectToCopy = JUDirectoryHelper::getListingById($listingIdToCopy);
		$copiedListingId     = $this->copyListings($listingArr, $catArr, $copyOptionsArr, $tmp = true, $fieldsData);
		$copiedListingObject = JUDirectoryHelper::getListingById($copiedListingId);

		

		if ($case == 'save2copy')
		{
			$titleField = new JUDirectoryFieldCore_title();
			$aliasField = new JUDirectoryFieldCore_alias();

			if ($fieldsData[$aliasField->id] == $listingObjectToCopy->alias)
			{
				$fieldsData[$aliasField->id] = $copiedListingObject->alias;
				if ($fieldsData[$titleField->id] == $listingObjectToCopy->title)
				{
					$fieldsData[$titleField->id] = $copiedListingObject->title;
				}
			}
		}

		
		return $copiedListingObject->id;
	}

	
	public function moveListings($listing_id_arr, $tocat_id, $move_option_arr = array())
	{
		$dispatcher = JDispatcher::getInstance();
		$user       = JFactory::getUser();
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
		$catTable = JTable::getInstance("Category", "JUDirectoryTable");
		if ($tocat_id)
		{
			if (!$catTable->load($tocat_id))
			{
				JError::raiseWarning(500, JText::_('COM_JUDIRECTORY_TARGET_CATEGORY_NOT_FOUND'));

				return false;
			}

			$table     = $this->getTable();
			$db        = JFactory::getDbo();
			$assetName = 'com_judirectory.category.' . (int) $tocat_id;
			
			$query = 'SELECT id FROM #__assets WHERE name="' . $assetName . '"';
			$db->setQuery($query);
			$tocat_asset_id = $db->loadResult();
			$canCreate      = $user->authorise('judir.listing.create', $assetName);
			if (!$canCreate)
			{
				JError::raiseError(100, JText::sprintf('COM_JUDIRECTORY_CAN_NOT_CREATE_LISTING_IN_THIS_CATEGORY', $catTable->title));

				return false;
			}
		}
		else
		{
			JError::raiseWarning(500, JText::_('COM_JUDIRECTORY_NO_TARGET_CATEGORY_SELECTED'));

			return false;
		}

		if (empty($listing_id_arr))
		{
			JError::raiseError(100, JText::_('COM_JUDIRECTORY_NO_ITEM_SELECTED'));

			return false;
		}

		set_time_limit(0);

		$moved_listings = array();
		foreach ($listing_id_arr AS $listing_id)
		{
			if (!$table->load($listing_id))
			{
				continue;
			}
			$assetName = 'com_judirectory.listing.' . (int) $listing_id;
			$canDoEdit = $user->authorise('judir.listing.edit', $assetName);
			if (!$canDoEdit)
			{
				if (!$user->id)
				{
					JError::raiseWarning(100, JText::sprintf('COM_JUDIRECTORY_YOU_DONT_HAVE_PERMISSION_TO_ACCESS_LISTING', $table->title));
					continue;
				}
				else
				{
					if (($user->id == $table->created_by))
					{
						$canDoEditOwn = $user->authorise('judir.listing.edit.own', $assetName);
						if (!$canDoEditOwn)
						{
							JError::raiseWarning(100, JText::sprintf('COM_JUDIRECTORY_YOU_DONT_HAVE_PERMISSION_TO_ACCESS_LISTING', $table->title));
							continue;
						}
					}
				}
			}

			
			$query = "SELECT cat_id FROM #__judirectory_listings_xref WHERE listing_id = " . $listing_id . " AND main=1";
			$db->setQuery($query);
			$cat_id = $db->loadResult();
			
			if ($tocat_id == $cat_id)
			{
				continue;
			}

			
			$result = $dispatcher->trigger($this->onContentBeforeMove, array($this->option . '.' . $this->name, $table, $tocat_id, $move_option_arr));
			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());

				return false;
			}

			
			if ($table->style_id == -1)
			{
				$oldTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfCategory($cat_id);
				$newTemplateStyleObject = JUDirectoryFrontHelperTemplate::getTemplateStyleOfCategory($tocat_id);
				if ($oldTemplateStyleObject->template_id != $newTemplateStyleObject->template_id)
				{
					if (in_array('keep_template_params', $move_option_arr))
					{
						$table->style_id = $oldTemplateStyleObject->id;
					}
					else
					{
						
						$query = "UPDATE #__judirectory_listings SET template_params = '' WHERE id=" . $listing_id;
						$db->setQuery($query);
						$db->execute();
					}
				}
			}


			
			$query = "SELECT COUNT(*) FROM #__judirectory_listings_xref WHERE cat_id=" . $tocat_id . " AND listing_id=" . $listing_id . " AND main=0";
			$db->setQuery($query);
			$is_secondary_cat = $db->loadResult();

			
			if ($is_secondary_cat)
			{
				
				$query = "DELETE FROM #__judirectory_listings_xref WHERE listing_id=" . $listing_id . " AND main=1";
				$db->setQuery($query);
				$db->execute();

				
				$query = "UPDATE #__judirectory_listings_xref SET main=1 WHERE cat_id=" . $tocat_id . " AND listing_id=" . $listing_id;
				$db->setQuery($query);
				$db->execute();
			}
			
			else
			{
				
				$query = "UPDATE #__judirectory_listings_xref SET cat_id=" . $tocat_id . " WHERE listing_id=" . $listing_id . " AND main=1";
				$db->setQuery($query);
				$db->execute();
			}

			if (in_array('keep_permission', $move_option_arr))
			{
				
				$query = 'UPDATE #__assets SET `parent_id` = ' . $tocat_asset_id . ' WHERE name="com_judirectory.listing.' . $listing_id . '"';
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				
				$query = 'UPDATE #__assets SET `parent_id` = ' . $tocat_asset_id . ', `rules` = "{}" WHERE name="com_judirectory.listing.' . $listing_id . '"';
				$db->setQuery($query);
				$db->execute();
			}

			$moved_listings[] = $listing_id;

			
			$this->cleanCache();

			
			$dispatcher->trigger($this->onContentAfterMove, array($this->option . '.' . $this->name, $table, $tocat_id, $move_option_arr));
		}

		$total_moved_listings = count($moved_listings);
		if ($total_moved_listings)
		{
			$old_field_groupid = JUDirectoryHelper::getCategoryById($cat_id)->fieldgroup_id;
			$new_field_groupid = JUDirectoryHelper::getCategoryById($tocat_id)->fieldgroup_id;
			$keep_extra_fields = in_array("keep_extra_fields", $move_option_arr);
			if ($keep_extra_fields)
			{
				$keep_extra_fields = $old_field_groupid == $new_field_groupid ? true : false;
			}

			
			if (!$keep_extra_fields)
			{
				foreach ($moved_listings AS $listing_id)
				{
					
					JUDirectoryHelper::deleteFieldValuesOfListing($listing_id);
				}
			}

			$old_criteria_groupid = JUDirectoryHelper::getCategoryById($cat_id)->criteriagroup_id;
			$new_criteria_groupid = JUDirectoryHelper::getCategoryById($tocat_id)->criteriagroup_id;
			$keep_rates           = in_array("keep_rates", $move_option_arr);
			if ($keep_rates)
			{
				$keep_rates = $old_criteria_groupid == $new_criteria_groupid ? true : false;
			}

			if (!$keep_rates)
			{
				JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
				$ratingTable = JTable::getInstance("Rating", "JUDirectoryTable");
				foreach ($moved_listings AS $listing_id)
				{
					$query = "SELECT id FROM #__judirectory_rating WHERE listing_id = " . $listing_id;
					$db->setQuery($query);
					$ratingIds = $db->loadColumn();
					foreach ($ratingIds AS $ratingId)
					{
						$ratingTable->delete($ratingId);
					}
				}
			}
		}

		return $total_moved_listings;
	}

	public function getPlugins()
	{
		return $this->pluginsCanEdit;
	}


	
	public function validateFields($fieldsData, $listingId)
	{
		$app    = JFactory::getApplication();
		$params = JUDirectoryHelper::getParams();

		
		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();
		$error    = false;

		$isNew = $listingId == 0 ? true : false;

		$categoriesField = JUDirectoryFrontHelperField::getField('cat_id', $listingId);

		if (($this->getListingSubmitType($listingId) == 'submit' && $categoriesField->canSubmit())
			|| ($this->getListingSubmitType($listingId) == 'edit' && $categoriesField->canEdit())
		)
		{
			$fieldValueCategories         = $fieldsData[$categoriesField->id];
			$categoriesField->is_new      = $isNew;
			$categoriesField->fields_data = $fieldsData;
			$fieldValueCategories         = $categoriesField->filterField($fieldValueCategories);
			$valid                        = $categoriesField->PHPValidate($fieldValueCategories);

			if ($valid === true)
			{
				$fieldsData[$categoriesField->id] = $fieldValueCategories;
				$catId                            = $fieldsData[$categoriesField->id]['main'];
			}
			
			else
			{
				$this->setError($valid);
				if ($isNew)
				{
					return false;
				}
				$catId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
				$error = true;
				unset($fieldsData[$categoriesField->id]);
			}
		}
		else
		{
			$catId = JUDirectoryFrontHelperCategory::getMainCategoryId($listingId);
		}

		
		$form                      = $this->getFormDefault();
		$xml_field_name_publishing = array();

		$elementsInPublishing = $form->xpath('//fieldset[@name="publishing"]/field | //field[@fieldset="publishing"]');

		foreach ($elementsInPublishing AS $elementsInPublishingKey => $elementsInPublishingVal)
		{
			$elementInPublishing         = $elementsInPublishingVal->attributes();
			$xml_field_name_publishing[] = (string) $elementInPublishing['name'];
		}

		
		$query = $db->getQuery(true);
		$query->select("field.*");
		$query->from("#__judirectory_fields AS field");
		$query->select("plg.folder");
		$query->join("", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id");
		$query->join("", "#__judirectory_fields_groups AS field_group ON field_group.id = field.group_id");
		$query->join("", "#__judirectory_categories AS c ON (c.fieldgroup_id = field.group_id OR field.group_id = 1 )");
		$query->where("field_group.published = 1");
		$query->where("field.published = 1");
		$query->where('field.publish_up <= ' . $db->quote($nowDate));
		$query->where('(field.publish_down = ' . $db->quote($nullDate) . ' OR field.publish_down >= ' . $db->quote($nowDate) . ')');
		$query->where("(c.id = " . $catId . " OR field.group_id = 1)");
		
		$query->where("field.field_name != 'cat_id'");
		if ($app->isSite() && !$params->get('submit_form_show_tab_publishing', 0))
		{
			if (!empty($xml_field_name_publishing))
			{
				$query->where('field.field_name NOT IN (' . implode(',', $db->quote($xml_field_name_publishing)) . ')');
			}
		}
		$query->group('field.id');

		if (!JUDIRPROVERSION)
		{
			$query->where("field.field_name != 'locations'");
			$query->where("field.field_name != 'addresses'");
		}

		$db->setQuery($query);
		$fields = $db->loadObjectList();

		
		foreach ($fields AS $field)
		{
			$fieldObj = JUDirectoryFrontHelperField::getField($field, $listingId);
			
			if (($this->getListingSubmitType($listingId) == 'submit' && $fieldObj->canSubmit())
				|| ($this->getListingSubmitType($listingId) == 'edit' && $fieldObj->canEdit())
			)
			{
				$fieldValue            = isset($fieldsData[$field->id]) ? $fieldsData[$field->id] : null;
				$fieldObj->is_new      = $isNew;
				$fieldObj->fields_data = $fieldsData;
				$fieldValue            = $fieldObj->filterField($fieldValue);
				$valid                 = $fieldObj->PHPValidate($fieldValue);
				
				if ($valid === true)
				{
					$fieldsData[$field->id] = $fieldValue;
				}
				else
				{
					$error = true;
					unset($fieldsData[$field->id]);
					$this->setError($valid);
				}
			}
		}

		if ($error)
		{
			return false;
		}
		else
		{
			return $fieldsData;
		}
	}

	
	public function getFieldLocations()
	{
		return null;
	}


	
	public function getCoreFields($fieldSet)
	{
		$db                   = JFactory::getDbo();
		$nullDate             = $db->getNullDate();
		$nowDate              = JFactory::getDate()->toSql();
		$app                  = JFactory::getApplication();
		$listingId            = $app->input->getInt('id', 0);
		$form                 = $this->getForm();
		$xml_field_name_array = $sorted_field_arr = array();
		
		foreach ($form->getFieldset($fieldSet) AS $key => $field)
		{
			
			if ($field->fieldname != 'cat_id')
			{
				$xml_field_name_array[] = $field->fieldname;
			}
		}

		if ($xml_field_name_array)
		{
			
			$query = $db->getQuery(true);
			$query->select("field.*, plg.folder FROM #__judirectory_fields AS field");
			$query->join("LEFT", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id");
			if ($fieldSet == "details")
			{
				$query->where("(field.field_name IN ('" . implode("', '", $xml_field_name_array) . "') OR (field.group_id = 1 AND field.field_name = '' AND plg.folder != 'locations' ))");
			}
			else
			{
				$query->where("field.field_name IN ('" . implode("', '", $xml_field_name_array) . "')");
			}
			
			$query->where("field.field_name != 'cat_id' AND plg.folder != 'core_gallery'");
			$query->order("field.ordering, field.id ASC");
			$db->setQuery($query);
			$fields = $db->loadObjectList();

			$ordering = 0;
			if ($fields)
			{
				foreach ($fields AS $keyField => $field)
				{
					$fieldObj = JUDirectoryFrontHelperField::getField($field, $listingId);

					if (($this->getListingSubmitType($listingId) == 'submit' && $fieldObj->canSubmit())
						|| ($this->getListingSubmitType($listingId) == 'edit' && $fieldObj->canEdit())
					)
					{
						$ordering++;
						$sorted_field_arr[$ordering] = $fieldObj;
					}

					
					$index_field_name = array_search($field->field_name, $xml_field_name_array);
					if ($index_field_name !== false)
					{
						unset($xml_field_name_array[$index_field_name]);
					}
				}
			}

			
			if ($xml_field_name_array)
			{
				foreach ($xml_field_name_array AS $field_name)
				{
					$ordering++;
					$sorted_field_arr[$ordering] = $field_name;
				}
			}
		}

		return $sorted_field_arr;
	}

	
	public function getExtraFields()
	{
		return array();
	}

	public function getRelatedListings()
	{
		$app               = JFactory::getApplication();
		$listing_id        = $app->input->getInt('id', 0);
		$relatedListingIds = $app->getUserState("com_judirectory.edit.listing.related_listings", array());
		$related_listings  = array();
		if ($relatedListingIds)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('listing.id, listing.title, listing.image');
			$query->from('#__judirectory_listings AS listing');
			$query->where('listing.id IN (' . implode(',', $relatedListingIds) . ')');
			$db->setQuery($query);
			$related_listings = $db->loadObjectList();
		}
		elseif ($listing_id)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('listing.id, listing.title, listing.image');
			$query->from('#__judirectory_listings_relations AS listingrel');
			$query->join('INNER', '#__judirectory_listings AS listing ON listingrel.listing_id_related = listing.id');
			$query->where('listingrel.listing_id = ' . $listing_id);
			$query->order('listingrel.ordering ASC');
			$db->setQuery($query);
			$related_listings = $db->loadObjectList();
		}

		if ($related_listings)
		{
			foreach ($related_listings AS $listing)
			{
				$listing->image_src = JUDirectoryHelper::getListingImage($listing->image);
			}
		}

		return $related_listings;
	}

	
	public function getGalleryField()
	{
		$app          = JFactory::getApplication();
		$docId        = $app->input->getInt('id', 0);
		$galleryField = JUDirectoryFrontHelperField::getField('gallery', $docId);
		if (($this->getListingSubmitType($docId) == 'submit' && $galleryField->canSubmit())
			|| ($this->getListingSubmitType($docId) == 'edit' && $galleryField->canEdit())
		)
		{
			return $galleryField;
		}

		return null;
	}

	
	public function feature(&$pks, $value = 1)
	{
		$app = JFactory::getApplication();
		
		if ($app->isSite())
		{
			return false;
		}

		
		$dispatcher = JDispatcher::getInstance();
		$user       = JFactory::getUser();
		$table      = $this->getTable();
		$pks        = (array) $pks;

		
		JPluginHelper::importPlugin('content');

		
		foreach ($pks AS $i => $pk)
		{
			$table->reset();

			if ($table->load($pk))
			{
				if (!$this->canEditState($table))
				{
					
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));

					return false;
				}
			}
		}

		
		if (!$table->feature($pks, $value, $user->get('id')))
		{
			$this->setError($table->getError());

			return false;
		}

		$context = $this->option . '.' . $this->name;

		
		$result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));

		if (in_array(false, $result, true))
		{
			$this->setError($table->getError());

			return false;
		}

		
		$this->cleanCache();

		return true;
	}

	
	public function getListingSubmitType($listingId, $listingObject = null, $isNew = null)
	{
		
		if (!is_null($isNew))
		{
			if ($isNew)
			{
				return 'submit';
			}
		}

		if ($listingId == 0)
		{
			return 'submit';
		}

		if (!is_object($listingObject))
		{
			$listingObject = JUDirectoryHelper::getListingById($listingId);
		}

		
		if ($listingObject->approved == 0)
		{
			return 'submit';
		}
		
		else
		{
			return 'edit';
		}
	}
}
