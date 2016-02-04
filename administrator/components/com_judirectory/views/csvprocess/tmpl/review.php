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
<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form method="post" name="adminForm" id="adminForm" class="form-validate">
		<fieldset>
			<legend><?php echo JText::_("COM_JUDIRECTORY_FIELDS_MAPPING"); ?></legend>
			<table class="table">
				<thead>
				<tr>
					<th>
						<?php echo JText::_("COM_JUDIRECTORY_CSV_COLUMNS"); ?>
					</th>
					<th>

					</th>
					<th>
						<?php echo JText::_("COM_JUDIRECTORY_LISTING_FIELDS"); ?>
					</th>
				</tr>
				</thead>
				<tbody>

				<?php
				foreach ($this->review['csv_columns'] AS $key => $column)
				{
					echo "<tr><td>" . $column . "</td> <td><i class=\"icon-arrow-right-4\"></i></td> <td>" . $this->review['csv_array_map_column_fieldcaption'][$key] . "</td></tr>";
				}
				?>
				</tbody>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("COM_JUDIRECTORY_CSV_IMPORT_CONFIG"); ?></legend>
			<table class="table">
				<thead>
				<tr>
					<th>
						<?php echo JText::_('COM_JUDIRECTORY_FIELD'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_JUDIRECTORY_VALUE'); ?>
					</th>
				</tr>
				</thead>
				<tbody>
                <tr>
                    <td>
                        <?php echo JText::_("COM_JUDIRECTORY_IF_LISTING_EXISTS_LABEL"); ?>
                    </td>
                    <td>
                        <?php echo $this->review['config']['save_options'] ?>
                    </td>

                </tr>
                <tr>
                    <td>
                        <?php echo JText::_("COM_JUDIRECTORY_FIELD_REBUILD_ALIAS_LABEL"); ?>
                    </td>
                    <td>
                        <?php echo $this->review['config']['rebuild_alias'] ? JText::_('JYES') : JText::_('JNO'); ?>
                    </td>
                </tr>
				<tr>
					<td>
						<?php echo JText::_("COM_JUDIRECTORY_FIELD_CREATED_BY"); ?>
					</td>
					<td>
						<?php if(!empty($this->review['config']['created_by'])) echo JFactory::getUser($this->review['config']['created_by'])->name; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_("COM_JUDIRECTORY_FORCE_PUBLISH"); ?>
					</td>
					<td>
                        <?php echo $this->review['config']['force_publish'] ? JText::_('JYES') : JText::_('JNO'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_("COM_JUDIRECTORY_SELECTED_DEFAULT_MAIN_CATEGORY"); ?>
					</td>
					<td>
						<?php
							if($this->review['config']['default_main_cat_id'])
							{
								$mainCat = $this->review['config']['default_main_cat_id'];
								$catObj  = JUDirectoryHelper::getCategoryById($mainCat);
								echo $catObj->title;
							}
						?>
					</td>
				</tr>
				</tbody>
			</table>
		</fieldset>

		<div>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>