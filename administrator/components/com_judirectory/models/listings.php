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


jimport('joomla.application.component.modellist');


class JUDirectoryModelListings extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'listing.id',
				'listing.title',
				'c.title',
				'l.title',
				'listing.created_by',
				'listing.created',
				'catid',
				'access',
				'published',
				'featured'
			);
		}

		parent::__construct($config);
	}

	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$category = $this->getUserStateFromRequest($this->context . '.filter.category', 'filter_catid');
		$this->setState('filter.catid', $category);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published');
		$this->setState('filter.published', $published);

		$featured = $this->getUserStateFromRequest($this->context . '.filter.featured', 'filter_featured');
		$this->setState('filter.featured', $featured);

		parent::populateState($ordering, $direction);
	}

	
	protected function getListQuery()
	{
		$listOrder = $this->state->get('list.ordering');
		$listDirn  = $this->state->get('list.direction');
		$search    = $this->state->get('filter.search');

		$db    = $this->getDBO();
		$query = $db->getQuery(true);
		$query->SELECT('listing.*');
		$query->FROM('#__judirectory_listings AS listing');

		$query->JOIN('', '#__judirectory_listings_xref AS listingxref ON listingxref.listing_id = listing.id AND listingxref.main =1');

		$query->SELECT('c.title AS category_title');
		$query->JOIN('', '#__judirectory_categories AS c ON listingxref.cat_id = c.id');

		$query->SELECT('ua.name AS created_by');
		$query->JOIN('LEFT', '#__users AS ua ON ua.id = listing.created_by');

		
		$query->SELECT('vl.title AS access_level');
		$query->JOIN('LEFT', '#__viewlevels AS vl ON vl.id = listing.access');

		
		$catid = $this->getState('filter.catid');
		if ($catid)
		{
			$query->where('c.id = ' . $db->quote($catid));
		}

		
		$access = $this->getState('filter.access');
		if ($access)
		{
			$query->where('listing.access = ' . (int) $access);
		}

		
		$published = $this->getState('filter.published', '');
		if ($published !== '')
		{
			$query->where('listing.published = ' . (int) $published);
		}

		
		$featured = $this->getState('filter.featured', '');
		if ($featured !== '')
		{
			$query->where('listing.featured = ' . (int) $featured);
		}

		
		$query->where('listing.approved = 1');

		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('listing.id = ' . (int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = substr($search, 7);
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('c.title LIKE ' . $search);
			}
			elseif (stripos($search, 'created_by:') === 0)
			{
				$search = substr($search, 11);
				if (is_numeric($search))
				{
					$query->where('listing.created_by = 0 OR listing.created_by IS NULL');
				}
				else
				{
					$search = $db->Quote('%' . $db->escape($search, true) . '%');
					$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
				}
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('listing.title LIKE ' . $search);
			}
		}

		$orderingAllow = array('listing.id', 'listing.title', 'listing.author', 'c.title', 'l.title',
			'listing.featured', 'listing.published', 'listing.created');
		if (in_array($listOrder, $orderingAllow))
		{
			if ($listOrder == 'c.title' || $listOrder == 'l.title')
			{
				$query->ORDER($listOrder . " " . $listDirn . ', listing.title');
			}
			else
			{
				$query->ORDER($listOrder . ' ' . $listDirn);
			}
		}

		return $query;
	}

}
