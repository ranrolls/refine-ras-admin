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
defined('_JEXEC') or die ('Restricted access');

JLoader::register('JUDirectoryInstallerHelper', __DIR__ . '/admin/helpers/installer.php');
JLoader::register('JUDirectoryInstallerHelper', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/installer.php');




class Com_JUDirectoryInstallerScript
{
	
	public function install($parent)
	{
	}

	public function discover_install($parent)
	{
	}

	
	public function uninstall($parent)
	{
		
		$JUDirectoryInstallerHelper = new JUDirectoryInstallerHelper();
		$JUDirectoryInstallerHelper->deleteJUDIRMenu();
	}

	
	public function update($parent)
	{
		
		
		

		$old_version = $this->getOldVersion();
	}

	
	public function preflight($type, $parent)
	{
		
		$phpVersion = floatval(phpversion());
		if ($phpVersion < 5)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Installation was unsuccessful because you are using an unsupported version of PHP. JUDirectory supports only PHP5 and above. Please kindly upgrade your PHP version and try again.', 'error');

			return false;
		}
	}

	
	public function postflight($type, $parent)
	{
		
		$JUDirectoryInstallerHelper = new JUDirectoryInstallerHelper();
		$JUDirectoryInstallerHelper->createJUDIRMenu();

		if ($type == 'install')
		{
			$db   = JFactory::getDbo();
			$date = JFactory::getDate();
			$user = JFactory::getUser();

			
			$query = "UPDATE #__judirectory_emails SET `created` ='" . $date->tosql() . "', `created_by` = " . $user->id . ", `checked_out` = 0, `checked_out_time` = '0000-00-00 00:00:00', `modified_by` = 0, `modified` = '0000-00-00 00:00:00'";
			$db->setQuery($query);
			$db->execute();

			
			$query = "UPDATE #__judirectory_fields_groups SET `created` ='" . $date->tosql() . "', `created_by` = " . $user->id . ", `checked_out` = 0, `checked_out_time` = '0000-00-00 00:00:00', `modified_by` = 0, `modified` = '0000-00-00 00:00:00'";
			$db->setQuery($query);
			$db->execute();

			
			$query = "UPDATE #__judirectory_fields SET `created` ='" . $date->tosql() . "', `created_by` = " . $user->id . ", `checked_out` = 0, `checked_out_time` = '0000-00-00 00:00:00', `modified_by` = 0, `modified` = '0000-00-00 00:00:00'";
			$db->setQuery($query);
			$db->execute();

			
			$query = "UPDATE #__judirectory_template_styles SET `created` ='" . $date->tosql() . "', `created_by` = " . $user->id . ", `checked_out` = 0, `checked_out_time` = '0000-00-00 00:00:00', `modified_by` = 0, `modified` = '0000-00-00 00:00:00'";
			$db->setQuery($query);
			$db->execute();

			
			$asset_str = '{"core.admin":[],"core.manage":{"6":1},"core.create":{"2":1},"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":{"2":1},"judir.category.create":{"6":1},"judir.category.edit":{"6":1},"judir.category.edit.state":{"6":1},"judir.category.edit.own":{"6":1},"judir.category.delete":{"6":1},"judir.category.delete.own":{"6":1},"judir.listing.create":{"6":1,"2":1},"judir.listing.create.auto_approval":[],"judir.listing.edit":{"6":1},"judir.listing.edit.own":{"6":1},"judir.listing.edit.auto_approval":[],"judir.listing.delete":{"6":1},"judir.listing.delete.own":{"6":1},"judir.listing.report":{"1":1},"judir.listing.report.no_captcha":[],"judir.listing.contact":{"1":1},"judir.listing.contact.no_captcha":[],"judir.comment.create":{"6":1,"2":1},"judir.comment.create.many_times":{"6":1,"2":1},"judir.comment.auto_approval":[],"judir.comment.reply":{"6":1,"2":1},"judir.comment.reply.auto_approval":[],"judir.comment.no_captcha":[],"judir.comment.vote":{"6":1,"2":1},"judir.comment.report":{"6":1,"2":1},"judir.comment.report.no_captcha":[],"judir.single.rate":{"6":1,"2":1},"judir.single.rate.many_times":[],"judir.criteria.rate":{"6":1,"2":1},"judir.criteria.rate.many_times":[],"judir.moderator.create":[],"judir.moderator.edit":[],"judir.moderator.edit.state":[],"judir.moderator.delete":[],"judir.field.value.submit":{"6":1,"2":1},"judir.field.value.edit":[],"judir.field.value.edit.own":{"6":1,"2":1},"judir.field.value.search":{"1":1,"6":1,"2":1}}';
			$query     = 'UPDATE #__assets SET `rules` = ' . $db->quote($asset_str) . ' WHERE `name` = "com_judirectory"';
			$db->setQuery($query);
			$db->execute();

			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables');

			
			$categoryTable = JTable::getInstance('Category', 'JUDirectoryTable');
			$query         = 'SELECT id FROM #__judirectory_categories';
			$db->setQuery($query);
			$categoryIds = $db->loadColumn();
			if ($categoryIds)
			{
				foreach ($categoryIds AS $categoryId)
				{
					if ($categoryTable->load($categoryId, true))
					{
						if ($categoryTable->check())
						{
							$categoryTable->store();
						}
					}
				}
			}

			
			$fieldGroupTable = JTable::getInstance('FieldGroup', 'JUDirectoryTable');
			$query           = 'SELECT id FROM #__judirectory_fields_groups';
			$db->setQuery($query);
			$fieldGroupIds = $db->loadColumn();
			if ($fieldGroupIds)
			{
				foreach ($fieldGroupIds AS $fieldGroupId)
				{
					if ($fieldGroupTable->load($fieldGroupId, true))
					{
						if ($fieldGroupTable->check())
						{
							$fieldGroupTable->store();
						}
					}
				}
			}

			
			$fieldTable = JTable::getInstance('Field', 'JUDirectoryTable');
			$query      = 'SELECT id FROM #__judirectory_fields';
			$db->setQuery($query);
			$fieldIds = $db->loadColumn();
			if ($fieldIds)
			{
				foreach ($fieldIds AS $fieldId)
				{
					if ($fieldTable->load($fieldId, true))
					{
						if ($fieldTable->check())
						{
							$fieldTable->store();
						}
					}
				}
			}

			
			$parent->getParent()->setRedirectURL('index.php?option=com_judirectory');
		}
	}

	public function getOldVersion()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('manifest_cache')
			->from('#__extensions')
			->where('element = ' . $db->quote('com_judirectory'));
		$db->setQuery($query);
		$result   = $db->loadResult();
		$manifest = new JRegistry($result);

		return $manifest->get('version');
	}
}
