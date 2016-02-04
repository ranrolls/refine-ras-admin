<?php
/**
 * ------------------------------------------------------------------------
 * JUDirectory for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 *
 * @copyright      Copyright (C) 2010-2015 JoomUltra Co., Ltd. All Rights Reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @author         JoomUltra Co., Ltd
 * @website        http://www.joomultra.com
 * @----------------------------------------------------------------------@
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'fieldgroup.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div class="jubootstrap">

	<div id="iframe-help"></div>

	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		<div class="width-60 fltlft">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_JUDIRECTORY_EDIT_FIELD_GROUP'); ?></legend>
				<ul class="adminformlist">
					<?php foreach ($this->form->getFieldset('details') AS $key => $field): ?>
						<?php if ($field->name == "jform[access]")
						{
							?>
							<li>
								<?php echo $field->label; ?>
								<?php echo $field->input; ?>
							</li>
							<?php if ($this->canDo->get('core.admin')): ?>
							<li>
								<span class="faux-label"><?php echo JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ?></span>

								<div class="button2-left">
									<div class="blank">
										<button type="button" onclick="document.location.href='#access-rules';">
											<?php echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ?></button>
									</div>
								</div>
							</li>
						<?php endif; ?>
						<?php
						}
						else
						{
							?>
							<li>
								<?php echo $field->label; ?>
								<?php echo $field->input; ?>
							</li>
						<?php
						} ?>
					<?php endforeach; ?>
				</ul>
			</fieldset>
		</div>

		<div class="width-40 fltrt">
			<?php echo JHtml::_('sliders.start', 'fieldgroup-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_PUBLISHING'), 'publishing'); ?>
			<fieldset class="adminform">
				<ul class="adminformlist">
					<?php foreach ($this->form->getFieldset('publishing') AS $key => $field): ?>
						<li>
							<?php if ($key == "jform_modified" || $key == "jform_modified_by")
							{
								if ($this->item->modified_by)
								{
									echo $field->label;
									echo $field->input;
								}
							}
							else
							{
								echo $field->label;
								echo $field->input;
							}?>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>

			<?php echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_LISTING_METADATA'), 'listing-metadata'); ?>
			<fieldset class="adminform">
				<ul class="adminformlist">
					<?php foreach ($this->form->getFieldset('listing_metadata') AS $field): ?>
						<li>
							<?php echo $field->label; ?>
							<?php echo $field->input; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>
			<?php echo JHtml::_('sliders.end'); ?>
		</div>

		<div class="clr"></div>

		<?php if ($this->canDo->get('core.admin')): ?>
			<div class="width-100 fltlft">
				<?php echo JHtml::_('sliders.start', 'fieldgroup-permissions-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
				<?php echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_PERMISSIONS'), 'permissions'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<?php foreach ($this->form->getFieldset('permissions') AS $field): ?>
							<li>
								<?php echo $field->label; ?>
								<?php echo $field->input; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</fieldset>
				<?php echo JHtml::_('sliders.end'); ?>
			</div>
		<?php endif; ?>

		<div>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>