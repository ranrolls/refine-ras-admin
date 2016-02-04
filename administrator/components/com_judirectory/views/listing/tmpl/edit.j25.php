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

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true).'/administrator/components/com_judirectory/assets/fix_j25/fix.bootstrap.css');
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

<div class="jubootstrap">

<div id="iframe-help"></div>

<form action="<?php echo JRoute::_('index.php?option=com_judirectory&layout=edit&id=' . (int) $this->item->id); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate">
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

<div class="width-60 fltlft">
	<?php
	echo JHtml::_('tabs.start', 'listing-' . $this->item->id, array('useCookie' => 1));
	echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_CORE_FIELDS_TAB'), 'main');
	echo $this->loadTemplate('main');
	echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_EXTRA_FIELDS_TAB'), 'fields');
	echo $this->loadTemplate('fields');
	if($this->fieldLocations)
	{
		echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_LOCATIONS_TAB'), 'locations');
		echo $this->fieldLocations->getInput(isset($this->fieldsData[$this->fieldLocations->id]) ? $this->fieldsData[$this->fieldLocations->id] : null);
	}
	echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_RELATED_LISTINGS_TAB'), 'related-listings');
	echo $this->loadTemplate('rel_listings');
	if (!empty($this->plugins))
	{
		echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_PLUGIN_PARAMS_TAB'), 'plugin_params');
		echo $this->loadTemplate('plugin_params');
	}
	echo JHtml::_('tabs.end');
	?>
</div>

<div class="width-40 fltrt">
	<?php echo $this->loadTemplate('gallery'); ?>
	<?php echo JHtml::_('sliders.start', 'listing-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
	<?php echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_PUBLISHING'), 'publishing'); ?>
	<fieldset class="adminform">
		<ul class="adminformlist">
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

					echo "<li>";
					
					if (is_object($field))
					{
						
						if ($field->field_name == "modified" || $field->field_name == "modified_by")
						{
							if ($this->item->modified_by)
							{
								echo $field->getLabel();
								echo $field->getModPrefixText();
								echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
								echo $field->getModSuffixText();
								echo $field->getCountryFlag();
							}
						}
						
						elseif ($field->field_name == "approved_by" || $field->field_name == "approved_time")
						{
							if ($this->item->approved_by)
							{
								echo $field->getLabel();
								echo $field->getModPrefixText();
								echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
								echo $field->getModSuffixText();
								echo $field->getCountryFlag();
							}
						}
						else
						{
							echo $field->getLabel();
							echo $field->getModPrefixText();
							echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
							echo $field->getModSuffixText();
							echo $field->getCountryFlag();
						}
					}
					
					else
					{
						$_field = $this->form->getField($field);
						
						if ($field == "modified" || $field == "modified_by")
						{
							if ($this->item->modified_by)
							{
								echo $_field->label;
								echo $_field->input;
							}
						}
						
						elseif ($field == "approved_by" || $field == "approved_time")
						{
							if ($this->item->approved_by)
							{
								echo $_field->label;
								echo $_field->input;
							}
						}
						else
						{
							echo $_field->label;
							echo $_field->input;

						}
					}
					echo "</li>";
				}
			}
			?>
		</ul>
	</fieldset>
	<?php
	echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_TEMPLATE_STYLE'), 'style-layout');
	?>
	<fieldset class="adminform">
		<ul class="adminformlist">
			<?php
			if ($this->fieldsetTemplateStyleAndLayout)
			{
				foreach ($this->fieldsetTemplateStyleAndLayout AS $field)
				{
					
					echo "<li>";
					if (is_object($field))
					{
						echo $field->getLabel();
						echo $field->getModPrefixText();
						echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
						echo $field->getModSuffixText();
						echo $field->getCountryFlag();
					}
					
					else
					{
						$field = $this->form->getField($field);
						echo $field->label;
						echo $field->input;
					}
					echo "</li>";
				}
			}
			?>
		</ul>
	</fieldset>

	<?php
	$fields = $this->form->getFieldSet('template_params');
	if ($fields)
	{
		echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_TEMPLATE_PARAMS'), 'template-params');
		?>
		<fieldset class="adminform">
			<ul class="adminformlist">
				<?php
				foreach ($fields AS $name => $field) : ?>
					<li>
						<?php echo $field->label; ?>
						<?php echo $field->input; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</fieldset>
	<?php
	} ?>

	<?php
	echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_METADATA'), 'metadata');
	?>
	<fieldset class="adminform">
		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset('metadata') AS $field): ?>
				<li>
					<?php echo $field->label; ?>
					<?php echo $field->input; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<?php
	echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_NOTES'), 'notes');
	?>
	<fieldset class="adminform">
		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset('notes') AS $field): ?>
				<li>
					<?php echo $field->label; ?>
					<?php echo $field->input; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<?php
	echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_PARAMS'), 'params');
	?>
	<fieldset class="adminform">
		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset('params') AS $field): ?>
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
	<?php
	echo JHtml::_('tabs.start', 'listing-acl-tab-' . $this->item->id, array('useCookie' => 1));
	echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_PERMISSION_LISTING_LABEL'), 'listing');
	?>
	<div class="width-100 fltlft">
		<?php echo JHtml::_('sliders.start', 'listing-permissions-sliders-listing' . $this->item->id, array('useCookie' => 1)); ?>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_PERMISSIONS'), 'permissions-listing'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getInput('rules'); ?>
				</li>
			</ul>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>
	<div class="clr"></div>
	<?php
	echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_PERMISSION_COMMENT_LABEL'), 'comment');
	?>
	<div class="width-100 fltlft">
		<?php echo JHtml::_('sliders.start', 'listing-permissions-sliders-comment' . $this->item->id, array('useCookie' => 1)); ?>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_JUDIRECTORY_FIELD_SET_PERMISSIONS'), 'permissions-comment'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getInput('comment_permissions'); ?>
				</li>
			</ul>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>
	<div class="clr"></div>
	<?php
	echo JHtml::_('tabs.end');
	?>
<?php endif; ?>

<div class="clr"></div>

<div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>
