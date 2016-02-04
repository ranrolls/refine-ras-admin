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

class JUDirectoryFieldCore_locations extends JUDirectoryFieldBase
{
	protected $field_name = 'locations';
	protected $fieldvalue_column = "locations";

	protected function getValue()
	{
		if ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__judirectory_locations');
			$query->where('listing_id = ' . $this->listing_id);
			$app = JFactory::getApplication();
			if ($app->isSite())
			{
				$query->where('published = 1');
			}
			$query->order('ordering ASC');
			$db->setQuery($query);
			$result = $db->loadObjectList();

			return !empty($result) ? $result : null;
		}

		return null;
	}


	public function onSaveListing($locations = '')
	{
		$locations = array_values($locations);

		if ($locations)
		{
			$app    = JFactory::getApplication();
			$images = array_values($app->input->files->get('location_image_' . $this->id));
			foreach ($locations as $key => $location)
			{
				if ($images[$key])
				{
					$location['upload_marker_icon'] = $images[$key];
					$locations[$key]                = $location;
				}
			}
		}

		return $locations;
	}

	
	public function storeValue($locations)
	{
		if ($locations)
		{
			$locationTable = JTable::getInstance('Location', 'JUDirectoryTable');

			foreach ($locations as $key => $location)
			{
				$locationTable->reset();

				$data                = array();
				$data['lat']         = $location['lat'];
				$data['lng']         = $location['lng'];
				$data['address']     = $location['address'];
				$data['address_id']  = $location['address_id'];
				$data['postcode']    = $location['postcode'];
				$data['marker_icon'] = $location['marker_icon'];
				$data['description'] = $location['description'];
				$data['published']   = $location['published'];
				$data['ordering']    = $key + 1;

				
				if ($location['id'] && $locationTable->load($location['id']))
				{
					
					if ($location['remove'] == 0)
					{
						
						if (isset($location['remove_image']) && $location['remove_image'] == 1)
						{
							$this->removeIcon($locationTable);
							$locationTable->image = '';
						}

						$locationTable->bind($data);
						$locationTable->store();
					}
					
					else
					{
						$this->removeIcon($locationTable);
						$locationTable->delete();
					}
				}
				
				elseif ($location['remove'] == 0)
				{
					$data['id']         = 0;
					$data['listing_id'] = $this->listing_id;
					$locationTable->bind($data);
					$locationTable->store();
				}
				
				else
				{
					continue;
				}

				if ($location['upload_marker_icon'])
				{
					$this->addIcon($location['upload_marker_icon'], $locationTable);
				}
			}
		}

		return true;
	}

	protected function addIcon($image, $locationTable)
	{
		if ($image['name'])
		{
			$mime_types = array("image/jpeg", "image/pjpeg", "image/png", "image/gif", "image/bmp", "image/x-windows-bmp");
			if (in_array($image['type'], $mime_types))
			{
				
				$this->removeIcon($locationTable);

				
				$imageOriginalDirectory = JPATH_ROOT . "/media/com_judirectory/images/location/original/" . $this->listing_id . "/";
				$imageDirectory         = JPATH_ROOT . "/media/com_judirectory/images/location/" . $this->listing_id . "/";

				if (!JFolder::exists($imageOriginalDirectory))
				{
					$file_index = $imageOriginalDirectory . 'index.html';
					$buffer     = "<!DOCTYPE html><title></title>";
					JFile::write($file_index, $buffer);
				}

				if (!JFolder::exists($imageDirectory))
				{
					$file_index = $imageDirectory . 'index.html';
					$buffer     = "<!DOCTYPE html><title></title>";
					JFile::write($file_index, $buffer);
				}

				$image_file_name = $locationTable->id . "_" . JUDirectoryHelper::fileNameFilter($image['name']);
				if (JFile::upload($image['tmp_name'], $imageOriginalDirectory . $image_file_name)
					&& JUDirectoryHelper::renderImages($imageOriginalDirectory . $image_file_name, $imageDirectory . $image_file_name, 'location_image', true, null, $this->listing_id)
				)
				{
					$locationTable->image = $image_file_name;
					$locationTable->store();
				}
			}
			else
			{
				JError::raise(
					E_NOTICE,
					500,
					JText::sprintf('COM_JUDIRECTORY_LOCATION_ICON_IS_NOT_VALID_MIME_TYPE', implode(",", $mime_types))
				);
			}
		}
	}

	protected function removeIcon($locationTable)
	{
		if ($locationTable->image)
		{
			$imageOriginalDirectory = JPATH_ROOT . "/media/com_judirectory/images/location/original/" . $this->listing_id . "/";
			$imageDirectory         = JPATH_ROOT . "/media/com_judirectory/images/location/" . $this->listing_id . "/";

			if (JFile::exists($imageOriginalDirectory . $locationTable->image))
			{
				JFile::delete($imageOriginalDirectory . $locationTable->image);
			}

			if (JFile::exists($imageDirectory . $locationTable->image))
			{
				JFile::delete($imageDirectory . $locationTable->image);
			}
		}
	}

	public function loadDefaultAssets($loadJS = true, $loadCSS = true)
	{
		static $loaded = array();

		if (!isset($loaded[$this->id]))
		{
			$document = JFactory::getDocument();
			
			if ($loadJS)
			{
				$language = $this->params->get("language", "") ? "&language=" . $this->params->get("language", "") : "";
				$region   = $this->params->get("region", "") ? "&region=" . $this->params->get("region", "") : "";
				$key      = $this->params->get("api_key", "") ? "&key=" . $this->params->get("api_key", "") : "";

				$document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" . $key . $language . $region);
				$document->addScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js");
				JText::script('COM_JUDIRECTORY_THE_GEOLOCATION_SERVICE_FAILED');
				JText::script('COM_JUDIRECTORY_YOUR_BROWSER_DOES_NOT_SUPPORT_GEOLOCATION');
				JText::script('COM_JUDIRECTORY_ADDRESS_NOT_FOUND');
				JText::script('COM_JUDIRECTORY_PLEASE_ENTER_THE_ADDRESS');
				JText::script('COM_JUDIRECTORY_PLEASE_ENTER_THE_STARTING_POINT');
				$document->addScript(JURI::root() . "components/com_judirectory/fields/" . $this->folder . "/judirlocation.js");
			}

			
			if ($loadCSS)
			{
				$document->addStyleSheet(JURI::root() . "components/com_judirectory/fields/" . $this->folder . "/style.css");
			}

			$loaded[$this->id] = true;
		}
	}

	protected function getLocations($fieldValue)
	{
		$locations = array();
		if ($fieldValue)
		{
			foreach ($fieldValue as $location)
			{
				$locations[] = (object) $location;
			}
		}
		elseif ($this->listing_id)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__judirectory_locations');
			$query->where('listing_id = ' . $this->listing_id);
			$query->order('ordering ASC');
			$db->setQuery($query);
			$locations = $db->loadObjectList();
		}

		if ($locations)
		{
			foreach ($locations as $location)
			{
				$location->selectAddress = JUDirectoryHelper::getAddressPath($location->address_id ? $location->address_id : 1);
			}
		}

		return $locations;
	}

	public function getInput($fieldValue = null)
	{
		$this->loadDefaultAssets();
		$document = JFactory::getDocument();

		$data      = '';
		$locations = $this->getLocations($fieldValue);
		if ($locations)
		{
			$data = addslashes(json_encode($locations));
		}

		$app             = JFactory::getApplication();
		$isSite          = $app->isSite() ? 'true' : 'false';
		$view            = $app->input->get('view');
		$params          = JUDirectoryHelper::getParams(null, $this->listing_id);
		$center          = $params->get('map_center', '62.323907,-150.109291');
		$center          = explode(',', $center);
		$zoom            = $params->get('map_zoom', '2');
		$fitBoundMaxZoom = $params->get('map_fitbound_maxzoom', '13');
		$script          = 'jQuery(document).ready(function($){
			$("#julocation-' . $this->id . '").judirlocation({
				                                id: ' . $this->id . ',
				                                data: "' . $data . '",
				                                isSite: ' . $isSite . ',
				                                view: "' . $view . '",
				                                mapOptions: {
					                                            zoom: ' . $zoom . ',
								                                center: {lat: ' . $center[0] . ', lng: ' . $center[1] . '},
								                                scrollwheel: true,
								                                fitBoundMaxZoom : ' . $fitBoundMaxZoom . '
							                                },

												JUriRoot: "' . JUri::root(true) . '",
				                                imageUrl: "' . JUri::root(true) . '/media/com_judirectory/images/location/' . $this->listing_id . '/",
				                                markerUrl: "' . JUri::root(true) . '/media/com_judirectory/images/marker/",
				                                selectMarkerUrl: "' . JUri::root(true) . '/index.php?option=com_judirectory&view=defaultimages&tmpl=component"
			                                });
			});';

		$script .= "jQuery(document).ready(function($){
			            $('#julocation-" . $this->id . " .locations').dragsort({ dragSelector: \"div\", dragEnd: saveOrder, placeHolderTemplate: \"<li class='placeHolder'><div></div></li>\", dragSelectorExclude: \"input, textarea,checkbox, select, .chzn-container, .location-remove-image, span.btn, a\" });
			            function saveOrder() {
			                var data = $('#julocation-" . $this->id . " .locations').map(function() { return $(this).data(\"itemid\"); }).get();
			            }
			        });";
		$document->addScriptDeclaration($script);

		return $this->fetch('input.php', __CLASS__);
	}

	
	public function getBackendOutput()
	{
		return JText::plural('COM_JUDIRECTORY_N_LOCATION', count($this->value));
	}

	public function getOutput($options = array())
	{
		$this->loadDefaultAssets();
		$document        = JFactory::getDocument();
		$value           = $this->value ? addslashes(json_encode($this->value)) : '';
		$app             = JFactory::getApplication();
		$params          = JUDirectoryHelper::getParams(null, $this->listing_id);
		$isSite          = $app->isSite() ? 'true' : 'false';
		$view            = $app->input->get('view');
		$center          = $params->get('map_center', '62.323907,-150.109291');
		$center          = explode(',', $center);
		$zoom            = $params->get('map_zoom', '2');
		$fitBoundMaxZoom = $params->get('map_fitbound_maxzoom', '13');

		$script = 'jQuery(document).ready(function($){
				$("#julocation-' . $this->listing_id . '").judirlocation({
						                                id: ' . $this->id . ',
						                                data: "' . $value . '",
						                                isSite: ' . $isSite . ',
						                                view: "' . $view . '",
						                                JUriRoot: "' . JUri::root(true) . '",
						                                mapOptions: {
										                                zoom: ' . $zoom . ',
										                                center: {lat: ' . $center[0] . ', lng: ' . $center[1] . '},
										                                fitBoundMaxZoom: ' . $fitBoundMaxZoom . '
									                                },
						                                markerUrl: "' . JUri::root(true) . '/media/com_judirectory/images/marker/"
					                                });
				});';

		$document->addScriptDeclaration($script);

		return $this->fetch('output.php', __CLASS__);
	}

	public function getSearchInput($defaultValue = "")
	{
		$this->setVariable('defaultValue', $defaultValue);

		return $this->fetch('searchinput.php', __CLASS__);
	}

	
	public function onSimpleSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			
			$search = JUDirectoryFrontHelper::UrlDecode($search);
			$db     = JFactory::getDbo();
			$_query = array();

			$query->join('LEFT', '#__judirectory_locations AS location ON location.listing_id = listing.id');
			$_query[] = $db->quoteName('location.address') . " LIKE " . $db->quote("%" . $search . "%");
			$_query[] = $db->quoteName('location.description') . " LIKE " . $db->quote("%" . $search . "%");

			$query->join('LEFT', '#__judirectory_addresses AS address ON address.id = location.address_id');

			$where[] = '(' . implode($_query, ' OR ') . ')';
		}
	}

	public function onSearch(&$query, &$where, $search)
	{
		if ($search !== "")
		{
			$_query        = array();
			$searchColumns = array('address', 'postcode', 'description');
			$db            = JFactory::getDbo();
			foreach ($searchColumns as $searchColumn)
			{
				if (isset($search[$searchColumn]) && $search[$searchColumn] !== '')
				{
					$_query[] = $db->quoteName($searchColumn) . " LIKE " . $db->quote("%" . $search[$searchColumn] . "%");
				}
			}

			$addressIds = array();
			if (isset($search['address_id']) && $search['address_id'] > 1)
			{
				$addresses = JUDirectoryHelper::getAddressTree($search['address_id'], true, true);
				if ($addresses)
				{
					foreach ($addresses as $address)
					{
						$addressIds[] = $address->id;
					}
				}
			}

			if ($addressIds)
			{
				$_query[] = ('address.id IN (' . implode(',', $db->quote($addressIds)) . ')');
			}

			if ($_query)
			{
				$query->join('LEFT', '#__judirectory_locations AS location ON location.listing_id = listing.id');
				if ($addressIds)
				{
					$query->join('LEFT', '#__judirectory_addresses AS address ON address.id = location.address_id');
				}
				$search_operator = $this->params->get("search_operator", 0);
				if ($search_operator == 0)
				{
					$search_operator = $search['condition'];
				}
				$condition = ($search_operator == 1) ? 'AND' : 'OR';
				$where[]   = '(' . implode($_query, ' ' . $condition . ' ') . ')';
			}
		}
	}

	public function onCopy($toListingId, &$fieldsData = array())
	{
		
		$listingId = $this->listing_id;
		if ($listingId)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->SELECT('id');
			$query->FROM('#__judirectory_locations');
			$query->WHERE('listing_id = ' . $listingId);
			$query->ORDER('ordering ASC');
			$db->setQuery($query);
			$locationIds = $db->loadColumn();
			$count       = 0;

			$locationTable = JTable::getInstance("Location", "JUDirectoryTable");
			if (!empty($locationIds))
			{
				foreach ($locationIds AS $locationId)
				{
					$locationTable->load($locationId, true);
					$locationTable->id         = 0;
					$locationTable->listing_id = $toListingId;
					$locationTable->check();
					$locationTable->store();
					
					if (isset($fieldsData[$this->id]))
					{
						$this->replaceFieldData($fieldsData[$this->id], $locationId, $locationTable->id);
					}
					$count++;
				}

				$locations_directory = JPATH_ROOT . "/media/com_judirectory/images/location/";

				if (JFolder::exists($locations_directory . $listingId))
				{
					JFolder::copy($locations_directory . $listingId, $locations_directory . $toListingId);
				}

				if (JFolder::exists($locations_directory . "original/" . $listingId))
				{
					JFolder::copy($locations_directory . "original/" . $listingId, $locations_directory . "original/" . $toListingId);
				}
			}
		}
	}

	protected function replaceFieldData(&$data, $oldLocationId, $newLocationId)
	{
		if ($data)
		{
			foreach ($data as $key => $locationData)
			{
				if ($locationData['id'] == $oldLocationId)
				{
					$data[$key]['id'] = $newLocationId;
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
			$query = "DELETE FROM #__judirectory_locations WHERE listing_id = " . $this->listing_id;
			$db->setQuery($query);
			$db->execute();

			
			$location_directory = JPATH_ROOT . "/media/com_judirectory/images/location/";
			if (JFolder::exists($location_directory . $this->listing_id))
			{
				JFolder::delete($location_directory . $this->listing_id);
				JFolder::delete($location_directory . "original/" . $this->listing_id);
			}
		}
	}

	public function orderingPriority(&$query = null)
	{
		$app       = JFactory::getApplication();
		$where_str = $app->isSite() ? ' AND location.published = 1' : '';
		$this->appendQuery($query, 'select', '(SELECT COUNT(*) FROM #__judirectory_locations AS location WHERE (location.listing_id = listing.id' . $where_str . ')) AS locations');

		return array('ordering' => 'locations', 'direction' => $this->priority_direction);
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