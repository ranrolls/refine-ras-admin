<?php
/**
* @version		$Id:edit.php 1 2015-06-04 06:35:13Z  $
* @copyright	Copyright (C) 2015, . All rights reserved.
* @license 		
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Set toolbar items for the page
$edit		= JFactory::getApplication()->input->get('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title(   JText::_( 'Fb' ).': <small><small>[ ' . $text.' ]</small></small>' );
JToolBarHelper::apply('fb.apply');
JToolBarHelper::save('fb.save');
if (!$edit) {
	JToolBarHelper::cancel('fb.cancel');
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'fb.cancel', 'Close' );
}
?>

<script language="javascript" type="text/javascript">


Joomla.submitbutton = function(task)
{
	if (task == 'fb.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}

</script>

	 	<form method="post" action="<?php echo JRoute::_('index.php?option=com_fb&layout=edit&id='.(int) $this->item->id);  ?>" id="adminForm" name="adminForm">
	 	<div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-60  <?php endif; ?>span8 form-horizontal fltlft">
		  <fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
		
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('title'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('title');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('asset_id'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('asset_id');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('filetype'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('filetype');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('created_date'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('created_date');  ?>
					</div>
				</div>		
					
		
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('description'); ?>
					</div>
				<?php if(version_compare(JVERSION,'3.0','lt')): ?>
				<div class="clr"></div>
				<?php  endif; ?>						
					
					<div class="controls">	
						<?php echo $this->form->getInput('description');  ?>
					</div>
				</div>		
					
		
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('state'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('state');  ?>
					</div>
				</div>		
			
						
          </fieldset>                      
        </div>
        <div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-30  <?php endif; ?>span2 fltrgt">
			        

        </div>                   
		<input type="hidden" name="option" value="com_fb" />
	    <input type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="fb" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>