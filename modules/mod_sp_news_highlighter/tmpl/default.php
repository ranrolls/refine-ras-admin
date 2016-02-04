<?php
/*
# SP News Highlighter Module by JoomShaper.com
# --------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2013 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$title	='';
$date	='';
?>
<script type="text/javascript">
	window.addEvent('domready',function(){
		var highlighter_sp1_id<?php echo $uniqid ?> = new sp_highlighter($('sp-nh-items<?php echo $uniqid ?>'), {
			size: {width: <?php echo $slider_width; ?>, height: <?php echo $height; ?>},
			fxOptions: {duration:  <?php echo $fxduration; ?>, transition: Fx.Transitions.<?php echo $transition; ?>},
			transition: <?php echo "'" .$effects. "'"; ?>
		});

		<?php if ($showbutton) {?>
			highlighter_sp1_id<?php echo $uniqid ?>.addPlayerControls('previous', [$('sp-nh-prev<?php echo $uniqid;?>')]);
			highlighter_sp1_id<?php echo $uniqid ?>.addPlayerControls('next', [$('sp-nh-next<?php echo $uniqid;?>')]);
		<?php } ?>	

		<?php if($params->get("autoPlay", 1) == 1) { ?>
			highlighter_sp1_id<?php echo $uniqid ?>.play(<?php echo $params->get('interval', 5000); ?>);
		<?php } ?>		
	});
</script>
<div id="sp-nh<?php echo $uniqid ?>" class="sp_news_higlighter">
	<div class="sp-nh-buttons" style="width:<?php echo $button_width; ?>px">
		<span class="sp-nh-text"><?php echo $title_text; ?></span>
		<?php if ($showbutton) { ?>
			<div id="sp-nh-prev<?php echo $uniqid;?>" class="sp-nh-prev"></div>
			<div id="sp-nh-next<?php echo $uniqid;?>" class="sp-nh-next"></div>
		<?php } ?>
	</div>	
	<div id="sp-nh-items<?php echo $uniqid ?>" class="sp-nh-item">
		<?php foreach ($list as $item): ?>
			<div class="sp-nh-item">
				<?php
					if($showtitle) 
						$title  = '<span class="sp-nh-title">' . modNewsHighlighterHelper::getText($item->title,$titlelimit,$titleas) . '</span>';

					if($date_format !='disabled') 
						$date = ' - <span class="sp-nh-date">' . JHTML::_('date', $item->date, JText::_($date_format)) . '</span>';	
					
					$text = $title.$date;
					
					$newstext = $linkable ? '<a class="sp-nh-link" href="' .$item->link. '">' . $text . '</a>' : $text;
					
					echo $newstext;
				?>	
			</div>
		<?php endforeach; ?>
	</div>
	<div style="clear:both"></div>	
</div>