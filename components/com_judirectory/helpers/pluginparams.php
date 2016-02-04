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

class JUDirectoryFrontHelperPluginParams
{
	
	protected static $cache = array();

	
	public static function getListingPluginParamRecursive($pluginName, $listingId, $param, $default = '', $inheritParam = '', $globalParam = '', $inherit = '-1', $global = '-2')
	{
		
		$inheritParam = $inheritParam ? $inheritParam : $param;
		
		$globalParam = $globalParam ? $globalParam : $param;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("listingxref.cat_id, listing.plugin_params");
		$query->from("#__judirectory_listings AS listing");
		$query->join("", "#__judirectory_listings_xref AS listingxref ON listing.id = listingxref.listing_id");
		$query->where("listing.id = " . $listingId);

		$db->setQuery($query);
		$listingObj    = $db->loadObject();
		$plugin_params = $listingObj->plugin_params;
		$catId         = $listingObj->cat_id;

		if ($plugin_params)
		{
			$plugins = new JRegistry($plugin_params);

			$pluginObject   = $plugins->get($pluginName, "");
			$pluginRegistry = new JRegistry($pluginObject);

			
			if ($pluginRegistry->get($inheritParam, '') !== $inherit)
			{
				
				if ($pluginRegistry->get($globalParam, $global) === $global)
				{
					$plugin          = JPluginHelper::getPlugin('judirectory', $pluginName);
					$pluginParamsStr = isset($plugin->params) ? $plugin->params : '{}';
					$pluginParams    = new JRegistry($pluginParamsStr);

					return $pluginParams->get($param, $default);
				}
				
				else
				{
					return $pluginRegistry->get($param, $default);
				}
			}
			
			else
			{
				return JUDirectoryFrontHelperPluginParams::getCatPluginParamRecursive($pluginName, $catId, $param, $default, $inheritParam, $globalParam, $inherit, $global);
			}
		}
		
		else
		{
			return $default;
		}
	}

	
	public static function getCatPluginParamRecursive($pluginName, $catId, $param, $default = '', $inheritParam = '', $globalParam = '', $inherit = '-1', $global = '-2')
	{
		
		$inheritParam = $inheritParam ? $inheritParam : $param;
		
		$globalParam = $globalParam ? $globalParam : $param;

		$path    = JUDirectoryHelper::getCategoryPath($catId);
		$rootCat = $path[0];

		$plugin          = JPluginHelper::getPlugin('judirectory', $pluginName);
		$pluginParamsStr = isset($plugin->params) ? $plugin->params : '{}';
		$pluginParams    = new JRegistry($pluginParamsStr);

		$pathCatToRoot = array_reverse($path);

		foreach ($pathCatToRoot AS $category)
		{
			$plugin_params = $category->plugin_params;

			if ($plugin_params)
			{
				$plugins = new JRegistry($plugin_params);

				$pluginObject   = $plugins->get($pluginName, "");
				$pluginRegistry = new JRegistry($pluginObject);

				
				if ($pluginRegistry->get($inheritParam, '') !== $inherit)
				{
					
					if ($pluginRegistry->get($globalParam, '') === $global)
					{
						return $pluginParams->get($param, $default);
					}
					
					else
					{
						return $pluginRegistry->get($param, $default);
					}
				}
				
				else
				{
					
					if ($category->parent_id == $rootCat->id)
					{
						return $pluginParams->get($param, $default);
					}
					
					else
					{
						continue;
					}
				}
			}
			
			else
			{
				return $default;
			}
		}

		
		return $default;
	}
}