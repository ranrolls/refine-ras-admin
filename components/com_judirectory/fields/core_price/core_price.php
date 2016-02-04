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

class JUDirectoryFieldCore_price extends JUDirectoryFieldText
{
	protected $field_name = 'price';

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		
		if ($this->getAttribute("type", "", "input") == "")
		{
			$this->setAttribute("type", "text", "input");
		}

		$this->addAttribute("class", $this->getInputClass(), "input");

		$value = !is_null($fieldValue) ? $fieldValue : $this->value;

		if ((int) $this->params->get("size", 32))
		{
			$this->setAttribute("size", (int) $this->params->get("size", 32), "input");
		}

		if ($this->params->get("placeholder", ""))
		{
			$placeholder = htmlspecialchars($this->params->get("placeholder", ""), ENT_COMPAT, 'UTF-8');
			$this->setAttribute("placeholder", $placeholder, "input");
		}

		$this->setVariable('value', $value);

		return $this->fetch('input.php', __CLASS__);
	}

	public function getOutput($options = array())
	{
		$this->setAttribute("style", 'float: left; margin-right: 2px;', "output");

		return parent::getOutput($options);
	}

}

?>