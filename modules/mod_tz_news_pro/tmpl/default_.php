<?php

/*------------------------------------------------------------------------

# MOD_TZ_NEW_PRO Extension

# ------------------------------------------------------------------------

# author    tuyennv

# copyright Copyright (C) 2013 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . 'modules/mod_tz_news_pro/css/default.css');
?>
<div class="mod_tz_news">
    <ul class="tz_news">
        <?php if (isset($list) && !empty($list)) :
            foreach ($list as $item) :
                $media = $item->media; ?>
                <?php if (!$media or ($media != null  AND $media->type != 'quote' AND $media->type != 'link' AND $media->type != 'audio')): ?>
                <li class="tz_item_default">
                   
                    <?php 
					 $var = $item->created;
					$aaa = explode('-', $var);
if($aaa['1'] == '01'){  $month = 'Jan';}
elseif($aaa['1'] == '02'){  $month = 'feb';} 
elseif($aaa['1'] == '03'){  $month = 'Mar';}
elseif($aaa['1'] == '04'){  $month = 'Apr';} 
elseif($aaa['1'] == '05'){  $month = 'May';}
elseif($aaa['1'] == '06'){  $month = 'Jun';} 
elseif($aaa['1'] == '07'){  $month = 'July';}
elseif($aaa['1'] == '08'){  $month = 'Aug';}
elseif($aaa['1'] == '09'){  $month = 'Sep';} 
elseif($aaa['1'] == '10'){  $month = 'Oct';}
elseif($aaa['1'] == '11'){  $month = 'Nov';}
else{ $month = 'Dec';}
$aa = $aaa['2'];
$a =  explode(' ', $aa);?>

		
					
					
					
					
					
					<?php if ($image == 1 or$des == 1): ?>
                        <div class="row">
<div class="news_time pull-left " >
<span class="pull-left date_1"><?php echo $a['0']; ?></span><div class="clearfix"></div>
<span class="pull-left date_2"><?php echo $month;?></span><div class="clearfix"></div>
<span class="pull-left date_3"><?php echo $aaa['0'];?></span>
</div>		
                        
                            <?php if ($image == 1 AND $item->image != null) : ?>
                                <div class="col-xs-3 tz_image">
                                    <?php if ($media) :
                                        $title_image = $media->imagetitle;
                                    else :
                                        $title_image = $item->title;
                                    endif; ?>
                                    <a href="<?php echo $item->link; ?>">
                                        <img src="<?php echo $item->image; ?>"
                                             title="<?php echo $title_image; ?>"
                                             alt="<?php echo $title_image; ?>"/>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if ($des == 1) : ?>
                                <span class="col-xs-8 tz_description">
                        
                          <!-- paste code  -->         <?php if ($title == 1) : ?>
                        <h6 class="tz_title blue_text">
                            <a href="<?php echo $item->link; ?>" class="blue_text"  title="<?php echo $item->title; ?>">
                                <?php echo $item->title; ?>
                            </a>
                        </h6>
                    <?php endif; ?>
                    <!-- paste code  -->
                                
                                    <?php if ($limittext) :
                                        echo substr($item->intro, 3, $limittext);
                                    else :
                                        echo $item->intro;
                                    endif;?>
                                    <div class="clearfix"></div>
                                    <?php if ($readmore == 1) : ?>
                                        <span class="tz_readmore orange_text">
                                            <a href="<?php echo $item->link; ?>" class="orange_text">
                                                <?php echo JText::_('MOD_TZ_NEWS_READ_MORE') ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($date == 1 or $hits == 1 or $author_new == 1 or $cats_new == 1): ?>
                        <div class="dv2">
                            <?php if ($date == 1) : ?>
                                <span class="tz_date">
                                    <?php echo JText::sprintf('MOD_TZ_NEWS_DATE_ALL', JHtml::_('date', $item->created, JText::_('MOD_TZ_NEWS_DATE_FOMAT'))); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($hits == 1) : ?>
                                <span class="tz_hits">
                                    <?php echo JText::sprintf('MOD_TZ_NEWS_HIST_LIST', $item->hit) ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($author_new == 1): ?>
                                <span class="tz_author">
                                    <?php echo JText::sprintf('MOD_TZ_NEWS_AUTHOR', $item->author); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($cats_new == 1): ?>
                                <span class="tz_category">
                                    <?php echo JText::sprintf('MOD_TZ_NEWS_CATEGORY', $item->category); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </li>
            <?php endif; ?>
                <!--use tz -portfolio-->
                <?php if ($show_quote == 1 AND $media AND $media->type == 'quote'): ?>
                <?php require JModuleHelper::getLayoutPath('mod_tz_news_pro', $params->get('layout', 'default') . '_quote'); ?>
            <?php endif; ?>

                <?php if ($show_link == 1 AND $media AND $media->type == 'link'): ?>
                <?php require JModuleHelper::getLayoutPath('mod_tz_news_pro', $params->get('layout', 'default') . '_link'); ?>
            <?php endif; ?>

                <?php if ($show_audio == 1 AND $media AND $media->type == 'audio'): ?>
                <?php require JModuleHelper::getLayoutPath('mod_tz_news_pro', $params->get('layout', 'default') . '_audio'); ?>
            <?php endif; ?>

            <?php endforeach;  ?>


  <? $db = JFactory::getDbo();
   $userQuery12 = "SELECT * FROM ras_categories as ab right join ras_content as ac on ab.id = ac.catid where ab.id = '11'";
   $userQuery12 = mysql_query($userQuery12);
    //$userData = $db->loadObjectList();
		 $total_records12 = mysql_num_rows($userQuery12);
		 $limit = $params->get('limit');
		 $num_pages = ceil($total_records12/$limit);
		 if($_REQUEST['page'] != '' && $_REQUEST['page'] != '1' )  {  ?>      
		 
		 
		 
		 
       <!-- start pagination --> 
       
        <a style="border:1px solid; background:#FFF;color:#000;" href="news?page=1" >Start</a> 
       <a style="border:1px solid; background:#FFF;color:#000;" href="news?page=<?=$_REQUEST['page'] - 1;?>" > <? echo '<<'; ?> </a> 
  
        
       
          <?  }  
		for ($x =1; $x <= $num_pages; $x++) { ?>
       
  <a href="news?page=<?=$x;?>" <? if($_REQUEST['page'] == $x)  {?>style="border:1px solid;
background:#EB4947;color:#fff;" <? }  else {  ?> style="border: 1px solid rgb(204, 204, 204); background:#FFF;
color:#000;" <? }?> >&nbsp;&nbsp;&nbsp;<?=$x;?>&nbsp;&nbsp;&nbsp;</a>

&nbsp;
               <?   } if($_REQUEST['page'] != $num_pages)  { ?> 
              <a style="border:1px solid; background:#FFF;color:#000;" href="news?page=<?=$_REQUEST['page'] + 1;?>" > <? echo '>>'; ?> </a> 
               
               <a style="border:1px solid; background:#FFF;color:#000;" href="news?page=<?=$num_pages;?>" >End </a>    <? }  
			 ?>
             
             
<!--<style type="text/javascript">
.sp-module ul > li { clear:none !important; float:left !important;}
</style>    -->         

    <div class="pagination">

					<p class="counter pull-right">
				Page 1 of 5			</p>
				<ul class="pagination"><li class="active" style="display: inline;"><a style="padding:none;">1</a></li><li style="display: inline; list-style:none;"><a style="padding:2px 10px;" title="2" href="/articles/page-2" class="">2</a></li><li style="display: inline;"><a title="3" href="/articles/page-3" class="">3</a></li><li><a title="4" href="/articles/page-4" class="">4</a></li><li><a title="5" href="/articles/page-5" class="">5</a></li><li><a title="»" href="/articles/page-2" class="next">»</a></li><li><a title="End" href="/articles/page-5" class="">End</a></li></ul>	</div>
    
    


       <!-- End pagination -->
	   
	   kjipokpklplopokp

        <?php endif; ?>
    </ul>
</div>