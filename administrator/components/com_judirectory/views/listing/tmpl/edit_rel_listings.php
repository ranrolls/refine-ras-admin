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
<script type="text/javascript">
	var default_image = "<?php echo JUDirectoryHelper::getDefaultListingImage(); ?>";
	var listing_image_url = "<?php echo JUri::root(true) . "/". JUDirectoryFrontHelper::getDirectory("listing_image_directory", "media/com_judirectory/images/listing/", true); ?>";
	function jSelectListing_related() {
		var $ul = jQuery(".related-listing-list").find(".related-listings");
		var $listing = $ul.find("li#listing-" + arguments[0]);
		if (!$listing.length && arguments[0] != <?php echo (int)$this->item->id; ?>) {
			var $li = '<li id="listing-' + arguments[0] + '">';
			$li += '<div class="listing-inner">';
			var image_src = arguments[2] ? listing_image_url + arguments[2] : default_image;
			if(image_src){
				$li += '<img class="image" src="' + image_src + '" title="' + arguments[1] + '" width="<?php echo $this->params->get('listing_image_width', 150)?>px" height="<?php echo $this->params->get('listing_image_height', 150); ?>px" />';
			}
			var href = 'index.php?option=com_judirectory&task=listing.edit&id=' + arguments[0];
			$li += '<a class="rel-listing-title" target="_blank" href="' + href + '">' + arguments[1] + '</a>';
			$li += '<a class="remove-rel-listing" href="#" title="<?php echo JText::_('COM_JUDIRECTORY_REMOVE_LISTING'); ?>" ><?php echo JText::_('COM_JUDIRECTORY_REMOVE_LISTING'); ?></a>';
			$li += '<input type="hidden" name="related_listings[]" value="' + arguments[0] + '" />';
			$li += '</div>';
			$li += '</li>';
			$ul.append($li);
		}
		
	}

	jQuery(document).ready(function ($) {
		var $ul = jQuery(".related-listing-list").find(".related-listings");
		$ul.on("click", ".remove-rel-listing", function () {
			$(this).parent().parent().remove();
		});

		$(".related-listing-list > ul").dragsort({ dragSelector: "li", dragEnd: saveOrder, placeHolderTemplate: "<li class='placeHolder'><div></div></li>", dragSelectorExclude: "input,a" });
		function saveOrder() {
			return;
		}
	});
</script>

<fieldset class="adminform">
	<div id="related-listing-list" class="related-listing-list">
		<ul class="related-listings">
			<?php
			
			if ($this->relatedListings)
			{
				foreach ($this->relatedListings AS $listing)
				{
					?>
					<li id="listing-<?php echo $listing->id; ?>" >
						<div class="listing-inner">
							<?php if($listing->image_src){ ?>
							<img class="image" src="<?php echo $listing->image_src; ?>" title="<?php echo $listing->title; ?>" style="max-width: 100px; max-height: 100px" />
							<?php } ?>
							<a class="rel-listing-title" target="_blank" href="index.php?option=com_judirectory&task=listing.edit&id=<?php echo $listing->id; ?>"><?php echo $listing->title; ?></a>
							<a class="remove-rel-listing" href="#" title="<?php echo JText::_('COM_JUDIRECTORY_REMOVE_LISTING'); ?>"><?php echo JText::_('COM_JUDIRECTORY_REMOVE_LISTING'); ?></a>
							<input type="hidden" name="related_listings[]" value="<?php echo $listing->id; ?>" />
						</div>
					</li>
			<?php
				}
			}
			$link = 'index.php?option=com_judirectory&amp;view=listings&amp;layout=modal&amp;tmpl=component&amp;function=jSelectListing_related';
			?>
		</ul>

		<div class="button2-left">
			<div class="blank">
				<a class="modal btn btn-mini" title="<?php echo JText::_('COM_JUDIRECTORY_ADD_LISTING'); ?>" href="<?php echo $link . '&amp;' . JSession::getFormToken(); ?>=1" rel="{handler: 'iframe', size: {x: 800, y: 450}}">
					<i class="icon-new"></i> <?php echo JText::_('COM_JUDIRECTORY_ADD_LISTING'); ?>
				</a>
			</div>
		</div>
	</div>
</fieldset>