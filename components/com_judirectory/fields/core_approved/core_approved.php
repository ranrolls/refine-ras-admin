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

class JUDirectoryFieldCore_approved extends JUDirectoryFieldCore_published
{
	
	protected $field_name = 'approved';

	
	public function getBackendOutput($details = true)
	{
		return JHtml::_('grid.boolean', $this->listing_id, $this->value);
	}

	
	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$value = !is_null($fieldValue) ? $fieldValue : $this->value;

		$options    = array();
		$obj        = new stdClass();
		$obj->value = 1;
		$obj->text  = JText::_("JYES");
		$options[]  = $obj;
		$obj        = new stdClass();
		$obj->value = 0;
		$obj->text  = JText::_("JNO");
		$options[]  = $obj;

		$this->setAttribute("type", "radio", "input");
		$this->addAttribute("class", $this->getInputClass(), "input");

		$this->setVariable('value', $value);
		$this->setVariable('options', $options);

		return $this->fetch('input.php', __CLASS__);
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
			
			if ($this->listing_id && is_object($this->listing) && $this->listing->approved <= 0)
			{
				
				return false;
			}

			
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
			
			if ($this->listing_id && is_object($this->listing) && $this->listing->approved <= 0)
			{
				
				return false;
			}

			
			return parent::canEdit($userID);
		}
	}

	
	public function storeValue($value)
	{
		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			
			return true;
		}
		else
		{
			
			$tmpListing = JUDirectoryHelper::getTempListing($this->listing_id);
			if (is_object($tmpListing))
			{
				return true;
			}

			$approveOption      = $app->input->post->get("approval_option");
			$approveOptionArray = array("ignore", "approve", "delete");
			if (in_array($approveOption, $approveOptionArray))
			{
				return true;
			}

			

			return parent::storeValue($value);
		}
	}
}

?>