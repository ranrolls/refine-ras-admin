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


class JUDirectoryModelFieldGroup extends JModelAdmin
{

	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		if ($record->id == 1)
		{
			return false;
		}

		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_judirectory.fieldgroup.' . (int) $record->id);
		}
		else
		{
			return parent::canEditState($record);
		}
	}

	
	public function getTable($type = 'FieldGroup', $prefix = 'JUDirectoryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		
		$form = $this->loadForm('com_judirectory.fieldgroup', 'fieldgroup', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		if ($data)
		{
			$data = (object) $data;
		}
		else
		{
			$data = $this->getItem();
		}

		
		if (!$this->canEditState($data))
		{
			
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			
			
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}

		if ($data->id == 1)
		{
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			
			
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}

		return $form;
	}

	
	public function getScript()
	{
		return 'administrator/components/com_judirectory/models/forms/fieldgroup.js';
	}

	
	protected function loadFormData()
	{
		
		$data = JFactory::getApplication()->getUserState('com_judirectory.edit.fieldgroup.data', array());
		if (empty($data))
		{
			$data = $this->getItem();
		}

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->preprocessData('com_judirectory.fieldgroup', $data);
		}

		return $data;
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

			
			$db = JFactory::getDbo();
			$db->setQuery('SELECT MAX(ordering) FROM #__judirectory_fields_groups');
			$max             = $db->loadResult();
			$table->ordering = $max + 1;
		}
		else
		{
			$table->modified_by = $user->id;
			$table->modified    = $date->toSql();
		}
	}

	
	protected function canDelete($record)
	{
		if ($record->id == 1)
		{
			JError::raiseWarning(500, JText::sprintf('COM_JUDIRECTORY_CAN_NOT_DELETE_FIELD_GROUP_X_BECAUSE_IT_IS_CORE_FIELD_GROUP', $record->name));

			return false;
		}

		$user = JFactory::getUser();

		return $user->authorise('core.delete', $this->option . '.fieldgroup.' . (int) $record->id);
	}

	
	public function save($data)
	{
		if(!$data['id'])
		{
			return false;
		}
		
		
		$dispatcher = JDispatcher::getInstance();
		$table      = $this->getTable();
		$key        = $table->getKeyName();
		$pk         = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew      = true;

		
		JPluginHelper::importPlugin('content');

		
		try
		{
			
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;
			}

			
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

			
			$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));
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

			
			
			
			$db            = JFactory::getDbo();
			$fieldgroup_id = $table->id;
			if (!isset($data['assigntocats']))
			{
				$data['assigntocats'] = array();
			}

			$query = "SELECT id FROM #__judirectory_categories WHERE fieldgroup_id =" . $fieldgroup_id . ' AND selected_fieldgroup != -1';
			$db->setQuery($query);
			$catid_has_this_exgr = $db->loadColumn();
			$cat_remove_exgr     = array_diff($catid_has_this_exgr, $data['assigntocats']);
			
			if (!empty($cat_remove_exgr))
			{
				$query = "UPDATE #__judirectory_categories SET selected_fieldgroup = 0, fieldgroup_id = 0 WHERE id IN (" . implode(',', $cat_remove_exgr) . ")";
				$db->setQuery($query);
				$db->execute();
				foreach ($cat_remove_exgr AS $cat_remove)
				{
					$listing_id_arr = JUDirectoryHelper::getListingIdsByCatId($cat_remove);
					foreach ($listing_id_arr AS $listingId)
					{
						JUDirectoryHelper::deleteFieldValuesOfListing($listingId);
					}

					JUDirectoryHelper::changeInheritedFieldGroupId($cat_remove, 0);
				}
			}

			$cat_add_exgr = array_diff($data['assigntocats'], $catid_has_this_exgr);
			if ($cat_add_exgr)
			{
				$query = "UPDATE #__judirectory_categories SET selected_fieldgroup = $fieldgroup_id, fieldgroup_id = $fieldgroup_id WHERE id IN (" . implode(', ', $cat_add_exgr) . ")";
				$db->setQuery($query);
				$db->execute();
				foreach ($cat_add_exgr AS $add_exgr)
				{
					JUDirectoryHelper::changeInheritedFieldGroupId($add_exgr, $fieldgroup_id);
				}
			}

			
			if ($table->field_ordering_type == 1)
			{
				$app             = JFactory::getApplication();
				$fields_ordering = $app->input->post->get("fields_ordering", array(), 'array');

				if ($fields_ordering)
				{
					JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');
					$fieldOrderingTable = JTable::getInstance("FieldOrdering", "JUDirectoryTable");
					$db                 = $this->getDbo();
					$query              = "SELECT id FROM #__judirectory_fields WHERE group_id = 1 OR group_id = " . $table->id;
					$db->setQuery($query);
					$field_ids = $db->loadColumn();
					$ordering  = 0;
					foreach ($fields_ordering AS $key => $field_id)
					{
						if (in_array($field_id, $field_ids))
						{
							$ordering++;
							$fieldOrderingTable->reset();
							if ($fieldOrderingTable->load(array("item_id" => $fieldgroup_id, "type" => "fieldgroup", "field_id" => $field_id)))
							{
								$fieldOrderingTable->bind(array("ordering" => $ordering));
							}
							else
							{
								$fieldOrderingTable->bind(array("id" => 0, "item_id" => $fieldgroup_id, "type" => "fieldgroup", "field_id" => $field_id, "ordering" => $ordering));
							}

							$fieldOrderingTable->store();
						}
					}
				}
			}

			
			$this->cleanCache();

			
			$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$pkName = $table->getKeyName();

		if (isset($table->$pkName))
		{
			$this->setState($this->getName() . '.id', $table->$pkName);
		}
		$this->setState($this->getName() . '.new', $isNew);

		return true;
	}

	
	public function savePriority($pks = null, $priority = null)
	{
		
		$table      = $this->getTable();
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		
		foreach ($pks AS $i => $pk)
		{
			$table->load((int) $pk);

			
			if (!$this->canEditState($table))
			{
				
				unset($pks[$i]);
				JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
			elseif ($table->priority != $priority[$i])
			{
				$table->priority = $priority[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());

					return false;
				}

				
				$condition = $this->getRepriorityConditions($table);
				$found     = false;

				foreach ($conditions AS $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key          = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		
		foreach ($conditions AS $cond)
		{
			$table->load($cond[0]);
			$table->repriority($cond[1]);
		}

		
		$this->cleanCache();

		return true;
	}
}
