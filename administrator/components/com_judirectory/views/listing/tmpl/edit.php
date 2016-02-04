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

JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_judirectory/helpers/html');
JHtml::_('behavior.calendar');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select:not(.browse_cat,.nochosen)');

JFactory::getDocument()->addScript(JUri::root() . "components/com_judirectory/assets/js/judir-tabs-state.js");
?>

<script type="text/javascript">
	var buttonClicked = false;
	jQuery(window).on('beforeunload', function (e) {
		if (!buttonClicked) {
			var message = '<?php echo JText::_('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_LEAVE_THIS_PAGE_ALL_DATA_YOU_ENTERED_WILL_BE_LOST'); ?>'; 
			if (!e) e = window.event;
			
			e.cancelBubble = true;
			e.returnValue = message;
			
			if (e.stopPropagation) {
				e.stopPropagation();
				e.preventDefault();
			}
			return message;
		}
	});

	Joomla.submitbutton = function (task) {
		buttonClicked = true;
		if (task == 'listing.cancel' || task == 'pendinglisting.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div id="iframe-help"></div>

<form action="<?php echo JRoute::_('index.php?option=com_judirectory&layout=edit&id=' . (int) $this->item->id); ?>"
      enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
<div id="alertChangeCategory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="alertChangeCategoryLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="alertChangeCategoryLabel"><?php echo JText::_('COM_JUDIRECTORY_CHANGE_MAIN_CATEGORY_WARNING'); ?></h3>
	</div>
	<div class="modal-body">
		<div id="messageChangeFieldGroup" class="alert alert-warning"></div>
		<div id="messageChangeTemplate" class="alert alert-warning"></div>
	</div>
	<div class="modal-footer">
		<button id="noConfirmChangeCat" class="btn"><?php echo JText::_('COM_JUDIRECTORY_CLOSE'); ?></button>
		<button id="confirmChangeCat" class="btn btn-primary"><?php echo JText::_('COM_JUDIRECTORY_CONFIRM_AND_CHANGE'); ?></button>
	</div>
</div>
<?php
	echo $this->loadTemplate('btn_group_control');
?>

<div class="row-fluid">
<div class="span8">
	<div class="form-horizontal">

		<?php echo JHtml::_('bootstrap.startTabSet', 'listing-' . $this->item->id, array('active' => 'details')); ?>

		<?php
		echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'details', JText::_('COM_JUDIRECTORY_CORE_FIELDS_TAB'));
		echo $this->loadTemplate('main');
		echo JHtml::_('bootstrap.endTab');
		?>

		<?php
		echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'fields', JText::_('COM_JUDIRECTORY_EXTRA_FIELDS_TAB'));
		echo $this->loadTemplate('fields');
		echo JHtml::_('bootstrap.endTab');
		?>

		<?php
		if($this->fieldLocations)
		{
			echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'locations', JText::_('COM_JUDIRECTORY_LOCATIONS_TAB'));
			echo $this->fieldLocations->getInput(isset($this->fieldsData[$this->fieldLocations->id]) ? $this->fieldsData[$this->fieldLocations->id] : null);
			echo JHtml::_('bootstrap.endTab');
		}
		elseif(!JUDIRPROVERSION)
		{
			echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'locations', JText::_('COM_JUDIRECTORY_LOCATIONS_TAB'));
			echo '<div class="alert alert-success">';
			echo '<p>This section helps to add multi locations to listing.</p>';
			echo '<p>Please upgrade to <a href="http://www.joomultra.com/ju-directory-comparison.html">Pro Version</a> to use this feature</p>';
			echo '</div>';
			echo JHtml::_('bootstrap.endTab');
		}
		?>

		<?php
		echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'related-listings', JText::_('COM_JUDIRECTORY_RELATED_LISTINGS_TAB'));
		echo $this->loadTemplate('rel_listings');
		echo JHtml::_('bootstrap.endTab');
		?>

		<?php
		if (!empty($this->plugins))
		{
			echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'plugin_params', JText::_('COM_JUDIRECTORY_PLUGIN_PARAMS_TAB'));
			echo $this->loadTemplate('plugin_params');
			echo JHtml::_('bootstrap.endTab');
		} ?>

		<?php
		if ($this->app->isAdmin())
		{
			if ($this->canDo->get('core.admin'))
			{
				echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'permissions', JText::_('COM_JUDIRECTORY_FIELD_SET_PERMISSIONS'));
				echo JHtml::_('bootstrap.startTabSet', 'listing-permission', array('active' => 'listing_permissions'));

				echo JHtml::_('bootstrap.addTab', 'listing-permission', 'listing_permissions', JText::_('COM_JUDIRECTORY_PERMISSION_LISTING_LABEL', true));
				foreach ($this->form->getFieldset('listing_permissions') AS $field)
				{
					echo $field->input;
				}
				echo JHtml::_('bootstrap.endTab');
				echo JHtml::_('bootstrap.addTab', 'listing-permission', 'comment_permissions', JText::_('COM_JUDIRECTORY_PERMISSION_COMMENT_LABEL', true));
				foreach ($this->form->getFieldset('comment_permissions') AS $field)
				{
					echo $field->input;
				}
				echo JHtml::_('bootstrap.endTab');
				echo JHtml::_('bootstrap.endTabSet');
				echo JHtml::_('bootstrap.endTab');
			}
		}
		?>
		<?php echo JHtml::_('bootstrap.endTabset'); ?>
	</div>
</div>

