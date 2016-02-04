<?php 
/*------------------------------------------------------------------------
# Module By http://www.themelan.com
# ------------------------------------------------------------------------
# Author    ThemeLan by http://www.themelan.com
# Copyright (C) 2013 - 2014 http://www.themelan.com All Rights Reserved.
# @license - GNU/GPL V2 for PHP files. CSS / JS are Copyrighted Commercial
# Websites: http://www.themelan.com
-------------------------------------------------------------------------*/
?>
<div id="lan_ourteam<?php echo $uniqid ?>" class="module<?php echo $moduleclass_sfx;?>">
		<div class="boxgrid caption">
		
			<img src="<?php if($params->get('about_1')!=null)
				{echo $params->get('about_1');

}
					else 
				{ echo JURI::root(true).'/modules/mod_lan_our_team/frontend/images/about_1.jpg';}?>" alt=""/>
			<div class="cover boxcaption" >
				<div class="boxcontain">
					<h3 class="red_text"><?php echo $params->get('tm_name1')?></h3>
					<?php /*?><h5><?php echo $params->get('tm_designation1')?></h5>
					<div class="boxicon">
						<a href="<?php echo $params->get('tm_link11')?>"><i class="fa fa-facebook"></i></a>
						<a href="<?php echo $params->get('tm_link12')?>"><i class="fa fa-twitter"></i></a>
						<a href="<?php echo $params->get('tm_link13')?>"><i class="fa fa-google-plus"></i></a>
						<a href="<?php echo $params->get('tm_link14')?>"><i class="fa fa-vine"></i></a>
					</div><?php */?>
					<p class="black_text"><?php echo $params->get('tm_disc1')?></p>
				</div>
			</div>
		</div>

		<div class="boxgrid caption">
			<img src="<?php if($params->get('about_2')!=null)
				{echo $params->get('about_2');}
					else 
				{ echo JURI::root(true).'/modules/mod_lan_our_team/frontend/images/about_2.jpg';}?>" alt=""/>
			<div class="cover boxcaption">
				<div class="boxcontain">
					<h3 class="red_text"><?php echo $params->get('tm_name2')?></h3>
					<?php /*?><h5><?php echo $params->get('tm_designation2')?></h5>
					<div class="boxicon">
						<a href="<?php echo $params->get('tm_link21')?>"><i class="fa fa-facebook"></i></a>
						<a href="<?php echo $params->get('tm_link22')?>"><i class="fa fa-twitter"></i></a>
						<a href="<?php echo $params->get('tm_link23')?>"><i class="fa fa-google-plus"></i></a>
						<a href="<?php echo $params->get('tm_link24')?>"><i class="fa fa-vine"></i></a>
					</div><?php */?>
					<p class="black_text"><?php echo $params->get('tm_disc2')?></p>
				</div>
			</div>
		</div>

		<div class="boxgrid caption">
			<img src="<?php if($params->get('about_3')!=null)
				{echo $params->get('about_3');}
					else 
				{ echo JURI::root(true).'/modules/mod_lan_our_team/frontend/images/about_3.jpg';}?>" alt=""/>
			<div class="cover boxcaption">
				<div class="boxcontain">
					<h3 class="red_text"><?php echo $params->get('tm_name3')?></h3>
					<?php /*?><h5><?php echo $params->get('tm_designation3')?></h5>
					<div class="boxicon">
						<a href="<?php echo $params->get('tm_link31')?>"><i class="fa fa-facebook"></i></a>
						<a href="<?php echo $params->get('tm_link32')?>"><i class="fa fa-twitter"></i></a>
						<a href="<?php echo $params->get('tm_link33')?>"><i class="fa fa-google-plus"></i></a>
						<a href="<?php echo $params->get('tm_link34')?>"><i class="fa fa-vine"></i></a>
					</div><?php */?>
					<p class="black_text"><?php echo $params->get('tm_disc3')?></p>
				</div>
			</div>
		</div>

		<div class="boxgrid caption">
			<img src="<?php if($params->get('about_4')!=null) 
				{echo $params->get('about_4');}
					else 
				{ echo JURI::root(true).'/modules/mod_lan_our_team/frontend/images/about_4.jpg';}?>" alt=""/>
			<div class="cover boxcaption">
				<div class="boxcontain">
					<h3 class="red_text"><?php echo $params->get('tm_name4')?></h3>
				<?php /*?>	<h5><?php echo $params->get('tm_designation4')?></h5>
					<div class="boxicon">
						<a href="<?php echo $params->get('tm_link41')?>"><i class="fa fa-facebook"></i></a>
						<a href="<?php echo $params->get('tm_link42')?>"><i class="fa fa-twitter"></i></a>
						<a href="<?php echo $params->get('tm_link43')?>"><i class="fa fa-google-plus"></i></a>
						<a href="<?php echo $params->get('tm_link44')?>"><i class="fa fa-vine"></i></a>
					</div><?php */?>
					<p class="black_text"><?php echo $params->get('tm_disc4')?></p>
				</div>
			</div>
		</div>
</div>