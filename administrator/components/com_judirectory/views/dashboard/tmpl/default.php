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

JHtml::_('behavior.multiselect');
JHtml::_('behavior.tooltip');

$model = $this->getModel();
$statistics = $this->get('Statistics');
$lastCreatedComments = $this->get('lastCreatedComments');
$lastCreatedListings = $model->getListings("lastCreatedListings");
$lastUpdatedListings = $model->getListings("lastUpdatedListings");
$popularListings = $model->getListings("popularListings");
$totalUnreadReports = $model->getTotalUnreadReports();
$totalClaims = $model->getTotalClaims();
$totalMailqs = $model->getTotalMailqs();
$totalPendingListing = JUDirectoryHelper::getTotalPendingListings();
$totalPendingComment = JUDirectoryHelper::getTotalPendingComments();
?>

<div id="iframe-help"></div>

<div class="adminform" id="adminForm">
<div class="cpanel-left">
<div id="position-icon" class="pane-sliders">
<?php if (JUDirectoryHelper::checkGroupPermission(null, "listcats"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=listcats'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_MANAGER'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/manager.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_MANAGER'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission("category.add"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;task=category.add'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_ADD_CATEGORY'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/category-add.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_ADD_CATEGORY'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission("listing.add"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;task=listing.add'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_ADD_LISTING'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/listing-add.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_ADD_LISTING'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "pendinglistings") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=pendinglistings'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_PENDING_LISTINGS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/pending-listing.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_PENDING_LISTINGS'); ?></span>
					<?php if ($totalPendingListing)
					{
						?>
						<span class="update-badge"><?php echo $totalPendingListing; ?></span>
					<?php
					}
					?>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "fields"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=fields'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_FIELDS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/field.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_FIELDS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "fieldgroups"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=fieldgroups'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_FIELD_GROUPS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/field-group.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_FIELD_GROUPS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "criterias") && JUDirectoryHelper::hasMultiRating())
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=criterias'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CRITERIAS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/criteria.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CRITERIAS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "criteriagroups") && JUDirectoryHelper::hasMultiRating())
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=criteriagroups'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CRITERIA_GROUPS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/criteria-group.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CRITERIA_GROUPS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "comments"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=comments'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_COMMENTS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/comment.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_COMMENTS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "pendingcomments"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=pendingcomments'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_PENDING_COMMENTS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/pending-comment.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_PENDING_COMMENTS'); ?></span>
					<?php if ($totalPendingComment)
					{
						?>
						<span class="update-badge"><?php echo $totalPendingComment; ?></span>
					<?php
					}
					?>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "emails") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=emails'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_EMAILS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/email.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_EMAILS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "mailqs") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=mailqs'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_MAIL_QUEUE'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/mailqueue.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_MAIL_QUEUE'); ?></span>
					<?php if ($totalMailqs)
					{
						?>
						<span class="update-badge"><?php echo $totalMailqs; ?></span>
					<?php
					}
					?>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "reports") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=reports'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_REPORTS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/report.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_REPORTS'); ?></span>
					<?php
					if ($totalUnreadReports)
					{
						?>
						<span class="update-badge"><?php echo $totalUnreadReports; ?></span>
					<?php
					}
					?>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "claims") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=claims'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CLAIMS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/claim.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CLAIMS'); ?></span>
					<?php
					if ($totalClaims)
					{
						?>
						<span class="update-badge"><?php echo $totalClaims; ?></span>
					<?php
					}
					?>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "logs") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=logs'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_LOGS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/log.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_LOGS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "plugins"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=plugins'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_PLUGINS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/plugin.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_PLUGINS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "styles"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=styles'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TEMPLATE_STYLES'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/style.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TEMPLATE_STYLES'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "languages"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=languages'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_LANGUAGES'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/language.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_LANGUAGES'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "collections") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=collections'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_COLLECTIONS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/collection.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_COLLECTIONS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php
}?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "tags"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=tags'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TAGS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/tag.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TAGS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "customlists") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=customlists'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CUSTOM_LISTS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/customlist.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CUSTOM_LISTS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "subscriptions") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=subscriptions'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_SUBSCRIPTIONS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/subscription.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_SUBSCRIPTIONS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "users") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=users'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_USERS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/user.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_USERS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "moderators") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=moderators'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_MODERATORS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/moderator.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_MODERATORS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "addresses") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=addresses'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_ADDRESSES'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/address.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_ADDRESSES'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "csvprocess") && JUDirectoryHelper::hasCSVPlugin())
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=csvprocess'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CSV'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/csv.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_CSV'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "backendpermission") && JUDIRPROVERSION)
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=backendpermission'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_BACKEND_PERMISSION'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/permission.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_BACKEND_PERMISSION'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<div class="cpanel">
	<div class="icon-wrapper">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=treestructure'); ?>">
				<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TREE_STRUCTURE'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/manager.png" />
				<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TREE_STRUCTURE'); ?></span>
			</a>
		</div>
	</div>
