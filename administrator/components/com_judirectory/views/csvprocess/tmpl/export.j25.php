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

<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate row-fluid" enctype="multipart/form-data">

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
            $ignoredFields = array('asset_id', 'introtext', 'fulltext', 'comments', 'addresses');
            $coreFields = $this->model->getCoreFields($ignoredFields);

			echo JHtml::_('tabs.start', 'csv-export', array('useCookie' => 1));
            ?>

            <?php
            
            if ($coreFields)
            {
	            echo JHtml::_('tabs.panel', JText::_('COM_JUDIRECTORY_CORE_FIELDS_TAB'), 'core-fields');
            ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%">
                                <input type="checkbox" sub-checkbox="core_field" id="core_field_toggle" title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value="" name="checkall-toggle" checked/>
                            </th>
                            <th class="center">
                                <?php echo JText::_('COM_JUDIRECTORY_FIELD_NAME'); ?>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $index = 0;
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
                            <td class="center">
                                <input type="checkbox" class="core_field" checked onclick="Joomla.isChecked(this.checked);" value="related_listings"  name="col[]" id="cb<?php echo ($key+1); ?>" />
                            </td>
                            <td class="center">
                                <label for="cb<?php echo ($key + 1); ?>"><?php echo JText::_('COM_JUDIRECTORY_FIELD_RELATED_LISTINGS'); ?></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php
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
	                    echo JHtml::_('tabs.panel', $group->name, "fieldgroup-$group->id");
                    ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="1%">
                                <input type="checkbox" id="<?php echo $group->id; ?>" sub-checkbox="group_<?php echo $group->id; ?>" class="field"
                                       title="<?php echo JText::_('COM_JUDIRECTORY_CHECK_ALL'); ?>" value="" name="checkall-toggle" />
                            </th>
                            <th class="center">
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
                                <td class="center">
                                    <label for="<?php echo $field->id; ?>"><?php echo ucfirst($field->caption); ?></label>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                <?php
                    }
                }
            ?>

            <?php
            echo JHtml::_('tabs.end');
            ?>
		</div>

		<div class="clr clearfix"></div>

		<div>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>