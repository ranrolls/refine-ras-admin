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

if ($this->params->get('image_display_mode', 'fancybox') == 'fancybox')
{
	?>
	<ul class="images clearfix">
		<?php
		foreach ($images AS $key => $image)
		{
			if ($key == 0)
			{
				$class = " first";
			}
			elseif ($key == count($images) - 1)
			{
				$class = " last";
			}
			else
			{
				$class = "";
			}

			$description = '';
			if ($image->title)
			{
				$description .= "<h4 class='img-title'>" . htmlspecialchars($image->title, ENT_QUOTES) . "</h4>";
			}

			if ($image->description)
			{
				$description .= "<div class='img-description'>" . htmlspecialchars($image->description, ENT_QUOTES) . '</div>';
			}
			?>
			<li class="image<?php echo $class; ?>">
				<a href="<?php echo JUri::root(true) . '/' . JUDirectoryFrontHelper::getDirectory('listing_big_image_directory', 'media/com_judirectory/images/gallery/big/', true) . $this->listing_id . '/' . $image->file_name; ?>"
				   class="fancybox" rel="gallery">
					<img
						src="<?php echo JUri::root(true) . '/' . JUDirectoryFrontHelper::getDirectory('listing_small_image_directory', 'media/com_judirectory/images/gallery/small/', true) . $this->listing_id . '/' . $image->file_name; ?>"
						width="<?php echo $params->get('listing_small_image_width', 100); ?>"
						height="<?php echo $params->get('listing_small_image_height', 100); ?>"
						alt="<?php echo htmlspecialchars($image->title, ENT_QUOTES); ?>"/>
				</a>

				<div class="title" style="display: none;"><?php echo $description; ?></div>
			</li>
		<?php
		} ?>
	</ul>
<?php
}
else
{ ?>
	<div class="camera_wrap camera_azure_skin camera_slideshow">
		<?php
		foreach ($images AS $key => $image)
		{
			$description = '';
			if ($image->title)
			{
				$description .= "<h4 class='img-title'>" . htmlspecialchars($image->title, ENT_QUOTES) . "</h4>";
			}

			if ($image->description)
			{
				$description .= "<div class='img-description'>" . htmlspecialchars($image->description, ENT_QUOTES) . '</div>';
			}
			?>
			<div
				data-thumb="<?php echo JUri::root(true) . '/' . JUDirectoryFrontHelper::getDirectory('listing_small_image_directory', 'media/com_judirectory/images/gallery/small/', true) . $this->listing_id . '/' . $image->file_name; ?>"
				data-src="<?php echo JUri::root(true) . '/' . JUDirectoryFrontHelper::getDirectory('listing_big_image_directory', 'media/com_judirectory/images/gallery/big/', true) . $this->listing_id . '/' . $image->file_name; ?>">
				<?php
				if ($description)
				{
					?>
					<div class="camera_caption fadeFromBottom">
						<?php echo $description; ?>
					</div>
				<?php
				} ?>
			</div>
		<?php
		} ?>
	</div>
<?php
} ?>