</div>

<?php
if (JUDirectoryHelper::checkGroupPermission(null, "globalconfig"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&view=globalconfig&layout=edit'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_GLOBALCONFIG'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/global-config.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_GLOBALCONFIG'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (JUDirectoryHelper::checkGroupPermission(null, "tools"))
{
	?>
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_judirectory&amp;view=tools'); ?>">
					<img alt="<?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TOOLS'); ?>" src="<?php echo JUri::root(true); ?>/administrator/components/com_judirectory/assets/img/icon/tool.png" />
					<span><?php echo JText::_('COM_JUDIRECTORY_DASHBOARD_TOOLS'); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php } ?>



</div>
</div>

<div class="cpanel-right">
<?php
echo JHtml::_('bootstrap.startAccordion', 'accordion', array('active' => 'top-5-sliders'));
echo JHtml::_('bootstrap.addSlide', 'accordion', JText::_('COM_JUDIRECTORY_TOP_5'), 'top-5-sliders', 'top-5-sliders');
echo JHtml::_('bootstrap.startTabSet', 'top-5', array('active' => 'last-add-listing'));
echo JHtml::_('bootstrap.addTab', 'top-5', 'last-add-listing', JText::_('COM_JUDIRECTORY_LAST_ADDED_LISTING'));
?>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th style="width: 30%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_TITLE'); ?></th>
		<th style="width: 20%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CATEGORIES'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED_BY'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED'); ?></th>
		<th style="width: 10%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'); ?></th>
		<th style="width: 10%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_APPROVED'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($lastCreatedListings AS $listing)
	{
		$link        = 'index.php?option=com_judirectory&amp;task=listing.edit&amp;id=' . $listing->id;
		$checked_out = $listing->checked_out ? JHtml::_('jgrid.checkedout', $listing->id, $listing->checked_out_name, $listing->checked_out_time, 'listings.', false) : '';
		?>
		<tr>
			<td><?php echo $checked_out ?>
				<a href="<?php echo $link; ?>" title="<?php echo $listing->title; ?>"><?php echo $listing->title; ?></a>
			</td>
			<td><?php echo $model->getCategories($listing->id); ?></td>
			<td><?php echo $listing->created_by_name; ?></td>
			<td><?php echo JHtml::date($listing->created, 'Y-m-d H:i:s'); ?></td>
			<td class="center"><?php echo JHtml::_('grid.boolean', $listing->id, $listing->published); ?></td>
			<td class="center"><?php echo JHtml::_('grid.boolean', $listing->id, $listing->approved); ?></td>
		</tr>
	<?php
	} ?>
	</tbody>
</table>
<?php
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'top-5', 'last-added-comment', JText::_('COM_JUDIRECTORY_LAST_ADDED_COMMENTS'));
?>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th style="width: 30%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_TITLE'); ?></th>
		<th style="width: 20%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_LISTING_TITLE'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED_BY'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED'); ?></th>
		<th style="width: 10%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'); ?></th>
		<th style="width: 10%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_APPROVED'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($lastCreatedComments AS $comment)
	{
		$link        = 'index.php?option=com_judirectory&amp;task=comment.edit&amp;id=' . $comment->id;
		$checked_out = $comment->checked_out ? JHtml::_('jgrid.checkedout', $comment->id, $comment->checked_out_name, $comment->checked_out_time, 'comments.', false) : '';
		$listing_link    = 'index.php?option=com_judirectory&amp;task=listing.edit&amp;id=' . $comment->listing_id;
		?>
		<tr>
			<td><?php echo $checked_out ?>
				<a href="<?php echo $link; ?>" title="<?php echo $comment->title; ?>">
					<?php echo $comment->title; ?>
				</a>
			</td>
			<td>
				<a href="<?php echo $listing_link; ?>" title="<?php echo $comment->listing_title; ?>">
					<?php echo $comment->listing_title; ?>
				</a>
			</td>
			<td><?php echo $comment->created_by_name; ?></td>
			<td><?php echo JHtml::date($comment->created, 'Y-m-d H:i:s'); ?></td>
			<td class="center"><?php echo JHtml::_('grid.boolean', $comment->id, $comment->published); ?></td>
			<td class="center"><?php echo JHtml::_('grid.boolean', $comment->id, $comment->approved); ?></td>
		</tr>
	<?php
	} ?>
	</tbody>
</table>
<!--  Last updated -->
<?php
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'top-5', 'last-updated-listing', JText::_('COM_JUDIRECTORY_LAST_UPDATED_LISTINGS'));
?>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th style="width: 50%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_TITLE'); ?></th>
		<th style="width: 20%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_UPDATED'); ?></th>
		<th style="width: 20%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED'); ?></th>
		<th style="width: 10%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($lastUpdatedListings AS $listing)
	{
		$link        = 'index.php?option=com_judirectory&amp;task=listing.edit&amp;id=' . $listing->id;
		$checked_out = $listing->checked_out ? JHtml::_('jgrid.checkedout', $listing->id, $listing->checked_out_name, $listing->checked_out_time, 'listings.', false) : '';
		?>
		<tr>
			<td><?php echo $checked_out ?>
				<a href="<?php echo $link; ?>" title="<?php echo $listing->title; ?>"><?php echo $listing->title; ?></a>
			</td>
			<td><?php echo $listing->updated; ?></td>
			<td><?php echo JHtml::date($listing->created, 'Y-m-d H:i:s'); ?></td>
			<td class="center"><?php echo JHtml::_('grid.boolean', $listing->id, $listing->published); ?></td>
		</tr>
	<?php
	} ?>
	</tbody>
