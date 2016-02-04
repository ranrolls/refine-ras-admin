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
?>
<h3 class="listing-related-caption">
	<?php echo JText::_('COM_JUDIRECTORY_RELATED_LISTINGS'); ?>
</h3>

<div class="related-listings clearfix">
	<?php foreach ($this->item->related_listings AS $relatedListing)
	{
		?>
		<div class="related-listing">
			<div class="related-listing-image">
				<a href="<?php echo $relatedListing->link; ?>" title="<?php echo $relatedListing->title; ?>">
					<img src="<?php echo $relatedListing->image; ?>" alt="<?php echo $relatedListing->title; ?>"/>
				</a>
			</div>
			<div class="related-listing-title">
				<a href="<?php echo $relatedListing->link; ?>" title="<?php echo $relatedListing->title; ?>">
					<?php echo $relatedListing->title; ?>
				</a>
			</div>
		</div>
	<?php
	} ?>
</div>