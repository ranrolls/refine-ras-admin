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
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$document = JFactory::getDocument();
$document->addScript(JUri::root(true).'/components/com_judirectory/assets/js/jquery.dragsort.min.js');
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task)
    {
		if (task == 'csvprocess.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};

    jQuery(document).ready(function($)
    {
        $("#jformcsv_cat_filter").change(function () {
            var catids = $(this).val().join(",");
            var search_sub_categories = 0;
            if ($("#sub_cat").prop('checked') == true) {
                search_sub_categories = 1;
            }
        });

        $('input[name="checkall-toggle"]').click(function(){
            var c = $(this).attr('sub-checkbox');
            if($(this).prop('checked')==false){
                $('.'+c).prop('checked', false);
            }else{
                $('.'+c).prop('checked', true);
            }
        });

        $("table.table tbody").dragsort({ dragSelector: "tr", placeHolderTemplate: "<tr class='placeHolder'><td></td></tr>", dragSelectorExclude: "input,label" });
    });
</script>

<style>
    .adminformlist li label{
    }

    table.table tr label{
        display: inline;
    }

    #jform_csv_sub_cat-lbl{
        display: inline-block;
        margin-right: 10px;
    }
</style>

<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

		<div class="span6">
			<fieldset class="adminform">
				<legend><?php echo JText::_("COM_JUDIRECTORY_CSV_EXPORT_SETTINGS"); ?></legend>
				<ul class="adminformlist">
					<?php
					foreach ($this->exportForm->getFieldset('details') AS $field)
					{
						echo "<li>";
						echo $field->label;
						echo $field->input;
						echo "</li>";
					}
					?>
				</ul>
			</fieldset>
		</div>

		<div class="span6">
            <legend><?php echo JText::_("COM_JUDIRECTORY_FIELDS_TO_EXPORT"); ?></legend>
            <?php
            $coreFields = $this->model->getCoreFields();

            echo JHtml::_('bootstrap.startTabSet', 'csv-export', array('active' => 'core-fields'));
            ?>

            <?php
            
            if ($coreFields)
            {
                echo JHtml::_('bootstrap.addTab', 'csv-export', 'core-fields', JText::_('COM_JUDIRECTORY_CORE_FIELDS_TAB'));
            ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%">
                                <input type="checkbox" sub-checkbox="core_field" id="core_field_toggle" title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value="" name="checkall-toggle" checked/>
                            </th>
                            <th>
                                <?php echo JText::_('COM_JUDIRECTORY_FIELD_NAME'); ?>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($coreFields AS $key => $field)
                        {
                            if (is_object($field)) {
                                if(!$field->canExport()){
                                    continue;
                                }

                                $value = $field->id;
                                $label = $field->getCaption(true);
                            } else {
                                $value = $field;
                                $label = ucfirst(str_replace('_', ' ', $field));
                            }

                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="core_field" checked
                                           onclick="Joomla.isChecked(this.checked);" value="<?php echo $value; ?>"
                                           name="col[]" id="cb<?php echo $key; ?>"/>
                                </td>
                                <td>
                                    <label for="cb<?php echo $key; ?>"><?php echo $label; ?></label>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                        <tr>
                            <td>
                                <input type="checkbox" class="core_field" checked onclick="Joomla.isChecked(this.checked);" value="related_listings"  name="col[]" id="cb<?php echo ($key+1); ?>" />
                            </td>
                            <td>
                                <label for="cb<?php echo ($key+1); ?>"><?php echo JText::_('COM_JUDIRECTORY_FIELD_RELATED_LISTINGS'); ?></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php
                echo JHtml::_('bootstrap.endTab');
            }
            ?>

            <?php
                $extraFields = $this->model->getExtraFields();
                $fieldGroups = array();
                foreach ($extraFields AS $field)
                {
                    if(!$field->canExport()){
                        continue;
                    }

                    if (isset($fieldGroups[$field->group_id]))
                    {
                        $fieldGroups[$field->group_id][] = $field;
                    }
                    else
                    {
                        $fieldGroups[$field->group_id] = array($field);
                    }
                }

                if (!empty($fieldGroups))
                {
                    foreach ($fieldGroups AS $groupId => $fields)
                    {
                        
                        $group = JUDirectoryFrontHelperField::getFieldGroupById($groupId);
                        echo JHtml::_('bootstrap.addTab', 'csv-export', "fieldgroup-$group->id", $group->name);
                    ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="1%">
                                <input type="checkbox" id="<?php echo $group->id; ?>" sub-checkbox="group_<?php echo $group->id; ?>" class="field"
                                       title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value="" name="checkall-toggle" />
                            </th>
                            <th>
                                <?php echo JText::_('COM_JUDIRECTORY_FIELD_NAME'); ?>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($fields AS $field)
                        { ?>
                            <tr>
                                <td class="center">
                                    <input type="checkbox" id="<?php echo $field->id; ?>" class="group_<?php echo $field->group_id; ?> field" onclick="Joomla.isChecked(this.checked);" value="<?php echo $field->id; ?>"  name="col[]" /></li>
                                </td>
                                <td>
                                    <label for="<?php echo $field->id; ?>"><?php echo ucfirst($field->getCaption(true)); ?></label>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                <?php
                        echo JHtml::_('bootstrap.endTab');
                    }
                }
            ?>

            <?php
            echo JHtml::_('bootstrap.endTabSet');
            ?>
		</div>

		<div class="clr clearfix"></div>

		<div>
			<input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>