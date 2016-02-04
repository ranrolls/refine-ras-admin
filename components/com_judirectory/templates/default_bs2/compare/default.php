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

if (is_array($this->items) && count($this->items))
{
	?>
	<div class="equal-height-row">
		<h3><?php echo JText::_('COM_JUDIRECTORY_LISTING_COMPARISON'); ?></h3>

		<div class="row-fluid">
			<div class="compare-field" style="float: left">
				<table class="table table-bordered table-hover">
					<tbody>
					<tr class="info">
						<td><?php echo JText::_('COM_JUDIRECTORY_LISTING_FIELD'); ?></td>
					</tr>
					<?php
					// Core fields
					foreach ($this->coreFields as $fieldObj)
					{
						?>
						<tr>
							<td>
								<?php
								$field = JUDirectoryFrontHelperField::getField($fieldObj);
								echo $field->getCaption(true);
								?>
							</td>
						</tr>
					<?php
					}
					?>

					<?php
					// Extra fields by group
					foreach ($this->extraFieldGroups as $fieldGroupId => $fieldGroupName)
					{
						?>
						<tr class="info">
							<td><?php echo $fieldGroupName; ?></td>
						</tr>
						<?php
						$fieldsInGroup = $this->model->getFieldsByGroupId($fieldGroupId);
						foreach ($fieldsInGroup as $fieldObj)
						{
							?>
							<tr>
								<td>
									<?php
									$field = JUDirectoryFrontHelperField::getField($fieldObj);
									echo $field->getCaption(true);
									?>
								</td>
							</tr>
						<?php
						}
						?>
					<?php
					}
					?>

					<tr>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=listing.removeCompare&all=1', false); ?>">
								<?php
									echo JText::_('COM_JUDIRECTORY_REMOVE_ALL');
								?>
							</a>
						</td>
					</tr>
					</tbody>
				</table>
			</div>

			<div class="compare-value" style="overflow: auto;">
				<table class="table table-bordered table-hover">
					<tbody>
					<tr class="info">
						<td colspan="<?php echo count($this->items); ?>"><?php echo JText::_('COM_JUDIRECTORY_LISTING_FIELD_VALUE'); ?></td>
					</tr>
					<?php
					// Core fields
					foreach ($this->coreFields as $fieldObj)
					{
						?>
						<tr>
							<?php
							foreach ($this->items as $item)
							{
								?>
								<td class="compare-col">
									<?php
									$field = JUDirectoryFrontHelperField::getField($fieldObj, $item);
									if (is_object($field) && ($field->canView(array("view" => "list")) || $field->canView(array("view" => "details"))))
									{
										echo $field->getDisplayPrefixText() . " " . $field->getOutput(array("view" => "list", "template" => $this->template)) . " " . $field->getDisplaySuffixText();
									} ?>
								</td>
							<?php
							}
							?>
						</tr>
					<?php
					}
					?>

					<?php
					// Extra fields by group
					foreach ($this->extraFieldGroups as $fieldGroupId => $fieldGroupName)
					{
						?>
						<tr class="info">
							<td colspan="<?php echo count($this->items); ?>"><?php echo $fieldGroupName; ?></td>
						</tr>
						<?php
						$fieldsInGroup = $this->model->getFieldsByGroupId($fieldGroupId);
						foreach ($fieldsInGroup as $fieldObj)
						{
							?>
							<tr>
								<?php
								foreach ($this->items as $item)
								{
									?>
									<td>
										<?php
										$field = JUDirectoryFrontHelperField::getField($fieldObj, $item);
										if (is_object($field) && ($field->canView(array("view" => "list")) || $field->canView(array("view" => "details"))))
										{
											echo $field->getDisplayPrefixText() . " " . $field->getOutput(array("view" => "list", "template" => $this->template)) . " " . $field->getDisplaySuffixText();
										} ?>
									</td>
								<?php
								}
								?>
							</tr>
						<?php
						}
						?>
					<?php
					}
					?>

					<tr>
						<?php
						foreach ($this->items as $item)
						{
							?>
							<td>
								<a href="<?php echo JRoute::_('index.php?option=com_judirectory&task=listing.removeCompare&listing_id=' . $item->id, false); ?>"><?php
									echo JText::_('COM_JUDIRECTORY_REMOVE'); ?></a>
							</td>
						<?php
						}
						?>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php
}
else
{
?>
	<div class="alert alert-block">
		<?php
			echo JText::_('COM_JUDIRECTORY_NO_LISTING_TO_COMPARE');
		?>
	</div>
<?php
}
?>