<div class="span4">
	<?php echo $this->loadTemplate('gallery'); ?>
	<?php echo JHtml::_('bootstrap.startAccordion', 'listing-sliders-' . $this->item->id, array('active' => 'publishing')); ?>

	<?php echo JHtml::_('bootstrap.addSlide', 'listing-sliders-' . $this->item->id, JText::_('COM_JUDIRECTORY_FIELD_SET_PUBLISHING'), 'publishing', 'publishing'); ?>
	<?php
	if ($this->fieldsetPublishing)
	{
		foreach ($this->fieldsetPublishing AS $field)
		{
			if(!JUDIRPROVERSION)
			{
				if (is_object($field))
				{
					if ($field->field_name == "approved" || $field->field_name == "approved_by" || $field->field_name == "approved_time")
					{
						continue;
					}
				}
				else
				{
					if ($field == "approved" || $field == "approved_by" || $field == "approved_time")
					{
						continue;
					}
				}
			}
			
			
			if (is_object($field))
			{
				echo '<div class="control-group ">';
				
				if ($field->field_name == "modified" || $field->field_name == "modified_by")
				{
					if ($this->item->modified_by)
					{
						echo '<div class="control-label">';
						echo $field->getLabel();
						echo '</div>';
						echo '<div class="controls">';
						echo $field->getModPrefixText();
						echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
						echo $field->getModSuffixText();
						echo $field->getCountryFlag();
						echo '</div>';
					}
				}
				
				elseif ($field->field_name == "approved_by" || $field->field_name == "approved_time")
				{
					if ($this->item->approved_by)
					{
						echo '<div class="control-label">';
						echo $field->getLabel();
						echo "</div>";
						echo '<div class="controls">';
						echo $field->getModPrefixText();
						echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
						echo $field->getModSuffixText();
						echo $field->getCountryFlag();
						echo '</div>';
					}
				}
				else
				{
					echo '<div class="control-label">';
					echo $field->getLabel();
					echo "</div>";
					echo '<div class="controls">';
					echo $field->getModPrefixText();
					echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
					echo $field->getModSuffixText();
					echo $field->getCountryFlag();
					echo '</div>';
				}
				echo "</div>";
			}
			
			else
			{
				$_field = $this->form->getField($field);
				
				if ($field == "modified" || $field == "modified_by")
				{
					if ($this->item->modified_by)
					{
						echo $_field->getControlGroup();
					}
				}
				
				elseif ($field == "approved_by" || $field == "approved_time")
				{
					if ($this->item->approved_by)
					{
						echo $_field->getControlGroup();
					}
				}
				else
				{
					echo $_field->getControlGroup();
				}
			}
		}
	}
	?>

	<?php echo JHtml::_('bootstrap.endSlide'); ?>

	<?php echo JHtml::_('bootstrap.addSlide', 'listing-sliders-' . $this->item->id, JText::_('COM_JUDIRECTORY_FIELD_SET_TEMPLATE_STYLE'), 'style-layout', 'style-layout'); ?>
	<?php
	if ($this->fieldsetTemplateStyleAndLayout)
	{
		foreach ($this->fieldsetTemplateStyleAndLayout AS $field)
		{
			
			echo '<div class="control-group ">';
			if (is_object($field))
			{
				echo '<div class="control-label">';
				echo $field->getLabel();
				echo '</div>';
				echo '<div class="controls">';
				echo $field->getModPrefixText();
				echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
				echo $field->getModSuffixText();
				echo $field->getCountryFlag();
				echo '</div>';
			}
			
			else
			{
				$field = $this->form->getField($field);
				echo $field->getControlGroup();
			}
			echo '</div>';
		}
	}
	?>
	<?php echo JHtml::_('bootstrap.endSlide'); ?>

	<?php
	$fields = $this->form->getFieldSet('template_params');
	if ($fields)
	{
		echo JHtml::_('bootstrap.addSlide', 'listing-sliders-' . $this->item->id, JText::_('COM_JUDIRECTORY_FIELD_SET_TEMPLATE_PARAMS'), 'template-params', 'template-params');
		foreach ($fields AS $name => $field) :
			echo $field->getControlGroup();
		endforeach;
		echo JHtml::_('bootstrap.endSlide');
	} ?>

	<?php
	echo JHtml::_('bootstrap.addSlide', 'listing-sliders-' . $this->item->id, JText::_('COM_JUDIRECTORY_FIELD_SET_METADATA'), 'metadata', 'metadata');
	foreach ($this->form->getFieldset('metadata') AS $field):
		echo $field->getControlGroup();
	endforeach;
	echo JHtml::_('bootstrap.endSlide');
	?>

	<?php
	echo JHtml::_('bootstrap.addSlide', 'listing-sliders-' . $this->item->id, JText::_('COM_JUDIRECTORY_FIELD_SET_NOTES'), 'notes', 'notes');
	foreach ($this->form->getFieldset('notes') AS $field):
		echo $field->getControlGroup();
	endforeach;
	echo JHtml::_('bootstrap.endSlide');
	?>

	<?php
	echo JHtml::_('bootstrap.addSlide', 'listing-sliders-' . $this->item->id, JText::_('COM_JUDIRECTORY_FIELD_SET_PARAMS'), 'params', 'params');
	foreach ($this->form->getFieldset('params') AS $field):
		echo $field->getControlGroup();
	endforeach;
	echo JHtml::_('bootstrap.endSlide');
	?>

	<?php echo JHtml::_('bootstrap.endAccordion'); ?>
</div>
</div>

<div>
	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>

<script src="<?php echo JUri::root()?>administrator/components/com_judirectory/assets/js/listing-fix-editor.js" type="text/javascript"></script>