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
JHtml::_('behavior.multiselect');
$configs = array();
$configs[] = array("name" => "field_attachment_directory", "default" => "media/com_judirectory/field_attachments/");
$configs[] = array("name" => "category_intro_image_directory", "default" => "media/com_judirectory/images/category/intro/");
$configs[] = array("name" => "category_detail_image_directory", "default" => "media/com_judirectory/images/category/detail/");
$configs[] = array("name" => "listing_original_image_directory", "default" => "media/com_judirectory/images/gallery/original/");
$configs[] = array("name" => "listing_small_image_directory", "default" => "media/com_judirectory/images/gallery/small/");
$configs[] = array("name" => "listing_big_image_directory", "default" => "media/com_judirectory/images/gallery/big/");
$configs[] = array("name" => "listing_image_directory", "default" => "media/com_judirectory/images/listing/");
$configs[] = array("name" => "avatar_directory", "default" => "media/com_judirectory/images/avatar/");
$configs[] = array("name" => "collection_icon_directory", "default" => "media/com_judirectory/images/collection/");
$configs[] = array("name" => "email_attachment_directory", "default" => "media/com_judirectory/email_attachments/");

if (extension_loaded('gd') && function_exists('gd_info') && function_exists('imagecreatetruecolor'))
{
	$hasGDLibText = '<span class="badge badge-success">' . JText::_('JYES') . '</span>';
}
else
{
	$hasGDLibText = '<span class="badge badge-important">' . JText::_('JNO') . '</span>';
}
?>
<div class="jubootstrap">
	<?php echo JUDirectoryHelper::getMenu(JFactory::getApplication()->input->get('view')); ?>

	<div id="iframe-help"></div>

	<form action="<?php echo JRoute::_('index.php?option=com_judirectory&view=tools&layout=information'); ?>" method="post" name="adminForm" id="adminForm" class="row-fluid">
		<div class="width-60 span8">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_JUDIRECTORY_INFORMATION'); ?></legend>
				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th><?php echo JText::_('COM_JUDIRECTORY_DIRECTORIES'); ?></th>
						<th style="width:100px"><?php echo JText::_('COM_JUDIRECTORY_STATUS'); ?></th>
					</tr>
					</thead>

					<tbody>
					<?php foreach ($configs AS $config)
					{
						$value  = JUDirectoryFrontHelper::getDirectory($config['name'], $config['default']);
						$status = is_writable(JPATH_ROOT . "/" . $value) ? "<span class='badge badge-success'>" . JText::_('COM_JUDIRECTORY_WRITEABLE') . "</span>" : "<span class='badge badge-important'>" . JText::_('COM_JUDIRECTORY_UNWRITEABLE') . "</span>";
						echo "<tr>";
						echo "<td>" . $value . "</td>";
						echo "<td>" . $status . "</td>";
						echo "</tr>";
					}
					?>
					</tbody>
				</table>

				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th><?php echo JText::_('COM_JUDIRECTORY_LIBRARIES'); ?></th>
						<th style="width:100px"><?php echo JText::_('COM_JUDIRECTORY_STATUS'); ?></th>
					</tr>
					</thead>
					
					<tbody>
					<tr>
						<td><?php echo JText::_('COM_JUDIRECTORY_GD_LIBRARY'); ?></td>
						<td><?php echo $hasGDLibText; ?></td>
					</tr>
					</tbody>
				</table>
			</fieldset>
		</div>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="com_judirectory" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>