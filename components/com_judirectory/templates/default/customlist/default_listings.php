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

// Row counter
$this->row_counter = 0;

// Column counter
$this->col_counter = 0;
?>

<?php
if($this->params->get('category_show_map', 1) && $this->locations)
{
	?>
	<div id="julocation" class="julocation">
		<div class="map-canvas" style="width: 100%; height: 300px; margin-top: 10px; border: 1px solid #CCCCCC;"></div>
	</div>
<?php
}
?>

<form name="judir-form-listings" id="judir-form-listings" class="judir-form-listings" method="post" action="#">
	<?php
		// Load header
		echo $this->loadTemplate('header');
	?>

	<?php
		$listing_list_attr = '';
		if ($this->allow_user_select_view_mode)
		{
			$listing_list_attr .= 'id="view-mode-switch" ';
		}

		$listing_list_attr .= 'class="judir-listing-list ' . ($this->view_mode == 2 ? 'judir-view-grid' : 'judir-view-list') . '"';
	?>

	<!-- Listing list -->
	<div <?php echo $listing_list_attr; ?>>
		<div
			class="judir-listing-row <?php echo $this->listing_row_class; ?> judir-listing-row-<?php echo $this->row_counter + 1; ?> row">
		<?php
		foreach ($this->items AS $index => $item)
		{
			$this->index = $index;
			$this->item  = $item;
			echo $this->loadTemplate('listing');
		}
		?>
		</div>
	</div>

	<?php
		// Load footer
		echo $this->loadTemplate('footer');
	?>
</form>
