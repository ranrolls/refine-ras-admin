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
<div id="judir-container" class="jubootstrap component judir-container view-dashboard">
	<div id="judir-dashboard" class="judir-dashboard">
		<?php
			echo $this->loadTemplate('toolbar');
		?>
		<div class="quick-box-wrapper">
			<div class="quick-box">
				<div class="quick-box-head">
					<div class="quick-box-title"><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_OVERVIEW'); ?></div>
				</div>
				<div class="quick-box-body clearfix">
					<ul class="stat-list">
						<li>
							<span class="stat-info"><?php echo $this->totalListings ?></span>
							<span> <a
									href="<?php echo $this->link_user_listings; ?>"><?php echo JText::_('COM_JUDIRECTORY_USER_LISTINGS'); ?></a></span>
						</li>
						<li>
							<span class="stat-info"><?php echo $this->totalPublishedListings; ?></span>
							<span> <a
									href="<?php echo $this->link_user_published_listings; ?>"><?php echo JText::_('COM_JUDIRECTORY_PUBLISHED_LISTINGS'); ?></a></span>
						</li>
						<?php
						if ($this->params->get('listing_owner_can_view_unpublished_listing', 0))
						{
							?>
							<li>
								<span class="stat-info"><?php echo $this->totalUnPublishedListings; ?></span>
								<span> <a
										href="<?php echo $this->link_user_unpublished_listings; ?>"><?php echo JText::_('COM_JUDIRECTORY_UNPUBLISHED_LISTINGS'); ?></a></span>
							</li>
						<?php
						} ?>
						<li>
							<span class="stat-info"><?php echo $this->totalPendingListings; ?></span>
							<span> <a
									href="<?php echo $this->approvedListing; ?>"><?php echo JText::_('COM_JUDIRECTORY_OWNER_PENDING_LISTINGS'); ?></a></span>
						</li>
						<li>
							<span class="stat-info"><?php echo $this->totalComments ?></span>
							<span> <a
									href="<?php echo $this->comment; ?>"><?php echo JText::_('COM_JUDIRECTORY_COMMENTS'); ?></a></span>
						</li>
						<li>
							<span class="stat-info"><?php echo $this->totalCollections ?></span>
							<span> <a
									href="<?php echo $this->collections; ?>"><?php echo JText::_('COM_JUDIRECTORY_COLLECTIONS'); ?></a></span>
						</li>
						<li>
							<span class="stat-info"><?php echo $this->totalSubscriptions ?></span>
							<span> <a
									href="<?php echo $this->subscriptions; ?>"><?php echo JText::_('COM_JUDIRECTORY_SUBSCRIPTIONS'); ?></a></span>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<?php
		$isModerator = JUDirectoryFrontHelperModerator::isModerator();
		if ($isModerator)
		{
			?>
			<div id="quick-box-wrapper">
				<div class="quick-box">
					<div class="quick-box-head">
						<div class="quick-box-title"><?php echo JText::_('COM_JUDIRECTORY_MODERATOR_AREA'); ?></div>
					</div>
					<div class="quick-box-body clearfix">
						<ul class="stat-list">

							<li>
								<span class="stat-info"><?php echo $this->total_listing_mod_can_view; ?></span>
								<span> <a
										href="<?php echo $this->listings_link; ?>"><?php echo JText::_('COM_JUDIRECTORY_LISTINGS'); ?></a></span>
							</li>

							<li>
								<span class="stat-info"><?php echo $this->total_listing_mod_can_approval; ?></span>
								<span> <a
										href="<?php echo $this->unapproved_listings_link; ?>"><?php echo JText::_('COM_JUDIRECTORY_PENDING_LISTINGS'); ?></a></span>
							</li>

							<li>
								<span class="stat-info"><?php echo $this->total_comments_mod_can_manage; ?></span>
								<span> <a
										href="<?php echo $this->comments_link; ?>"><?php echo JText::_('COM_JUDIRECTORY_COMMENTS'); ?></a></span>
							</li>
							<li>
								<span class="stat-info"><?php echo $this->total_comments_mod_can_approval; ?></span>
								<span> <a
										href="<?php echo $this->unapproved_comments_link; ?>"><?php echo JText::_('COM_JUDIRECTORY_PENDING_COMMENTS'); ?></a></span>
							</li>
							<li>
								<span>
									<a class="btn btn-mini"
									   href="<?php echo JRoute::_('index.php?option=com_judirectory&view=modpermissions'); ?>">
										<i class="fa fa-shield"></i> <?php echo JText::_('COM_JUDIRECTORY_MODERATOR_PERMISSIONS'); ?>
									</a>
								</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<?php
		} ?>
	</div>
</div>