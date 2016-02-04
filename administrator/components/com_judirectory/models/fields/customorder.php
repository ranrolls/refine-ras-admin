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

class JFormFieldCustomOrder extends JFormFieldList
{
	protected $type = 'CustomOrder';

	protected function getOptions()
	{
		
		$options = array();

		foreach ($this->element->children() as $option)
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

		$getSortFields = $this->getFieldOrderingPriority();
		if (count($getSortFields))
		{
			foreach ($getSortFields as $field)
			{
				$options[] = JHtml::_('select.option', $field->field_name . ' asc', ucfirst($field->field_name) . ' ' . JText::_('JGLOBAL_ORDER_ASCENDING'));
				$options[] = JHtml::_('select.option', $field->field_name . ' desc', ucfirst($field->field_name) . ' ' . JText::_('JGLOBAL_ORDER_DESCENDING'));
			}
		}

		return $options;
	}

	public function getFieldOrderingPriority()
	{
		$db       = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$nowDate  = JFactory::getDate()->toSql();
		$query    = $db->getQuery(true);
		$query->select("field.*");
		$query->from("#__judirectory_fields AS field");
		$query->select("plg.folder");
		$query->join("", "#__judirectory_plugins AS plg ON field.plugin_id = plg.id ");
		$query->join("", "#__judirectory_fields_groups AS field_group ON field.group_id = field_group.id");
		$query->join("", "#__judirectory_categories AS c ON ((c.fieldgroup_id = field_group.id AND c.published = 1) OR field.group_id = 1)");

		$where   = array();
		$where[] = 'field.published = 1';
		$where[] = 'field.publish_up <= ' . $db->quote($nowDate);
		$where[] = '(field.publish_down = ' . $db->quote($nullDate) . ' OR field.publish_down > ' . $db->quote($nowDate) . ')';


		$where[] = 'field.allow_priority = 1';
		$where[] = 'field_group.published = 1';

		
		$query->where("(" . implode(" AND ", $where) . ")", "OR");

		$query->group('field.id');

		$query->order('field.priority ASC');

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}

?>