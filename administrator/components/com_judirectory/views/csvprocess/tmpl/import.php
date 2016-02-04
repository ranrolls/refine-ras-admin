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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'csvprocess.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    };
</script>

<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<fieldset class="adminform">
		<legend><?php echo JText::_("COM_JUDIRECTORY_IMPORT_CSV"); ?></legend>
		<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
			<ul class="adminformlist">
				<li>
					<label id="jform_file-lbl" for="jform_file"><?php echo JText::_("COM_JUDIRECTORY_SELECT_CSV_FILE"); ?><span class="star">&nbsp;*</span></label>
					<input type="file" name="file" id="jform_file" class="required"/>
				</li>
				<li>
					<label id="jform_delimiter-lbl" for="jform_delimiter"><?php echo JText::_("COM_JUDIRECTORY_CSV_DELIMITER"); ?></label>
					<input id="jform_delimiter" type="text" name="delimiter" value="," />
				</li>
				<li>
					<label id="jform_enclosure-lbl" for="jform_enclosure"><?php echo JText::_("COM_JUDIRECTORY_CSV_ENCLOSURE"); ?></label>
					<input id="jform_enclosure" type="text" name="enclosure" value='"' />
				</li>
			</ul>

			<div class="clr clearfix"></div>

			<div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</form>
	</fieldset>
</div>