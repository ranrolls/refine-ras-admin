<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<div class="row " >
<div class="col-xs-12 ">
<div class="bg_white" style="border:1px solid #dddddd; padding-bottom:20px">
	
	<div class="col-sm-5 col-sm-offset-4  pull-left" >
	 <div style="height:30px;"></div>
		<div class="remind<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<h1>
					<?php echo $this->escape($this->params->get('page_heading')); ?>
				</h1>
			<?php endif; ?>

			<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=remind.remind'); ?>" method="post" class="form-validate">
				<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
				<p class="bgcolor"><?php echo JText::_($fieldset->label); ?></p>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<div class="form-group">
						<?php echo $field->label; ?>
						<div class="group-control">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<?php endforeach; ?>
				<div class="form-group">
					<button type="submit" class="btn btn-primary validate blue_btn"><?php echo JText::_('JSUBMIT'); ?></button>
				</div>
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	
	
	</div>

	<div class="login-right col-sm-5 pull-left"> <img  src="images/page/login_right.png" class="sp-default-logo"> </div>
	
   <div class="clearfix"></div>
   </div>    
   </div>
</div>


