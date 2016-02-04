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
$rootCat = JUDirectoryFrontHelperCategory::getRootCategory();
$cat_id = JFactory::getApplication()->input->getInt("cat_id", $rootCat->id);
$search_in = $this->state->get('filter.search_in');
?>

<ul class="manager-actions nav nav-list" style="margin-bottom: 20px;">
	<?php
	$actions = JUDirectoryHelper::getActions('com_judirectory', 'category', $cat_id);
	if ($actions->get("judir.listing.create"))
	{
		if ($this->listingGroupCanDoManage && $this->allowAddListing)
		{
			echo "<li><a class='add-listing' href='index.php?option=com_judirectory&task=listing.add&cat_id=$cat_id'><i class='icon-file-add'></i>" . JText::_('COM_JUDIRECTORY_ADD_LISTING') . "</a></li>";
		}
	}
	if ($actions->get("judir.category.create"))
	{
		if ($this->catGroupCanDoManage)
		{
			echo "<li><a class='add-category' href='index.php?option=com_judirectory&task=category.add&parent_id=$cat_id'><i class='icon-folder-plus'></i>" . JText::_('COM_JUDIRECTORY_ADD_CATEGORY') . "</a></li>";
		}
	}
	if (JUDirectoryHelper::checkGroupPermission(null, "pendinglistings") && JUDIRPROVERSION)
	{
		echo "<li><a class='approved' href='index.php?option=com_judirectory&view=pendinglistings'><i class='icon-clock'></i>" . JText::sprintf('COM_JUDIRECTORY_PENDING_LISTINGS_N', JUDirectoryHelper::getTotalPendingListings()) . "</a></li>";
	}
	?>
</ul>

<div class="category-tree">
	<?php echo JUDirectoryHelper::getCategoryDTree($cat_id); ?>
</div>

<div id="judir-search" style="margin-top: 15px;">
	<form name="search-form" id="search-form" action="index.php?option=com_judirectory" method="POST">
		<fieldset>
			<div class="input-append">
				<input type="text" name="searchword" class="input-medium" size="20" maxlength="250" value="" placeholder="<?php echo JText::_('COM_JUDIRECTORY_SEARCH'); ?>" />
				<button type="submit" name="submit_simple_search" class="btn"><i class="icon-search"></i>&nbsp;</button>
			</div>
		</fieldset>

		<div class="clearfix">
			<select name="view" id="search-in" class="input-medium">
				<option value="searchlistings" selected><?php echo JText::_('COM_JUDIRECTORY_SEARCH_LISTINGS'); ?></option>
				<option value="searchcategories"><?php echo JText::_('COM_JUDIRECTORY_SEARCH_CATEGORIES'); ?></option>
			</select>
			<?php
			if(JUDIRPROVERSION)
			{ ?>
				<a class="btn btn-mini"
				   href="index.php?option=com_judirectory&amp;task=advsearch.search"><?php echo JText::_('COM_JUDIRECTORY_SEARCH_MORE'); ?></a>
			<?php
			} ?>
		</div>
	</form>
</div>