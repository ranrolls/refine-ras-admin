<?php 

/**

 * File name: $HeadURL: svn://tools.janguo.de/jacc/trunk/admin/templates/modules/tmpl/default.php $

 * Revision: $Revision: 147 $

 * Last modified: $Date: 2013-10-06 10:58:34 +0200 (So, 06. Okt 2013) $

 * Last modified by: $Author: michel $

 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.

 * @license 

 */

defined('_JEXEC') or die('Restricted access'); 

?>

<div class="latest_news<?php echo $params->get( 'moduleclass_sfx' ) ?>">

	<h2 class="blue_text heading_home" style=" border-bottom: 1px solid hsl(0, 0%, 91%);">Latest News</h2>

   <?php

$db = JFactory::getDBO();

 $userQuery = "SELECT * FROM ras_categories as ab right join ras_content as ac on ab.id = ac.catid where ac.catid = '11' and ac.state = '1'  order by ac.created  desc limit 2";
//$userQuery = "SELECT * FROM  ras_content where catid = '11' order by id desc limit 2";

     $db->setQuery($userQuery);

       $userData = $db->loadObjectList();

  //print_r($userData);



?>



<div class="fieldset">       

            <ul class="form-list">



			<?php 

                                foreach($userData as $fulldata){  ?>

                            

	                    <li class="fields">

					<div class="field">

                  

                    <div style="float:left;">

                   <? 

				   $paramsArray = array();

				     $paramsArray = json_decode($fulldata->images, true);

$paramsArray['image_intro'];

  ?>

                  </div>  

             

				   

				   <?php $var = $fulldata->created;

				   

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

<div class="news_time pull-left">

<span class="pull-left date_1"><? echo $a['0'];?></span><div class="clearfix"></div>

<span class="pull-left date_2 "><? echo $month;  ?></span><div class="clearfix"></div>

<span class="pull-left date_3 "><? echo $aaa['0']; ?></span>

</div>

                  <div class="pull-left img_box">  

                    <?php if($paramsArray['image_intro']!= '' ){ ?>

                    
 <a href="news/<?=$fulldata->alias?>" class="blue_text">   
                    <img src="<?=$paramsArray['image_intro']?>" height="70" width="70" ></a> 

                    <? } else { ?> 
 <a href="nnews/<?=$fulldata->alias?>" class="blue_text">   
<img src="images/news_icon.jpg" height="64" width="60" > </a><? } ?> 

                    </div>

                    

                    <div class="pull-left width_80">

					<span class="latest_heading">
 <a href="news/<?=$fulldata->alias?>" class="blue_text">   
<?php echo substr($fulldata->title,0,40);?>.. </a></span>

	<span><?=substr($fulldata->introtext,0,60);?></span><span class="mob_hide_768"><?=substr($fulldata->introtext,60,60);?></span>
						</div>  

                      

					</div>

					 

				

			 

	<div class="clearfix"></div>		<!--<div class="buttons-set"></div> -->

 </li> 

			<?php } ?>	

            	 

	

				<a href="news" class="pull-right blue_text ">View all news >></a>	 

                			</ul> 

</div>

<div class="clearfix"></div>

</div>