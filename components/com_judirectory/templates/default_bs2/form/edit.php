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
JHtml::_('formbehavior.chosen', 'select:not(.browse_cat)');

JFactory::getDocument()->addScript(JUri::root() . "components/com_judirectory/assets/js/judir-tabs-state.js");
?>

<script type="text/javascript">
	var buttonClicked = false;
	jQuery(window).on('beforeunload', function (e) {
		if (!buttonClicked) {
			var message = '<?php echo JText::_('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_LEAVE_THIS_PAGE_ALL_DATA_YOU_ENTERED_WILL_BE_LOST'); ?>'; //This is displayed on the dialog
			if (!e) e = window.event;
			//e.cancelBubble is supported by IE - this will kill the bubbling process.
			e.cancelBubble = true;
			e.returnValue = message;
			//e.stopPropagation works in Firefox.
			if (e.stopPropagation) {
				e.stopPropagation();
				e.preventDefault();
			}
			return message;
		}
	});

	Joomla.submitbutton = function (task) {
		buttonClicked = true;
		if (task == 'form.cancel' || task == 'modpendinglisting.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
</script>

<div id="judir-container" class="jubootstrap component judir-container view-form judir-form">
	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&layout=edit&id=' . (int) $this->item->id); ?>"
	      enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div id="alertChangeCategory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="alertChangeCategoryLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="alertChangeCategoryLabel"><?php echo JText::_('COM_JUDIRECTORY_CHANGE_MAIN_CATEGORY_WARNING'); ?></h3>
		</div>
		<div class="modal-body">
			<div id="messageChangeFieldGroup"></div>
			<div id="messageChangeTemplate"></div>
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
    if($this->params->get('submit_form_show_tab_related', 0))
    {
        echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'related-listings', JText::_('COM_JUDIRECTORY_RELATED_LISTINGS_TAB'));
        echo $this->loadTemplate('rel_listings');
        echo JHtml::_('bootstrap.endTab');
    }
	?>

	<?php
    if($this->params->get('submit_form_show_tab_plugin_params', 0))
    {
        if (!empty($this->plugins))
        {
            echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'plugin_params', JText::_('COM_JUDIRECTORY_PLUGIN_PARAMS_TAB'));
            echo $this->loadTemplate('plugin_params');
            echo JHtml::_('bootstrap.endTab');
        }
    }
    ?>

	<?php
	if ($this->fieldGallery)
	{
		echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'gallery', JText::_('COM_JUDIRECTORY_GALLERY_TAB'));
		echo $this->fieldGallery->getInput();
		echo JHtml::_('bootstrap.endTab');
	}
	?>

	<?php
    if($this->params->get('submit_form_show_tab_publishing', 0) || $this->params->get('submit_form_show_tab_style', 0)
        || $this->params->get('submit_form_show_tab_meta_data', 0) || $this->params->get('submit_form_show_tab_params', 0)
        || $this->params->get('submit_form_show_tab_permissions', 0))
    {
        echo JHtml::_('bootstrap.addTab', 'listing-' . $this->item->id, 'others', JText::_('COM_JUDIRECTORY_OTHERS_TAB'));
        echo $this->loadTemplate('others');
        echo JHtml::_('bootstrap.endTab');
    }
	?>

	<?php echo JHtml::_('bootstrap.endTabset'); ?>
	</div>

	<div>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</form>
</div>

<script src="<?php echo JUri::root()?>administrator/components/com_judirectory/assets/js/listing-fix-editor.js" type="text/javascript"></script>