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

class JFormFieldModal_CustomList extends JFormField
{

	protected $type = 'Modal_CustomList';

	protected function getInput()
	{
		JHtml::_('behavior.modal', 'a.modal');

		
		$script   = array();
		$script[] = '	function jSelectlisting_' . $this->id . '(id, title, object) {';
		$script[] = '		document.id("' . $this->id . '_id").value = id;';
		$script[] = '		document.id("' . $this->id . '_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		
		$html = array();
		$link = 'index.php?option=com_judirectory&amp;view=customlists&amp;layout=modal&amp;tmpl=component&amp;function=jSelectlisting_' . $this->id;

		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT title' .
			' FROM #__judirectory_custom_lists' .
			' WHERE id = ' . (int) $this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg())
		{
			JError::raiseWarning(500, $error);
		}

		if (empty($title))
		{
			$title = JText::_('COM_JUDIRECTORY_SELECT_CUSTOMLIST');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '</div>';

		$app = JFactory::getApplication();
		
		if ($app->isAdmin())
		{
			$html[] = '<div class="button2-left">';
			$html[] = '  <div class="blank">';
			$html[] = '	<a class="modal" title="' . JText::_('COM_JUDIRECTORY_SELECT_CUSTOMLIST') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' . JText::_('COM_JUDIRECTORY_SELECT_CUSTOMLIST') . '</a>';
			$html[] = '  </div>';
			$html[] = '</div>';
		}

		
		$class = '';
		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . (int) $this->value . '" />';

		return implode("\n", $html);

	}
}