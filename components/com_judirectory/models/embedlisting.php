<?php
/**
 * ------------------------------------------------------------------------
 * JUDirectory for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 *
 * @copyright      Copyright (C) 2010-2014 JoomUltra Co., Ltlisting. All Rights Reservelisting.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @author         JoomUltra Co., Ltd
 * @website        http://www.joomultra.com
 * @----------------------------------------------------------------------@
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class JUDirectoryModelEmbedListing extends JUDIRModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'listing.id',
				'listing.title',
				'c.title',
				'listing.access',
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
		
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '');
		$this->setState('filter.search', $search);

		$featured = $this->getUserStateFromRequest($this->context . '.filter.featured', 'filter_featured', '');
		$this->setState('filter.featured', $featured);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_catid', '');
		$this->setState('filter.catid', $categoryId);

		
		$params = JUDirectoryHelper::getParams();
		$this->setState('params', $params);

		
		parent::populateState('listing.title', 'asc');

		$field_display = $this->getUserStateFromRequest($this->context . '.field_display', 'field_display', array());
		$this->setState('field_display', $field_display);
	}

	
	protected function getListQuery()
	{
		
		$db   = $this->getDbo();
		$date = JFactory::getDate();

		$now      = $date->toSql();
		$nullDate = $db->getNullDate();

		$query = $db->getQuery(true);

		
		$query->select('listing.id, listing.title, listing.alias, listing.created, listing.access');
		$query->from('#__judirectory_listings AS listing');

		
		$query->join('', '#__judirectory_listings_xref AS listingxref ON dxref.doc_id = listing.id AND dxref.main = 1');
		
		$query->select('c.title AS category_title');
		$query->join('', '#__judirectory_categories AS c ON c.id = dxref.cat_id');

		
		$categoryIdArrayCanAccess = JUDirectoryFrontHelperPermission::getAccessibleCategoryIds();
		if (is_array($categoryIdArrayCanAccess) && count($categoryIdArrayCanAccess) > 0)
		{
			$query->where('c.id IN(' . implode(",", $categoryIdArrayCanAccess) . ')');
		}
		else
		{
			$query->where('c.id IN("")');
		}

		
		$query->select('vl.title AS access_title');
		$query->join('LEFT', '#__viewlevels AS vl ON vl.id = listing.access');

		
		$access = $this->getState('filter.access');
		if ($access)
		{
			$query->where('listing.access = ' . (int) $access);
		}

		
		$categoryId = $this->getState('filter.catid');
		if (is_numeric($categoryId))
		{
			$query->where('c.id = ' . (int) $categoryId);
		}

		$query->where('c.published = 1');
		$query->where('c.publish_up <= ' . $db->quote($now));
		$query->where('(c.publish_down = ' . $db->quote($nullDate) . ' OR c.publish_down > ' . $db->quote($now) . ')');

		
		$featured = $this->getState('filter.featured', '');
		if ($featured !== '')
		{
			$query->where('listing.featured = ' . (int) $featured);
		}

		
		$query->where('listing.approved = 1');
		$query->where('listing.published = 1');
		$query->where('listing.publish_up <= ' . $db->quote($now));
		$query->where('(listing.publish_down = ' . $db->quote($nullDate) . ' OR listing.publish_down > ' . $db->quote($now) . ')');

		
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('listing.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
				$query->where('listing.title LIKE ' . $search);
			}
		}

		
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol)
		{
			$query->order($orderCol . ' ' . $orderDirn);
		}

		return $query;
	}
}