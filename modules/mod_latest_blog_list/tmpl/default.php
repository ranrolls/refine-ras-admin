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

<div class="latest_blog_list<?php echo $params->get( 'moduleclass_sfx' ) ?> latest_news">

<h2 class="blue_text heading_home" style="border-bottom: 1px solid hsl(0, 0%, 91%);">Latest Blogs</h2>

    

   

   <?php



$db = JFactory::getDBO();

  $userQuery = "SELECT * FROM ras_k2_items  order by id desc limit 2"; 

     $db->setQuery($userQuery);

       $userData = $db->loadObjectList();

  //print_r($userData);



?>









<div class="fieldset">

			<ul class="form-list">



			<?php 

                                foreach($userData as $fulldata){ 
	//Read more link
		// $link = K2HelperRoute::getItemRoute($fulldata->id.':'.urlencode($fulldata->alias), $fulldata->catid.':'.urlencode($fulldata->category->alias));	
		// $lll =  $item->link = urldecode(JRoute::_($link));
		 
                                  ?>

                            

	                    <li class="fields">

					<div class="field">

                 

                    <div class="pull-left img_box">

					<? 

					$paramsArray = array();

					$paramsArray = json_decode($fulldata->images, true);

					$paramsArray['image_intro'];

					?>

                    <?php 

                 $imagePOST = md5("Image".$fulldata->id)."_L.jpg";
					
					
					if($imagePOST != '' ){ ?>

                  <a href="blog/<?=$fulldata->alias?>" class="blue_text"> <img src="<?="media/k2/items/cache/".md5("Image".$fulldata->id)."_L.jpg"?>" height="70" width="70" > </a>

                    <? } else { ?> <a href="blog/<?=$fulldata->alias?>" class="blue_text"><img src="images/default.jpg" height="70" width="70" ></a><? } ?> 

                    </div>

                    <div class="pull-left width_80"> 

					<span  class="latest_heading"><a href="blog/<?=$fulldata->alias?>" class="blue_text" ><?php echo $fulldata->title;?></a></span>

                  <p><?php echo substr($fulldata->introtext,0,50);?> <p>

						</div>  



					</div>

                      <div class="clearfix"></div>

<!-- <div class="buttons-set"></div> -->

 </li> 

			<?php } ?>	



<a href="blog" class="pull-right blue_text ">View all posts >></a>	 

			</ul> 



</div>

</div>