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

<div class="fandb<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	
<?php

$db = JFactory::getDBO();

  $userQuery = "SELECT * FROM ras_fandbstartup_fb order by id asc";
		
			
     $db->setQuery($userQuery);
       $userData = $db->loadObjectList();
   

?>

 

<div class="fieldset">
			
			<ul class="form-list f-b-start">

			<?php $i = 1;
                                foreach($userData as $fulldata){  ?>
                            
	                    <li class="fields">
					<div class="field">
                  <div class="col-xs-1 br_left_line">  <?=$i;?></div>
					 <div class="col-xs-8 br_left_line">                    
                    <h3 class="blue_text" ><?php echo $fulldata->title;?></h3>
					</div>
				
			 	 <div class="col-xs-3">    
			<div class="buttons-set">
			<a href="/images/fnb/<?php echo $fulldata->filetype;?>" download>
<button class=" btn btn-primary btn-block" title="Download" name="sub_btn" type="button" >Download</button></a>
 
</div>
</div>
			<?php  $i++; } ?>	
             
		 </li> 
				   <div class="clearfix">  </div>	 
			</ul>

		</div>
 