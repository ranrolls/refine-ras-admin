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

class JUDirectoryFieldCore_created_by extends JUDirectoryFieldBase
{
	protected $field_name = 'created_by';
	protected $fieldvalue_column = 'ua.name';

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$value = !is_null($fieldValue) ? $fieldValue : $this->value;
		if ($value > 0)
		{
			$user = JFactory::getUser($this->value);
		}
		else
		{
			$user     = new stdClass();
			$user->id = 0;
			$app      = JFactory::getApplication();
			if ($app->isAdmin())
			{
				$user->name = JText::_('COM_JUDIRECTORY_SELECT_USER');
			}
			else
			{
				$user->name = '';
			}
		}

		$this->addAttribute("class", $this->getInputClass(), "input");
		$this->setAttribute("disabled", "disabled", "input");

		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			return JHtml::_('judirectoryadministrator.user', $user, $this->getName(), $this->getId(), $this->getAttribute(null, null, "input"));
		}
		else
		{
			$this->setAttribute("type", "text", "input");

			$this->setVariable('user', $user);

			return $this->fetch('input.php', __CLASS__);
		}
	}

	public function getSearchInput($defaultValue = "")
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->setAttribute("type", "text", "search");

		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			$this->setVariable('value', $defaultValue);

			return $this->fetch('searchinput.php', __CLASS__);
		}
		else
		{
			JHtml::_('behavior.modal');
			$document = JFactory::getDocument();
			$script   = '
				function jSelectUser_' . $this->getId() . '(id, title) {
					var old_id = document.getElementById("' . $this->getId() . '").value;
					if (old_id != id) {
						document.getElementById("' . $this->getId() . '").value = id;
						document.getElementById("' . $this->getId() . '_name").value = title;
					}
					SqueezeBox.close();
				}';
			$document->addScriptDeclaration($script);

			$user       = new stdClass();
			$user->id   = "";
			$user->name = JText::_('COM_JUDIRECTORY_SELECT_USER');

			$this->setAttribute("disabled", "disabled", "search");
			$this->setAttribute("value", $user->name, "search");

			return JHtml::_('judirectoryadministrator.user', $user, $this->getName(), $this->getId(), $this->getAttribute(null, null, "search"));
		}
	}

	public function onSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$app = JFactory::getApplication();
			if ($app->isSite())
			{
				$db      = JFactory::getDbo();
				$where[] = $this->fieldvalue_column . " LIKE '%" . $db->escape($search, true) . "%'";
			}
			else
			{
				$where[] = "ua.id = " . (int) $search;
			}
		}
	}

	public function onSimpleSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$db      = JFactory::getDbo();
			$where[] = $this->fieldvalue_column . " LIKE '%" . $db->escape($search, true) . "%'";
		}
	}

	public function getBackendOutput()
	{
		$user = JFactory::getUser($this->value);
		if ($user)
		{
			if (!$user->get('guest'))
			{
				return $user->name;
			}
			else
			{
				return JText::_('COM_JUDIRECTORY_GUEST');
			}
		}
		else
		{
			return JText::_("COM_JUDIRECTORY_UNDEFINED");
		}
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$username = "";
		if ($this->field_name == "created_by" && $this->listing->created_by_alias)
		{
			$username = $this->listing->created_by_alias;
		}
		elseif ($this->value)
		{
			$user = JFactory::getUser($this->value);
			if ($user)
			{
				if ($user->id)
				{
					$username = $user->name;
				}
				else
				{
					$username = JText::_('COM_JUDIRECTORY_GUEST');
				}
			}
			else
			{
				$username = JText::_("COM_JUDIRECTORY_UNDEFINED");
			}
		}

		$this->setVariable('username', $username);

		return $this->fetch('output.php', __CLASS__);
	}

	
	public function storeValue($value)
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			if ($this->is_new)
			{
				
				if (!$value)
				{
					$user  = JFactory::getUser();
					$value = $user->id;
				}
			}

			return parent::storeValue($value);
		}
		else
		{
			return true;
		}
	}

	
	public function canSubmit($userID = null)
	{
		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			return false;
		}
		else
		{
			return parent::canSubmit($userID);
		}
	}

	
	public function canEdit($userID = null)
	{
		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			return false;
		}
		else
		{
			return parent::canEdit($userID);
		}
	}

	public function orderingPriority(&$query = null)
	{
		$this->appendQuery($query, 'select', 'ua.name AS created_by_name');
		$this->appendQuery($query, 'left join', '#__users AS ua ON listing.created_by = ua.id');

		return array('ordering' => 'created_by_name', 'direction' => $this->priority_direction);
	}
}

?>