</table>
<!--  !Last updated -->

<!--  popular listing -->
<?php
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'top-5', 'popular-listings', JText::_('COM_JUDIRECTORY_POPULAR_LISTINGS'));
?>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th style="width: 45%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_TITLE'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_HITS'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED'); ?></th>
		<th style="width: 15%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_MODIFIED'); ?></th>
		<th style="width: 10%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_PUBLISHED'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($popularListings AS $listing)
	{
		$link        = 'index.php?option=com_judirectory&amp;task=listing.edit&amp;id=' . $listing->id;
		$checked_out = $listing->checked_out ? JHtml::_('jgrid.checkedout', $listing->id, $listing->checked_out_name, $listing->checked_out_time, 'listings.', false) : '';
		?>
		<tr>
			<td><?php echo $checked_out ?>
				<a href="<?php echo $link; ?>" title="<?php echo $listing->title; ?>"><?php echo $listing->title; ?></a>
			</td>
			<td><?php echo $listing->hits; ?></td>
			<td><?php echo $listing->created; ?></td>
			<td><?php echo $listing->modified; ?></td>
			<td class="center"><?php echo JHtml::_('grid.boolean', $listing->id, $listing->published); ?></td>
		</tr>
	<?php
	} ?>
	</tbody>
</table>
<!--  !popular listing -->

