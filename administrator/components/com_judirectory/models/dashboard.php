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


class JUDirectoryModelDashboard extends JModelList
{
	
	protected function getListQuery()
	{
		return true;
	}

	

	public function getLastCreatedComments()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('cm.*');
		$query->from('#__judirectory_comments AS cm');
		$query->select('listing.title AS listing_title');
		$query->join('LEFT', '#__judirectory_listings AS listing ON(listing.id = cm.listing_id)');
		$query->select('ua.name AS created_by_name');
		$query->join('LEFT', '#__users AS ua ON(ua.id = cm.user_id)');
		$query->select('ua1.name AS checked_out_name');
		$query->join('LEFT', '#__users AS ua1 ON(ua1.id = cm.checked_out)');
		$query->where('cm.parent_id != 0 AND cm.level != 0');
		$db->setQuery($query, 0, 5);
		$data = $db->loadObjectList();

		return $data;
	}

	public function getCategories($listing_id, $link = true)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($link)
		{
			$query->select("c.title, c.id");
		}
		else
		{
			$query->select("c.title");
		}

		$query->from("#__judirectory_categories AS c");
		$query->join("", "#__judirectory_listings_xref AS listingxref ON listingxref.cat_id = c.id");
		$query->join("", "#__judirectory_listings AS listing ON listingxref.listing_id = listing.id");
		$query->where("listing.id = $listing_id");
		$query->order("listingxref.main DESC, c.title ASC");
		$db->setQuery($query);
		$result = array();
		if ($link)
		{
			$categories = $db->loadObjectList();
			foreach ($categories AS $category)
			{
				$href     = "index.php?option=com_judirectory&view=listcats&cat_id=" . $category->id;
				$result[] = "<a href=\"$href\">" . $category->title . "</a>";
			}
		}
		else
		{
			$result = $db->loadColumn();
		}

		return implode(", ", $result);
	}

	public function getListings($type, $limit = 5)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->SELECT("listing.id, listing.title, listing.created, listing.modified, listing.updated, listing.hits, listing.approved, listing.published, listing.checked_out, listing.checked_out_time");
		$query->FROM("#__judirectory_listings AS listing");
		$query->SELECT("ua1.name AS checked_out_name");
		$query->JOIN("LEFT", "#__users AS ua1 ON ua1.id = listing.checked_out");
		switch ($type)
		{
			case "lastCreatedListings" :
				$query->SELECT("ua.name AS created_by_name");
				$query->JOIN("LEFT", "#__users AS ua ON ua.id = listing.created_by");
				$query->ORDER("listing.created DESC LIMIT 0, $limit");
				break;

			case "lastUpdatedListings" :
				$query->SELECT("ua.name AS modified_by_name");
				$query->JOIN("LEFT", "#__users AS ua ON ua.id = listing.modified_by");
				$query->WHERE("listing.updated > '0000-00-00 00:00:00'");
				$query->ORDER("listing.updated DESC LIMIT 0, $limit");
				break;

			case "popularListings" :
				$query->WHERE("listing.hits > 0");
				$query->ORDER("listing.hits DESC LIMIT 0, $limit");
				break;
		}
		$db->setQuery($query);
		$data = $db->loadObjectList();

		return $data;
	}

	public function getStatistics()
	{
		$db     = $this->getDbo();
		$static = array();

		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*) AS total_categories");
		$query->FROM("#__judirectory_categories");
		$db->setQuery($query);
		$total                = $db->loadResult();
		$static['Categories'] = $total;

		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*) AS total_listings");
		$query->FROM("#__judirectory_listings");
		$db->setQuery($query);
		$total              = $db->loadResult();
		$static['Listings'] = $total;

		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*) AS total_tags");
		$query->FROM("#__judirectory_tags");
		$db->setQuery($query);
		$total          = $db->loadResult();
		$static['Tags'] = $total;

		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*) AS total_comments");
		$query->FROM("#__judirectory_comments");
		$query->where('parent_id != 0 AND level != 0');
		$db->setQuery($query);
		$total              = $db->loadResult();
		$static['Comments'] = $total;

		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*) AS total_collections");
		$query->FROM("#__judirectory_collections");
		$db->setQuery($query);
		$total                 = $db->loadResult();
		$static['Collections'] = $total;

		return $static;
	}

	public function getTotalUnreadReports()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*)");
		$query->FROM("#__judirectory_reports");
		$query->WHERE("`read` != 1");
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function getTotalClaims()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*)");
		$query->FROM("#__judirectory_claims");
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function getTotalMailqs()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->SELECT("COUNT(*)");
		$query->FROM("#__judirectory_mailqs AS mq");
		$query->JOIN('', '#__judirectory_emails AS m ON (mq.email_id = m.id)');
		$db->setQuery($query);

		return $db->loadResult();
	}
}
