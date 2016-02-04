<?php
/**
* @title		Simple image gallery module
* @website		http://www.joomshaper.com
* @copyright	Copyright (C) 2010 - 2013 JoomShaper.com. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="sp-sig<?php echo $uniqid ?>" class="sp-sig <?php echo $params->get('moduleclass_sfx'); ?>">
	<?php foreach($list as $item) { ?>
		<a href="<?php echo $item['image'] ?>" rel="lightbox-atomium" title="<?php echo $item['title'] ?>">
			<div class="sp_img_wrapper">
				<img class="sp_simple_gallery" src="<?php echo $item['thumb'] ?>" alt="<?php echo $item['title'] ?>" />
				<div class="img-overlay">
					<div class="overlay_container">
						<div class="overlay_content">
							<i class="icon-picture"></i>
						</div>	
					</div>
				</div>
			</div>
		</a>
	<?php } ?>
</div>