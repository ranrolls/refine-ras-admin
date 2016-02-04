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

class JUDirectoryInstallerHelper
{
	
	public function createJUDIRMenu()
	{
		$menu    = array('name'   => 'JU Directory',
		                 'alias'  => JFilterOutput::stringURLSafe('ju-directory'),
		                 'link'   => 'index.php?option=com_judirectory&view=tree&id=1',
		                 'access' => 1,
		                 'params' => array());
		$submenu = array(/*'index' => array('name'   => JText::_('COM_JUDIRECTORY_DASHBOARD'),
			                 'alias'  => JFilterOutput::stringURLSafe(JText::_('COM_JUDIRECTORY_DASHBOARD')),
			                 'link'   => 'index.php?option=com_judirectory&view=dashboard',
			                 'access' => 1,
			                 'params' => array())*/
		);

		
		$lang  = JFactory::getLanguage();
		$debug = $lang->setDebug(false);

		$this->createMenu($menu, $submenu);
		$this->cleanCache();
		$lang->setDebug($debug);
	}

	public function createMenu($menu, $submenu)
	{
		jimport('joomla.utilities.string');
		jimport('joomla.application.component.helper');

		$component_id = JComponentHelper::getComponent('com_judirectory')->id;

		
		$db    = JFactory::getDbo();
		$query = "UPDATE #__menu SET component_id=" . $db->quote($component_id) . " WHERE type = 'component' AND link LIKE '%option=com_judirectory%'";
		$db->setQuery($query);
		$db->execute();
		if ($db->getErrorNum())
			throw new Exception ($db->getErrorMsg(), $db->getErrorNum());

		$table = JTable::getInstance('MenuType');
		$data  = array(
			'menutype'    => 'judirectorymenu',
			'title'       => 'JU Directory',
			'description' => ''
		);
		if (!$table->bind($data) || !$table->check())
		{
			
			return true;
		}

		if (!$table->store())
		{
			throw new Exception ($table->getError());
		}

		
		$table = JTable::getInstance('menu');
		$table->load(array('menutype' => 'judirectorymenu', 'link' => $menu ['link']));
		$paramdata = array('menu-anchor_title'     => '',
		                   'menu-anchor_css'       => '',
		                   'menu_image'            => '',
		                   'menu_text'             => 1,
		                   'page_title'            => '',
		                   'show_page_heading'     => 0,
		                   'page_heading'          => '',
		                   'pageclass_sfx'         => '',
		                   'menu-meta_description' => '',
		                   'menu-meta_keywords'    => '',
		                   'robots'                => '',
		                   'secure'                => 0);

		$gparams = new JRegistry($paramdata);

		$params = clone $gparams;
		$params->loadArray($menu['params']);
		$data = array(
			'menutype'     => 'judirectorymenu',
			'title'        => $menu ['name'],
			'alias'        => $menu ['alias'],
			'link'         => $menu ['link'],
			'type'         => 'component',
			'published'    => 1,
			'parent_id'    => 1,
			'component_id' => $component_id,
			'access'       => $menu ['access'],
			'params'       => (string) $params,
			'home'         => 0,
			'language'     => '*',
			'client_id'    => 0
		);
		$table->setLocation(1, 'last-child');
		if (!$table->bind($data) || !$table->check() || !$table->store())
		{
			
			$table->alias = 'joomultra-directory';
			if (!$table->check() || !$table->store())
			{
				throw new Exception ($table->getError());
			}
		}

		$parent = $table;
		
		foreach ($submenu as $menuitem)
		{
			$params = clone $gparams;
			$params->loadArray($menuitem['params']);
			$table = JTable::getInstance('menu');
			$table->load(array('menutype' => 'judirectorymenu', 'link' => $menuitem ['link']));
			$data = array(
				'menutype'     => 'judirectorymenu',
				'title'        => $menuitem ['name'],
				'alias'        => $menuitem ['alias'],
				'link'         => $menuitem ['link'],
				'type'         => 'component',
				'published'    => 1,
				'parent_id'    => $parent->id,
				'component_id' => $component_id,
				'access'       => $menuitem ['access'],
				'params'       => (string) $params,
				'home'         => 0,
				'language'     => '*',
				'client_id'    => 0
			);
			$table->setLocation($parent->id, 'last-child');
			if (!$table->bind($data) || !$table->check() || !$table->store())
			{
				throw new Exception ($table->getError());
			}
		}

		
		$defaultmenu = JMenu::getInstance('site')->getDefault();
		if (!$defaultmenu) return true;
		$table = JTable::getInstance('menu');
		$table->load(array('menutype' => $defaultmenu->menutype, 'type' => 'alias', 'title' => 'JU Directory'));
		if (!$table->id)
		{
			$data = array(
				'menutype'     => $defaultmenu->menutype,
				'title'        => 'JU Directory',
				'alias'        => 'judirectory-' . JFactory::getDate()->format('Y-m-d'),
				'link'         => 'index.php?Itemid=' . $parent->id,
				'type'         => 'alias',
				'published'    => 0,
				'parent_id'    => 1,
				'component_id' => 0,
				'access'       => 1,
				'params'       => '{"aliasoptions":"' . (int) $parent->id . '","menu-anchor_title":"","menu-anchor_css":"","menu_image":""}',
				'home'         => 0,
				'language'     => '*',
				'client_id'    => 0
			);
			$table->setLocation(1, 'last-child');
		}
		else
		{
			$data = array(
				'alias'  => 'judirectory-' . JFactory::getDate()->format('Y-m-d'),
				'link'   => 'index.php?Itemid=' . $parent->id,
				'params' => '{"aliasoptions":"' . (int) $parent->id . '","menu-anchor_title":"","menu-anchor_css":"","menu_image":""}',
			);
		}
		if (!$table->bind($data) || !$table->check() || !$table->store())
		{
			throw new Exception ($table->getError());
		}

		return true;
	}

	public function deleteJUDIRMenu()
	{
		$table = JTable::getInstance('MenuType');
		$table->load(array('menutype' => 'judirectorymenu'));
		if ($table->id)
		{
			$success = $table->delete();
			if (!$success)
			{
				JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
			}
		}

		$this->cleanCache();
	}

	public function cleanCache()
	{
		
		$cache = JFactory::getCache();
		$cache->clean('mod_menu');
	}
}
