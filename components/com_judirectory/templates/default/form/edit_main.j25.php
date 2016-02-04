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
<!--CALL FIELD CAT ID-->
<?php
if ($this->fieldCatid)
{
	?>
	<div class="form-group">
		<?php echo $this->fieldCatid->getLabel(); ?>
		<div class="col-sm-10">
			<?php
			echo $this->fieldCatid->getModPrefixText();
			echo $this->fieldCatid->getInput(isset($this->fieldsData[$this->fieldCatid->id]) ? $this->fieldsData[$this->fieldCatid->id] : null);
			echo $this->fieldCatid->getModSuffixText();
			echo $this->fieldCatid->getCountryFlag();
			// parse field using joomla field
			?>
		</div>
	</div>
<?php
}
else
{
	$fieldCatid = $this->form->getField("cat_id");
	?>
	<div class="form-group">
		<?php echo $fieldCatid->label; ?>
		<div class="col-sm-10"><?php echo $fieldCatid->input; ?></div>
	</div>
<?php
}
?>
<!--END CALL FIELD CAT ID-->

<!--CALL MORE FIELD-->
<?php
if ($this->fieldsetDetails)
{
	foreach ($this->fieldsetDetails AS $field)
	{
		// Load field from DB
		if (is_object($field))
		{
			if ($field->field_name == 'description')
			{
				?>
				<div class="form-group">
					<?php echo $field->getLabel(); ?>
					<div class="col-sm-10">
						<?php
						echo $field->getModPrefixText();
						echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
						echo $field->getModSuffixText();
						echo $field->getCountryFlag();
						?>
					</div>
				</div>
			<?php
			}
			else
			{
				?>
				<div class="form-group">
					<?php echo $field->getLabel(); ?>
					<div class="col-sm-10">
						<?php
						echo $field->getModPrefixText();
						echo $field->getInput(isset($this->fieldsData[$field->id]) ? $this->fieldsData[$field->id] : null);
						echo $field->getModSuffixText();
						echo $field->getCountryFlag();
						?>
					</div>
				</div>
			<?php
			}
			?>
		<?php
		}
		// Load field as XML joomla field
		else
		{
			$field = $this->form->getField($field);
			?>
			<div class="form-group">
				<?php echo $field->label; ?>
				<div class="col-sm-10"><?php echo $field->input; ?></div>
			</div>
		<?php
		}
	}
}
?>
<!--END CALL MORE FIELD-->