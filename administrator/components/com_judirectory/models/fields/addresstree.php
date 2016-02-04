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

JLoader::register('JFormFieldList', JPATH_LIBRARIES . '/joomla/form/fields/list.php');

class JFormFieldAddressTree extends JFormFieldList
{
	protected $type = 'AddressTree';

	protected function getOptions()
	{
		$app  = JFactory::getApplication();
		$view = $app->input->getCmd('view', '');

		
		$options = array();

		foreach ($this->element->children() AS $option)
		{
			
			if ($option->getName() != 'option')
			{
				continue;
			}

			
			$tmp = JHtml::_(
				'select.option', (string) $option['value'],
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
				((string) $option['disabled'] == 'true')
			);

			
			$tmp->class = (string) $option['class'];

			
			$tmp->onclick = (string) $option['onclick'];

			
			$options[] = $tmp;
		}

		JLoader::register('JUDirectoryHelper', JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/judirectory.php', false);

		$checkPublished = ($this->element['checkpublished'] == 'true' || $this->element['checkpublished'] == '1') ? true : false;
		$getSelf        = ($this->element['fetchself'] == 'true' || $this->element['fetchself'] == '1') ? true : false;
		$parentId       = $this->element['parentid'] ? $this->element['parentid'] : 1;
		$startLevel     = $this->element['startlevel'] ? $this->element['startlevel'] : 0;
		$separation     = $this->element['separation'] ? $this->element['separation'] : '|—';
		$ignoreaddress  = $this->element['ignoreaddress'] ? explode(',', $this->element['ignoreaddress']) : array();

		if ($view == 'address')
		{
			$addressId     = $app->input->get('id', 0);
			$ignoreaddress = $addressId ? array($addressId) : array();
			$_options      = JUDirectoryHelper::getAddressOptions($parentId, $getSelf, $checkPublished, $ignoreaddress, $startLevel, $separation);
		}
		else
		{
			$_options = JUDirectoryHelper::getAddressOptions($parentId, $getSelf, $checkPublished, $ignoreaddress, $startLevel, $separation);
		}

		reset($options);

		$options = array_merge($options, $_options);

		return $options;
	}
}

?>