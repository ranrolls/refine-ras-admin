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

$this->col_counter++;


$item_class_arr = array();
$item_class_arr[] = "judir-listing judir-listing-column" . $this->col_counter;
if ($this->item->label_new)
{
	$item_class_arr[] = "new";
}

if ($this->item->label_updated)
{
	$item_class_arr[] = "updated";
}

if ($this->item->label_hot)
{
	$item_class_arr[] = "hot";
}

if ($this->item->label_featured)
{
	$item_class_arr[] = "featured";
}

$item_class_arr[] = $this->listing_column_class ? $this->listing_column_class : "";
$item_class_arr[] = $this->view_mode == 2 ? "col-md-" . $this->listing_bootstrap_columns[$this->col_counter - 1] : "col-md-12";

$item_class = implode(" ", $item_class_arr);

$listing_grid_col = $this->listing_bootstrap_columns[$this->col_counter - 1];

// Get all list view fields
$additionFields = array();
$fields = JUDirectoryFrontHelperField::getFields($this->item, 1, array(), array(), $additionFields);

// Ignore these fields from summary fields
$ignoredFields = array("title", "description", "image", "publish_up", "created_by", "featured", "cat_id", "tags","introtext");



$summaryFields = array();
foreach ($fields AS $fieldKey => $field)
{


	if (!in_array($fieldKey) && is_object($field) && $field->canView(array("view" => "list")))
	{
		$summaryFields[] = $field;
		//print_r($field);
	}
}
?>
	<div class="<?php echo $item_class; ?>" data-list-class="col-md-12" data-grid-class="col-md-<?php echo $listing_grid_col; ?>">
	<?php
	$imageField = isset($fields['image']) ? $fields['image'] : null;
	if ($imageField && $imageField->canView(array("view" => "list")))
	{
		?>
		<div class="listing-image"><?php echo $imageField->getOutput(array("view" => "list", "template" => $this->template)); ?></div>
	<?php
	} ?>
	 

	<?php
	if(JUDIRPROVERSION)
	{
		echo $this->loadTemplate('private_actions');
	}
	?>


	<?php
	$titleField = isset($fields['title']) ? $fields['title'] : null;
	if ($titleField && $titleField->canView(array("view" => "list")))
	{
		?>
		<h3 class="listing-title">
			<?php
			echo $titleField->getDisplayPrefixText() . " " . $titleField->getOutput(array("view" => "list", "template" => $this->template)) . " " . $titleField->getDisplaySuffixText();

			if ($this->item->label_new)
			{
				?>
				<span class="label label-new"><?php echo JText::_('COM_JUDIRECTORY_NEW'); ?></span>
			<?php
			}

			if ($this->item->label_updated)
			{
				?>
				<span class="label label-updated"><?php echo JText::_('COM_JUDIRECTORY_UPDATED'); ?></span>
			<?php
			}

			if ($this->item->label_hot)
			{
				?>
				<span class="label label-hot"><?php echo JText::_('COM_JUDIRECTORY_HOT'); ?></span>
			<?php
			}

			if ($this->item->label_featured)
			{
				?>
				<span class="label label-featured"><?php echo JText::_('COM_JUDIRECTORY_FEATURED'); ?></span>
			<?php
			} ?>
		</h3>
	<?php
		echo $this->item->event->afterDisplayTitle;
	} ?>

	<?php
	$descriptionField = isset($fields['description']) ? $fields['description'] : null;
	
	 
	if ($descriptionField && $descriptionField->canView(array("view" => "list")))
	{
		echo $this->item->event->beforeDisplayContent;
		?>

		<div class="listing-introtext">
			<?php// echo $descriptionField->getDisplayPrefixText() . " " . $descriptionField->getOutput(array("view" => "list", "template" => $this->template)) . " " . $descriptionField->getDisplaySuffixText(); ?>
 </div>
  
	<?php
		echo $this->item->event->beforeDisplayContent;
		//echo $this->item->event->afterDisplayTitle;
	} ?>


<!-----Start Here -----> 
<?php
	
if ($descriptionField && $descriptionField->canView(array("view" => "list")))
{
//print_r($descriptionField);
	 
//foreach ($summaryFields AS $summaryField)
//{
//}
echo ($descriptionField ->value);
	
	 
	

?>
		<div class="listing-introtext">
			<?php //echo $descriptionField->fulltext. " " . $descriptionField->getOutput(array("view" => "list", "template" => $this->template)) . " " . $descriptionField->fulltext; ?>
 </div>
 
 <?php }
 
?>
	  
<!----End Here ------->

	<?php
	$tagsField = isset($fields['tags']) ? $fields['tags'] : null;
	if ($tagsField && $tagsField->canView(array("view" => "list")))
	{
		?>
		<div class="listing-tags pull-left">
			<span class="caption"><span class="fa fa-tags"></span></span>
			<?php echo $tagsField->getDisplayPrefixText() . " " . $tagsField->getOutput(array("view" => "list", "template" => $this->template)) . " " . $tagsField->getDisplaySuffixText(); ?>
		</div>
	<?php
	} ?>

	<?php
	if($this->params->get('show_compare_btn_in_listview', 0))
	{
	?>
		<div class="listing-actions pull-right">
			<div class="pull-right">
	            <a onclick="addToCompare(<?php echo $this->item->id; ?>);"
	               title="<?php echo JText::_('COM_JUDIRECTORY_ADD_TO_COMPARE'); ?>" class="hasTooltip btn btn-default btn-xs">
	                <i class="fa fa-exchange"></i>
	            </a>
			</div>
		</div>
	<?php
	} ?>
	<!-- end: listing-actions -->

	<?php
	if (count($summaryFields) > 0)
	{
		?>
		<ul class="listing-summary">
			<?php
			foreach ($summaryFields AS $summaryField)
			{
				?>
				<li class="listing-field field-<?php echo $summaryField->id; ?>">
					<?php
					if ($summaryField->hasCaption())
					{
						?>
						<div class="caption">
							<?php echo $summaryField->getCaption(); ?>
						</div>
					<?php
					} ?>
					<div class="value">
						<?php echo $summaryField->getDisplayPrefixText() . " " . $summaryField->getOutput(array("view" => "list", "template" => $this->template)) . " " . $summaryField->getDisplaySuffixText(); ?>
					</div>
				</li>
			<?php
			} ?>
		</ul>
	<?php
	} ?>
	<!-- end: listing-summary -->
	</div>
	<!--end: judir-listing -->
<?php
if ((($this->col_counter % $this->listing_columns) == 0) && (($this->index + 1) < count($this->items)))
{
	$this->row_counter += 1;
	$this->col_counter = 0;
	?>
	</div>
	<!--end: judir-listing-row -->
	<div class="judir-listing-row <?php echo $this->listing_row_class; ?> judir-listing-row-<?php echo $this->row_counter + 1; ?> row">
<?php
}
