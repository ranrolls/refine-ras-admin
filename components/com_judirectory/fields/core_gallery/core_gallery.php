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

class JUDirectoryFieldCore_gallery extends JUDirectoryFieldBase
{
	protected $field_name = 'gallery';
	protected $fieldvalue_column = "gallery";

	
	protected function getValue()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__judirectory_images');
		$query->where('published = 1');
		$query->where('listing_id=' . $this->listing_id);
		$query->order('ordering ASC');
		$db->setQuery($query);
		$images = $db->loadObjectList();

		return $images;
	}

	public function filterField($values)
	{
		$oldImages = $values ? $values : array();
		$app       = JFactory::getApplication();
		$newImages = $app->input->files->get('field_' . $this->id);

		$gallery = $gallery['old'] = $gallery['new'] = array();

		$params         = JUDirectoryHelper::getParams(null, $this->listing_id);
		$maxUploadImage = 4;
		$count          = 0;

		if ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = 'SELECT id FROM #__judirectory_images WHERE listing_id = ' . (int) $this->listing_id;
			$db->setQuery($query);
			$imageIds = $db->loadColumn();

			foreach ($oldImages AS $key => $image)
			{
				if (!in_array($image['id'], $imageIds))
				{
					continue;
				}

				if ($maxUploadImage > 0 && $count >= $maxUploadImage)
				{
					break;
				}

				$gallery['old'][] = $image;

				if (!$image['remove'])
				{
					$count++;
				}
			}
		}

		$error = array();
		if ($newImages && (($count < $maxUploadImage && $maxUploadImage > 0) || $maxUploadImage <= 0))
		{
			$legal_extensions            = "jpeg,jpg,png,gif,bmp";
			$legal_mime                  = "image/jpeg,image/pjpeg,image/png,image/gif,image/bmp,image/x-windows-bmp";
			$image_min_width             = $params->get("image_min_width", 50);
			$image_min_height            = $params->get("image_min_height", 50);
			$image_max_width             = $params->get("image_max_width", 1024);
			$image_max_height            = $params->get("image_max_height", 1024);
			$image_max_size              = $params->get("image_max_size", 400) * 1024;
			$num_files_exceed_limit      = 0;
			$num_files_invalid_dimension = 0;
			foreach ($newImages AS $image)
			{
				if ($image['name'])
				{
					$image['name'] = str_replace(' ', '_', JFile::makeSafe($image['name']));

					if ($count >= $maxUploadImage)
					{
						$num_files_exceed_limit++;
						continue;
					}

					if (!JUDirectoryFrontHelperPermission::canUpload($image, $error, $legal_extensions, $image_max_size, true, $legal_mime, '', $legal_extensions))
					{
						continue;
					}

					$image_dimension = getimagesize($image['tmp_name']);

					if ($image_dimension[0] < $image_min_width || $image_dimension[1] < $image_min_height || $image_dimension[0] > $image_max_width || $image_dimension[1] > $image_max_height)
					{
						$num_files_invalid_dimension++;
						continue;
					}

					$gallery['new'][] = $image;
					$count++;
				}
			}

			$app = JFactory::getApplication();

			if ($error)
			{
				foreach ($error AS $key => $count)
				{
					switch ($key)
					{
						case 'WARN_SOURCE':
						case 'WARN_FILENAME':
						case 'WARN_FILETYPE':
						case 'WARN_FILETOOLARGE' :
						case 'WARN_INVALID_IMG' :
						case 'WARN_INVALID_MIME' :
						case 'WARN_IEXSS' :
							$error_str = JText::plural("COM_JUDIRECTORY_N_FILE_" . $key, $count);
							break;
					}

					$app->enqueueMessage($error_str, 'notice');
				}
			}

			if ($num_files_exceed_limit)
			{
				$image_upload_limit = JUDirectoryHelper::formatBytes($image_max_size * 1024);
				$app->enqueueMessage(JText::plural('COM_JUDIRECTORY_N_IMAGES_ARE_NOT_SAVED_BECAUSE_THEY_EXCEEDED_FILE_SIZE_LIMIT', $num_files_exceed_limit, $image_upload_limit), 'notice');
			}

			if ($num_files_invalid_dimension)
			{
				$app->enqueueMessage(JText::plural('COM_JUDIRECTORY_N_IMAGES_ARE_NOT_SAVED_BECAUSE_THEY_ARE_NOT_VALID_DIMENSION', $num_files_invalid_dimension, $image_min_width, $image_max_width, $image_min_height, $image_max_height), 'notice');
			}
		}

		$gallery['count'] = $count;

		return $gallery;
	}

	public function PHPValidate($values)
	{
		
		$params = JUDirectoryHelper::getParams(null, $this->listing_id);
		if (!$values['count'] && $this->isRequired())
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_JUDIRECTORY_YOU_HAVE_TO_UPLOAD_AT_LEAST_ONE_IMAGE'), 'error');

			return false;
		}

		return true;
	}

	public function onMigrateListing($value)
	{
		
		$gallery = $value;

		
		$date = JFactory::getDate();

		
		$image_ordering = 0;

		
		$configListingOriginalImageDirectory = JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/");
		$configListingSmallImageDirectory    = JUDirectoryFrontHelper::getDirectory("listing_small_image_directory", "media/com_judirectory/images/gallery/small/");
		$configListingBigImageDirectory      = JUDirectoryFrontHelper::getDirectory("listing_big_image_directory", "media/com_judirectory/images/gallery/big/");

		
		$listing_original_image_directory = JPATH_ROOT . "/" . $configListingOriginalImageDirectory . $this->listing_id . "/";
		$listing_small_image_directory    = JPATH_ROOT . "/" . $configListingSmallImageDirectory . $this->listing_id . "/";
		$listing_big_image_directory      = JPATH_ROOT . "/" . $configListingBigImageDirectory . $this->listing_id . "/";

		
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables', 'JUDirectoryTable');
		$imageTable = JTable::getInstance('Image', 'JUDirectoryTable');

		
		if (!empty($gallery['new']))
		{
			if (!JFolder::exists($listing_original_image_directory))
			{
				$file_index = $listing_original_image_directory . 'index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			if (!JFolder::exists($listing_small_image_directory))
			{
				$file_index = $listing_small_image_directory . 'index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			if (!JFolder::exists($listing_big_image_directory))
			{
				$file_index = $listing_big_image_directory . 'index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			$countNewImage = 0;
			foreach ($gallery['new'] AS $image)
			{
				$img_file_name = JUDirectoryHelper::generateImageNameByListing($this->listing_id, $image['name']);

				
				if (!JFile::copy($image['tmp_name'], $listing_original_image_directory . $img_file_name)
					|| !JUDirectoryHelper::renderImages($listing_original_image_directory . $img_file_name, $listing_small_image_directory . $img_file_name, 'listing_small', true, null, $this->listing_id)
					|| !JUDirectoryHelper::renderImages($listing_original_image_directory . $img_file_name, $listing_big_image_directory . $img_file_name, 'listing_big', true, null, $this->listing_id)
				)
				{
					continue;
				}

				$imageTable->reset();
				$dataImage = array('id' => 0, 'file_name' => $img_file_name, 'listing_id' => $this->listing_id, 'published' => 1, 'ordering' => ++$image_ordering, 'created' => $date->toSql());
				$imageTable->bind($dataImage);
				$imageTable->check();
				$imageTable->store();
				$countNewImage++;
			}
		}

		return null;
	}

	public function storeValue($value)
	{
		return true;
	}


	
	public function onSaveListing($value = '')
	{
		
		$gallery = $value;

		
		$date = JFactory::getDate();

		
		$image_ordering = 0;

		
		$configListingOriginalImageDirectory = JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/");
		$configListingSmallImageDirectory    = JUDirectoryFrontHelper::getDirectory("listing_small_image_directory", "media/com_judirectory/images/gallery/small/");
		$configListingBigImageDirectory      = JUDirectoryFrontHelper::getDirectory("listing_big_image_directory", "media/com_judirectory/images/gallery/big/");

		
		$listing_original_image_directory = JPATH_ROOT . "/" . $configListingOriginalImageDirectory . $this->listing_id . "/";
		$listing_small_image_directory    = JPATH_ROOT . "/" . $configListingSmallImageDirectory . $this->listing_id . "/";
		$listing_big_image_directory      = JPATH_ROOT . "/" . $configListingBigImageDirectory . $this->listing_id . "/";

		
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/tables', 'JUDirectoryTable');
		$imageTable = JTable::getInstance('Image', 'JUDirectoryTable');

		if (!$this->is_new)
		{
			
			if (!empty($gallery['old']))
			{
				foreach ($gallery['old'] AS $image)
				{
					if ($imageTable->load($image['id']))
					{
						
						if ($image['remove'] == 1)
						{
							if ($imageTable->delete())
							{
								JFile::delete($listing_original_image_directory . $image['file_name']);
								JFile::delete($listing_small_image_directory . $image['file_name']);
								JFile::delete($listing_big_image_directory . $image['file_name']);
							}
						}
						
						else
						{
							$image_ordering++;
							$_image                = array();
							$_image['title']       = $image['title'];
							$_image['description'] = $image['description'];
							$_image['published']   = $image['published'];
							$_image['ordering']    = $image_ordering;
							$imageTable->bind($_image);
							$imageTable->check();
							$imageTable->store();
						}
					}
				}
			}
		}

		
		if (!empty($gallery['new']))
		{
			if (!JFolder::exists($listing_original_image_directory))
			{
				$file_index = $listing_original_image_directory . 'index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			if (!JFolder::exists($listing_small_image_directory))
			{
				$file_index = $listing_small_image_directory . 'index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			if (!JFolder::exists($listing_big_image_directory))
			{
				$file_index = $listing_big_image_directory . 'index.html';
				$buffer     = "<!DOCTYPE html><title></title>";
				JFile::write($file_index, $buffer);
			}

			$countNewImage = 0;
			foreach ($gallery['new'] AS $image)
			{
				$img_file_name = JUDirectoryHelper::generateImageNameByListing($this->listing_id, $image['name']);

				
				if (!JFile::move($image['tmp_name'], $listing_original_image_directory . $img_file_name)
					|| !JUDirectoryHelper::renderImages($listing_original_image_directory . $img_file_name, $listing_small_image_directory . $img_file_name, 'listing_small', true, null, $this->listing_id)
					|| !JUDirectoryHelper::renderImages($listing_original_image_directory . $img_file_name, $listing_big_image_directory . $img_file_name, 'listing_big', true, null, $this->listing_id)
				)
				{
					continue;
				}

				$imageTable->reset();
				$dataImage = array('id' => 0, 'file_name' => $img_file_name, 'listing_id' => $this->listing_id, 'published' => 1, 'ordering' => ++$image_ordering, 'created' => $date->toSql());
				$imageTable->bind($dataImage);
				$imageTable->check();
				$imageTable->store();
				$countNewImage++;
			}
		}

		

		return null;
	}

	public function onCopy($toListingId, &$fieldsData = array())
	{
		if ($this->listing_id && $toListingId)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->SELECT('id');
			$query->FROM('#__judirectory_images');
			$query->WHERE('listing_id = ' . $this->listing_id);
			$query->ORDER('ordering ASC');
			$db->setQuery($query);
			$imageIds = $db->loadColumn();
			$count    = 0;
			if (!empty($imageIds))
			{
				JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_judirectory/tables");
				$imageTable = JTable::getInstance("Image", "JUDirectoryTable");

				foreach ($imageIds AS $imageId)
				{
					$imageTable->load($imageId, true);
					$imageTable->id         = 0;
					$imageTable->listing_id = $toListingId;
					$imageTable->check();
					$imageTable->store();
					
					if (isset($fieldsData[$this->id]) && $fieldsData[$this->id])
					{
						$this->replaceFieldData($fieldsData[$this->id], $imageId, $imageTable->id);
					}
					$count++;
				}

				$ori_dir_listing_ori   = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/") . $this->listing_id . "/";
				$ori_dir_listing_small = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_small_image_directory", "media/com_judirectory/images/gallery/small/") . $this->listing_id . "/";
				$ori_dir_listing_big   = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_big_image_directory", "media/com_judirectory/images/gallery/big/") . $this->listing_id . "/";

				$new_dir_listing_ori   = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_original_image_directory", "media/com_judirectory/images/gallery/original/") . $toListingId . "/";
				$new_dir_listing_small = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_small_image_directory", "media/com_judirectory/images/gallery/small/") . $toListingId . "/";
				$new_dir_listing_big   = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory("listing_big_image_directory", "media/com_judirectory/images/gallery/big/") . $toListingId . "/";

				if (JFolder::exists($ori_dir_listing_ori))
				{
					JFolder::copy($ori_dir_listing_ori, $new_dir_listing_ori);
				}

				if (JFolder::exists($ori_dir_listing_small))
				{
					JFolder::copy($ori_dir_listing_small, $new_dir_listing_small);
				}

				if (JFolder::exists($ori_dir_listing_big))
				{
					JFolder::copy($ori_dir_listing_big, $new_dir_listing_big);
				}
			}
		}
	}

	public function replaceFieldData(&$data, $oldImageId, $newImageId)
	{
		if ($data)
		{
			foreach ($data as $key => $imageData)
			{
				if ($imageData['id'] == $oldImageId)
				{
					$data[$key]['id'] = $newImageId;
					break;
				}
			}
		}
	}

	public function onDelete($deleteAll = false)
	{
		
		if ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = "DELETE FROM #__judirectory_images WHERE listing_id = " . $this->listing_id;
			$db->setQuery($query);
			$db->execute();

			
			$dir_listing_ori   = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory('listing_original_image_directory', 'media/com_judirectory/images/gallery/original/') . $this->listing_id . "/";
			$dir_listing_small = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory('listing_small_image_directory', 'media/com_judirectory/images/gallery/small/') . $this->listing_id . "/";
			$dir_listing_big   = JPATH_ROOT . "/" . JUDirectoryFrontHelper::getDirectory('listing_big_image_directory', 'media/com_judirectory/images/gallery/big/') . $this->listing_id . "/";

			if (JFolder::exists($dir_listing_ori))
			{
				JFolder::delete($dir_listing_ori);
				JFolder::delete($dir_listing_small);
				JFolder::delete($dir_listing_big);
			}
		}
	}

	
	public function onSearch(&$query, &$where, $search)
	{
		if ($search === "")
		{
			return false;
		}
		$app       = JFactory::getApplication();
		$where_str = $app->isSite() ? ' AND image.published = 1' : '';
		if ($search == 1)
		{
			$where[] = '(SELECT COUNT(image.id) FROM #__judirectory_images as image WHERE image.listing_id = listing.id' . $where_str . ') > 0 ';
		}
		else
		{
			$where[] = '(SELECT COUNT(image.id) FROM #__judirectory_images as image WHERE image.listing_id = listing.id' . $where_str . ') = 0 ';
		}

	}

	
	public function onSimpleSearch(&$query, &$where, $search)
	{
		return;
	}

	
	public function onTagSearch(&$query, &$where, $tag = null)
	{
		return;
	}

	
	public function loadDefaultAssets($loadJS = true, $loadCSS = true)
	{
		static $loaded = array();

		if ($this->folder && !isset($loaded[$this->folder]))
		{
			$document = JFactory::getDocument();
			
			if ($loadJS)
			{
				$document->addScript(JUri::root() . "components/com_judirectory/fields/" . $this->folder . "/judirgallery.js");
				$big_image_dir = JUDirectoryFrontHelper::getDirectory("listing_big_image_directory", "media/com_judirectory/images/gallery/big/", true) . $this->listing_id . "/";
				$params        = JUDirectoryHelper::getParams(null, $this->listing_id);
				$requiredImage = $this->isRequired() ? 1 : 0;
				$maxImages     = 4;

				$script = '
					jQuery(document).ready(function($){
						options = {
							juri_root    : "' . JUri::root() . '",
							requiredImage:  ' . (int) $requiredImage . ',
							maxImages    :  ' . (int) $maxImages . ',
							big_image_dir: "' . $big_image_dir . '",
							is_site      : 0
						};
						$("#image-gallery").judirgallery(options);
					});
				';

				$document->addScriptDeclaration($script);
			}

			
			if ($loadCSS)
			{
				$document->addStyleSheet(JUri::root() . "components/com_judirectory/fields/" . $this->folder . "/style.css");
			}

			$loaded[$this->folder] = true;
		}
	}


	public function loadOutputAssets()
	{
		static $loaded = array();

		$display_mode = $this->params->get('image_display_mode', 'fancybox');
		$store_id     = md5($this->id . "::" . $display_mode);

		if (!isset($loaded[$store_id]))
		{
			$document = JFactory::getDocument();

			if ($display_mode == 'fancybox')
			{
				$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/fancybox/css/jquery.fancybox.css");
				$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/fancybox/css/jquery.fancybox-thumbs.css");
				$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/fancybox/css/jquery.fancybox-buttons.css");
				$document->addStyleSheet(JUri::root() . "components/com_judirectory/fields/" . $this->folder . "/style.css");

				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/fancybox/js/jquery.mousewheel-3.0.6.pack.js");
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/fancybox/js/jquery.fancybox.pack.js");
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/fancybox/js/jquery.fancybox-thumbs.js");
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/fancybox/js/jquery.fancybox-buttons.js");
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/fancybox/js/jquery.fancybox-media.js");
				
				$script = "
				jQuery(document).ready(function ($) {
					$('.fancybox').fancybox({
						openEffect  : 'fade',
						closeEffect : 'fade',
						prevEffect : 'fade',
						nextEffect : 'fade',
						helpers : {
							title : {
								type : 'inside'
							},
							buttons	: {
							}
						},
						beforeLoad: function() {
				            this.title = $(this.element).next('.title').html();
				        }
					});
				});";

				$document->addScriptDeclaration($script);
			}
			else
			{
				$document->addStyleSheet(JUri::root(true) . "/components/com_judirectory/assets/camera_slideshow/css/camera.css");

				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/camera_slideshow/scripts/jquery.mobile.customized.min.js");
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/camera_slideshow/scripts/jquery.easing.1.3.js");
				$document->addScript(JUri::root(true) . "/components/com_judirectory/assets/camera_slideshow/scripts/camera.min.js");
				$script = "
				jQuery(document).ready(function ($) {
					$('.camera_slideshow').camera({
						thumbnails: true,
						autoAdvance: false
					});
				});";

				$document->addScriptDeclaration($script);
			}

			$loaded[$store_id] = true;
		}
	}

	
	protected function getImageGallery($fieldValue)
	{
		if (!empty($fieldValue))
		{
			$db = JFactory::getDbo();
			foreach ($fieldValue AS $key => $image)
			{
				$query = 'SELECT file_name FROM #__judirectory_images WHERE id = ' . $image['id'];
				$db->setQuery($query);
				$imageGallery[$key]['file_name'] = $db->loadResult();
			}

			return $imageGallery;
		}
		elseif ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->SELECT('*');
			$query->FROM('#__judirectory_images');
			$query->WHERE('listing_id=' . $this->listing_id);
			$query->ORDER('ordering ASC');
			$db->setQuery($query);
			$rows = $db->loadAssocList();

			return $rows;
		}

		return array();
	}

	public function getInput($fieldValue = null)
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->loadDefaultAssets();

		$images = $this->getImageGallery($fieldValue);
		$this->setVariable('images', $images);

		return $this->fetch('input.php', __CLASS__);
	}


	public function getOutput($options = array())
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$this->loadOutputAssets();

		$params = JUDirectoryHelper::getParams(null, $this->listing_id);

		$this->setVariable('params', $params);
		$this->setVariable('images', $this->value);

		return $this->fetch('output.php', __CLASS__);
	}


	public function getBackendOutput()
	{
		return JText::plural('COM_JUDIRECTORY_N_IMAGES', count($this->value));
	}

	public function getSearchInput($defaultValue = "")
	{
		if (!$this->isPublished())
		{
			return "";
		}

		$options    = array();
		$obj        = new stdClass();
		$obj->value = "";
		$obj->text  = JText::_("COM_JUDIRECTORY_SELECT_OPTION");
		$options[]  = $obj;
		$obj        = new stdClass();
		$obj->value = 1;
		$obj->text  = JText::_("JYES");
		$options[]  = $obj;
		$obj        = new stdClass();
		$obj->value = 0;
		$obj->text  = JText::_("JNO");
		$options[]  = $obj;

		$this->setVariable('value', $defaultValue);
		$this->setVariable('options', $options);

		return $this->fetch('searchinput.php', __CLASS__);
	}

	public function orderingPriority(&$query = null)
	{
		$app       = JFactory::getApplication();
		$where_str = $app->isSite() ? ' AND image.published = 1' : '';
		$this->appendQuery($query, 'select', '(SELECT COUNT(*) FROM #__judirectory_images AS image WHERE image.listing_id = listing.id' . $where_str . ') AS images');

		return array('ordering' => 'images', 'direction' => $this->priority_direction);
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}

	
}

?>