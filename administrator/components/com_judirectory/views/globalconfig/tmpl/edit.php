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
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tabstate');

$app = JFactory::getApplication();
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'globalconfig.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div class="jubootstrap">

	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&view=globalconfig&layout=edit'); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate judirectory-config form-vertical">
		<?php
		$fieldSets = $this->form->getFieldsets();
		if ($fieldSets)
		{
			if ($this->isJoomla3x)
			{
				echo JHtml::_('bootstrap.startTabSet', 'globalconfig', array('active' => 'config_general'));
			}
			else
			{
				echo JHtml::_('tabs.start', 'judirectory', array('useCookie' => 1));
			}
			foreach ($fieldSets AS $name => $fieldSet)
			{

				$label = $fieldSet->label ? $fieldSet->label : JText::_('COM_JUDIRECTORY_FIELD_SET_' . strtoupper($fieldSet->name));
				if ($this->isJoomla3x)
				{
					echo JHtml::_('bootstrap.addTab', 'globalconfig', $fieldSet->name, JText::_($label));
				}
				else
				{
					echo JHtml::_('tabs.panel', JText::_($label), '');
				}
				?>

				<?php
				if(!JUDIRPROVERSION)
				{
					foreach ($this->form->getFieldset($name) AS $field)
					{
						if($this->form->getFieldAttribute($field->fieldname, 'proversion', null, $field->group) == 'true')
						{
							$this->form->setFieldAttribute($field->fieldname, 'disabled', 'disabled', $field->group);
							$fieldLabel = $this->form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
							$this->form->setFieldAttribute($field->fieldname, 'label', JText::_($fieldLabel) . ' <span class="pro-version">Pro version</span>', $field->group);
							$fieldClass = $this->form->getFieldAttribute($field->fieldname, 'class', '', $field->group);
							$this->form->setFieldAttribute($field->fieldname, 'class', $fieldClass . ' disabled', $field->group);
							$this->form->setFieldAttribute($field->fieldname, 'name', '', $field->group);
						}
					}
				}

				foreach ($this->form->getFieldset($name) AS $field)
				{
					if(!$app->input->getInt('showhidden', 0) && $this->form->getFieldAttribute($field->fieldname, 'hiddenfield', null, $field->group) == 'true')
					{
						$class = 'hidden';
					}
					elseif($this->form->getFieldAttribute($field->fieldname, 'hiddenfield', null, $field->group) == 'true')
					{
						$class = 'hiddenfield';
					}
					else
					{
						$class = '';
					}
					?>
					<div class="control-group <?php echo $class; ?>">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php
				} ?>

				<?php
				if ($this->isJoomla3x)
				{
					echo JHtml::_('bootstrap.endTab');
				}
				?>
			<?php
			}

			if ($this->isJoomla3x)
			{
				echo JHtml::_('bootstrap.endTabSet');
			}
			else
			{
				echo JHtml::_('tabs.end');
			}
		}
		?>

		<div>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>