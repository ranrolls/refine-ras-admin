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


class JUDirectoryViewDefaultImages extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		$app = JFactory::getApplication();

		$this->type = $app->input->get('type', '');

		if (!$this->type || $this->type == 'default')
		{
			$this->image_url       = JUri::root(true) . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/", true) . "default/";
			$this->image_directory = JPath::clean(JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/") . "default/");
		}
		elseif ($this->type == 'marker')
		{
			$this->image_url       = JUri::root(true) . "/media/com_judirectory/images/marker/";
			$this->image_directory = JPath::clean(JPATH_ROOT . "/media/com_judirectory/images/marker/");
		}

		$this->folders = $this->getFolders();
		$this->folder  = $app->input->get('folder', '', 'string');
		
		if (!in_array($this->folder, $this->folders))
		{
			$this->folder = '';
		}
		$this->images = $this->getImages();

		

		$this->setDocument();

		
		parent::display($tpl);
	}

	public function getImages()
	{
		$current_directory = JPath::clean($this->image_directory . $this->folder . "/");
		$filenames         = array();
		foreach (glob($current_directory . "{*.png,*.gif,*.jpg,*.bmp}", GLOB_BRACE) as $filename)
		{
			$filenames[] = str_replace(array($this->image_directory, "\\"), array("", "/"), JPath::clean($filename));
		}

		return $filenames;
	}

	public function getFolders()
	{
		$folders = array(array('value' => '', 'text' => '/'));
		foreach (glob($this->image_directory . '*', GLOB_ONLYDIR) as $filename)
		{
			$folder    = str_replace($this->image_directory, "", JPath::clean($filename));
			$folders[] = array('value' => $folder, 'text' => "/" . $folder);
		}

		return $folders;
	}

	
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::root(true) . '/components/com_judirectory/assets/css/defaultimages.css');
	}

}
