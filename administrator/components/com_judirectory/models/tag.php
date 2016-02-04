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


class JUDirectoryModelTag extends JModelAdmin
{
	protected $cache = array();

	
	public function getTable($type = 'Tag', $prefix = 'JUDirectoryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		
		$form = $this->loadForm('com_judirectory.tag', 'tag', array('control' => 'jform', 'load_data' => $loadData));
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

		return $form;
	}

	
	protected function loadFormData()
	{
		
		$data = JFactory::getApplication()->getUserState('com_judirectory.edit.tag.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		if (JUDirectoryHelper::isJoomla3x())
		{
			$this->preprocessData('com_judirectory.tag', $data);
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
			$db->setQuery('SELECT MAX(ordering) FROM #__judirectory_tags');
			$max             = $db->loadResult();
			$table->ordering = $max + 1;
		}
		else
		{
			$table->modified_by = $user->id;
			$table->modified    = $date->toSql();
		}
	}

	
	public function getItem($pk = null)
	{
		$pk      = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$storeId = md5(__METHOD__ . "::" . $pk);
		if (!isset($this->cache[$storeId]))
		{
			$item = parent::getItem($pk);
			if ($item->id)
			{
				
				$registry = new JRegistry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}

			$this->cache[$storeId] = $item;
		}

		return $this->cache[$storeId];
	}

}