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

//$total_sub_cats = count($this->subcategories);
$counter = 0;
?>
<div class="judir-subcats clearfix">
	<div
		class="judir-subcat-row clearfix <?php $this->params->get('add_class_rows_cat'); ?> judir-subcat-row-<?php echo $counter; ?>">
		<?php
		foreach ($this->subcategories AS $key => $subcategory){
		if (!empty($subcategory->images))
		{
			$images = json_decode($subcategory->images);
		} ?>
		<div
			class="judir-subcat clearfix <?php echo $this->params->get('add_class_columns_cat') . " judir-subcat-column" . $key; ?>" <?php echo $this->subCatStyle; ?>>

			<?php if ($this->params->get('category_show_image', 1) && !empty($subcategory->images))
			{
				?>
				<div class="subcat-image">
					<a href="<?php JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($subcategory->id)); ?>">
						<img
							src="<?php echo JUri::root(true) . '/media/com_judirectory/images/category/intro/' . $images->intro_image; ?>"
							style="max-width:<?php echo $this->params->get('category_intro_image_width', 200); ?>px;max-height:<?php echo $this->params->get('category_intro_image_height', 200); ?>px"
							alt="<?php echo $images->intro_image_alt; ?>"
							title="<?php echo $images->intro_image_caption; ?>"/>
					</a>
				</div>
			<?php } ?>

			<div class="subcat-title">
				<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getCategoryRoute($subcategory->id)); ?>"><?php echo $subcategory->title; ?></a>
				<?php
				$totalSubCats = JUDirectoryFrontHelper::getTotalInsideCategories($subcategory->id);
				$totalListings = JUDirectoryFrontHelper::getTotalListingInCategory($subcategory->id);
				?>
				<span><?php echo "(" . $totalSubCats . " Subcategory /" . $totalListings . " Listing)"; ?></span>
			</div>

			<?php
			if ($this->params->get('show_subcategories_introtext', 1))
			{
				$cat_introtext_limit = (int) $this->params->get('categories_introtext_limit', 1);
				if ($cat_introtext_limit == 1)
				{
					$cat_introtext_limit_char = (int) $this->params->get('categories_introtext_limit_character', 1500);
					$cat_introtext            = JUDirectoryFrontHelperString::truncateHtml($subcategory->introtext, $cat_introtext_limit_char);
				}
				else
				{
					$cat_introtext = $subcategory->introtext;
				}
				?>
				<div class="subcat-desc">
					<?php
					if ($this->params->get('plugin_support', 0))
					{
						echo JHtml::_('content.prepare', $cat_introtext);
					}
					else
					{
						echo $cat_introtext;
					}
					?>
				</div>
			<?php
			}
			?>
		</div>

		<?php
		if ((($key + 1) % $this->categoryColumns) == 0){
		$counter += 1;
		?>
	</div>
	<div
		class="judir-subcat-row clearfix <?php $this->params->get('add_class_rows_cat'); ?> judir-subcat-row-<?php echo $counter; ?>">
		<?php
		}
		}
		?>
	</div>
</div>