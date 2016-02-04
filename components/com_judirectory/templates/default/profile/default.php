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

JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'criteria.cancel' || document.formvalidator.isValid(document.id('judirectory-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('judirectory-form'));
		}
	}
</script>

<div id="judir-container" class="jubootstrap component judir-container view-profile">
	<form
		action="#"
		method="post" name="adminForm" id="judirectory-form"
		class="form-validate form-horizontal" enctype="multipart/form-data">
		<fieldset>
			<legend><?php echo JText::_('COM_JUDIRECTORY_EDIT_PROFILE'); ?></legend>
			<?php foreach ($this->form->getFieldset('details') AS $key => $field)
			{
				// Ignore notes field
				if ($field->id == 'jform_notes')
				{
					continue;
				}

				$canShow = false;
				?>
				<div class="form-group">
					<?php if ($field->id == 'jform_avatar')
					{
						if ($this->params->get('avatar_source', 'juavatar') == 'juavatar')
						{
							$canShow = true;
						}
					}
					else
					{
						if ($this->params->get('edit_account_details', 1) == 1)
						{
							$canShow = true;
						}
						else
						{
							$haystack = array('jform_email', 'jform_password', 'jform_password2');
							if (!in_array($field->id, $haystack))
							{
								$canShow = true;
							}
						}
					}

					if ($canShow)
					{
						echo '<div class="control-label col-sm-2">' . $field->label . '</div>';
						echo ' <div class="col-sm-10">' . $field->input . '</div>';
					}
					?>
				</div>
			<?php
			} ?>
			<div class="form-group">
				<div class="col-sm-10">
					<button type="button" onclick="Joomla.submitbutton('profile.save')"
					   class="btn btn-default btn-primary"> <?php echo JText::_('COM_JUDIRECTORY_SUBMIT'); ?></button>
					<button onclick="Joomla.submitbutton('profile.cancel')"
					   class="btn btn-default"> <?php echo JText::_('COM_JUDIRECTORY_CANCEL'); ?></button>
				</div>
			</div>
		</fieldset>

		<div>
			<input type="hidden" name="user_id" value="<?php echo $this->item->id ?>"/>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>