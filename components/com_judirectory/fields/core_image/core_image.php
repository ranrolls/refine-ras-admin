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

class JUDirectoryFieldCore_image extends JUDirectoryFieldBase
{
	protected $field_name = 'image';

	public function getPredefinedValuesHtml()
	{
		return '<span class="readonly">' . JText::_('COM_JUDIRECTORY_NOT_SET') . '</span>';
	}

	public function PHPValidate($values)
	{
		$values = $this->value;
		
		if (!$values)
		{
			$app    = JFactory::getApplication();
			$image  = $app->input->files->get($this->getId() . "_image");
			$values = $image['name'];
		}

		return parent::PHPValidate($values);
	}

	
	public function loadDefaultAssets($loadJS = true, $loadCSS = true)
	{
		static $loaded = array();

		if ($this->folder && !isset($loaded[$this->folder]))
		{
			$document = JFactory::getDocument();
			
			if ($loadJS)
			{
				$script = '
					jQuery(document).ready(function($){
						var default_image_url = "' . JUri::root(true) . '/' . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/", true) . 'default/";
						var default_image = "' . JUDirectoryHelper::getDefaultListingImage() . '";
						var image_wrap = jQuery("#' . $this->getId() . '_wrap");
						var image_src = jQuery(".image-src", image_wrap);
						var remove_image = jQuery(".remove-image", image_wrap);
						var revert_image = jQuery(".revert-image", image_wrap);
						var image_value = jQuery(".image-value", image_wrap);
						var img_el = jQuery("img", image_wrap);
						jSelectImage = function(imageName){
							
							if(imageName){
								image_src.attr("src", default_image_url + imageName);
							
							}else if(default_image){
								image_src.attr("src", default_image);
							
							}else{
								image_src.remove();
							}

							
							if(remove_image.hasClass("remove")){
								revert_image.data("removeHidden", "1");
							
							}else{
								revert_image.removeClass("hidden");
							}

							image_value.val("default/" + imageName);

							if(SqueezeBox){
								SqueezeBox.close();
							}
						}

						remove_image.click(function(e){
							e.preventDefault();
							image_value.val("");
							if($(this).hasClass("remove")){
								remove_image.removeClass("remove").html("<i class=\"icon-trash\"></i> ' . JText::_('COM_JUDIRECTORY_REMOVE') . '");
								img_el.css("opacity", 1);
								
								if(revert_image.data("removeHidden") == "1"){
									revert_image.removeClass("hidden");
									revert_image.data("removeHidden", "0");
								}
							}else{
								remove_image.addClass("remove").html("<i class=\"icon-undo\"></i> ' . JText::_('COM_JUDIRECTORY_RESTORE') . '");
								img_el.css("opacity", 0.5);
								
								if(!revert_image.hasClass("hidden")){
									revert_image.addClass("hidden");
									revert_image.data("removeHidden", "1");
								}
							}
						});

						
						revert_image.click(function(e){
							e.preventDefault();

							var imageUrl = image_value.data("ori-image-url");
							var value = image_value.data("ori-image-value");
							image_src.attr("src", imageUrl);
							image_value.val(value);

							$(this).addClass("hidden");
						});
					});
				';

				$document->addScriptDeclaration($script);
			}

			if ($loadCSS)
			{
				$document->addStyleSheet(JUri::root() . "components/com_judirectory/fields/" . $this->folder . "/" . "style.css");
			}

			$loaded[$this->folder] = true;
		}
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->loadDefaultAssets();

		
		if (isset($this->listing) && $this->listing->cat_id)
		{
			$params = JUDirectoryHelper::getParams($this->listing->cat_id);
		}
		else
		{
			$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		}

		$max_upload = ini_get('upload_max_filesize');
		$max_upload = JUDirectoryHelper::formatBytes(self::convertBytes($max_upload));

		$value     = !is_null($fieldValue) ? $fieldValue : $this->value;
		$image_src = JUDirectoryHelper::getListingImage($value);

		$this->setAttribute("type", "file", "input");
		
