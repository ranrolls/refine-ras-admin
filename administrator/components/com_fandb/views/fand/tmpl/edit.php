<?php
/*------------------------------------------------------------------------
# edit.php - fandb Component
# ------------------------------------------------------------------------
# author    Refine
# copyright Copyright (C) 2015. All Rights Reserved
# license   hello
# website   www.refine-interactive.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');

?>
<form action="<?php echo JRoute::_('index.php?option=com_fandb&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			<div class="adminformlist">
				<?php foreach($this->form->getFieldset('details') as $field){ ?>
					<div>
						<?php echo $field->label; echo $field->input;?>
					</div>
					<div class="clr"></div>
				<?php }; ?>
			</div>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="fand.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>