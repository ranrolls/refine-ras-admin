<?php
    /*------------------------------------------------------------------------
    # mod_sp_countdown - Countdown module by JoomShaper.com
    # ------------------------------------------------------------------------
    # Author    JoomShaper http://www.joomshaper.com
    # Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
    # License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
    # Websites: http://www.joomshaper.com
    -------------------------------------------------------------------------*/
    // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="sp_countdown">
    <div class="sp_countdown_pre_text"><!-- Pre Text -->
		<?php echo $params->get('pre_text')?>
	</div>
    <div id="sp_countdown_cntdwn<?php echo $module->id; ?>" class="sp_countdown_container"><!-- Dynamically creates timer --></div><!-- Countdown Area -->
    <?php if(trim($params->get('post_text'))!=''): ?><!-- Post Text -->
		<div style="clear:both"></div>	
		<div class="sp_countdown_post_text">
			<?php echo $params->get('post_text')?>
		</div>
    <?php endif; ?>
    <?php if(trim($params->get('show_button'))==1): ?><!-- Button -->
        <div class="sp_countdown_button">
			<a class="button sp_countdown_button_link" href="<?php echo $params->get('button_link') ?>"><span><?php echo $params->get('button_text') ?></span></a>
		</div>
    <?php endif; ?>
	<div style="clear:both"></div>
</div>