		if (!$this->value)
		{
			$this->addAttribute("class", "validate-images", "input");
			$this->addAttribute("class", $this->getInputClass(), "input");
		}

		$this->setVariable('image_src', $image_src);
		$this->setVariable('max_upload', $max_upload);
		$this->setVariable('params', $params);
		$this->setVariable('value', $value);

		return $this->fetch('input.php', __CLASS__);
	}

	protected function convertBytes($value)
	{
		if (is_numeric($value))
		{
			return $value;
		}
		else
		{
			$value_length = strlen($value);
			$qty          = substr($value, 0, $value_length - 1);
			$unit         = strtolower(substr($value, $value_length - 1));
			switch ($unit)
			{
				case 'k':
					$qty *= 1024;
					break;
				case 'm':
					$qty *= 1048576;
					break;
				case 'g':
					$qty *= 1073741824;
					break;
			}

			return $qty;
		}
	}

	public function getBackendOutput()
	{
		$html      = '';
		$image_src = JUDirectoryHelper::getListingImage($this->value);
		if ($image_src)
		{
			$html = '<a href="' . $image_src . '" title="' . JText::_('COM_JUDIRECTORY_PREVIEW_IMAGE') . '" class="modal">
						<img src="' . $image_src . '" style="max-width: 20px; max-height: 20px" />
					</a>';
		}

		return $html;
	}

	public function canView($options = array())
	{
		$params        = JUDirectoryHelper::getParams(null, $this->listing_id);
		$default_image = JUDirectoryHelper::getDefaultListingImage();
		if ($this->value == "" && $default_image)
		{
			$this->value = "default/" . $params->get('listing_default_image', 'default-listing.png');
		}

		return parent::canView($options);
	}

	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		if (!$this->value)
		{
			return "";
		}

		$image_src = JUDirectoryHelper::getListingImage($this->value);

		if (!$this->listing_id || !$image_src)
		{
			return '';
		}

		$isDetailsView = $this->isDetailsView($options);

		$this->setVariable('image_src', $image_src);
		$this->setVariable('isDetailsView', $isDetailsView);

		return $this->fetch('output.php', __CLASS__);
	}

	public function onMigrateListing($value)
	{
		
		$imageKeyArray = $this->getId() . "_image";
		$image         = $value[$imageKeyArray];

		
		$mime_types = array("image/jpeg", "image/pjpeg", "image/png", "image/gif", "image/bmp", "image/x-windows-bmp");
		if ($image['name'])
		{
			if (in_array($image['type'], $mime_types))
			{
				
				$imageDirectory  = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/");
				$image_file_name = $this->listing_id . "_" . JUDirectoryHelper::fileNameFilter($image['name']);
				if (JFile::copy($image['tmp_name'], $imageDirectory . "original/" . $image_file_name)
					&& JUDirectoryHelper::renderImages($imageDirectory . "original/" . $image_file_name, $imageDirectory . $image_file_name, 'listing_image', true, null, $this->listing_id)
				)
				{
					$value = $image_file_name;

					return $value;
				}
			}
		}
		elseif ($value == "" || strpos($value, 'default/') === 0)
		{
			if ($this->listing && $this->listing->image && strpos($this->listing->image, 'default/') === false)
			{
				$this->removeIcon();
			}

			return $value;
		}
	}


	public function onSaveListing($value = '')
	{
		$app = JFactory::getApplication();

		
		$image = $app->input->files->get($this->getId() . "_image");
		
		$mime_types = array("image/jpeg", "image/pjpeg", "image/png", "image/gif", "image/bmp", "image/x-windows-bmp");
		if ($image['name'])
		{
			if (in_array($image['type'], $mime_types))
			{
				
				$this->removeIcon();

				
				$imageDirectory  = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/");
				$image_file_name = $this->listing_id . "_" . JUDirectoryHelper::fileNameFilter($image['name']);
				if (JFile::upload($image['tmp_name'], $imageDirectory . "original/" . $image_file_name)
					&& JUDirectoryHelper::renderImages($imageDirectory . "original/" . $image_file_name, $imageDirectory . $image_file_name, 'listing_image', true, null, $this->listing_id)
				)
				{
					$value = $image_file_name;

					return $value;
				}
			}
			else
			{
				JError::raise(
					E_NOTICE,
					500,
					JText::sprintf('COM_JUDIRECTORY_IMAGE_IS_NOT_VALID_MIME_TYPE')
				);
			}
		}
		elseif ($value == "" || strpos($value, 'default/') === 0)
		{
			if ($this->listing && $this->listing->image && strpos($this->listing->image, 'default/') === false)
			{
				$this->removeIcon();
			}

			return $value;
		}
	}

	protected function removeIcon()
	{
		
		if (strpos($this->listing->image, "default/") === 0)
		{
			return;
		}

		$imageDirectory = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/");

		
		if (JFile::exists($imageDirectory . $this->listing->image))
		{
			JFile::delete($imageDirectory . $this->listing->image);
		}

		if (JFile::exists($imageDirectory . "original/" . $this->listing->image))
		{
			JFile::delete($imageDirectory . "original/" . $this->listing->image);
		}
	}

	public function onDelete($deleteAll = false)
	{
		if ($this->value)
		{
			$this->removeIcon();
		}
	}

	public function onCopy($toListingId, &$fieldsData = array())
	{
		$db = JFactory::getDbo();

		
		if ($this->listing->image && strpos($this->listing->image, "default/") === false)
		{
			$ori_image_name = $this->listing->image;
			$new_image_name = $toListingId . substr($ori_image_name, strpos($ori_image_name, '_'));
			$query          = "UPDATE #__judirectory_listings SET image = '" . $new_image_name . "' WHERE id=" . $toListingId;
			$db->setQuery($query);
			$db->execute();

			$image_directory = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/");
			if (JFile::exists($image_directory . $ori_image_name))
			{
				JFile::copy($image_directory . $ori_image_name, $image_directory . $new_image_name);
			}

			if (JFile::exists($image_directory . "original/" . $ori_image_name))
			{
				JFile::copy($image_directory . "original/" . $ori_image_name, $image_directory . "original/" . $new_image_name);
			}
		}

		
		if ($this->listing_id && isset($fieldsData[$this->id]))
		{
			$toListingObject       = JUDirectoryHelper::getListingById($toListingId);
			$fieldsData[$this->id] = $toListingObject->image;
		}
	}

	public function onExport()
	{
		if ($this->listing->image)
		{
			$imageDir = JUDirectoryFrontHelper::getDirectory('listing_image_directory', 'media/com_judirectory/images/listing/') . 'original/';

			return $imageDir . $this->listing->image;
		}

		return '';
	}

	public function onImport($value, &$message = '')
	{
		$imageDir     = JPATH_ROOT . '/' . JUDirectoryFrontHelper::getDirectory('listing_image_directory', 'media/com_judirectory/images/listing/');
		$originalDir  = $imageDir . 'original/';
		$imagePath    = JUDirectoryHelper::getPhysicalPath($value);
		$oldImagePath = '';
		if (!$this->is_new && $this->listing->image)
		{
			$oldImagePath = JPath::clean($originalDir . $this->listing->image);
		}

		if ($imagePath != $oldImagePath)
		{
			if ($imagePath && JFile::exists($imagePath))
			{
				
				if (JFile::exists($oldImagePath))
				{
					JFile::delete($imageDir . $this->listing->image);
					JFile::delete($originalDir . $this->listing->image);
				}

				$imageName = basename($imagePath);
				$imageName = $this->listing_id . "_" . JUDirectoryHelper::fileNameFilter($imageName);
				JFile::copy($imagePath, $originalDir . $imageName);
				JUDirectoryHelper::renderImages($originalDir . $imageName, $imageDir . $imageName, 'listing_image', true, null, $this->listing_id);
				$value = $imageName;

				return basename($value);

			}
			else
			{
				
				$message = $value . JText::_(' does not exist');
			}
		}

		return false;
	}
}

?>