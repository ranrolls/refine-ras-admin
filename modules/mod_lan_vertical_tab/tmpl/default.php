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
<div class="module<?php echo $moduleclass_sfx;?>">
	<div class="lan_vertical_timeline">
		<div class="lan_vertical_left">
			<ul class="nav nav-tabs" role="tablist" id="myTab">
				<li role="presentation" class="active">
					<a href="#home" aria-controls="home" role="tab" data-toggle="tab">
						<ul class="cbp_tmtimeline">
							<li>
								<div class="cbp_tmicon"></div>
								<div class="cbp_tmlabel">
									<h3><?php echo $params->get('vt_title1')?></h3>
									<p><?php echo $params->get('vt_subtitle1')?></p>
								</div>
							</li>
						</ul>
					
					</a>
				</li>
				<li role="presentation">
					<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
						<ul class="cbp_tmtimeline">
							<li>
								<div class="cbp_tmicon"></div>
								<div class="cbp_tmlabel">
									<h3><?php echo $params->get('vt_title2')?></h3>
									<p><?php echo $params->get('vt_subtitle2')?></p>
								</div>
							</li>
						</ul>
					</a>
				</li>
				<li role="presentation">
					<a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
						<ul class="cbp_tmtimeline">
							<li>
								<div class="cbp_tmicon"></div>
								<div class="cbp_tmlabel">
									<h3><?php echo $params->get('vt_title3')?></h3>
									<p><?php echo $params->get('vt_subtitle3')?></p>
								</div>
							</li>
						</ul>
					</a>
				</li>
				<li role="presentation">
					<a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
						<ul class="cbp_tmtimeline">
							<li>
								<div class="cbp_tmicon"></div>
								<div class="cbp_tmlabel">
									<h3><?php echo $params->get('vt_title4')?></h3>
									<p><?php echo $params->get('vt_subtitle4')?></p>
								</div>
							</li>
						</ul>
					</a>
				</li>
			</ul>
		</div>

		<div class="lan_vertical_right tab-content">
			<div role="tabpanel" class="tab-pane active" id="home">
				<div class="tab_img">
				<img src="<?php if($params->get('vt_img1')!=null)
					{echo $params->get('vt_img1');}
						else 
					{ echo JURI::root(true).'/modules/mod_lan_vertical_tab/frontend/images/l4.jpg';}?>" alt=""/>
		
				</div>
				<div class="tab_contain">
					<h3><?php echo $params->get('vt_dstitle1')?></h3>
					<p><?php echo $params->get('vt_disc1')?></p>
					<a class="btn btn-primary" href="<?php echo $params->get('vt_redmore1')?>">Read More</a>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="profile">
				<div class="tab_img">
				<img src="<?php if($params->get('vt_img2')!=null)
					{echo $params->get('vt_img2');}
						else 
					{ echo JURI::root(true).'/modules/mod_lan_vertical_tab/frontend/images/l10.jpg';}?>" alt=""/>
		
				</div>
				<div class="tab_contain">
					<h3><?php echo $params->get('vt_dstitle2')?></h3>
					<p><?php echo $params->get('vt_disc2')?></p>
					<a class="btn btn-primary" href="<?php echo $params->get('vt_redmore2')?>">Read More</a>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="messages">
				<div class="tab_img">
				<img src="<?php if($params->get('vt_img3')!=null)
					{echo $params->get('vt_img3');}
						else 
					{ echo JURI::root(true).'/modules/mod_lan_vertical_tab/frontend/images/l20.jpg';}?>" alt=""/>
		
				</div>
				<div class="tab_contain">
					<h3><?php echo $params->get('vt_dstitle3')?></h3>
					<p><?php echo $params->get('vt_disc3')?></p>
					<a class="btn btn-primary" href="<?php echo $params->get('vt_redmore3')?>">Read More</a>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="settings">
				<div class="tab_img">
				<img src="<?php if($params->get('vt_img4')!=null)
					{echo $params->get('vt_img4');}
						else 
					{ echo JURI::root(true).'/modules/mod_lan_vertical_tab/frontend/images/l16.jpg';}?>" alt=""/>
		
				</div>
				<div class="tab_contain">
					<h3><?php echo $params->get('vt_dstitle4')?></h3>
					<p><?php echo $params->get('vt_disc4')?></p>
					<a class="btn btn-primary" href="<?php echo $params->get('vt_redmore4')?>">Read More</a>
				</div>
			</div>
		</div>

		<script>
			jQuery('#myTab a').click(function (e) {
			  e.preventDefault()
			  jQuery(this).tab('show');
			  jQuery( ".tab_img" ).addClass( "animated slideUp" );
			});
		</script>
	</div>
	<div style="clear:both;"></div>
</div>