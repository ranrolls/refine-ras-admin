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


jimport('joomla.database.tablenested');

class JUDirectoryTableAddress extends JTableNested
{
	public static $getPath;
	public static $getTree;

	
	public function __construct(&$db)
	{
		parent::__construct('#__judirectory_addresses', 'id', $db);
	}

	
	public function getPath($pk = null, $diagnostic = false)
	{
		$k  = $this->_tbl_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		if (!isset(self::$getPath[$pk][(int) $diagnostic]))
		{
			self::$getPath[$pk][(int) $diagnostic] = parent::getPath($pk, $diagnostic);
		}

		return self::$getPath[$pk][(int) $diagnostic];
	}

	
	public function getTree($pk = null, $diagnostic = false)
	{
		$k  = $this->_tbl_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		if (!isset(self::$getTree[$pk][(int) $diagnostic]))
		{
			self::$getTree[$pk][(int) $diagnostic] = parent::getTree($pk, $diagnostic);
		}

		return self::$getTree[$pk][(int) $diagnostic];
	}

	
	public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
	{
		
		if ($parentId === null)
		{
			
			$parentId = $this->getRootId();
			if ($parentId === false)
			{
				return false;
			}
		}

		
		if (!isset($this->_cache['rebuild.sql']))
		{
			$query = $this->_db->getQuery(true);
			$query->select($this->_tbl_key);
			$query->from($this->_tbl);
			$query->where('parent_id = %d');

			
			if (property_exists($this, 'ordering'))
			{
				$query->order('parent_id, ordering, lft');
			}
			else
			{
				$query->order('parent_id, lft');
			}
			$this->_cache['rebuild.sql'] = (string) $query;
		}

		

		
		$this->_db->setQuery(sprintf($this->_cache['rebuild.sql'], (int) $parentId));
		$children = $this->_db->loadObjectList();

		
		$rightId = $leftId + 1;

		
		foreach ($children AS $node)
		{
			
			
			
			$rightId = $this->rebuild($node->{$this->_tbl_key}, $rightId, $level + 1);

			
			if ($rightId === false)
			{
				return false;
			}
		}

		
		
		$query = $this->_db->getQuery(true);
		$query->update($this->_tbl);
		$query->set('lft = ' . (int) $leftId);
		$query->set('rgt = ' . (int) $rightId);
		$query->set('level = ' . (int) $level);
		$query->where($this->_tbl_key . ' = ' . (int) $parentId);
		$this->_db->setQuery($query);

		
		if (!$this->_db->execute())
		{
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_REBUILD_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);

			return false;
		}

		
		return $rightId + 1;
	}

	
	public function check()
	{
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_JUDIRECTORY_TITLE_MUST_NOT_BE_EMPTY'));

			return false;
		}

		if (trim($this->alias) == '')
		{
			$this->alias = $this->title;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		if (trim(str_replace('&nbsp;', '', $this->description)) == '')
		{
			$this->description = '';
		}

		
		
		

		return true;
	}
}
