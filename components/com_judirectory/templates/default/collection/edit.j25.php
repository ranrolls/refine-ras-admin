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
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'collection.cancel' || document.formvalidator.isValid(document.id('collection-form'))) {
			Joomla.submitform(task, document.getElementById('collection-form'));
		}
	};
</script>

<?php
if (is_object($this->item) && $this->item->id > 0)
{
	echo "<h2>" . JText::_("COM_JUDIRECTORY_EDIT") . ": " . $this->item->title . "</h2>";
}
else
{
	echo "<h2>" . JText::_("COM_JUDIRECTORY_CREATE_NEW_COLLECTION") . "</h2>";
}
?>
<div id="judir-container" class="jubootstrap juform">
	<form
		action="<?php echo JRoute::_("index.php?option=com_judirectory&layout=edit" . isset($this->item) && is_object($this->item) ? "&id=" . (int) $this->item->id : ""); ?>"
		method="post" name="collection-form" id="collection-form" class="form-validate form-horizontal form-search"
		enctype="multipart/form-data">
		<?php
		echo JHtml::_('tabs.start', 'judirectory', array('useCookie' => 1));
		if(!$this->item || !$this->item->global)
		{
			echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_COLLECTION_DETAILS'), 'collection-details');
			foreach ($this->form->getFieldset('details') AS $field)
			{
				?>
				<div class='control-group'>
					<div class="control-label">
						<?php echo $field->label; ?>
					</div>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
			<?php
			}
		}
		echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_LISTINGS'), 'listings');
		?>
		<h4 class="text-right">
			<?php if (isset($this->items))
			{
				echo JText::plural("COM_JUDIRECTORY_N_LISTINGS_IN_COLLECTION", count($this->items));
			} ?>
		</h4>

		<div class="collection-search-listings">
			<input type="text" id="search-listing" class="input-large autosuggest" placeholder="<?php echo JText::_('COM_JUDIRECTORY_TYPE_TO_SEARCH_LISTING'); ?>"/>
			<input type="hidden" id="listing-id" name="listing-id" value=""/>
			<input type="hidden" id="listing-title" name="listing-title" value=""/>
			<input type="hidden" id="listing-image" name="listing-image" value=""/>
			<input type="hidden" id="listing-link" name="listing-link" value=""/>
			<button type="submit" class="btn btn-default" id="add-listing-to-collection"><?php echo JText::_("COM_JUDIRECTORY_ADD_LISTING"); ?></button>
		</div>

		<div>
			<input type="hidden" id="pending" value=""/>
		</div>

		<table class="table table-striped table-bordered collection-listings-list" id="table-collection-items">
			<thead>
			<tr>
				<th class="center">
					<p class="text-center"><?php echo JText::_("COM_JUDIRECTORY_LISTINGS"); ?></p>
				</th>
				<th class="center">
					<p class="text-center"><?php echo JText::_("COM_JUDIRECTORY_FIELD_CREATED"); ?></p>
				</th>
				<th class="center">
					<p class="text-center"><?php echo JText::_("COM_JUDIRECTORY_REMOVE"); ?></p>
				</th>
			</tr>
			</thead>

			<tbody id="table-collection-items-tbody">
			<?php
			if (count($this->items))
			{
				$i = 0;
				foreach ($this->items AS $item)
				{
					$specialFields = JUDirectoryFrontHelperField::getFields($item, null, array("description", "created", "title", "cat_id", "image"));
					?>
					<tr id="coll-item-row-<?php echo $i; ?>">
						<td>
							<?php
							$imageField = $specialFields['image'];
							if (isset($imageField) && $imageField->canView())
							{
								?>
								<div class="collection-item-icon">
									<?php echo $imageField->getOutput(array('view' => "list")); ?>
								</div>
							<?php
							}
							?>
							<?php
							$titleField = $specialFields['title'];
							if (isset($titleField) && $titleField->canView())
							{
								echo $titleField->getDisplayPrefixText() . " " . $titleField->getOutput() . " " . $titleField->getDisplaySuffixText();
							}
							?>
							<input type="hidden" name="listings[]" value="<?php echo $item->id; ?>"/>
						</td>
						<td >
							<p class="text-center">
							<?php
							echo $item->createdAgo;
							?>
							</p>
						</td>
						<td>
							<p class="text-center"><i class="fa fa-trash-o btn-lg remove-listing" data-index="<?php echo $i; ?>"></i></p>
						</td>
					</tr>
					<?php
					$i++;
				}
			}
			?>
			</tbody>
		</table>
		<?php
		echo JHtml::_('tabs.end');
		?>

		<div class="judir-submit-buttons">
			<button type="button" class="btn btn-default btn-primary" id="save-edit-collection-button">
				<i class="fa fa-save"></i> <?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?>
			</button>

			<button type="button" class="btn btn-default" onclick="Joomla.submitbutton('collection.cancel')">
				<?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?>
			</button>
		</div>

		<div>
			<input type="hidden" name="listing-image-width" value="<?php echo $this->listing_image_width; ?>"/>
			<input type="hidden" name="listing-image-height" value="<?php echo $this->listing_image_height; ?>"/>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>