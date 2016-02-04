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

class JUDirectoryFieldCore_url extends JUDirectoryFieldLink
{
	protected $field_name = 'url';

	
	public function getCounter()
	{
		if ($this->visits)
		{
			return $this->visits;
		}
		else
		{
			return null;
		}
	}

	public function redirectUrl()
	{
		if ($this->params->get("link_counter", 0) && $this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = "UPDATE #__judirectory_listings SET visits = visits + 1 WHERE id = " . $this->listing_id;
			$db->setQuery($query);
			$db->execute();
		}

		$app = JFactory::getApplication();
		$url = $this->value;
		$app->redirect($url);
	}
}

?>