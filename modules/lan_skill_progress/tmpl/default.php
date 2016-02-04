<?php 
/*------------------------------------------------------------------------
# Module By http://www.themelan.com
# ------------------------------------------------------------------------
# Author    ThemeLan by http://www.themelan.com
# Copyright (C) 2013 - 2014 http://www.themelan.com All Rights Reserved.
# @license - GNU/GPL V2 for PHP files. CSS / JS are Copyrighted Commercial
# Websites: http://www.themelan.com
-------------------------------------------------------------------------*/
defined('_JEXEC') or die;
?>

<div id="lan_skill" class="skill <?php echo $moduleclass_sfx;?>">
	<div class="skill_part">
		<div class="lan_skill" id="myStat1" data-dimension="<?php echo $params->get('data_dimension1')?>" data-text="<?php echo $params->get('data_percent1')?>%" data-info="" data-width="<?php echo $params->get('data_width1')?>" data-percent="<?php echo $params->get('data_percent1')?>" data-fgcolor="<?php echo $params->get('data_fgcolor1')?>" data-bgcolor="<?php echo $params->get('data_bgcolor1')?>">
		</div>
		<p><?php echo $params->get('title1')?></p>
	</div>
	
	<div class="skill_part">
		<div class="lan_skill" id="myStat2" data-dimension="<?php echo $params->get('data_dimension2')?>" data-text="<?php echo $params->get('data_percent2')?>%" data-info="" data-width="<?php echo $params->get('data_width2')?>" data-percent="<?php echo $params->get('data_percent2')?>" data-fgcolor="<?php echo $params->get('data_fgcolor2')?>" data-bgcolor="<?php echo $params->get('data_bgcolor2')?>">
		</div>
		<p><?php echo $params->get('title2')?></p>
	</div>
	
	<div class="skill_part">
		<div class="lan_skill" id="myStat3" data-dimension="<?php echo $params->get('data_dimension3')?>" data-text="<?php echo $params->get('data_percent3')?>%" data-info="" data-width="<?php echo $params->get('data_width3')?>" data-percent="<?php echo $params->get('data_percent3')?>" data-fgcolor="<?php echo $params->get('data_fgcolor3')?>" data-bgcolor="<?php echo $params->get('data_bgcolor3')?>">
		</div>
		<p><?php echo $params->get('title3')?></p>
	</div>
	
	<div class="skill_part">
		<div class="lan_skill" id="myStat4" data-dimension="<?php echo $params->get('data_dimension4')?>" data-text="<?php echo $params->get('data_percent4')?>%" data-info="" data-width="<?php echo $params->get('data_width4')?>" data-percent="<?php echo $params->get('data_percent4')?>" data-fgcolor="<?php echo $params->get('data_fgcolor4')?>" data-bgcolor="<?php echo $params->get('data_bgcolor4')?>">
		</div>
		<p><?php echo $params->get('title4')?></p>
	</div>

</div><!--/skill-->


