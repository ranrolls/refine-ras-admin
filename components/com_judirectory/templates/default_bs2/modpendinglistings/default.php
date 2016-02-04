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
<div id="judir-container" class="jubootstrap component judir-container view-pendinglistings">
<h2 class="judir-view-title"><?php echo JText::_('COM_JUDIRECTORY_PENDING_LISTINGS'); ?></h2>

<?php if (!is_array($this->items) || !count($this->items))
{
	?>
	<div class="alert alert-block">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo JText::_('COM_JUDIRECTORY_NO_PENDING_LISTING'); ?>
	</div>
<?php
} ?>

<form name="judir-listings-form" id="judir-listings-form" class="judir-form" method="post"
      action="<?php echo JRoute::_('index.php?option=com_judirectory&view=modpendinglistings'); ?>">

<?php
	echo $this->loadTemplate('header');
?>

<table class="table table-striped table-bordered">
<thead>
<tr>
	<th style="width:5%" class="center">
		<input type="checkbox" name="judir-cbAll" id="judir-cbAll"
		       title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value=""/>
	</th>
	<th>
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_TITLE'); ?>
	</th>
	<th style="width:15%" class="center">
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_CATEGORY'); ?>
	</th>
	<th style="width:15%" class="center">
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED_BY'); ?>
	</th>
	<th style="width:15%" class="center">
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_CREATED'); ?>
	</th>
	<th style="width:5%" class="center">
		<?php echo JText::_('COM_JUDIRECTORY_FIELD_ID'); ?>
	</th>
</tr>
</thead>

<tbody>
<?php
if (is_array($this->items) && count($this->items))
{
	foreach ($this->items AS $i => $item)
	{
		$this->item = $item;
		$fields = JUDirectoryFrontHelperField::getFields($item, null);
		?>
		<tr>
		<td class="center">
			<input type="checkbox" checked="checked" class="judir-cb" name="cid[]" value="<?php echo $item->id; ?>"
			       id="judir-cb-<?php echo $i; ?>"/>
		</td>
		<td>
			<?php
			$user = JFactory::getUser();
			$uri = JUri::getInstance();
			// Show checked_out icon if the listing is checked out by a different user
			if (property_exists($item, 'checked_out') && property_exists($item, 'checked_out_time') && $item->checked_out > 0 && $item->checked_out != $user->get('id'))
			{
				$checkoutUser = JFactory::getUser($item->checked_out);
				$date         = JHtml::_('date', $item->checked_out_time);
				$tooltip      = JText::_('COM_JUDIRECTORY_CHECKED_OUT') . ' <br/> ' . JText::sprintf('COM_JUDIRECTORY_CHECKED_OUT_BY', $checkoutUser->name) . ' <br /> ' . $date;

				$url    = JRoute::_('index.php?option=com_judirectory&task=forms.checkin&id=' . $item->id . '&' . JSession::getFormToken() . '=1' . '&return=' . base64_encode(urlencode($uri)));

				echo '<a class="btn btn-mini" href="' . $url . '"><i class="hasTooltip fa fa-lock" title="' . $tooltip . '"></i></a>';
			}
			?>
			<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=modpendinglisting.edit&id=' . (int) $item->id . '&approve=1'); ?>"
			   title="<?php echo $item->title; ?>">
				<?php echo $item->title; ?></a>
			<?php if ($item->approved == 0)
			{
				?>
				<span class="label label-pending"><?php echo JText::_('COM_JUDIRECTORY_NEW_PENDING_LISTING'); ?></span>
			<?php
			}
			elseif ($item->approved < 0)
			{
				?>
				<span class="label label-pending"><?php echo JText::_('COM_JUDIRECTORY_EDIT_PENDING_LISTING'); ?></span>
			<?php
			} ?>

			<?php
			if ($item->label_unpublished)
			{
				?>
				<span class="label label-unpublished"><?php echo JText::_('COM_JUDIRECTORY_UNPUBLISHED'); ?></span>
			<?php
			}

			if ($item->label_pending)
			{
				?>
				<span class="label label-pending"><?php echo JText::_('COM_JUDIRECTORY_PENDING'); ?></span>
			<?php
			}

			if ($item->label_expired)
			{
				?>
				<span class="label label-expired"><?php echo JText::_('COM_JUDIRECTORY_EXPIRED'); ?></span>
			<?php
			}

			if ($item->label_new)
			{
				?>
				<span class="label label-new"><?php echo JText::_('COM_JUDIRECTORY_NEW'); ?></span>
			<?php
			}

			if ($item->label_updated)
			{
				?>
				<span class="label label-updated"><?php echo JText::_('COM_JUDIRECTORY_UPDATED'); ?></span>
			<?php
			}

			if ($item->label_hot)
			{
				?>
				<span class="label label-hot"><?php echo JText::_('COM_JUDIRECTORY_HOT'); ?></span>
			<?php
			}

			if ($item->label_featured)
			{
				?>
				<span class="label label-featured"><?php echo JText::_('COM_JUDIRECTORY_FEATURED'); ?></span>
			<?php
			} ?>
		</td>
		<td class="center">
			<?php
			$categoriesField = $fields['cat_id'];
			echo $categoriesField->getOutput(array("view" => "list", "template" => $this->template));
			?>
		</td>
		<td class="center">
			<?php
			$createdByField = $fields['created_by'];
			echo $createdByField->getOutput(array("view" => "list", "template" => $this->template));
			?>
		</td>
		<td class="center">
			<?php
			$createdField = $fields['created'];
			echo $createdField->getOutput(array("view" => "list", "template" => $this->template));
			?>
		</td>
		<td class="center">
			<?php echo $item->id; ?>
		</td>
		</tr>
	<?php
	}
} ?>
</tbody>
</table>

<?php
	echo $this->loadTemplate('footer');
?>

<div>
	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>

</div>