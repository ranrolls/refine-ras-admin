<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="row " >
<div class="col-xs-12 ">
<div class="bg_white" style="border:1px solid #dddddd;  padding-bottom:20px">
	<div class="col-sm-5 col-sm-offset-4  pull-left" >
  <div style="height:30px;"></div>
<div class="profile<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<div class="page-header">
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
</div>
<?php endif; ?>



<?php echo $this->loadTemplate('params'); ?>
<?php echo $this->loadTemplate('core'); ?>
<?php /*?><?php echo $this->loadTemplate('custom'); ?><?php */?>


<?php if (JFactory::getUser()->id == $this->data->id) : ?>
<ul class="btn-toolbar pull-right">
	<li class="btn-group">
		<a class="btn blue_btn  " style="color:#fff;"  href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id);?>">
			  <?php echo JText::_('COM_USERS_EDIT_PROFILE'); ?></a>
	</li>
</ul>
<?php endif; ?>

</div></div>

<div class="login-right col-sm-5 pull-left"> <img  src="images/page/login_right.png" class="sp-default-logo"> </div>

   <div class="clearfix"></div>
   </div>    
   </div>
</div>