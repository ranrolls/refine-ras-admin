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

$item_class = "";
if ($this->item->label_new)
{
	$item_class .= " new";
}

if ($this->item->label_updated)
{
	$item_class .= " updated";
}

if ($this->item->label_hot)
{
	$item_class .= " hot";
}

if ($this->item->label_featured)
{
	$item_class .= " featured";
}
?>
<div id="judir-container"
     class="jubootstrap component judir-container view-listing layout-print <?php echo $item_class; ?> category-<?php echo $this->item->cat_id; ?> <?php echo isset($this->tl_catid) ? 'tlcat-id-' . $this->tl_catid : ""; ?> <?php echo $this->item->class_sfx; ?> <?php echo $this->pageclass_sfx; ?>">

	<?php
		echo $this->loadTemplate('listing_details');
	?>

	<?php
	if (is_object($this->item->fieldGallery) && $this->item->fieldGallery->canView())
	{
		echo $this->loadTemplate('gallery');
	} ?>

	<?php
	if(is_object($this->item->fieldLocations) && $this->item->fieldLocations->canView())
	{
		echo $this->loadTemplate('map');
	} ?>
</div>