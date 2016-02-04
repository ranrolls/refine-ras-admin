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
?>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		Joomla.submitbutton = function (task) {
			if (task == 'csvprocess.config') {
				var duplicate = false;
				var assigned = [];
				$('select').each(function () {
					var value = $(this).val();
					if (jQuery.inArray(value, assigned) > -1 && value != "ignore") {
						$("#duplicated_mapping").show();
						$(this).css('border-color', 'red');
						duplicate = true;
					} else {
						assigned.push(value);
						$(this).css('border-color', '');
					}
				});

				if (duplicate != true) {
					Joomla.submitform(task, document.getElementById('adminForm'));
				} else {
					return false;
				}
			} else {
				Joomla.submitform(task, document.getElementById('adminForm'));
			}
		};
	});
</script>

<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate">
		<fieldset class="adminform">
			<legend><?php echo JText::_("COM_JUDIRECTORY_FIELDS_MAPPING"); ?></legend>
			<span id="duplicated_mapping" style="color: red;display: none"><?php echo JText::_("COM_JUDIRECTORY_MORE_THAN_ONE_CSV_COLUMNS_IS_MAPPING_WITH_ONE_LISTING_FIELD"); ?></span>
			<table class="table table-striped">
				<thead>
				<tr>
					<th><?php echo JText::_("COM_JUDIRECTORY_CSV_COLUMNS"); ?></th>
					<th></th>
					<th><?php echo JText::_("COM_JUDIRECTORY_LISTING_FIELDS"); ?></th>
				</tr>
				</thead>

				<tbody>
				<?php
                $app = JFactory::getApplication();
                $dataCSV = $app->getUserState('csv.dataCSV');
                $csv_assigned_fields = isset($dataCSV['csv_assigned_fields']) ? $dataCSV['csv_assigned_fields'] : array();
				if (isset($this->mapped_columns) && !empty($this->mapped_columns))
				{
                    $i = 0;
					foreach ($this->mapped_columns AS $csvColumnName => $detectedField)
					{
                        $value = isset($csv_assigned_fields[$i]) ? $csv_assigned_fields[$i] : $detectedField;
						echo "<tr><td>" . $csvColumnName . "</td><td><i class=\"icon-arrow-right-4\"></i></td><td>".JHTML::_('select.genericlist', $this->fieldsOption,'assign[]','','value','text',$value)."</td></tr>";
                        $i++;
					}
				}
				?>
				</tbody>
			</table>

			<div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</fieldset>
	</form>
</div>