<!--  Static -->
<?php
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'top-5', 'static', JText::_('COM_JUDIRECTORY_STATISTICS'));
?>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th style="width: 75%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_TYPE'); ?></th>
		<th style="width: 25%"><?php echo JText::_('COM_JUDIRECTORY_FIELD_TOTAL'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($statistics AS $key => $value)
	{
		?>
		<tr>
			<td><?php echo $key; ?></td>
			<td><?php echo $value; ?></td>
		</tr>
	<?php
	} ?>
	</tbody>
</table>
<!--  !Static -->

<?php
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.endTabSet');
echo JHtml::_('bootstrap.endSlide');
echo JHtml::_('bootstrap.endAccordion');
?>

<?php
if(JUDIRPROVERSION)
{
	echo JHtml::_('bootstrap.startAccordion', 'accordion-chart', array('active' => 'chart'));
	echo JHtml::_('bootstrap.addSlide', 'accordion-chart', JText::_('COM_JUDIRECTORY_CHART'), 'chart', 'chart');
	$document = JFactory::getDocument();
	$document->addScript('https://www.google.com/jsapi');
	$app          = JFactory::getApplication();
	$type         = $app->getUserState('com_judirectory.dashboard.chart.type', 'day');
	$uploadData = $this->getModel()->getSubmissionData($type);
	?>
	<script type="text/javascript">
		uploadData = '<?php echo json_encode($uploadData); ?>';
		var parsed = JSON.parse(uploadData);
		uploadData = [];
		for (key in parsed) {
			if (parsed.hasOwnProperty(key)) {
				uploadData[key] = parsed[key];
			}
		}

		google.load("visualization", "1", {packages: ["corechart"]});
		google.setOnLoadCallback(drawChart);

		function drawChart() {
			var vAxisTitle = getvAxisTitle('<?php echo $type; ?>');
			_drawChart(uploadData, vAxisTitle);
		}

		function _drawChart(uploadData, vAxisTitle) {
			var data = new google.visualization.DataTable();
			data.addColumn('string', '<?php echo JText::_('COM_JUDIRECTORY_DAY')?>');
			data.addColumn('number', '<?php echo JText::_('COM_JUDIRECTORY_SUBMISSIONS')?>');
			data.addRows(uploadData.length);
			for (var $i = 0; $i < uploadData.length; $i++) {
				for (var $j = 0; $j < uploadData[$i].length; $j++) {
					if ($j == 0) {
						data.setCell($i, $j, String(uploadData[$i][$j]));
					} else {
						data.setCell($i, $j, parseInt(uploadData[$i][$j]));
					}
				}
			}

			var options = {
				axisTitlesPosition: 'in',
				chartArea: {left: 50, top: 80, width: '100%'},
				legend: {position: 'top'},
				title: '<?php echo JText::sprintf('COM_JUDIRECTORY_LISTING_SUBMISSION_CHART', date('M/Y')); ?>',
				pointSize: 2,
				lineWidth: 1,
				hAxis: {title: '<?php echo JText::_('COM_JUDIRECTORY_TIMES'); ?>'},
				vAxis: {title: vAxisTitle}
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		}

		function getvAxisTitle(type) {
			switch (type) {
				case 'day':
					var vAxisTitle = '<?php echo JText::_('COM_JUDIRECTORY_HOUR'); ?>';
					break;
				case 'week':
				case 'month':
					var vAxisTitle = '<?php echo JText::_('COM_JUDIRECTORY_DAY'); ?>';
					break;
				case 'year':
					var vAxisTitle = '<?php echo JText::_('COM_JUDIRECTORY_MONTH'); ?>';
					break;
				default :
					var vAxisTitle = '';
			}

			return vAxisTitle;
		}

		jQuery(document).ready(function ($) {
			$('#upload_chart').change(function () {
				type = $(this).val();
				$.ajax({
					url: "index.php?option=com_judirectory&task=dashboard.getChartData",
					data: {type: type},
					dataType: 'json',
					beforeSend: function () {
						$('#chart_div').css({opacity: 0.5}).append('<img style="position: absolute; top: 50%; left: 50%; opacity: 1" src="<?php echo JURi::base(true);?>/components/com_judirectory/assets/img/orig-loading.gif"/>');
					}
				})
					.done(function (uploadData) {
						if (uploadData) {
							$('#chart_div').css({opacity: 1});
							var vAxisTitle = getvAxisTitle(type);
							_drawChart(uploadData, vAxisTitle);
						}
					});
			});
		});
	</script>

	<?php
	$typeOptions = array('day' => JText::_('COM_JUDIRECTORY_DAY'), 'week' => JText::_('COM_JUDIRECTORY_WEEK'), 'month' => JText::_('COM_JUDIRECTORY_MONTH'), 'year' => JText::_('COM_JUDIRECTORY_YEAR'));
	echo JHtml::_('select.genericlist', $typeOptions, 'upload_chart', 'class="input-medium"', 'text', 'value', $type);
	?>

	<div id="chart_div" style="width: 100%; height: 350px;"></div>

	<?php
	echo JHtml::_('bootstrap.endSlide');
	echo JHtml::_('bootstrap.endAccordion');
}
?>
</div>
</div>

<div class="clearfix"></div>

<div class="center small">
	<div><?php echo JUDirectoryHelper::getComVersion(); ?></div>
	<div>A product of <a href="http://www.joomultra.com" title="Visit JoomUltra website" target="_blank">JoomUltra</a></div>
</div>